<?php
    // Путь к Unix-сокету
    $socketPath = '/var/www/html/smtpd_milters.sock';
	if(file_exists($socketPath))
		unlink($socketPath);
    // Создание Unix-сокета
    $server = socket_create(AF_UNIX, SOCK_STREAM, 0);
    if ($server === false) {
        die("Unable to create socket: " . socket_strerror(socket_last_error()));
    }

    // Привязка сокета к файлу
    if (socket_bind($server, $socketPath) === false) {
        die("Unable to bind socket: " . socket_strerror(socket_last_error()));
    }

    // Ожидание подключения
    if (socket_listen($server, 5) === false) {
        die("Unable to listen on socket: " . socket_strerror(socket_last_error()));
    }

    echo "PHP filter is listening on $socketPath...\n";

    // Обработка входящих соединений
    while (($client = socket_accept($server)) !== false) {
        
		$data = ''; // Переменная для данных
		$buffer = '';
		$length = 1024; // Максимальная длина блока данных для получения
		$bytesReceived = 0;
		// Получаем данные через сокет
		while (($bR = socket_recv($client, $buffer, $length, 0)) > 0) {
			$data .= $buffer; // Собираем полученные данные
			$bytesReceived += $bR;
		}
		
		/*if ($bytesReceived === false || $bytesReceived === 0) {
			echo "Ошибка при получении данных: " . socket_strerror(socket_last_error()) . "\n";
			socket_close($client);
			continue;
		}*/
		
		// Если получены данные, записываем в лог-файл
		file_put_contents('smtpd_milters.log', bin2hex($data).$bytesReceived, FILE_APPEND); // Запись в файл
		
        // Отправляем ответ в Postfix
        socket_write($client, 'bytesReceived='.$bytesReceived."OK\n");
		
		// Ожидаем, что данные были отправлены
		flush();
		
        // Закрытие соединения
        socket_close($client);
    }

    // Закрытие сервера
    socket_close($server);

    // Закрытие соединения с базой данных
    //$mysqli->close();
	
/*
sudo vim /etc/systemd/system/smtpd_milters.service
    Вставьте следующую конфигурацию:

[Unit]
Description=PHP Mail Filter
After=network.target

[Service]
ExecStart=/usr/bin/php /var/www/html/smtpd_milters.php
WorkingDirectory=/var/www/html
User=vmail
Group=vmail
Restart=always
StandardOutput=syslog
StandardError=syslog

[Install]
WantedBy=multi-user.target

    Перезагрузите systemd и активируйте сервис:

sudo systemctl daemon-reload
sudo systemctl enable smtpd_milters.service
sudo systemctl start smtpd_milters.service
sudo systemctl status smtpd_milters.service


vim /etc/postfix/main.cf
smtpd_milters = unix:/var/www/html/smtpd_milters.sock
smtpd_milters = inet:localhost:8891, unix:/var/www/html/smtpd_milters.sock


netstat -tuln | grep 8891

Для Unix-сокета:

lsof | grep smtpd_milters.sock


nc -U /var/www/html/smtpd_milters.sock
echo -n "TEST MESSAGE" | nc -U /var/www/html/smtpd_milters.sock
*/
?>