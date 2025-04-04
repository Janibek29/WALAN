<?php
	/*
	* Copyright (C) 2025 Жанибек Какимбаев
	*
	* Бұл бағдарлама GNU General Public License v3.0 (GPLv3) лицензиясы бойынша таратылады.
	* Лицензия мәтіні: https://www.gnu.org/licenses/gpl-3.0.html
	*/
	namespace mvc\vs;
	
	class vJB extends vTV {
		/*function init() {
			$r = '';
			
			return $r;
		}
		
		function r() {
			$r = '';
			$r .= 'JB';
			return $r;
		}*/
		
		function tmpD($rw) {
			$r = '';
			$tt = '';
			if(isset($rw['d'][$this->oC->fnTT]))
				$tt = $rw['d'][$this->oC->fnTT];
			if($tt=='')
				$tt = $rw['d'][$this->oC->fnNM];
			$r .= "<tr><td>";
			$r .= "<a href=\"".$this->oC->sd['url']."&";
				$r .= $this->oC->pcr.'pk='.$rw['rk'];
			$r .= "\" style=\"color: black;font-weight:bold;text-decoration: none;\">";
			$r .= $tt."</a>";
			$r .= "</td>";
			//print_r($this->oC->sd);
			//if($this->oC->sd['ism']!=1) {
				
				$r .= "<td>";
				if(isset($this->oM->sd['admin']) && $this->oM->sd['admin']>0) {
					$r .= "<a href=\"".$this->oC->sd['url']."&";
					$r .= $this->oC->pcr.'rk='.$rw['rk'];
					$r .= "\" style=\"color: black;font-size: 1.5em;text-decoration: none;\">✍</a>";
				}
				$r .= "</td>";
			//}
			$r .= "</tr>";
			return $r;
		}
		
		function tmpF($rw) {
			$r = '';
			$tt = '';
			if(isset($rw['d'][$this->oC->fnTT]))
				$tt = $rw['d'][$this->oC->fnTT];
			if($tt=='')
				$tt = $rw['d'][$this->oC->fnNM];
			
			
			
			//$r .= print_r($this->oC->sd['its'][$this->oC->sd['trv']]['rs'], true);
			
			$r .= "<tr>";
			$r .= "<td>";
			$r .= "<a href=\"".$this->oC->sd['url']."&";
			$r .= $this->oC->pcr.'rk='.$rw['rk'];
			$r .= "\" style=\"color: black;text-decoration:none;\">".$tt."</a>";
			/*if($this->oC->sd['ism']!=1) {				
				$r .= "<a href=\"".$this->oC->sd['url']."&";
				$r .= $this->oC->pcr.'rk='.$rw['rk'];
				$r .= "\" style=\"color: black;text-decoration:none;\">".$tt."</a>";			
			} else {
				$tvURL = $rw['d'][$this->oC->sd['tvURL']];
				//$r .= 'tvURL='.$tvURL;
				
				if($tvURL=='') {	
					$id = $rw['rk'];//$rw['d']['TP'];
					$url = "?pg=g&m=$id";
					
				} else {
					$url = $tvURL;
				}
				
				$r .= "<a href=\"".$url."\"";
				$r .= " style=\"color: black;text-decoration:none;\">".$tt."</a>";
			}*/
			
			$r .= "</td>";
			
			$r .= "<td>";
			$r .= "</td></tr>";
			return $r;
		}
		
		function rf($rw) {
			$r = '';
			
			if(isset($this->oM->sd['admin']) && $this->oM->sd['admin']>0) {
				$tmpRW = $this->tmpRW();
				preg_match_all('/\{(.+?)\}/', $tmpRW, $matches);
				$ip = [];
				$oCL = new \mvc\vs\vCL($this->oM, $ip);
				//$r .= print_r($rw, true);
				$oc = $this->cr;
				$oc .= $this->oM->ssd.$this->oC->fp;
				$oc .= $this->oM->ssd.'sd';
				$oc .= $this->oM->ssd.$this->oC->sd['trv'];
				$oc .= $this->oM->ssd.'rs';
				$oc .= $this->oM->ssd.$rw['rk'];
				$oc .= $this->oM->ssd.'fn';	
				foreach($matches[1] as $k=>$h) {
					$tg = $oCL->cl($oc, $h, $rw);
					$tmpRW = str_replace('{'.$h.'}', $tg, $tmpRW);
				}
				$r .= $tmpRW;
				
				$r .= '<br/>';
			}
			
			if(isset($this->oC->sd['hd'])) {
				$r .= '<div style="margin-left:auto; margin-right:auto; width: 300px; background: white;">';
				if(isset($this->oM->sd['admin']) && $this->oM->sd['admin']>0) {
					$url = '?'.$this->oM->cmd."=".$this->cr;
					$url .= $this->oM->ssd.$this->oC->fp;
					$url .= $this->oM->ssd."fuf";
					$rk = $rw['rk'];
					$ocf = "onchange=\"";
					$ocf .= $this->oM->jo.".ufs('$url', this.files, 0, 0, 'rk=$rk');\"";
					$r .= "<input type=\"file\" multiple $ocf/>";
				}
				
				
				$r .= $this->oC->sd['hd'];
				$r .= '</div>';
			}
			
			return $r;
		}
		
		function tmpRW() {
			$r = '';
			ob_start();
			?>
			{NM.e}{N.e}{CT.bool}{H.bt.cf}
			<?php
			$r .= ob_get_contents();
			ob_end_clean();
			return $r;
		}
	}
?>