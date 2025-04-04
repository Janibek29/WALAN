<?php
	/*
	* Copyright (C) 2025 Жанибек Какимбаев
	*
	* Бұл бағдарлама GNU General Public License v3.0 (GPLv3) лицензиясы бойынша таратылады.
	* Лицензия мәтіні: https://www.gnu.org/licenses/gpl-3.0.html
	*/
	namespace mvc\vs;
	
	class vPG extends jV {
		function init() {
			$r = '';
			
			return $r;
		}
		
		function r() {
			$r = '';
			//$r .= 'pgsql'.extension_loaded('pgsql');
			//phpinfo();
			//\pg_connect();
			//$r .= $this->oM->sd['cn'];
			switch($this->oC->sd['pg']) {
				case 'main':
					$ip = [];
					$ip['n'] = 1;
					$ip['fp'] = 1;
					$ip['url'] = '?pg=main';
					$r .= showV($this->oM, '\\mvc\\vs\\vJB', $ip);
				break;
				case 'i':
					phpinfo();
				break;
				case 's':
					require_once('s.php');
				break;
				case 'j':
					require_once('j.php');
				break;
				case 'a':
					$ip = [];
					$ip['n'] = 1;
					$ip['fp'] = 1;
					$r .= showV($this->oM, '\\mvc\\vs\\vA', $ip);
					break;
				case 'admin':
					$ip = [];
					$ip['n'] = 1;
					$ip['fp'] = 1;
					$ip['url'] = '?pg=admin';
					$r .= showV($this->oM, '\\mvc\\vs\\vAdmin', $ip);
					break;
				case 'um':
					if($this->oM->sd['uid']>0) {
						$ip = [];
						$ip['n'] = 1;
						$ip['fp'] = 1;
						$ip['pk'] = 1;
						$ip['rk'] = 0;
						$ip['tvism'] = true;
						$ip['tvpd'] = $this->oM->sd['gtp'];
						$ip['pk'] = $ip['tvpd'];
						//echo $ip['pk'];
						$ip['tvtrv'] = $this->oM->getTRV('SCF');
						$ip['url'] = '?pg=um';
						$r .= showV($this->oM, '\\mvc\\vs\\vTTV', $ip);
					} else {
						$r .= 'Авторлау қажет';
					}
					break;
					
				case 'g':
					if($this->oM->sd['uid']>0) {
						$ip = [];
						$ip['n'] = 1;
						$ip['fp'] = 1;
						$ip['url'] = '?pg=g';
						$r .= showV($this->oM, '\\mvc\\vs\\vGRID', $ip);
					} else {
						$r .= 'Авторлау қажет';
					}
					break;
				case 'd':
					if($this->oM->sd['uid']>0) {
						$ip = [];
						$ip['n'] = 1;
						$ip['fp'] = 1;
						$ip['url'] = '?pg=d';
						$r .= showV($this->oM, '\\mvc\\vs\\vDC', $ip);
					} else {
						$r .= 'Авторлау қажет';
					}
					break;
				case 'b':
					if($this->oM->sd['uid']>0) {
						$ip = [];
						$ip['n'] = 1;
						$ip['fp'] = 1;
						$ip['url'] = '?pg=b';
						$r .= showV($this->oM, '\\mvc\\vs\\vB', $ip);
					} else {
						$r .= 'Авторлау қажет';
					}
					break;
				case 'ems':
					if($this->oM->sd['uid']>0) {
						$ip = [];
						$ip['n'] = 1;
						$ip['fp'] = 1;
						$ip['url'] = '?pg=ems';
						$r .= showV($this->oM, '\\mvc\\vs\\vEMS', $ip);
					} else {
						$r .= 'Авторлау қажет';
					}
				break;
				case 'dev':
					if($this->oM->sd['uid']>0) {
						$drw = $this->oM->getSRW('RS/dev', 'id');
						//$r .= print_r($drw, true);
						if($drw['ID']==$this->oM->sd['gtp']) {
							if(isset($_GET['do'])) {
								$ar = explode(SSD, $_GET['do']);
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
								//echo 'cnt='.$cnt;
								if($cnt==4) {
									$cn = str_replace(SSC, '\\', $cn);
									$ip = [];
									$ip['n'] = $ar[1];
									$ip['fp'] = $ar[2];
									$f = $ar[3];
									$k = $ip['n'];
									if(class_exists($cn) && $k>-1 && $f!='') {
										$o = new $cn($this->oM, $ip);
										if(method_exists($o, $f)) {
											$r .=  $o->{$f}();
										} else {
											$r .= 'Әдіс жоқ cn='.$cn.' f='.$f;
										}
										unset($o);
									} else {
										$r .= 'Класс жоқ cn='.$cn.' f='.$f;
									}									
								} elseif($cnt==3 && $ctp=='v') {
									$cn = str_replace(SSC, '\\', $cn);
									$ip = [];
									$ip['n'] = $ar[1];
									$ip['fp'] = $ar[2];
									$r .= showV($this->oM, $cn, $ip);
								}
							} else {
								$ip = [];
								$ip['n'] = 1;
								$ip['fp'] = 1;
								$ip['url'] = '?pg=dev';
								
								$r .= showV($this->oM, '\\mvc\\vs\\vDEV', $ip);
							}
						}
					} else {
						$r .= 'Авторлау қажет';
					}
				break;
				/*
				case 'po':
					if($this->oM->sd['uid']>0) {
						
						
					} else {
						$r .= 'Авторлау қажет';
					}
				break;
				*/
				default:
					
			}
			
			return $r;
		}

		function hd() {
			$r = '';
			ob_start();
			?>
				<div class="cd">
					<a href="">Open</a>
				</div>
			<?php
			$r .= ob_get_contents();
			ob_end_clean();
			return $r;
		}

	}
?>
