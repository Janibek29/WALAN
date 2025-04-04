<?php
	/*
	* Copyright (C) 2025 Жанибек Какимбаев
	*
	* Бұл бағдарлама GNU General Public License v3.0 (GPLv3) лицензиясы бойынша таратылады.
	* Лицензия мәтіні: https://www.gnu.org/licenses/gpl-3.0.html
	*/
	namespace mvc\cs;
	class cGRID extends jC {
		var $oc;

		function init() {
			$r = '';
			
			if(!isset($this->sd['sp'])) {
				$this->sd['sp'] = 1;
			}
			if(!isset($this->sd['sf'])) {
				$this->sd['sf'] = 1;
			}
			if(!isset($this->fd['m'])) {
				$this->fd['m'] = 0;
			}
			if(!isset($this->fd['p'])) {
				$this->fd['p'] = '';
			}
			if(!isset($this->fd['pkv'])) {
				$this->fd['pkv'] = 0;
			}
			if(!isset($this->fd['tn'])) {
				$this->fd['tn'] = '';
			}
			if(!isset($this->fd['ftp'])) {
				$this->fd['ftp'] = '';
			}
			if(!isset($this->fd['fpk'])) {
				$this->fd['fpk'] = '';
			}
			if(!isset($this->fd['fm'])) {
				$this->fd['fm'] = 0;
			}
			if(!isset($this->fd['sts'])) {
				$this->fd['sts'] = '';
			}
			if(!isset($this->fd['sfm'])) { //IS Save Form
				$this->fd['sfm'] = 0;
			}
			if(!isset($this->fd['svd'])) { //is saved
				$this->fd['svd'] = 0;
			}
			if(!isset($this->fd['dbhost'])) { //is saved
				$this->fd['dbhost'] = '';
			}
			if(!isset($this->fd['dbport'])) { //is saved
				$this->fd['dbport'] = '';
			}
			if(!isset($this->fd['dbun'])) { //is saved
				$this->fd['dbun'] = '';
			}
			if(!isset($this->fd['dbpw'])) { //is saved
				$this->fd['dbpw'] = '';
			}
			if(!isset($this->fd['dbnm'])) { //is saved
				$this->fd['dbnm'] = '';
			}
			if(!isset($this->fd['rwfms'])) { //is saved
				$this->fd['rwfms'] = '';
			}
			if(!isset($this->fd['hd'])) {
				$this->fd['hd'] = '';
			};
			if(!isset($this->fd['showsr'])) {
				$this->fd['showsr'] = false;
			}
			
			
			$this->sd['rdtp'] = 'fd';
			
			
		  return $r;
		}

		function rq() {
			$r = '';
			if(isset($_GET['m']) && $_GET['m']>0) {
				$m = $_GET['m'];
				$fp = 0;
				$trv = 0;
				$findedm = false;
				foreach($this->sd['fms'] as $fp1=>$f) {
					if(isset($f['m']) && $f['m']==$m) {
						$findedm = true;
						$fp = $fp1;
						$trv = $f['trv'];
					}
				}

				if($findedm) {
					header('location: ?pg=g&trv='.$trv.'&p=-'.$fp);
				} else {
					$this->fd = [];
					$fp = abs($this->rdk());
					
					$this->fp = $fp;
					$this->fd['m'] = $m;
					
					$trw = $this->oM->getRWS($m, 'TP');
					
					$trw1 = $this->oM->getRWS($trw['TP']
						, 'id, nm, uf, s, tp, pkf, ll, udf'
					);
					//print_r($trw1);
					$this->oM->s = $trw1['S'];
					$trv = $trw1['ID'];
					$tn = $trw1['NM'];
					$this->fd['showsr'] = false;
					$this->fd['fm'] = 0;
					$this->fd['fpk'] = 0;
					$this->fd['p'] = -$fp;
					$this->fd['trv'] = $trv;
					$this->fd['tn'] = $tn;
					$this->fd['ftp'] = 'rs';
					$this->fd['sts'] = 'sel';
					$this->fd['sfm'] = 0;
					if(!isset($this->fd['its'][$trv])) {
						$this->setRS($trw1);
					}
					$this->setSD();
					header('location: ?pg=g&trv='.$trv.'&p='.$this->fd['p']);
				}
				
			}
		
			if(isset($_GET['p'])) {
				$trv = $_GET['trv'];
				$p = $_GET['p'];
				$this->fd['p'] = $p;
				$this->sd['sp'] = 1;
				if(is_numeric($p)) {
					if($p==0) { //rs from db
						
					} elseif($p>0) {//rw from db
					
					} elseif($p<0) {
						//p болмаса шықпай тұр мәлімет oc дан кейін
						$fp = abs($p);
						if(isset($this->sd['fms'][$fp])) {
							$this->fp = $fp;
							$this->fd =$this->sd['fms'][$this->fp];
							$this->fd['p'] = $p;
							if($this->fd['ftp']=='rs') {								
								if(!isset($this->fd['its'][$trv])) {
									$trw1 = $this->oM->getRWS($trv
										, 'id, nm, uf, s, tp, pkf, ll, udf'
									);
									$this->setRS($trw1);
									$this->setSD();
								}
							} elseif($this->fd['ftp']=='rw') {
								$hd = '';
								if(isset($this->fd['hd']))
									$hd = $this->fd['hd'];
								$ftp = $this->fd['ftp'];
								$fs = $this->fd['fs'];
								$rw = $this->fd['rw'];
								$oc = $this->oM->getOC($this->cr, 'fd', $this->fp
									, $this->fd['trv'], $ftp, $rw['rk']);
								$this->fd['hdt'] = $this->getHD($hd
								, $ftp, $fs, $rw, $oc, $this->fd['trv']);
								$this->setSD();
							}
						}							
					}
				} elseif(substr($p, 0, 1)=='i') {//addFMSshow
					$this->fd['rwfms'] = $this->oM->getFMS($trv);
				} elseif(substr($p, 0, 1)=='e') {
					$ar = explode(':', $p);
					$fpk = $ar[1];
					$fm = 0;
					$pkv = $ar[2];
					$fp = 0;
					foreach($this->sd['fms'] as $fp1=>$f) {
						if($f['fpk']==$fpk && $f['ftp']=='rw'
							&& $f['trv']==$trv && $f['pkv']==$pkv
							) {
							$fp = -$fp1;
							break;
						}
					}
					if($fp==0) {
						$fp = $this->setFM($trv, $p, $fpk, $fm, $pkv);
					}
					//if($fm>0)
					header('location: ?pg=g&trv='.$trv.'&p='.$fp);
				} elseif(substr($p, 0, 1)=='a') {
					$ar = explode(':', $p);
					$fpk = $ar[1];
					$fm = $ar[2];
					$pkv = 0;
					$p = $this->setFM($trv, $p, $fpk, $fm, $pkv);
					header('location: ?pg=g&trv='.$trv.'&p='.$p);
				}
				$this->setPCR();	
			}
		
			
			
			if(isset($_GET['sp']) && isset($_GET['sf'])) {
				$this->sd['sp'] = $_GET['sp'];
				$this->sd['sf'] = $_GET['sf'];
			}

			
			return $r;
		}	
		
		function ils()  {
			$r = '';
			$md = $_POST['md'];
			foreach($this->fd['ils'] as $mn=>$it) {
				$this->fd['ils'][$mn]['C'] = 0;
				if($md==$it['MD'])
					$this->fd['ils'][$mn]['C'] = 1;
			}
			//echo $md; print_r($this->fd['ils']);
			return $r;
		}
		
		function sh()  {
			$r = '';
			if($this->fd['ftp']=='rs') {
				$trw1 = $this->oM->getRWS($this->fd['trv']
					, 'id, nm, uf, s, tp, pkf, ll, udf'
				);				
				$this->setRS($trw1);
				//echo $this->oM->q;
				header('location: ?pg=g&trv='.$this->fd['trv'].'&p=-'.$this->fp);
			}
			$this->gotoshow = true;
			return $r;
		}
		
		function cmp()  {
			$r = '';
			if($this->fd['ftp']=='rs') {
				$trv = $this->fd['trv'];
				if(isset($this->fd['its'][$this->fd['trv']]['fs']) 
				&& isset($_GET['fn']) && isset($_GET['v'])
				&& isset($_GET['tp'])) {
					$tp = $_GET['tp'];
					$fn = $_GET['fn'];
					$fs = $this->fd['its'][$this->fd['trv']]['fs'];
					switch($tp) {
						case 1:
							$fs[$fn]['SCMP'] = $_GET['v'];
						break;
						case 2:
							$fs[$fn]['STXT'] = $_GET['v'];
						break;
					}					
					$this->fd['its'][$this->fd['trv']]['fs'] = $fs;
					header('location: ?pg=g&trv='.$trv.'&p=-'.$this->fp);
				}
			}
			$this->gotoshow = true;
			return $r;
		}
		
		function csv()  {
			if(isset($this->fd['its'][$this->fd['trv']]['rs'])) {
				
				$this->oM->conn($this->fd['dbtp']
					, $this->fd['dbhost']
					, $this->fd['dbport']
					, $this->fd['dbun']
					, $this->fd['dbpw']
					, $this->fd['dbnm']
				);



				$q = $this->fd['its'][$this->fd['trv']]['q'];
				$rs = $this->oM->s($q);
				$r = '';
				foreach($rs as $rk=>$rw) {
					$j = 0;
					foreach($rw as $fn=>$v) {
						$j++;
						if($j>1)
							$r .= ';';
						$r .= '"'.$v.'"';
					}
					$r .= "\r\n";
				}
				header('Content-Description: File Transfer');
				header('Content-Type: application/octet-stream');
				header('Content-Disposition: attachment; filename=e.csv');
				header('Content-Transfer-Encoding: binary');
				header('Expires: 0');
				header('Cache-Control: must-revalidate');
				header('Pragma: public');
				header('Content-Length: ' . strlen($r));
				ob_clean();
				flush();
				
				echo $r;
			}
		}

		function sgn() {
			$r = '';
			
			///echo $_POST['txt'];
			if(isset($this->fd['rw']) && isset($_POST['txt'])) {
				
				$txt = urldecode($_POST['txt']);
				
				$rw = $this->fd['rw'];
				if(isset($rw['dcf']) && isset($rw['d'][$rw['dcf']]) && $rw['d'][$rw['dcf']]>0) {
					$dc = $rw['d'][$rw['dcf']];
					$this->oM->addSGN($dc, $rw, $txt);
					$this->fd['rw'] = $rw;
					$r .= 'Қол қойылды';
					//header('location: ?pg=g&trv='.$this->fd['trv']
						//.'&p=-'.$this->fp
					//);
				}
			}
			//exit;
			return $r;
		}
		
		function ob() {
			$r = '';
			$trv = $_GET['trv'];
			if(isset($this->fd['its'][$trv]['fs'])) {
				$fn = $_GET['fn'];
				$fs = $this->fd['its'][$trv]['fs'];
				$f = $fs[$fn];
				if(isset($f['OB'])) {
					switch($f['OB']) {
						case 1:
							$f['OB'] = 0;
						break;
						case 0:
							$f['OB'] = -1;
						break;
						case -1:
							$f['OB'] = 1;
						break;
					}
				} else {
					$f['OB'] = 1;
				}
				$fs[$fn] = $f;
				$this->fd['its'][$trv]['fs'] = $fs;
				$trw1 = $this->oM->getRWS($trv
					, 'id, nm, uf, s, tp, pkf, ll, udf'
				);
				
				$this->setRS($trw1);
				header('location: ?pg=g&trv='.$trv.'&p=-'.$this->fp);
			}
			$this->gotoshow = true;
			return $r;
		}
		
		function sr() {
			$r = '';
			$sr = $_GET['sr'];
			if($sr==0) {
				$this->fd['showsr'] = false;
			} else {
				$this->fd['showsr'] = true;
			}
			$this->gotoshow = true;
			header('location: ?pg=g&trv='.$this->fd['trv'].'&p=-'.$this->fp);
		}
		
		function cp() {
			$r = '';
			$this->gotoshow = true;
			$trv = $this->fd['trv'];
			if($this->fd['ftp']=='rs') {
				if(isset($this->fd['its'][$trv])) {
					$this->fd['its'][$trv]['cp'] = $_GET['cp'];
					$trw1 = $this->oM->getRWS($trv
						, 'id, nm, uf, s, tp, pkf, ll, udf'
					);
					$this->setRS($trw1);
					header('location: ?pg=g&trv='.$trv.'&p=-'.$this->fp);
				}
			}
		}
		
		function xfm() {
			$r = '';
			$trv = $this->fd['trv'];
			$p = '-'.$this->fp;
			if($this->fd['ftp']=='rs' || $this->fd['sfm']==1) {			
				$this->fd = [];
				unset($this->sd['fms'][$this->fp]);
				$this->fp = 0;
				$this->gotoshow = true;
				//header('location: ?pg=g');
				header('location: ?pg=g&sp=0&sf=1');
			} else {
				$fpk = $this->fd['fpk'];
				$p = '-'.$fpk;
				header('location: ?pg=g&trv='.$trv.'&p='.$p);
			}
		}
		
		function clearRS() {
			$trv = $_GET['trv'];
			unset($this->fd['its'][$trv]);
			$this->setSD();
			//$this->gotoshow = true;
			header('location: ?pg=g&trv='.$trv.'&p=-'.$this->fp);
		}
		
		function setFM($trv, $p, $fpk, &$fm, $pkv) {
			
			$sts = 'upd';
			if($pkv==0) {
				$sts = 'ins';
			}
			$pfm = $this->sd['fms'][$fpk];		
			if($pfm['ftp']=='rs') {
				$p = $this->rdk();
			} elseif($pfm['ftp']=='rw') {
				//$this->addrd($trv);
				//$p = $this->sd['rdk'];
				$p = $this->rdk();
			}
					
					
				
			$trw1 = $this->oM->getRWS($trv
				, 'id, nm, uf, s, tp, pkf, pdf, ttf, fmf, udf, dcf, ctf, its'
				.', (select tp from scf s1 where s1.id=scf.tp) ptp'
			);
			$this->fp = abs($p);
			$this->fd = [];
			$this->fd['showsr'] = false;
			$this->fd['p'] = $p;
			$this->fd['trv'] = $trv;
			$this->fd['pkv'] = $pkv;
			$this->fd['fpk'] = $fpk;
			$this->fd['fm'] = $fm;
			$this->fd['ftp'] = 'rw';	
			$this->fd['tn'] = $trw1['NM'];
			$this->fd['sts'] = 'ins';
		

			if($pfm['ftp']=='rs') {
				$this->fd['sfm'] = 1;
			} elseif($pfm['ftp']=='rw') {
				$this->fd['sfm'] = 0;
			}
			$this->fd['svd'] = 0;
			$this->oM->s = $trw1['S'];
			$this->setRW($trw1, $trw1['FMF'], $fm, $trw1['PKF']
				, $this->fd['pkv']
			);
			
			$this->setSD();
			return $p;
		}

		


		function setRW($trw1, $fmf, &$fm, $pkf, $pkv) {
			$trw2 = $this->oM->getRWS($trw1['TP']
			, 'id, dbhost, dbport, un, pw, dbnm, tp'
			);
			if(!isset($fmf))
				$fmf = '';
			$fmf = strtoupper($fmf);
			$trv = $this->fd['trv'];
			
			$tn = $trw1['NM'];
			
			
			if(isset($trw2['TP'])) {
				$tp = $trw2['TP'];
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
				$tp = $this->oM->ja['db']['dbtp'];
				$this->fd['dbtp'] = $tp;
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
			
			if($this->oM->s!=1) {
				$this->oM->dbtp = $tp;
			}
			
			$this->fd['fnp'] ='';	
			$this->fd['hdt'] = '';
			
			if($pkv>0) {//upd
				$rw = $this->oM->getRW($trv, $tn
					, $pkf, $pkv, []
				);
				$rw['sts'] = 'upd';
				$fm = 0;
				if($fmf!='')
					$fm = $rw['d'][$fmf];
				$this->fd['rw'] = $rw;
				
				if($fm>0) {
					$trw3 = $this->oM->getRWS($fm
					, 'uf'
					);
					if(isset($trw3['UF'])) {
						$ar = $this->oM->uf($trw3['UF']);						
						$this->fd['fnp'] =	$ar['fnp'];
					} else {
						$this->setTMPH($trw1, $tn, $pkf);
					}
				} else {
					$this->setTMPH($trw1, $tn, $pkf);
				}
			} elseif($pkv==0) {//ins
				if($fm>0) {
					$this->fd['fm'] = $fm;
					$trw3 = $this->oM->getRWS($fm
					, 'uf'
					);
					$ar = $this->oM->uf($trw3['UF']);
					
					$this->fd['fnp'] =	$ar['fnp'];
				} else {			
					$this->setTMPH($trw1, $tn, $pkf);
					
				}
			}
			
			if(isset($this->fd['hd']))
				$hd = $this->fd['hd'];
			if($this->fd['fnp']!='' && file_exists($this->fd['fnp'])) {				
				$hd = file_get_contents($this->fd['fnp']);
				$this->fd['hd'] = $hd;
			}
			$ar = $this->getFS($trw1, $hd, 'rw', $trv);
			$fs = $ar['fs'];
			$this->fd['fs'] = $fs;
			$this->fd['hdt'] = $ar['hdt'];
			//ITS
			if(isset($this->fd['ils'])) {
				foreach($this->fd['ils'] as $mn=>$it) {
					$trv = $it['MD'];
					$fk = $it['FK'];
					$fv = $pkv;
					if(!isset($this->fd['its'][$trv])) {					
						$trw1 = $this->oM->getRWS($trv
							, 'id, nm, uf, s, tp, pkf, ll, udf'
						);
						$this->setITS($trw1, $trv, $fk, $fv);
					}
				}
			}
		}
		
		function setTMPH($trw1, $tn, $pkf) {
			$this->oM->s("select * from $tn where $pkf=0");
			$fs = [];
			foreach($this->oM->selfs as $k=>$fn) {
				$f['ID'] = $k;
				$f['NM'] = $fn;
				$f['H'] = '';
				$fs[$fn] = $f;
			}
			$this->fd['fs'] = $fs;
			$this->fd['hd'] = $this->getTMPRWH($trw1, $fs);
			$this->fd['fnp'] = '';
		}
		
		function getTMPRWH($trw1, $fs) {
			$r = '';
			$r .= '<table>';
			foreach($fs as $fn=>$f) {
				$r .= '<tr>';
				$r .= '<td>'.$fn.'</td><td>{'.$fn.'.e}</td>';
				$r .= '</tr>';
			}
			$r .= '</table>';
			if(isset($trw1['ITS'])) {
				$r .= $trw1['ITS'];				
			}
			return $r;
		}
		
		function getTMPRSH($fs) {
			$r = '';
			$r .= '<table>';
			$r .= '<thead>';
			$r .= '<tr>';
			$r .= '<th>#add#</th>';
			foreach($fs as $fn=>$f) {				
				$r .= '<th>'.$fn.'</th>';				
			}
			$r .= '</tr>';
			$r .= '<tr>';
			$r .= '<th>#rc#</th>';
			foreach($fs as $fn=>$f) {
				$r .= '<th>{'.$fn.'.e}</th>';				
			}
			$r .= '</tr>';
			$r .= '</thead>';
			$r .= '#tbody#';
			$r .= '</table>';
			return $r;
		}
		
		function setRS($trw1) {			
			$trw2 = $this->oM->getRWS($trw1['TP']
			, 'id, dbhost, dbport, un, pw, dbnm, tp'
			);
			if(isset($trw2['TP'])) {
				$tp = $trw2['TP'];
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
				$tp = $this->oM->ja['db']['dbtp'];
				$this->fd['dbtp'] = $tp;
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
			if($this->oM->s!=1) {
				$this->oM->dbtp = $tp;
			}
			
			$trv = $this->fd['trv'];
			$this->setITS($trw1, $trv, '', 'null');
		}

		function setITS($trw1, $trv, $fk, $fv) {
			$tn = $trw1['NM'];
			$pkf = $trw1['PKF'];
			$fnp = '';
			if(isset($trw1['UF'])) {				
				$ar = $this->oM->uf($trw1['UF']);				
				$fnp =	$ar['fnp'];
			}
			
			if(file_exists($fnp)) {
				$this->fd['its'][$trv]['fnp'] = $fnp;
			} else {
				$this->oM->s("select * from $tn where $pkf=0");
				$fs = [];
				foreach($this->oM->selfs as $k=>$fn) {
					$f['ID'] = $k;
					$f['NM'] = $fn;
					$f['H'] = '';
					$fs[$fn] = $f;
				}
				$this->fd['its'][$trv]['fnp'] = '';
				$this->fd['its'][$trv]['hd'] = $this->getTMPRSH($fs);
			}
			
			if(isset($this->fd['its'][$trv]['fs'])) {
				$fs = $this->fd['its'][$trv]['fs'];				
			} else {
				if(isset($this->fd['its'][$trv]['hd']))
					$hd = $this->fd['its'][$trv]['hd'];
				if($fnp!='' && file_exists($fnp)) {				
					$hd = file_get_contents($fnp);
					$this->fd['its'][$trv]['hd'] = $hd;
				}
				$ar = $this->getFS($trw1, $hd, 'rs', $trv);
				$fs = $ar['fs'];				
				$this->fd['its'][$trv]['fs'] = $fs;
				$this->fd['its'][$trv]['hdt'] = $ar['hdt'];
			}
			
			if($trw1['UDF']!='') {
				$fs[$trw1['UDF']]['SCMP'] = 1;
				$fs[$trw1['UDF']]['STXT'] = $this->oM->sd['uid'];
			}
			if($fk!='') {
				$fs[$fk]['SCMP'] = 1;
				$fs[$fk]['STXT'] = $fv;
				$fs[$fk]['FK'] = 1;
				$fs[$fk]['H'] = $fk.'.v';
				//$this->fd['its'][$trv]['hd'] = $this->getTMPRSH($fs);
			}
			$this->fd['its'][$trv]['fs'] = $fs;
			$w = $this->oM->getW($fs);
			
			$ll = $trw1['LL'];
			$this->fd['its'][$trv]['ll'] = $ll;
			if(!isset($this->fd['its'][$trv]['cp']))
				$this->fd['its'][$trv]['cp'] = 1;
			$cp = $this->fd['its'][$trv]['cp'];
			$rc = 0;
			$pc = 0;
			$this->fd['its'][$trv]['rs'] = $this->oM->getRS($trv, $tn, $fs, $w, $ll, $cp, $rc, $pc);			
			$this->fd['its'][$trv]['q'] = $this->oM->q;
			$this->fd['its'][$trv]['rc'] = $rc;
			$this->fd['its'][$trv]['pc'] = $pc;
			
			if(!isset($this->fd['its'][$trv]['rd']))
				$this->fd['its'][$trv]['rd'] = [];
		}
		
		
		function getFS($trw1, $hd, $ftp, $trv) {
			$r = [];
			$fs = [];
			//$this->fd['fs'] = $fs;
			//$ftp = $this->fd['ftp'];
			/*if(isset($this->fd['hd']))
				$hd = $this->fd['hd'];
			if($this->fd['fnp']!='' && file_exists($this->fd['fnp'])) {				
				$hd = file_get_contents($this->fd['fnp']);
				$this->fd['hd'] = $hd;
			}*/
			preg_match_all('/\{(.+?)\}/', $hd, $ms);
			$rw = [];
			$rk = 0;
			
			$oc = $this->oM->getOC($this->cr, 'fd', $this->fp, $trv, $ftp, $rk);
			
			
			$pkv = 0;
			if(isset($this->fd['pkv']))
				$pkv = $this->fd['pkv'];
			
			$i = 0;
			foreach($ms[1] as $k=>$h) {
				$i++;
				$ar = explode('.', $h);
				$f = [];
				$f['ID'] = $i;
				$f['H'] = $h;
				$f['NM'] = $ar[0];
				$fs[$f['NM']] = $f;
			}	
			//$this->fd['fs'] = $fs;

			if($ftp=='rw') { 
				if($pkv==0) {
					$fm = $this->fd['fm'];
					$rw = $this->getnewrw($trw1, $fm, $fs);
					
					
					if($this->oM->s==1) {
						$rw['tp'] = $this->oM->ja['sys']['md'];
					}
					$this->fd['rw'] = $rw;
				} elseif($pkv>0) {
					$rw = $this->fd['rw'];
					$this->oM->setM($fs, $rw);
					$this->fd['rw'] = $rw;
				}
			}
			//$this->oM->logs($hd);
			$r['fs'] = $fs;
			$r['hdt'] = $this->getHD($hd, $ftp, $fs, $rw, $oc, $trv);
			
			return $r;
		}

		function getHD($hd, $ftp, $fs, $rw, $oc, $trv) {
			foreach($fs as $fn=>$f) {
				if($ftp=='rs') {
					$this->setPCR();
					$url = $this->sd['url'].'&'.$this->ccr;					
					$d = '<a href="'.$url.'ob&trv='.$trv.'&fn='.$fn.'">'.$f['ID'].'</a>';
					$hd = str_replace('{'.$f['H'].'}', $d, $hd);
				} elseif($ftp=='rw') {
					$ip = [];
					$oCL = new \mvc\vs\vCL($this->oM, $ip);
					$oCL->o = $this;
					$tg = $oCL->cl($oc, $f['H'], $rw);
					unset($oCL);
					$hd = str_replace('{'.$f['H'].'}', $tg, $hd);
				}
			}
			
			//ITS
			preg_match_all('/\[(.+?)\]/', $hd, $ms);
			foreach($ms[1] as $k=>$h) {
				if(!isset($this->fd['ils'])) {
					$ar = explode(',', $h);
					foreach($ar as $k1=>$a) {
						$ar1 = explode('.', $a);
						$il = [];
						$mn = trim($ar1[0]);
						$il['MD'] = $this->oM->getTRV($mn);
						$il['MN'] = $mn;
						$trw1 = $this->oM->getRWS($il['MD']
							, 'tt'
						);
						$il['TT'] = $trw1['TT'];
						$il['FK'] = $ar1[1];
						$il['C'] = 0;
						if($k1==0)
							$il['C'] = 1;
						$this->fd['ils'][$mn] = $il;
					}
				}
				$hd = str_replace('['.$h.']', '', $hd);
			}
			return $hd;
		}

		
		
		
		
	}
?>
