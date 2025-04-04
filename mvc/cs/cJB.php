<?php
	/*
	* Copyright (C) 2025 Жанибек Какимбаев
	*
	* Бұл бағдарлама GNU General Public License v3.0 (GPLv3) лицензиясы бойынша таратылады.
	* Лицензия мәтіні: https://www.gnu.org/licenses/gpl-3.0.html
	*/
	namespace mvc\cs;
	class cJB extends cTV {
		public $jbpath;
		public $jf;
		public $ja;
		
		function init() {
			$r = '';
			$this->jbpath = 'jb/';
			$this->jf = $this->jbpath.'jb.json';
			if(file_exists($this->jf)) {
				$this->ja = json_decode(file_get_contents($this->jf), true);
			} else {
				$this->createjf();
			}
			
			if(!isset($this->sd['tvPKF'])) {
				$this->sd['tvPKF'] = $this->ja['pkf'];
				$this->sd['tvPDF'] = $this->ja['pdf'];
				$this->sd['tvCNT'] = $this->ja['ctf'];
				$this->sd['tvNM'] = $this->ja['nmf'];
				$this->sd['tvTT'] = $this->ja['ttf'];
				$this->sd['tvURL'] = 'V';	
			}
			$this->fnPKF = $this->sd['tvPKF'];
			$this->fnPDF = $this->sd['tvPDF'];
			$this->fnCNT = $this->sd['tvCNT'];
			$this->fnNM = $this->sd['tvNM'];
			$this->fnTT = $this->sd['tvTT'];
			
			if(!isset($this->sd['url']) && isset($this->ip['url']))
				$this->sd['url'] = $this->ip['url'];
				
			$this->sd['tvpd'] = 1;
			//$this->sd['ism'] = 1;
			$this->sd['trv'] = 1;
			if(!isset($this->sd['its'][$this->sd['trv']]['rs'])) {
				$this->sd['its'][$this->sd['trv']]['rs'] = $this->ja['rs'];
			}
			
			if(!isset($this->oM->sd['admin'])) {
				$this->sd['its'][$this->sd['trv']]['rs'] = $this->ja['rs'];
			}
			
			$this->pk = $this->sd['tvpd'];
			if(!isset($this->sd['html'])) {
				$this->sd['html'] = '';
			}
			
			if(!isset($this->ja['uf'])) {
				$this->ja['uf'] = 1;
				file_put_contents(
					$this->jf
					, json_encode($this->ja, JSON_UNESCAPED_UNICODE)
				);
			}
			
			return $r;
		}
		
		function fuf() {
			$r = '';
			//$r .= print_r($_GET, true);
			//$r .= print_r($_POST, true);
			//$r .= print_r($_FILES, true);
			if(isset($this->oM->sd['admin']) && $this->oM->sd['admin']>0) {
				$rk = $_POST['rk'];
				$rw = $this->sd['its'][$this->sd['trv']]['rs'][$rk];
				$pk = $rw['pk'];
				$nm = 'pk'.$pk.'rk'.$rk;
				$dir = $this->jbpath.$nm.'/';
				
				
				
				foreach($_FILES as $k=>$file) {
					$uf = $this->ja['uf'];
					$this->ja['uf']++;
					
					
					$fn = $file['name'];
					$ufn = $file['tmp_name'];				
					$file_info = pathinfo($fn);
					$nfn = $uf.'.'.$file_info['extension'];
					$cfn = $dir.'d'.$nfn;
					if (copy($ufn, $cfn)) {
						$r .= "\r\n".$cfn;
					}
				}
				
				file_put_contents(
					$this->jf
					, json_encode($this->ja, JSON_UNESCAPED_UNICODE)
				);
			}
			return $r;
		}
		
		function save() {
			$r = '';
			if(isset($this->oM->sd['admin']) && $this->oM->sd['admin']>0) {
				$this->ja['rs'] = $this->sd['its'][$this->sd['trv']]['rs'];
				
				file_put_contents(
					$this->jf
					, json_encode($this->ja, JSON_UNESCAPED_UNICODE)
				);
			}
			$this->gotoshow = true;
			header('location: '.$this->sd['url']);
			return $r;
		}
		
		function cf() {
			$r = '';
			//$r .= print_r($_POST, true);
			//$r .= print_r($_GET, true);
			if(isset($this->oM->sd['admin']) && $this->oM->sd['admin']>0) {
				$oc = $_POST['oc'];
				$ar = explode($this->oM->ssd, $oc);
				$rk = $ar[6];
				$ip = [];
				$oCL = new \mvc\cs\cCL($this->oM, $ip);
				$ar = $oCL->getRW($oc);
				$rw = $ar['rw'];
				
				if($rk>0) {
					$nm = 'pk'.$rw['pk'].'rk'.$rw['rk'];
					$dir = $this->jbpath.$nm.'/';
					if(!file_exists($dir)) {
						mkdir($dir);
					}
					$fn = $dir.$nm.'.html';
					if(!file_exists($fn)) {
						file_put_contents($fn, $rw['d']['NM']);
					}
				}
			}
			return $r;
		}
		
		function rq() {
			$r = '';
			if(isset($_GET[$this->pcr.'pk'])) {
				$this->pk = $_GET[$this->pcr.'pk'];
			}
			
			if(isset($_GET[$this->pcr.'rk'])) {
				$this->rk = $_GET[$this->pcr.'rk'];
				if(isset($this->sd['its'][$this->sd['trv']]['rs'][$this->rk])) {
					$rw = $this->sd['its'][$this->sd['trv']]['rs'][$this->rk];
					$this->pk = $rw['pk'];
					$rw['ctf'] = 'CT';
					$nm = 'pk'.$this->pk.'rk'.$this->rk;
					$dir = $this->jbpath.$nm.'/';
					$fn = $dir.$nm.'.html';
					if(file_exists($fn)) {
						$this->sd['hd'] = file_get_contents($fn);
						$this->sd['hd'] = str_replace('{uf}', $dir, $this->sd['hd']);
					} else {
						$this->sd['hd'] = $fn;
					}
					
					$this->sd['its'][$this->sd['trv']]['rs'][$this->rk] = $rw;
				}
			}
			return $r;
		}
		
		function createjf() {
			$r = '';
			$this->ja = [];
			$this->ja['pkf'] = 'ID';
			$this->ja['pdf'] = 'PD';
			$this->ja['ctf'] = 'CT';
			$this->ja['nmf'] = 'NM';
			$this->ja['ttf'] = 'NM';
			
			$this->ja['id'] = 0;
			$this->ja['pd'] = 1;
			
			$this->ja['id']++;
			$rw['d']['ID'] = $this->ja['id'];
			$rw['d']['PD'] = $this->ja['id'];
			$rw['d']['NM'] = '';
			$rw['d']['CT'] = 0;
			$rw['d']['N'] = 1;
			$rw['rk'] = $rw['d']['ID'];
			$rw['pk'] = 0;
			$rw['cnt'] = 0;
			$rw['trv'] = $this->sd['trv'];
			$rw['ctf'] = $this->ja['ctf'];
			$this->ja['rs'][$this->ja['id']] = $rw;
			
			$pk = $this->ja['id'];
			$this->ja['id']++;
			$rk = $this->ja['id'];
			$rw['d']['ID'] = $rk;
			$rw['d']['PD'] = $pk;
			$rw['d']['NM'] = 'Кіріс';
			$rw['d']['CT'] = 0;
			$rw['d']['N'] = 2;
			$rw['rk'] = $rk;
			$rw['pk'] = $pk;
			$rw['cnt'] = 0;
			$rw['ctf'] = $this->ja['ctf'];
			$rw['trv'] = $this->sd['trv'];
			$this->ja['rs'][$rk] = $rw;
			
			file_put_contents(
				$this->jf
				, json_encode($this->ja, JSON_UNESCAPED_UNICODE)
			);
			return $r;
		}
	}
?>