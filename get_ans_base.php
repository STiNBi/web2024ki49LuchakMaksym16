<?php
// Параметри для з’єднання
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "users";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


// SQL-запит --- feedback
$sql = "SELECT * FROM useranswer";

$result = $conn->query($sql);

$answers = array();

while ($row = $result->fetch_assoc()) {
    $answer = array(
        "name" => $row['Name'],
        "age" => $row['Age'],
        "response" => $row['Answer']
    );
    $answers[] = $answer;
}
echo json_encode($answers);
$conn->close();


?>
