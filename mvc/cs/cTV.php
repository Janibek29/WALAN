<?php
	/*
	* Copyright (C) 2025 Жанибек Какимбаев
	*
	* Бұл бағдарлама GNU General Public License v3.0 (GPLv3) лицензиясы бойынша таратылады.
	* Лицензия мәтіні: https://www.gnu.org/licenses/gpl-3.0.html
	*/
	namespace mvc\cs;
	
	abstract class cTV extends jC {
		public $q;
		public $qs;
		
		public $tn;
		public $fnPKF;
		public $fnPDF;
		public $fnCNT;
		public $fnNM;
		public $fnTT;
		
		public $pd;
		public $pk;
		public $rk;
		public $ism;

		function init() {
			$r = '';
			return $r;
		}

		function rq() {
			$r = '';
			if(isset($_GET[$this->pcr.'pk'])) {
				$this->pk = $_GET[$this->pcr.'pk'];
			}
			
			if(isset($_GET[$this->pcr.'rk'])) {
				$this->rk = $_GET[$this->pcr.'rk'];
				$this->pk = $this->sd['its'][$this->sd['trv']]['rs'][$this->rk]['pk'];
				
			}
			return $r;
		}
		

		function reload() {
			$r = '';
			unset($this->fm['tv']['rs']);
			return $r;
		}
		
		function save() {
			$r = '';
			$this->qs = [];
			$rs = $this->sd['its'][$this->sd['trv']]['rs'];
			$this->getSaveRSSQL($rs);
			
			//print_r($this->qs);
			$this->oM->qscSCF($this->qs);
			foreach($rs as $rk=>$rw) {
				if($rw['sts']=='ins' || $rw['sts']=='upd') {
					$rs[$rk]['sts'] = 'sel';
				}
			}
			$this->sd['its'][$this->sd['trv']]['rs'] = $rs;
			//echo 'Saved';
			$this->gotoshow = true;
			header('location: '.$this->sd['url']);
			return $r;
		}
		
		function getSaveRSSQL(&$rs) {
			foreach($rs as $rk=>$rw) {
				if($rw['sts']=='ins') {
					if($rw['d'][$this->fnPKF]=='') {
						$rw['d'][$this->fnPKF] = $this->oM->ivSCF($rw['tn']);
						$rs[$rk] = $rw;
					}
				}
			}
			//print_r($rs);	
			foreach($rs as $rk=>$rw) {
				if($rw['sts']=='ins' || $rw['sts']=='upd') {
					if(isset($rs[$rw['pk']]['d'][$this->fnPKF]))
						$rw['d'][$this->fnPDF] = $rs[$rw['pk']]['d'][$this->fnPKF];
					$this->qs[] = $this->getSaveRWSQL($rw);
					$rs[$rk] = $rw;
				}
			}
			return;
		}
		

		function getSaveRWSQL(&$rw) {
			$s = '';
			$tn = $rw['tn'];
			$pkf = $rw['pkf'];
			$pkv = $rw['d'][$pkf];
			//$sts = $this->getRWSTS($rw, $pkv);
			$sts = $rw['sts'];
			switch($sts) {
				case 'ins':
					if($pkv == '') {
						$pkv = iv($tn);
						$rw['d'][$pkf] = $pkv;
					}
					$i = 0;
					$s = 'insert into '.$tn;
					$s1 = '';
					foreach($rw['d'] as $fn=>$fv) {
                
						$i++;
						if ($i==1) {
						  $s .= "(".$fn;
						  $s1 .= '('.$this->oM->nv($fv);
						} else {
						  $s .= ",".$fn;
						  $s1 .= ','.$this->oM->nv($fv);
						}
					}
            
					$s .= ') values '.$s1.')';
					$rw['sts'] = 'upd';
				break;
				case 'upd':
					$s = 'update '.$tn.' ';
					$s1 = '';
					$i = 0;
					foreach($rw['d'] as $fn=>$fv) {
						$s1 = $fn."=".$this->oM->nv($fv);
						$i++;
						if ($i==1) 
						  $s .= "set ".$s1; 
						else 
						  $s .= ",".$s1;  
					}
					if($i>0)
					  $s .= " where $pkf=$pkv";
					else
					  $s = '';
					$rw['sts'] = 'upd';
				break;

				case 'del':
				$s = '';
				if($pkv>0)
				  $s = 'delete from '.$tn.' where '.$pkf.'='.$pkv;
				break;
			}
			return $s;
		}




		function newrw() {
			$r = '';
			//echo 'newrw';
			$pk = $_GET[$this->pcr.'pk'];
			$rs = $this->sd['its'][$this->sd['trv']]['rs'];
			$rk = $this->getMaxRK($rs);
			$trw = $rs[$rk];
			$rk++;
			$rw = $this->getNewRW($rk, $pk, $trw);
			$rw['pk'] = $pk;
			$rw['d']['NM'] = 'NEW';
			$rw['d']['TT'] = 'NEW';
			$rw['d'][$this->fnPDF] = $pk;
			$rs[$rk] = $rw;
			$this->sd['its'][$this->sd['trv']]['rs'] = $rs;
			//print_r($this->sd['its'][$this->sd['trv']]['rs']);
			$this->gotoshow = true;
			header('location: '.$this->sd['url'].'&'.$this->pcr.'pk='.$pk);
			return $r;
		}
		
		function getMaxRK($rs) {
		  $maxRK = 0;
		  if(isset($rs)) {
			if(count($rs)>0)
			$maxRK = max(array_keys($rs));
		  }
		  return $maxRK;
		}
		
		function getNewRW($rk, $pk, $trw) {
		  $rw = $trw;
		  $rw['rk'] = $rk;
		  $rw['pk'] = $pk;
		  $rw['sts'] = 'ins';
		  $rw['cnt'] = 0;
		  $rw['ctf'] = '';
		  foreach($trw['d'] as $fn=>$v) {
			$rw['d'][$fn] = '';
		  }
		  return $rw;
		}
		
		
		
		function tmpD($rw) {
			$r = '';
			$r .= "<a href=\"?pg=admin&pk=".$rw['rk']."\" style=\"text-decoration: none;\">".$rw['d'][$this->fnTT]."</a>";
			$r .= "<a href=\"?pg=admin&rk=".$rw['rk']."\" style=\"font-size: 3rem;text-decoration: none;\">✍</a>";
			return $r;
		}
		
		function tmpF($rw) {
			$r = '';
			$r .= "<a href=\"?pg=admin&rk=".$rw['rk']."\">".$rw['d'][$this->fnTT]."</a>";
			return $r;
		}
	}
?>
