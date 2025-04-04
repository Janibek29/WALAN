#!/usr/bin/php
<?php
// Подключение к базе данных MySQL
$host = 'localhost';
$dbname = 'your_database';
$user = 'your_user';
$pass = 'your_password';

try {
    // Подключение к базе данных
    //$pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    //$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Чтение письма из STDIN
    $sender = "";
    $recipient = "";
    $message = "";

    // Переменная для флага, указывающего, что тело письма начинается
    $bodyStarted = false;

    // Чтение заголовков и содержимого
    while ($line = fgets(STDIN)) {
        // Получение отправителя
        if (preg_match('/^From: (.*)/', $line, $matches)) {
            $sender = trim($matches[1]);
        }
        
        // Получение получателя
        if (preg_match('/^To: (.*)/', $line, $matches)) {
            $recipient = trim($matches[1]);
        }
        
        // Чтение тела сообщения
        if ($line == "\r\n" || $line == "\n") {
            $bodyStarted = true;  // Письмо начинается с тела после пустой строки
        }

        if ($bodyStarted) {
            $message .= $line;  // Собираем тело сообщения
        }
    }
/*
    // Если получены данные, записываем их в базу данных
    if ($sender && $recipient) {
        $stmt = $pdo->prepare("INSERT INTO messages (sender, recipient, message, received_at) VALUES (:sender, :recipient, :message, NOW())");
        $stmt->bindParam(':sender', $sender);
        $stmt->bindParam(':recipient', $recipient);
        $stmt->bindParam(':message', $message);
        $stmt->execute();
    }*/

    // После того как письмо обработано, выводим его обратно на стандартный вывод
    // Это нужно для того, чтобы передать его обратно в Postfix

    // Сначала передаем заголовки
    fwrite(STDOUT, "From: $sender\r\n");
    fwrite(STDOUT, "To: $recipient\r\n");
    fwrite(STDOUT, "Subject: Re: Received message\r\n");  // Можете добавить свой заголовок
    fwrite(STDOUT, "X-Processed-By: PHP filter\r\n");  // Примерный дополнительный заголовок
    fwrite(STDOUT, "\r\n");  // Пустая строка для разделения заголовков и тела

    // Затем передаем тело сообщения
    fwrite(STDOUT, $message);

    // Завершаем обработку, отправляем успех
    echo "250 OK\n";

} catch (PDOException $e) {
    // Если произошла ошибка при работе с базой данных, выводим ошибку
    echo "450 Error: " . $e->getMessage() . "\n";
    exit(1);
}
?>
