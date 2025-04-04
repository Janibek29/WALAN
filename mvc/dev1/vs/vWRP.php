<?php
	/*
	* Copyright (C) 2025 Жанибек Какимбаев
	*
	* Бұл бағдарлама GNU General Public License v3.0 (GPLv3) лицензиясы бойынша таратылады.
	* Лицензия мәтіні: https://www.gnu.org/licenses/gpl-3.0.html
	*/
	namespace mvc\dev1\vs;
	
	class vWRP extends \mvc\vs\jV {
		function init() {
			$r = '';
			return $r;
		}
		
		function r() {
			$r = '';
			$srw = $this->oM->getSRW('TABLES/WRPTASKG', 'nm');
			if(isset($srw['NM'])) {
				//$r .= print_r($srw, true);
				$r .= $this->showWRP();
			} else {
				//$r .= $this->b("", "c = confirm('TEST?'); ", "test", '', 'TEST');
				$r .= $this->b("", "c = confirm('Орнату?'); ", "iWRP", '', 'Орнату');
			}
			return $r;
		}
		
		function showWRP() {
			$r = '';
			//$r .= 'fp='.$this->oC->fp;
			$url = $this->oC->getURL('fp');
			$r .= '<a href="'.$url.'&'.$this->oC->gp.'fp=1">FP</a>';
			switch($this->oC->fp) {
				case 1:
					$ar = $this->oM->getHTML($this->getH1()
						, $this->oM->dn //cr
						, 1 //fp
						, 'sd'
						, 1 //trv
						, 'rw'
						, $this->oM->sd['rw']
						, $this->oM
					);
					$r .= $ar['hd'];
					
					$ar = $this->oM->getHTMLITS($this->getH1()
						, $this->oM->dn //cr
						, 1 //fp
						, 'sd'
						, 1 //trv
						, 'rw'
						, $this->oM->sd['rw']
						, $this->oM
					);
					$r .= $ar['hd'];
				break;
				case 2:
				break;
			}
			return $r;
		}
		
		function getH1() {
			$r = '';
			ob_start();
			?>
				<input type="text"/>
				[IT1.M]
			<?php
			$r .= ob_get_contents();
			ob_end_clean();
			return $r;
		}
		
		
	}
?>
