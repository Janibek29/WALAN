#!/usr/bin/php
<?php
// Чтение заголовков и содержимого письма из STDIN
$sender = "";
$recipient = "";

// Разбор заголовков (например, Sender и To)
while ($line = fgets(STDIN)) {
    // Получаем отправителя
    if (preg_match('/^From: (.*)/', $line, $matches)) {
        $sender = trim($matches[1]);
    }
    
    // Получаем получателя
    if (preg_match('/^To: (.*)/', $line, $matches)) {
        $recipient = trim($matches[1]);
    }

    // Если получены оба заголовка, можно завершить чтение
    if ($sender && $recipient) {
        break;
    }
}

// Параметры подключения к базе данных
$host = 'localhost';
$dbname = 'your_database';
$user = 'your_user';
$pass = 'your_password';

try {
    /*// Подключение к базе данных
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Запись в базу данных
    $stmt = $pdo->prepare("INSERT INTO messages (sender, recipient, received_at) VALUES (:sender, :recipient, NOW())");
    $stmt->bindParam(':sender', $sender);
    $stmt->bindParam(':recipient', $recipient);
    $stmt->execute();
    
    // Отправляем сообщение обратно в Postfix
    while ($line = fgets(STDIN)) {
        echo $line;
    }
*/
    echo "250 OK\n";  // Успешный ответ для Postfix
} catch (PDOException $e) {
    echo "450 Error: " . $e->getMessage() . "\n";  // Ошибка
    exit(1);
}
?>
