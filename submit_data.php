<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "users";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$name = $_POST['name'];
$age = (int) $_POST['age'];
$response = $_POST['response'];

// Підготовка SQL
$sql = "INSERT INTO useranswer (Name, Age, Answer) VALUES (?, ?, ?)";

// Підготовка заяви
if ($stmt = $conn->prepare($sql)) {

    $stmt->bind_param("sis", $name, $age, $response);
    if ($stmt->execute()) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();

} else {

    echo "Error preparing statement: " . $conn->error;
}

$conn->close();


?>
