<?php
// Підключення до бази даних (замініть значеннями ваші дані)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "users";

$conn = new mysqli($servername, $username, $password, $dbname);

// Перевірка з'єднання
if ($conn->connect_error) {
    die("Помилка з'єднання з базою даних: " . $conn->connect_error);
}

// Отримання даних з POST-запиту
$firstName = $_POST['firstName'];
$lastName = $_POST['lastName'];
$username = $_POST['username'];
$password = $_POST['password'];

// Перевірка, чи існує користувач з таким же ім'ям користувача
$sql_check_username = "SELECT * FROM login_default WHERE username='$username'";
$result_check_username = $conn->query($sql_check_username);

if ($result_check_username->num_rows > 0) {
    // Якщо користувач з таким ім'ям користувача вже існує, виведіть повідомлення про помилку
    echo "Користувач з таким ім'ям користувача вже існує!";
} else {
    // Якщо користувач з таким ім'ям користувача не існує, виконайте реєстрацію
    // Хешування паролю (додаткова безпека)
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    // Підготовка та виконання SQL-запиту для вставки нового користувача
    $sql_insert_user = "INSERT INTO login_default (password, first_name	, last_name	, username) VALUES ('$passwordHash', '$firstName', '$lastName', '$username')";

    if ($conn->query($sql_insert_user) === TRUE) {
        echo "Ви успішно зареєстровані!";
    } else {
        echo "Помилка під час реєстрації: " . $conn->error;
    }
}

// Закриття з'єднання з базою даних
$conn->close();
?>
