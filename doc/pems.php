<?php
// Чтение сообщения из стандартного ввода (входящие данные)
$stdin = fopen('php://stdin', 'r');
if (!$stdin) {
    die("Ошибка при открытии стандартного ввода.\n");
}

$email_data = '';
$read = [$stdin]; // Потоки для чтения
$write = null;
$except = null;
$tv_sec = 0; // Таймаут (0 означает немедленную проверку)

if (stream_select($read, $write, $except, $tv_sec)) {
    // Данные есть в потоке
    //$input = fgets($stdin); // Читаем данные из stdin
    //echo "Вы ввели: " . $input;
	while ($line = fgets($stdin)) {
		$email_data .= $line;
	}
} else {
    // Нет данных для чтения
    //echo "Нет данных для чтения.\n";
}

fclose($stdin);

$logfn = '/var/www/html/pems.log';
$msgfn = '/var/www/html/pems.msg';
file_put_contents($msgfn, $email_data);

// Можно добавить обработку почты, например, извлечь адрес отправителя
/*preg_match('/^From: (.*)$/m', $email_data, $matches);
if (isset($matches[1])) {
    $from = $matches[1];
    file_put_contents($logfn, "Отправитель: $from\n", FILE_APPEND);
}*/
echo $email_data;  // Возвращаем обработанное письмо обратно в Postfix

exit(0);
?>