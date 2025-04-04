<?php
	/*
	* Copyright (C) 2025 Жанибек Какимбаев
	*
	* Бұл бағдарлама GNU General Public License v3.0 (GPLv3) лицензиясы бойынша таратылады.
	* Лицензия мәтіні: https://www.gnu.org/licenses/gpl-3.0.html
	*/
	namespace mvc\cs;
	class cAdmin extends cTV {
		function f() {
			$r = '';
			//$r .= $this->fp;
			$this->gotoshow = true;
			return $r;
		}

		function init() {
			$r = '';
			$this->tn = 'scf';
			$this->fnPKF = 'ID';
			$this->fnPDF = 'PD';
			$this->fnCNT = 'CT';
			$this->fnNM = 'NM';
			$this->fnTT = 'TT';
			$this->sd['tvpd'] = 1;
			if(isset($this->oM->ja['tables']['scf'])) {
				if(!isset($this->sd['url']))
					$this->sd['url'] = $this->ip['url'];
				
				if(!isset($this->sd['trv']))
					$this->sd['trv'] = $this->oM->ja['tables']['scf'];
				
				if(!isset($this->sd['its'][$this->sd['trv']]['rs'])) {
					if(isset($this->sd['hd'])) {
						$hd = $this->sd['hd'];
						preg_match_all('/\{(.+?)\}/', $hd, $ms);
						$this->sd['ms'] = $ms;
						$i = 0;
						$fs = [];
						foreach($ms[1] as $k=>$h) {
							$i++;
							$ar = explode('.', $h);
							$cnt = count($ar);
							if($cnt==2) {
								$f = [];
								$f['ID'] = $i;
								$f['H'] = $h;
								$f['NM'] = $ar[0];
								$fs[$f['NM']] = $f;
							}
						}
						$this->sd['its'][$this->sd['trv']]['fs'] = $fs;
						$this->sd['its'][$this->sd['trv']]['rs'] = 
						$this->oM->setTVRS(
							$this->sd['tvpd'], $this->oM->ja['db']['dbtp'], $this->oM->dbSCF, $this->sd['trv']
							, 'ID', 'PD', 'CT', $fs
						);
					}					
				}
			}
			parent::init();
			return $r;
		}
		
	}
?>
