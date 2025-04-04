<?php
// Адрес и путь к сокету LMTP
$lmtp_socket_path = '/var/spool/postfix/private/dovecot-lmtp'; 

// Получаем данные почты (например, из stdin, если передается через фильтр Postfix)
$email = '';
while ($line = fgets(STDIN)) {
    $email .= $line;
}

// Подключаемся к сокету LMTP
$socket = fsockopen('unix://' . $lmtp_socket_path, -1);

if (!$socket) {
    die("Не удалось подключиться к сокету LMTP.\n");
}

// Получаем приветствие от сервера LMTP
$response = fgets($socket, 1024);
if (strpos($response, '220') !== 0) {
    die("Ошибка соединения с сервером LMTP: $response");
}
/*
// Отправляем команду `LH` (send mail), которая сообщает серверу LMTP, что письмо будет отправлено
$command = "LH\r\n";
fwrite($socket, $command);
$response = fgets($socket, 1024);
if (strpos($response, '250') !== 0) {
    die("Ошибка команды LH: $response");
}

// Отправляем адрес получателя (например, для user@domain.com)
$recipient = "7admin@tirlik.kz"; // Убедитесь, что это правильный адрес
$command = "RCPT TO:<$recipient>\r\n";
fwrite($socket, $command);
$response = fgets($socket, 1024);
if (strpos($response, '250') !== 0) {
    die("Ошибка команды RCPT TO: $response");
}

// Отправляем команду DATA, чтобы указать, что будет отправлено сообщение
$command = "DATA\r\n";
fwrite($socket, $command);
$response = fgets($socket, 1024);
if (strpos($response, '354') !== 0) {
    die("Ошибка команды DATA: $response");
}*/

// Отправляем тело письма
fwrite($socket, $email . "\r\n.\r\n");

// Получаем ответ от сервера
$response = fgets($socket, 1024);
if (strpos($response, '250') !== 0) {
    die("Ошибка отправки письма: $response");
}

// Закрываем соединение
fwrite($socket, "QUIT\r\n");
fclose($socket);

echo "Письмо успешно отправлено через LMTP.\n";
?>
