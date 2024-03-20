<?php 

// Database configuration 
$dbHost     = "localhost"; 
$dbUsername = "root"; 
$dbPassword = ""; 
$dbName     = "users"; 
 
// Create database connection 
$db = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName); 

// Check connection 
if ($db->connect_error) { 
    die("Connection failed: " . $db->connect_error); 
} 
 
$jsonStr = file_get_contents('php://input');
$jsonObj = json_decode($jsonStr);
 
if(!empty($jsonObj->request_type) && $jsonObj->request_type == 'user_auth'){ 
    $credential = !empty($jsonObj->credential) ? $jsonObj->credential : ''; 
 
    list($header, $payload, $signature) = explode (".", $credential); 
    $responsePayload = json_decode(base64_decode($payload)); 
 
    if(!empty($responsePayload)){ 
        $mail       = !empty($responsePayload->email) ? $responsePayload->email : ''; 
        $google_id  = !empty($responsePayload->sub) ? $responsePayload->sub : ''; 
        $first_name = !empty($responsePayload->given_name) ? $responsePayload->given_name : ''; 
        $last_name  = !empty($responsePayload->family_name) ? $responsePayload->family_name : '';

        // Update the query to use the correct table and column names
        $query = "SELECT * FROM login_google WHERE google_id = ?"; 
        $stmt = $db->prepare($query);
        $stmt->bind_param("s", $google_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result->num_rows > 0){  
            $query = "UPDATE login_google SET first_name = ?, last_name = ?, mail = ? WHERE google_id = ?"; 
            $stmt = $db->prepare($query);
            $stmt->bind_param("ssss", $first_name, $last_name, $mail, $google_id);
        }else{
            $query = "INSERT INTO login_google (mail, google_id, first_name, last_name) VALUES (?, ?, ?, ?)"; 
            $stmt = $db->prepare($query);
            $stmt->bind_param("ssss", $mail, $google_id, $first_name, $last_name);
        } 
         
        $stmt->execute();
         
        $output = [ 
            'status' => 1, 
            'msg' => 'Account data processed successfully!', 
            'pdata' => $responsePayload 
        ]; 
        echo json_encode($output); 
    }else{ 
        echo json_encode(['error' => 'Account data is not available!']); 
    } 
} 
?>
