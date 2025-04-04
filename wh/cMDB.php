<?php
	/*
	* Copyright (C) 2025 Жанибек Какимбаев
	*
	* Бұл бағдарлама GNU General Public License v3.0 (GPLv3) лицензиясы бойынша таратылады.
	* Лицензия мәтіні: https://www.gnu.org/licenses/gpl-3.0.html
	*/
	class cMDB {
		var $host;
		var $un;
		var $pw;
		var $db;
		var $mc;
		public $jf;
		public $ja;
		
		function __construct() {
			
		}
		
		function dropdb() {
			$this->conn();
			$this->qdb($this->ja['db']['dbtp']
				, $this->dbSCF
				, "drop database ".$this->ja['db']['dbnm']
			);
		}


		function chdb() {
			$finded = false;

			$this->conn();
			$rs = $this->sel("show databases");
			//print_r($rs);
			foreach($rs as $rk=>$rw) {
				if($rw['DATABASE']==$this->ja['db']['dbnm']) {
					$finded = true;
					break;
				}
			}


			return $finded;
		}
			
		function conn() {
			$r = '';
			$this->mc = new mysqli($this->ja['db']['host'], $this->ja['db']['un'], $this->ja['db']['pw'], $this->ja['db']['dbnm']);
			if ($this->mc->connect_errno) {
				$r .= $this->mc->connect_error;

			} else {
				$this->mc->query("SET AUTOCOMMIT=0");
				$this->mc->query("set names utf8");
				$this->mc->query("set character_set_client='utf8'");
				$this->mc->query("set character_set_results='utf8'");
				$this->mc->query("set collation_connection='utf8_general_ci'");
			}
			return $r;
		}

		function sel($q) {
			$r = array();
			$result = $this->mc->query($q);

			try {
				if($result){
					$j = 0;

					$finfo = mysqli_fetch_fields($result);
					$finfo = $result->fetch_fields();
					while ($row = $result->fetch_array(MYSQLI_ASSOC)){
						$j++;
						foreach ($finfo as $val) {
							$r[$j][strtoupper($val->name)] = $row[$val->name];
						}

					}
					$result->close();
					//$this->db->next_result();
				} else {
					echo($this->mc->error);
					throw new Exception($q);
				}
			} catch (Exception $ex) {
				echo $q;
				echo $ex->getMessage();
				throw new Exception($q);
			}

			return $r;
		}
		
		function qry($q) {
			if ($this->mc->query($q) === false) {
				printf($this->mc->error."\n");
				$this->mc->query('rollback');
				throw new Exception($q);

			}
		}


		function cmt() {
			$this->qry('commit');
		}
	}
?>