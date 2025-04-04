<?php
	/*
	* Copyright (C) 2025 Жанибек Какимбаев
	*
	* Бұл бағдарлама GNU General Public License v3.0 (GPLv3) лицензиясы бойынша таратылады.
	* Лицензия мәтіні: https://www.gnu.org/licenses/gpl-3.0.html
	*/
	namespace mvc\cs;
	
	class cDEV extends jC {
		public $dcsrs;
		public $isud;
		public $ud;
		public $vp;
		public $cp;
		public $tvf;
		public $tcf;
		public $tjf;
		public $vfs;
		public $uid;
		public $unm;
		public $ths;
		public $pgs;
		public $fms;
		
		function init() {
			$r = '';
			
			//if(!isset($this->fd['rs'])) {
			//	$this->setrs();
			//}
			$this->uid = $this->oM->sd['uid'];
			$this->unm = $this->oM->sd['nm'];
			$this->tvf = 'mvc/tvf.php';
			$this->tcf = 'mvc/tcf.php';
			$this->tjf = 'mvc/tjf.php';
			$this->ths = THS;
			$this->pgs = PGS;
			$this->fms = FMS;
			$this->isud = false;
			$this->mkclspathu();
			$this->rq();
			return $r;
		}
		
		function rq() {
			$r = '';
			
			//$fp = 
			if(isset($_GET[$this->pcr.'fp']) && $_GET[$this->pcr.'fp']==2 && isset($_GET['dcs'])) {
				$this->isudf($_GET['dcs']);
			}
				
			return $r;
		}
		
		function vcufs() {
			$r = '';
			$fn = urldecode($_GET['fn']);
			$s = substr($fn, 0, 1);
			//$r .= $fn;
			//$r .= print_r($_FILES, true);
			$f = $_FILES['file0'];
			$file_name = $f['name'];
			$uploadfile = $f['tmp_name'];
			$file_info = pathinfo($file_name);
			$fn1 = basename($fn);
			$fn2 = basename($file_name);
			if($fn1==$fn2) {
				$tofn = $fn;
				if (copy($uploadfile, $tofn)) {
					$r .= 'Көшірілді';
				} else {
				  $errors= error_get_last();
				  echo "COPY ERROR: ".$errors['type'];
				  echo "<br />\n".$errors['message'];
				}
			} else {
				$r .= "Сәйкес емес $fn1 $fn2";
			}
			
			return $r;
		}
		
		function gf() {
			$r = '';
			$file = urldecode($_GET['fn']);
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
		
		function vcgf() {
			$r = '';
			$fn = urldecode($_GET['fn']);
			$s = substr($fn, 0, 1);
			$p = '';
			switch($s) {
				case 'v':
					$p = $this->vp;
				break;
				case 'c':
					$p = $this->cp;
				break;
			}
			//$r .= 'gf'.print_r($_GET, true);
			$file = $p.'/'.$fn;
			//echo $file;
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
					if (ob_get_contents()) ob_end_clean();//ob_clean();
					flush();
					readfile($file);
				}
			}
			return $r;
		}
		
		function jgf() {
			$r = '';
			$fn = urldecode($_GET['fn']);
			$file = $this->ud.'/'.$fn;
			echo $file;
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
		
		function mkclspathu() {
			$this->ud = 'mvc/'.$this->unm;
			if(!file_exists($this->ud)) {
				mkdir($this->ud);
			}
			
			$this->vp = $this->ud.'/vs';
			if(!file_exists($this->vp)) {
				mkdir($this->vp);
			}
			$this->cp = $this->ud.'/cs';
			if(!file_exists($this->cp)) {
				mkdir($this->cp);
			}
			
			$this->vfs = glob($this->vp."/*.php");
		}
		
		function isudf($dcsid) {
			$r = false;
			$uid = $_SESSION['uid'];
			$q = "select id, nm, tt, sdt, txt, (select nm from scf where id=dcs.uid) unm from dcs where id=$dcsid and uid=$uid";
			$rs = $this->oM->s($q);
			$cnt = count($rs);
			if($cnt==1) {
				$this->fd['rw'] = $rs[1];
				$this->isud = true;
			}
			return $r;
		}
		
		function setrs() {
			$r = '';
			$q = 'select id, nm, tt, sdt, txt, (select nm from scf where id=dcs.uid) unm from dcs where uid='.$_SESSION['uid'];
			$this->fd['rs'] = $this->oM->sd($q);
			return $r;
		}
		
		function addcs() {
			$r = '';
			$qs = [];
			//$id = $this->oM->iv('dcs');
			$nm = $_POST['nm'];
			$nm = strtoupper(substr($nm, 0, 1)).substr($nm, 1);
			
			$cf = $this->ud.'/j'.$nm.'.php';
			$p = str_replace('/', '\\', $this->ud);
			$jCLS = file_get_contents($this->tjf);
			$jCLS = str_replace('{h}', '', $jCLS);
			
			$jCLS = str_replace('{p}', $p, $jCLS);
			$jCLS = str_replace('{nm}', $nm, $jCLS);
			file_put_contents($cf, $jCLS);
			//$this->setrs();
			return $r;
		}
		
		function addmvc() {
			$r = '';
			$qs = [];
			//$id = $this->oM->iv('dcs');
			$nm = $_POST['nm'];
			$nm = strtoupper(substr($nm, 0, 1)).substr($nm, 1);
			//$uid = $_SESSION['uid'];
			//$q = "insert into dcs (id, nm, uid) values ($id, '$nm', $uid)";
			//$r .= __FUNCTION__;
			//$r .= $_POST['nm'];
			//$qs[] = $q;
			//$this->oM->qsc($qs);
			$this->createVC($nm);
			//$this->setrs();
			return $r;
		}
		
		function createVC($nm) {
			$r = '';
			//V
			$vf = $this->vp.'/v'.$nm.'.php';
			$vCLS = file_get_contents($this->tvf);
			//copy($this->tvf, $vf);
			$vCLS = str_replace('{h}', '', $vCLS);
			$vp = str_replace('/', '\\', $this->vp);
			$vCLS = str_replace('{p}', $vp, $vCLS);
			$vCLS = str_replace('{nm}', $nm, $vCLS);
			file_put_contents($vf, $vCLS);
			
			$cf = $this->cp.'/c'.$nm.'.php';
			//copy($this->tcf, $cf);
			$cCLS = file_get_contents($this->tcf);
			$cCLS = str_replace('{h}', '', $cCLS);
			$cp = str_replace('/', '\\', $this->cp);
			$cCLS = str_replace('{p}', $cp, $cCLS);
			$cCLS = str_replace('{nm}', $nm, $cCLS);
			file_put_contents($cf, $cCLS);
			
			return $r;
		}
	}
?>