<?php
	/*
	* Copyright (C) 2025 Жанибек Какимбаев
	*
	* Бұл бағдарлама GNU General Public License v3.0 (GPLv3) лицензиясы бойынша таратылады.
	* Лицензия мәтіні: https://www.gnu.org/licenses/gpl-3.0.html
	*/
	namespace mvc\cs;
	class cCreateSCF extends jC {

		function init() {
			$r = '';
			//$this->jf = $this->ip['jf'];
			return $r;
		}

		function rq() {
			if(isset($_POST['admun'])) {
				if($_POST['admpw1'] == $_POST['admpw2']) {
					echo $this->installSCF();
				} else {
					echo 'құпия сөз бірдей емес';
				}
			}
			
		}


		function initSCF() {
			$this->oM->ja['db']['dbtp'] = $_POST['dbtp'];
			$this->oM->ja['db']['host'] = $_POST['host'];
			$this->oM->ja['db']['port'] = $_POST['port'];
			$this->oM->ja['db']['un'] = $_POST['un'];
			$this->oM->ja['db']['pw'] = $_POST['pw'];
			$this->oM->ja['db']['dbnm'] = $_POST['dbnm'];
			$this->oM->ja['db']['drop'] = $_POST['drop'];
			$this->oM->ja['db']['admun'] = $_POST['admun'];
			$this->oM->ja['db']['admpw1'] = $_POST['admpw2'];
			$this->oM->ja['db']['admpw2'] = $_POST['admpw2'];
		}
		
		function installSCF() {
			$r = '';
			$this->initSCF();
			$id = 1; $root = $id;
			$qs2 = [];
			$qs2 = array_merge($qs2, $this->createSDS());
			$qs2 = array_merge($qs2, $this->createSCF($id, $root));			
			$qs2 = array_merge($qs2, $this->qsTYPES($id, $root));			
			switch($this->oM->ja['db']['dbtp']) {
				case 1:
					$this->oM->ja['db']['dbtp'] = $this->oM->ja['sys']['md'];
				break;
				case 2:
					$this->oM->ja['db']['dbtp'] = $this->oM->ja['sys']['pg'];
				break;
				case 3:
					$this->oM->ja['db']['dbtp'] = $this->oM->ja['sys']['sl'];
				break;
			}
			
			if($this->oM->ja['db']['drop']==1) {
				if($this->oM->chdbSCF()) {
					$r .= $this->oM->ja['db']['dbnm'];
					$r .= ' қоймасы бар.';
					$r .= ' Басқа атауын ендіріңіз.';
				} else {
					$this->oM->qSCF("create database "
						.$this->oM->ja['db']['dbnm']
						." default character set utf8 collate utf8_general_ci;");
					$r .= $this->oM->ja['db']['dbnm'];
					$r .= ' қоймасы жасалды.';
					$this->oM->connSCF();
					$qs = $this->createSCF();
				}
			} elseif($this->oM->ja['db']['drop']==2) {
				if($this->oM->chdbSCF()) {
					$this->oM->dropdbSCF();
				}
				$this->oM->qSCF("create database "
					.$this->oM->ja['db']['dbnm']
					." default character set utf8 collate utf8_general_ci;");
				
				$this->oM->connSCF();
				$this->oM->qscSCF($qs2);
				
				$qs1 = [];
				$qs1 = array_merge($qs1, $this->createACS());
				$qs1 = array_merge($qs1, $this->createACE());
				$qs1 = array_merge($qs1, $this->createACB());
				$qs1 = array_merge($qs1, $this->createSUF());
				$qs1 = array_merge($qs1, $this->createSGN());
				$qs1 = array_merge($qs1, $this->createSSS());
				$qs1 = array_merge($qs1, $this->createCHS());
				$qs1 = array_merge($qs1, $this->createCUS());
				$qs1 = array_merge($qs1, $this->createMSG());
				$qs1 = array_merge($qs1, $this->createCVS());
				$qs1 = array_merge($qs1, $this->createMVC());
				$qs1 = array_merge($qs1, $this->createASS());
				$qs1 = array_merge($qs1, $this->createITS());
				$qs1 = array_merge($qs1, $this->createEMS());
				$this->oM->qscSCF($qs1);
				
				$qs = [];
				$qs = array_merge($qs, $this->qsGVS($id, $root));
				$qs = array_merge($qs, $this->qsDBS($id, $root));
				$qs = array_merge($qs, $this->qsTABLES($id, $root));
				$qs = array_merge($qs, $this->qsPGS($id, $root));
				$qs = array_merge($qs, $this->qsRS($id, $root));
				$qs = array_merge($qs, $this->qsUS($id, $root));
				$qs = array_merge($qs, $this->qsGS($id, $root));
				$qs = array_merge($qs, $this->qsFMS($id, $root));
				
				
				
				$qs = array_merge($qs, $this->addFMS());
				$qs = array_merge($qs, $this->setACQ());
				$qs = array_merge($qs, $this->addACE());
				
				//echo $this->oM->qsh($qs);
				$this->oM->qscSCF($qs);
				$this->oM->setTVCT();
				$qs = [];
				$qs = array_merge($qs, $this->addACS());
				$this->oM->qscSCF($qs);
				
				unset($_SESSION);
				session_destroy();
				$this->oM->sd['uid'] = 0;
				
				
				$this->createCA();//Корневой сертификат и приватный ключ ca.p12
				//Құрылымды json сақтау
				file_put_contents(
					$this->oM->jf
					, json_encode($this->oM->ja, JSON_UNESCAPED_UNICODE));
					
				header('location: ?pg=main');
			}
			return $r;
		}
		
		function createCA() {
			// Директория для хранения сертификатов
			/*$certDir = __DIR__ . '/';
			if (!is_dir($certDir)) {
				mkdir($certDir, 0777, true);
			}*/

			// Генерация пары RSA ключей
			$privateKeyResource = openssl_pkey_new([
				"private_key_bits" => 2048,  // Размер ключа в битах
				"private_key_type" => OPENSSL_KEYTYPE_RSA,  // Тип ключа
			]);
			
			// Создание самоподписанного сертификата
			$dn = [
				"countryName" => "KZ",
				"stateOrProvinceName" => "QyzylOrda",
				"localityName" => "QyzylOrda",
				"organizationName" => "TIRLIK",
				"organizationalUnitName" => "TIRLIK",
				"commonName" => "TIRLIK",
				"emailAddress" => "zhtirlik82@gmail.com"
			];

			// Генерация самоподписанного сертификата
			$certificate = openssl_csr_new($dn, $privateKeyResource);
			$certificate = openssl_csr_sign($certificate, null, $privateKeyResource, 3650, ["digest_alg" => "sha256"]);
			
			// Создание файла P12 (PKCS#12) для сертификата и ключа
			$p12File = KYS . 'ca.p12';
			$p12Password = "Pw20241211"; // Установите пароль для P12
			
			$this->oM->ja['scf']['capw'] = $p12Password;
			
			// Экспорт в формат P12
			openssl_pkcs12_export_to_file($certificate, $p12File, $privateKeyResource, $p12Password);	
			//echo "Корневой сертификат и приватный ключ успешно созданы и сохранены в формате P12.\n";
			//echo "P12 файл: {$p12File}\n";

		}
		
		function addFMS() {
			$qs = [];
			$fms = $this->oM->ja['fms']['fmTPG1'];
			$qs[] = "update scf set fms='$fms' where id=".$this->oM->ja['tables']['tpg'];
			$fms = $this->oM->ja['fms']['fmACS'];
			$qs[] = "update scf set fms='$fms' where id=".$this->oM->ja['tables']['acs'];
			return $qs;
		}
		
		function setACQ() {
			$qs = [];
			$qs[] = "update scf set acq='".base64_encode($this->acqscf())."' where id=".$this->oM->ja['tables']['scf'];
			$acq = "select {kf} id, nm tt from t where ({kf}={pkv} or ({pkv} is null and nm like '%{t}%'))";
			$qs[] = "update scf set acq='".base64_encode($acq)."' where id=".$this->oM->ja['tables']['t'];
			$acq = "select {kf} id, concat(ifnull(cd,''), ' ', ifnull(nm,'')) tt from acs where ({kf}={pkv} or ({pkv} is null and concat(ifnull(cd,''), ' ', ifnull(nm,'')) like '%{t}%'))";
			$qs[] = "update scf set acq='".base64_encode($acq)."' where id=".$this->oM->ja['tables']['acs'];
			return $qs;
		}
		
		function addACE() {
			$qs = [];
			$q = "insert into ace (dc) values (null)";
			$qs[] = $q;
			return $qs;
		}
		
		function addACS() {
			$qs = [];
			$ar = ExcelA('acs.xlsx', 0, 3, 0, 3); //ExcelA($fn, $si, $bl, $bc, $ec)
			//$this->oM->logs(print_r($ar, true));
			ob_clean();
			$i = 0;
			$pd = 0;
			$pd1 = 0;
			$fm = $this->oM->ja['fms']['fmACS'];
			foreach($ar as $k=>$a) {
				if($a[2]=='') {
					$i++;
					$q = "insert into acs (id, nm, fm) values ($i, '".$a[0]."', $fm)";
					$qs[] = $q;
					$pd = $i;					
				} elseif($a[1]=='') {
					if($pd>0) {
						$i++;
						$cd = $a[0];
						$nm = $a[2];
						$q = "insert into acs (id, pd, cd, nm, fm) values ($i, $pd, '$cd', '$nm', $fm)";
						$qs[] = $q;
						$pd1 = $i;
					}
				} else {	
					if($pd1>0) {
						$i++;
						$cd = $a[1];
						$nm = $a[2];
						
						$s1 = 'null';
						$s2 = 'null';
						$s3 = 'null';						
						if($cd=='1041') {
							$s1 = $this->oM->ja['tables']['as1'];
							$s2 = $this->oM->ja['tables']['as2'];
							$s3 = $this->oM->ja['tables']['as3'];
							
							$pkv = $this->oM->ivSCF('as1');
							$dc = $this->oM->dcv($s1, $pkv);
							$q = "insert into as1 (id, dc, nm) values ($pkv, $dc, 'as1.nm.$pkv.$dc')";
							$qs[] = $q;
							
							$pkv = $this->oM->ivSCF('as2');
							$dc = $this->oM->dcv($s2, $pkv);
							$q = "insert into as2 (id, dc, nm) values ($pkv, $dc, 'as2.nm.$pkv.$dc')";
							$qs[] = $q;
							
							$pkv = $this->oM->ivSCF('as3');
							$dc = $this->oM->dcv($s3, $pkv);
							$q = "insert into as3 (id, dc, nm) values ($pkv, $dc, 'as3.nm.$pkv.$dc')";
							$qs[] = $q;
						}
						
						if($cd=='1042') {
							$s1 = $this->oM->ja['tables']['as3'];
							$s2 = $this->oM->ja['tables']['as2'];
							$s3 = $this->oM->ja['tables']['as1'];
						}
						$q = "insert into acs (id, pd, cd, nm, fm, s1, s2, s3) values ($i, $pd1, '$cd', '$nm', $fm, $s1, $s2, $s3)";
						$qs[] = $q;
					}
				}
			}
			return $qs;
		}
		
		function createEMS() {
			$qs = [];
			$q = "
				create table ems (
					id int not null primary key auto_increment comment 'Кілт'
					, uid int comment 'imap uid'
					, sndr varchar(192) comment 'Жіберуші'
					, rcpt varchar(192) comment 'Қабылдаушы'
					, sbj varchar(192) comment 'Тақырып'
					, mid varchar(192) comment 'Message-ID'
					, dt varchar(50) comment 'Уақыты'
				)
			";
			$qs[] = $q;
			return $qs;
		}
		
		function createITS() {
			$qs = [];
			$q = "
				create table itm (
					id int not null primary key auto_increment comment 'Кілт'
					, nm varchar(192) comment 'Атауы'					
				)
			";
			$qs[] = $q;
			
			$q = "
				create table it1 (
					id int not null primary key auto_increment comment 'Кілт'
					, m int
					, nm varchar(192) comment 'Атауы'
					, index (m), foreign key (m) references itm(id)
				)
			";
			$qs[] = $q;
			
			$q = "
				create table it2 (
					id int not null primary key auto_increment comment 'Кілт'
					, m int
					, nm varchar(192) comment 'Атауы'
					, index (m), foreign key (m) references itm(id)
				)
			";
			$qs[] = $q;
			
			$q = "
				create table it3 (
					id int not null primary key auto_increment comment 'Кілт'
					, m int
					, nm varchar(192) comment 'Атауы'
					, index (m), foreign key (m) references itm(id)
				)
			";
			$qs[] = $q;
			
			$q = "
				create table it4 (
					id int not null primary key auto_increment comment 'Кілт'
					, m int
					, nm varchar(192) comment 'Атауы'
					, index (m), foreign key (m) references it3(id)
				)
			";
			$qs[] = $q;
			return $qs;
		}
		
		function createASS() {
			$qs = [];
			$q = "
				create table as1 (
					id int not null primary key auto_increment comment 'Кілт'
					, dc int
					, nm varchar(192) comment 'Атауы'					
				)
			";
			$qs[] = $q;
			
			$q = "
				create table as2 (
					id int not null primary key auto_increment comment 'Кілт'
					, dc int
					, nm varchar(192) comment 'Атауы'
				)
			";
			$qs[] = $q;
			
			$q = "
				create table as3 (
					id int not null primary key auto_increment comment 'Кілт'
					, dc int
					, nm varchar(192) comment 'Атауы'
				)
			";
			$qs[] = $q;
			
			
			return $qs;
		}
		
		function createSDS() {
			$qs = [];
			$q = "
				create table sds (
					id int not null primary key auto_increment comment 'Кілт'
					, trv int comment 'Кесте'
					, pkv int
					, fm int comment 'Кесте қалпы'
					, dt timestamp default current_timestamp comment 'Ендірілген уақыты'
					, d text comment 'Мәтін'
					, uid int
					, gid int
					-- , index (trv), foreign key (trv) references scf(id)
			) comment 'Құжаттар';
			";
			$qs[] = $q;
			return $qs;
		}
		
		function createSCF(&$id, $root) {
			$qs = [];	
					
			$q = "
				create table scf (
					id int not null primary key auto_increment comment 'Кілт'
					, dc int comment 'Құжат'
					, pd int comment 'Аталық'
					, ct int comment 'Мирас жолдар'

					, tp int comment 'Тұрпаты SCF'
					, fms varchar(192) comment 'Keste Qaliptary'

					, dbhost varchar(192) comment 'dbhost'
					, dbport varchar(192) comment 'dbport'
					, dbnm varchar(192) comment 'dbnm'
					, dbun varchar(192) comment 'dbun'
					, dbpw varchar(192) comment 'dbpw'
					, un varchar(50) comment 'UN'
					, pw varchar(192) comment 'PW'
					, a tinyint(1) comment 'Қолданушы белсенділігі'

					, nm varchar(192) comment 'Атауы'
					, tt varchar(192) comment 'Тұспалы|acn'
					, acq text comment 'acsql'
					-- , n int comment 'Реті'
					-- , ic int comment 'Жасалған'
					, nn varchar(192) comment 'Өзге атауы'
					, ats varchar(192) comment 'attrbs'

					, ll int default 10 comment 'prc'
					, pkf varchar(30) comment 'pkf'
					, nmf varchar(30) comment 'nmf'
					, ttf varchar(30) comment 'ttf'
					, pdf varchar(30) comment 'pdf'
					, ctf varchar(30) comment 'ctf'
				  
					, dcf varchar(30) comment 'dcf'
					, fmf varchar(30) comment 'fmf'
					, udf varchar(30) comment 'udf'
					, pns varchar(192) comment 'phones'
					, ems varchar(192) comment 'emails'
					, v varchar(192) comment 'мәні'
					, s bool comment 'systable'
					, uf varchar(192) comment 'UF'
					, its varchar(192) comment 'Ішкі кестелер[TN1.FKFN,TN2.FKFN,TN3.FKFN]'

					, UNIQUE (un)
					, index (dc), foreign key (dc) references sds(id)
					, index (tp), foreign key (tp) references scf(id)
				) comment 'Құрылым';";
			$qs[] = $q;
			$q = "insert into scf (id, nm) values ($root, 'ROOT')";
			$qs[] = $q;
			$id++; $q = "insert into scf (id, pd, nm, tt) values (
				$id, $root, 'TABLES', 'TABLES'
			)";$qs[] = $q; $this->oM->ja['scf']['tables'] = $id;
				$pd = $id;
				$id++; $q = "insert into scf (id, pd, nm, tt, s, pkf, nmf, ttf, pdf, ctf, uf, dcf) values (
					$id, $pd, 'SCF', 'SCF', 1, 'ID', 'NM', 'TT', 'PD', 'CT', 'tbs/scf/', 'DC'
							)"; $qs[] = $q; $this->oM->ja['tables']['scf'] = $id;

				$id++; $q = "insert into scf (id, pd, nm, tt, s) values (
					$id, $pd, 'SDS', 'SDS', 1
				  )"; $qs[] = $q; $this->oM->ja['tables']['sds'] = $id;
				  
			$q = "alter table sds add index(trv)";
			$qs[] = $q;
			$q = "alter table sds add foreign key (trv) references scf(id)";
			$qs[] = $q;
			$q = "alter table sds add index(uid)";
			$qs[] = $q;
			$q = "alter table sds add foreign key (uid) references scf(id)";
			$qs[] = $q;  
			$q = "alter table sds add index(gid)";
			$qs[] = $q;
			$q = "alter table sds add foreign key (gid) references scf(id)";
			$qs[] = $q;
			
			$q = "				
			  CREATE OR REPLACE TRIGGER insSCF
			  BEFORE INSERT
				 ON scf FOR EACH ROW

			  BEGIN
				DECLARE sdsID INT;
				set sdsID = (SELECT AUTO_INCREMENT
				 FROM information_schema.TABLES
				 WHERE TABLE_SCHEMA = '".$this->oM->ja['db']['dbnm']."'
				AND TABLE_NAME = 'sds');
				
				insert into sds (id, trv) values (sdsID, ".$this->oM->ja['tables']['scf'].");
				SET NEW.DC = sdsID;
			  END;
			";
			$qs[] = $q;
			return $qs;	
		}
		
		
		
		function createACS() {
			$qs = [];
			$q = "
				create table acs (
				id int not null primary key auto_increment comment 'Кілт'
				, pd int
				, fm int
				, cd varchar(192) comment 'Код|ttf'
				, nm varchar(255) comment 'Атауы'
				, ba bool comment 'БөлекБаланстан'
				, c bool comment 'Санды'
				, a int comment 'Белсенді|А|П|АП'
				, cr bool comment 'АқшаБірлігіАБ'
				, s1 int comment 'Сб1'
				, s2 int comment 'Сб2'
				, s3 int comment 'Сб3'
				-- , index (a), foreign key (a) references sds(id)
				-- , index (s1), foreign key (s1) references sds(id)
				-- , index (s2), foreign key (s2) references sds(id)
				-- , index (s3), foreign key (s3) references sds(id)
			) comment 'Есеп жоспары';
			";
			$qs[] = $q;
			return $qs;
		}
		
		function createACE() {
			$qs = [];
			$q = "
				create table ace (
					id int not null primary key auto_increment comment 'Кілт'
					, ud int
					, dc int comment 'Құжат|ttf'
					, dt timestamp default current_timestamp comment 'Уақыты'
					, d int comment 'Дебет'
					, ds1 int comment 'ДтСб1'
					, ds2 int comment 'ДтСб2'
					, ds3 int comment 'ДтСб3'
					, dcr int comment 'ДтАқшаБірлігіАБ'
					, dcnt decimal(11,4) comment 'ДтСаны'				
					, dcrsm decimal(11,4) comment 'ДтАБСомасыҚосынды'
					, c int comment 'Кредит'
					, cs1 int comment 'КтСб1'
					, cs2 int comment 'КтСб2'
					, cs3 int comment 'КтСб3'
					, ccr int comment 'КтАқшаБірлігіАБ'
					, ccnt decimal(11,4) comment 'КтСаны'				
					, ccrsm decimal(11,4) comment 'КтАБСомасыҚосынды'
					, sm decimal(11,4) comment 'СомасыҚосынды'
					, ct varchar(192) comment 'Мазмұны'
					, index (d), foreign key (d) references acs(id)
					, index (c), foreign key (c) references acs(id)
					, index (ud), foreign key (ud) references scf(id)
					, index (dc), foreign key (dc) references sds(id)
					, index (ds1), foreign key (ds1) references sds(id)
					, index (ds2), foreign key (ds2) references sds(id)
					, index (ds3), foreign key (ds3) references sds(id)
					, index (dcr), foreign key (dcr) references sds(id)
					, index (cs1), foreign key (cs1) references sds(id)
					, index (cs2), foreign key (cs2) references sds(id)
					, index (cs3), foreign key (cs3) references sds(id)
					, index (ccr), foreign key (ccr) references sds(id)
				) comment 'Өтімдер';
			";
			$qs[] = $q;
			return $qs;
		}
		
		function createACB() {
			$qs = [];
			$q = "
				create table acb (
				id int not null primary key auto_increment comment 'Кілт'
				, dt date comment 'Уақыты'
				, a int comment 'Есеп коды'
				, cr int comment 'ДтАқшаБірлігіАБ'
				, s1 int comment 'КтСб1'
				, s2 int comment 'КтСб2'
				, s3 int comment 'КтСб3'
				, sm decimal(11,4) comment 'СомасыҚосынды'
				, index (a), foreign key (a) references acs(id)
				, index (cr), foreign key (cr) references sds(id)
				, index (s1), foreign key (s1) references sds(id)
				, index (s2), foreign key (s2) references sds(id)
				, index (s3), foreign key (s3) references sds(id)
			) comment 'Қалдықтар';
			";
			$qs[] = $q;
			return $qs;
		}
		
		function createSUF() {
			$qs = [];
			$q = "
				create table suf (
					id int not null primary key auto_increment comment 'Кілт'
					, rv int comment 'rv'
					, nm varchar(192)
					, ext varchar(5)
					, dt timestamp default current_timestamp comment 'Ендірілген уақыты'
					, uid int
					, index (uid), foreign key (uid) references scf(id)
				) comment 'Жүктелген файлдар';
			";
			$qs[] = $q;
			return $qs;	
		}
		
		function createSGN() {
			$q = "
			  create table sgn (
				id int not null primary key auto_increment comment 'Кілт'
				, dc int comment 'Құжат'
				, ud int
				, txt varchar(192) comment 'Мәтін'
				, dt timestamp default current_timestamp comment 'Ендірілген уақыты'  
				-- , index (rv), foreign key (rv) references srs(id)
				, index (ud), foreign key (ud) references scf(id)
			  ) comment 'Қолдар';
			";
			$qs[] = $q;
			return $qs;
		}
		
		function createSSS() {
			$q = "
			  create table sss (
					id int not null primary key auto_increment comment 'Кілт'
					, uid int
					, nm varchar(192) comment 'Атауы'
					, rip varchar(192) comment 'REMOTE_ADDR'
					, cc varchar(192) comment 'country_code'
					, cn varchar(192) comment 'city_name_ru'
					, rn varchar(192) comment 'region_name_ru'
					, sid varchar(192) comment 'sid'
					, sdt timestamp default current_timestamp comment 'Дата'
					, index (uid), foreign key (uid) references scf(id)
				) comment 'Сессиялар';
			";
			$qs[] = $q;
			return $qs;
		}
		
		//Чаттар
		function createCHS() {
			$q = "
			  create table chs (
					id int not null primary key auto_increment comment 'Кілт'
					, nm varchar(192) comment 'Атауы'
					, tp tinyint default 1 comment 'Саны1two2many'
					, sdt timestamp default current_timestamp comment 'Дата'
				) comment 'Чаттар';
			";
			$qs[] = $q;
			return $qs;
		}
		
		//Чат қолданушылары
		function createCUS() {
			$q = "
			  create table cus (
					id int not null primary key auto_increment comment 'Кілт'
					, cid int comment 'Чат'
					, uid int comment 'Қолданушы'
					, wsd int comment 'Автор'
					, tp tinyint comment 'Бағыт1from2to'
					, sdt timestamp default current_timestamp comment 'Дата'
					, index (cid), foreign key (cid) references chs(id)
					, index (uid), foreign key (uid) references scf(id)
					, index (wsd), foreign key (wsd) references sss(id)
				) comment 'Чат қолданушылары';
			";
			$qs[] = $q;
			return $qs;
		}
		
		//Хабарламалар
		function createMSG() {
			$q = "
			  create table msg (
					id int not null primary key auto_increment comment 'Кілт'
					, rs int comment 'rs'
					, uid int comment 'Автор'
					, wsd int comment 'Автор'
					, cid int comment 'chat'
					, fid int comment 'File'
					, msg text comment 'Хабарлама'
					, sdt timestamp default current_timestamp comment 'Дата'
					, index (uid), foreign key (uid) references scf(id)
					, index (wsd), foreign key (wsd) references sss(id)
					, index (cid), foreign key (cid) references chs(id)
				) comment 'Хабарламалар';
			";
			$qs[] = $q;
			return $qs;
		}
		
		//Чат қолданушылар статусы
		function createCVS() {
			$q = "
			  create table cvs (
					id int not null primary key auto_increment comment 'Кілт'
					, cid int comment 'Чат'
					, uid int comment 'Қолданушы'
					, wsd int comment 'Автор'
					, sdt timestamp default current_timestamp comment 'Дата'
					, index (cid), foreign key (cid) references chs(id)
					, index (uid), foreign key (uid) references scf(id)
					, index (wsd), foreign key (wsd) references sss(id)
				) comment 'Чат қолданушылар статусы';
			";
			$qs[] = $q;
			return $qs;
		}
		
		//Хабарлама қолданушылар статусы
		function createMVC() {
			$q = "
			  create table mvs (
					id int not null primary key auto_increment comment 'Кілт'
					, mid int comment 'Хабарлама'
					, uid int comment 'Қолданушы'
					, wsd int comment 'Автор'
					, sdt timestamp default current_timestamp comment 'Дата'
					, index (mid), foreign key (mid) references msg(id)
					, index (uid), foreign key (uid) references scf(id)
					, index (wsd), foreign key (wsd) references sss(id)
				) comment 'Хабарлама қолданушылар статусы';
			";
			$qs[] = $q;
			return $qs;
		}
		
		function qsGVS(&$id, $root) {
			$qs = [];
			
			$id++; $q = "insert into scf (id, pd, nm, tt) values (
				$id, $root, 'GVS','GVS'
			)";$qs[] = $q; $this->oM->ja['scf']['gvs'] = $id;

				$pd = $id; $id++;$q = "insert into scf (id, pd, nm, v) values (
					$id, $pd, 'TH', 'tht'
				)";$qs[] = $q;
				
				$pd = $id; $id++;$q = "insert into scf (id, pd, nm, v) values (
					$id, $pd, 'DM', 'tirlik.kz'
				)";$qs[] = $q; $this->oM->ja['gvs']['dm'] = $id;
			
			return $qs;
		}
		
		
		function qsTYPES(&$id, $root) {
			$qs = [];

			$id++; $q = "insert into scf (id, pd, nm) values (
				$id, $root, 'TYPES'
			)";$qs[] = $q; 
			
				$pd = $id;
				$id++; $q = "insert into scf (id, pd, nm) values (
					$id, $pd, 'SYS'
				)"; $qs[] = $q; $this->oM->ja['scf']['sys'] = $id;

					$pd1 = $id;
					$id++; $q = "insert into scf (id, pd, nm, tt) values (
						$id, $pd1, 'MariaDB', 'MariaDB'
					)"; $qs[] = $q;$this->oM->ja['sys']['md'] = $id;
					$id++; $q = "insert into scf (id, pd, nm, tt) values (
						$id, $pd1, 'PostgreSQL', 'PostgreSQL'
					)"; $qs[] = $q; $this->oM->ja['sys']['pg'] = $id;
					$id++; $q = "insert into scf (id, pd, nm, tt) values (
						$id, $pd1, 'SQLite', 'SQLite'
					)"; $qs[] = $q;$this->oM->ja['sys']['sl'] = $id;
