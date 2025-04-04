<?php
// Функция для записи в базу данных
function insert_into_db($subject, $sender, $receiver) {
    // Параметры подключения к базе данных
    $servername = "localhost";
    $username = "root";
    $password = "password";
    $dbname = "email_db";

    // Создаем подключение
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Проверка соединения
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Подготовка и выполнение SQL-запроса
    $stmt = $conn->prepare("INSERT INTO email_log (subject, sender, receiver) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $subject, $sender, $receiver);

    $stmt->execute();

    // Закрытие соединения
    $stmt->close();
    $conn->close();
}

// Чтение входящего сообщения из stdin
$raw_email = file_get_contents("php://stdin");

// Разбор заголовков письма (можно использовать PHP функцию)
$headers = [];
preg_match_all("/^([^:]+): (.*)$/m", $raw_email, $matches, PREG_SET_ORDER);

foreach ($matches as $match) {
    $headers[strtolower($match[1])] = $match[2];
}

// Извлечение информации
$subject = isset($headers['subject']) ? $headers['subject'] : 'No Subject';
$sender = isset($headers['from']) ? $headers['from'] : 'Unknown';
$receiver = isset($headers['to']) ? $headers['to'] : 'Unknown';

file_put_contents('test.log', 'Hello');
// Запись в базу данных
//insert_into_db($subject, $sender, $receiver);
?>