<?php
	/*
	* Copyright (C) 2025 Жанибек Какимбаев
	*
	* Бұл бағдарлама GNU General Public License v3.0 (GPLv3) лицензиясы бойынша таратылады.
	* Лицензия мәтіні: https://www.gnu.org/licenses/gpl-3.0.html
	*/
	namespace mvc\vs;
	
	class vMSG extends jV {
		public $rgp;//rd refresh get parameter
		public $rpp;//rd refresh post parameter
		public $gotoshow;
		function init() {
			$r = '';
			$this->gotoshow = false;
			return $r;
		}
		
		function r() {
			$r = '';
			//$r .= $this->b("", "c = confirm('TEST?'); ", "test", $this->rd(), 'TEST');
			//$r .= 'uid='.$this->oC->oM->sd['uid'];
			if($this->oM->sd['uid']==0) {
				$r .= $this->NOUSER();
			} else {
				$r .= $this->replSYS($this->replC($this->getHTML()));				
			}
			return $r;
		}
		
		function NOUSER() {
			$r = '';
			//$r .= $this->oM->sd['wnm'].' '.$this->oM->WALANSID;
			/*if($this->oC->oM->sd['wnm']=='') {
				$r .= $this->getNOUSERHTML();
				//$h = str_replace('{nouser}', $this->getMSGHTML(), $h);
			} else {
				//$r .= $this->replSYS($this->replC($this->getHTML()));
				$r .= $this->replSYS($this->getMSGH($this->getHTML()));
			}*/
			
			$h = $this->replSYS($this->getMSGH($this->getHTML()));
			
			$r .= $h;
			return $r;
		}
		
		function replSYS($h) {
			$r = $h;
			if(isset($this->oM->sd['tt']))
				$r = str_replace('{UTT}', $this->oM->sd['tt'], $r);
			$r = str_replace('{pg}', $this->oC->fd['pg'], $r);
			$r = str_replace('{obj}', $this->oC->fd['obj'], $r);
			$r = str_replace('{vw}', $this->vw, $r);
			$r = str_replace('{fp}', $this->oC->fp, $r);
			return $r;
		}
		
		function replC($h) {
			$r = '';
			//$r .= $this->oC->fm['showus'];
			if($this->oC->fd['cid']==0) {
				if(!$this->oC->fd['showus']) {
					$h = str_replace('{main}', $this->getCHSHTML(), $h);
					$h = str_replace('{chs}', $this->getCHS(), $h);
				} else {
					$h = str_replace('{main}', $this->getUSHTML(), $h);
					$h = str_replace('{us}', $this->getUS(), $h);
					
				}
			} else {
				$h = $this->getMSGH($h);				
			}
			
			$r .= $h;
			return $r;
		}
		
		function rz() {
			$r = '';
			$cmd = $this->oM->cmd;
			$ssd = $this->oM->ssd;
			$r .= $this->oM->jo.".a('?".$cmd."=".$this->vw.$ssd.$this->oC->fp."r".$this->rgp."', '".$this->rpp."');";
			return $r;
		}
		
		function rd() {
			$r = '';
			$cmd = $this->oM->cmd;
			$ssd = $this->oM->ssd;
			$r .= $this->oM->jo.".a('?".$cmd."=".$this->vw.$ssd."r".$this->rgp."', '".$this->rpp."');";
			return $r;
		}
		
		function getMSGH($h) {
			$h = str_replace('{main}', $this->getMSGHTML(), $h);
			if($this->oC->oM->sd['uid']>0) {
				$h = str_replace('{back}', $this->getBACKHTML(), $h);
			} else {
				$h = str_replace('{back}', '', $h);
			}
			$h = str_replace('{msg}', $this->getMSG($this->oC->fd['cid']), $h);
			$h = str_replace('{snd}', $this->getSND($this->oC->fd['cid']), $h);
			$h = str_replace('{msgnm}', $this->oC->fd['msgnm'], $h);
			$h = str_replace('{cid}', $this->oC->fd['cid'], $h);
			return $h;
		}
		
		function idCHS() {
			$r = '';
			$r .= $this->getCHS();
			return $r;
		}
		
		function idMSG() {
			$r = '';
			//$r .= 'idMSG';
			$r .= $this->getMSG($this->oC->fd['cid']);
			return $r;
		}
		
		function getUS() {
			$r = '';
			if($this->oM->sd['uid']>0) {
				$q = "
				  select id, nm, tt
					from scf
				   where pd=".$this->oM->ja['scf']['us']."
				     and id<>".$this->oM->sd['uid']."
				   ";
					 
				$rs = $this->oC->oM->sSCF($q);
				
				$r .= '<table>';
				foreach($rs as $rk=>$rw) {
					$r .= '<tr><td>';
					$r .= "<a style=\"cursor:pointer; font-size: 30px; font-weight: bold;\" onclick=\"jT.p='touid=".$rw['ID']."&tounm=".$rw['TT']."'; jT.a('?o=mvc:cs:cMSG;1;1;addCHS', jT.p); jT.a('?o=mvc:vs:vMSG;1;1;r', '');\">".$rw['TT'].'</a>';
					$r .= '</td></tr>';
				}
				$r .= '</table>';
			}
			
			return $r;
		}
		
		function getSND($cid) {
			$r = '';
			//$r .= '<input type="text" id="msgtxt"/><button onclick="sendMSG('.$cid.')">SEND</button>';
			//$r .= '<input type="text" id="msgtxt"/>';
			//$r .= '<textarea id="msgtxt" cols="30" rows="5"></textarea>';
			//$r .= $this->b("", " const msgtxt = document.getElementById('msgtxt'); var msg = encodeURIComponent(msgtxt.value); jT.p='cid=".$cid."&msg='+msg;", "sendMSG", $this->rz(), '&#128206');
			
			//$this->rpp = $this->oC->ufp.'&'.$this->oC->pfxvw.'cid='.$cid;
			$ufp = str_replace('?', '', $this->oC->ufp);
			$this->rgp = '&'.$ufp.'&'.$this->oC->pfxvw.'cid='.$cid;
			$r .= $this->b("", " const msgtxt = document.getElementById('msgtxt'); var msg = encodeURIComponent(msgtxt.value); jT.p='cid=".$cid."&msg='+msg;", "sendMSG", $this->rz(), 'SEND');
			$this->rgp = '';
			return $r;
		}
		
		function getCHS() {
			$r = '';
			//$r .= __FILE__.':'.__LINE__;
			if($this->oC->oM->sd['uid']>0) {
				$q = "
				  select cus.cid
				         , case 
							when (select count(*) 
							        from cus c1 
								   where c1.cid=chs.id 
								     and c1.uid<>".$this->oC->oM->sd['uid']." 
									 and c1.uid is not null)=1 then (select (select tt from scf where id=c1.uid) 
							                      from cus c1 
												 where c1.cid=chs.id and c1.uid<>".$this->oC->oM->sd['uid'].")
						    else chs.nm
						 end tt
						 , (select count(*) from msg where msg.cid=chs.id)-(select count(*) from mvs, msg where msg.cid=chs.id and mvs.mid=msg.id and mvs.uid=".$this->oC->oM->sd['uid'].") v
						 , 1 d
						 , (select count(*) from cus c1 where c1.cid=cus.cid) cu
					from cus
						 , chs
				   where cus.cid=chs.id 
					 and cus.uid=".$this->oC->oM->sd['uid']."
				  union all
				  select cus.cid
				         , cus.cid tt
						 , (select count(*) from msg where msg.cid=chs.id) v
						 , 2 d
						 , (select count(*) from cus c1 where c1.cid=cus.cid) cu
					from cus
						 , chs
				   where cus.cid=chs.id 
					 -- and cus.uid=null
					 and cus.wsd>0
				";
					 
					 
				//echo $q;	 
				$rs = $this->oC->oM->s($q);
				
				$r .= '<table>';
				foreach($rs as $rk=>$rw) {
					if($rw['D']==1 || ($rw['D']==2 && $rw['CU']==1)) {
						$c = 'color: black;';
						if($rw['D']==2)
							$c = 'color: red;';
						$r .= '<tr><td style="width:200px; '.$c.'">';
						//$r .= $rw['CU'];
						if($rw['TT']=='')
							$rw['TT'] = $rw['CID'];
						//$r .= '<a href="'.$this->oC->ufp.'&'.$this->oC->pfxvw.'cid='.$rw['CID'].'">'.$rw['NM'].'</a>';
						$r .= "<a style=\"cursor:pointer; font-size: 30px; font-weight: bold;\" onclick=\"jT.p='cid=".$rw['CID']."&nm=".$rw['TT']."'; jT.a('?CKF=mvc:cs:cMSG;1;1;cid', jT.p); jT.a('?CKF=mvc:vs:vMSG;1;1;r', '');\">".$rw['TT'].'</a>';
						$r .= '</td>';
						$r .= '<td>';
						if($rw['V']>0)
							$r .= '<div style="background-color:#00D757; color:#FFF; font-weight: bold;   border-radius:30px; padding: 5px; cursor: pointer;" >'.$rw['V'].'</div>';
						$r .= '</td>';
						$r .= '</tr>';
					}
				}
				$r .= '</table>';
			} elseif($this->oC->oM->WALANSID>0) {
				$q = "
				  ";
				
			}
			//$uid = 0;
			//$r .= $this->b("", " const msgtxt = document.getElementById('msgtxt'); var msg = encodeURIComponent(msgtxt.value); jT.p='uid=".$uid.";", "addCHS", $this->rz(), '+');
			
			return $r;
		}
		
		function getMSG($cid) {
			$r = '';
			//$r .= __FILE__.':'.__LINE__;
			$q = '';
			if($this->oC->oM->sd['uid']>0) {
				$q = "
				  select id, uid, wsd
						 , (select nm from scf where id=msg.uid) unm
						 , (select tt from scf where id=msg.uid) utt
						 , msg
						 , (select count(*) from mvs where mid=msg.id and uid=".$this->oC->oM->sd['uid'].") v
					from msg
				   where cid=$cid";
			} elseif($this->oM->sd['wid']>0) {
				$q = "
				  select id, uid, wsd
						 , case when msg.uid>0 then 
						       (select nm from scf where id=msg.uid)
						   else
							   (select nm from sss where id=msg.wsd)
						   end unm
						 , case when msg.uid>0 then 
						       (select tt from scf where id=msg.uid)
						   else
							   (select nm from sss where id=msg.wsd)
						   end utt
						 , msg
						 , (select count(*) from mvs where mid=msg.id and wsd=".$this->oM->sd['wid'].") v
					from msg
				   where cid=$cid";
			}
			$rs = $this->oM->s($q);
			//print_r($rs);
			
			$r .= '<div id="NEWMSG" style="position: absolute; display: none; background-color:#00D757; color:#FFF; font-weight: bold; bottom: 200px; right: 50px;  border-radius:30px; padding: 10px; cursor: pointer;" onclick="jMSG.goBottom()">'.$this->oC->fd['newmsg'].'</div>';
			if($this->oC->oM->sd['uid']==0 && $this->oC->oM->sd['wnm']=='') {
				$r .= $this->getNOUSERHTML();
			}
			//$r .= 'WALANSID='.$this->oC->oM->WALANSID;
			$r .= '<table style="width:300px">';
			foreach($rs as $rk=>$rw) {
				if($this->oM->sd['uid']>0 && $rw['V']==0) {
					$q = "insert into mvs (mid, uid) values (".$rw['ID'].", ".$this->oC->oM->sd['uid'].")";
					$this->oC->oM->q($q);$this->oC->oM->q('commit');
				}
				
				if($this->oM->sd['wid']>0 && $rw['V']==0) {
					$qs = [];
					$q = "insert into mvs (mid, wsd) values (".$rw['ID'].", ".$this->oM->sd['wid'].")";
					$qs[] = $q;
					$this->oC->oM->qscSCF($qs);
				}
				
				$r .= '<tr>';
				$r .= '<td>';
				$dvCLS = 'dvLMSG';
				$icu = false;
				if($this->oM->sd['uid']>0 && $rw['UID']==$this->oM->sd['uid']) {
					$dvCLS = 'dvRMSG';
					$icu = true;
				} elseif($this->oM->sd['wid']>0 && $rw['WSD']==$this->oM->sd['wid']) {
					$dvCLS = 'dvRMSG';
					$icu = true;
				}
				
				$r .= '<div class="'.$dvCLS.'">';				
				if(!$icu) {
					$r .= '<div style="font-weight:bold">';
					$r .= $rw['UTT'];
					$r .= '</div>';
				}
				$r .= base64_decode($rw['MSG']);
				$r .= '</div>';
				
				$r .= '</td>';
				$r .= '</tr>';
			}
			$r .= '</table>';
			return $r;
		}
		
		function getBACKHTML() {
			$r = '';
			ob_start();
			?>
				<img src="img/Back.png" style="height:50px"
						onclick="jT.p='cid=0'; jT.a('?o=mvc:cs:cMSG;1;1;cid', jT.p); jT.a('?o=mvc:vs:vMSG;1;1;r', '');"
					/>
			<?php
			$r .= ob_get_contents();
			ob_end_clean();
			return $r;
		}
		
		function getNOUSERHTML() {
			$r = '';
			ob_start();
			?>
				<br/>
				<br/>
				<br/>
				<br/>
				<br/>
				<div id="dvNOUSER" align="center">
					Есіміңіз
					<br/><input type="text" id="WNM"/>
					<br/><button onclick="var p='wnm='+encodeURIComponent(document.getElementById('WNM').value); jMSG.a('?o=mvc:cs:cMSG;1;1;wnm', p); jMSG.a('?o=mvc:vs:vMSG;1;1;r', '');">Сақтау</button>
				</div>
			<?php
			$r .= ob_get_contents();
			ob_end_clean();
			return $r;
		}
		
		function getUSHTML() {
			$r = '';
			ob_start();
			?>
				<div class="dvHEAD">
					<img src="img/Back.png" style="height:50px"
						onclick="jT.p='cid=0'; jT.a('?o=mvc:cs:cMSG;1;1;cid', jT.p); jT.a('?o=mvc:vs:vMSG;1;1;r', '');"
					/>
				</div>
				<div class="dvUS">
					{us}
				</div>
			<?php
			$r .= ob_get_contents();
			ob_end_clean();
			return $r;
		}
		
		function getCHSHTML() {
			$r = '';
			ob_start();
			?>
				<div id="idCHS" class="dvCHS">
					{chs}
				</div>
				<img src="img/addCHS.png"  style="position: absolute; bottom: 30px; right: 30px; margin: 10px;"
						onclick="jT.p='touid=0&tounm='; jT.a('?o=mvc:cs:cMSG;1;1;addCHS', jT.p); jT.a('?o=mvc:vs:vMSG;1;1;r', '');"
					/>
			<?php
			$r .= ob_get_contents();
			ob_end_clean();
			return $r;
		}
		
		function getMSGHTML() {
			$r = '';
			ob_start();
			?>
				<div class="dvHEAD">
					{back}					
					<br/>{msgnm}
				</div>
				<div id="idMSG">
					{msg}
				</div>
				<div class="dvSND">
					<table>
						<tbody>
							<tr><td rowspan="4"><textarea id="msgtxt" cols="30" rows="5" style="resize: none;"></textarea></td></tr>
							
							<tr><td><label for="loadfilemsg"><img src="img/loadPhoto.png" style="height:20px"/></label>
					<input type="file" id="loadfilemsg" style="display: none;" accept="image/*" onchange="
					  var v=jT.ufs('?o=jFILE;1;1;ufs&dg=270', this.files, 1000, 0.9);
					" capture=""/></td></tr>
							
							<tr><td><img src="img/clip.png" style="height:20px"/></td></tr>
							<tr><td><img src="img/sendMSG.png" style="height:20px"
								onclick="
									var msgtxt = document.getElementById('msgtxt'); 
									var msg = encodeURIComponent(msgtxt.value);
									msgtxt.value = '';
									jMSG.p='cid={cid}&msg='+msg; 
									jMSG.a('?o=mvc:cs:cMSG;1;1;sendMSG', jMSG.p); 
									jMSG.a('?o=mvc:vs:vMSG;1;1;idMSG', '');
								"
							/></td></tr>
						</tbody>
					</table>
					
					
				</div>
			<?php
			$r .= ob_get_contents();
			ob_end_clean();
			return $r;
		}
		
		function getHTML() {
			$r = '';
			ob_start();
			?>
				<style>
					.dvLMSG {
						/*border: 1px solid #333;*/
						float: left;
						border-radius: 5px;
						background-color: #FFFFFF;
						max-width: 250px;
						/*inline-size: 150px;*/
						overflow-wrap: break-word;
						white-space: pre-wrap;
					}
					.dvRMSG {
						/*border: 1px solid #333;*/
						float: right;
						border-radius: 5px;
						background-color: #D8FDD2;
						max-width: 250px;
						/*inline-size: 150px;*/
						overflow-wrap: break-word;
						white-space: pre-wrap;
					}
					.dvMBS {
						border: 1px solid #333;
						width: 300px;
						height: 50px;
					}
					.dvCHS {
						/*border: 1px solid #333;*/
						width: 300px;
						height: 460px;
						/*background-color: #FBF4EC;*/
						
						overflow: scroll;
					}
					#idMSG {
						border: 1px solid #333;
						width: 300px;
						height: 300px;
						background-color: #FBF4EC;
						overflow: auto;
						/*padding-right: 20px;*/
					}
					.dvMAIN {
						/*border: 1px solid #333;*/
						width: 300px;
						display: block;
						margin-left: auto;
						margin-right: auto;
					}
					
					.btnNO {
						background-color: transparent;
						background-repeat: no-repeat;
						border: none;
						cursor: pointer;
						overflow: hidden;
						outline: none;
						font-weight: bold;
					}
				</style>
				<br/>
				<div class="dvMAIN">
					{main}					
				</div>
				<script>
					function sendMSG(cid) {
						const msgtxt = document.getElementById('msgtxt')
						//alert('SEND Message '+msgtxt.value+' to '+cid);
						var msg = encodeURIComponent(msgtxt.value);
					}
					alert('idMSG');
					setInterval(() => {
						const idMSG = document.getElementById('idMSG');
						alert(idMSG);
						if (typeof idMSG !== 'undefined') {
							alert('idMSG');
						}
					}, 2000);
				</script>
			<?php
			$r .= ob_get_contents();
			ob_end_clean();
			return $r;
		}
		
		function getUHTML() {
			$r = '';
			ob_start();
			?>
			<?php
			$r .= ob_get_contents();
			ob_end_clean();
			return $r;
		}
	}
?>