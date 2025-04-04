<?php
	/*
	* Copyright (C) 2025 Жанибек Какимбаев
	*
	* Бұл бағдарлама GNU General Public License v3.0 (GPLv3) лицензиясы бойынша таратылады.
	* Лицензия мәтіні: https://www.gnu.org/licenses/gpl-3.0.html
	*/
	namespace mvc\vs;
	
	class vTB extends jV {
		function init() {
			$r = '';
			
			return $r;
		}
		
		function r() {
			$r = '';
			
			//$r .= $this->oC->trv;
			if($this->oC->te) {
				$r .= $this->showTB();
			} else {
				$r .= $this->showAddTB();
			}
			/*$r .= $this->oC->ip['dbtp'];
			$r .= $this->oC->ip['dbhost'];
			$r .= $this->oC->ip['dbport'];
			$r .= $this->oC->ip['dbun'];
			$r .= $this->oC->ip['dbpw'];
			$r .= $this->oC->ip['dbnm'];*/
			
			return $r;
		}
		
		function showAddTB() {
			$r = '';
			if(isset($this->oC->dbtp) && $this->oC->tn!='') {
				$r .= $this->b('', "", 'addTB', 'window.location.reload();', '[+]');
			}
			return $r;
		}
		
		function showTB() {
			$r = '';
			if(isset($this->oC->fs)) {
				$r .= $this->oC->tn;
				$r .= '<table>';
				foreach($this->oC->fs as $fn=>$f) {
					$r .= '<tr>'.'<td>'.$fn.'</td>'.'<td>'.$f['TP'].'|'.$f['RTN'].'|'.$f['RCN'].'</td>'.'</tr>';
				}
				$r .= '<tr>';
				$r .= '<td>FNM</td>';
				$r .= '<td><input id="FNM" type="text"/></td>';
				$r .= '</tr>';
				$r .= '<tr>';
				$r .= '<td>FTP</td>';
				$r .= '<td>'.$this->getFTP().'</td>';
				$r .= '</tr>';
				$r .= '<tr>';
				$r .= '<td>FWD</td>';
				$r .= '<td><input id="FWD" type="text"/></td>';
				$r .= '</tr>';
				$r .= '<tr>';
				$r .= '<td></td>';
				$r .= '<td>'.$this->b('', "var fn=document.querySelector('#FNM').value;
				var tp=document.querySelector('#FTP').value; 
				var wd=document.querySelector('#FWD').value; 
				c=confirm(fn+' '+tp+' '+wd+' өрісін қосайын бы?'); 
				jT.p='fn='+fn+'&tp='+tp+'&wd='+wd; "
				, 'addFN', '', '[+]').'</td>'; //window.location.reload();
				$r .= '</tr>';
				$r .= '</table>';
			}
			return $r;
		}
		
		function getFTP() {
			$r = '';
			$r .= '<select id="FTP">';
			$r .= '<option value=""></option>';
			$r .= '<option value="int">int</option>';
			$r .= '<option value="varchar">varchar</option>';
			$r .= '<option value="date">date</option>';
			$r .= '<option value="datetime">datetime</option>';
			$r .= '</select>';
			return $r;
		}
		
	}
?>