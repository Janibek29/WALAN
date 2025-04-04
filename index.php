<?php
	/*
	* Copyright (C) 2025 Жанибек Какимбаев
	*
	* Бұл бағдарлама GNU General Public License v3.0 (GPLv3) лицензиясы бойынша таратылады.
	* Лицензия мәтіні: https://www.gnu.org/licenses/gpl-3.0.html
	*/
	namespace index {
		session_start();
		error_reporting(E_ALL);
		ini_set('display_errors','On');
		ob_start();
		//echo extension_loaded('pgsql'); 
		define('CMD', 'o');  //Класс Кілті Әдісі
		define('SSC', ':'); //namespace бөлімі
		define('SSD', ';'); //Кілт пен Әдіс бөлімі
		define('JO', 'jT'); //JS object
		define('THS', 'ths/');
		define('IMG', 'img/');
		define('PGS', 'pgs/');
		define('FMS', 'fms/');
		define('M', '/m/');
		define('TBS', __DIR__.'/tbs/');
		define("MD", __DIR__.'/m/');
		define("FOC", __DIR__.'/foc/');
		define("KYS", __DIR__.'/kys/');
		define("PVT", KYS.'/pvt/');
		define("PBC", KYS.'/pbc/');
		require_once(MD.'SxGeo22_API/SxGeo.php');
		require_once('al.php');//Autoload
		require_once('gf.php');//Global Functions
		/*
		//echo hash('sha512', 'admin');
		
		$salt = bin2hex(random_bytes(8)); // 16 символов соли

		echo crypt('admin', '$6$' . $salt);*/

		$ip = [];
		$ip['n'] = 1;
		$ip['cmd'] = CMD;
		$ip['ssc'] = SSC;
		$ip['ssd'] = SSD;
		$ip['jo'] = JO;
		$oM = new \mvc\ms\jM($ip);


		$ar = explode('?', $_SERVER['REQUEST_URI']);
		$oM->wurl =$ar[0] ;
		$oM->jf = 'scf.json';
		$oM->vt = 1;//Desktop	
		if(\is_mobile()) {
			$oM->vt = 2;//Mobile
			if($oM->w>$oM->h) {
				$oM->vt = 3;
			}
		}
		if(file_exists($oM->jf)) {
			$oM->ja = json_decode(file_get_contents($oM->jf), true);
			$oM->connSCF();
			$oM->init();
		}
		
		
		$r = '';
		
		if(isset($_GET[CMD])) {
			$ar = explode(SSD, $_GET[CMD]);
			$cnt = count($ar);
			$cn = $ar[0];
			$ctp = 'j';
			$pos = strpos($cn, 'cs'.SSC.'c');
			if ($pos !== false) {
					$ctp = 'c';
			}
			$pos = strpos($cn, 'vs'.SSC.'v');
			if ($pos !== false) {
					$ctp = 'v';
			}
			
			if($cnt==4) {
				$cn = str_replace(SSC, '\\', $cn);
				$ip = [];
				$ip['n'] = $ar[1];
				$ip['fp'] = $ar[2];
				$f = $ar[3];
				$k = $ip['n'];
				if($cn=='mvc\\ms\\jM') {
					if(method_exists($oM, $f)) {
						$r .=  $oM->{$f}();					
					}
				} else {					
					if(class_exists($cn) && $k>-1 && $f!='') {
						$o = new $cn($oM, $ip);
						if(method_exists($o, $f)) {
							$r .=  $o->{$f}();
							if($o->gotoshow)
								goto show;
						} else {
							$r .= 'Әдіс жоқ cn='.$cn.' f='.$f;
						}
						unset($o);
					} else {
						$r .= 'Класс жоқ cn='.$cn.' f='.$f;
					}
				}
			} elseif($cnt==3 && $ctp=='v') { //20250207 Негізі V ны көрсетпеу қажет
				/*$cn = str_replace(SSC, '\\', $cn);
				$ip = [];
				$ip['n'] = $ar[1];
				$ip['fp'] = $ar[2];
				echo showV($oM, $cn, $ip);*/
			}
		} elseif(isset($_GET['exit'])) {
			$ip = [];
			$oA = new \mvc\cs\cA($oM, $ip);
			$r .= $oA->sexit();
			unset($oA);
			//goto show;
			//ob_start(); // Starts Output Buffering
			header('location: ?pg=a');
		} else {//шығару
			show:
			if(file_exists($oM->jf)) {
				$thrw = $oM->getSRW('GVS/TH', 'v');
				$pg = 'main';
				switch($pg) {
					case '':
					break;
					case 'main1':
						$r .= print_r($rw, true);
						break;
					default:
						$thp = THS.$thrw['V'].'/';
						$th = $thp.$thrw['V'].'.php';
						require_once($th);
				}
			} else {
				$ip = [];
				$ip['n'] = 1;
				$ip['fp'] = 1;
				$r .= showV($oM, '\\mvc\\vs\\vCreateSCF', $ip);
			}
		}
				
		echo $r;
		
		ob_end_flush();
		
		unset($oM);
	}
?>