/*
					$id++; $q = "insert into scf (id, pd, nm, tt) values (
						$id, $pd, 'rl', 'rl'
					)"; $qs[] = $q;
 */
				$id++; $q = "insert into scf (id, pd, nm) values (
					$id, $pd, 'LGS'
				)"; $qs[] = $q; $this->oM->ja['scf']['lgs'] = $id;
					$pd1 = $id;
					$id++; $q = "insert into scf (id, pd, nm, tt) values (
						$id, $pd1, 'KZ', 'KZ'
					)"; $qs[] = $q;$this->oM->ja['lgs']['kz'] = $id;
					$id++; $q = "insert into scf (id, pd, nm, tt) values (
						$id, $pd1, 'US', 'US'
					)"; $qs[] = $q;$this->oM->ja['lgs']['us'] = $id;
					$id++; $q = "insert into scf (id, pd, nm, tt) values (
						$id, $pd1, 'RU', 'RU'
					)"; $qs[] = $q;$this->oM->ja['lgs']['ru'] = $id;
					
				$id++; $q = "insert into scf (id, pd, nm) values (
					$id, $pd, 'CRS'
				)"; $qs[] = $q; $this->oM->ja['scf']['crs'] = $id;
					$pd1 = $id;
					$id++; $q = "insert into scf (id, pd, nm, tt) values (
						$id, $pd1, 'KZT', 'KZT'
					)"; $qs[] = $q;$this->oM->ja['crs']['kzt'] = $id;
					$id++; $q = "insert into scf (id, pd, nm, tt) values (
						$id, $pd1, 'USD', 'USD'
					)"; $qs[] = $q;$this->oM->ja['crs']['usd'] = $id;
					$id++; $q = "insert into scf (id, pd, nm, tt) values (
						$id, $pd1, 'RUB', 'RUB'
					)"; $qs[] = $q;$this->oM->ja['crs']['rub'] = $id;

				
			return $qs;
		}
		
		function qsPGS(&$id, $root) {
			$qs = [];

			$id++; $q = "insert into scf (id, pd, nm, v) values (
				$id, $root, 'PGS', 'pgs/'
			)";$qs[] = $q;
			
					$pd = $id;
					$id++; $q = "insert into scf (id, pd, nm, v) values (
						$id, $pd, 'grid', 'grid/'
					)"; $qs[] = $q;
					
			return $qs;
		}


		function qsDBS(&$id, $root) {
			$qs = [];

			$id++; $q = "insert into scf (id, pd, nm, tt) values (
				$id, $root, 'DBS', 'DBS'
			)";$qs[] = $q;$this->oM->ja['scf']['dbs'] = $id;
				$pd = $id;
				$id++; $q = "insert into scf (id, pd, nm, tt, dbhost, dbport, dbun, dbpw, dbnm, tp) values (
					$id, $pd, 'tirlikpg', 'tirlikpg', '194.110.54.178', 5432, 'pgdbuser', 'pgdbpass', 'mypgdb', ".$this->oM->ja['sys']['pg']."
				)";$qs[] = $q;$this->oM->ja['dbs']['tirlikpg'] = $id;
			/*	
				$id++; $q = "insert into scf (id, pd, nm, tt, dbhost, dbport, un, pw, dbnm, tp) values (
					$id, $pd, 'tirlikmd', 'tirlikmd', '194.110.54.178', 3306, 'root', 'MD20221228j', 'walan', ".$this->oM->ja['sys']['md']."
				)";$qs[] = $q;$this->oM->ja['dbs']['tirlikmd'] = $id;
*/
/*
				$id++; $q = "insert into scf (id, pd, nm, tt, dbhost, dbport, un, pw, dbnm, tp) values (
					$id, $pd, 'tirlikpg', 'tirlikpg', '194.110.54.178', 5432, 'pgdbuser', 'pgdbpass', 'public', ".$this->oM->ja['sys']['pg']."
				)";$qs[] = $q;$this->oM->ja['dbs']['tirlikpg'] = $id;
	
 */
				return $qs;
		}
		
		
		
		function acqscf() {
		  $r = '';
			
		  $q = "
		  select id, tt from (
		  
			select id, nm, dc, concat('TABLES.', nm) tt
			  from scf 
			 where pd=".$this->oM->ja['scf']['tables']."
		     union all
			select id, nm, dc, concat('FMS.', nm) tt
			  from scf 
			 where pd=".$this->oM->ja['scf']['fms']."
			 union all
			select id, nm, dc, concat('US.', nm) tt
			  from scf 
			 where pd=".$this->oM->ja['scf']['us']."
			 union all
			select id, nm, dc, concat('RLS.', nm) tt
			  from scf 
			 where pd=".$this->oM->ja['scf']['rls']."
			union all
			select id, nm, dc, concat('SYS.', nm) tt
			  from scf 
			 where pd=".$this->oM->ja['scf']['sys']." 
			union all
			select id, nm, dc, concat('CRS.', nm) tt
			  from scf 
			 where pd=".$this->oM->ja['scf']['crs']." 
			union all
			select id, nm, dc, concat('DBS.', nm) tt
			  from scf 
			 where pd=".$this->oM->ja['scf']['dbs']."
			 ) t
			where ({kf}={pkv} or ({pkv} is null and tt 
				like '%{t}%')) limit 0, 10";
		  $r = $q;
		  return $r;
		}
		
		function qsTABLES(&$id, $root) {
			$qs = [];
			$pd = $this->oM->ja['scf']['tables'];
			/*
			$id++; $q = "insert into scf (id, pd, nm, tt) values (
				$id, $root, 'TABLES', 'TABLES'
			)";$qs[] = $q; $this->oM->ja['scf']['tables'] = $id;
				$pd = $id;
				$id++; $q = "insert into scf (id, pd, nm, tt, s, pkf, nmf, ttf, pdf, ctf, uf) values (
					$id, $pd, 'SCF', 'SCF', 1, 'ID', 'NM', 'TT', 'PD', 'CT', 'tbs/scf/'
							)"; $qs[] = $q; $this->oM->ja['tables']['scf'] = $id;

				$id++; $q = "insert into scf (id, pd, nm, tt, s) values (
					$id, $pd, 'SDS', 'SDS', 1
				  )"; $qs[] = $q; $this->oM->ja['tables']['sds'] = $id;*/
			
				$id++; $q = "insert into scf (id, pd, nm, tt, s, pkf, uf, fmf, ll) values (
					$id, $pd, 'ACS', 'ACS', 1, 'ID', 'tbs/acs/', 'FM', 0
				  )"; $qs[] = $q; $this->oM->ja['tables']['acs'] = $id;
				
				$id++; $q = "insert into scf (id, pd, nm, tt, s, pkf, udf, dcf, uf) values (
					$id, $pd, 'ACE', 'ACE', 1, 'ID', 'UD', 'DC', 'tbs/ace/'
				  )"; $qs[] = $q; $this->oM->ja['tables']['ace'] = $id;
				
				$id++; $q = "insert into scf (id, pd, nm, tt, s) values (
					$id, $pd, 'ACB', 'ACB', 1
				  )"; $qs[] = $q; $this->oM->ja['tables']['acb'] = $id;
				
				$id++; $q = "insert into scf (id, pd, nm, tt, s) values (
					$id, $pd, 'SUF', 'SUF', 1
				  )"; $qs[] = $q; $this->oM->ja['tables']['suf'] = $id;
				
				$id++; $q = "insert into scf (id, pd, nm, tt, s) values (
					$id, $pd, 'SGN', 'SGN', 1
				  )"; $qs[] = $q; $this->oM->ja['tables']['sgn'] = $id;
				
				$id++; $q = "insert into scf (id, pd, nm, tt, s, pkf, ttf, dcf) values (
					$id, $pd, 'AS1', 'AS1', 1, 'ID', 'NM', 'DC'
				  )"; $qs[] = $q; $this->oM->ja['tables']['as1'] = $id;
				  
				$id++; $q = "insert into scf (id, pd, nm, tt, s, pkf, ttf, dcf) values (
					$id, $pd, 'AS2', 'AS2', 1, 'ID', 'NM', 'DC'
				  )"; $qs[] = $q; $this->oM->ja['tables']['as2'] = $id;
				
				$id++; $q = "insert into scf (id, pd, nm, tt, s, pkf, ttf, dcf) values (
					$id, $pd, 'AS3', 'AS3', 1, 'ID', 'NM', 'DC'
				  )"; $qs[] = $q; $this->oM->ja['tables']['as3'] = $id;
				
				
				  
				$id++; $q = "insert into scf (id, pd, nm, tt, s, pkf, ttf, its) values (
					$id, $pd, 'ITM', 'ITM', 1, 'ID', 'NM', '[IT1.M, IT2.M, IT3.M]'
				  )"; $qs[] = $q; $this->oM->ja['tables']['itm'] = $id;
				  
				$id++; $q = "insert into scf (id, pd, nm, tt, s, pkf, ttf) values (
					$id, $pd, 'IT1', 'IT1', 1, 'ID', 'NM'
				  )"; $qs[] = $q; $this->oM->ja['tables']['it1'] = $id;
				
				$id++; $q = "insert into scf (id, pd, nm, tt, s, pkf, ttf) values (
					$id, $pd, 'IT2', 'IT2', 1, 'ID', 'NM'
				  )"; $qs[] = $q; $this->oM->ja['tables']['it2'] = $id;
				
				$id++; $q = "insert into scf (id, pd, nm, tt, s, pkf, ttf, its) values (
					$id, $pd, 'IT3', 'IT3', 1, 'ID', 'NM', '[IT4.M]'
				  )"; $qs[] = $q; $this->oM->ja['tables']['it3'] = $id;
				
				$id++; $q = "insert into scf (id, pd, nm, tt, s, pkf, ttf) values (
					$id, $pd, 'IT4', 'IT4', 1, 'ID', 'NM'
				  )"; $qs[] = $q; $this->oM->ja['tables']['it4'] = $id;
				
				$id++; $q = "insert into scf (id, pd, nm, tt, s, pkf, ttf) values (
					$id, $pd, 'EMS', 'EMS', 1, 'ID', 'TOEM'
				  )"; $qs[] = $q; $this->oM->ja['tables']['ems'] = $id;
				  
				$id++; $q = "insert into scf (id, pd, nm, tt, tp, pkf, nmf, ttf, pdf, ctf, dcf, uf) values (
					$id, $pd, 'T', 'T', "
					.$this->oM->ja['dbs']['tirlikpg']
					.", 'ID', 'NM', 'NM', 'PD', 'CT', 'DC', 'tbs/t/'
				)"; $qs[] = $q; $this->oM->ja['tables']['t'] = $id;

					
				$id++; $q = "insert into scf (id, pd, nm, tt, tp, pkf, nmf, ttf, pdf, ctf, uf, dcf, fmf, udf) values (
					$id, $pd, 'TPG', 'TPG', "
					.$this->oM->ja['dbs']['tirlikpg']
					.", 'ID', 'NM', 'TT', 'PD', 'CT', 'tbs/tpg/'
					, 'dc', 'fm', 'ud'
				)"; $qs[] = $q; $this->oM->ja['tables']['tpg'] = $id;
		
				
			return $qs;
		}



		function qsRS(&$id, $root) {
			$qs = [];

			$id++; $q = "insert into scf (id, pd, nm) values (
				$id, $root, 'RS'
			)";$qs[] = $q; $this->oM->ja['scf']['rls'] = $id;
					$pd = $id;
					$id++; $q = "insert into scf (id, pd, nm, tt) values (
						$id, $pd, 'admin', 'admin'
					)"; $qs[] = $q; $this->oM->ja['rs']['admin'] = $id;
					//table link					
						$pd1 = $id;
						$id++; $q = "insert into scf (id, pd, nm, tt, tp) values (
							$id, $pd1, 'tables', 'tables', null
						)"; $qs[] = $q; $this->oM->ja['rs']['tables'] = $id;
							$pd2 = $id;
							$id++; $q = "insert into scf (id, pd, nm, tt, tp) values (
								$id, $pd2, 't', 't', ".$this->oM->ja['tables']['t']."
							)"; $qs[] = $q;
							$id++; $q = "insert into scf (id, pd, nm, tt, tp) values (
								$id, $pd2, 'tpg', 'tpg', ".$this->oM->ja['tables']['tpg']."
							)"; $qs[] = $q;
							
							$id++; $q = "insert into scf (id, pd, nm, tt, tp) values (
								$id, $pd2, 'ace', 'ace', ".$this->oM->ja['tables']['ace']."
							)"; $qs[] = $q;
							
							$id++; $q = "insert into scf (id, pd, nm, tt, tp) values (
								$id, $pd2, 'acs', 'acs', ".$this->oM->ja['tables']['acs']."
							)"; $qs[] = $q;
							
							$id++; $q = "insert into scf (id, pd, nm, tt, tp) values (
								$id, $pd2, 'ac1', 'ac1', ".$this->oM->ja['tables']['as1']."
							)"; $qs[] = $q;
							
							$id++; $q = "insert into scf (id, pd, nm, tt, tp) values (
								$id, $pd2, 'ac2', 'ac2', ".$this->oM->ja['tables']['as2']."
							)"; $qs[] = $q;
							
							$id++; $q = "insert into scf (id, pd, nm, tt, tp) values (
								$id, $pd2, 'ac3', 'ac3', ".$this->oM->ja['tables']['as3']."
							)"; $qs[] = $q;
							
							$id++; $q = "insert into scf (id, pd, nm, tt, tp) values (
								$id, $pd2, 'itm', 'itm', ".$this->oM->ja['tables']['itm']."
							)"; $qs[] = $q;
							
						$id++; $q = "insert into scf (id, pd, nm, tt, v) values (
							$id, $pd1, 'dc', 'dc', '?pg=d&dc=5'
						)"; $qs[] = $q;						
						
						$id++; $q = "insert into scf (id, pd, nm, tt, v) values (
							$id, $pd1, 'balance', 'balance', '?pg=b'
						)"; $qs[] = $q;	
						
						$id++; $q = "insert into scf (id, pd, nm, tt, v) values (
							$id, $pd1, 'ems', 'ems', '?pg=ems'
						)"; $qs[] = $q;	
					
					//DEV
					$id++; $q = "insert into scf (id, pd, nm, tt) values (
						$id, $pd, 'dev', 'dev'
					)"; $qs[] = $q; $this->oM->ja['rs']['dev'] = $id;
					$pd1 = $id;
						$id++; $q = "insert into scf (id, pd, nm, tt, v) values (
							$id, $pd1, 'dev', 'dev', '?pg=dev'
						)"; $qs[] = $q;
						
					//USERS	
					$id++; $q = "insert into scf (id, pd, nm, tt) values (
						$id, $pd, 'user', 'users'
					)"; $qs[] = $q; $this->oM->ja['rs']['us'] = $id;
					
					
			return $qs;
		}
		
		function qsUS(&$id, $root) {
			$qs = [];
			
			$id++; $q = "insert into scf (id, pd, nm, tt) values (
				$id, $root, 'US', 'US'
			)";$qs[] = $q; $this->oM->ja['scf']['us'] = $id;
					$pd = $id;					
					  // Ваш пароль									
					// Генерация случайной соли для SHA512-CRYPT
					//$salt = '$6$' . bin2hex(random_bytes(16));  // $6$ — это спецификатор для SHA512-CRYPT
					// Хешируем пароль с использованием SHA512-CRYPT
					//$hpw = crypt($password, $salt);
					$un = '7'.$this->oM->ja['db']['admun'];
					$pw = $this->oM->ja['db']['admpw1'];
					$this->oM->ja['pw'][$un] = $pw;
					$hpw = password_hash($pw, PASSWORD_BCRYPT);	
					$id++; $q = "insert into scf (id, pd, nm, tt, un, pw) values (
						$id, $pd, 'admin', 'admin'
						, '$un'
						, '$hpw'
					)"; $qs[] = $q; $this->oM->ja['us']['admin'] = $id;
					
					
					$un = '7dev1';
					$pw = 'dev1';
					$this->oM->ja['pw'][$un] = $pw;
					$hpw = password_hash($pw, PASSWORD_BCRYPT);
					$id++; $q = "insert into scf (id, pd, nm, tt, un, pw) values (
						$id, $pd, 'dev1', 'dev1'
						, '$un'
						, '$hpw'
					)"; $qs[] = $q; $this->oM->ja['us']['dev1'] = $id;
			return $qs;
		}
		
		function qsGS(&$id, $root) {
			$qs = [];
			$id++; $q = "insert into scf (id, pd, nm, tt) values (
				$id, $root, 'GS', 'GS'
			)";$qs[] = $q; $this->oM->ja['scf']['gs'] = $id;
					$pd = $id;
					$id++; $q = "insert into scf (id, pd, nm, tt, tp) values (
						$id, $pd, 'admin', 'admin'
						, ".$this->oM->ja['rs']['admin']."
					)"; $qs[] = $q; $this->oM->ja['gs']['admin'] = $id;
						$p1 = $id;
						$id++; $q = "insert into scf (id, pd, nm, tt, tp) values (
							$id, $p1, 'admin', 'admin'
							, ".$this->oM->ja['us']['admin']."
						)"; $qs[] = $q;
					
					
					
					$id++; $q = "insert into scf (id, pd, nm, tt, tp) values (
						$id, $pd, 'dev', 'dev'
						, ".$this->oM->ja['rs']['dev']."
					)"; $qs[] = $q; $this->oM->ja['gs']['dev'] = $id;
						$p1 = $id;
						$id++; $q = "insert into scf (id, pd, nm, tt, tp) values (
							$id, $p1, 'dev1', 'dev1'
							, ".$this->oM->ja['us']['dev1']."
						)"; $qs[] = $q;
			return $qs;
		}

		function qsFMS(&$id, $root) {
			$qs = [];
			$id++; $q = "insert into scf (id, pd, nm, tt) values (
				$id, $root, 'FMS', 'FMS'
			)";$qs[] = $q; $this->oM->ja['scf']['fms'] = $id;
				$pd = $id;
				
				$id++; $q = "insert into scf (id, pd, nm, tt, uf) values (
					$id, $pd, 'fmTPG1', 'PostgreSQL TPG кесте қалпы', 'fms/fmTPG1/'
				)";$qs[] = $q; $this->oM->ja['fms']['fmTPG1'] = $id;
				
				$id++; $q = "insert into scf (id, pd, nm, tt, uf) values (
					$id, $pd, 'fmACS', 'fmACS', 'fms/fmACS/'
				)";$qs[] = $q; $this->oM->ja['fms']['fmACS'] = $id;
			return $qs;
		}


	}
?>
