<?php
	/*
	* Copyright (C) 2025 Жанибек Какимбаев
	*
	* Бұл бағдарлама GNU General Public License v3.0 (GPLv3) лицензиясы бойынша таратылады.
	* Лицензия мәтіні: https://www.gnu.org/licenses/gpl-3.0.html
	*/
?>
<!DOCTYPE html>
<html lang="ru">
   <head>
			<meta charset="UTF-8">
			<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
      <title>WALAN</title>
		
		<link href="<?php echo M; ?>jquery-ui-1.13.2.custom/jquery-ui.css" rel="stylesheet">
		<script src="<?php echo M; ?>jquery-ui-1.13.2.custom/external/jquery/jquery.js"></script>
		<script src="<?php echo M; ?>jquery-ui-1.13.2.custom/jquery-ui.js"></script>
		<script src="<?php echo M; ?>datetimepicker-master/build/jquery.datetimepicker.full.min.js"></script>
		

		<script>
		  var SSC = "<?php echo SSC; ?>";
		  var SSD = "<?php echo SSD; ?>";
		  var CMD = "<?php echo CMD; ?>";
		</script>
		<script src="jT.js"></script>
		<script>
			//jT.setwh();
			$(function() {
				jT.jq();
			});
		</script>
		<style>
			body {
				background: #E2EEF7;
				padding: 0px;
				margin: 0px;
			}


			:root {
				--mainPath: "/walan/ths/thс/";
			}

			header {
				background-color: #f2f2f2;
				display: table;
				width: calc(100%);
				margin: 0px;
				padding: 0px;
				height: 150px;
			}

			footer {
				background: #5DFE03;
				display: table;
				width: calc(100%);
				height: 200px;
			}



			.sticky {
				position: -webkit-sticky;
				position: sticky;
				top: 0;
				z-index: 9999;
			}

			.dirdiv {
				background: url(img/dirdiv.png) no-repeat;
				 height: 100%;
				 width: 100%;
			}

			#hdiv {
				background-image: url(/thm/tht/img/hl2.png);
				height: 30px;
				bottom: 0;
				left: 0;
			}

			#fdiv {
				background-image: url(/thm/tht/img/fl1.png);
				height: 30px;
				bottom: 0;
				left: 0;
			}

		</style>
	 </head>
	 <body>
		<img id="loadimg" src="<?=IMG?>load.gif" style="position: absolute; display: none; z-index:9999; top: 50%; left: 50%; width: 30px; transform: translate(-50%, -50%);"/>
		<div id="maindiv">
			<header class="sticky">
				<table>
					<tbody>
						<tr>
							<td rowspan="2" align="center">
								<a href="?pg=j">
									<img src="<?=IMG?>/nemo.png" width="100"/>
								</a>
								<br/><a href="?pg=main">Web Алаң <br/>Mvc</a></td>
						</tr>
						<tr>
							<td>
								<a href="?pg=s">SESSIONS</a>
								<?php
									$ar = $oM->getHTML('{LGS.lsd:SCF:TYPES/LGS.focM:rl}'
										, $oM->dn //cr
										, 1 //fp
										, 'sd'
										, 1 //trv
										, 'rw'
										, $oM->sd['rw']
										, $oM
									);
									echo $ar['hd'];
								?>
							</td>
							<td>
								<div style="position:fixed; width: 50px; top:20px; right:20px;">
									<a href="?pg=a"><img height="50" src="<?=IMG?>a.png"/></a>
									<a style="text-decoration: none; font-size: 3em; color: green;" href="?pg=um">㊂</a>
								</div>															
							</td>
						</tr>
						<tr>
							<td>
								
							</td>
						</tr>
					</tbody>
				</table>	
				
			</header>
			<div id="pg">
			<?php 
				$ip = [];
				$ip['n'] = 1;
				$ip['fp'] = 1;
				echo showV($oM, '\\mvc\\vs\\vPG', $ip);
			?>
			</div>
			<footer>
				<script src="https://tirlik.kz/jMSG.js"></script>
				<script>jMSG.init(1, "57F473", "https://tirlik.kz/");</script>
				
				<script src="https://tirlik.kz/jEMS.js"></script>
				<script>jEMS.init(1, "57F473", "https://tirlik.kz/");</script>
				
				
			</footer>
		</div>
   </body>
</html>
