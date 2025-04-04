<?php
	/*
	* Copyright (C) 2025 Жанибек Какимбаев
	*
	* Бұл бағдарлама GNU General Public License v3.0 (GPLv3) лицензиясы бойынша таратылады.
	* Лицензия мәтіні: https://www.gnu.org/licenses/gpl-3.0.html
	*/
	namespace mvc\vs;
	
	class vB extends jV {
		
		function ipcr() {
			$r = '';
			//$this->ip['hd'] = $this->rsACS();
			return $r;
		}
		
		function init() {
			$r = '';
			
			return $r;
		}
		
		function r() {
			$r = '';
			$r .= $this->stl();
			$r .= '<div class="cA4">';
			$ar = $this->oM->getHTML(
				$this->period()
				, $this->cr
				, $this->oC->fp
				, 'fd'
				, $this->oC->fd['trv']
				, 'rw'
				, $this->oC->fd['rw']
				, $this->oC
			);
			$r .= $ar['hd'];
			
			$r .= $this->b('', "", 'setPERIOD', "jT.a('?".$this->oC->cvw."r','');", "Шығару");
			
			
			
			$BRS = $this->getBRS($this->oC->fd['rs']);
			$r .= $this->showB($BRS);
			$r .= $this->showR($BRS);
			$r .= '</div>';
			return $r;
		}
		
		function rsACS() {
			$r = '';
			ob_start();
			?>
			<table>
				<thead>
					<tr><th>#rc#</th></tr>
					<tr><th>{ID.v}</th></tr>
				</thead>
				#tbody#
			</table>
			<?php
			$r .= ob_get_contents();
			ob_end_clean();
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
		
		function period() {
			$r = '';
			ob_start();
			?>
			Басы{BDT.date.r}Соңы{EDT.date.r}
			<?php
			$r .= ob_get_contents();
			ob_end_clean();
			return $r;
		}
		
		function getBRS($rs) {
			$BRS = [];
			foreach($rs as $rk=>$rw) {
				$BRW = [];
				$BRW['OB'] = $rw['OB'];
				$BRW['CD'] = $rw['CD'];
				$BRW['NM'] = $rw['NM'];
				$BRW['BD'] = $rw['BD'];
				$BRW['BC'] = $rw['BC'];
				$BRW['OD'] = $rw['OD'];
				$BRW['OC'] = $rw['OC'];
				$sm = round($rw['BD']-$rw['BC']+$rw['OD']-$rw['OC'],2);
				
				if($sm>=0) {
				  $eed = abs($sm);
				  $eec = 0;
				} elseif($sm<0) {
				  $eed = 0;
				  $eec = abs($sm);
				}
				
				$BRW['ED'] = $eed;
				$BRW['EC'] = $eec;
				
				$BRS[] = $BRW;
			}

			return $BRS;
		}
		
		function showB($BRS) {
			$r = '';
			$r .= '<table class="tb">';
			$r .= '<thead>';
			$r .= '<tr>';
			$r .= '<th colspan="2">Есеп</th>';		
			$r .= '<th colspan="2">Басы</th>';
			$r .= '<th colspan="2">Айналым</th>';
			$r .= '<th colspan="2">Соңы</th>';
			$r .= '</tr>';
			$r .= '<tr>';
			$r .= '<th>КОД</th>';
			$r .= '<th>Атауы</th>';
			$r .= '<th>Debt</th>';
			$r .= '<th>Credit</th>';		
			$r .= '<th>Debt</th>';
			$r .= '<th>Credit</th>';
			$r .= '<th>Debt</th>';
			$r .= '<th>Credit</th>';
			$r .= '</tr>';
			$r .= '</thead>';
			$r .= '<tbody>';
			$bd = 0;
			$bc = 0;
			$od = 0;
			$oc = 0;
			$ed = 0;
			$ec = 0;
			foreach($BRS as $rk=>$rw) {
				$r .= '<tr>';
				$r .= '<td>'.$rw['CD'].'</td>';
				$r .= '<td style="width:300px;">'.$rw['NM'].'</td>';
				$r .= '<td>'.$rw['BD'].'</td>';
				$r .= '<td>'.$rw['BC'].'</td>';		
				$r .= '<td>'.$rw['OD'].'</td>';
				$r .= '<td>'.$rw['OC'].'</td>';
				$r .= '<td>'.$rw['ED'].'</td>';
				$r .= '<td>'.$rw['EC'].'</td>';
				$r .= '</tr>';
				
				if($rw['OB']!=1) {
					$bd += $rw['BD'];
					$bc += $rw['BC'];
					$od += $rw['OD'];
					$oc += $rw['OC'];
					$ed += $rw['ED'];
					$ec += $rw['EC'];
				}
			}
			$r .= '<tr><td>Итог</td><td>'.$bd.'</td><td>'.$bc.'</td><td>'.$od.'</td><td>'.$oc.'</td><td>'.$ed.'</td><td>'.$ec.'</td></tr>';
			$r .= '</tbody>';
			$r .= '</table>';
			return $r;
		}
		
		function showR($BRS) {
			$r = '';
			
			$V1 = 0; //Активы
			$V2 = 0; //Обязательства
			$V3 = 0; //Капитал
			$V4 = 0; //Доходы/Расходы
			$V5 = 0; //Обязательства и Капитал
			$V6 = 0; //Разница
			
			//Активы
			$A=0;
			//Обязательства
			$O3400=0;
			//Капитал
			$KK=0;
			//Доходы
			$D6000=0;
			//Расходы
			$P7890=0;
			foreach($BRS as $rk=>$rw) {
				$pcd = '';
				if(isset($rw['CD'])) {
					$ar = explode('.', $rw['CD']);
					$pcd = $ar[0];
				}
				
				//Активы
				if(in_array($pcd, ['1100', '1200', '1300', '1400', '1500', '1600', '1700', '1800', '2100', '2400', '2800', '2900'])) {
					$A+=($rw['ED']-$rw['EC']);
				}
				
				//Обязательства
				if(in_array($pcd, ['3100', '3200', '3300', '3400', '3500', '3600', '3700', '3800', '3900', '4100', '4200', '4300', '4400'])) {
					$O3400+=($rw['EC']-$rw['ED']);
				}

				//Капитал
				if(in_array($pcd, ['5100', '5200', '5300', '5400'])) {
					$KK+=($rw['EC']-$rw['ED']);
				}
				
				//Доходы
				if(in_array($pcd, ['6100', '6200', '6300', '6400', '6700', '6800', '9100', '5999'])) {
					$D6000+=($rw['EC']-$rw['ED']);
				}
				
				//Расходы
				if(in_array($pcd, ['7100', '7200', '7500', '7700', '8000', '9500', '9900'])) {
					$P7890+=($rw['ED']-$rw['EC']);
				}

			}
			$V1 = $A; //Активы
			$V2 = $O3400;//Обязательства
			$V3 = $KK; //Капитал
			$V4 = $D6000-$P7890;//Строка(Д6000)+"-"+Строка(Р7890); //Доходы/Расходы
			$V5 = $V2+$V3+$V4;//КП; //Обязательства и Капитал
			$V6 = $V1-$V5; //Разница
			//$r .= $A.' '.$O3400.' '.$KK.' '.$D6000;
			$r .= '<br/>Активы='.$V1;
			$r .= '<br/>Обязательства='.$V2;
			$r .= '<br/>Капитал='.$V3;
			$r .= '<br/>Доходы/Расходы='.$V4.' '.$D6000.' '.$P7890;
			$r .= '<br/>Обязательства и Капитал='.$V5;
			$r .= '<br/>Разница='.$V6;
			return $r;
		}
	}
?>