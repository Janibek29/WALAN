<?php
	/*
	* Copyright (C) 2025 Жанибек Какимбаев
	*
	* Бұл бағдарлама GNU General Public License v3.0 (GPLv3) лицензиясы бойынша таратылады.
	* Лицензия мәтіні: https://www.gnu.org/licenses/gpl-3.0.html
	*/
	namespace mvc\vs;
	
	class vAdmin extends vTV {
	
		function tmpD($rw) {
			$r = '';
			$tt = $rw['d'][$this->oC->fnTT];
			if($tt=='')
				$tt = $rw['d'][$this->oC->fnNM];
			$r .= "<tr><td>";
			$r .= "<a href=\"?pg=admin&";
			$r .= $this->oC->pcr.'pk='.$rw['rk'];
			$r .= "\" style=\"color: black;font-weight:bold;text-decoration: none;\">";
			$r .= $tt."</a>";
			$r .= "</td><td>";
			$r .= "<a href=\"?pg=admin&";
			$r .= $this->oC->pcr.'rk='.$rw['rk'];
			$r .= "\" style=\"color: black;font-size: 1.5em;text-decoration: none;\">✍</a>";
			$r .= "</td></tr>";
			return $r;
		}
		
		function tmpF($rw) {
			$r = '';
			$tt = $rw['d'][$this->oC->fnTT];
			if($tt=='')
				$tt = $rw['d'][$this->oC->fnNM];
			$r .= "<tr><td>";
			$r .= "<a href=\"?pg=admin&";
			$r .= $this->oC->pcr.'rk='.$rw['rk'];
			$r .= "\" style=\"color: black;text-decoration:none;\">";
			$r .= $tt."</a>";
			$r .= "</td><td>";
			$r .= "</td></tr>";
			return $r;
		}
		
		function rf($rw) {
			$r = '';			
			if(isset($this->oC->sd['hd']) && isset($this->oC->sd['its'][$this->oC->sd['trv']]['fs'])) {
				$hd = $this->oC->sd['hd'];
				$ms = $this->oC->sd['ms'];
				$fs = $this->oC->sd['its'][$this->oC->sd['trv']]['fs'];
				$ip = [];
				$oCL = new \mvc\vs\vCL($this->oM, $ip);
				$oCL->o = $this->oC;
				$oc = $this->cr;
				$oc .= $this->oM->ssd.$this->oC->fp;
				$oc .= $this->oM->ssd.'sd';
				$oc .= $this->oM->ssd.$this->oC->sd['trv'];
				$oc .= $this->oM->ssd.'rs';
				$oc .= $this->oM->ssd.$rw['rk'];
				$oc .= $this->oM->ssd.'fn';
				//print_r($fs);
				foreach($ms[1] as $k=>$h) {
					$tg = $oCL->cl($oc, $h, $rw);
					$hd = str_replace('{'.$h.'}', $tg, $hd);
				}
				unset($oCL);
				$r .= $hd;
			}
			return $r;
		}
		
		function tmpRW() {
			$r = '';
			ob_start();
			?>
			<table>
				<tbody>
					<tr><td>ID</td><td>{ID.v}</td></tr>
					<tr><td>DBHOST</td><td>{DBHOST.e}</td></tr>
					<tr><td>DBPORT</td><td>{DBPORT.e}</td></tr>
					<tr><td>DBUN</td><td>{DBUN.e}</td></tr>
					<tr><td>DBPW</td><td>{DBPW.e}</td></tr>
					<tr><td>UN</td><td>{UN.e}</td></tr>
					<tr><td>PW</td><td>{PW.pw}</td></tr>
					<tr><td>NM</td><td>{NM.e}</td></tr>
					<tr><td>TT</td><td>{TT.e}</td></tr>
					<tr><td>DBNM</td><td>{DBNM.e}</td></tr>					
					<tr><td>PKF</td><td>{PKF.e}</td></tr>
					<tr><td>TTF</td><td>{TTF.e}</td></tr>
					<tr><td>PDF</td><td>{PDF.e}</td></tr>
					<tr><td>CTF</td><td>{CTF.e}</td></tr>
					<tr><td>UDF</td><td>{UDF.e}</td></tr>
					<tr><td>DCF</td><td>{DCF.e}</td></tr>
					<tr><td>FMF</td><td>{FMF.e}</td></tr>
					<tr><td>PNS</td><td>{PNS.e}</td></tr>
					<tr><td>EMS</td><td>{EMS.e}</td></tr>
					<tr><td>S</td><td>{S.bool}</td></tr>
					<tr><td>SGN</td><td>{SGN.bool}</td></tr>
					<tr><td>V</td><td>{V.e}</td></tr>
					<tr><td>LL</td><td>{LL.e}</td></tr>
					<tr><td>CT</td><td>{CT.bool}</td></tr>
					<tr><td>TP</td><td>{TP.ac:scf}</td></tr>
					<tr><td>FMS</td><td>{FMS.e}</td></tr>
					<tr><td>ITS</td><td>{ITS.e}</td></tr>
					<tr><td>UF</td><td>{UF.e}</td></tr>
					<tr><td>KEYS</td><td>{KEYS.b.focKYS}</td></tr>
					<tr><td>KEY1</td><td>{Құпия сөзді ендіріңіз.p.focKYS}</td></tr>
					<tr><td>ACQ</td><td>{ACQ.txt}</td></tr>
					<tr><td colspan="2">{UF.uf}</td></tr>
					<tr><td colspan="2">{NM.tb}</td></tr>
				</tbody>
			</table>
			<?php
			$r .= ob_get_contents();
			ob_end_clean();
			return $r;
		}
		
		
		
	}
?>
