<?php
	/*
	* Copyright (C) 2025 Жанибек Какимбаев
	*
	* Бұл бағдарлама GNU General Public License v3.0 (GPLv3) лицензиясы бойынша таратылады.
	* Лицензия мәтіні: https://www.gnu.org/licenses/gpl-3.0.html
	*/
	//echo $fn;
	//print_r($rw);
	//print_r($rw['d']['PD']);
	//echo $this->oM->ja['scf']['us'];
	
	if($rw['d']['PD']==$this->oM->ja['scf']['us'] && isset($_POST['pw'])) {
		$un = $rw['d']['UN'];
		$this->oM->createUP12($un, urldecode($_POST['pw']));
	}
?>