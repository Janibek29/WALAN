<?php
	/*
	* Copyright (C) 2025 Жанибек Какимбаев
	*
	* Бұл бағдарлама GNU General Public License v3.0 (GPLv3) лицензиясы бойынша таратылады.
	* Лицензия мәтіні: https://www.gnu.org/licenses/gpl-3.0.html
	*/
	namespace mvc\vs;
	
	class vCreateSCF extends jV {
		function init() {
			$r = '';
			
			return $r;
		}
		
		function r() {
			$r = '';
			if(isset($_POST['admun'])) {
				$r .= $this->ofm();
				
			} else {
				$hd = $this->cfm();
				$tt = 'drop';
				$hd = str_replace('{#bt#}'
					, $this->b(""
						, "var dbnm=; c = confirm('$tt?'); jT.p='';"
						, "", '', $tt
					)
					, $hd);
				$r .= $hd;

			}
					return $r;
		}

		function cfm() {
			$r = '';
			ob_start();
			?>
		<!DOCTYPE html>
		<html lang="ru">
			 <head>
					<meta charset="UTF-8">
					<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
					<title>HTML Document</title>
					<style>
						.cd {
							margin-left: auto;
    					margin-right: auto;
							width:400px;
						}
					</style>
					<script>
						var SSC = "<?php echo SSC; ?>";
						var SSD = "<?php echo SSD; ?>";
						var CMD = "<?php echo CMD; ?>";
					</script>
					<script src="jT.js"></script>

			 </head>
			 <body>
				<div class="cd">
				<form method="post">
						<table><tbody>
										<tr><td>name</td><td><input name="nm" type="text" value="WALAN"/></td></tr>
										<tr><td>dbtp</td><td><select name="dbtp"><option value="1">MariaDB</option><option value="2">PostgreSQL</option><option value="3">SQLite3</option></select></td></tr>

										<tr><td>host</td><td><input name="host" type="text" value="127.0.0.1"/></td></tr>
										<tr><td>port</td><td><input name="port" type="text" value=""/></td></tr>


										<tr><td>un</td><td><input name="un" type="text" value="root"/></td></tr>
										<tr><td>pw</td><td><input name="pw" type="password" value="root"/></td></tr>
										<tr><td>db</td><td><input name="dbnm" type="text" value="walan"/></td></tr>
										<tr><td>drop</td><td><select name="drop"><option value="1">create</option><option value="2">dropcreate</option></select></td></tr>

										<tr><td>admin</td><td><input name="admun" type="text" value="admin"/></td></tr>
										<tr><td>admpw1</td><td><input name="admpw1" type="text" value="admin"/></td></tr>
										<tr><td>admpw2</td><td><input name="admpw2" type="text" value="admin"/></td></tr>
										<tr><td></td><td><input type="submit" value="Create"/></td></tr>
						</tbody></table>
					</form>
				</div>
			 </body>
		</html>
						<?php
			$r .= ob_get_contents();
			ob_end_clean();
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
					<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
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
					<a href="">Open</a>
				</div>
			 </body>
		</html>
						<?php
			$r .= ob_get_contents();
			ob_end_clean();
			return $r;
		}

	}
?>
