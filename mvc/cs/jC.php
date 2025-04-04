<?php
	/*
	* Copyright (C) 2025 Жанибек Какимбаев
	*
	* Бұл бағдарлама GNU General Public License v3.0 (GPLv3) лицензиясы бойынша таратылады.
	* Лицензия мәтіні: https://www.gnu.org/licenses/gpl-3.0.html
	*/
	namespace mvc\cs;
	
	abstract class jC {
		var $oM;
		var $ip;
		var $sd;
		var $n;
		var $fp;
		var $fd;
		var $cn;
		var $vw;
		var $cr;
		var $gotoshow;
		public $pcr;
		public $ccr;
		public $pvw;
		public $cvw;
		public $hcr;
		
		public $gp;
		
		abstract protected function init();
		abstract protected function rq();

		function __construct($oM, $ip) {
			$this->oM = $oM;
			
			
			$this->cn = get_class($this);
			$this->cn = str_replace('\\', $this->oM->ssc, $this->cn);

			

			//get obj
			if(isset($_GET[$this->oM->cmd])) {
				$ar = explode($this->oM->ssd, $_GET[$this->oM->cmd]);
				$cnt = count($ar);
				$cn = $ar[0];
				if($cn==$this->cn){
					$ctp = 'j';
					$pos = strpos($cn, 'cs'.SSC.'c');
					if ($pos !== false) {
							$ctp = 'c';
					}
					$pos = strpos($cn, 'vs'.SSC.'v');
					if ($pos !== false) {
							$ctp = 'v';
					}

					$ip['n'] = $ar[1];
					$ip['fp'] = $ar[2];
				}
			}	

			$this->ip = $ip;

			$this->n = 1;
			if(isset($this->ip['n']))
				$this->n = $this->ip['n'];
			$this->fp = 1;
			if(isset($this->ip['fp'])) {
				$this->fp = $this->ip['fp'];
			}
			$this->gotoshow = false;
			
			$this->setvw();			
			
			
			
			$this->sd = $this->oM->getFSD($this->vw);
			if(!isset($this->sd['its'])) {
				$this->sd = array_merge($this->sd, $this->defSD());
			}
			if(!isset($this->sd['rdk'])) {
				$this->sd['rdk'] = 0;
			}

			if(!isset($this->sd['rdtp'])) {
				$this->sd['rdtp'] = '';
			}
			
			if(!isset($this->sd['url']) && isset($this->ip['url'])) {
				$this->sd['url'] = $this->ip['url'];
			}
			
			
			
			if(isset($this->sd['url']))
				$this->hcr = $this->sd['url'].'&'.$this->pcr;
			
			$ssd = $this->oM->ssd;			
			$this->pcr = $this->cr.$ssd.$this->fp.$ssd;
			if(isset($_GET[$this->pcr.'fp'])) {
				$this->fp = $_GET[$this->pcr.'fp'];
			}
			
			$this->gp = $this->cr.$ssd;
			//echo $this->gp;
			if(isset($_GET[$this->gp.'fp'])) {
				$this->fp = $_GET[$this->gp.'fp'];
			}
			
			$this->setPCR();
			
			
			if(!isset($this->sd['fms'][$this->fp]))
				$this->sd['fms'][$this->fp] = [];//fd = form data
			$this->fd = $this->sd['fms'][$this->fp];			
			
			
			$this->init();
			$this->rq();
		}
		
		function __destruct() {
			$this->setSD();
		}
		
		function setSD() {
			if($this->fp>0) {
				$this->sd['fms'][$this->fp] = $this->fd;
				
			}
			$this->oM->setFSD($this->vw, $this->sd);
		}
		
		function setvw() {
			$this->cr = $this->cn.$this->oM->ssd.$this->n;
			$this->vw = str_replace('cs'.$this->oM->ssc.'c', 'vs'.$this->oM->ssc.'v', $this->cr);
		}
		
		/*
		function getOC($cr, $sd, $fp, $trv, $ftp, $rk) {
			return $this->oM->getOC($cr, $sd, $fp, $trv, $ftp, $rk);
		}
		
		function getHTML($hd, $cr, $fp, $sd, $trv, $ftp, $rw, $o) {
			return $this->oM->getHTML($hd, $cr, $fp, $sd, $trv, $ftp, $rw, $o);
		}
		*/
		function defSD() {
			$r = [];
			$r['fs'] = [];
			/*
				$fn = 'NM';
			$f['ID'] = 1;
			$f['NM'] = $fn;
			$f['LS'] = '';
			 $fs[$fn] = $f;
			 */
			$r['rw'] = [];
			/*
			 $rw['d']['ID'];
			 */
			$r['its'] = [];
			/*
				$it[$trv]['fs'];
				$it[$trv]['rs'][$rk][rw];
				$it[$trv]['rd'][$rk][rw];
			 */
			return $r;
		}
		
		function getURL($wp) {
			$r = '';
			if(isset($_GET)) {
				$i = 0;
				foreach($_GET as $p=>$v) {
					if($p!=$this->gp.$wp) {
						$i++;
						if($i==1)
							$r .= '?';
						if($i>1)
							$r .= '&';
						
						$r .= $p.'='.$v;
					}
				}
			}
			return $r;
		}
		
		function sfp() {
			$r = '';
			$this->ufp = '';
			if(isset($_GET)) {
				$get = array_unique($_GET);
				$i = 0;
				foreach($get as $p=>$v) {
					$pos = strpos($p, $this->vw);
					$pos1 = strpos($v, $this->vw.';r');//CKF=cls:dvs:dev1:mvc:vs:vHD;1;r
					if($pos === false && $pos1 === false && $p!='fp') {
						$i++;
						if($i==1)
							$this->ufp .= '?';
						if($i>1)
							$this->ufp .= '&';
						if(is_array($v)) {
							$this->ufp .= $p.'='.print_r($v, true);
						} else {
							$this->ufp .= $p.'='.$v;
						}
					}
				}
			}
			//$r = $this->ufp;
			return $r;
		}
		
		function setPCR() {
			$ssd = $this->oM->ssd;
			
			$this->pcr = $this->cr.$ssd.$this->fp.$ssd;
			$this->ccr = $this->oM->cmd.'='.$this->pcr;
			$this->pvw = $this->vw.$ssd.$this->fp.$ssd;
			$this->cvw = $this->oM->cmd.'='.$this->pvw;
			
			
		}

		function rdk() {
			$rk = $this->sd['rdk'];
			$rk--;
			$this->sd['rdk'] = $rk;
			return $rk;
		}
		
		function getnewrw($trw1, $fm, $fs) {
			$rw = [];
			$d = $this->oM->td($fs);
			$rw = $this->oM->trw($d);
			$rw['tn'] = $trw1['NM'];
			$rw['trv'] = $trw1['ID'];
			if(!isset($trw1['PTP']))
				$trw1['PTP'] = '';
			$rw['tp'] = $trw1['PTP'];
			if($trw1['S']==1)
				$rw['tp'] = $this->oM->ja['db']['dbtp'];
			$rw['sts'] = 'ins';
			$rw['pkf'] = strtoupper($trw1['PKF']);
			if(!isset($trw1['PDF']))
				$trw1['PDF'] = '';
			$rw['pdf'] = strtoupper($trw1['PDF']);
			if(!isset($trw1['FMF']))
				$trw1['FMF'] = '';
			$rw['fmf'] = strtoupper($trw1['FMF']);
			if($rw['fmf']!='')
				$rw['d'][$rw['fmf']] = $fm;
			
			
			if(!isset($trw1['UDF']))
				$trw1['UDF'] = '';
			$rw['udf'] = strtoupper($trw1['UDF']);
			if(!isset($trw1['DCF']))
				$trw1['DCF'] = '';
			$rw['dcf'] = strtoupper($trw1['DCF']);
			
			if($rw['udf']!='')
				$rw['d'][$rw['udf']] = $this->oM->sd['uid'];
			return $rw;
		}
		
		function addrd($trv) {
			$r = '';
			$tp = $this->sd['rdtp'];
			switch($tp) {
			case 'sd':
				//$trv = $this->sd['trv'];
				$fs = $this->sd['its'][$trv]['fs'];
				$rd = $this->sd['its'][$trv]['rd'];
				//$fm = $this->sd['its'][$trv]['fm'];
				break;
			case 'fd':
				//$trv = $this->fd['trv'];
				$fs = $this->fd['its'][$trv]['fs'];
				$rd = $this->fd['its'][$trv]['rd'];
				//$fm = $this->fd['its'][$trv]['fm'];
				break;
			}

			$trw1 = $this->oM->getRWS($trv
         , 'id, nm, uf, s, tp, pkf, pdf, ttf'
					.', (select tp from scf s1 where s1.id=scf.tp) ptp'
			);
			$rw = $this->getnewrw($trw1, $fm, $fs);
			$rk = $this->rdk();
			$rw['rk'] = $rk;
			$rd[$rk] = $rw;
			
			switch($tp) {
			case 'sd':
				$this->sd['its'][$trv]['rd'] = $rd;
				break;
			case 'fd':
				$this->fd['its'][$trv]['rd'] = $rd;
				break;
			}

			//$r = $rk;
			return $r;
		}

		function savefd() {
			$r = '';
			$trv = 0;
			if(isset($this->fd['trv']))
				$trv = $this->fd['trv'];
			
			$trw1 = $this->oM->getRWS($trv
				, 'nm, tp, s'
			);
			$trw2 = $this->oM->getRWS($trw1['TP']
				, 'id, dbhost, dbport, un, pw, dbnm, tp'
			);
			
			if($trw1['S']==1) {
				$this->oM->dbtp = $this->oM->ja['db']['dbtp'];
				$this->oM->db = $this->oM->dbSCF;
			} else {
				$this->oM->conn($trw2['TP']
					, $trw2['DBHOST']
					, $trw2['DBPORT']
					, $trw2['UN']
					, $trw2['PW']
					, $trw2['DBNM']
				);
			} 

			$p = 0;
			$qs = [];
			$p = '-'.$this->fp;
			if($this->fd['sfm']==1) {
				if($this->fd['ftp']=='rs') {
					$rd = $this->fd['its'][$this->fd['trv']]['rd'];
					$qs = $this->oM->getSaveRSSQL($rd);
					$this->fd['its'][$this->fd['trv']]['rd'] = $rd;
				} elseif($this->fd['ftp']=='rw') {
					$rw = $this->fd['rw'];
					$qs[] = $this->oM->getSaveRWSQL($rw);
					$this->fd['rw'] = $rw;
					$pkv = $rw['d'][$rw['pkf']];
					$this->fd['pkv'] = $pkv;
					$trv = $this->fd['trv'];
					$this->setSD();
					$qs = $this->saveits($qs, $this->fp, $pkv);
					$this->fd = $this->sd['fms'][$this->fp];
				}
			} else {
				if($this->fd['ftp']=='rw') {
					$trv = $this->fd['trv'];
					$fpk = $this->fd['fpk'];
					$rw = $this->fd['rw'];
					//echo 'sts='.$this->fd['rw']['sts'];
					$pfd = $this->sd['fms'][$fpk];
					if(isset($pfm->fd['its'][$trv]['rd'][$p])) {
						$pfd['its'][$trv]['rd'][$p] = $rw;
					} else {
						$pfd['its'][$trv]['rd'][$p] = $rw;
					}
					$this->sd['fms'][$fpk] = $pfd;
				}
			}
			
			//print_r($qs);
			$this->oM->qsc($qs);
			//$this->gotoshow = true;		
			header('location: '.$this->sd['url'].'&trv='.$trv.'&p='.$p);
			return $r;
		}
		
		function saveits($qs, $fp, $pkv) {
			$fd = $this->sd['fms'][$fp];			
			
			//Ішкі кестелерді сақтау
			if(isset($fd['its']))
			foreach($fd['its'] as $itrv=>$it) {
				$rd = $it['rd'];
				$fs = $it['fs'];
				$fk = '';
				$fv = 0;
				foreach($fs as $fn=>$f) {
					if(isset($f['FK']) && $f['FK']==1) {
						$fk = $fn;
						$fv = $pkv;
					}
				}
				if($fk!='') {
					foreach($rd as $rk=>$rw) {
						$rd[$rk]['d'][$fk] = $fv;
					}
				}
				$qs = array_merge($qs, $this->oM->getSaveRSSQL($rd));
				$fd['its'][$itrv]['rd'] = $rd;
				foreach($rd as $rk=>$rw) {
					$fp1 = abs($rk);
					if(isset($this->sd['fms'][$fp1])) {
						$this->sd['fms'][$fp1]['rw'] = $rw;
						$qs = array_merge($qs, $this->saveits([], $fp1, $rw['d'][$rw['pkf']]));
					}
				}
				
			}
			$this->sd['fms'][$fp] = $fd;
			return $qs;
		}
	}
?>
