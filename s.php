<?php
	/*
	* Copyright (C) 2025 Жанибек Какимбаев
	*
	* Бұл бағдарлама GNU General Public License v3.0 (GPLv3) лицензиясы бойынша таратылады.
	* Лицензия мәтіні: https://www.gnu.org/licenses/gpl-3.0.html
	*/
	$rr = '';
	$q = "select id, sdt, rip, cn, cc, rn from sss order by id desc";
	$rs = $this->oC->oM->sSCF($q);
	
	$i = 0;
	foreach($rs as $rk=>$rw) {
		$i++;
	}
	
	$rr .= '<div style="width: 350px; margin: auto; overflow: scroll;">';
	$rr .= 'Сайтты қараған аумақ '.$i;
	$rr .= '<br/>';
	$rr .= '<table>';
	foreach($rs as $rk=>$rw) {
		$rr .= '<tr><td>'.$rw['ID'].'</td><td>'.$rw['SDT'].'</td><td>'.$rw['RIP'].'</td><td>'.$rw['CC'].'</td><td>'.$rw['CN'].'</td></tr>';
	}
	$rr .= '</table>';
	$rr .= '</div>';
	echo $rr;
?>