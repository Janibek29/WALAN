<?php
	/*
	* Copyright (C) 2025 Жанибек Какимбаев
	*
	* Бұл бағдарлама GNU General Public License v3.0 (GPLv3) лицензиясы бойынша таратылады.
	* Лицензия мәтіні: https://www.gnu.org/licenses/gpl-3.0.html
	*/
  $r = '';
  
  $r .= "<div align=\"center\"  style=\"padding: 0px; margin: 0px;\">";
  $r .= '<div align="center" style="max-width: 400px;padding: 0px; margin: 0px;">';
  
  if(isset($_COOKIE['LG'])) {
    //$r .= '<div align="left">'.get_lg($_COOKIE['LG'], $slg_rs).'</div>';
  }
  $r .= "<br/><a target=\"_blank\" href=\"https://kyzylorda.hh.kz/resume/cf7190e0ff00cd9a6f0039ed1f556262724e44\"><img style=\"width:300px; height: auto;\" src=\"".IMG."j.png\"/></a>";
  //$r .= "<br/><a target=\"_blank\" href=\"https://enbek.kz/ru/резюме/3907686\">ENBEK</a>";
  $r .= "<br/><a target=\"_blank\" href=\"https://www.enbek.kz/ru/resume/5616831\">ENBEK</a>";
  $r .= "<br/><a target=\"_blank\" href=\"img/eh.pdf\">EH</a>";
  $r .= "<br/>WALAN - 1M -> 8vc (Mvc) platform, accounting software 1table->8tables,";
  $r .= "Debt Credit, chart of accounts, accounting entries, accounting balances,";
  $r .= "balance sheet report.";
  $r .= "<br/>";
  $r .= "Document management system (DMS) with Electronic Digital Signature (EDS) Document";
  $r .= "<br/>based on ";
  $r .= "OpenSSL ROOT CA, USERS(PUBLIC, PRIVATE) key ";
  $r .= "<br/>";
  $r .= "Postfix Dovecot mail transfer agent (MTA) ";
  $r .= "<br/>";
  $r .= "Dovecot and Postfix are open-source email server software";
  $r .= "<br/>";
  $r .= "<br/><a href=\"?pg=s\">SESSIONS</a>";
  $r .= "<br/>";
  //$r .= "<button onclick=\"window.location='?pg=u'\">Вход</button>";
  $r .= "<br/>";
  $r .= "<a style=\"font-size: 1em;\" href=\"tel:+77000821129\"><b style=\"font-size: 1.2em;\">+77000821129</b></a>";
  $r .= "<br/>";
  $r .= "<a style=\"font-size: 1em;\" href=\"whatsapp://send?text=Здравствуйте&phone=+77000821129&abid=+77000821129\"><b style=\"font-size: 1.2em;\">+77000821129</b></a>";
          
  $r .= "<br/>zh_82@mail.ru";
  $r .= "<br/>zhtirlik82@gmail.com";
  
  
  
  $r .= "<br/>";
  $r .= "<a target=\"_blank\" href=\"".IMG."c1.jpeg\"><img style=\"width:100px; height: auto;\" src=\"".IMG."c1.jpeg\"/></a>";
  $r .= "<a target=\"_blank\" href=\"".IMG."c2.jpeg\"><img style=\"width:100px; height: auto;\" src=\"".IMG."c2.jpeg\"/></a>";
  $r .= "<a target=\"_blank\" href=\"".IMG."A.jpeg\"><img style=\"width:100px; height: auto;\" src=\"".IMG."A.jpeg\"/></a>";
  $r .= "<br/>";
  $r .= "<a target=\"_blank\" href=\"".IMG."cc1.jpeg\"><img style=\"width:100px; height: auto;\" src=\"".IMG."cc1.jpeg\"/></a>";
  $r .= "<a target=\"_blank\" href=\"".IMG."cc2.jpeg\"><img style=\"width:100px; height: auto;\" src=\"".IMG."cc2.jpeg\"/></a>";
  $r .= "<a target=\"_blank\" href=\"".IMG."B24.jpg\"><img style=\"width:100px; height: auto;\" src=\"".IMG."B24.jpg\"/></a>";
  
  $r .= "</div";
  $r .= "</div";
  /*$mainpg = 'pg1';
  if(isset($_GET['mainpg']))
    $mainpg = $_GET['mainpg'];
  
  switch($mainpg) {
    case 'pg1':
      require_once('mainpg1.php');
    break;
    case 'pg2':
      require_once('mainpg2.php');
    break;
    case 'pg3':
      require_once('mainpg2.php');
    break;
  }
  */
  echo $r;
  $r = '';
?>