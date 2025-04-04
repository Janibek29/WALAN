<?php
	/*
	* Copyright (C) 2025 Жанибек Какимбаев
	*
	* Бұл бағдарлама GNU General Public License v3.0 (GPLv3) лицензиясы бойынша таратылады.
	* Лицензия мәтіні: https://www.gnu.org/licenses/gpl-3.0.html
	*/
	namespace mvc\cs;
	class cTTV extends cTV {
		function init() {
			$r = '';
			if(!isset($this->sd['tvtrv']))
				$this->sd['tvtrv'] = $this->ip['tvtrv'];
			
			if(!isset($this->sd['tvPKF'])) {
				$this->tn = $this->oM->getTN($this->sd['tvtrv']);
				$rw = $this->oM->getTTV($this->tn);
				$this->sd['tvPKF'] = $rw['PKF'];
				$this->sd['tvPDF'] = $rw['PDF'];
				$this->sd['tvCNT'] = $rw['CTF'];
				$this->sd['tvNM'] = $rw['NMF'];
				$this->sd['tvTT'] = $rw['TTF'];
				$this->sd['tvURL'] = 'V';	
			}
			$this->fnPKF = $this->sd['tvPKF'];
			$this->fnPDF = $this->sd['tvPDF'];
			$this->fnCNT = $this->sd['tvCNT'];
			$this->fnNM = $this->sd['tvNM'];
			$this->fnTT = $this->sd['tvTT'];
			
			if(isset($this->oM->ja['tables']['scf'])) {
				if(!isset($this->sd['ism']))
					$this->sd['ism'] = $this->ip['tvism'];
				$this->ism = $this->sd['ism'];

				if(!isset($this->sd['url']))
					$this->sd['url'] = $this->ip['url'];
				if(!isset($this->sd['trv']))
					$this->sd['trv'] = $this->ip['tvtrv'];
				if(!isset($this->sd['tvpd'])) {
					$this->sd['tvpd'] = $this->ip['tvpd'];
					
				}
				
				
				if(!isset($this->sd['its'][$this->sd['trv']]['rs'])) {
					$this->sd['its'][$this->sd['trv']]['rs'] = 
						$this->oM->setTVRS(
							$this->sd['tvpd'], $this->oM->ja['db']['dbtp'], $this->oM->dbSCF
							, $this->sd['trv']
							, $this->fnPKF, $this->fnPDF, $this->fnCNT, []
						);
				}
			}
			parent::init();
			return $r;
		}
	}
?>
