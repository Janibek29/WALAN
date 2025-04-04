<?php
	/*
	* Copyright (C) 2025 Жанибек Какимбаев
	*
	* Бұл бағдарлама GNU General Public License v3.0 (GPLv3) лицензиясы бойынша таратылады.
	* Лицензия мәтіні: https://www.gnu.org/licenses/gpl-3.0.html
	*/
	class jCDB {
		public $oMDB;
		
		function __construct($oMDB) {
			$this->oMDB = $oMDB;
			$this->rq();
		}
		
		function rq() {
			if(isset($_POST['host']) && isset($_POST['dbnm'])) {
				echo $this->installCDB();
			}
		}
		
		function show() {
			$r = '';
			$r .= $this->showCDB();
			return $r;
		}
		
		function showCDB() {
			$r = '';
			ob_start();
			?>
				<form method="post">
					<table><tbody>
						<tr><td>host</td><td><input name="host" type="text" value="127.0.0.1"/></td></tr>
						<tr><td>un</td><td><input name="un" type="text" value="root"/></td></tr>
						<tr><td>pw</td><td><input name="pw" type="password" value="root"/></td></tr>
						<tr><td>db</td><td><input name="dbnm" type="text" value="walan"/></td></tr>
						<tr><td>drop</td><td><select name="drop"><option value="1">create</option><option value="2">dropcreate</option></select></td></tr>
						<tr><td></td><td><input type="submit" value="Create"/></td></tr>
					</tbody></table>
				</form>
			<?php
			$r .= ob_get_contents();
			ob_end_clean();
			return $r;
		}
		
		
		
		function initCDB() {
			$this->oMDB->ja['db']['host'] = $_POST['host'];
			$this->oMDB->ja['db']['un'] = $_POST['un'];
			$this->oMDB->ja['db']['pw'] = $_POST['pw'];
			$this->oMDB->ja['db']['dbnm'] = $_POST['dbnm'];
			$this->oMDB->ja['db']['drop'] = $_POST['drop'];			
		}
		
		function installCDB() {
			$r = '';
			$this->initCDB();
			
			if($this->oMDB->ja['db']['drop']==1) {
				if($this->oMDB->chdbSCF()) {
					$r .= $this->oMDB->ja['db']['dbnm'];
					$r .= ' қоймасы бар.';
					$r .= ' Басқа атауын ендіріңіз.';
				} else {
					$this->oM->qSCF("create database "
						.$this->oM->ja['db']['dbnm']
						." default character set utf8 collate utf8_general_ci;");
					$r .= $this->oM->ja['db']['dbnm'];
					$r .= ' қоймасы жасалды.';
					$this->oM->connSCF();
					$qs = $this->createSCF();
				}
			} elseif($this->oM->ja['db']['drop']==2) {
				if($this->oM->chdbSCF()) {
					$this->oM->dropdbSCF();
				}
			}
			
			$qs = [];
			$qs = array_merge($qs, $this->createMAP());
			
			$r .= print_r($qs, true);
			return $r;
		}
		
		function createMAP() {
			$qs = [];
			$q = "
				create table map (
					id int not null primary key auto_increment comment 'Кілт'
					, pd int comment 'Родитель'
					, nm1 varchar(192) comment 'Название1'
					, nm2 varchar(192) comment 'Название2'
					, tp varchar(192) comment 'ТИП'
					, dv1 varchar(192) comment 'ЗначениеПоУмолчанию1'
					, dv2 varchar(192) comment 'ЗначениеПоУмолчанию2'          
				) comment '';
			";
			$qs[] = $q;
			return $qs;
		}
	}
?>