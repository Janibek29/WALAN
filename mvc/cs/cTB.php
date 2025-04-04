<?php
	/*
	* Copyright (C) 2025 Жанибек Какимбаев
	*
	* Бұл бағдарлама GNU General Public License v3.0 (GPLv3) лицензиясы бойынша таратылады.
	* Лицензия мәтіні: https://www.gnu.org/licenses/gpl-3.0.html
	*/
	namespace mvc\cs;
	class cTB extends jC {
		var $tn;
		var $trv;
		var $fs;
		var $te;
		var $dbtp;
		function init() {
			$r = '';
			if(!isset($this->fd['tn'])) {
				$this->fd['tn'] = $this->ip['tn'];
			}
			if(isset($this->ip['tn']))
				$this->fd['tn'] = $this->ip['tn'];
			if(!isset($this->fd['trv'])) {
				$this->fd['trv'] = $this->ip['trv'];
			}
			if(isset($this->ip['trv']))
				$this->fd['trv'] = $this->ip['trv'];
			
			$this->tn = $this->fd['tn'];
			$this->trv = $this->fd['trv'];
			//$this->trv = $this->oM->getTRV($this->tn);
			$trw1 = $this->oM->getRWS($this->trv
				, 'id, nm, uf, s, tp, pkf, pdf, ttf, fmf, udf, dcf, ctf'
				.', (select tp from scf s1 where s1.id=scf.tp) ptp'
			);
			$trw2 = $this->oM->getRWS($trw1['TP']
			, 'id, dbhost, dbport, un, pw, dbnm, tp'
			);
			if(isset($trw2['TP'])) {
				$this->dbtp = $trw2['TP'];
				$this->fd['dbtp'] = $trw2['TP'];
				$this->fd['dbhost'] = $trw2['DBHOST'];
				$this->fd['dbport'] = $trw2['DBPORT'];
				$this->fd['dbun'] = $trw2['UN'];
				$this->fd['dbpw'] = $trw2['PW'];
				$this->fd['dbnm'] = $trw2['DBNM'];
				$this->oM->conn($this->fd['dbtp']
					, $this->fd['dbhost']
					, $this->fd['dbport']
					, $this->fd['dbun']
					, $this->fd['dbpw']
					, $this->fd['dbnm']
				);
			} else {				
				$this->fd['dbtp'] = $this->oM->ja['db']['dbtp'];
				$this->fd['dbhost'] = $this->oM->ja['db']['host'];
				$this->fd['dbport'] = $this->oM->ja['db']['port'];
				$this->fd['dbun'] = $this->oM->ja['db']['un'];
				$this->fd['dbpw'] = $this->oM->ja['db']['pw'];
				$this->fd['dbnm'] = $this->oM->ja['db']['dbnm'];
				$this->oM->conn($this->fd['dbtp']
					, $this->fd['dbhost']
					, $this->fd['dbport']
					, $this->fd['dbun']
					, $this->fd['dbpw']
					, $this->fd['dbnm']
				);
			}
			
			
			
			$this->te = $this->oM->TE($this->fd['dbtp'], $this->fd['dbnm'], 'public', $this->tn);
			if($this->te) {
				$tfs = $this->oM->TFS($this->fd['dbtp'], $this->fd['dbnm'], 'public', $this->tn);
				//$rs = $this->oM->s("select * from ".$this->tn);
				$this->fs = [];
				foreach($tfs as $k=>$f) {
					$f['ID'] = $k;
					$f['NM'] = $f['CN'];
					$f['TP'] = $f['TP'];
					$f['RTN'] = $f['RTN'];
					$f['RCN'] = $f['RCN'];
					$this->fs[$f['NM']] = $f;
				}
				
				/* postgresql
				SELECT k1.table_schema,
       k1.table_name,
       k1.column_name,
       k2.table_schema AS referenced_table_schema,
       k2.table_name AS referenced_table_name,
       k2.column_name AS referenced_column_name
FROM information_schema.key_column_usage k1
JOIN information_schema.referential_constraints fk USING (constraint_schema, constraint_name)
JOIN information_schema.key_column_usage k2
  ON k2.constraint_schema = fk.unique_constraint_schema
 AND k2.constraint_name = fk.unique_constraint_name
 AND k2.ordinal_position = k1.position_in_unique_constraint;
 */
			}
			/*
			create table t5 (
				id serial primary key
				, nm varchar(192)
			)*/
			return $r;
		}
		
		function rq() {
			$r = '';
			
			return $r;
		}
		
		function addTB() {
			$r = '';
			switch($this->dbtp) {
				case $this->oM->ja['sys']['md']:
					$qs = [];
					$q = "create table ".$this->tn." (
						id int not null primary key auto_increment
						, fm int
						, dc int
						, ud int
					)";
					$qs[] = $q;
					$this->oM->qsc($qs);
				break;
				case $this->oM->ja['sys']['pg']:
					$qs = [];				
					$q = "create table ".$this->tn." (
						id serial primary key
						, fm int
						, dc int
						, ud int
					)";
					$qs[] = $q;
					$this->oM->qsc($qs);
				break;
			}
			return $r;
		}
		
		function addFN() {
			$r = '';
			if(isset($this->fd['dbtp'])) {
				$this->dbtp = $this->fd['dbtp'];
				/*$this->oM->conn($this->fd['dbtp']
					, $this->fd['dbhost']
					, $this->fd['dbport']
					, $this->fd['dbun']
					, $this->fd['dbpw']
					, $this->fd['dbnm']
				);*/
				$fn = $_POST['fn'];
				$tp = $_POST['tp'];
				$wd = $_POST['wd'];
				switch($this->dbtp) {
					case $this->oM->ja['sys']['md']:
						$qs = [];
						$q = "ALTER TABLE cars
	ADD color VARCHAR(255);
						)";
						$qs[] = $q;
						$this->oM->qsc($qs);
					break;
					case $this->oM->ja['sys']['pg']:
						$qs = [];
						if($wd!='')
							$wd = '('.$wd.')';
						$q = "alter table ".$this->tn." add ".$fn." $tp$wd";
						echo $q;
						$qs[] = $q;
						$this->oM->qsc($qs);
					break;
				}
			}
			$r .= 'Қосылды';
			return $r;
		}
	}
?>