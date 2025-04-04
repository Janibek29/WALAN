<?php
	/*
	* Copyright (C) 2025 Жанибек Какимбаев
	*
	* Бұл бағдарлама GNU General Public License v3.0 (GPLv3) лицензиясы бойынша таратылады.
	* Лицензия мәтіні: https://www.gnu.org/licenses/gpl-3.0.html
	*/
	namespace mvc\cs;
	class cPG extends jC {

		function init() {
			$r = '';
			return $r;
		}

		function rq() {
			global $pg;
			if(!isset($this->sd['pg'])) {
				$this->sd['pg'] = '';
			}
			$this->sd['pg'] = $pg;
			
			if(isset($_GET['pg'])) {
				$this->sd['pg'] = $_GET['pg'];
			}
		}

}
?>
