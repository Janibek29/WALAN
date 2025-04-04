<?php
	/*
	* Copyright (C) 2025 Жанибек Какимбаев
	*
	* Бұл бағдарлама GNU General Public License v3.0 (GPLv3) лицензиясы бойынша таратылады.
	* Лицензия мәтіні: https://www.gnu.org/licenses/gpl-3.0.html
	*/
	namespace mvc\cs;
	
	class cMSG extends jC {
		var $pfxvw;
		public $ufp;
		
		function init() {
			$r = '';
			$this->sfp();
			$ssd = $this->oM->ssd;
			//$this->ChatsCount = $this->getChatsCount();
			$this->pfxvw = $this->vw.$ssd.$this->fp.$ssd;
			
			if(!isset($this->fd['cid']))
				$this->fd['cid'] = 0;
			if(!isset($this->fd['touid']))
				$this->fd['touid'] = 0;
			if(!isset($this->fd['showus']))
				$this->fd['showus'] = false;
			if(!isset($this->fd['msgnm']))
				$this->fd['msgnm'] = '';
			if(!isset($this->fd['newmsg']))
				$this->fd['newmsg'] = 0;
			$this->fd['pg'] = '';
			if(isset($_GET['pg'])) {
				$this->fd['pg'] = $_GET['pg'];
			}
			$this->fd['obj'] = '';
			if(isset($_GET['OBJ']))
				$this->fd['obj'] = $_GET['OBJ'];
			
			$this->rq();
			return $r;
		}
		
		function rq() {
			$r = '';
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
		
		function wnm() {
			$r = '';
			if($this->oM->sd['wid']>0) {
				$qs = [];
				$wnm = $_POST['wnm'];
				$this->oM->sd['wnm'] = $wnm;
				$q = "update sss set nm='$wnm' where id=".$this->oM->sd['wid'];
				$qs[] = $q;
				$this->oM->qscSCF($qs);
			}
			return $r;
		}
		
		function getNEWMSGCHSU($uid) {
			$r = 0;
			$q = "
				  select sum((select count(*) 
				                from msg 
							   where msg.cid=chs.id)
							-(select count(*) 
							    from mvs, msg 
							   where msg.cid=chs.id 
							     and mvs.mid=msg.id 
								 and mvs.uid=$uid)) v
					from cus
						 , chs
				   where cus.cid=chs.id 
					 and cus.uid=$uid";
			$rs1 = $this->oM->sSCF($q);
			$r = $rs1[1]['V'];
			return $r;
		}
		
		function getNEWMSGCHSW($wsd) {
			$r = 0;
			$q = "
				  select sum((select count(*) 
				                from msg 
							   where msg.cid=chs.id)
							-(select count(*) 
							    from mvs, msg 
							   where msg.cid=chs.id 
							     and mvs.mid=msg.id 
								 and mvs.wsd=$wsd)) v
					from cus
						 , chs
				   where cus.cid=chs.id 
					 and cus.wsd=$wsd";
			$rs1 = $this->oM->s($q);
			$r = $rs1[1]['V'];
			return $r;
		}
		
		function NEWMSGCHS() {
			$r = 0;
			if($this->oM->sd['uid']>0)
				$r = $this->getNEWMSGCHSU($this->oM->sd['uid']);
			elseif($this->oM->WALANSID>0) {
				$r = $this->getNEWMSGCHSW($this->oM->WALANSID);
			}
			return $r;
		}
		
		function getNEWMSGU($cid, $uid) {
			$r = 0;
			$q = "
			  select m1.cnt-m2.cnt newmsg
			    from (select count(*) cnt from msg where cid=$cid) m1 
			         , (select count(*) cnt
				          from mvs, msg 
						 where msg.cid=$cid 
						   and mvs.mid=msg.id 
						   and mvs.uid=$uid) m2";
			$rs1 = $this->oM->s($q);
			$r = $rs1[1]['NEWMSG'];
			return $r;
		}
		
		function getNEWMSGW($cid, $wsd) {
			$r = 0;
			$q = "
			  select m1.cnt-m2.cnt newmsg
			    from (select count(*) cnt from msg where cid=$cid) m1 
			         , (select count(*) cnt
				          from mvs, msg 
						 where msg.cid=$cid 
						   and mvs.mid=msg.id 
						   and mvs.wsd=$wsd) m2";
			$rs1 = $this->oM->s($q);
			$r = $rs1[1]['NEWMSG'];
			return $r;
		}
		
		function NEWMSG() {
			$r = 0;
			if($this->oM->sd['uid']>0)
				$r = $this->getNEWMSGU($this->fm['cid'], $this->oM->sd['uid']);
			elseif($this->oM->WALANSID>0) {
				$r = $this->getNEWMSGW($this->fm['cid'], $this->oM->WALANSID);
			}
			return $r;
		}
		
		function newmsg0() {
			$r = '';
			$this->sd['newmsg'] = 0;
			return $r;
		}
		
		function newCHS($tounm) {
			$qs = [];
			$cid = $this->oM->ivSCF('chs');
			$this->fd['cid'] = $cid;			
			if($tounm=='')
				$tounm = $this->fd['cid'];
			$this->fd['msgnm'] = $tounm;
			
			$q = "insert into chs (id, nm, tp) values ($cid, '$tounm', 1)";
			$qs[] = $q;
			if($this->oM->sd['uid']>0) {
				$uid = $this->oM->sd['uid'];
				$q = "insert into cus (cid, uid) values ($cid, $uid)";
				$qs[] = $q;
			} elseif($this->oM->sd['wid']>0) {
				$wsd = $this->oM->sd['wid'];
				$q = "insert into cus (cid, wsd) values ($cid, $wsd)";
				$qs[] = $q;
			}
			$this->oM->qscSCF($qs);
			return $cid;
		}
		
		function addCHS() {
			$r = '';
			$qs = [];
			$touid = $_POST['touid'];
			$tounm = $_POST['tounm'];
			if($touid>0) {
				if($this->oM->sd['uid']>0) { //Авторизация жасалған
					$uid = $this->oM->sd['uid'];
					if($touid<>$uid) {
						//Тексеру cus тан чат бар ма uid пен touid бойынша
						$q = "
						  select cus.cid, count(*) cnt
							from cus
								 , chs
						   where cus.cid = chs.id
							 and chs.tp = 1
							 and cus.uid in ($uid, $touid)
						   group by cus.cid
						";
						$q = "
						  select t.cid, t.uid, t.touid, (select tt from scf where id=t.touid) tott from (
						  select cus.cid
						         , max(case when cus.tp=1 then cus.uid end) uid
						         , max(case when cus.tp=2 then cus.uid end) touid
							from cus
								 , chs
						   where cus.cid = chs.id
							 and chs.tp = 1
							 and cus.uid in ($uid, $touid)
						   group by cus.cid) t
						   where t.uid=$uid
						     and t.touid=$touid
						";
						//echo $q; 
						$rs = $this->oM->sSCF($q);
						//print_r($rs);
						$cnt = count($rs);
						//if($cnt==0 || ($cnt==1 && $rs[1]['CNT']==1)) {
						if($cnt==0) {
							$cid = $this->newCHS($tounm);
							$cusid = $this->oM->ivSCF('cus');
							$q = "insert into cus (id, cid, uid, tp) values ($cusid, $cid, $uid, 1)";
							$qs[] = $q;
							$cusid = $this->oM->ivSCF('cus');
							$q = "insert into cus (id, cid, uid, tp) values ($cusid, $cid, $touid, 2)";
							$qs[] = $q;
							$this->oM->qscSCF($qs);
						} elseif($cnt==1) {
							$this->fd['cid'] = $rs[1]['CID'];
							$this->fd['showus'] = false;
							$this->fd['msgnm'] = $rs[1]['TOTT'];
						} else {
							$this->fd['cid'] = 0;
							$this->fd['showus'] = false;
						}
					} else {
						$this->fd['showus'] = false;
					}
				} else { //Сессия қолданушы
					
				}
			} else {
				$this->fd['showus'] = true;
			}
			//$r .= 'test';
			return $r;
		}
		
		function sendMSG() {
			$r = '';
			if($this->fd['cid']>0)
				$cid = $this->fd['cid'];
			else
				$cid = $_POST['cid'];
			$msg = base64_encode(urldecode($_POST['msg']));
			if($msg!='') {
				if($this->oM->sd['uid']>0) {
					$this->sendMSGU($cid, $msg);
				} elseif($this->oM->sd['wid']>0) {
					$this->sendMSGW($cid, $msg);
				}
			}
			return $r;
		}
		
		function sendMSGU($cid, $msg) {
			$r = '';
			$uid = $this->oM->sd['uid'];
			$qs = [];
			//Чат Қолданушысын тексеру, жоқ болса ендіру
			$q = 'select count(*) cnt from cus where cid='.$cid.' and uid='.$uid;
			$rs = $this->oM->sSCF($q);
			if($rs[1]['CNT']==0) {
				$q = 'insert into cus (cid, uid) values ('.$cid.', '.$uid.')';
				$qs[] = $q;
			}
			$mid = $this->oM->ivSCF('msg');
			$q = "insert into msg (id, cid, uid, msg) values ($mid, $cid, $uid, '$msg')";
			$qs[] = $q;
			$q = "insert into mvs (mid, uid) values ($mid, $uid)";
			$qs[] = $q;
			$this->oM->qscSCF($qs);
			return $r;
		}
		
		function sendMSGW($cid, $msg) {
			$r = '';
			if($this->oM->sd['wnm']!='') { 
				$wsd = $this->oM->sd['wid'];
				$qs = [];
				if($cid==0) {
					$cid =  $this->newCHS('');
				}
				//Чат Қолданушысын тексеру, жоқ болса ендіру
				$q = 'select count(*) cnt from cus where cid='.$cid.' and wsd='.$wsd;
				$rs = $this->oM->sSCF($q);
				if($rs[1]['CNT']==0) {
					$q = 'insert into cus (cid, wsd) values ('.$cid.', '.$wsd.')';
					$qs[] = $q;
				}
				
				$mid = $this->oM->ivSCF('msg');
				$q = "insert into msg (id, cid, wsd, msg) values ($mid, $cid, $wsd, '$msg')";
				$qs[] = $q;
				$q = "insert into mvs (mid, wsd) values ($mid, $wsd)";
				$qs[] = $q;
				//print_r($qs);
				$this->oM->qscSCF($qs);
			}
			return $r;
		}
		
		function cid() {
			$r = '';
			if(isset($_POST['cid'])) {
				$this->fd['cid'] = $_POST['cid'];
				$this->fd['showus'] = false;
				if(isset($_POST['nm']))
					$this->fd['msgnm'] = $_POST['nm'];
			}
			return $r;
		}
		
		function cid1() {
			$r = '';
			if(isset($_GET[$this->pfxvw.'cid'])) {
				$this->fm['cid'] = $_GET[$this->pfxvw.'cid'];
			}
			//$r .= $this->fm['cid'];
			$ChatsCount = $this->getChatsCount();
			//echo 'ChatsCount='.$ChatsCount;
			
			if($ChatsCount==0 && isset($_SESSION['uid'])) {
				$qs = [];
				//chs
				$chs1 = $this->oM->iv('chs');
				$q = "
				  insert into chs (id, nm) values(
					".$chs1."
					, ".$this->oM->nv('test1')."
				  )
				";
				$qs[] = $q;
				$chs2 = $this->oM->iv('chs');
				$q = "
				  insert into chs (id, nm) values(
					".$chs2."
					, ".$this->oM->nv('test2')."
				  )
				";
				$qs[] = $q;
				$chs3 = $this->oM->iv('chs');
				$q = "
				  insert into chs (id, nm) values(
					".$chs3."
					, ".$this->oM->nv('test3')."
				  )
				";
				$qs[] = $q;
				$this->oM->qsc($qs);
			}
			return $r;
		}
		/*
		function getChatsCount() {
			$r = 0;
			if(isset($_SESSION['uid'])) {
				$q = "
				  select count(*) cnt
					from cus
						 , chs
				   where cus.cid=chs.id 
					 and cus.uid=".$_SESSION['uid']."
				";
				$rs = $this->oM->s($q);
				$r = $rs[1]['CNT'];
			}
			return $r;
		}
		
		function getSID() {
			$r = 0;
			$qs = [];
			$r = $this->oM->iv("sss");
			$this->fm['usid'] = $r;
			$q = "insert into sss (id) values ($r)";
			$qs[] = $q;
			$this->oM->qsc($qs);
			return $r;
		}*/
	}
?>