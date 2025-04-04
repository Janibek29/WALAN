<?php
	/*
	* Copyright (C) 2025 Жанибек Какимбаев
	*
	* Бұл бағдарлама GNU General Public License v3.0 (GPLv3) лицензиясы бойынша таратылады.
	* Лицензия мәтіні: https://www.gnu.org/licenses/gpl-3.0.html
	*/
	namespace mvc\ms;
	class jM {
		var $trv;
		var $fs;
		var $rw;
		var $it;
		var $dn;
		var $n;
		var $sd;
		var $dbSCF;
		var $db;
		var $dbtp;
		var $w;
		var $h;
		var $vt;
		var $jf;
		var $ja;
		var $ip;
		var $wurl;
		var $cmd;
		var $ssc;
		var $ssd;
		var $jo;
		var $s;
		var $selfs;
		var $seltp;
		var $q;
		var $vw;
		
		function __construct($ip) {
			$this->it = [];
			$this->ip = $ip;
			if(isset($this->ip['n']))
				$this->n = $this->ip['n'];
			if(isset($this->ip['cmd']))
				$this->cmd = $this->ip['cmd'];
			if(isset($this->ip['ssc']))
				$this->ssc = $this->ip['ssc'];
			if(isset($this->ip['ssd']))
				$this->ssd = $this->ip['ssd'];
			if(isset($this->ip['jo']))
				$this->jo = $this->ip['jo'];
			
			$this->dn = get_class($this);
			//$this->logs($this->dn);
			$this->dn = str_replace('\\', $this->ssc, $this->dn);
			$this->dn = $this->dn.$this->ssd.$this->n;
			$this->vw = $this->dn;
			$this->getSD();
			if(!isset($this->sd['wnm']))
				$this->sd['wnm'] = '';
			if(!isset($this->sd['uid']))
				$this->sd['uid']=0;
			if(!isset($this->sd['rw']))
				$this->sd['rw']=[];
			if(!isset($this->sd['wid']))
				$this->sd['wid'] = 0;
		}
		
		function __destruct() {
			$this->setSD();
		}
		
		
		
		function init() {
			if(!isset($this->sd['rw']['d']['LGS'])) {
				$this->sd['rw']['d']['LGS'] = $this->getSID('TYPES/LGS/KZ');
				$srw = $this->getRWS($this->sd['rw']['d']['LGS']
					, 'nm'
				);
				$this->sd['rw']['m']['LGS'] = $srw['NM'];
			}
			if(!isset($this->sd['cn'])) {
				$this->sd['cn'] = '';
			}
			if(!isset($this->sd['crt'])) {
				$this->sd['crt'] = '';
			}
			if (isset($_SERVER['SSL_CLIENT_CERT'])) {
				// Парсим сертификат для получения CN
				$certData = openssl_x509_parse($_SERVER['SSL_CLIENT_CERT']);
				if(isset($certData['subject']['CN']))
					$this->sd['cn'] = $certData['subject']['CN']; // CN из сертификата
			}
			
			//Сертификат бойынша авторлау
			
			if($this->sd['uid']==0 && $this->sd['cn']!='') {
				$ip = [];
				$ip['n'] = 1;
				$ip['fp'] = 1;
				$oA = new \mvc\cs\cA($this, $ip);
				$oA->acn($this->sd['cn']);
				unset($oA);
			}
			
			if(!isset($this->sd['REMOTE_ADDR'])) {
				$this->sd['wid'] = $this->startWALANSID();
			}
		}
		
		function logs($d) {
			$dbgt=debug_backtrace();
			file_put_contents('logs.log', "\r\n".date("Y-m-d h:i:s").' '.$dbgt[0]['file'].' '.$dbgt[0]['line'].' '.$d, FILE_APPEND); //print_r($dbgt, true)
		}
		
		
		function startWALANSID() {
			$r = 0;
			$qs = [];
			$this->sd['REMOTE_ADDR'] = $_SERVER['REMOTE_ADDR'];
			$ip = $this->Get_User_Ip();
			$r = $this->ivSCF("sss");
			//$SxGeo = new SxGeo(MD.'SxGeo22_API/SxGeo.dat', SXGEO_BATCH | SXGEO_MEMORY);
			// получаем двухзначный ISO-код страны (RU, UA и др.)
			//$country_code = $SxGeo->getCountry($ip);
			$SxGeo = new \SxGeo(MD.'SxGeo22_API/SxGeoCity.dat', SXGEO_BATCH | SXGEO_MEMORY);
			$city = $SxGeo->get($ip);
			// также можно использовать следующий код
			// $SxGeo->getCity($ip);

			// широта
			$lat = '';
			
			if(isset($city['city']['lat']))
				$lat = $city['city']['lat'];
			// долгота
			$lon = '';
			if(isset($city['city']['lon']))
				$lon = $city['city']['lon'];
			// название города на русском языке
			$city_name_ru = '';
			if(isset($city['city']['name_ru']))
				$city_name_ru = $city['city']['name_ru'];
			// название города на английском языке
			
			$city_name_en = '';
			if(isset($city['city']['name_en']))
				$city_name_en = $city['city']['name_en'];
			//$city_name_en = \geoip_country_name_by_name($ip);
			// ISO-код страны
			$country_code = '';
			if(isset($city['country']['iso']))
				$country_code = $city['country']['iso'];
			//$country_code = \geoip_country_code_by_name($ip);
			//$country_timezone = $city['country']['timezone'];
			// для получения информации более полной информации (включая регион) можно осуществить через метод getCityFull
			$city = $SxGeo->getCityFull($ip);
			// название региона на русском языке
			$region_name_ru = '';
			if(isset($city['region']['name_ru']))
				$region_name_ru = $city['region']['name_ru'];
			// название региона на английском языке
			$region_name_en = '';
			if(isset($city['city']['name_en']))
			$region_name_en = $city['city']['name_en'];
			// ISO-код региона
			//$region_name_iso = $city['city']['iso'];
			//$q = "insert into sss (id, rip) values ($r, '".$_SERVER['REMOTE_ADDR']."')";
			$sid = session_id();
			$q = "insert into sss (id, rip, cc, cn, rn, sid) values ($r, '".$ip."', '".$country_code."', '".$city_name_en."', '".$region_name_en."', '$sid')";
			$qs[] = $q;
			$this->qscSCF($qs);
			return $r;
		}
		
		function updateUID() {
			$r = '';
			if($this->sd['wid']>0) {
				$qs = [];
				$q = 'update sss set uid='.$this->sd['uid'].' where id='.$this->sd['wid'];
				$qs[] = $q;
				$this->qscSCF($qs);
			}
			return $r;
		}
		
		function Get_User_Ip() {
			$IP = false;
			if (getenv('HTTP_CLIENT_IP'))
			{
				$IP = getenv('HTTP_CLIENT_IP');
			}
			else if(getenv('HTTP_X_FORWARDED_FOR'))
			{
				$IP = getenv('HTTP_X_FORWARDED_FOR');
			}
			else if(getenv('HTTP_X_FORWARDED'))
			{
				$IP = getenv('HTTP_X_FORWARDED');
			}
			else if(getenv('HTTP_FORWARDED_FOR'))
			{
				$IP = getenv('HTTP_FORWARDED_FOR');
			}
			else if(getenv('HTTP_FORWARDED'))
			{
				$IP = getenv('HTTP_FORWARDED');
			}
			else if(getenv('REMOTE_ADDR'))
			{
				$IP = getenv('REMOTE_ADDR');
			}

			//If HTTP_X_FORWARDED_FOR == server ip
			if((($IP) && ($IP == getenv('SERVER_ADDR')) && (getenv('REMOTE_ADDR')) || (!filter_var($IP, FILTER_VALIDATE_IP))))
			{
				$IP = getenv('REMOTE_ADDR');
			}

			if($IP)
			{
				if(!filter_var($IP, FILTER_VALIDATE_IP))
				{
					$IP = false;
				}
			}
			else
			{
				$IP = false;
			}
			return $IP;
		}
		
		function cSCFSQLT($q, $dbn, $tn) {
			$r = false;
			$sq = "
			  select 1
				from information_schema.tables 
			   where table_schema='$dbn' 
				 and table_name='$tn';
			";
			$rs = $this->sSCF($sq);
			if(count($rs)==0) {
				$qs = [];
				$qs[] = $q;
				$this->qscSCF($qs);		
			} else {
				$r = true;
				echo "$tn кестесі бар";
			}
			return $r;
		}
		
		function GT($tn, $its) {			
			$tbid = 0;
			$pkfid = 0;
			$qs = $this->g($this->ja['db']['dbnm'], $tn, $this->ja['scf']['tables'], $tbid, $pkfid, $its);
			
			//print_r($qs);
			$this->qscSCF($qs);
			return $pkfid;
		}
		
		function g($dbn, $tn, $tbs_id, &$tb_id, &$pkf, $its) {
			$r = '';
			$qs = [];
			$tid = 0;
			$terr = '';
			//SCF кестесін іздеу
			$q = "select id from scf where pd=".$this->ja['scf']['tables']." and upper(nm)=upper('$tn')";
			$rs = $this->sSCF($q);
			$cnt = count($rs);
			if($cnt==1) {
				$tid = $rs[1]['ID'];
			} elseif($cnt>1) {
				$tid = $rs[1]['ID'];
				$terr .= 'Кесте көп';
			}
			
			$q = "
						select table_comment 
						  from information_schema.tables 
						 where table_schema = '$dbn' 
						   and table_name = '$tn'";
			$rs = $this->sSCF($q);
			$tc = $rs[1]['TABLE_COMMENT'];
			
			$q = "
				select upper(c.table_name) tn
					   , upper(c.column_name) fn
					   , upper(c.column_key) tp
					   , upper((select kcu.referenced_table_name
							from information_schema.key_column_usage kcu
						   where kcu.constraint_schema = c.table_schema
							 and kcu.referenced_table_schema = kcu.constraint_schema
							 and kcu.table_name = c.table_name
							 and kcu.column_name = c.column_name
						  )) rtn 
					   , upper((select kcu.referenced_column_name
							from information_schema.key_column_usage kcu
						   where kcu.constraint_schema = c.table_schema
							 and kcu.referenced_table_schema = kcu.constraint_schema
							 and kcu.table_name = c.table_name
							 and kcu.column_name = c.column_name
						  )) rfn
					   , c.data_type
					   , c.column_comment fc
					   , c.column_type
				  from information_schema.columns c
				 where c.table_schema = '$dbn'
				   and c.table_name = '$tn'
			";
			$rs = $this->sSCF($q); //print_r($rs);
			  
			if($tid==0) {
				$tid = $this->ivSCF('scf');
				$pd = $this->ja['scf']['tables'];
				$q = "insert into scf (id, pd, nm, tt, tp, s, its) values (
					$tid, $pd, '$tn', '$tn', null, 1, '$its'
				)";
				$qs[] = $q;
				$tb_id = $tid;
			} else {
				$tb_id = $tid;
			}
			$pkffn = '';
			$pdf = '';
			$pcf = '';
			$ttf = '';
			foreach($rs as $rk=>$rw) {
				//SCF өрісін іздеу
				$fn = $rw['FN'];
				$fc = $rw['FC'];
				$fid = 0;
				$ftp = 0;
				$ferr = '';
				if($tid>0) {
					$q = "select id from scf where pd=$tid and upper(nm)=upper('$fn')";
					//echo $q;
					$rs1 = $this->sSCF($q);
					$cnt = count($rs1);
					if($cnt==1) {
						$fid = $rs1[1]['ID'];
					} elseif($cnt>1) {
						$fid = $rs1[1]['ID'];
						$ferr .= 'Өріс '.$fn.' көп';
					}
				}
				
				if($ferr=='') {
					if($rw['TP']=='PRI') { //PKF
						$q = "update scf set pkf='$fn' where id=$tid";
						$qs[] = $q;
					}
					/*
					if($fid==0) //Егер Өріс болмаса
						$id = $this->ivSCF('scf');
					  
					if($rw['TP']=='PRI') {
						$ftp = $this->ja['fld']['pkf'];						
						if($fid>0) { //Егер Өріс бар болса
							$pkf = $fid;
						} else {
							$pkf = $id;
						}
						$pkffn = $fn;
					} elseif($rw['FN']=='RS') {
						$ftp = $this->ja['fld']['frs'];						
					} elseif($rw['FN']=='FM') {
						$ftp = $this->ja['fld']['fm'];
					} elseif($rw['DATA_TYPE']=='int') {
						$ftp = $this->ja['fld']['int'];
					} elseif($rw['DATA_TYPE']=='tinyint') {
						if($rw['COLUMN_TYPE']=='tinyint(1)') {
							$ftp = $this->ja['fld']['bool'];
						} else {
							$ftp = $this->ja['fld']['int'];
						}
					} elseif($rw['DATA_TYPE']=='varchar') {
						$ftp = $this->ja['fld']['varchar'];
					} elseif($rw['DATA_TYPE']=='text') {
						$ftp = $this->ja['fld']['text'];					  
					} elseif($rw['DATA_TYPE']=='date') {
						$ftp = $this->ja['fld']['date'];
					} elseif($rw['DATA_TYPE']=='timestamp') {
						$ftp = $this->ja['fld']['timestamp'];
					} elseif($rw['DATA_TYPE']=='decimal') {
						$ftp = $this->ja['fld']['decimal'];
					}
					
					$ar = explode('|', $fc);
					$cnt = count($ar);
					
					if($cnt==2) {
						$fc = $ar[0];
						if($ar[1]=='acn') {
							$ftp = $this->ja['fld']['acn'];
						}
						if($ar[1]=='aca') {
							$ftp = $this->ja['fld']['aca'];
						}
						if($ar[1]=='pdf') {
							$pdf = $fn;
							$ftp = $this->ja['fld']['pdf'];
						}
						if($ar[1]=='pcf') {
							$pcf = $fn;
							$ftp = $this->ja['fld']['pcf'];
						}
						if($ar[1]=='ttf') {
							$ttf = $fn;
							$ftp = $this->ja['fld']['ttf'];
						}
					}
					
					
					
					if($ftp>0) {
						if($fid>0) { //Егер Өріс бар болса
							$qs[] = $this->oM->uqscf($fid, $tb_id, $ftp, $fn, $fc);
						} else {
							$qs[] = $this->oM->iqscf($id, $tb_id, $ftp, $fn, $fc);	
						}
					}
					*/
				} else {
					echo $ferr;
				}
			}
			
			//TABLE
			
			$ar = explode('|', $tc);
			$cnt = count($ar);
			$tbtp = '';
			$ll = 0;
			if($cnt==3) {
				$tbtp = $ar[0];
				$tc = $ar[1];
				if(substr($ar[2], 0, 3)=='ll=') {
					$ll = substr($ar[2], 3);
				}
			}
			$q = "update scf set ll=$ll where id=$tb_id";
			$qs[] = $q;
			//echo 'tc='.$tc.'tbtp='.$tbtp.'pdf='.$pdf.'pcf='.$pcf.'ttf='.$ttf;
			if($tbtp=='tree' && !($pdf=='' && $pcf=='' && $ttf=='')) {
				$q = "insert into $tn ($pkffn, $ttf) values (1, 'ROOT')";
				$qs[] = $q;
			}
					
			//ADMIN RLS TABLES
			
			if($terr=='') {
				
				$tc = $tn;
				$q = "
				  select table_comment tc
					from information_schema.tables 
				   where table_schema='$dbn' 
					 and table_name='$tn';
				";
				$rs = $this->sSCF($q);
				if(count($rs)==1) {
					$tc = $rs[1]['TC'];
				}
				$tmpd = $this->ja['rs']['tables'];
				
				if($tid>0) { 
					$q = "select id from scf where pd=$tmpd and tp=$tb_id";
					//echo $q;
					$rs1 = $this->sSCF($q);
					if(count($rs1)==1) {//Егер Мәзірде кесте табылса
						$mid = $rs1[1]['ID'];
						$q = "update scf set pd=$tmpd, tp=$tid, tt='$tn' where id=$mid";
						$qs[] = $q;
						/*$qs[] = $this->oM->uqscf($mid, $tmpd, $tb_id, $tn, $tc);
						function uqscf($id, $pd, $tp, $nm, $tt) {
							return ;
						}*/
					} else {
						$mid = $this->ivSCF('scf');
						$q = "insert into scf (id, pd, tp, tt) values ($mid, $tmpd, $tid, '$tn')";
						$qs[] = $q;
					}
					/*
					$q = "select id from scf where pd=$tmpd and tp=$pkf";
					$rs1 = $this->sSCF($q);
					if(count($rs1)==1) {//Егер Мәзірде кестегеҚосу табылса
						$mid = $rs1[1]['ID'];
						$qs[] = $this->oM->uqscf($mid, $tmpd, $pkf, $tn, $tc.'+');
					}*/			
				} else {
					$id = $this->oM->iv('scf'); $qs[] = $this->oM->iqscf($id, $tmpd, $tb_id, $tn, $tc);
					$id = $this->oM->iv('scf'); $qs[] = $this->oM->iqscf($id, $tmpd, $pkf, $tn, $tc.'+');
				}
				
			} else {
				echo $terr;
			}
			//print_r($qs);
			return $qs;
		}
		
		function createTF($tn, $c) {
			$dn = TBS.$tn.'/';
			if(!file_exists($dn)) {
				mkdir($dn);
			}
			$uf = $dn.'/uf';
			if(!file_exists($uf)) {
				mkdir($uf);
			}
			$fn = $dn.$tn.'.html';
			if(!file_exists($fn)) {
				file_put_contents($fn, $c);
			}
		}
		
		function createUP12($un, $pw) {
			/*echo $rw['d']['ID'];
			
			$output=null;
			$retval=null;
			exec('whoami', $output, $retval);
			echo "Returned with status $retval and output:\n";
			print_r($output);*/

			// Установите путь к файлам CA и ключу
			$caCertFile = KYS.'ca.crt';
			$caKeyFile = KYS.'ca.key';
			$caPassphrase = 'CA20241212'; // Если у CA есть пароль

			// Создание приватного ключа пользователя
			$userKeyResource = openssl_pkey_new([
				"private_key_bits" => 2048,
				"private_key_type" => OPENSSL_KEYTYPE_RSA,
			]);

			// Создание запроса на сертификат (CSR)
			$userCsrResource = openssl_csr_new([
				"commonName" => $un,
				"organizationName" => "TIRLIK",
				"organizationalUnitName" => "IT Department",
				"localityName" => "Qyzylorda",
				"stateOrProvinceName" => "Qyzylorda",
				"countryName" => "KZ",
			], $userKeyResource);

			// Генерация сертификата с подписанием от CA
			$caCert = file_get_contents($caCertFile);
			$caKey = openssl_pkey_get_private(file_get_contents($caKeyFile), $caPassphrase);

			$certificateValidityDays = 365; // Срок действия сертификата (в днях)
			$serialNumber = openssl_random_pseudo_bytes(16); // Генерация случайного серийного номера

			// Подписываем CSR с помощью сертификата CA и приватного ключа CA
			$userCsrResource = openssl_csr_sign($userCsrResource, $caCert, $caKey, $certificateValidityDays, [
				'serialNumber' => $serialNumber,
			]);

			// Сохранение сертификата в файл user.crt
			openssl_x509_export($userCsrResource, $userCert);

			// Сохранение сертификата на диск
			//file_put_contents('user.crt', $userCert);

			// Сохранение приватного ключа на диск
			//openssl_pkey_export_to_file($userKeyResource, 'user.key');
			
			// Путь к итоговому файлу PKCS#12
			$p12File = KYS.$un.'.p12';
			// Пароль для защиты p12
			$password = $pw;
			//echo "Сертификат успешно создан и сохранен в файлы user.crt и user.key.";
			openssl_pkcs12_export_to_file($userCert, $p12File, $userKeyResource, $password);
		}
		
		function setUPW($uid, $pw) {
			$qs = [];
			$hpw = password_hash($pw, PASSWORD_BCRYPT);
			$q = "update scf set pw='$hpw' where id=$uid";
			$qs[] = $q;
			$this->qscSCF($qs);
		}
		
		function getOC($cr, $sd, $fp, $trv, $ftp, $rk) {
			$oc = $cr;
			$oc .= $this->ssd.$fp;
			$oc .= $this->ssd.$sd;
			$oc .= $this->ssd.$trv;
			$oc .= $this->ssd.$ftp;
			$oc .= $this->ssd.$rk;
			$oc .= $this->ssd.'fn';
			return $oc;
		}
		
		function getHTML($hd, $cr, $fp, $sd, $trv, $ftp, $rw, $o) {
			$r = [];
			$fs = [];
			preg_match_all('/\{(.+?)\}/', $hd, $m);
			foreach($m[1] as $k=>$h) {
				$ar = explode('.', $h);
				$fn = $ar[0];
				$f['ID'] = $k;
				$f['NM'] = $fn;
				$f['H'] = $h;						
				$fs[$fn] = $f;
			}
			if(!isset($rw['rk'])) {
				$rw['rk'] = 1;
				$rw['trv'] = $trv;
			}
			$oc = $this->getOC($cr, $sd, $fp, $trv, $ftp, $rw['rk']);	
			
			$ip = [];
			$oCL = new \mvc\vs\vCL($this, $ip);
			$oCL->o = $o;
			
			foreach($fs as $fn=>$f) {
				if($ftp=='rs') {
					$url = $this->sd['url'];
					$url .= '&'.$this->oM->cmd.'=';
					$url .= $this->pcr;
					
					$d = '<a href="'.$url.'ob&fn='.$fn.'">'.$f['ID'].'</a>';
					$hd = str_replace('{'.$f['H'].'}', $d, $hd);
				} elseif($ftp=='rw') {
					$tg = $oCL->cl($oc, $f['H'], $rw);
					$hd = str_replace('{'.$f['H'].'}', $tg, $hd);
				}
			}
			unset($oCL);
			
			
			$r['fs'] = $fs;
			$r['hd'] = $hd;
			return $r;
		}
		
		function getHTMLITS($hd, $cr, $fp, $sd, $trv, $ftp, $rw, $o) {
			$r = [];
			//ITS
			$fd = [];
			//htmlspecialchars(print_r($hd));
			preg_match_all('/\[(.+?)\]/', $hd, $ms);
			foreach($ms[1] as $k=>$h) {
				if(!isset($fd['ils'])) {
					$ar = explode(',', $h);
					
					foreach($ar as $k1=>$a) {
						$ar1 = explode('.', $a);
						
						$il = [];
						$mn = trim($ar1[0]);
						//echo htmlspecialchars($ar1[0]);
						//$r .= 'mn'.$mn;
						$il['MD'] = $this->getTRV($mn);
						$il['MN'] = $mn;
						$trw1 = $this->getRWS($il['MD']
							, 'tt'
						);
						if(isset($trw1['TT'])) {
							$il['TT'] = $trw1['TT'];
							$il['FK'] = $ar1[1];
							$il['C'] = 0;
							if($k1==0)
								$il['C'] = 1;
						}
						$fd['ils'][$mn] = $il;
					}
				}
				$hd = str_replace('['.$h.']', '', $hd);
			}
			
			if(isset($this->oC->fd['ils'])) {
				$r .= $this->showILS();
				foreach($this->oC->fd['ils'] as $mn=>$it) {
					if($it['C']==1) {
						$trv = $it['MD'];
						//$this->oC->fd['trv'] = $trv;//$it['FK'];
						//$r .= '<option '.$s.' value="'.$it['MD'].'">'.$mn.'.'.$fk.'</option>';
						$r .= $this->showRS($trv);
						
					}
				}
			}
			
			//$r['fs'] = $fs;
			$r['hd'] = $hd;
			return $r;
		}
		
		function rwACE(&$rw) {
			$ar = $this->getACH($rw['d']['D']);
			$rw['h']['DS1'] = $ar['S1'];
			$rw['h']['DS2'] = $ar['S2'];
			$rw['h']['DS3'] = $ar['S3'];
			//$this->logs($rw['d']['D'].$ar['S1']);
			
			$ar = $this->getACH($rw['d']['C']);
			$rw['h']['CS1'] = $ar['S1'];
			$rw['h']['CS2'] = $ar['S2'];
			$rw['h']['CS3'] = $ar['S3'];
			
			
		}
		
		function getACH($ac) {
			$r = [];
			$r['S1'] = '';
			$r['S2'] = '';
			$r['S3'] = '';
			if($ac>0) {
				$q = "
				select (select nm from scf where id=acs.s1) s1
				       , (select nm from scf where id=acs.s2) s2
					   , (select nm from scf where id=acs.s3) s3 
				  from acs 
				 where id=$ac";
				//$this->logs($q);
				$rs = $this->sSCF($q);
				if(count($rs)==1) {
					if($rs[1]['S1']!='')
						$r['S1'] = 'acd:'.$rs[1]['S1'];
					if($rs[1]['S2']!='')
						$r['S2'] = 'acd:'.$rs[1]['S2'];
					if($rs[1]['S3']!='')
						$r['S3'] = 'acd:'.$rs[1]['S3'];
				}
			}
			return $r;
		}
		
		function TE($dbtp, $dbnm, $dbsc, $tn) {
		  $r = false;
		  switch($dbtp) {
				case $this->ja['sys']['md']:
					$q = "select count(*) cnt from information_schema.tables 
					   where upper(table_schema) = upper('$dbnm') 
					   and upper(table_name) = upper('$tn')";
					$rs = $this->s($q);
					if($rs[1]['CNT']==1)
						$r = true;
					//print_r($rs);
				break;
				case $this->ja['sys']['pg']:
					$q = "select count(*) cnt from information_schema.tables 
					 where upper(table_catalog) = upper('$dbnm') 
					   and upper(table_schema) = upper('$dbsc') 
					   and upper(table_name) = upper('$tn')";
					   //echo $q;
					$rs = $this->s($q);
					if($rs[1]['CNT']==1)
						$r = true;
					//print_r($rs);
				break;
				case $this->ja['sys']['sl']:
					$q = "";
				break;				
		  }
		  return $r;
		}
		
		function TFS($dbtp, $dbnm, $dbsc, $tn) {
		  $r = [];
		  switch($dbtp) {
				case $this->ja['sys']['md']:
					$q = "select upper(column_name) cn, upper(data_type) tp
					from information_schema.columns 
					where upper(table_schema) = upper('$dbnm')
					and upper(table_name) = upper('$tn')";
					$q = "
					select upper(c.table_name) tn
						   , upper(c.column_name) cn
						   , upper(c.column_key) ck
						   , upper(k2.referenced_table_name) rtn 
						   , upper(k2.referenced_column_name) rcn
						   , c.data_type tp
						   , c.column_comment fc
						   , c.column_type tp1
					  from information_schema.columns c
					  join information_schema.key_column_usage k2
						on k2.constraint_schema = c.table_schema
						and k2.referenced_table_schema = k2.constraint_schema
						and k2.table_name = c.table_name
						and k2.column_name = c.column_name
					 where upper(c.table_schema) = upper('$dbnm')
					   and upper(c.table_name) = upper('$tn')";
					$r = $this->s($q);
				break;
				case $this->ja['sys']['pg']:
				$q = "
				select k1.table_schema,
       k1.table_name,
       k1.column_name,
       k2.table_schema as referenced_table_schema,
       k2.table_name as referenced_table_name,
       k2.column_name as referenced_column_name
from information_schema.key_column_usage k1
join information_schema.referential_constraints fk using (constraint_schema, constraint_name)
join information_schema.key_column_usage k2
  on k2.constraint_schema = fk.unique_constraint_schema
 and k2.constraint_name = fk.unique_constraint_name
 and k2.ordinal_position = k1.position_in_unique_constraint;
				";
					$q = "select upper(column_name) cn, upper(data_type) tp, '' rtn, '' rcn
					from information_schema.columns
					where upper(table_catalog) = upper('$dbnm')
					  and upper(table_schema) = upper('$dbsc')
					  and upper(table_name) = upper('$tn')";
					$r = $this->s($q);
				break;
				case $this->ja['sys']['sl']:
					$q = "";
				break;				
		  }
		  return $r;
		}
		
		function getOB($fs) {
		  $r = array();
		  $i = 0;
		  $s = '';
		  $odr = '';
		  //print_r($fs);
		  foreach($fs as $fn=>$f) {
			  $i++;
			  if(isset($f['OB']) && ($f['OB']>0 || $f['OB']<0)) {
				if($odr!='')
				  $odr .= ',';
				if($f['OB']==1)
				  $odr .= $fn;
				elseif($f['OB']==-1)
				  $odr .= $fn.' desc';
			  }			
		  }		  
		  $r = $odr;
		  return $r;
		}
		
		function addSGN($dc, &$rw, $txt) {
			$qs = [];
			$uid = $this->sd['uid'];
			$q = "insert into sgn (dc, txt, ud) values ($dc, '$txt', $uid)";
			$qs[] = $q;
			$this->qscSCF($qs);
			$this->setRWSGN($rw);
		}
		
		
		function setRWSGN(&$rw) {
			$r = 0;
			if($this->sd['uid']>0 && isset($rw['dcf']) && isset($rw['d'][$rw['dcf']])) {
				$dc = $rw['d'][$rw['dcf']];
				$uid = $this->sd['uid'];
				$q = 'select count(*) cnt from sgn where dc='.$dc;
				$rs = $this->sSCF($q);
				$rw['sgc'] = $rs[1]['CNT'];
				
				$q = 'select count(*) cnt from sgn where dc='.$dc.' and ud='.$uid;
				$rs = $this->sSCF($q);
				$rw['sgn'] = $rs[1]['CNT'];
				
				if($rw['sgc']>0) {
					$q = 'select id, txt, ud, dt, (select tt from scf where id=sgn.ud) un from sgn where dc='.$dc;
					$rw['sgr'] = $this->sSCF($q);
				}
			}
			return $r;
		}
		
		function getDCACE($dc, $fs) {
			$rs = [];
			//$q = "select * from ace where dc=$dc";
			$q = "select * from ace";
			$trv = $this->getSID('TABLES/ACE');
			$trw1 = $this->getRWS($trv
			   , 'id, nm, uf, s, tp, pkf, pdf, ttf, dcf, udf, fmf, ctf'
				 .', (select tp from scf s1 where s1.id=scf.tp) ptp'
			);
			$rs1 = $this->sSCF($q);
			foreach($rs1 as $rk=>$d) {				
				$rw = $this->trw($d);
				$rw['rk'] = $rk;
				$this->setM($fs, $rw);
				$this->setH($fs, $rw);
				$this->setRW($rw, $trw1);
				$rs[$rk] = $rw;
				
			}
			return $rs;
		}

		function setH($fs, &$rw) {
			$this->rwACE($rw);
			foreach($fs as $fn=>$f) {
				if(isset($f['H'])){
					$ar = explode('.', $f['H']);
					$ar = explode(':', $ar[1]);
					$tp = $ar[0];
					if(in_array($tp, ['acd', 'lsd'])) {
						$v = $rw['d'][$fn];
						
						if($v>0) {
							$q = 'select trv, pkv from sds where id='.$v;
							//echo $q;
							$rs = $this->sSCF($q);
							if(count($rs)==1) {
								
								$ftrv = $rs[1]['TRV'];
								$pkv = $rs[1]['PKV'];
								$trw1 = $this->getRWS($ftrv
									, 'nm, dcf, tp'
								);
								
								$trw2 = $this->getRWS($trw1['TP']
									, 'id, dbhost, dbport, un, pw, dbnm, tp'
								);
								$kf = $trw1['DCF'];
								$ar = $this->acq($ftrv);
								$acq = $ar['acq'];
								//$this->logs(__FILE__.__LINE__.$trw1['NM'].' trv='.$ftrv.' pkv='.$pkv.' acq='.$acq);
								//$rw['m'][$fn] = 'm';
								if($pkv>0 && $acq!='') {
									//$this->logs(__FILE__.__LINE__.$fn.$tp.$v);
									$acq = str_replace('{kf}', $kf, $acq);
									$acq = str_replace('{pkv}', $v, $acq);
									$acq = str_replace('{t}', '', $acq);
									//echo $acq;
									//echo $this->ac($ftrv, $ar['s'], $acq);
									//$this->logs(__FILE__.__LINE__.$tp.$acq);
									if($ar['s']=='1') {
										$rs = $this->sSCF($acq);
									} else {
										$this->conn($trw2['TP']
											, $trw2['DBHOST']
											, $trw2['DBPORT']
											, $trw2['UN']
											, $trw2['PW']
											, $trw2['DBNM']
										);
							
										$rs = $this->selrs($this->dbtp, $this->db, $acq);									
									}
									//$this->logs(' s='.$ar['s'].print_r($rs, true));
									
									if(count($rs)==1) {
										$id = $rs[1]['ID'];
										$tt = $rs[1]['TT'];
										$rw['m'][$fn] = $tt;
										$rw['h'][$fn] = 'acd:'.$trw1['NM'];
										//$this->logs(__FILE__.__LINE__.' h='.$rw['h'][$fn]);
									}
									//print_r($rs);
								}
							}
						}
					}
				}
			}	
		}



		function getW($fs) {
			//Іздеу
			$i=0;
			$sr = '';
			foreach($fs as $fn=>$f) {
			  $i++;
			  $srd = '';
			  $pfn = 't.'.$fn;
			  //echo $f['SCMP'].' ';
			  //print_r($f);
			  if(isset($f['SCMP']) && isset($f['STXT']) && $f['SCMP']>0) {
				if($f['STXT']!='') {
				  $ar = explode('|', $f['STXT']);
				  $s1d = $ar[0];
				  if(isset($ar[1]))
					$s2d = $ar[1];
				}
				switch($f['SCMP']) {
				  
				  case 1: //Тең
					$srd = $pfn."='".$s1d."' ";
				  break;
				  case 2: //Аралық
					$srd = $pfn." between '".$s1d."' and '".$s2d."'";
				  break;
				  case 3: //Үлкен немесе тең
					$srd = $pfn." >= '".$s1d."'";
				  break;
				  case 4: //Кіші немесе тең
					$srd = $pfn." <= '".$s1d."'";
				  break;
				  case 5: //Кездеседі
					$srd = $pfn." like '%".$s1d."%'";
				  break;
				  case 6: //Кездеседі басында
					$srd = $pfn." like '".$s1d."%'";
				  break;
				  case 7: //Кездеседі аяғында
					$srd = $pfn." like '%".$s1d."'";
				  break;
				  case 8: //Тізімнен
					$srd = $pfn." in (".$s1d.")";
				  break;
				}
			  }
			  
			  if($sr!='' && $srd!='')
				$sr .= ' and ';
			  
			  $sr .= $srd;

			}
			return $sr;
		}
		
		function getFMS($trv) {
	    $fms = [];
			$q = "
			  select id, nm, tt 
				  from scf
				 where id in (select fms from scf s1 where s1.id=$trv)
			";
			$fms = $this->sSCF($q);
			return $fms;
		}
		
		
		function qsh($qs) {
			$r = '';
			foreach($qs as $k=>$sql) {
				$r .= '<br/>'.$sql.';';
			}
			return $r;
		}

		
		function getSaveRSSQL(&$rs) {
			$qs = [];
			foreach($rs as $rk=>$rw) {
				if($rw['sts']=='ins') {
					if($rw['d'][$rw['pkf']]=='') {
						$rw['d'][$rw['pkf']] = $this->iv($rw['tp']
							, $rw['tn'], $rw['pkf']
						);
						$rs[$rk] = $rw;
					}
				}
			}
			foreach($rs as $rk=>$rw) {
				if($rw['sts']=='ins' || $rw['sts']=='upd') {

					if(isset($rs[$rw['pk']]['d'][$rw['pkf']]))
						$rw['d'][$rw['pdf']] = $rs[$rw['pk']]['d'][$rw['pkf']];
					
					$qs[] = $this->getSaveRWSQL($rw);
					$rs[$rk] = $rw;

				}
			}
			return $qs;
		}
		

		function getSaveRWSQL(&$rw) {
			$s = '';
			$tn = $rw['tn'];
			$pkf = $rw['pkf'];
			$pkv = $rw['d'][$pkf];
			$dcf = $rw['dcf'];
			$trv = $rw['trv'];
			
			$sts = $rw['sts'];
			switch($sts) {
				case 'ins':
					if($pkv == '') {
						
						$pkv = $this->iv($rw['tp']
							, $tn, $pkf
						);
						$rw['d'][$pkf] = $pkv;
						//$this->logs('tp='.$rw['tp']);
					}
					
					if($dcf!='' && $rw['d'][$dcf]=='') {						
						$dcv = $this->dcv($trv
							, $pkv
						);
						$rw['d'][$dcf] = $dcv;
					}
					$i = 0;
					$s = 'insert into '.$tn;
					$s1 = '';
					foreach($rw['d'] as $fn=>$fv) {
                
						$i++;
						if ($i==1) {
						  $s .= "(".$fn;
						  $s1 .= '('.$this->nv($fv);
						} else {
						  $s .= ",".$fn;
						  $s1 .= ','.$this->nv($fv);
						}
					}
            
					$s .= ') values '.$s1.')';
					$rw['sts'] = 'upd';
				break;
				case 'upd':
					//echo __FILE__.__LINE__.'dcf='.$dcf.' trv='.$rw['trv'].' pkv='.$pkv.'dc='.$rw['d'][$dcf];
					if($dcf!='' && $rw['d'][$dcf]=='') {						
						$dcv = $this->dcv($trv
							, $pkv
						);
						$rw['d'][$dcf] = $dcv;
					}
					$s = 'update '.$tn.' ';
					$s1 = '';
					$i = 0;
					foreach($rw['d'] as $fn=>$fv) {
						$s1 = $fn."=".$this->nv($fv);
						$i++;
						if ($i==1) 
						  $s .= "set ".$s1; 
						else 
						  $s .= ",".$s1;  
					}
					if($i>0)
					  $s .= " where $pkf=$pkv";
					else
					  $s = '';
					$rw['sts'] = 'upd';
				break;

				case 'del':
				$s = '';
				if($pkv>0)
				  $s = 'delete from '.$tn.' where '.$pkf.'='.$pkv;
				break;
			}
			return $s;
		}
		
		function dcv($trv, $pkv) {
			$id = $this->ivSCF('sds');
			$qs = [];
			$q = "insert into sds (id, trv, pkv) values ($id, $trv, $pkv)";
			$qs[] = $q;
			//$this->logs($q);
			$this->qscSCF($qs);
			/*
			id int not null primary key auto_increment comment 'Кілт'
					, trv int comment 'Кесте'
					, pkv int
					, fm int comment 'Кесте қалпы'
					, dt timestamp default current_timestamp comment 'Ендірілген уақыты'
					, uid int
					, gid int*/
			return $id;
		}
			
		function td($fs) {
			$r = [];
			foreach($fs as $fn=>$f) {
				$r[$fn] = '';
			}
			return $r;
		}

		
		function setRW(&$rw, $trw1) {
			
			$rw['tn'] = strtoupper($trw1['NM']);
			$rw['trv'] = strtoupper($trw1['ID']);
			if(!isset($trw1['PTP']))
				$trw1['PTP'] = '';
			$rw['tp'] = strtoupper($trw1['PTP']);
			//if($trw1['S']==1)
				//$rw['tp'] = $this->ja['db']['dbtp'];
			$rw['pkf'] = strtoupper($trw1['PKF']);
			if(!isset($trw1['PDF']))
				$trw1['PDF'] = '';
			$rw['pdf'] = strtoupper($trw1['PDF']);
			if(!isset($trw1['DCF']))
				$trw1['DCF'] = '';
			$rw['dcf'] = strtoupper($trw1['DCF']);
			if(!isset($trw1['UDF']))
				$trw1['UDF'] = '';
			$rw['udf'] = strtoupper($trw1['UDF']);
			if(!isset($trw1['FMF']))
				$trw1['FMF'] = '';
			$rw['fmf'] = strtoupper($trw1['FMF']);
			if(!isset($trw1['CTF']))
				$trw1['CTF'] = '';
			$rw['ctf'] = strtoupper($trw1['CTF']);
			
			$this->setRWSGN($rw);
		}

		function getRW($trv, $tn, $pkf, $pkv, $fs) {
			$rw = [];
			$trw1 = $this->getRWS($trv
			   , 'id, nm, uf, s, tp, pkf, pdf, ttf, dcf, udf, fmf, ctf'
				 .', (select tp from scf s1 where s1.id=scf.tp) ptp'
			);
			$rs1 = $this->s("select * from $tn where $pkf=$pkv");
			if(count($rs1)==1) {
				$rw = $this->trw($rs1[1]);
				$this->setM($fs, $rw);
				$this->setRW($rw, $trw1);
			}
			return $rw;
		}
		
		function sp($q, $ll, $cp, &$rc, &$pc) {
			/*
			ll select rows count
			cp current page
			rc rows count
			pc pages count
			*/
			$r = '';

			$qq = 'select count(*) cnt from ('.$q.') t'; 
			
			$rs = $this->s($qq);
			//$this->logs($qq.print_r($rs, true));
			$rc = $rs[1]['CNT'];
			
			if ($rc>0 and $ll>0)
				$pc = ceil($rc/$ll);
			else
				$pc = 1;

			$ls = $ll * $cp - $ll;
			$qq = $q;
			if($ll>0) {
				switch($this->dbtp) {
					case $this->ja['sys']['md']://MariaDB
						$qq = $qq.' limit '.$ls.', '.$ll;  //echo $qq."<br/>";
					break;
					case $this->ja['sys']['pg']://PostgreSQL
						$qq = $qq.' limit '.$ll.' offset '.$ls;  //echo $qq."<br/>";
					break;
				}
			}
			$r = $qq;
			return $r;
		}
		
		
		function setM($fs, &$rw) {
			///$this->logs(__FILE__.__LINE__.print_r($fs, true));
			foreach($fs as $fn=>$f) {
				if(isset($f['H'])) {
					$ar = explode('.', $f['H']);
					$cnt = count($ar);
					$fn = $ar[0];//fn
					$tp = $ar[1];
					if(isset($ar[2])) {						
						$foc = $ar[2];						
					} 
					$v = 0;
					if(isset($rw['d'][$fn]))
						$v = $rw['d'][$fn];
					$ar = explode(':', $tp);
					$tp1 = $ar[0];
					//
					
					if($cnt>1 && is_numeric($v) && $v>0 && in_array($tp1, ['ac', 'ls'])) {
						
						
						if(!is_numeric($ar[1])) {
							
							$ftrv = $this->getTRV($ar[1]);
							$m = $this->getM($ftrv, $v, $tp1);
							$rw['m'][$fn] = $m;
							
						}
					}
				}
			}
		}
		
		function getRS($trv, $tn, &$fs, $w, $ll, $cp, &$rc, &$pc) {
			$rs = [];
			$trw1 = $this->getRWS($trv
			   , 'id, nm, uf, s, tp, pkf, pdf, ctf, ttf, dcf, udf, fmf'
				 .', (select tp from scf s1 where s1.id=scf.tp) ptp'
			);
			$ob = $this->getOB($fs);
			if($w!='')
				$w = ' where '.$w;
			
			if($ob!='')
				$ob = 'order by '.$ob;
			
			$q = "select * from $tn t ".$w.' '.$ob;
			$this->q = $q;
			$q = $this->sp($q, $ll, $cp, $rc, $pc);
			$rs1 = $this->s($q);
			foreach($rs1 as $rk=>$d) {
				$rw = $this->trw($d);				
				$this->setM($fs, $rw);
				$this->setRW($rw, $trw1);
				$rs[$rk] = $rw;
			}
			return $rs;
		}

		function uf($v) {
			$r = [];
			if(isset($v)) {
				$r['ufp'] = $v;
				$r['ufu'] = $r['ufp'].'uf/';
				$ar = explode('/', $r['ufp']);
				$c = count($ar);
				$r['fn'] = $ar[($c-2)].'.html';
				$r['fnp'] = $r['ufp'].$r['fn'];
			} else {
				echo __FILE__.__LINE__.'UF=null';
				$r['ufp'] = '';
				$r['ufu'] = '';
				$r['fn'] = '';
				$r['fnp'] = '';
			}
			return $r;
		}

		function getM($trv, $pkv, $tp) {
			$r = '';
			
			$trw1 = $this->getRWS($trv
				, 'id, nm, s, pkf, ttf, dcf, acq'
			);			
			//echo __FILE__.__LINE__.'tp='.$tp;
			//$this->logs(__FILE__.__LINE__.' '.$trv.' '.$pkv.' '.$tp);
			//$this->logs(__FILE__.__LINE__.' '.print_r($trw1, true));
			if(isset($trw1['NM'])) {
				
				$tn = $trw1['NM'];
				$s = $trw1['S'];
				$pkf = $trw1['PKF'];
				$ttf = $trw1['TTF'];
				$dcf = $trw1['DCF'];
				
				switch($tp) {
					case 'ls':
					$q = "select $ttf from $tn where $pkf=$pkv";
				//	echo __FILE__.__LINE__.'q='.$q;
					if($s==1){
						$rs1 = $this->sSCF($q); 
					} else {
						$rs1 = $this->selrs($this->dbtp, $this->db, $q);	
					}
					if(count($rs1)==1)
						$r = $rs1[1][$ttf];
					break;
					
					case 'ac':
					case 'acd':
					
					if($trw1['ACQ']!='') {
						$acq = base64_decode($trw1['ACQ']);
						//echo __FILE__.__LINE__.'acq='.$acq;
						$kf = 'id';
						if($tp=='acd')
							$kf = $dcf;
						$acq = str_replace('{kf}', $kf, $acq);
						$acq = str_replace('{pkv}', $pkv, $acq);
						$acq = str_replace('{t}', '', $acq);
						if($s==1){
							$rs2 = $this->sSCF($acq); 
						} else {
							
							$rs2 = $this->selrs($this->dbtp, $this->db, $acq);	
						}
						
						//print_r($rs2);
						if(count($rs2)==1) {
							$r = $rs2[1]['TT'];
						}
					}
					break;
				}
			}
			return $r;
		}


		function getTTV($tn) {
			$q = "
			  select id, pkf, pdf, nmf, ttf, ctf, v
					from scf 
			   where pd=".$this->ja['scf']['tables']." 
				and nm=upper('$tn')";
			$rs = $this->sSCF($q);
			return $rs[1];
		}

		function getTRV($tn) {
			$r = 0;
			$q = "select id from scf where pd=".$this->ja['scf']['tables']." and nm=upper('$tn')";
			$rs = $this->sSCF($q);
			if(count($rs)==1)
				$r = $rs[1]['ID'];
			return $r;
		}
		
		function getTN($trv) {
			$q = "select nm from scf where id=$trv";
			$rs = $this->sSCF($q);
			return $rs[1]['NM'];
		}


		function ls($trv, $tp, $pd) {
			$r = [];
			$tn = '';
			$s = '';
			$pkf = '';
			$ttf = '';
			$pdf = '';
			$q = "select nm, s, pkf, ttf, pdf from scf where id=$trv";
			$rs = $this->sSCF($q);
			if(count($rs)==1) {
				$tn = $rs[1]['NM'];
				$s = $rs[1]['S'];
				$pkf = $rs[1]['PKF'];
				$ttf = $rs[1]['TTF'];
				$pdf = $rs[1]['PDF'];
			}
			
			if(isset($pd) && !is_numeric($pd)) {
				$id = $this->getSID($pd);				
				$lsq = "select $pkf id, $ttf tt from $tn where $pdf=$id";
				//$this->logs($pd.$lsq);
							
			} else {
				$lsq = "select $pkf id, $ttf tt from $tn";	
			}
			
			if($s=='1') {
				$r = $this->selrs($this->ja['db']['dbtp'], $this->dbSCF, $lsq);
			} else {
				$r = $this->selrs($this->dbtp, $this->db, $lsq);
			}
				
			return $r;
		}

		function acq($trv) {
			$r = [];
			$acq = '';
			$tn = '';
			$s = '';
			$ll = 0;
			$q = "select nm, acq, s, ll, pkf, ttf, dcf from scf where id=$trv";
			$rs = $this->sSCF($q);
			if(count($rs)==1) {
				$tn = $rs[1]['NM'];
				$s = $rs[1]['S'];
				$ll = $rs[1]['LL'];
				$pkf = $rs[1]['PKF'];
				$ttf = $rs[1]['TTF'];
				$dcf = $rs[1]['DCF'];
				if($rs[1]['ACQ']=='') {
					if(!($pkf=='' && $ttf==''))// && $dcf==''))
						$acq = "select {kf} id, $ttf tt from $tn where ({kf}={pkv} or ({pkv} is null and $ttf like '%{t}%'))";					
				} else {
					$acq = base64_decode($rs[1]['ACQ']);
				}
				
			}
			$r['acq'] = $acq;
			$r['tn'] = $tn;
			$r['s'] = $s;
			$r['ll'] = $ll;
			return $r;
		}


		function ac($trv, $s, $acq, $term) {
			$r = '';
			

			if($acq!='') {
				if($s=='1') {
					$rs = $this->sSCF($acq);
				} else {
					$rs = $this->selrs($this->dbtp, $this->db, $acq);
					
				}
				//print_r($rs);
				$cnt = count($rs);
				$s = "[";
				$s .= "{\"id\": \"0\", \"value\": \"[x]\"}";
				if($cnt==0) {
					$s .= ",{\"id\": \"-1\", \"value\": \"+$term\"}";
				}
				foreach($rs as $rk=>$rw) {
					if($rw['ID']>0) {
						$v = '';
						if($rw['TT']!=''){
							$v = htmlspecialchars_decode($rw['TT'], ENT_QUOTES);
						}
						
						$v = str_replace('"', '', $v);
						$l = "{\"id\": \"".$rw['ID']."\", \"value\": \"".$v."\"}";
						$s .= ",".$l;
					}
				}
				$s .= "]";
				$r = $s;
			}
			return $r;
		}



		function trw($d) {
			$r = [];
			$r['d'] = $d;
			$r['m'] = [];
			$r['tn'] = '';
			$r['tp'] = 0;
			$r['pkf'] = '';
			$r['pdf'] = '';
			$r['fmf'] = '';
			$r['ctf'] = '';
			$r['dcf'] = '';
			$r['trv'] = 0;
			$r['rk'] = 1;
			$r['pk'] = '';
			$r['sts'] = 'sel';
			$r['cnt'] = 0;
			$r['c'] = 0;
			$r['v'] = 1;
			$r['sgn'] = 0;
			$r['sgc'] = 0;
			$r['sgr'] = [];
			return $r;
		}

		function setTVCT() {
			$qs = [];
			$q = '
				update scf 
			     set ct=(select count(*) from scf s where s.pd=scf.id)';
			$qs[] = $q;
			$this->qscSCF($qs);
		}


		function getTVRS($pd, $tp, $db, $q, $pkf) {
      $r = array();
      if($pd>0) {
        $qq = str_replace('{pd}', $pd, $q);
        $rs = $this->selrs($tp, $db, $qq);
        foreach($rs as $rk=>$rw) {
        $r[] = $rw;
				$r = array_merge($r
					, $this->getTVRS($rw[$pkf]
					, $tp
					, $db
					, $q
					, $pkf));
        }
      }
      return $r;
    }

    function setTVRS($pd, $tp, $db, $trv, $pkf, $pdf, $ctf, $fs) {
			$r = '';
			$tn =$this->getTN($trv);
			$q = 'select * from '.$tn.' where '.$pdf.'={pd}';
			$rs = $this->getTVRS($pd, $tp, $db, $q, $pkf);
			$rs1 = [];
      if(is_array($rs) && isset($rs)) {
				foreach($rs as $rk=>$d) {
					$rk1 = $d[$pkf];
					$rw = $this->trw($d);
					$rw['tn'] = $tn;
					$rw['pkf'] = $pkf;
					$rw['pdf'] = $pdf;
					$rw['ctf'] = $ctf;
					$rw['rk'] = $rk1;
					$rw['pk'] = $d[$pdf];
					$rw['cnt'] = $d[$ctf];
					if($rw['cnt']=='')
						$rw['cnt'] = 0;
					$this->setM($fs, $rw);
					$rs1[$rk1] = $rw;
				}
      }
      return $rs1;
    }

		function nv($v) {
			$r = 'null';
			if($v!='')
				$r = "'$v'";
			return $r;
		}



		function iv($tp, $tn, $pkf) {
			$r = '';
			switch($tp) {
				case $this->ja['sys']['md']:                                             
						$q = "
							select auto_increment
							from information_schema.tables
							 where table_schema = '".$this->ja['db']['dbnm']."'
							 and upper(table_name) = upper('$tn')
							 limit 1
						"; //echo $q;
						$q = "SHOW TABLE STATUS LIKE '$tn'";
						$rs = $this->s($q);
						$r = $rs[1]['AUTO_INCREMENT'];
						if($r=='')
							$r = 0;
						$q = 'alter table '.$tn.' auto_increment = '.($r+1);
						$this->qdb($tp, $this->db, $q);
					break;
				case $this->ja['sys']['pg']:      
					$q = "select nextval('".$tn.'_'.$pkf."_seq') v";
					$rs = $this->s($q);
					//print_r($rs);
					//echo 'dbtp'.$this->dbtp.' tp'.$tp;
					//$rs = $this->selrs($tp, $this->db, $q);
					$r = $rs[1]['V'];
				break;
        case $this->ja['sys']['md']:
        break;

      }
			
			return $r;
		}
		
		
		function ivSCF($tn) {
			$r = 0;
			$q = "
			  select auto_increment
				from information_schema.tables
			   where table_schema = '".$this->ja['db']['dbnm']."'
				 and upper(table_name) = upper('$tn')
			   limit 1
			"; //echo $q;
			$q = "SHOW TABLE STATUS LIKE '$tn'";
			$rs = $this->sSCF($q);
			$r = $rs[1]['AUTO_INCREMENT'];
			if($r=='')
			  $r = 0;
			$this->qSCF('alter table '.$tn.' auto_increment = '.($r+1));
			return $r;
		}
		
		function s($q) {
			switch($this->s) {
			case 1://Sys
				$rs = $this->selrs($this->ja['db']['dbtp'], $this->dbSCF, $q);
				break;
			default:
				$rs = $this->selrs($this->dbtp, $this->db, $q);
			}
				
			
			return $rs;
		}

		function sSCF($q) {
			$rs = $this->selrs($this->ja['db']['dbtp'], $this->dbSCF, $q);
			return $rs;
		}

		
		function getRWS($sid, $scffs) {
			$r = [];
			if($sid>0) {

				$q = "
					select ".$scffs."
						from scf
					 where id=$sid
				";
				$rs = $this->sSCF($q);
				if(count($rs)==1)
					$r = $rs[1];
			}
			return $r;
		}

		function getSRW($ps, $scffs) {
			$r = 0;
			$sid = $this->getSID($ps);
			$r = $this->getRWS($sid, $scffs);
			return $r;
		}

		function getSRV($ps) {
			$r = 0;
			$sid = $this->getSID($ps);
			if($sid>0) {

				$q = "
					select id
						from srs
				   
					 where trv=$trv 
				     and pkv=$sid
				";
				$rs = $this->s($q);
				if(count($rs)==1)
					$r = $rs[1]['RS'];
			}
			return $r;
		}
		
		function getSID($ps) {
			$r = 0;
			$ar = explode('/', $ps);
			foreach($ar as $k=>$v) {
				$r = $this->getPV($k, $r, $v);
			}			
			return $r;
		}
		
		function getPV($k, $p, $nm) {
			$r = 0;
			if($k==0)
				$pd = 1;
			else
				$pd = $p;
			$q = "
				select id
				  from scf
				 where pd=$pd
				   and nm='$nm'
			";
			$rs = $this->sSCF($q);
			if(count($rs)==1)
				$r = $rs[1]['ID'];
			return $r;
		}
			
		function qscSCF($qs) {
			foreach($qs as $sql) {
				$this->qSCF($sql);
			}
			$this->qSCF('commit');		
		}
		
		function qsc($qs) {
			foreach($qs as $sql) {
				$this->qdb($this->dbtp, $this->db, $sql);
			}
			$this->qdb($this->dbtp, $this->db, 'commit');
		}	

		function qSCF($q) {
			$this->qdb($this->ja['db']['dbtp']
				, $this->dbSCF
				, $q
			);
		}
		
		function dropdbSCF() {
			$this->conSCF();
			$this->qdb($this->ja['db']['dbtp']
				, $this->dbSCF
				, "drop database ".$this->ja['db']['dbnm']
			);
		}


		function chdbSCF() {
			$finded = false;

			switch($this->ja['db']['dbtp']) {
				case $this->ja['sys']['md']:
					$this->conSCF();
					$rs = $this->sSCF("show databases");
					//print_r($rs);
					foreach($rs as $rk=>$rw) {
						if($rw['DATABASE']==$this->ja['db']['dbnm']) {
							$finded = true;
							break;
						}
					}
				break;
			}


			return $finded;
		}

		function conSCF() {
			switch($this->ja['db']['dbtp']) {
				case $this->ja['sys']['md']:
					$this->dbSCF = new \mysqli(
						$this->ja['db']['host']
						, $this->ja['db']['un']
						, $this->ja['db']['pw']
					);
					if ($this->dbSCF->connect_errno) {
						printf("Не удалось подключиться: %s\n", $this->db->connect_error);
						exit();
					}

					$this->dbSCF->query("SET AUTOCOMMIT=0");
					$this->dbSCF->query("set names utf8");
					$this->dbSCF->query("set character_set_client='utf8'");
					$this->dbSCF->query("set character_set_results='utf8'");
					$this->dbSCF->query("set collation_connection='utf8_general_ci'");
				break;
			}
		}

		
		function connSCF() {
			switch($this->ja['db']['dbtp']) {
				case $this->ja['sys']['md']:                                             
					$this->dbSCF = $this->connMDB(                       
						$this->ja['db']['host']
						, $this->ja['db']['port']
						, $this->ja['db']['un']
            , $this->ja['db']['pw']
            , $this->ja['db']['dbnm']
					);
					break;
				case $this->ja['sys']['pg']:      
					$this->dbSCF = $this->connPG(                       
						$this->ja['db']['host']
						, $this->ja['db']['port']
						, $this->ja['db']['un']
            , $this->ja['db']['pw']
            , $this->ja['db']['dbnm']
          );
				break;
        case $this->ja['sys']['md']:
        break;

      }
		}

		function conn($tp, $host, $port, $un, $pw, $dbnm) {
			$this->dbtp = $tp;
			switch($tp) {
				case $this->ja['sys']['md']:
					$this->db = $this->connMDB(                       
						$host
						, $port
						, $un
            , $pw
            , $dbnm
					);
					break;
				case $this->ja['sys']['pg']:      
					$this->db = $this->connPG(                       
						$host
						, $port
						, $un
            , $pw
            , $dbnm
          );
				break;
        case $this->ja['sys']['sl']:
        break;

      }
		}
		
		function connMDB($host, $port, $un, $pw, $dbnm) {
			//date_default_timezone_set("Asia/Qyzylorda");
			$ports = '';
			if($port>0) {
				$ports = ':'.$port;
			}
			$db = new \mysqli($host.$ports, $un, $pw, $dbnm);
			if ($db->connect_errno) {
				printf("Не удалось подключиться: %s\n", $this->db->connect_error);
				exit();
			}

			$db->query("SET AUTOCOMMIT=0");
			$db->query("set names utf8");
			$db->query("set character_set_client='utf8'");
			$db->query("set character_set_results='utf8'");
			$db->query("set collation_connection='utf8_general_ci'");

			return $db;
		}


		function connPG($host, $port, $un, $pw, $dbnm) {//5432
			$db = \pg_connect("host=$host port=$port dbname=$dbnm user=$un password=$pw");
				if (!$db) {
						echo "An error occurred.\n";
						exit;
					}
					
			return $db;
		}

		
		function connS3($fn) {
			$db = new SQLite3($fn);

		}
		
		function qdb($tp, $db, $q) {
			switch($tp) {
				case $this->ja['sys']['md']://MariaDB
					if($q!='') {
						//print_r($q);
						$result = $db->query($q);
					}
					break;
				case $this->ja['sys']['pg']://PostgreSQL
					$result = pg_query($db, $q);

				break;
				case $this->ja['sys']['sl']:
					$result = $db->query($q);
				break;
			}
		}
		
		function selrs($tp, $db, $q) {
			$rs = array();

			switch($tp) {
				case $this->ja['sys']['md']://MariaDB
					try {
						$result = $db->query($q);
						if($result){
							
							//$finfo = mysqli_fetch_fields($result);
							$finfo = $result->fetch_fields();
							$this->selfs = [];
							$this->seltp = [];
							$i = 0;
							foreach ($finfo as $val) {
								$i++;
								$fn = strtoupper($val->name);
								$this->selfs[$i] = strtoupper($fn);
								$ln = '';
								if($val->length>0)
									$ln = '('.$val->length.')';
								$this->seltp[$i] = $val->type;//.$ln;
							}
							$j = 0;
							while ($row = $result->fetch_array(MYSQLI_ASSOC)){
								$j++;
								foreach ($finfo as $val) {
									$rs[$j][strtoupper($val->name)] = $row[$val->name];
								}
																																																									}
							$result->close();
						} else {
							echo $q;
							echo($this->db->error);
							throw new Exception($q);
						}
					} catch (Exception $ex) {
							echo $q;
							echo $ex->getMessage();
							throw new Exception($q);
					}

					
					break;

				case $this->ja['sys']['pg']://PostgreSQL
					$result = \pg_query($db, $q);
					if (!$result) {
						echo "An error occurred.\n";
						exit;
					}

					$cns =[];
					$this->selfs = [];
					$this->seltp = [];
					$ncols = \pg_num_fields($result);
					for ($j = 0; $j < $ncols; ++$j) {						
						$fn = \pg_field_name($result, $j);
						$tp = \pg_field_type($result, $j);
						$pln = \pg_field_prtlen($result, $fn);
						$cns[$j] = $fn;
						$this->selfs[$j] = strtoupper($fn);
						$ln = '';
						if($pln>0)
							$ln = '('.$pln.')';
						$this->seltp[$j] = $tp;//.$ln;
					}
					$j = 0;
					while($row = \pg_fetch_array($result, NULL, PGSQL_ASSOC)) {
						$j++;
						foreach ($cns as $k=>$fn) {
							$rs[$j][strtoupper($fn)] = $row[$fn];
						}
					}
				break;
				
				case $this->ja['sys']['sl']://SQLite3
					$result = $db->query($q);
					$cc = $result->numColumns();
					while($row = $result->fetchArray(SQLITE3_ASSOC)){
						$j++;
						for( $i=0; $i<$cc; $i++) {
							$fn = $result->columnName($i);
							$rs[$j][strtoupper($fn)] = $row[$fn];
						}

					}
				break;
			}

			return $rs;
		}

		function getSD() {
			if(isset($_SESSION[$this->dn])) {
				$this->sd = &$_SESSION[$this->dn];
			}
		}
		
		function setSD() {
			if(isset($_SESSION)) {
				$_SESSION[$this->dn] = $this->sd;
			}
		}
		
		function getFSD($vw) {//$this->vw
			$r = array();
			if(isset($this->sd[$vw])) {
				$r = &$this->sd[$vw];
			}
			return $r;
		}
		
		function setFSD($vw, $sd) {
			if(isset($_SESSION)) {
				$this->sd[$vw] = $sd;
			}
		}
	}
?>
