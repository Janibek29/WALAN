<?php
	/*
	* Copyright (C) 2025 Жанибек Какимбаев
	*
	* Бұл бағдарлама GNU General Public License v3.0 (GPLv3) лицензиясы бойынша таратылады.
	* Лицензия мәтіні: https://www.gnu.org/licenses/gpl-3.0.html
	*/
	namespace mvc\vs;
	
	class vGRID extends jV {
		var $rc;
		var $url;
		function init() {
			$r = '';
			
			return $r;
		}
		
		function r() {
			$r = '';
			$r .= '<div style="margin: 10px;">';
				if($this->oC->sd['sp']==0) {
					$r .= $this->treefms(0);
				} elseif($this->oC->sd['sp']==1) {
					$r .= $this->showDATA();
				}
			$r .= '</div>';
			return $r;
		}

		function showDATA() {
			$r = '';
			$r .= '<a href="'.$this->oC->sd['url'];
			$r .= '&sp=0&sf='.$this->oC->fp.'">[=]</a>';
			$r .= '<div>';
				
				$r .= '<div style="width: 300px;">';
					$r .= '<a href="'.$this->oC->sd['url'];
					$r .= '&'.$this->oC->ccr."savefd\"";
					$r .= '>[✓]</a>';
					$nb = '&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;';
					$r .= $nb.$nb.$nb.$nb.$nb.' <a href="'.$this->oC->sd['url'];
					$r .= '&'.$this->oC->ccr.'xfm"';
					$r .= ' >[x]</a>';
				$r .= '</div>';
				$p = $this->oC->fd['p'];
				$ftp = $this->oC->fd['ftp'];
				$sts = $this->oC->fd['sts'];
				$fpk = $this->oC->fd['fpk'];
				$fm = $this->oC->fd['fm'];

				if(is_numeric($p)) {
					if($p==0) { //rs from db
						//$r .= ' table';	
							
							
							
							
					} elseif($p>0) {//rw from db
						
					} elseif($p<0) {
					  $fp = abs($p);
						if($fp==$this->oC->fp) {
							$this->url = $this->oC->sd['url'];
							$this->url .= '&'.$this->oC->ccr;
							if(isset($this->oC->fd['trv']) && $this->oC->fd['trv']>0) {
								if($this->oC->fd['ftp']=='rs') {
									$r .= '<a href="'.$this->oC->sd['url'];
									$r .= '&'.$this->oC->ccr."clearRS&trv=".$this->oC->fd['trv']."\"";
									$r .= '>[@]</a>';
									
									
									$url1 = $this->url.'cp';
									$cp = 1; $r .= '<a href="'.$url1.'&cp='.$cp.'">[<<]</a>';
									$pc = 0;
									$rc = 0;
									if(isset($this->oC->fd['its'][$this->oC->fd['trv']]['pc'])
										&& isset($this->oC->fd['its'][$this->oC->fd['trv']]['cp'])
										&& isset($this->oC->fd['its'][$this->oC->fd['trv']]['rc'])) {
										$pc = $this->oC->fd['its'][$this->oC->fd['trv']]['pc'];
										$cp = $this->oC->fd['its'][$this->oC->fd['trv']]['cp'];
										$this->rc = $this->oC->fd['its'][$this->oC->fd['trv']]['rc'];
									}
									if($cp>1)
										$r .= '<a href="'.$url1.'&cp='.($cp-1).'">[<]</a>';
									
									$r .= "<select onchange=\"if (this.value) window.location.href='$url1&cp='+this.value\">";
									for($i = 1; $i<=$pc; $i++) {
										$s = '';
										if($i==$cp)
											$s = 'selected';
										$r .= '<option value="'.$i.'" '.$s.'>'.$i.'</option>';
									}
									$r .= '</select>';
									
									if($cp<$pc)
										$r .= '<a href="'.$url1.'&cp='.($cp+1).'">[>]</a>';
									if($pc>0)
										$r .= '<a href="'.$url1.'&cp='.$pc.'">[>>]</a>';
									if(isset($this->oC->fd['showsr'])) {
										if($this->oC->fd['showsr']) {
											$sr = 0;
											$r .= $this->showsr();
										} else {
											$sr = 1;
										}
										$r .= '<a href="'.$this->url.'sr&sr='.$sr.'">[☆]</a>';
									}
									$r .= '<a href="'.$this->url.'csv">[~]</a>';
									
									$r .= '<!--'.$this->oC->fd['its'][$this->oC->fd['trv']]['q'].'-->';
									$r .= $this->showRS($this->oC->fd['trv']);
								} elseif($this->oC->fd['ftp']=='rw') {
									$rw = $this->oC->fd['rw'];
									if($rw['dcf']!='' && isset($rw['d'][$rw['dcf']])) {
										$dc = $rw['d'][$rw['dcf']];
										$r .= '<a href="?pg=r&dc='.$dc.'">[%]</a>';
									}
									$r .= $this->showRW();
									
									//Қол қою
									if(isset($rw['dcf']) && isset($rw['d'][$rw['dcf']]) && $rw['d'][$rw['dcf']]>0) {
										$dc = $rw['d'][$rw['dcf']];
										$sgn = $rw['sgn'];
										$r .= $this->showSGN($dc, $sgn, $rw['sgr']);
									}
								}
							}
						}	
					}
				} elseif(substr($p, 0, 1)=='i' && isset($_GET['trv'])) {//addFMSshow
					$ar = explode(':', $p);
					$trv = $_GET['trv'];//$this->oC->fd['trv'];
					$fpk = $ar[1];
					$r .= '<br/>Қосу Қалпын таңдаңыз';
					$r .= $this->showFMS($trv, $fpk);
				}
			$r .= '</div>';
			return $r;
		}
		
		function showSGN($dc, $sgn, $sgr) {
			$r = '';
			$r .= '<table>';
			foreach($sgr as $rk=>$rw) {
				$r .= '<tr><td>'.$rw['DT'].'</td><td>'.$rw['TXT'].'</td><td>'.$rw['UN'].'</td></tr>';
			}
			$r .= '</table>';
			if($sgn==0) {
				$bc = "var txt=prompt('Қол қою мәтіні',''); if(txt=='') { c=false;} else {jT.p='txt='+encodeURIComponent(txt);}";
				
				$r .= $this->b('', $bc, 'sgn', "jT.a('".$this->rurl."', '');", '✍');//window.location.reload();
			}
			return $r;
		}
		
		function showSCMP($fn, $SCMP) {
			$r = '';
			$url = $this->oM->cmd.'=';
			$url .= $this->cr.$this->oM->ssd;
			$url .= $this->oC->fp.$this->oM->ssd;
			$url .= '0'.$this->oM->ssd;
			$url .= "0".$this->oM->ssd;
			$r .= "<select onchange=\"window.location.href='".$this->url."cmp&tp=1&fn=$fn&v='+this.value\">";
			$s = '';
			
			//0-Бос
			if($SCMP==0)
				$s = 'selected';
			$r .= '<option value="0" '.$s.'></option>';
			$s = '';
			
			//1-Тең
			if($SCMP==1)
				$s = 'selected';
			$r .= '<option value="1" '.$s.'>=</option>';
			$s = '';
			//2-Аралық
			if($SCMP==2)
				$s = 'selected';
			$r .= '<option value="2" '.$s.'>~|~</option>';
			$s = '';
			//3 Үлкен немесе тең
			if($SCMP==3)
				$s = 'selected';
			$r .= '<option value="3" '.$s.'>>=</option>';
			$s = '';
			//4 Кіші немесе тең
			if($SCMP==4)
				$s = 'selected';
			$r .= '<option value="4" '.$s.'><=</option>';
			$s = '';
			//5 Кездеседі
			if($SCMP==5)
				$s = 'selected';
			$r .= '<option value="5" '.$s.'>%%</option>';
			$s = '';
			//6 Кездеседі басында
			if($SCMP==6)
				$s = 'selected';
			$r .= '<option value="6" '.$s.'>^%</option>';
			$s = '';
			//7 Кездеседі аяғында
			if($SCMP==7)
				$s = 'selected';
			$r .= '<option value="7" '.$s.'>%^</option>';
			$s = '';
			//8 Тізімнен
			if($SCMP==8)
				$s = 'selected';
			$r .= '<option value="8" '.$s.'>in</option>';
			$s = '';
			$r .= '</select>';
			return $r;
		}
		
		function showsr() {
			$r = '';
			$r .= '<br/>';
			
			if(isset($this->oC->fd['its'][$this->oC->fd['trv']]['fs'])) {
				$fs = $this->oC->fd['its'][$this->oC->fd['trv']]['fs'];
				$r .= '<table>';
				foreach($fs as $fn=>$f) {
					$r .= '<tr>';
					$r .= '<td>'.$fn.'</td>';
					$SCMP = 0;
					if(isset($f['SCMP']))
						$SCMP = $f['SCMP'];
					$r .= '<td>'.$this->showSCMP($fn, $SCMP).'</td>';
					$STXT = '';
					if(isset($f['STXT']))
						$STXT = $f['STXT'];
					$r .= "<td><input type=\"text\" value=\"$STXT\"";
					$r .= " onchange=\"window.location.href='".$this->url."cmp&tp=2&fn=$fn&v='+encodeURIComponent(this.value)\"/></td>";
					$r .= '</tr>';
				}
				$r .= '</table>';
				$r .= '<a href="'.$this->url.'sh">[▶]</a>';
			}
			
			return $r;
		}
		
		function treefms($fpk) {
			$r = '';
			$r .= '<ul>';
			foreach($this->oC->sd['fms'] as $fp=>$f) {
				if(isset($f['fpk']) && $f['fpk']=='') {
					$f['fpk'] = 0;
				}
				
				if(isset($f['fpk']) && $f['fpk']==$fpk && isset($f['trv'])) {
					$tt = '';
					if(isset($f['tn']))
						$tt = $f['tn'];

					if($f['sfm']==1 
						&& $f['svd']==0) {
						$tt = '*'.$tt;
					}
					$s = '';
					if($fp==$this->oC->sd['sf']) {
						$s = 'style="font-weight: bold"';
					}
					$trv = $f['trv'];
					$a = '<a '.$s.' href="'.$this->oC->sd['url'].'&trv='.$trv;
					$a .= '&p=-'.$fp.'">'.$tt.'</a>';

					$r .= '<li>'.$a;
					$r .= $this->treefms($fp);
					$r .= '</li>';
				}
			}
			$r .= '</ul>';
			return $r;
		}

		function showFMS($trv, $fpk) {
			$r = '';
			//print_r($this->oC->fd['rwfms']);
			if(isset($this->oC->fd['rwfms'])) {
				$rs = $this->oC->fd['rwfms'];
				$r .= '<table>';
				$r .= '<tr><td>';
				$p = 'a:'.$fpk.':0';
				$r .= '<a href="'.$this->oC->sd['url'].'&trv='.$trv.'&p='.$p;
					$r .= '">[#]</a>';
				$r .= '</td></tr>';
				foreach($rs as $rk=>$rw) {
					$r .= '<tr><td>';
					$tt = $rw['TT'];
					if($tt=='') {
						$tt = $rw['NM'];
					}
					$fm = $rw['ID'];
					$p = 'a:'.$fpk.':'.$fm;
					$r .= '<a href="'.$this->oC->sd['url'].'&trv='.$trv.'&p='.$p;
					$r .= '">'.$tt.'</a>';
					$r .= '</td></tr>';
				}
			}
			$r .= '</table>';
			return $r;
		}
		
		function showILS() {
			$r = '';
			$rurl = "?".$this->oC->cvw."r".'&trv='.$this->oC->fd['trv'].'&p=-'.$this->oC->fp;
			$ra = "jT.a('$rurl', '');";
						
			$r .= "Ішкі кесте<select onchange=\"jT.a('?".$this->oC->ccr."ils', 'md='+this.value); $ra\">";
			$r .= '<option></option>';
			foreach($this->oC->fd['ils'] as $mn=>$it) {
				$s = '';
				if($it['C']==1)
					$s = 'selected';
				$fk = $it['FK'];
				$r .= '<option '.$s.' value="'.$it['MD'].'">'.$it['TT'].'</option>';
			}
			$r .= '</select>';
			return $r;
		}
		
		function showRW() {
			$r = '';
			$hdt = $this->oC->fd['hdt'];
			$hdt = str_replace('#add#', $this->getA('ins', [], $this->oC->fd['trv']), $hdt);
			//$r .= print_r($this->oC->fd['ils'], true);
			
			$r .= $hdt;
			if(isset($this->oC->fd['ils'])) {
				$r .= $this->showILS();
				foreach($this->oC->fd['ils'] as $mn=>$it) {
					if($it['C']==1) {
						$trv = $it['MD'];
						//$this->oC->fd['trv'] = $trv;//$it['FK'];
						//$r .= '<option '.$s.' value="'.$it['MD'].'">'.$mn.'.'.$fk.'</option>';
						$r .= $this->showRS($trv);
						
					}
				}
			}
			return $r;
		}

		function showRS($trv) {
			$r = '';
			//$r .= print_r($this->oC->fd['its'][$trv]['fs'], true);
			$hdt = $this->oC->fd['its'][$trv]['hdt'];
			$rc = $this->oC->fd['its'][$trv]['rc'];
			$hdt = str_replace('#tbody#', $this->getTB($trv), $hdt);
			$hdt = str_replace('#add#', $this->getA('ins', [], $trv), $hdt);
			$hdt = str_replace('#rc#', $rc, $hdt);			
			$r .= $hdt;
			return $r;
		}
	
		function getTB($trv) {
			$r ='';
			//$trv = $this->oC->fd['trv'];
			$fs = $this->oC->fd['its'][$trv]['fs'];
			$rs = $this->oC->fd['its'][$trv]['rs'];
			$rd = $this->oC->fd['its'][$trv]['rd'];
			if(isset($this->oC->fd['dbtp'])) {
				$this->oM->conn($this->oC->fd['dbtp']
					, $this->oC->fd['dbhost']
					, $this->oC->fd['dbport']
					, $this->oC->fd['dbun']
					, $this->oC->fd['dbpw']
					, $this->oC->fd['dbnm']
				);
			}
			//print_r($fs);
			$r .= '<tbody>';
			foreach($rs as $rk=>$rw) {
				$r .= '<tr>';
				
				$r .= '<td>';
					if(isset($rw['sgc']))
						$r .= $rw['sgc'];
					if(isset($rw['sgn']) && $rw['sgn']>0) {
						$r .= '✔';
						//$r .= '✍';
					}
					
					$r .= $this->getA('upd', $rw, $trv);
					if($rw['dcf']!='' && isset($rw['d'][$rw['dcf']])) {
						$dc = $rw['d'][$rw['dcf']];
						$r .= '<a href="?pg=d&dc='.$dc.'">[%]</a>';
					}
				$r .= '</td>';
				foreach($fs as $fn=>$f) {
					$r .= '<td>';
					$v = '';
					if(isset($rw['d'][$fn])) {
						$v = $rw['d'][$fn];
						$ar = explode('.', $f['H']);
						$ar1 = explode(':', $ar[1]);
						$m = '';
						if(isset($rw['m'][$fn])) {
							$m = $rw['m'][$fn];
						}
						/*
						if(isset($ar1[0]) 
							&& in_array($ar1[0], ['ac', 'ls'])
							&& is_numeric($v) && $v>0
							&& $m!='') {
							$tp = $ar1[0];
							$ftrv = $this->oM->getTRV($ar1[1]);

							$m = $this->setM($v, $ftrv, $tp, $rk);
						}
						*/
						if($m!='') {
							$v = $m;
						}
					}
					$r .= $v; 
					$r .= '</td>';
				}
				
				
				$r .= '</tr>';
			}
			
			foreach($rd as $rk=>$rw) {
				$r .= '<tr>';
				$r .= '<td><a href="?pg=g&trv='.$trv.'&p='.$rk;
				$r .= '">[*]</a>';
				//$r .= $rk.$rw['sts'].$rw['pkf'].$rw['tn'];
				$r .= '</td>';
				foreach($fs as $fn=>$f) {
					$r .= '<td>';
					$v = '';
					if(isset($rw['d'][$fn])) {
						$v = $rw['d'][$fn];
					}
					$r .= $v; 
					$r .= '</td>';
				}
				
				
				$r .= '</tr>';
			}
			$r .= '</tbody>';
			return $r;
		}

		function setM($v, $ftrv, $tp, $rk) {
			$m = '';
			
			

			$oc = $this->oC->getOC($this->oC->fp
			, $this->oC->fd['trv'], $this->oC->fd['ftp'], $rk);
			$ip = [];
			$o = new \mvc\cs\cCL($this->oM, $ip);
			$m = $o->fac($oc
 	     , $ftrv, $v, $tp, $this);
			//$m = $this->oM->getM($ftrv, $v, $tp);
			unset($o);
			//$m .= $v;
			return $m;
		}

		function getA($tp, $rw, $trv) {
			$r = '';
			//$trv = $this->oC->fd['trv'];
			$fpk = $this->oC->fp;
			
			switch($tp) {
				case 'ins':
					$p = 'i:'.$fpk;
					$tt = '[+]';
				break;
				case 'upd':
					$id = $rw['d'][$rw['pkf']];
					$p = 'e:'.$fpk.':'.$id;
					$tt = '[✍]';
				break;
			}
			
			$r .= '<a href="'.$this->oC->sd['url'].'&trv='.$trv.'&p='.$p.'">'.$tt.'</a>';
			return $r;
		}

		function fms() {
			$r = '';
			$trv = $this->oC->fd['trv'];
			$r .= '';
			foreach($this->oC->sd['fms'] as $fp=>$f) {
				$r .= '';
				$tt = '';
				if(isset($f['tn']))
					$tt = $f['tn'];
				//echo $fp;
				//echo $f['m'];
				
				if($f['sfm']==1 
					&& $f['svd']==0) {
					$tt = '*'.$tt;
				}
				$s = '';
				if($fp==$this->oC->fp) {
					$s = 'style="font-weight: bold"';
				}
				$r .= '<a '.$s.' href="'.$this->oC->sd['url'].'&trv='.$trv;
				$r .= '&p=-'.$fp.'">['.$tt.']</a>';
			}
			return $r;
		}
	}
?>
