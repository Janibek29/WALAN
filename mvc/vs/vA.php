<?php
	/*
	* Copyright (C) 2025 Жанибек Какимбаев
	*
	* Бұл бағдарлама GNU General Public License v3.0 (GPLv3) лицензиясы бойынша таратылады.
	* Лицензия мәтіні: https://www.gnu.org/licenses/gpl-3.0.html
	*/
	namespace mvc\vs;
	
	class vA extends jV {
		
		function init() {
			$r = '';
			
			return $r;
		}
		
		function r() {
			$r = '';
			$r .= '<div style="margin-left: auto;margin-right: auto; width: 300px;">';
			$r .= '<div align="right"><a href="?pg=main">[X]</a></div>';
			$r .= '<br/>';
			if($this->oC->oM->sd['uid']>0) { //Егер қолданушы кірген болса
				$r .= $this->u();//Қолданушы қалпы
			} else {//әйтпесе кіру қалпы
				$r .= $this->a();
			}
			$r .= '</div>';
			//$r .= $this->b("", "", "c", $this->rd(), "btn");
			return $r;
		}
		
		function va() {
			$r = '';
			$r .= '<div style="position: fixed; top: 30px; right: 30px;">';
			$r .= '<a href="?pg=a">';
			if($this->oC->oM->sd['uid']>0) {
				$tt = $this->oC->oM->sd['un'];
				if($this->oC->oM->sd['tt']!='')
					$tt = substr(strtoupper($this->oC->oM->sd['tt']),0,1);
				$r .= '<div style="border-radius: 30px; padding: 15px; background: #E2EEF7; font-size: 40px;">'.$tt.'</div>';
			} else {
				$r .= '<img width="30" src="'.IMG.'a.png"/>';
			}
			$r .= '</a>';
			$r .= '</div>';
			return $r;
		}
		
		//Аутентификация
		function a() {
			$r = '';
			$r .= $this->ah();
			return $r;
		}
		
		//Қолданушы
		function u() {
			$r = '';
			$r .= $this->oM->sd['nm'];
			$r .= $this->b('', "var pw=''; c=confirm('Құпия сөзді өзгерту?'); if(c) {pw=prompt('Құпия сөзді ендіріңіз');} jT.p='pw='+pw;", 'pw', '', 'PW');
			$r .= '<br/>';
			$r .= "<input type=\"button\" value=\"<-\" onclick=\"if(confirm('Шығу?')) window.open('?exit', '_self')\"/>";
			
			if(isset($this->oM->sd['gnm'])) {				
				$r .= 'Таңдалған Топ ';
				if(isset($this->oM->sd['admin']) 
					&& $this->oM->sd['admin']>0) {
						//$r .= $this->b('', $this->oM->jo.".p='';", '', "location.href = '?pg=admin'; ", $this->oM->sd['gnm']);
						$r .= '<a href="?pg=admin">[admin]</a>';
				} else {
					$r .= $this->oM->sd['gnm'];
				}
			}
			//$r .= 'admin='.$this->oM->sd['admin'];
			$r .= '<br/>'.$this->get_gss();
			
			$ra = "jT.a('?".$this->oC->cvw."r', '');";
			if(!file_exists(KYS.$this->oM->sd['un'].'.p12')) {
				
				$r .= $this->b('', "var pw=''; c=confirm('Қол қою кілтін жасайын ба?'); if(c) {pw=prompt('Құпия сөзді ендіріңіз');} jT.p='pw='+pw; ", 'createUP12', $ra, 'Кілт жасау');
			} else {
				$r .= '<a href="?'.$this->oC->ccr.'getUP12">Қол қою кілтін алу</a>';
			}
			
			if($this->oM->sd['crt']=='') {
				$r .= '<br/><br/>Кілтіңіз бар болса, жүктеңіз';
				$r .= '<br/>'."<input type=\"file\" onchange=\"var pw=prompt('Құпия сөзді ендіріңіз'); jT.ufs('?".$this->oC->ccr.'setCSR'."', this.files, 0, 0, 'pw='+pw); $ra\"/>";
			}
			return $r;
		}
		
		function get_gss() {
		  $r = '';
		  $gs = explode(',',$this->oC->oM->sd['gs']);
		  $r .= "<table>";
		  foreach($gs as $gid) {
			if($gid>0) {
			  $q = "select nm from scf where id='$gid'";
			  $rs = $this->oC->oM->sSCF($q);
			  $r .= "<tr><td><button onclick=\"window.open('?pg=a&gid=$gid', '_self');\">".$rs[1]['NM'].'</button></td></tr>';
			}
		  }
		  $r .= "</table>";
		  return $r;
		}
		
		function ah() {
			$r = '';
			ob_start();
			?>			
			<form method="post">
				<table><tbody>
					<tr><td>Қолданушы</td></tr>
					<tr><td><?php echo $this->getUN();?></td></tr>
					<tr><td>Құпия сөз</td></tr>
					<tr><td><input name="pw" type="password"/></td></tr>
					<tr><td align="center"><input name="btnlogin" type="submit" value="Войти"/></td></tr>
				</tbody></table>
			</form>
			<?php
			$r .= ob_get_contents();			
			//$r = str_replace('{fmAuth}', $this->oC->oM->ja['fms']['fmAuth'], $r);	
			ob_end_clean();
			return $r;
		}
		
		function getUN() {
			$r = '';
			ob_start();
			?>
				<select name="pc">
					<option value="+7" selected>+7</option>
				</select>
				<input type="text" name="ph" style="width: 100px" value="{ph}"/>
			<?php
			
			$r .= ob_get_contents();   
			$ph = '';
			if(isset($_SESSION['reg']['ph'])) {
				$ph = $_SESSION['reg']['ph'];
			}
			$r = str_replace('{ph}', $ph, $r);			
			ob_end_clean();
			return $r;
		}
	}
?>
