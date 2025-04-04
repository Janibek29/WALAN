<?php
/*
	$mysqli = new mysqli("localhost", "username", "password", "database_name");
    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }*/

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
    while (true) {		
        // Принятие соединения от Postfix
        $client = socket_accept($server);
        if ($client === false) {
			//echo "client=false";
			//echo "Unable to accept connection: " . socket_strerror(socket_last_error()) . "\n";
            continue;
        }

        // Чтение данных из сокета
        /*$data = socket_read($client, 1024);
        if ($data === false) {
            socket_close($client);
            continue;
        }*/
		
		// Чтение данных
		/*$data = '';
		while (($buf = socket_read($client, 1024)) !== false) {
			$data .= $buf;
		}*/
		
		/*
		$data = '';
		while ($buf = socket_read($client, 1024)) {
			if ($buf === false) {
				socket_close($client);
				continue;
			}
			$data .= $buf;
		}*/
		
		/*$data = '';
		while (($buf = socket_read($client, 1024)) !== false) {
			if (empty($buf)) {
				socket_write($client, "OK\n");
				socket_close($client);
				continue;
			}
			$data .= $buf;
		}*/
        // Разбираем данные (например, это может быть raw email message)
        //$email_message = $data;
		
		
		
		// Инициализация переменной для хранения данных
		$data = '';
		$buffer = '';
		$length = 1024; // Максимальная длина блока данных для получения

		// Используем socket_recv() для получения данных
		/*
		while (($bytesReceived = socket_recv($client, $buffer, $length, 0)) > 0) {
			$data .= $buffer; // Добавляем полученные данные в переменную $data

			// Вы можете добавить логику для выхода из цикла, если достигнут конец сообщения
			// Например, если сообщение завершено, вы можете проверить данные на наличие конца строки или спецсимволов
			if (strpos($data, "\r\n\r\n") !== false) { // Это пример, для обработки завершения заголовков и тела email
				break;
			}
		}

		if ($bytesReceived === false) {
			echo "Ошибка при получении данных: " . socket_strerror(socket_last_error()) . "\n";
			socket_close($client);
			continue;
		}
		*/
		
		$data = ''; // Переменная для данных
		$buffer = '';
		$length = 1024; // Максимальная длина блока данных для получения

		// Получаем данные через сокет
		while (($bytesReceived = socket_recv($client, $buffer, $length, 0)) > 0) {
			$data .= $buffer; // Собираем полученные данные
		}
		
		file_put_contents('smtpd_milters.log', $data);
		
/*
        // Пример: получение заголовков и тела письма из raw-сообщения
        $headers = null;
        $body = null;

        // Разделение заголовков и тела письма
        $parts = preg_split("/\r?\n\r?\n/", $email_message, 2);
        if (count($parts) > 1) {
            $headers = $parts[0]; // Заголовки
            $body = $parts[1]; // Тело письма
        }

        // Теперь, например, можно извлечь нужные данные из заголовков
        // Пример: извлечение отправителя, получателя и темы
        $email_sender = null;
        $email_recipient = null;
        $email_subject = null;

        // Регулярные выражения для извлечения информации из заголовков
        if (preg_match("/^From: (.*)$/m", $headers, $matches)) {
            $email_sender = trim($matches[1]);
        }
        if (preg_match("/^To: (.*)$/m", $headers, $matches)) {
            $email_recipient = trim($matches[1]);
        }
        if (preg_match("/^Subject: (.*)$/m", $headers, $matches)) {
            $email_subject = trim($matches[1]);
        }

        // Запись данных в базу данных
        $sql = "INSERT INTO email_logs (email_subject, email_sender, email_recipient, received_at) 
                VALUES (?, ?, ?, NOW())";

        if ($stmt = $mysqli->prepare($sql)) {
            $stmt->bind_param("sss", $email_subject, $email_sender, $email_recipient);
            $stmt->execute();
            $stmt->close();
        }
*/
        // Отправляем ответ в Postfix
        socket_write($client, "OK\n");

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