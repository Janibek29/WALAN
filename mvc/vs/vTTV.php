<?php
	/*
	* Copyright (C) 2025 Жанибек Какимбаев
	*
	* Бұл бағдарлама GNU General Public License v3.0 (GPLv3) лицензиясы бойынша таратылады.
	* Лицензия мәтіні: https://www.gnu.org/licenses/gpl-3.0.html
	*/
	namespace mvc\vs;
	
	class vTTV extends vTV {
	
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

			if($this->oC->sd['ism']!=1) {
				
				$r .= "<td>";
				
				$r .= "<a href=\"".$this->oC->sd['url']."&";
				$r .= $this->oC->pcr.'rk='.$rw['rk'];
				$r .= "\" style=\"color: black;font-size: 1.5em;text-decoration: none;\">✍</a>";
				$r .= "</td>";
			}
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
			$r .= "<tr>";
			$r .= "<td>";
			if($this->oC->sd['ism']!=1) {				
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
			}
			
			$r .= "</td>";
			
			$r .= "<td>";
			$r .= "</td></tr>";
			return $r;
		}
		
		function rf($rw) {
			$r = '';
			//$r .= 'ID='.$rw['d']['ID'].' PD='.$rw['d']['PD'].' PGS='.$this->oC->oM->ja['scf']['PGS'];
			/*if(in_array($rw['d']['PD']
				, [
					$this->oC->oM->ja['scf']['PGS']
					, $this->oC->oM->ja['scf']['FMS']
				])) {
				$r .= str_replace('{NM.ED}', $this->getTG('NM.ED', $rw), '{NM.ED}');
				$r .= str_replace('{TT.ED}', $this->getTG('TT.ED', $rw), '{TT.ED}');
				//print_r($rw);
				$oFILES = new vFILES(1, $rw);				
				$r .= $oFILES->show();
				unset($oFILES);
				
			} else {*/
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
			//}
			return $r;
		}
		
		function tmpRW() {
			$r = '';
			ob_start();
			?>
			<table>
				<tbody>
					<tr><td>{ID.ID.tt}</td><td>{ID.v}</td></tr>
					<tr><td>{DBHOST.DBHOST.tt}</td><td>{DBHOST.e}</td></tr>
					<tr><td>{DBPORT.DBPORT.tt}</td><td>{DBPORT.e}</td></tr>
					<tr><td>{UN.UN.tt}</td><td>{UN.e}</td></tr>
					<tr><td>{PW.PW.tt}</td><td>{PW.e}</td></tr>
					<tr><td>{NM.NM.tt}</td><td>{NM.e}</td></tr>
					<tr><td>{TT.TT.tt}</td><td>{TT.e}</td></tr>
					
					
					<tr><td>{PKF.PKF.tt}</td><td>{PKF.e}</td></tr>
					<tr><td>{TTF.TTF.tt}</td><td>{TTF.e}</td></tr>
					<tr><td>{PDF.PDF.tt}</td><td>{PDF.e}</td></tr>
					<tr><td>{CTF.CTF.tt}</td><td>{CTF.e}</td></tr>

					<tr><td>{V.V.tt}</td><td>{V.ls:scf}</td></tr>
					<tr><td>{CT.CT.tt}</td><td>{CT.bool}</td></tr>
					<tr><td>{TP.TP.tt}</td><td>{TP.ac:scf}</td></tr>
					<tr><td>{ACQ.ACQ.tt}</td><td>{ACQ.txt}</td></tr>
				</tbody>
			</table>
			<?php
			$r .= ob_get_contents();
			ob_end_clean();
			return $r;
		}
		
		
		
	}
?>
