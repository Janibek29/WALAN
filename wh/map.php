<?php
	/*
	* Copyright (C) 2025 Жанибек Какимбаев
	*
	* Бұл бағдарлама GNU General Public License v3.0 (GPLv3) лицензиясы бойынша таратылады.
	* Лицензия мәтіні: https://www.gnu.org/licenses/gpl-3.0.html
	*/
	//Основной Веб-Интерфейс настройки
	session_start();
	error_reporting(E_ALL);
	ini_set('display_errors','On');

	require_once('iProviderInterface.php');
	require_once('cWHBX24.php');
	require_once('cSOAP1C.php');

	require_once('cMDB.php');
	require_once('cMAP.php');
	require_once('cSYNC.php');

	require_once('jCDB.php');
	
	$oMDB = new cMDB();
	$oMDB->jf = 'map.json';
	if(file_exists($oMDB->jf)) {
		$oMDB->ja = json_decode(file_get_contents($oMDB->jf), true);
		$oMDB->conn();
		$oMDB->init();
	}

	if(isset($_GET['cmd'])) {
		$r = '';
		$oMAP = new cMAP();
		
		$r .= $oMAP->cmd($_GET['cmd']);
		echo $r;
		exit;
	}

?>

<!DOCTYPE html>
<html>
<head>
    <title>MAP</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=50, initial-scale=1, user-scalable=no">
    <script>
		var jJS = {
			a: function (url, pst) {
				if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
				  var xhr = new XMLHttpRequest();
				}
				else {// code for IE6, IE5
				  var xhr = new ActiveXObject("Microsoft.XMLHTTP");      
				}
				
				
				
				xhr.ontimeout = (e) => {
				  
				};
					  
				  xhr.onload = () => {			
					if (xhr.readyState === 4) {
					  if (xhr.status === 200) {
						var c = '';
						var k = '';
						var f = '';
						var fp = 1;
						var ar = xhr.responseURL.split('?');
						var urlps = ar[1].split('&');
						//urlps.forEach(function(elem, ind) {
							elem = urlps[0];
							ar = elem.split('=');
							if(ar[0]==CMD) {
								ar = ar[1].split(SSD);
								c = ar[0];
								k = ar[1];
								f = ar[2];
								if(!isNaN(f)) {
									fp = f;
									f = ar[3];
								}
							}
						//});
						
						if(xhr.responseText!='') {
							this.ares(c, k, f, xhr.responseText, fp);
						} /*else if(this.rurl!=''){
							this.a(this.rurl, '');
						}*/
						
						if(f=='rl') {
							window.location.reload();
						}
						
						if(this.lockShow)
							this.LockOff();
					  }
					}			
				  };
				  
				  xhr.onerror = (e) => {
					alert(xhr.statusText);
				  };
				  
				  if(this.lockShow)
					this.LockOn();
				
			
				xhr.open("POST", url, true);
				xhr.setRequestHeader('Pragma', 'no-cache');
				xhr.setRequestHeader('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
				xhr.setRequestHeader("If-Modified-Since", "Sat, 1 Jan 2000 00:00:00 GMT");
				xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
				xhr.timeout = this.atimeout;
				xhr.send(pst);
			}
			
			, ares: function(c, k, f, rtxt, fp) {
				//alert("c="+c+" k="+k+" f="+f);
				switch (c) {
					case 'cls:mvc:cs:cBC':
						switch (f) {
						  case 'bc':
							location.href = "?pg="+k+"&bc="+encodeURIComponent(rtxt);
						  break;
						  case 'nc':
							location.href = "?pg="+k+"&nc="+encodeURIComponent(rtxt);
						  break;
						}
					break;
					default:
						
						switch (f) {
							/*case 'idMSG':
								document.getElementById('idMSG').innerHTML = rtxt;
							break;*/
						  case 'r':
							var dvnm = c+SSD+k;
							//alert(dvnm);
							//alert(rtxt);
							document.getElementById(dvnm).innerHTML = rtxt;
							this.jq();
						  break;
						  case 'getshowed':
							if(rtxt==1) {
								this.olshowed = true;
							} else {
								this.olshowed = false;
							}
							this.OL();
						  break;
						  case 'getDial':
							document.getElementById("msgDial").innerHTML = rtxt;
							this.rckf = '';
							
						  break;
						  case 'getDialMsgCount':
							if(this.DialMsgCount!=rtxt) {
								this.UpdateMsg = true;
								this.DialMsgCount = rtxt;
							}
							this.rckf = '';
							
						  break;
						  
						  case 'ufs':
							alert(rtxt);
							location.reload();
						  break;
						  
						  default:
							
							//$r .= $this->jo.".a('?".$this->cmd."=".$this->vw.$this->ssd."r".$this->rgp."', '".$this->rpp."');";
							/*
							alert(rtxt);
							if(rtxt.substring(1, 2)=='r'.SSD) {
								alert("c="+c+" k="+k+" f="+f);
								//document.getElementById(c+SSD+k).innerHTML = rtxt;				
							} else
								alert("c="+c+" k="+k+" f="+f+" rtxt="+rtxt);*/
								alert(rtxt);
						}
						
				}
			}
		}
    </script>
</head>
<body>
<?php
	if(file_exists($oMDB->jf)) {
		$oMAP = new cMAP($oMDB);
		echo $oMAP->cmd('show');
		unset($oMAP);
	} else {
		$oCDB = new jCDB($oMDB);
		echo $oCDB->show();
		unset($oCDB);
	}
?>
</body>
</html>