<?php
	/*
	* Copyright (C) 2025 Жанибек Какимбаев
	*
	* Бұл бағдарлама GNU General Public License v3.0 (GPLv3) лицензиясы бойынша таратылады.
	* Лицензия мәтіні: https://www.gnu.org/licenses/gpl-3.0.html
	*/
	namespace mvc\cs;
	class cCL extends jC {
		function init() {
			$r = '';
			return $r;
		}
		
		function rq() {
			$r = '';
			return $r;
		}
		
		function bt() {
			$r = '';
			$oc = $_POST['oc'];
			$ar = $this->getRW($oc);
			$f = $_POST['f'];
			$o = $ar['o'];
			$rk = $ar['rk'];
			if(method_exists($o, $f)) {
				$r .=  $o->{$f}();
			}
			return $r;
		}
		
		function focB() {
			$r = '';
			//$r .= print_r($_GET, true);
			//$r .= print_r($_POST, true);
			$oc = $_POST['oc'];
			$ar = $this->getRW($oc);
			$fn = $ar['fn'];
			$fs = $ar['fs'];
			$rw = $ar['rw'];
			$o = $ar['o'];
			
			$focfn = FOC.$_POST['foc'].'.php';
			if(file_exists($focfn)) {
				require_once($focfn);
			}
			
			return $r;
		}

		function fgf() {
			$r = '';
			//$r .= print_r($_GET, true);
			$oc = $_GET['oc'];
			$tp = $_GET['tp'];
			$ar = $this->getRW($oc);
			$aruf = $this->oM->uf($ar['rw']['d'][$ar['fn']]);
			
			//$r .= print_r($aruf, true);
			$pth = $aruf[$tp];
			
			$file = $pth.urldecode($_GET['fn']);
			$fn = basename($file);
			if($file!='') {
				if(file_exists($file)) {					
					header('Content-Description: File Transfer');
					header('Content-Type: application/octet-stream');
					header('Content-Disposition: attachment; filename='.$fn);
					header('Content-Transfer-Encoding: binary');
					header('Expires: 0');
					header('Cache-Control: must-revalidate');
					header('Pragma: public');
					header('Content-Length: ' . filesize($file));
					ob_clean();
					flush();
					readfile($file);
				}
			}

			return $r;
		}

		function fuf() {
			$r = '';
			//$r .= print_r($_GET, true);
			//$r .= print_r($_FILES, true);
			//$r .= print_r($_POST, true);

			

			$oc = $_GET['oc'];
			$tp = $_GET['tp'];
			$ar = $this->getRW($oc);

			$aruf = $this->oM->uf($ar['rw']['d'][$ar['fn']]);
			
			//$r .= print_r($aruf, true);
			$pth = $aruf[$tp];
			switch($tp) {
			case 'ufp':

				if($aruf['fn']==$_FILES['file0']['name']) {
					$fn = $_FILES['file0']['name'];
					$ufn = $_FILES['file0']['tmp_name'];
					$file_info = pathinfo($fn);
					if (copy($ufn, $pth.$fn)) {
					}
				}
				break;
			case 'ufu':
				foreach($_FILES as $k=>$file) {
					$fn = $file['name'];
					$ufn = $file['tmp_name'];
					$file_info = pathinfo($fn);
					if (copy($ufn, $pth.$fn)) {
					}
				}
				break;
			}
			//$this->setRW($ar['rw'], $ar['o'], $oc);
			return $r;
		}
		
		function arOC($oc) {
			$r = [];
			return $r;
		}
		
		function getRW($oc) {
			$r = [];
			$rw = [];
			$fs = [];
			$ar = explode($this->oM->ssd, $oc);
			$cn = $ar[0];
			$n = $ar[1];
			$fp = $ar[2];
			$stp = $ar[3];
			$trv = $ar[4];
			$rtp = $ar[5];
			$rk = $ar[6];
			$fn = $ar[7];
		//echo $oc;	
			$cn = str_replace($this->oM->ssc, '\\', $cn);
			$ip = [];
			$ip['n'] = $n;
			$ip['fp'] = $fp;//oc=mvc:ms:jM;1;1;sd;1;rw;1;LGS&v=
			if($cn=='mvc\\ms\\jM')
				$o = $this->oM;
			else
				$o = new $cn($this->oM, $ip);
			switch($stp) {
				case 'sd':
					switch($rtp) {
						case 'rs':
							if(isset($o->sd['its'][$trv]['fs']))
								$fs = $o->sd['its'][$trv]['fs'];
							$rw = $o->sd['its'][$trv]['rs'][$rk];
						break;
						case 'rw':
							if(isset($o->sd['fs']))
								$fs = $o->sd['fs'];
							$rw = $o->sd['rw'];
						break;
					}
					
				break;
				
				case 'fd':
					switch($rtp) {
						case 'rs':
							if(isset($o->fd['its'][$trv]['fs']))
								$fs = $o->fd['its'][$trv]['fs'];
							$rw = $o->fd['its'][$trv]['rd'][$rk];
						break;
						case 'rw':
							if(isset($o->fd['fs']))
								$fs = $o->fd['fs'];
							$rw = $o->fd['rw'];
						break;
					}
					
				break;
			}
			$r['o'] = $o;
			$r['trv'] = $trv;
			$r['fs'] = $fs;
			$r['rw'] = $rw;
			$r['fn'] = $fn;
			$r['rk'] = $rk;
			return $r;
		}
		
		function setRW($rw, $fs, $o, $oc) {
			$ar = explode($this->oM->ssd, $oc);
			$cn = $ar[0];
			$n = $ar[1];
			$fp = $ar[2];
			$stp = $ar[3];
			$trv = $ar[4];
			$rtp = $ar[5];
			$rk = $ar[6];
			$fn = $ar[7];
			
			switch($stp) {
				case 'sd':
					switch($rtp) {
						case 'rs':
							$o->sd['its'][$trv]['fs'] = $fs;
							$o->sd['its'][$trv]['rs'][$rk] = $rw;
						break;
						case 'rw':
							$o->sd['fs'] = $fs;
							$o->sd['rw'] = $rw;
						break;
					}
					
				break;
				
				case 'fd':
					switch($rtp) {
						case 'rs':
							$o->fd['its'][$trv]['fs'] = $fs;
							$o->fd['its'][$trv]['rd'][$rk] = $rw;
						break;
						case 'rw':
							$o->fd['fs'] = $fs;
							$o->fd['rw'] = $rw;
						break;
					}
					
				break;
			}
			unset($o);
		}

		function fls($oc, $ftrv, $o, $tp, $pd) {
			$ls = [];
			//$this->oM->logs(print_r($oc, true));
			$ar = explode($this->oM->ssd, $oc);
			$cn = $ar[0];
			$n = $ar[1];
			$fp = $ar[2];
			$stp = $ar[3];
			$trv = $ar[4];
			$rtp = $ar[5];
			$rk = $ar[6];
			$fn = $ar[7];

			$cn = str_replace($this->oM->ssc, '\\', $cn);
			$ip = [];
			$ip['n'] = $n;
			$ip['fp'] = $fp;
			//echo '<br/>'.$oc;
	//		$o = new $cn($this->oM, $ip);
			
			switch($stp) {
				case 'sd':
					switch($rtp) {
					case 'rs':
							if(isset($o->sd['its'][$trv]['fs'][$fn]['LS'])) {
								$ls = $o->sd['its'][$trv]['fs'][$fn]['LS'];
							} else {
								$ls = $this->oM->ls($ftrv, $tp, $pd);
								$o->sd['its'][$trv]['fs'][$fn]['LS'] = $ls;
							}
						break;
					case 'rw':
							if(isset($o->sd['fs'][$fn]['LS'])) {
								$ls = $o->sd['fs'][$fn]['LS'];
							} else {
								$ls = $this->oM->ls($ftrv, $tp, $pd);
								$o->sd['fs'][$fn]['LS'] = $ls;
							}
						break;
					}
					
				break;
				
				case 'fd':
					switch($rtp) {
					case 'rs':
							if(isset($o->fd['its'][$trv]['fs'][$fn]['LS'])) {
								$ls = $o->fd['its'][$trv]['fs'][$fn]['LS'];
							} else {
								$ls = $this->oM->ls($ftrv, $tp, $pd);
								$o->fd['its'][$trv]['fs'][$fn]['LS'] = $ls;
							}
						break;
					case 'rw':
							if(isset($o->fd['fs'][$fn]['LS'])) {
								$ls = $o->fd['fs'][$fn]['LS'];
							} else {
								$ls = $this->oM->ls($ftrv, $tp, $pd);
								$o->fd['fs'][$fn]['LS'] = $ls;
							}
						break;
					}
				break;
			}
			//unset($o);


			return $ls;
		}



		function fac($oc, $ftrv, $v, $tp, $o) {
			$m = '';
			$ar = explode($this->oM->ssd, $oc);
			$cn = $ar[0];
			$n = $ar[1];
			$fp = $ar[2];
			$stp = $ar[3];
			$trv = $ar[4];
			$rtp = $ar[5];
			$rk = $ar[6];
			$fn = $ar[7];

			$cn = str_replace($this->oM->ssc, '\\', $cn);
			$ip = [];
			$ip['n'] = $n;
			$ip['fp'] = $fp;
			
			switch($stp) {
				case 'sd':
					switch($rtp) {
					case 'rs':
							if(isset($o->sd['its'][$trv]['rs'][$rk]['m'][$fn])) {
								$m = $o->sd['its'][$trv]['rs'][$rk]['m'][$fn];
							} else {
								$m = $this->oM->getM($ftrv, $v, $tp);
								//$o->sd['its'][$trv]['rs'][$rk]['m'][$fn] = $m;
							}
						break;
					case 'rw':
							if(isset($o->sd['rw']['m'][$fn])) {
								$m = $o->sd['rw']['m'][$fn];
							} else {
								$m = $this->oM->getM($ftrv, $v, $tp);
								$o->sd['rw']['m'][$fn] = $m;
							}
						break;
					}
					
				break;
				
				case 'fd':
					switch($rtp) {
					case 'rs':
							if(isset($o->fd['its'][$trv]['rs'][$rk]['m'][$fn])) {
								$m = $o->fd['its'][$trv]['rs'][$rk]['m'][$fn];
							} else {
								$m = $this->oM->getM($ftrv, $v, $tp);
								$o->fd['its'][$trv]['rs'][$rk]['m'][$fn] = $m;
							}
						break;
					case 'rw':
							if(isset($o->fd['rw']['m'][$fn])) {
								$m = $o->fd['rw']['m'][$fn];
							} else {
								$m = $this->oM->getM($ftrv, $v, $tp);
								$o->fd['rw']['m'][$fn] = $m;
							}
						break;
					}
				break;
			}


			return $m;
		}





		function pw() {
			$r = '';
			$r .= print_r($_POST, true);
			return $r;
		}

		
		
		
		function ac() {
			$r = '';
			$oc = $_POST['oc'];
			$ftrv = $_POST['ftrv'];
			$tp = $_POST['tp'];
			$term = $_POST['term'];
			$ar = explode($this->oM->ssd, $oc);
			$cn = $ar[0];
			$n = $ar[1];
			$fp = $ar[2];
			$stp = $ar[3];
			$trv = $ar[4];
			$rtp = $ar[5];
			$rk = $ar[6];
			$fn = $ar[7];
			$cn = str_replace($this->oM->ssc, '\\', $cn);
			$ip = [];
			$ip['n'] = $n;
			$ip['fp'] = $fp;
			if($ftrv>0) {
				$o = new $cn($this->oM, $ip);
				if(isset($o->fd['dbtp'])) {
					$this->oM->conn($o->fd['dbtp']
						, $o->fd['dbhost']
						, $o->fd['dbport']
						, $o->fd['dbun']
						, $o->fd['dbpw']
						, $o->fd['dbnm']
					);
				}
				$ar = $this->oM->acq($ftrv);
				$acq = $ar['acq'];
				$kf = 'id';
				if($tp=='acd') {
					$trw1 = $this->oM->getRWS($ftrv
						, 'nm, dcf, tp, s'
					);
					$trw2 = $this->oM->getRWS($trw1['TP']
						, 'id, dbhost, dbport, un, pw, dbnm, tp'
					);
					$kf = $trw1['DCF'];
					if($ar['s']!=1) {
						$this->oM->conn($trw2['TP']
							, $trw2['DBHOST']
							, $trw2['DBPORT']
							, $trw2['UN']
							, $trw2['PW']
							, $trw2['DBNM']
						);
					}
				}
				$acq = str_replace('{kf}', $kf, $acq);
				$acq = str_replace('{pkv}', 'null', $acq);
				$acq = str_replace('{t}', $term, $acq);
				$r = $this->oM->ac($ftrv, $ar['s'], $acq, $term);
			}
			return $r;
		}

		function oc() {
			$r = '';
			//$r .= print_r($_POST, true);
			$oc = $_POST['oc'];
			$foc = $_POST['foc'];
			//$this->oM->logs($foc);
			$tp = $_POST['tp'];
			$v = $_POST['v'];
			
			
			$m = '';
			if(isset($_POST['m']))
				$m = $_POST['m'];
			
			if($tp=='ac' && $v==-1) { //Қосу
				$m = substr($m, 1);
				echo $tp.$v.$m;
			}
			
			$ar = $this->getRW($oc);
			$fn = $ar['fn'];
			$fs = $ar['fs'];
			$rw = $ar['rw'];
			$o = $ar['o'];
			if(isset($rw['ctf']) && $fn==$rw['ctf'])
				$rw['cnt'] = $v;
			if(isset($rw['sts']) && $rw['sts']!='ins')
				$rw['sts'] = 'upd';
			
			//echo $tp;
			if($tp=='txt')
				$v = base64_encode($v);
			
			
			$rw['d'][$fn] = $v;
			$rw['m'][$fn] = $m;
			
			if($foc!='r')
				$this->foc($foc, $tp, $fs, $rw, $oc);
			
			$this->setRW($rw, $fs, $o, $oc);
			return $r;	
		}


		function foc($foc, $tp, &$fs, &$rw, $oc) {
			$r = '';
			//print_r($oc);
			$ar = explode($this->oM->ssd, $oc);
			$cn = $ar[0];
			$n = $ar[1];
			$fp = $ar[2];
			$stp = $ar[3];
			$trv = $ar[4];
			$rtp = $ar[5];
			$rk = $ar[6];
			$fn = $ar[7];
			
			//$this->oM->logs($cn.' '.$foc);
			$ar = explode(':', $foc);
			$foc = $ar[0];
			if($cn=='mvc:ms:jM') {				
				$ufufoc = $foc.'.php';
				if(file_exists($ufufoc)) {
					require_once($ufufoc);
				}
			} else {
				$trw1 = $this->oM->getRWS($trv
					, 'id, nm, uf, s, tp, pkf, pdf, ttf, fmf, udf, dcf, ctf'
					.', (select tp from scf s1 where s1.id=scf.tp) ptp'
				);
				$ufufoc = '';
				if(isset($trw1['UF'])) {				
					$ar = $this->oM->uf($trw1['UF']);				
					$ufu =	$ar['ufu'];
					$ufufoc = $ufu.$foc.'.php';
				}
				//echo $ufufoc;
				if(file_exists($ufufoc)) {
					require_once($ufufoc);
				}
			}
			return $r;	
		}
	}
?>
