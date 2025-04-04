<?php
	/*
	* Copyright (C) 2025 Жанибек Какимбаев
	*
	* Бұл бағдарлама GNU General Public License v3.0 (GPLv3) лицензиясы бойынша таратылады.
	* Лицензия мәтіні: https://www.gnu.org/licenses/gpl-3.0.html
	*/
	namespace mvc\vs;
	
	abstract class vTV extends jV {
		public $rs;
				
		function init() {
			$r = '';
			if(!isset($this->oC->sd['hd']))
				$this->oC->sd['hd'] = $this->tmpRW();
			//$this->oC->sd['its'][$this->oC->sd['trv']]['rs']
			if(isset($this->oC->sd['its'][$this->oC->sd['trv']]['rs'])) {
				$this->rs = $this->oC->sd['its'][$this->oC->sd['trv']]['rs'];
				//echo 'rs';
			}
			return $r;
		}
		
		function r() {
			$r = '';
			$r .= '<div style="margin: 20px;">';
			
			if($this->oC->ism!=1)
				$r .= $this->hd();
			//$r .= "<div style=\"background: #F0F0F0; width:300px;\">";			
			$r .= "<a class=\"pnm\" style=\"font-size:2em; text-decoration: none;\" 
				href=\"".$this->oC->sd['url'];
			$r .= '&'.$this->oC->pcr.'pk='.$this->oC->sd['tvpd'];
			$r .= '" >⌂</a>';
			$r .= $this->get_pnm($this->oC->pk, 0);
			//$r .= "</div>";
			//$r .= print_r($this->rs, true);
			//if($this->oC->rk=='')
				//$this->oC->rk = 0;
			
			//$r .= 'rk='.$this->oC->rk;
			$r .= "<div>";
			switch($this->oC->rk) {
				case 0:
					
					$r .= $this->tv($this->oC->pk);
				break;
				default:
					$r .= $this->rf(
						$this->rs[$this->oC->rk]
					);
				break;
			}
			$r .= "</div>";
			$r .= "</div>";
			return $r;
		}
		
		function hd() {
			$r = '';
			if(isset($this->oM->sd['admin']) && $this->oM->sd['admin']>0) {
				$r .= '<a href="'.$this->oC->sd['url'];
				$r .= '&'.$this->oM->cmd.'='.$this->oC->pcr."save";
				$r .= '&'.$this->oC->pcr.'pk='.$this->oC->sd['tvpd'];	
				$r .= '">[✓]</a>';

				$r .= '<a href="'.$this->oC->sd['url'];
				$r .= '&'.$this->oM->cmd.'='.$this->oC->pcr."newrw";
				$r .= '&'.$this->oC->pcr.'pk='.$this->oC->pk;	
				$r .= '">[new]</a>';
				$r .= '<br/>';
			}
			return $r;
		}
		
		function get_pnm($p_rk, $ol=0) {
		  $r = '';
		  //$this->atg_cls = "atg";
		  
		  if(isset($this->rs)) {
			foreach($this->rs as $rk=>$rw) {
			  if($rk==$p_rk) {
					if($rw['pk']>$this->oC->pd) { // && $rw['pk']>0
						$r .= $this->get_pnm($rw['pk']).'/';
					} 
					if($p_rk==$this->oC->pd) {
						
					} else {
						$img = '';
						if($rw['d'][$this->oC->fnPKF]>0) {
						$id = $rw['d'][$this->oC->fnPKF];
						}

						$tt = $rw['d'][$this->oC->fnTT];
						if($tt=='') {
							$tt = $rw['d'][$this->oC->fnNM];
						}
						$r .= "<a class=\"pnm\" style=\" text-decoration: none; font-weight:bold;\"";
						$r .= "href=\"".$this->oC->sd['url']."&";
						$r .= $this->oC->pcr.'pk='.$rw['rk'];
						$r .= '">'.$tt."</a>";
					}
			  }
			}
		  }
		  return $r;
		}
		
		function tv($pk) {
			$r = '';
			
			
			//echo 0;
			//$r .= print_r($this->rs, true);
			if(isset($this->rs)) {
				$r .= "<table>";	
				$rs = [];
				foreach($this->rs as $rk=>$rw) {
					if($rw['pk']==$pk) {
						if(isset($rw['d']['N']))
							$rs[$rk] = $rw['d']['N'];
						$rs[$rk] = 0;
					}
				}
				
				asort($rs);
				
				$trs = [];
				foreach($rs as $rk=>$rw) {
					$trs[$rk] = $this->rs[$rk];
				}
			
				foreach($trs as $rk=>$rw) {
					if($rw['cnt']>0) {
						$r .= $this->tmpD($rw);
					} else {
						$r .= $this->tmpF($rw);
					}
				}

				$r .= "</table>";
			}
			
			
			return $r;
		}
		
		function tmpD($rw) {
			$r = '';
			$r .= "<a href=\"?pg=admin&pk=".$rw['rk']."\" style=\"text-decoration: none;\">".$rw['d'][$this->oC->fnTT]."</a>";
			$r .= "<a href=\"?pg=admin&rk=".$rw['rk']."\" style=\"font-size: 3rem;text-decoration: none;\">✍</a>";
			return $r;
		}
		
		function tmpF($rw) {
			$r = '';
			$r .= "<a href=\"?pg=admin&rk=".$rw['rk']."\">".$rw['d'][$this->oC->fnTT]."</a>";
			return $r;
		}
	}
?>
