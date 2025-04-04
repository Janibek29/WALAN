<?php
	/*
	* Copyright (C) 2025 Жанибек Какимбаев
	*
	* Бұл бағдарлама GNU General Public License v3.0 (GPLv3) лицензиясы бойынша таратылады.
	* Лицензия мәтіні: https://www.gnu.org/licenses/gpl-3.0.html
	*/
  namespace Autoload {
    
	function myErrorHandler($errno, $errstr, $errfile, $errline)
    {
      $msg = '';
      $ar = explode('?', $_SERVER['REQUEST_URI']);
		$lurl = $ar[0];
	  $msg .=  '<a href="'.$lurl.'?exit">OUT</a><br/>';
      //$msg .=  '<a href="/pma521/index.php" target="_blank">pma520</a><br/>';
      $msg .=  "\r\n";
        if (!(error_reporting() & $errno)) {
            // Этот код ошибки не включён в error_reporting,
            // так что пусть обрабатываются стандартным обработчиком ошибок PHP
            $msg .= 'Этот код ошибки не включён в error_reporting '. $errstr;
            //throw new \Exception('');
            //return true;
        }

        // может потребоваться экранирование $errstr:
        $errstr = htmlspecialchars($errstr);

        switch ($errno) {
          case E_USER_ERROR:
              $msg .= "<b>Пользовательская ОШИБКА</b> [$errno] $errstr<br />\n";
              $msg .= "  Фатальная ошибка в строке $errline файла $errfile";
              $msg .= ", PHP " . PHP_VERSION . " (" . PHP_OS . ")<br />\n";
              $msg .= "Завершение работы...<br />\n";
              //exit(1);

          case E_USER_WARNING:
              $msg .= "<b>Пользовательское ПРЕДУПРЕЖДЕНИЕ</b> [$errno] $errstr<br />\n";
              break;

          case E_USER_NOTICE:
              $msg .= "<b>Пользовательское УВЕДОМЛЕНИЕ</b> [$errno] $errstr<br />\n";
              break;

          default:
              $msg .= "Неизвестная ошибка: [$errno] $errstr строке $errline файла $errfile<br />\n";
              break;
        }
        
        //str_replace("\r", "<br/>"
        //nl2br($msg);
        echo $msg;
        throw new \Exception('');
        /* Не запускаем внутренний обработчик ошибок PHP */
        return true;
    }
    
    function fatalErrorShutdownHandler(){
      $last_error = error_get_last();
      if ($last_error['type'] === E_ERROR) {
        // fatal error
        myErrorHandler(E_ERROR, $last_error['message'], $last_error['file'], $last_error['line']);
      }
    }

    set_error_handler("Autoload\myErrorHandler", E_ALL);
    //register_shutdown_function('Autoload\fatalErrorShutdownHandler');
    
    spl_autoload_register(function ($class) {
      $file = str_replace('\\', DIRECTORY_SEPARATOR, $class).'.php';
      if (file_exists($file)) {
          require $file;
          return true;
      }
      return false;
    });
    
      
  }
?>