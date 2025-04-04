<?php
	/*
	* Copyright (C) 2025 Жанибек Какимбаев
	*
	* Бұл бағдарлама GNU General Public License v3.0 (GPLv3) лицензиясы бойынша таратылады.
	* Лицензия мәтіні: https://www.gnu.org/licenses/gpl-3.0.html
	*/
	namespace mvc\vs;
	
	class vDEV extends jV {
		function init() {
			$r = '';
			return $r;
		}
		
		function r() {			
			$r = '';
			
			$pg = '';
			if(isset($_GET['pg']))
				$pg = $_GET['pg'];
			$r .= '<div style="margin-left:30px;"';
			$r .= $this->showDEV($pg);
			$r .= '</div';
			return $r;
		}
		
		function showDEV($pg) {			
			$r = '';
			$r .= '<br/>';
			$url = "?pg=$pg&".$this->oC->pcr;
			//$r .= 'fp='.$this->oC->fp;
			if($this->oC->fp>1)
				$r .= "<input type=\"button\" onclick=\"location.href='".$url."fp=1';\" value=\"<-\" />";
			switch($this->oC->fp) {
				case 1:
					
					$r .= "<br/><input type=\"button\" onclick=\"location.href='".$url."fp=2';\" value=\"MVC\" />";
					$r .= '<br/>'."<input type=\"button\" onclick=\"location.href='".$url."fp=3';\" value=\"Классы\" />";
					$r .= '<br/>'."<input type=\"button\" onclick=\"location.href='".$url."fp=4';\" value=\"Темы\" />";
					$r .= '<br/>'."<input type=\"button\" onclick=\"location.href='".$url."fp=5';\" value=\"Страницы\" />";
					$r .= '<br/>'."<input type=\"button\" onclick=\"location.href='".$url."fp=6';\" value=\"Формы\" />";
				break;
				case 2:
					$r .= $this->getMVC();
				break;
				case 3:					
					$r .= $this->getCS();
				break;
				case 4:
					$r .= $this->getTHS();//THS Темы										
				break;
				case 5:
					$r .= $this->getPGS();//PGS Страницы										
				break;
				case 6:
					$r .= $this->getFMS();//FMS Формы					
				break;
				case 7:
					if(isset($_GET['vnm'])) {
						$r .= "<input type=\"button\" onclick=\"location.href='?pg=$pg&fp=2';\" value=\"MVC\" />";
						$cn = str_replace('/','\\',$this->oC->vp).'\\'.$_GET['vnm'];
						$r .= $cn;
						$r .= getObj($cn, 1);
					}
				break;
				case 10:
					if($this->oC->isud) {
						//$r .= $this->oC->fp;
						$r .= $this->getVC();
					}
				break;
			}
			return $r;
		}
		
		function get($vnm) {			
			$r = '';
			
			return $r;
		}
		
		function getTHS() {
			$r = '';
			$r .= 'Темы<br/>';
			$path = $this->oC->ths;
			if(isset($_GET['vfc'])) {
				switch($_GET['vfc']) {
					case 'd':
						$path = $_GET['p'];
					break;
					case 'f':
					break;
				}
			}
				
			$r .= $this->VF($this->oC->ths, $path);
			return $r;
		}
		
		function vsuf() {
			$r = '';
			$ocf = "onchange=\"var v=''; var mv=''; var dcsfn = document.querySelector('#dcsfn');";
			$ocf .= " jT.cn='".$this->cr."'; jT.n=".$this->n.";";
			$ocf .= " v=jT.ufs('?".$this->oM->cmd."=".$this->cr.$this->oM->ssd."1;vcufs&fn='+dcsfn.innerHTML, this.files, 0, 0);";
			$ocf .= "\"";
			ob_start();
			?>
				<style>
					.vft {
					  background:#FFF;
					  border-collapse:collapse;
					  table-layout: fixed;
					}

					.vft th {
					  /*background:#F0F0F0;
					  border:1px solid #000;*/
					}

					.vft tr {
					  /*background:#F0F0F0;
					  border:1px solid #000;*/
					}

					.vft td{
					  /*background:#FFF;  
					  border:1px solid #000;*/
					  cursor:pointer;
					}

				</style>
				
				<div id="dcsuf" style="position:fixed;border:1px solid #333; background: #FFF; top:180px;display:none;">
					<div id="dcsfn"></div>
					<input type="file" <?=$ocf?>/>
					<button onclick="hidedcsuf()">X</button>
				</div>
				<script>
					function showdcsuf(fn) {
						var dcsuf = document.querySelector("#dcsuf");
						var dcsfn = document.querySelector("#dcsfn");
						
						dcsfn.innerHTML = fn;
						
						dcsuf.style.top = event.pageY+"px";
						dcsuf.style.left = event.pageX+"px";
						dcsuf.style.display = "block";
						
						//alert(event.pageX + " " +event.pageY);
					}
					
					function hidedcsuf() {
						var dcsuf = document.querySelector("#dcsuf");
						dcsuf.style.display = "none";
					}
				</script>
			
			<?php
			$r .= ob_get_contents();
			ob_end_clean();
			return $r;
		}
		
		function VF($gp, $path) {
			$r = '';
			$r .= $this->vsuf();
			$ar = explode('/', $path);
			foreach($ar as $k=>$p) {
				if($p!='') {
					$ph = '';
					for($i=0;$i<=$k;$i++) {
						$ph .= $ar[$i].'/';
					}
					$r .= "<a href=\"?pg=".$_GET['pg']."&".$this->oC->cr.";1;fp=".$this->oC->fp."&d=".$ph."\">";
					$r .= $p.'/';
					$r .= "</a>";
				}
			}
			$r .= "<table class=\"vft\"><tbody>";
			$dirs = glob($path."*", GLOB_ONLYDIR);
			//print_r($dirs);
			foreach($dirs as $k=>$d) {
				//$fn = str_replace($gp, '', $d);
				$fn = $d;
				//$fn = basename($d);
				$r .= "<tr><td class='vfd'>";
				$r .= "<a href=\"?pg=".$_GET['pg']."&".$this->oC->cr.";1;fp=".$this->oC->fp."&vfc=d&p=".$fn."/\">";
				$r .= $fn;
				$r .= "</a>";
				$r .= "</td></tr>";
			}
			$files = glob($path."*.*");
			foreach($files as $k=>$f) {
				//$fn = basename($f);
				$fn = $f;
				$r .= "<tr><td class='vff'>";
				$r .= '<a href="?'.$this->oM->cmd.'=mvc:cs:cDEV;1;1;gf&fn='.htmlentities(urlencode($fn)).'" target="_blank">';
				$r .= $fn;
				$r .= "</a><button onclick=\"showdcsuf('$fn')\">⯆</button>";
				$r .= "</td></tr>";
			}
			$r .= "</tbody></table>";
			//$r .= $this->b("", "var nm = prompt('ClassName',''); jT.p='nm='+nm;", "addf", $this->rd(), '+');
			$r .= "<button onclick=\"showdcsuf('')\">⯆</button>";
			return $r;
		}
		
		function getPGS() {
			$r = '';
			$r .= 'Страницы';
			return $r;
		}
		
		function getFMS() {
			$r = '';
			$r .= 'Формы';
			return $r;
		}
		
		function getVC() {
			$r = '';
			//$r .= print_r($this->oC->fd['rw'], true);
			$r .= '[V⯅][V⯆]';
			$r .= '[C⯅][C⯆]';
			return $r;
		}
		
		function getCS() {
			$r = '';
			$r .= 'Классы';
			$r .= $this->b("", "var nm = prompt('ClassName',''); jT.p='nm='+nm;", "addcs", '', '+');
			
			$r .= "<table><tbody>";
			$cdir = $this->oC->ud;
			//$r .= $cdir;
			$files = glob($cdir."/*.php");
			foreach($files as $k=>$f) {
				$fn = basename($f);
				$r .= "<tr>";
				$r .= '<td><a href="?'.$this->oM->cmd.'=mvc:cs:cDEV;1;1;jgf&fn='.htmlentities(urlencode($fn)).'" target="_blank">'.$fn."</a><button onclick=\"showdcsuf('$fn')\">U</button></td>";
				$r .= "</tr>";
			}
			$r .= "</tbody></table>";			
			$r .= $this->vsuf();
			return $r;
		}
		
		function getMVC() {
			$r = '';			
			$r .= 'MVC';
			$r .= $this->b("", "var nm = prompt('MVC class name',''); jT.p='nm='+nm;", "addmvc", '', '+');
			$r .= '<table>';			
			$r .= '<tbody>';
			$pg = '';
			if(isset($_GET['pg']))
				$pg = $_GET['pg'];
			foreach($this->oC->vfs as $k=>$f) {
			//foreach($rs as $rk=>$rw) {
				$fn = basename($f);				
				$nm = str_replace('v', '', $fn);
				$nm = str_replace('.php', '', $nm);
				$cf = $this->oC->cp.'/c'.$nm.'.php';
				$vf = $this->oC->vp.'/v'.$nm.'.php';
				$c = '';
				if(file_exists($cf))
					$c = basename($cf);
				
				$r .= '<tr>';
				$vf1 = str_replace('.php', '', $vf);
				$vf1 = str_replace('/', $this->oM->ssc, $vf1);
				$r .= '<td><a href="?pg=dev&do='.$vf1.$this->oM->ssd.'1'.$this->oM->ssd.'1" target="_blank">[Show]</a></td>';
				$r .= '<td><a href="?'.$this->oM->cmd.'=mvc:cs:cDEV;1;1;vcgf&fn='.htmlentities(urlencode($fn)).'" target="_blank">'."$fn</a></td>";
				$r .= "<td><button onclick=\"showdcsuf('$vf')\">V</button></td>";
				$r .= '<td><a href="?'.$this->oM->cmd.'=mvc:cs:cDEV;1;1;vcgf&fn='.htmlentities(urlencode($c)).'" target="_blank">'."$c</a></td>";
				$r .= "<td><button onclick=\"showdcsuf('$cf')\">C</button></td>";
				$r .= '</tr>';
			}
			$r .= '</tbody>';
			$r .= '</table>';
			
			$r .= $this->vsuf();
			return $r;
		}
	}
?>