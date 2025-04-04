<?php
	/*
	* Copyright (C) 2025 Жанибек Какимбаев
	*
	* Бұл бағдарлама GNU General Public License v3.0 (GPLv3) лицензиясы бойынша таратылады.
	* Лицензия мәтіні: https://www.gnu.org/licenses/gpl-3.0.html
	*/
	namespace mvc\cs;
	class cDC extends jC {
		function init() {
			$r = '';
			$this->fd['trv'] = $this->oM->getSID('TABLES/ACE');
			$this->fd['sfm'] = 1;
			$this->fd['ftp'] = 'rs';
			$this->sd['rdtp'] = 'fd';
			return $r;
		}
		
		function rq() {
			$r = '';
			if($this->fd['trv']>0 && isset($_GET['dc'])) {
				if(isset($this->ip['hd'])) {
					$hd = $this->ip['hd'];
					$fs = [];
					preg_match_all('/\{(.+?)\}/', $hd, $m);
					foreach($m[1] as $k=>$h) {
						$ar = explode('.', $h);
						$fn = $ar[0];
						$f['ID'] = $k;
						$f['NM'] = $fn;
						$f['H'] = $h;						
						$fs[$fn] = $f;
					}
					$this->fd['dc'] = $_GET['dc'];
					if(!isset($this->fd['its'][$this->fd['trv']]['rd'])) {
						$this->fd['its'][$this->fd['trv']]['fm'] = 0;
						$this->fd['its'][$this->fd['trv']]['fs'] = $fs;
						$this->fd['its'][$this->fd['trv']]['rd'] = $this->oM->getDCACE($this->fd['dc'], $fs);
					}
				}
			}
			
			if(isset($_GET['p'])) {
				$p = $_GET['p'];
				if(substr($p, 0, 1)=='a') {
					$ar = explode(':', $p);
					$fpk = $ar[1];
					$fm = $ar[2];
					$pkv = 0;
					$p = $this->addrd();
				}
			}
			
			return $r;
		}
	}
?>
