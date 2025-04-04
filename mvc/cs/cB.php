<?php
	/*
	* Copyright (C) 2025 Жанибек Какимбаев
	*
	* Бұл бағдарлама GNU General Public License v3.0 (GPLv3) лицензиясы бойынша таратылады.
	* Лицензия мәтіні: https://www.gnu.org/licenses/gpl-3.0.html
	*/
	namespace mvc\cs;
	class cB extends jC {
		function init1() {
			$r = '';
			$this->fd['trv'] = $this->oM->getSID('TABLES.ACS');
			$this->fd['sfm'] = 1;
			$this->fd['ftp'] = 'rs';
			
			if($this->fd['trv']>0) {
				$trv = $this->fd['trv'];
				$trw1 = $this->oM->getRWS($trv
					, 'id, nm, tp, s'
				);
				$trw2 = $this->oM->getRWS($trw1['TP']
					, 'id, dbhost, dbport, un, pw, dbnm, tp'
				);
				$tn = $trw1['NM'];
				if(isset($this->ip['hd'])) {
					$hd = $this->ip['hd'];
					$fs = [];
					preg_match_all('/\{(.+?)\}/', $hd, $m);
					foreach($m[1] as $k=>$h) {
						$ar = explode('.', $h);
						$fn = $ar[0];
						$f['ID'] = $k;
						$f['NM'] = $fn;
						$f['H'] = $h;						
						$fs[$fn] = $f;
					}
					if(!isset($this->fd['it'][$trv]['rd'])) {
						$w = '';				
						$ll = 0;						
						if(!isset($this->fd['it'][$trv]['cp']))
							$this->fd['it'][$trv]['cp'] = 1;
						$cp = $this->fd['it'][$trv]['cp'];
						$rc = 0;
						$pc = 0;
						/*
						if($trw1['S']==1) {
							$this->oM->connSCF();
							//$this->oM->db = $this->oM->dbSCF;
						} else {
							$this->oM->conn($trw2['TP']
								, $trw2['DBHOST']
								, $trw2['DBPORT']
								, $trw2['UN']
								, $trw2['PW']
								, $trw2['DBNM']
							);
						}
						*/
						$this->oM->s = $trw1['S'];
						$this->fd['it'][$trv]['fs'] = $fs;
						$this->fd['it'][$trv]['rs'] = $this->oM->getRS($trv, $tn, $fs, $w, $ll, $cp, $rc, $pc);
						$this->fd['it'][$trv]['rd'] = [];
						$this->fd['it'][$trv]['q'] = $this->oM->q;
						$this->fd['it'][$trv]['rc'] = $rc;
						$this->fd['it'][$trv]['pc'] = $pc;
						$this->fd['it'][$trv]['ll'] = $ll;
						
						$ftp = 'rs';
						$rw = [];
						$oc = '';
						//$this->fd['hdt'] = $this->oM->getHD($hd, $ftp, $fs, $rw, $oc);
						
						$ip = [];
						$ip['n'] = 1;
						$ip['fp'] = 1;
						$ip['url'] = $this->ip['url'];
						$oGRID = new \mvc\cs\cGRID($this->oM, $ip);
						
						$this->fd['hdt'] = $oGRID->getHD($hd, $ftp, $fs, $rw, $oc);
					}
				}
			}
			return $r;
		}
		
		function init() {
			$r = '';
			if(!isset($this->fd['trv'])) {
				$this->fd['trv'] = 1;
			}
			
			if(!isset($this->fd['rw'])) {
				$this->fd['rw']['d']['BDT'] = '';
				$this->fd['rw']['d']['EDT'] = '';
			}
			
			if(!isset($this->fd['rs'])) {
				$sdt = '2021-07-01';
				$bdt = '2021-09-16';
				$edt = '2021-09-20';
				$this->setB($sdt, $bdt, $edt);				
			}
			return $r;
		}
		
		function rq() {
			$r = '';
			
			return $r;
		}
		
		function setPERIOD() {
			$r = '';
			$sdt = '2021-07-01';
			$bdt = $this->fd['rw']['d']['BDT'];
			$edt = $this->fd['rw']['d']['EDT'];
			$this->setB($sdt, $bdt, $edt);	
			return $r;
		}
		
		function setB($sdt, $bdt, $edt) {
			$q = "
				select a.cd
					   , a.nm
					   , 0 ob
					   , t.bd
					   , t.bc
					   , t.od
					   , t.oc
				  from acs a
					   left join (select t.a
						   , ifnull(round(abs(case when sum(t.sm)>=0 then sum(t.sm) end),2),0) bd
						   , ifnull(round(abs(case when sum(t.sm)<0 then sum(t.sm) end),2),0) bc
						   , ifnull(round(sum(t.sm1),2),0) od
						   , ifnull(round(sum(t.sm2),2),0) oc
					  from (select a, cr, sm, 0 sm1, 0 sm2 from acb b where b.dt='$sdt' 
							union all
							select d, dcr, sm, 0, 0 from ace e where e.dt>='$sdt' and e.dt<'$bdt'
							union all
							select c, ccr, -sm, 0, 0 from ace e where e.dt>='$sdt' and e.dt<'$bdt'
							union all
							select d, dcr, 0, sm, 0 from ace e where e.dt>='$bdt' and e.dt<='$edt'
							union all
							select c, ccr, 0, 0, sm from ace e where e.dt>='$bdt' and e.dt<='$edt'
							) t
					  group by t.a) t on t.a=a.id
				 order by 1 
			";
			$this->fd['q'] = $q;
			$this->fd['rs'] = $this->oM->sSCF($q);
		}
	}
?>