<?php
	/*
	* Copyright (C) 2025 Жанибек Какимбаев
	*
	* Бұл бағдарлама GNU General Public License v3.0 (GPLv3) лицензиясы бойынша таратылады.
	* Лицензия мәтіні: https://www.gnu.org/licenses/gpl-3.0.html
	*/
	function is_base64($s) {
		  return (bool) preg_match('/^[a-zA-Z0-9\/\r\n+]*={0,2}$/', $s);
	}
	
	function is_base641($s){
		// Check if there are valid base64 characters
		if (!preg_match('/^[a-zA-Z0-9\/\r\n+]*={0,2}$/', $s)) return false;

		// Decode the string in strict mode and check the results
		$decoded = base64_decode($s, true);
		if(false === $decoded) return false;

		// Encode the string again
		if(base64_encode($decoded) != $s) return false;

		return true;
	}

	function isBase641($string) {
		return (bool) preg_match('/^[a-zA-Z0-9+\/=]+\s*$/', $string) && strlen($string) % 4 === 0;
	}
	
	function isBase64($string) {
		// Проверка на символы, которые допустимы в Base64
		return (bool) preg_match('/^[A-Za-z0-9+\/=]+$/', $string) && strlen($string) % 4 === 0;
	}

	// Рекурсивная функция для преобразования объекта в массив
	function objectToArray($object) {
		// Если это объект
		if (is_object($object)) {
			$object = get_object_vars($object);
		}

		// Если это массив
		if (is_array($object)) {
			return array_map('objectToArray', $object);
		} else {
			return $object;
		}
	}
	
	function sendmail($as, $at, $sb, $bdhtml) {
    
    //use PHPMailer\PHPMailer\Exception;

    

    $mail = new PHPMailer;
    
    $mail->SMTPDebug = 3;                               // Enable verbose debug output
    //$mail->SMTPDebug = 2;
    //$mail->SMTPDebug = SMTP::DEBUG_SERVER;
    
    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->Host = 'smtp.mail.ru';  // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    //$mail->Username = 'zhtirlik82@gmail.com';                 // SMTP username
    $mail->Username = 'zhtirlik82@mail.ru';
    //$mail->Password = '8upuxMl9';                           // SMTP password
    //Пароль для внешнего приложения
    $mail->Password = 'j4xbP0kjpuKDbE1mxwzV';
    //$mail->SMTPSecure = 'tls';
    //$mail->Port = 587;
    $mail->SMTPSecure = 'ssl'; 
    $mail->Port = 465;                                    // TCP port to connect to
    $mail->CharSet = 'UTF-8';
    $mail->isHTML(true); // Set email format to HTML
    $mail->setFrom('zhtirlik82@mail.ru', 'Тірлік');
    
    /*
    $mail->From = "from@yourdomain.com";
    $mail->FromName = "Full Name";
    */
    
    //$mail->addAddress("zh_82@mail.ru", "Recipient Name");
    $a = explode(',', $as);
    foreach($a as $v)
      $mail->addAddress(trim($v));
      
    //Provide file path and name of the attachments
    //$mail->addAttachment("file.txt", "File.txt");
    //$mail->addAttachment("images/profile.png"); //Filename is optional
    $a = explode(',', $at);
    foreach($a as $v)
      $mail->addAttachment($v);
      
    $mail->isHTML(true);

    $mail->Subject = $sb;
    $mail->Body = $bdhtml;
    //$mail->AltBody = "This is the plain text version of the email content";
    
    
    if(!$mail->send()) {
        echo 'Message could not be sent.';
        echo 'Mailer Error: ' . $mail->ErrorInfo;
    } else {
        echo $mail->getLastMessageID().'Message has been sent';
    }
    /*
    try {
      $mail->send();
    } catch (Exception $e) {
    ⠀⠀//echo "Mailer Error: " . $mail->ErrorInfo;
    }*/
  }
	
	function gpw($mc) {
		$chars="qazxswedcvfrtgbnhyujmkiolp1234567890QAZXSWEDCVFRTGBNHYUJMKIOLP";
		$max=$mc;
		$size=StrLen($chars)-1;
		$password=null;
		while($max--)
		$password.=$chars[rand(0,$size)];

		return $password;
	}
  
	function ExcelA($fn, $si, $bl, $bc, $ec) {
		$r = array();
		error_reporting(E_ALL);
		ini_set('display_errors', TRUE);
		ini_set('display_startup_errors', TRUE);
		
		if(!defined('EOL'))
			define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');


		date_default_timezone_set('Europe/London');

		  $file_name = $fn;
		  $SheetIndex = $si;
		  $start_line = $bl;
		  $column_beg = $bc;
		  $column_end = $ec;
		  
		  require_once MD.'PHPExcel-1.8/Classes/PHPExcel/IOFactory.php';
		  //require_once M.'PHPExcel-1.8/Classes/PHPExcel/IOFactory.php';
		  $objPHPExcel = PHPExcel_IOFactory::load($file_name);
		  //echo "excel";
		  
		  
		  $objPHPExcel->setActiveSheetIndex($SheetIndex);
		  $sheet = $objPHPExcel->getActiveSheet();
		  $lines = $sheet->getHighestRow();
		  
		  //echo $lines;
		  //$sheet->getStyle('A1:T' . $lines)
				//->getNumberFormat()
				//->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
		  /*
		$objPHPExcel->getActiveSheet()
				->getStyle('A1:T' . $lines)
				->getNumberFormat()
				->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
			   */ 
		  $rowkey = -1;
		  for ($i = $start_line; $i <= $lines; $i++) { 
			$nColumn = PHPExcel_Cell::columnIndexFromString($sheet->getHighestColumn());
			$Excel_row = array();
			
			
			for ($j=$column_beg; $j<=$column_end; $j++) {
				//$sheet->setCellValueExplicitByColumnAndRow($j, $i, PHPExcel_Cell_DataType::TYPE_STRING);
			  $cell = $sheet->getCellByColumnAndRow($j, $i);
			  //$cell->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
			  //$val = $cell->getValue();
			  $val = $cell->getFormattedValue();
			  //$format = 'd.m.Y';
			  //$val = $cell->getFormattedValue();
			  //if($val != '' && PHPExcel_Shared_Date::isDateTime($cell))
				//$val = date($format, PHPExcel_Shared_Date::ExcelToPHP($val)); 
			  
			  //if((substr($val,0,1) === '=' ) && (strlen($val) > 1))
			  if(!is_null($val) && (substr($val,0,1) === '=' ))
				//$val = $cell->getCalculatedValue();
				$val = $cell->getOldCalculatedValue();
			  
			  
			  //$Excel_row[$j] = strval($val);
			  $Excel_row[$j] = $val;
			}
			
			$r[] = $Excel_row;

		  }
		return $r;
	}
	
	function showV($oM, $cn, $ip) {
		$r = '';
		$o = new $cn($oM, $ip);
		$r .= $o->show();
		unset($o);
		return $r;
	}

	function is_mobile() {
		if ( empty( $_SERVER['HTTP_USER_AGENT'] ) ) {
			$is_mobile = false;
		} elseif ( strpos( $_SERVER['HTTP_USER_AGENT'], 'Mobile' ) !== false // Many mobile devices (all iPhone, iPad, etc.)
			|| strpos( $_SERVER['HTTP_USER_AGENT'], 'Android' ) !== false
			|| strpos( $_SERVER['HTTP_USER_AGENT'], 'Silk/' ) !== false
			|| strpos( $_SERVER['HTTP_USER_AGENT'], 'Kindle' ) !== false
			|| strpos( $_SERVER['HTTP_USER_AGENT'], 'BlackBerry' ) !== false
			|| strpos( $_SERVER['HTTP_USER_AGENT'], 'Opera Mini' ) !== false
			|| strpos( $_SERVER['HTTP_USER_AGENT'], 'Opera Mobi' ) !== false ) {
				$is_mobile = true;
		} else {
			$is_mobile = false;
		}

		return $is_mobile;
	}
?>
