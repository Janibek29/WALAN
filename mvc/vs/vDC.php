<?php
	/*
	* Copyright (C) 2025 Жанибек Какимбаев
	*
	* Бұл бағдарлама GNU General Public License v3.0 (GPLv3) лицензиясы бойынша таратылады.
	* Лицензия мәтіні: https://www.gnu.org/licenses/gpl-3.0.html
	*/
	namespace mvc\vs;
	
	class vDC extends jV {
		
		function ipcr() {
			$r = '';
			$this->ip['hd'] = $this->getACETRH();
			return $r;
		}
		
		function init() {
			$r = '';
			
			return $r;
		}
		function r() {
			$r = '';
			$r .= 'Өтімдер';
			$r .= '<a href="'.$this->oC->sd['url'];
			$r .= '&'.$this->oC->ccr."savefd\"";
			$r .= '>[✓]</a>';
			
			$p = 'a:0:0';
			$tt = '[+]';
			$trv = $this->oC->fd['trv'];
			$r .= '<a href="'.$this->oC->sd['url'].'&trv='.$trv.'&p='.$p.'">'.$tt.'</a>';
			$r .= $this->stl();
			$r .= $this->showACE();
			return $r;
		}
		
		function showACE() {
			$r = '';
			$r .= '<table class="tb">';
			$r .= '<thead>';
			$r .= '<tr><th rowspan="3">ID</th>';
			$r .= '<th>ДТ шоты</th><th>ДТ Субконто</th><th>ДТ Саны</th><th>КТ шоты</th><th>КТ Субконто</th><th>КТ Саны</th><th>Сомы</th>';
			$r .= '</tr>';
			$r .= '<tr>';
			$r .= '<th>ДТ Бөлімі</th><th></th><th>ДТ бірлігі</td><th>КТ Бөлімі</th><th></th><th>КТ бірлігі</th><th>Мазмұны</th>';
			$r .= '</tr>';
			$r .= '<tr>';
			$r .= '<th>Уақыты</th><th></th><th>ДТ бірлік сомы</th><th></th><th></th><th>КТ бірлік сомы</th><th>Номер журнала</th>';
			$r .= '</tr>';
			$r .= '</thead>';
			
			if(isset($this->oC->fd['its'][$this->oC->fd['trv']]['rd'])) {
				$rs = $this->oC->fd['its'][$this->oC->fd['trv']]['rd'];
				$ip = [];
				$oCL = new \mvc\vs\vCL($this->oM, $ip);
				$oCL->o = $this->oC;
				$r .= '<tbody>';				
				foreach($rs as $rk=>$rw) {
					$oc = $this->cr;
					$oc .= $this->oM->ssd.$this->oC->fp;
					$oc .= $this->oM->ssd.'fd';
					$oc .= $this->oM->ssd.$this->oC->fd['trv'];
					$oc .= $this->oM->ssd.'rs';
					$oc .= $this->oM->ssd.$rw['rk'];
					$oc .= $this->oM->ssd.'fn';
					$hd = $this->ip['hd'];
					preg_match_all('/\{(.+?)\}/', $hd, $m);
					foreach($m[1] as $k=>$h) {
						$tg = $oCL->cl($oc, $h, $rw);
						$hd = str_replace('{'.$h.'}', $tg, $hd);
					}
					$r .= $hd;
				}
				$r .= '</tbody>';
			}
			$r .= '</table>';
			unset($oCL);
			return $r;
		}
		
		function stl() {
			$r = '';
			ob_start();
			?>
			<style>
				.cA4 {
					width: 210mm;
					/*height: 297mm;*/
					background:#FFF;
					display: block;
					margin-left: auto;
					margin-right: auto;
				}
				.tbline {
				  background:#F0F0F0;
				  border-bottom:3px solid #000;
				}
				
				.tb {
					border-collapse:collapse; 
					table-layout:fixed;
					border:1px solid #000;
				}
				
				.tb th {
				  background:#F0F0F0;
				  border:1px solid #000;
				}

				.tb tr {
				  background:#F0F0F0;
				  border:1px solid #000;
				}

				.tb td{
				  background:#FFF;  
				  border:1px solid #000;
				  cursor:pointer;
				}
				
				
			</style>
			<?php
			$r .= ob_get_contents();
			ob_end_clean();
			return $r;
		}
		
		function getACETRH() {
			$r = '';
			$r .= '<tr>';
			$r .= '<th rowspan="3">{ID.v}</th>';
			$r .= '<td>{D.ac:ACS.foc:r}</td>';
			$r .= '<td>{DS1.acd:0}</td>';
			$r .= '<td>{DCNT.e}</td>';
			$r .= '<td>{C.ac:ACS.foc:r}</td>';
			$r .= '<td>{CS1.acd:0}</td>';
			$r .= '<td>{CCNT.e}</td>';
			$r .= '<td>{SM.e}</td>';
			$r .= '</tr>';
			
			$r .= '<tr>';
			$r .= '<td></td>';
			$r .= '<td>{DS2.acd:0}</td>';
			$r .= '<td>{DCR.lsd:SCF:TYPES/CRS}</td>';
			$r .= '<td></td>';
			$r .= '<td>{CS2.acd:0}</td>';
			$r .= '<td>{CCR.lsd:SCF:TYPES/CRS}</td>';
			$r .= '<td></td>';
			$r .= '</tr>';
			
			$r .= '<tr>';
			$r .= '<td>{DT.e}</td>';
			$r .= '<td>{DS3.acd:0}</td>';
			$r .= '<td>{DCRSM.e}</td>';
			$r .= '<td></td>';
			$r .= '<td>{CS3.acd:0}</td>';
			$r .= '<td>{CCRSM.e}</td>';
			$r .= '<td>{CT.e}</td>';
			$r .= '</tr>';
			
			$r .= '<tr><td colspan="8" style="background:#F0F0F0;">-</td></tr>';
			
			return $r;
		}

	}
?>
