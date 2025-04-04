<?php
	/*
	* Copyright (C) 2025 Жанибек Какимбаев
	*
	* Бұл бағдарлама GNU General Public License v3.0 (GPLv3) лицензиясы бойынша таратылады.
	* Лицензия мәтіні: https://www.gnu.org/licenses/gpl-3.0.html
	*/
	namespace mvc\dev1\cs;
	
	class cWRP extends \mvc\cs\jC {		
		function init() {
			$r = '';			
			return $r;
		}
		
		function rq() {
			$r = '';			
			return $r;
		}
		

		function iWRP() {
			$r = '';
			
			
			//Шаралар тақырыбы
			$tn = 'wrptaskg';
			$q = "
			create table $tn (
				id int not null primary key auto_increment comment 'Кілт'
				, ar int comment 'author'
				, sb varchar(192) comment 'Тема'
				, msg text comment 'Сообщение'
				, sdt timestamp default current_timestamp comment 'Дата'
				, index (ar), foreign key (ar) references scf(id)
			) comment 'Шаралар тақырыбы';
			";
			$this->oM->cSCFSQLT($q, $this->oM->ja['db']['dbnm'], $tn);
			$this->oM->GT(strtoupper($tn), '[WRPTASKS.G]');
			
			//Шаралар
			$tn = 'wrptasks';
			$q = "
			create table $tn (
				id int not null primary key auto_increment comment 'Кілт'
				, g int not null comment 'Тақырыбы'
				, ar int comment 'author'
				, sb varchar(192) comment 'Тема'
				, msg text comment 'Сообщение'
				, sdt timestamp default current_timestamp comment 'Дата'
				, index (g), foreign key (g) references wrptaskg(id)
				, index (ar), foreign key (ar) references scf(id)
			) comment 'Шаралар';
			";
			$this->oM->cSCFSQLT($q, $this->oM->ja['db']['dbnm'], $tn);
			$this->oM->GT(strtoupper($tn), '');
			$this->oM->createTF(strtoupper($tn), '');
			$c = ob_get_contents();
			ob_end_clean();
			
			
			if($c!='') {
				$r .= $c;				
			} else {
				$r .= 'Орнатылды';
			}
			return $r;
		}

		function test() {
			$r = '';
			$r .= 'test';
			return $r;
		}
		
	}
?>
