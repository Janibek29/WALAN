<?php
	/*
	* Copyright (C) 2025 Жанибек Какимбаев
	*
	* Бұл бағдарлама GNU General Public License v3.0 (GPLv3) лицензиясы бойынша таратылады.
	* Лицензия мәтіні: https://www.gnu.org/licenses/gpl-3.0.html
	*/
	namespace mvc\dev1;
	
	class vDgfhh extends \mvc\vs\jV {
		function init() {
			$r = '';
			return $r;
		}
		
		function r() {
			$r = '';
			//$this->oC->fd['dev']
			return $r;
		}
		
		function getHTML() {
			$r = '';
			ob_start();
			?>
				<input type="text"/>
				
			<?php
			$r .= ob_get_contents();
			ob_end_clean();
			return $r;
		}
	}
?>