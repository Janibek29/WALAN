<?php
	/*
	* Copyright (C) 2025 Жанибек Какимбаев
	*
	* Бұл бағдарлама GNU General Public License v3.0 (GPLv3) лицензиясы бойынша таратылады.
	* Лицензия мәтіні: https://www.gnu.org/licenses/gpl-3.0.html
	*/
	namespace mvc\cs;
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\Exception;

	require MD.'PHPMailer/src/Exception.php';
	require MD.'PHPMailer/src/PHPMailer.php';
	require MD.'PHPMailer/src/SMTP.php';
	class cEMS extends jC {
		var $trv;
		var $imapStream;
		var $uid;
		var $hostname;
		var $f;
		
		function init() {
			$r = '';
			$this->trv = 1;
			//echo 'fp='.$this->fp;
			if($this->fp==2 && isset($this->oM->sd['un']) && isset($_GET['f'])) {
				$this->f = $_GET['f'];
				/*if(!$this->isRS($this->f)) {
					$rs = $this->getRS($this->f);
					if(isset($this->fd['its'][$this->trv]['rs'])) {
						$rs = $this->fd['its'][$this->trv]['rs'] + $rs;
						//$rs = array_merge($this->fd['its'][$this->trv]['rs'], $rs);
					}
					$this->fd['its'][$this->trv]['rs'] = $rs;
				}*/
				$this->fd['its'][$this->trv]['rs'] = $this->getMRS($this->f);
			} elseif($this->fp==5) {
				if(!isset($this->fd['its'][$this->trv]['rs']))
					$this->setSENT();
			} elseif($this->fp==4) {
				if(!isset($this->fd['rw'])) {
					$this->fd['rw'] = [];
				}
			}
			if(!isset($this->fd['muid'])) {
				$this->fd['muid'] = 0;
			}
			return $r;
		}
		
		function rq() {
			$r = '';
			$ssd = $this->oM->ssd;
			if($this->fp==3 && isset($_GET['id'])) {
				$id = $_GET['id'];
				//if($this->fd['muid']!=$id) {
					$this->setMSG($id);
				//}
			}
			return $r;
		}
		
		function getMRS($f) {
			$r = [];
			$u = '<'.$this->oM->sd['un'].'@'.'tirlik.kz>';
			switch($f) {
				case 'INBOX':
					$w = "rcpt='$u'";
				break;
				case 'Sent':
					$w = "sndr='$u'";
				break;
			}
			//$q = "select id, REPLACE(REPLACE(sndr, '<', ''), '>', '') as sndr, rcpt, sbj, dt from ems where $w";
			//$q = "select id, mid, sndr, rcpt, sbj, DATE_FORMAT(dt, '%Y-%m-%d %H:%i:%s') AS dt from ems where $w order by id desc";
			//SELECT id, mid, sndr, rcpt, sbj, 
			//DATE_FORMAT(STR_TO_DATE(dt, '%Y-%m-%d %H:%i:%s'), '%Y-%m-%d %H:%i:%s') AS dt
			$q = "select id, uid, mid, sndr, rcpt, sbj, STR_TO_DATE(SUBSTRING_INDEX(dt, ',', -1), '%d %b %Y %H:%i:%s') AS dt from ems where $w order by id desc";
			$rs = $this->oM->sSCF($q); //print_r($rs);
			foreach($rs as $rk=>$rw) {
				$rd = [];
				$rd['f'] = $f;
				$rd['d'] = $rw;
				
				
				// Пример строки, содержащей несколько частей MIME в формате Base64
				$encodedString = $rd['d']['SBJ'];

				// Регулярное выражение для поиска всех частей, закодированных в Base64
				preg_match_all('/=\?UTF-8\?B\?([A-Za-z0-9+\/=]+)\?=/', $encodedString, $matches);

				// Декодируем все найденные части
				$decodedString = '';
				foreach ($matches[1] as $encodedPart) {
					// Декодируем каждую часть из Base64
					$decodedString .= base64_decode($encodedPart);
				}

				// Выводим результат
				$rd['d']['SBJ'] = $decodedString;

				$r[$rd['d']['ID']] = $rd;
			}
			return $r;
		}
		
		function rrs() {
			$r = '';
			//$r .= print_r($_POST, true);
			//$r .= urldecode(base64_decode($_POST['d']));
			//$r .= urldecode(base64_decode($_POST['d']));
			//$r .= 'rrs';
			
			unset($this->fd['its'][$this->trv]['rs']);
			//$r .= print_r($this->fd['its'][$this->trv]['rs'], true);
			return $r;
		}
		
		function sendMSG() {
			$r = '';
			//$r .= print_r($this->fd['rw']);
			$toem = $this->fd['rw']['d']['TO'];
			$sbj = $this->fd['rw']['d']['SBJ'];
			//$msg = $this->fd['rw']['d']['MSG'];
			
			$d = urldecode($_POST['d']);			
			$d = base64_encode($d);
			$msg = $d;
			$dc = $this->oM->ivSCF('sds');
			$qs = [];
			$q = "insert into sds (id, d, uid) values ($dc, '$d', ".$this->oM->sd['uid'].")";
			$qs[] = $q;
			$this->oM->qscSCF($qs);
			
			// Настройка для сохранения письма на сервере
			$this->imapOpen('Sent');

			$mail = new PHPMailer(true);

			try {
				// Настройки SMTP
				$mail->SMTPDebug = 0;
				$mail->Debugoutput = 'html';
				$mail->isSMTP();  
				//$mail->isMail();				
				$mail->Host       = 'tirlik.kz';                      // Установите свой SMTP-сервер
				//$mail->Host       = 'localhost';
				$mail->SMTPAuth   = true;
				//$mail->SMTPAuth = false;                               // Без аутентификации, если используется Postfix
				$mail->SMTPSecure = 'tls';
				$mail->Username   = $this->oM->sd['un'].'@'.'tirlik.kz';                // Ваш email
				$mail->Password   = $this->oM->ja['pw'][$this->oM->sd['un']];                         // Ваш пароль
				//echo $mail->Username.' '.$mail->Password;
				$mail->CharSet = "UTF-8";
				//$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
				//$mail->Port = 465;
				//$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;  // Использование STARTTLS
				$mail->Port = 587;  // Порт для STARTTLS

				// Отправитель и получатели
				$mail->setFrom($this->oM->sd['un'].'@'.'tirlik.kz', $this->oM->sd['tt']);
				$mail->addAddress($toem, 'User 1');   // Получатель через + (например, user1)
				// Можно добавить других пользователей
				//$mail->addAddress('username+user2@example.com', 'User 2');   // Получатель через + (например, user2)

				// Контент письма
				$mail->isHTML(true);                                          // Устанавливаем формат письма в HTML
				$mail->Subject = $sbj;
				$mail->Body    = base64_decode($msg);
				// Добавляем код для сохранения отправленного письма в "Отправленные" через IMAP:
				$mail->addCustomHeader('X-Save-To-Sent', 'true'); // Пример заголовка для сохранения на сервере
				$mail->addCustomHeader('X-Custom-ID', $dc);//id документа
				if ($mail->send()) {
					echo 'Message has been sent';
					imap_append($this->imapStream, $this->hostname.'Sent', $mail->getSentMIMEMessage());
					imap_close($this->imapStream);
				} else {
					echo $mail->ErrorInfo;
				}
				//$mail->send();
				//echo 'Message has been sent';
			} catch (Exception $e) {
				echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
			}
			
			return $r;
		}
		
		function getUID($id, $uid, $mid) {
			$r = 0;
			if($uid>0) {
				$r = $uid;
			} else {
				// Получаем количество сообщений
				$numMessages = imap_num_msg($this->imapStream);
				
				// Перебираем все сообщения и проверяем заголовки
				for ($msgNum = 1; $msgNum <= $numMessages; $msgNum++) {
					$headers = imap_fetchheader($this->imapStream, $msgNum);

					// Проверяем наличие нужного Message-ID в заголовках
					if (strpos($headers, 'Message-ID: ' . $mid) !== false) {
						$uid = imap_uid($this->imapStream, $msgNum);
						$qs = [];
						$q = "update ems set uid=$uid where id=$id";
						//echo $q;
						$qs[] = $q;
						$this->oM->qscSCF($qs);
						$r = $uid;
					}
				}
			}
			return $r;
		}
		
		// Функция для извлечения тела письма, учитывая кодировку
		function get_body($mailbox, $emailNumber, $structure) {
			$body = '';
			
			if (isset($structure->parts) && count($structure->parts) > 0) {
				// Обрабатываем вложенные части
				foreach ($structure->parts as $partNumber => $part) {
					$bodyPart = imap_fetchbody($mailbox, $emailNumber, $partNumber + 1);
					$encoding = $part->encoding;
					
					//$headers = imap_fetchheader($mailbox, $emailNumber, $partNumber + 1);
					/*$headers = imap_fetchheader($mailbox, $emailNumber, $partNumber + 1);
					// Ищем строку Content-Transfer-Encoding
					preg_match('/Content-Transfer-Encoding:\s*(\S+)/i', $headers, $matches);

					// Проверяем, что кодировка найдена
					if (isset($matches[1])) {
						$encoding = $matches[1];
						echo $encoding;
					}*/
					//echo 'encoding='.$encoding;
					// Декодируем тело в зависимости от кодировки
					switch ($encoding) {
						case 0: // 7bit
						case 1: // 8bit
							$body .= $bodyPart;
							break;
						case 3: // base64
							$body .= base64_decode($bodyPart);
							break;
						case 2: // quoted-printable
							$body .= quoted_printable_decode($bodyPart);
							break;
						case 4: // binary
							$body .= $bodyPart;
							break;
						default:
							$body .= $bodyPart;
							break;
					}
				}
			} else {
				// Если письмо состоит из одной части
				$bodyPart = imap_fetchbody($mailbox, $emailNumber, 1);
				$encoding = $structure->encoding;
				//echo 'encoding='.$encoding;
				// Декодируем тело в зависимости от кодировки
				switch ($encoding) {
					case 0: // 7bit
					case 1: // 8bit
						$body = $bodyPart;
						break;
					case 3: // base64
						$body = base64_decode($bodyPart);
						break;
					case 2: // quoted-printable
						$body = quoted_printable_decode($bodyPart);
						break;
					case 4: // binary
						$body = $bodyPart;
						break;
					default:
						$body = $bodyPart;
						break;
				}
			}
			/*$body = preg_replace('/\s+/', '', $body);
			//if (preg_match('/^[A-Za-z0-9+\/=]+\s*$/', $body)) {
			if (preg_match('/^[A-Za-z0-9+\/=]+$/', $body)) {
				// Декодируем содержимое из base64
				$body = base64_decode($body);
				
				// Выводим декодированное содержимое
				$body = nl2br(htmlspecialchars($body));
			}*/
			//$body = nl2br(htmlspecialchars($body));
			//$body = htmlspecialchars($body);
			$body = nl2br($body);
			return $body;
		}
		
		function setMSG($id) {
			//$this->fd['muid'] = $id;
			$this->fd['rw'] = $this->sd['fms'][2]['its'][$this->trv]['rs'][$id];
			$this->imapOpen($this->fd['rw']['f']);
			$uid = $this->getUID($id, $this->fd['rw']['d']['UID'], $this->fd['rw']['d']['MID']);
			$this->fd['rw']['d']['UID'] = $uid;
			$this->sd['fms'][2]['its'][$this->trv]['rs'][$id] = $this->fd['rw'];
			if ($uid>0) {
				$emailNumber = imap_msgno($this->imapStream, $uid);
				$structure = imap_fetchstructure($this->imapStream, $emailNumber);
				$this->fd['msg'] = $this->get_body($this->imapStream, $emailNumber, $structure);
			}
		}
		
		function setMSG1($id) {
			$r = '';
			//error_reporting(0);
			$this->fd['muid'] = $id;
			$this->fd['rw'] = $this->sd['fms'][2]['its'][$this->trv]['rs'][$id];
			
			// Ищем письмо по Message-ID
			//$mid = 'HEADER Message-ID "' . $this->fd['rw']['d']['MID'] . '"';
			
			//$searchResult = imap_search($this->imapStream, $mid);
			$this->imapOpen($this->fd['rw']['f']);
			
			$uid = $this->getUID($id, $this->fd['rw']['d']['UID'], $this->fd['rw']['d']['MID']);
			$this->fd['rw']['d']['UID'] = $uid;
			$this->sd['fms'][2]['its'][$this->trv]['rs'][$id] = $this->fd['rw'];
			
			/*
			$mid = 'HEADER Message-ID "' . $this->fd['rw']['d']['MID'] . '"';
			
			echo htmlspecialchars($mid);
			$searchResult = imap_search($this->imapStream, $mid, SE_UID);
			print_r($searchResult);*/
			if ($uid>0) {
				// Получаем номер письма из поиска
				$email_number = imap_msgno($this->imapStream, $uid);
				$header = imap_fetchheader($this->imapStream, $email_number);
				$structure = imap_fetchstructure($this->imapStream, $email_number);
				echo 'encoding='.$structure->encoding;
				/*Поле encoding структуры указывает на тип кодировки тела письма:

    0: 8bit
    1: 7bit
    2: base64
    3: quoted-printable
    4: binary
    5: MIME (по умолчанию)*/
				$message = '';
				if ($structure->type == 1) { // multipart
					foreach ($structure->parts as $part_number => $part) {
						echo '<br/>'.$part->encoding;
						
						if ($part->ifdparameters) {
							// Проверяем, есть ли вложение
							foreach ($part->dparameters as $object) {
								if (strtolower($object->attribute) == "filename") {
									// Получаем имя вложения
									$attachment_name = $object->value;
									$attachment_data = imap_fetchbody($mailbox, $email_number, $part_number + 1);

									// Декодируем вложение (если оно закодировано в base64 или quoted-printable)
									if ($part->encoding == 4) {
										$attachment_data = base64_decode($attachment_data);
									} elseif ($part->encoding == 3) {
										$attachment_data = quoted_printable_decode($attachment_data);
									}

									// Сохраняем вложение на сервер
									//file_put_contents("/path/to/save/$attachment_name", $attachment_data);
									echo "Вложение $attachment_name сохранено.\n";
								}
							}
						}
					}
					$message = imap_fetchbody($this->imapStream, $email_number, 2);
					$message = quoted_printable_decode($message);
				} elseif($structure->type == 0) {
					// Простое текстовое сообщение
					$message = imap_fetchbody($this->imapStream, $email_number, 1);
				} else {
					// Для более сложных сообщений (например, HTML или вложения)
					$message = imap_fetchbody($this->imapStream, $email_number, 2); // Пример для HTML, можно изменить индекс для разных частей
				}
				
				if(is_base64($message)) {
					$message = base64_decode($message);
				}
				
				//if ($structure->encoding == 3) { // Если кодировка quoted-printable
					$message = imap_qprint($message); // Декодируем содержимое
				//}
				
				$dc = 0;
				if (preg_match('/^X-Custom-ID:\s*(.+)$/mi', $header, $matches)) {
					$dc = trim($matches[1]);
				}
				
				if($dc>0) {
					$q = "select d from sds where id=$dc";
					$rs = $this->oM->sSCF($q);
					if(count($rs)==1) {
						$message = 'DC'.base64_decode($rs[1]['D']);
					}
				}
				
				$this->fd['msg'] = nl2br($message);
				
				//error_reporting(E_ALL);
			}
			
			imap_close($this->imapStream);
			return $r;
		}
		
		function isRS($f) {
			$r = false;
			if(isset($this->fd['its'][$this->trv]['rs'])) {
				$rs = $this->fd['its'][$this->trv]['rs'];
				foreach($rs as $rk=>$rw) {
					if($rw['f']==$f) {
						$r = true;
						break;
					}
				}
			}
			return $r;
		}
		
		function imapOpen($imapfolder) {
			$r = 0;
			$this->hostname = '{localhost:993/imap/ssl/novalidate-cert}';  // Укажите ваш сервер IMAP
			$username = $this->oM->sd['un'].'@'.'tirlik.kz';  // Ваш email
			$password = $this->oM->ja['pw'][$this->oM->sd['un']];  // Ваш пароль
			// Подключаемся к серверу IMAP
			$this->imapStream = imap_open($this->hostname.$imapfolder, $username, $password);
			//$imap_stream = imap_open($hostname, $username, $password, 0, 1, array('ssl' => array('cafile' => '/var/www/html/kys/ca.crt')));
			
			if (!$this->imapStream) {
				echo 'Ошибка при подключении: ' . imap_last_error();
				exit;
			}
			
			return $r;
		}
		
		function getUSN() {
			$r = 0;
			$hostname = '{tirlik.kz:993/imap/ssl/novalidate-cert}INBOX';  // Укажите ваш сервер IMAP
			$username = $this->oM->sd['un'].'@'.'tirlik.kz';  // Ваш email
			$password = $this->oM->sd['pw'];  // Ваш пароль
			// Подключаемся к серверу IMAP
			$inbox = imap_open($hostname, $username, $password);
			//$inbox = imap_open($hostname, $username, $password, 0, 1, array('ssl' => array('cafile' => '/var/www/html/kys/ca.crt')));
			if (!$inbox) {
				echo 'Ошибка при подключении: ' . imap_last_error();
				exit;
			}
			// Поиск непрочитанных сообщений (UNSEEN)
			$emailsUNSEEN = imap_search($inbox, 'UNSEEN');
			$r = count($emailsUNSEEN);			
			return $r;
		}
		
		function getRS($f) {
			$rs = [];
			$rk = 0;
			$this->imapOpen($f);
			
			switch($f) {
				case 'INBOX':
					$emails = imap_search($this->imapStream, 'ALL');
				break;
				case 'Sent':
					$emails = imap_search($this->imapStream, 'ALL', SE_UID, $f);
				break;
			}
			

			if ($emails) {
				rsort($emails); // Сортируем письма по убыванию
				foreach ($emails as $email_number) {
					
					$rk++;
					
					$overview = imap_fetch_overview($this->imapStream, $email_number, 0);
					//$message = imap_fetchbody($this->inbox, $email_number, 2);
					//
					$structure = imap_fetchstructure($this->imapStream, $email_number);
					
					$message = '';
					$structure = imap_fetchstructure($this->imapStream, $email_number);
					if ($structure->type == 0) {
						// Простое текстовое сообщение
						$message = imap_fetchbody($this->imapStream, $email_number, 1);
					} else {
						// Для более сложных сообщений (например, HTML или вложения)
						$message = imap_fetchbody($this->imapStream, $email_number, 2); // Пример для HTML, можно изменить индекс для разных частей
					}
					//$rw['d']['ID'] = $rk;
					/*
					ob_start(); 
print_r(\imap_headerinfo($inbox, $email_number));
print_r(\imap_fetch_overview($inbox, $email_number));
print_r(\imap_fetchheader($inbox, $email_number));
echo htmlentities(ob_get_clean());*/

					/*
					if (isset($overview[0]->message_id)) {
						$rw['d']['ID'] = $overview[0]->message_id;
					}
					
					//if($rw['d']['ID']=='') {
						$header = imap_fetchheader($inbox, $email_number);
			//Message-ID
						// Ищем Message-ID в заголовках
						preg_match('/Message-ID: (.*?)\r\n/', $header, $matches);
						
						if (!empty($matches[1])) {
							$rw['d']['ID'] = $matches[1];
						}
					//}*/
					$header = imap_fetchheader($this->imapStream, $email_number);
					if (preg_match('/Content-Transfer-Encoding: base64/i', $header)) {
						$message = base64_decode($message);
					}
					
					$headers = \imap_headerinfo($this->imapStream, $email_number);
					//print_r($headers);
					$rw['d']['MID'] = \htmlentities($headers->message_id);
					$uid = imap_uid($this->imapStream, $email_number);
					$rw['d']['ID'] = $uid;

/*
					// Получаем информацию о заголовках с помощью imap_headerinfo
					$header_info = imap_headerinfo($inbox, $email_number);

					// Проверяем наличие Message-ID
					if (!empty($header_info->message_id)) {
						$rw['d']['ID'] = $header_info->message_id;
					}*/
		
		
					// Проверяем, является ли сообщение непрочитанным
					$is_unseen = ($overview[0]->seen == 0);

					// Декодируем тему письма
					$subject = '';
					if(isset($overview[0]->subject))
						$subject = imap_utf8($overview[0]->subject);
					$from = imap_utf8($overview[0]->from);
					$to = imap_utf8($overview[0]->to);
					$date = \DateTime::createFromFormat('D, d M Y H:i:s O', $overview[0]->date);
					
					$sender_name = '';
					if (preg_match('/"([^"]+)"/', $from, $matches)) {
						$sender_name = $matches[1]; // Имя отправителя
					}
					// Извлекаем только email-адрес отправителя
					$sender_email = '';
					preg_match('/<(.+)>/', $from, $matches);
					$sender_email = isset($matches[1]) ? $matches[1] : $from; // Если email есть в угловых скобках, используем его
					
					$toem = '';
					preg_match('/<(.+)>/', $to, $matches);
					$toem = isset($matches[1]) ? $matches[1] : $to;
					
					
					$dc = 0;
					if (preg_match('/^X-Custom-ID:\s*(.+)$/mi', $header, $matches)) {
						$dc = trim($matches[1]);
					}

					$rw['f'] = $f;
					
					$rw['d']['DC'] = $dc;
					$rw['d']['USN'] = $is_unseen;
					$rw['d']['SBJ'] = $subject;
					$rw['d']['FROMNM'] = $from;
					$rw['d']['FROMEM'] = $sender_email;
					$rw['d']['TONM'] = $to;
					$rw['d']['TOEM'] = $toem;
					$dt = '';
					if ($date !== false) {
						$dt = $date->format('Y-m-d H:i:s');
					}
					$rw['d']['DT'] = $dt;
					//$message = trim($message);
					//if (base64_encode(base64_decode($message, true)) === $message) {
					//if(isBase64($message)) {
					if(is_base64($message)) {
						$message = base64_decode($message);
					}
					if ($structure->encoding == 3) { // Если кодировка quoted-printable
						$message = imap_qprint($message); // Декодируем содержимое
					}
					$rs[$uid] = $rw;
				}
			}
			// Закрываем соединение с сервером
			imap_close($this->imapStream);
			
			return $rs;
		}
		
		function setSENT() {
			$rs = [];
			$rk = 0;
			
			
			//imap_createmailbox($inbox, '{imap.example.com:993/imap/ssl}Sent');
			/*
			$folders = imap_list($this->inbox, $this->hostname, '*');

			// Проверка на успешное получение списка папок
			if ($folders) {
				echo "Доступные папки:<br>";
				foreach ($folders as $folder) {
					echo $folder . "<br>";
				}
			} else {
				echo "Не удалось получить список папок.";
			}
			// Открытие папки "Sent" (или "Sent Mail", в зависимости от настроек сервера)
			$sentFolder = 'Sent';  // Обычно "Sent", но может быть и "Sent Mail"
			//$sentFolder = 'INBOX.Sent';
			//$sentFolder = 'Sent Mail';
			$status = imap_status($this->inbox, $this->hostname . $sentFolder, SA_ALL);

			if (!$status) {
				echo "Не удалось получить статус папки 'Sent'. Проверьте правильность имени папки.".$sentFolder;
				exit;
			}*/
			$sentFolder = 'Sent';
			$this->imapOpen($sentFolder);			
			$emails = imap_search($this->inbox, 'ALL', SE_UID, $sentFolder);

			if ($emails) {
				rsort($emails); // Сортируем письма по убыванию
				print_r($emails);
				foreach ($emails as $email_number) {
					
					$rk++;
					
					$overview = imap_fetch_overview($this->inbox, $email_number, 0);
					
					// Попытка извлечь текст письма
					$message = '';
					$structure = imap_fetchstructure($this->inbox, $email_number);
					if ($structure->type == 0) {
						// Простое текстовое сообщение
						$message = imap_fetchbody($this->inbox, $email_number, 1);
					} else {
						// Для более сложных сообщений (например, HTML или вложения)
						$message = imap_fetchbody($this->inbox, $email_number, 2); // Пример для HTML, можно изменить индекс для разных частей
					}

					$header = imap_fetchheader($this->inbox, $email_number);
					if (preg_match('/Content-Transfer-Encoding: base64/i', $header)) {
						$message = base64_decode($message);
					}
					
					$headers = \imap_headerinfo($this->inbox, $email_number);
					$rw['d']['MID'] = \htmlentities($headers->message_id);
					$uid = imap_uid($this->inbox, $email_number);
					$rw['d']['ID'] = $uid;

/*
					// Получаем информацию о заголовках с помощью imap_headerinfo
					$header_info = imap_headerinfo($inbox, $email_number);

					// Проверяем наличие Message-ID
					if (!empty($header_info->message_id)) {
						$rw['d']['ID'] = $header_info->message_id;
					}*/
		
		
					// Проверяем, является ли сообщение непрочитанным
					$is_unseen = ($overview[0]->seen == 0);

					// Декодируем тему письма
					$subject = '';
					if(isset($overview[0]->subject))
						$subject = imap_utf8($overview[0]->subject);
					$from = imap_utf8($overview[0]->from);
					$date = \DateTime::createFromFormat('D, d M Y H:i:s O', $overview[0]->date);
					
					$sender_name = '';
					if (preg_match('/"([^"]+)"/', $from, $matches)) {
						$sender_name = $matches[1]; // Имя отправителя
					}
					// Извлекаем только email-адрес отправителя
					$sender_email = '';
					preg_match('/<(.+)>/', $from, $matches);
					$sender_email = isset($matches[1]) ? $matches[1] : $from; // Если email есть в угловых скобках, используем его
					
					
					$rw['d']['USN'] = $is_unseen;
					$rw['d']['SBJ'] = $subject;
					$rw['d']['FROMNM'] = $from;
					$rw['d']['FROMEM'] = $sender_email;
					$rw['d']['DT'] = $date->format('Y-m-d H:i:s');
					
					//$message = trim($message);
					//if (base64_encode(base64_decode($message, true)) === $message) {
					//if(isBase64($message)) {
					if(is_base64($message)) {
						$message = base64_decode($message);
					}
					$message = imap_qprint($message);
					$rw['d']['MSG'] = $message;
					$rw['d']['S'] = $structure;
					$rs[$uid] = $rw;
				}
			}

			// Закрываем соединение с сервером
			imap_close($this->inbox);
			
			$this->fd['its'][$this->trv]['rs'] = $rs;
		}
	}
?>
