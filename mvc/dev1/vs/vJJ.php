<?php
	/*
	* Copyright (C) 2025 Жанибек Какимбаев
	*
	* Бұл бағдарлама GNU General Public License v3.0 (GPLv3) лицензиясы бойынша таратылады.
	* Лицензия мәтіні: https://www.gnu.org/licenses/gpl-3.0.html
	*/
	namespace mvc\dev1\vs;
	
	class vJJ extends \mvc\vs\jV {
		function init() {
			$r = '';
			return $r;
		}
		
		function r() {
			$r = '';
			$r .= $this->b("", "c = confirm('TEST?'); ", "test", $this->rd(), 'TEST');
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