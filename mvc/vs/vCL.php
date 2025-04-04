<?php
	/*
	* Copyright (C) 2025 Жанибек Какимбаев
	*
	* Бұл бағдарлама GNU General Public License v3.0 (GPLv3) лицензиясы бойынша таратылады.
	* Лицензия мәтіні: https://www.gnu.org/licenses/gpl-3.0.html
	*/
	namespace mvc\vs;
	
	class vCL extends jV {
		var $o;
		function init() {
			$r = '';
			//$this->ip['sd'];
			return $r;
		}
		
		function r() {
			$r = '';
			
			return $r;
		}
		
	
	
		function cl($oc, $h, $rw) {
			$r = '';
			$ar = explode('.', $h);
			$tt = '';
			$ftrv = 0;
			$foc = '';
			$pd = 0;
			$cnt = count($ar);
			if($cnt==3) {
				$fn = $ar[0];
				$tp = $ar[1];//fn
				$foc = $ar[2];
			} elseif($cnt==2) {
				$fn = $ar[0];//fn
				$tp = $ar[1];
			}
			//$r .= $tp;
			if($cnt>1) {
				$v = '';
				if(isset($rw['d'][$fn]))
					$v = htmlspecialchars($rw['d'][$fn], ENT_QUOTES);
				
				if(isset($rw['h'][$fn]))
					$tp = $rw['h'][$fn];
				//$r .= $tp;
				if(in_array(substr($tp, 0, 2), ['ac', 'ls'])) {
					$ar = explode(':', $tp);
					$tp = $ar[0];
					if(isset($ar[2]))
						$pd = $ar[2];
					if(!is_numeric($ar[1])) {
						$ftrv = $this->oM->getTRV($ar[1]);
					}
				}
				
				$oc = str_replace('fn', $fn, $oc);//actrv lstrv int date datetime varchar float
				$rk = $rw['rk'];
				$trv = $rw['trv'];
				$ar = explode($this->oM->ssd, $oc);
				$cn = $ar[0];
				$n = $ar[1];
				  $fp = $ar[2];
				  $stp = $ar[3];
				  $trv = $ar[4];
				  $rtp = $ar[5];
				$cn = str_replace($this->oM->ssc, '\\', $cn);
				$m = '';
				if(isset($rw['m'][$fn]))
					$m = $rw['m'][$fn];
				$vv = $v;
				if($m!='')
					$vv = $m;
				
				$rurl = '';
				$ra = '';
				if($foc!='') {
					$ar = explode(':', $foc);
					$rc = '';
					if(isset($ar[1])) {
						$rc = $ar[1];
					}
					if($rc!='') {
						$fp1 = 0;
						if(isset($this->o->fp))
							$fp1 = $this->o->fp;
						if(isset($this->o->oC->fp))
							$fp1 = $this->o->oC->fp;
						$rurl = "?".$this->oM->cmd."=";
						$rurl .= $this->o->vw.$this->oM->ssd.$fp1.$this->oM->ssd.$rc.'&trv='.$trv.'&p=-'.$fp1;
						$ra = "jT.a('$rurl', '');";
					}
				}
				//$r .= $tp;
				$url = "?".$this->oM->cmd."=";
				$url .= $this->oC->pcr."oc";
				//$occ = "onchange=\"jT.cn='".$this->cr;
				//$occ .= "'; jT.n='".$this->n."'; ";
				$occ = "onchange=\"";
				$occ .= "var c=true; var v=''; var m='';";
				$occ .= "{vjs} ";
				$occ .= "jT.p='oc=$oc&v='+v+'&m='+m+'&foc=$foc&tp=$tp'; ";
				$occ .= "if(c) {jT.rurl='$rurl'; jT.a('$url', jT.p); $ra} \" "; //
				$joc = str_replace('{vjs}'
					, 'v=encodeURIComponent(this.value); '
					,  $occ);
				$istp = false;
				if(isset($rw['udf']))
					$istp = in_array($fn, [$rw['pkf'],$rw['pdf'], $rw['dcf'], $rw['fmf'], $rw['udf']]);
				if($istp) {
					$r .= $v;
				} else {
					switch($tp) {
						case 'TXT':
							$r .= '<textarea '.$oc1.'>'.base64_decode($v).'</textarea>';
						break;
						case 'PD':
							//$r .= '<input type="text" value="'.$v.'" '.$oc1.'/>';
							$r .= $v;
						break;
						case 'date':
							$r .= '<input type="text" class="datepicker" value="'.$v.'" '.$joc.'/>';
						break;
						case 'datetime':
							$r .= '<input type="text" class="datetimepicker" value="'.$v.'" '.$joc.'/>';
						break;
						case 'p':
							$r .= $this->b(""
								, "let pw=prompt('$fn?', ''); if(pw === null) c=false;
									jT.p='oc=$oc&foc=$foc&pw='+encodeURIComponent(pw);"
								, "focB", '', $fn);
						break;
						case 'b':
							$r .= $this->b(""
								, "c=confirm('$fn?'); 
									jT.p='oc=$oc&foc=$foc';"
								, "focB", '', $fn);
						break;
						case 'bt':
							$r .= $this->b(""
								, "c=confirm('$fn?'); 
									jT.p='oc=$oc&f=$foc';"
								, "bt", '', $fn);
						break;
						case 'pw':
							$r .= $this->b(""
								, "c=confirm('Құпия сөзді өзгертейін ба?'); 
									jT.p='oc=$oc';"
								, "pw", '', 'pw');
						break;
						case 'tt':
							$r .= $tt;
						break;
						case 'v':
							$r .= $vv;
						break;
						
						case 'ac':
						case 'acd':
							if($ftrv>0) {
								$r .= "<input type=\"text\" class=\"ac\"";
								$r .= ' data-oc="'.$oc.'"';
								$r .= ' data-foc="'.$foc.'"';
								$r .= ' data-ftrv="'.$ftrv.'"';
								$r .= ' data-tp="'.$tp.'"';
								$r .= ' data-rurl="'.$rurl.'"';
								$r .= " value=\"".$m."\"";
								$r .= " />";
							} else {
								$r .= $m;
							}
						break;
						case 'ls':
						case 'lsd':
							if($ftrv>0) {
								$fls =$this->oC->fls($oc, $ftrv, $this->o, $tp, $pd);
								
								$joc = str_replace('{vjs}'
									, 'v=encodeURIComponent(this.value); m=encodeURIComponent(this.options[this.selectedIndex].text); '
									,  $occ);
					
								$r .= '<select '.$joc.'>';
								$r .= '<option value=""></option>';
								foreach($fls as $rk=>$rw) {
									$sl = '';
									if($v==$rw['ID'])
										$sl = 'selected';
									$r .= '<option '.$sl.' value="'.$rw['ID'].'">'.$rw['TT'].'</option>';
								}
								$r .= '</select>';
								//$r .= $pd;
							} else {
								$r .= $m;
							}
						break;
						
						case 'txt':
							$r .= '<textarea '.$joc.'>'.base64_decode($v).'</textarea>'; 
						break;
						
						


						case 'e':
							$r .= '<input type="text" value="'.$v.'" '.$joc.'/>';
						break;
						case 'bool':
							$ch = '';
							if($v>0)
								$ch = 'checked';
							$boc = str_replace('{vjs}'
								, 'var v=0; if(this.checked) v=1; '
								,  $occ);
							$r .= "<input type=\"checkbox\" $ch $boc/>";
						break;
						case 'tb':
							$ip = [];
							$ip['n'] = 1;
							$ip['fp'] = 1;
							$ip['tn'] = $v;
							$id = $rw['d'][$rw['pkf']];
							/*if(isset($this->o->fd['dbtp'])) {	
								$ip['dbtp'] = $this->o->fd['dbtp'];
								$ip['dbhost'] = $this->o->fd['dbhost'];
								$ip['dbport'] = $this->o->fd['dbport'];
								$ip['dbun'] = $this->o->fd['dbun'];
								$ip['dbpw'] = $this->o->fd['dbpw'];
								$ip['dbnm'] = $this->o->fd['dbnm'];
							}*/
							if($id>0) {
								$ip['trv'] = $id;
								$r .= showV($this->oM, '\\mvc\\vs\\vTB', $ip);
							}
						break;
						case 'uf':
							if($v!='') {
								$ar = $this->oM->uf($v);
								
								$gf = "?".$this->oM->cmd."=".$this->cr;
								$gf .= $this->oM->ssd.$this->oC->fp;
								//$gf .= $this->oM->ssd."0";
								//$gf .= $this->oM->ssd."0";
								$gf .= $this->oM->ssd."fgf&tp={utp}&oc=$oc&fn={fn}";
								
								$url = '?'.$this->oM->cmd."=".$this->cr;
								$url .= $this->oM->ssd.$this->oC->fp;
								//$url .= $this->oM->ssd."0";
								//$url .= $this->oM->ssd."0";
								$url .= $this->oM->ssd."fuf";
									
								$ocf = "onchange=\"";
								$ocf .= $this->oM->jo.".ufs('$url&oc=$oc&tp={utp}', this.files, 0, 0);\"";
								if(file_exists($ar['fnp'])) {
									
									$fgf = str_replace('{utp}', 'ufp', $gf);
									$fgf = str_replace('{fn}', $ar['fn'], $fgf);
									$r .= "<a href=\"$fgf\" target=\"_blank\">".$ar['fn']."</a>";
									
									
									
									$ocf1 = str_replace('{utp}', 'ufp', $ocf);
									
									$r .= "<input type=\"file\" $ocf1/>";
								} 
								
								if(file_exists($ar['ufu'])) {
									$ufs = glob($ar['ufu']."*.*");
									$r .= '<br/>'.$ar['ufu'];
									$r .= '<table>';
									foreach($ufs as $k=>$ufl) {
										$r .= "<tr><td>";
										$fnm = \basename($ufl);
										//$r .= $fnm;
										$fgf = str_replace('{utp}', 'ufu', $gf);
										$fgf = str_replace('{fn}', $fnm, $fgf);
										$r .= "<a href=\"$fgf\" target=\"_blank\">".$fnm."</a>";
										$r .= "</td></tr>";
									}
									$r .= "<tr><td>";
									$ocf2 = str_replace('{utp}', 'ufu', $ocf);
									$r .= "<input type=\"file\" $ocf2 multiple/>";
									$r .= "</td></tr>";
									$r .= '</table>';
								}
							}
						break;
					}
				}
			}
			return $r;
		}
	
	
	}
?>
