<?php
	/*
	* Copyright (C) 2025 Жанибек Какимбаев
	*
	* Бұл бағдарлама GNU General Public License v3.0 (GPLv3) лицензиясы бойынша таратылады.
	* Лицензия мәтіні: https://www.gnu.org/licenses/gpl-3.0.html
	*/
	namespace mvc\cs;
	
	class cA extends jC {
		
		function init() {
			$r = '';
			return $r;
		}
		
		function rq() {
			global $lurl;
			if(isset($_GET['f'])) {
				$this->fm['f'] = $_GET['f'];
				//header('location: ?pg=a');
			}
			
			if(isset($_POST['f'])) {
				$this->fm['f'] = $_POST['f'];
			}
			
			if(isset($_GET['gid']) && $_GET['gid']>0) {
				$this->gid($_GET['gid']);
				//header('Location: '.$lurl.'?pg=a');
			}
			
			if($this->oM->sd['uid']==0 && isset($_POST['btnlogin'])) {
				if(isset($_POST['ph']) && isset($_POST['pw'])) {
					$pc = $_POST['pc'];
					$pc = str_replace('+', '', $pc);
					$un = $pc.$_POST['ph'];
					$pw = $_POST['pw'];
					$ar = $this->a($un, $pw);
					//print_r($ar);
					$cnt = count($ar);
					if($cnt>0) {
						unset($_POST['ph']);
						unset($_POST['pw']);
						$this->setu($ar);
						$this->oM->sd['pw'] = $pw;
						ob_clean();
						header('location: ?pg=a');
					} else {
						echo 'Қолданушы жоқ немесе Құпия сөз дұрыс емес';
					}
				}
			}
		}
		
		function setCSR() {
			$fn = $_FILES['file0']['name'];
			$ufn = $_FILES['file0']['tmp_name'];
			$pw = $_POST['pw'];
			$crt = file_get_contents($ufn);
			unlink($ufn);
			
			
			if (openssl_pkcs12_read($crt, $certs, $pw)) {
				$cert = $certs['cert'];
				$certDetails = openssl_x509_parse($cert);
				$subject = $certDetails['subject'];
				$CN = '';
				if (isset($subject['CN'])) {
					$CN = $subject['CN'];
				}
				
				if($CN==$this->oM->sd['un'])
					$this->oM->sd['crt'] = $crt;
			}
			
			/*$file_info = pathinfo($fn);
			if (copy($ufn, $pth.$fn)) {
			}*/
		}
		
		function createUP12() {
			if($this->oM->sd['uid']>0) {
				$un = $this->oM->sd['un'];
				$pw = urldecode($_POST['pw']);
				$this->oM->createUP12($un, $pw);
			}
		}
		
		function getUP12() {
			$r = '';
			if($this->oM->sd['uid']>0) {
				$file = KYS.$this->oM->sd['un'].'.p12';
				$fn = basename($file);
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
		
		function pw() {
			$r = '';
			$pw = $_POST['pw'];
			
			if($pw=='') {
				$pw = gpw(6);
			}
			
			$this->oM->setUPW($this->oM->sd['uid'], $pw);
			
			file_put_contents('pw.log', "\r\n".date("Y-m-d h:i:s").' '.$this->oM->sd['uid'].' '.$this->oM->sd['un'].' '.$pw, FILE_APPEND);
			
			$r .= $pw;
			
			return $r;
		}
		
		function sexit() {
			$r = '';
			unset($_SESSION);
			session_destroy();
			$this->oM->sd['uid'] = 0;
			//echo '<a href="?pg=main">Open</a>';
			//$r .= $this->ofm();
			return $r;
		}	
	
		function ofm() {
			$r = '';
			ob_start();
			?>
			<!DOCTYPE html>
			<html lang="ru">
			   <head>
				  <meta charset="UTF-8">
				  <meta name="viewport" content="width=device-width, ini
				  <title>HTML Document</title>
				  <style>
					.cd {
					  margin-left: auto;
					  margin-right: auto;
					  width:400px;
					}
				  </style>
			   </head>
			   <body>
				<div class="cd">
				  <a href="?pg=main">Open</a>
				</div>
			   </body>
			</html>
			<?php
			$r .= ob_get_contents();
			ob_end_clean();
			return $r;
		}

		function acn($cn) {
			$r = '';
			$q = "
				select scf.pd, us.un, us.pw
                       , (select nm from scf s where s.id=scf.pd) pnm
					   , scf.nm snm
			           , us.id, us.nm, us.tt
				  from scf us
					     , scf
				 where us.pd = ".$this->oM->ja['scf']['us']."
				   and us.un = '$cn'
				   and scf.tp = us.id
			"; //
			$rs = $this->oM->sSCF($q);
			$cnt = count($rs);
			if($cnt==1) {
				$ar = $this->aset($rs);
				$cnt = count($ar);
				if($cnt>0) {
					$this->setu($ar);
				}
			} else {
				//echo 'Қолданушы жоқ';
				//echo $q.' cnt='.$cnt;
			}
			return $r;
		}
		
		function a($un, $pw) {
			$r = array();
			//$hpw = password_hash($pw, PASSWORD_DEFAULT);
			$q = "
				select scf.pd, us.un, us.pw
                       , (select nm from scf s where s.id=scf.pd) pnm
					   , scf.nm snm
			           , us.id, us.nm, us.tt
				  from scf us
					     , scf
				 where us.pd = ".$this->oM->ja['scf']['us']."
				   and us.un = '$un'
				   and scf.tp = us.id
			";
			
			//echo $q;
			$rs = $this->oM->sSCF($q);
			if(count($rs)>0) {
				if(password_verify($pw, $rs[1]['PW'])) { //BCRYPT
				//if (crypt($pw, $rs[1]['PW']) === $rs[1]['PW']) { //SHA512-CRYPT
					$r = $this->aset($rs);
				}
			}
			
			
			return $r;
		}
		
		function aset($rs) {
			$r = array();

			$r['uid'] = $rs[1]['ID'];
			$r['nm'] = $rs[1]['NM'];
			$r['tt'] = $rs[1]['TT'];
			$r['un'] = $rs[1]['UN'];
			$r['gs'] = '';
			foreach($rs as $rk=>$rw) {
			  if($rk>1)
					$r['gs'] .= ',';
			  $r['gs'] .= $rw['PD'];
			}

			return $r;
		}
		
		function setu($ar) {
			$this->oM->sd['uid'] = $ar['uid'];
			$this->oM->updateUID();
			$this->oM->sd['gs'] = $ar['gs'];
			$this->oM->sd['gid'] = '';
			$this->oM->sd['gc'] = '';
			$this->oM->sd['un'] = $ar['un'];
			$this->oM->sd['nm'] = $ar['nm'];
			$this->oM->sd['tt'] = $ar['tt'];
			//$this->oM->sd['fm'] = $ar['fm'];
			$ar = explode(',', $this->oM->sd['gs']);
			if(count($ar)==1)
				$this->gid($ar[0]);
		}
		
		function gid($gid) {
		  $r = '';
		  $this->oM->sd['gid'] = $gid;
		  $this->oM->sd['gnm'] = $this->get_gnm($gid);
		  $this->oM->sd['gc'] = $this->get_gc($this->oM->sd['uid'], $gid);
		  $this->oM->sd['SMSG'] = '';
		  $this->oM->sd['gss'] = $this->get_uid($gid);
			$q = "
        select count(*) cnt 
					from scf 
				 where tp=".$this->oM->ja['rs']['admin']." 
           and id=$gid";
		  $rs = $this->oM->sSCF($q); //echo $q; exit;
		  $this->oM->sd['admin'] = $rs[1]['CNT'];
			$q = "select tp from scf where id=$gid"; 
			$rs = $this->oM->sSCF($q);
		  $this->oM->sd['gtp'] = $rs[1]['TP']; //Рөл ID
		  
		  return $r;
		}
		
		function get_gnm($gid) {
		  $r = '';
		  $rs = $this->oM->sSCF('select nm from scf where id='.$gid);
		  $r .= $rs[1]['NM'];
		  return $r;
		}
		
		function get_gc($uid, $gid) {
		  $r = 0;
		  $q = "
			select id
			  from scf
			 where tp = $uid
			   and pd = $gid
		  "; $rs = $this->oM->sSCF($q);
		  if(count($rs)==1)
			$r = $rs[1]['ID'];
		  return $r;
		}
		
		function get_uid($pid) {
		  $r = '';
		  //$q = $this->qa[1];
		  $q = "select id, (select count(*) from scf g1 where g1.pd=scf.id) cnt from scf where pd=$pid";
		  //$q = str_replace('#PID', $pid, $q);
		  $rs = $this->oM->sSCF($q);
		  
		  

		  foreach($rs as $rk=>$rw) {
			//if($rw['CS_ID']!='') {
			  if($r!='')
				$r .= ',';
			  $r .= $rw['ID'];//$r .= $rw['CS_ID'];
			//} //else {
			  $u = $this->get_uid($rw['ID']);
			  if($u!='')
				$r .= ',';
			  $r .= $u;
			//}
		  }
		  
		  //$_SESSION['SMSG'] .= "\r\n".$r;
		  
		  return $r;
		}
	}
?>
