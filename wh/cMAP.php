<?php
	/*
	* Copyright (C) 2025 Жанибек Какимбаев
	*
	* Бұл бағдарлама GNU General Public License v3.0 (GPLv3) лицензиясы бойынша таратылады.
	* Лицензия мәтіні: https://www.gnu.org/licenses/gpl-3.0.html
	*/
class cMAP {
    var $a;
    var $snm;
    var $db;
    var $ss;
    var $oMDB;

    function __construct() {
        $this->a = array();
        $this->ss = true;
        $cnm = get_class($this);
        $this->snm = str_replace('\\', ':', $cnm);
    }

    function cmd($cmd) {
        $r = '';
        if($this->ss && isset($_SESSION[$this->snm]))
            $this->a = $_SESSION[$this->snm];

        //$this->init();

        switch($cmd) {
            case 'show':
                $r .= $this->show();
                break;
            case 'refr':
                $r .= $this->refr();
                break;

            case 'rwtpedit':
                $r .= $this->rwtpedit($_POST['rknm']);
                break;

            case 'set_tp':
                $r .= $this->set_tp($_POST['rknm'], $_POST['tp']);
                break;
            case 'sync':
                $r .= $this->sync();
                break;
            case 'loadmap':
                $r .= $this->loadmap();
                break;

            case 'savemap':
                $r .= $this->savemap();
                break;

            case 'loadjson':

                $r .= $this->loadjson($_POST['fn']);
                break;

            case 'savejson':
                $r .= $this->savejson();
                break;

            case 'dokey':
                $r .= $this->dokey($_POST['rknm']);
                break;

            case 'del_field':
                $r .= $this->del_field($_POST['rknm']);
                break;

            case 'slf':
                $r .= $this->slf($_POST['rknm'],$_POST['nm'],$_POST['f']);
                break;

            case 'pf':
                $this->pf($_POST['rk']);
                break;



            case 'pg':
                $this->a['pg'] = $_POST['pg'];
                $this->a['post'] = $_POST;
                break;

            case 'cnf_oc':
                $r .= $this->cnf_oc();
                break;

            case 'oc':
                $r .= $this->oc();
                break;



            case 'create_tables':
                $r .= $this->create_tables();
                break;

            case 'conn':
                $r .= $this->conn();
                break;

            case 'add_ent':
                if(!isset($this->a['rs']))
                    $this->a['rs'] = array();
                $this->a['rs'] = array_merge($this->a['rs'], $this->tmp_rs($this->a['rk']));
                //$this->set_ft();
                break;
            case 'add_field':
                //$r .= print_r($_POST, true);
                //$r .= print_r($this->add_field($_POST['pk']), true);
                $this->a['rs'] = array_merge($this->a['rs'], $this->add_field($_POST['pk'], $this->a['rk']));
                break;
            case 'clr':
                $r .= $this->clr();
                break;
            case 'fn':
                $r .= $this->fn($_GET['fn']);
                break;

        }

        if($this->ss)
            $_SESSION[$this->snm] = $this->a;
        return $r;
    }

    function init() {
        $r = '';
        if(!isset($this->a['fn'])) {
            $this->a['fn'] = 'map.json'; //Файл JSON куда сохраняется настройки Маппинга
            $this->a['pg'] = 1; //Страница отображения
            $this->a['post'] = array();
            $this->a['host'] = 'localhost'; //MySQL HOST
            $this->a['un'] = 'sync'; //MySQL UserName
            $this->a['pw'] = 'tmXKz5Wo'; //MySQL User Password
            $this->a['db'] = 'syncmap'; //MySQL DB
            $this->a['con'] = 0; //MySQL Connected
            $this->a['tncnf'] = 'CNF'; //MySQL CNF
            $this->a['tnoto'] = 'OTO'; //MySQL OTO
            $this->a['tnlog'] = 'LOG'; //MySQL CNF
            $this->a['pf'] = array(); //ProviderFields
            $this->a['ft'] = array(); //FieldTypes
            $this->a['url1c'] = '';
            $this->a['un1c'] = '';
            $this->a['pw1c'] = '';
            $this->a['urlb24'] = '';
            if(!isset($this->a['rs'])) {
                $this->loadjson($this->a['fn']);
                //$this->a['rk'] = $this->get_max_rk($this->a['rs']);
            }

        }
        if($this->a['con']==1) {
            $err = $this->conn();
            if($err!='') {
                echo $err;
                $this->a['con'] = 0;
                $_SESSION[$this->snm] = $this->a;
            }
        }

        return $r;
    }

    function show() {
        $r = '';
        //$r .= print_r($this->a['rs'], true);
        $r .= '<div id="'.$this->snm.'">';
        $r .= $this->refr();
        $r .= '</div>';
        return $r;
    }

    function refr() {
        $r = '';
		
        /*$r .= $this->button('', 'pg', 'pg=1', '', '<');
        switch($this->a['pg']) {
            case 1;
                $r .= $this->pg1();
                break;
            case 2;
                if(isset($this->a['post']['pk']) && isset($this->a['post']['ppk']))
                    $r .= $this->pg2($this->a['post']['pk'], $this->a['post']['ppk']);
                break;
        }*/

        return $r;
    }

    function button($bc, $cmd, $p, $ac, $nm) {
        $r = "<button";
        $r .= " onclick=\" var c=true; var res=''; ".$bc;
        $r .= " if(c) res=ajx('?cmd=$cmd', '$p'); if(res!='') alert(res); else document.getElementById('".$this->snm."').innerHTML=ajx('?cmd=refr', ''); ".$ac;
        $r .= "\">";
        $r .= $nm."</button>";
        return $r;
    }

    function tmp() {
        $r = '';
        return $r;
    }

    function rwtpedit($rknm) {
        $r = '';
        $this->a['rs'][$rknm]['e'] = 1;
        return $r;
    }


    function set_tp($p_rknm, $p_tp) {
        $r = '';
        if(isset($this->a['ft'])) {
            foreach ($this->a['ft'] as $rknm => $rw) {
                if($rw['id']==$p_tp) {
                    $this->a['ft'][$rknm]['sl'] = 1;
                } else {
                    $this->a['ft'][$rknm]['sl'] = 0;
                }
            }
        }
        return $r;
    }

    function set_ft() {
        $r = '';
        $i = 0;
        $i++;
        $this->a['ft']['rw'.$i]['id'] = -1;
        $this->a['ft']['rw'.$i]['nm'] = 'ent';
        $this->a['ft']['rw'.$i]['sl'] = 0;
        $i++;
        $this->a['ft']['rw'.$i]['id'] = -3;
        $this->a['ft']['rw'.$i]['nm'] = 'key';
        $this->a['ft']['rw'.$i]['sl'] = 0;
        $i++;
        $this->a['ft']['rw'.$i]['id'] = -2;
        $this->a['ft']['rw'.$i]['nm'] = 'varchar';
        $this->a['ft']['rw'.$i]['sl'] = 0;
        $i++;
        $this->a['ft']['rw'.$i]['id'] = -4;
        $this->a['ft']['rw'.$i]['nm'] = 'date';
        $this->a['ft']['rw'.$i]['sl'] = 0;
        $i++;
        $this->a['ft']['rw'.$i]['id'] = -5;
        $this->a['ft']['rw'.$i]['nm'] = 'number';
        $this->a['ft']['rw'.$i]['sl'] = 0;
        $i++;
        $this->a['ft']['rw'.$i]['id'] = -6;
        $this->a['ft']['rw'.$i]['nm'] = 'edit';
        $this->a['ft']['rw'.$i]['sl'] = 0;
        /*
        if(isset($this->a['rs'])) {
            foreach ($this->a['rs'] as $rk => $rw) {
                if ($rw['d']['TP'] == "ent") {
                    $i++;
                    $this->a['ft']['rw'.$i]['id'] = $rw['rk'];
                    $this->a['ft']['rw'.$i]['nm'] = $rw['d']['NM1'];
                    $this->a['ft']['rw'.$i]['sl'] = 0;
                }
            }
        }
        */
        return $r;
    }

    function get_tp_sel($rk) {
        $r = '';
        $rknm = 'rw'.$rk;
        $r .= "<select style=\"width:100px;\" onchange=\"var res; res=ajx('?cmd=set_tp', 'rknm=$rknm&tp='+this.value); if(res!='') alert(res); else document.getElementById('".$this->snm."').innerHTML=ajx('?cmd=refr', '');\">";
        $r .= '<option value=""></option>';

        if(isset($this->a['ft'])) {
            foreach($this->a['ft'] as $rknm=>$rw) {
                $s = '';
                if($rw['sl']==1) {
                    $s = 'selected';
                }
                $r .= '<option value="'.$rw['id'].'" '.$s.'>'.$rw['nm'].'</option>';
            }
        }

        $r .= '</select>';
        return $r;
    }

    function get_tp_sel1($rk, $tp) {
        $r = '';
        $rknm = 'rw'.$rk;
        $r .= "<select style=\"width:100px;\" onchange=\"var res; res=ajx('?cmd=set_tp', 'rknm=$rknm&tp='+this.value); if(res!='') alert(res); else document.getElementById('".$this->snm."').innerHTML=ajx('?cmd=refr', '');\">";
        $r .= '<option value=""></option>';
        $r .= '<option value="-1">Сущность</option>';
        $r .= '<option value="-2">Строка</option>';
        $r .= '<option value="-3">Ключ</option>';
        $r .= '<option value="-4">Дата</option>';
        $r .= '<option value="-5">Число</option>';

        $i=0;
        if(isset($this->a['rs'])) {
            foreach($this->a['rs'] as $rk=>$rw) {
                if ($rw['d']['TP'] == -1) {
                    $i++;
                    $s = '';
                    if($rw['rk']==$tp) {
                        $s = 'selected';
                    }
                    $r .= '<option value="'.$rw['rk'].'" '.$s.'>'.$rw['d']['NM1'].'</option>';
                }
            }
        }

        $r .= '</select>';
        return $r;
    }

    function get_max_rk($rs) {
        $r = 0;
        $a = array();
        foreach ($rs as $rknm=>$rw) {
            $a[] = $rw['rk'];
        }
        $r = max($a);
        return $r;
    }

    function sync() {
        $r = '';
        $oSYNC = new cSYNC();
        $oSYNC->loadjson('map.json');
        $oSYNC->oMDB = new cMDB('', '', '', '');
        $oSYNC->init();
        $oSYNC->sync();
        unset($oSYNC);
        $r .= 'Синхронизовано';
        return $r;
    }

    function bx($e, $id) {
        $r = '';
        $this->conn();
        $q = "insert into log (f, e, k) values ('b24', '$e', '$id')";
        $this->oMDB->qry($q);$this->oMDB->qry('commit');
        return $r;
    }

    function loadmap() {
        $r = '';

        $q = "select id, pd, nm1, nm2, tp, dv1, dv2 from map";
        $rs = $this->oMDB->sel($q);
        foreach($rs as $rk=>$rw) {
            $rwa['rk'] = $rw['ID'];
            $rwa['pk'] = $rw['PD'];
            $rwa['d']['NM1'] = $rw['NM1'];
            $rwa['d']['NM2'] = $rw['NM2'];
            $rwa['d']['TP'] = $rw['TP'];
            $rwa['d']['DV1'] = $rw['DV1'];
            $rwa['d']['DV2'] = $rw['DV2'];
            $this->a['rs']['rw'.$rwa['rk']] = $rwa;
        }
        if(isset($this->a['rs'])) {
            $this->a['rk'] = $this->get_max_rk($this->a['rs']);
            //echo 'rk='.$this->a['rk'];
        }

        return $r;
    }

    function savemap() {
        $r = '';

        if(isset($this->a['rs']) && count($this->a['rs'])>0) {
            $q = 'select count(*) cnt from map';
            $rs = $this->oMDB->sel($q);
            if($rs[1]['CNT']==0) {
                foreach($this->a['rs'] as $rk=>$rw) {
                    if($rw['d']['NM1']!='' && $rw['d']['NM2']!='') {
                        $q = "insert into map (id, pd, nm1, nm2, tp) values (".$rw['rk'].", ".$rw['pk'].", '".$rw['d']['NM1']."', '".$rw['d']['NM2']."', ".$rw['d']['TP'].")";
                        $this->oMDB->qry($q);
                        $this->oMDB->qry('commit');
                    }
                }
            } else {
                $r .= 'Существуют записи в map';
            }
        }
        return $r;
    }

    function loadjson($fn) {
        $r = '';
        $this->a = json_decode(file_get_contents($fn), true);
        if(isset($this->a['rs'])) {
            $this->a['rk'] = $this->get_max_rk($this->a['rs']);
            //echo 'rk='.$this->a['rk'];
        }
        return $r;
    }

    function savejson() {
        $r = '';
        if($this->a['fn']!='') {
            $r .= 'Сохранено в '.$this->a['fn'];

            file_put_contents($this->a['fn'], json_encode($this->a));
        }
        return $r;
    }

    function conn() {
        $r = '';
        $this->oMDB->host = $this->a['host'];
        $this->oMDB->un = $this->a['un'];
        $this->oMDB->pw = $this->a['pw'];
        $this->oMDB->db = $this->a['db'];
        $r .= $this->oMDB->conn();
        $this->a['con'] = 1;
        return $r;
    }

    function wh($tp, $e, $k) {
        $r = '';

        if($tp!='' && $e!='' && $k!='') {
            $this->conn();
            $this->oMDB->qry("insert into log (f, e, k) values ('".$tp."','".$e."','".$k."')");
            $this->oMDB->qry("commit");
        }
        return $r;
    }

    function dokey($rknm) {
        $r = '';
        foreach($this->a['rs'] as $rk=>$rw) {
            if($rw['d']['TP']!=-1) {
                if($rk==$rknm) {
                    $rw['d']['TP'] = -3;
                } else {
                    $rw['d']['TP'] = -2;
                }
            }
            $this->a['rs'][$rk] = $rw;
        }
        return $r;
    }

    function del_field($rknm) {
        $r = '';
        $prw = $this->a['rs'][$rknm];
        foreach($this->a['rs'] as $rk=>$rw) {
            if($rw['pk']==$prw['rk']) {
                $this->del_field($rk);
            }
        }
        unset($this->a['rs'][$rknm]);
        return $r;
    }

    function slf($rknm, $nm, $p_f) {
        $r = '';
        $fs = $this->a['pf'][$rknm][$nm];
        foreach($fs as $fn=>$f) {
            if($f['NM']==$p_f)
                $fs[$fn]['SL'] = 2;
            else
                $fs[$fn]['SL'] = 0;
        }
        //print_r($fs);
        $this->a['pf'][$rknm][$nm] = $fs;

        return $r;
    }

    function pf($p_rk) { //Get ProviderFields
        $r = '';
        foreach($this->a['rs'] as $rk=>$rw) {
            if($rw['rk']==$p_rk) {
                $url = $this->a['url1c'];
                $un = $this->a['un1c'];
                $pw = $this->a['pw1c'];
                $oSOAP1C = new cSOAP1C($url, $un, $pw);
                $a = $oSOAP1C->efs($rw['d']['NM1']);
                $this->a['pf'][$rk]['fs1'] = $a['fs'];
                unset($oSOAP1C);

                $url = $this->a['urlb24'];

                $oWHBX24 = new cWHBX24($url);

                $data = $oWHBX24->efs($rw['d']['NM2']);
                //$data = $oWHBX24->executeREST($rw['d']['NM2'].'.fields', []);
                $ff = array();
                $fn = 'NoField';
                $ff[$fn]['NM'] = '';
                $ff[$fn]['TP'] = 'NoField';
                $ff[$fn]['TT'] = 'NoField';
                $ff[$fn]['SL'] = 0;
                $ff[$fn]['CMP'] = '';

                if(isset($data['result'])) { //$data['error']
                    foreach($data['result'] as $fn=>$f) {
                        $ff[$fn]['NM'] = $fn;
                        $ff[$fn]['TP'] = $f['type'];
                        $ff[$fn]['TT'] = $f['title'];
                        $ff[$fn]['SL'] = 1;
                        $ff[$fn]['CMP'] = '';
                    }
                }
                $this->a['pf'][$rk]['fs2'] = $ff;
                //print_r($data);
                unset($oWHBX24);


            }
        }




        return $r;
    }

    function get_pf_sel($rk, $nm) {
        $r = '';

        $rknm = 'rw'.$rk;
        $fs = $this->a['pf'][$rknm][$nm];
/*
        if($nm=='fs2') {
            $findedNF = false;
            foreach($fs as $fn=>$f) {
                if($f['TP']=='NF') {
                    $findedNF = true;
                }
            }
            if(!$findedNF) {
                $f['NM'] = '';
                $f['TP'] = 'NF';
                $f['TT'] = 'NF';
                $f['SL'] = 1;
                $f['CMP'] = '';
                $this->a['pf'][$rknm][$nm]['NF'] = $f;
            }

        }*/

        $iss = false;
        foreach($fs as $fn=>$f) {
            if($f['SL']==2) {
                $iss = true;
            }
        }
        $r .= "<select style=\"width:100px;\" onchange=\"var res; res=ajx('?cmd=slf', 'rknm=$rknm&nm=$nm&f='+this.value); if(res!='') alert(res); else document.getElementById('".$this->snm."').innerHTML=ajx('?cmd=refr', '');\">";
        $i=0;
        foreach($fs as $fn=>$f) {
            $i++;
            $s = '';
            if(!$iss && $i==1) {
                $this->a['pf'][$rknm][$nm][$fn]['SL'] = 2;
                $s = 'selected';
            }
            if($f['SL']==2) {
                $s = 'selected';
            }
            $r .= '<option value="'.$f['NM'].'" '.$s.'>'.$f['NM'].'('.$f['TP'].')'.'</option>';
        }
        $r .= '</select>';
        return $r;
    }

    function pg2($pk, $ppk) {
        $r = '';
        if(!isset($this->a['ft']))
            $this->set_ft();
        $prw = $this->a['rs']['rw'.$pk];
        $r .= $prw['d']['NM1'].'='.$prw['d']['NM2'];
        $r .= "<br/>";
        if(isset($this->a['pf']['rw'.$pk])) {
            //print_r($this->a['pf']['rw'.$pk]['fs2']);
            $r .= $this->get_pf_sel($pk, 'fs1');
            $r .= $this->get_pf_sel($pk, 'fs2');
            $r .= $this->get_tp_sel($pk, $prw['d']['TP']);
            $r .= $this->button('', 'add_field', 'pk='.$pk, '', 'Добавить ПОЛЕ');
        }


        //print_r($this->a['pf']);
        $r .= '<table>';
        $r .= '<tbody>';
        $r .= $this->get_tb($pk, $ppk);
        $r .= '</tbody>';
        $r .= '</table>';

        return $r;
    }

    function pg1() {
        $r = '';

        //$r .= print_r($this->a['rs'], true);

        $r .= $this->button('', 'clr', '', '', 'Очистить');
        $r .= $this->cnf();
        $r .= '<table>';
        $r .= '<tbody>';
        $i = 0;
        if(isset($this->a['rs']))
            foreach($this->a['rs'] as $rk=>$rw) {
                if($rw['d']['TP']=='ent') {
                    //$r .= $this->get_tb($rw['rk']);
                    $i++;
                    $r .= '<tr>';
                    //$r .= '<td>'.$rw['rk'].'->'.$rw['pk'].$this->get_tp($rw['d']['TP']).'</td>';
                    //$r .= '<td>'.$this->get_tp($rw['d']['TP']).'</td>';
                    $r .= '<td>'.$i.'</td>';
                    $r .= '<td><input type="text" name="'.$rk.'_obj" value="'.$rw['d']['NM1'].'" '.$this->get_oc($rw['rk'],'NM1').'/>';
                    $r .= '<td><input type="text" name="'.$rk.'_obj" value="'.$rw['d']['NM2'].'" '.$this->get_oc($rw['rk'],'NM2').'/>';
                    $r .= $this->button("", 'pg', 'pg=2&pk='.$rw['rk'].'&ppk='.$rw['pk'], '', '[*]');
                    $r .= $this->button("c=confirm('Получить ПОЛЯ?');", 'pf', 'rk='.$rw['rk'], '', '[#]');
                    $r .= $this->button("c=confirm('Удалить?');", 'del_field', 'rknm='.$rk, '', 'Удалить');
                    $r .= '</td>';
                    $r .= '</tr>';



                }
            }
        $r .= '</tbody>';
        $r .= '</table>';
        if(isset($this->a['rs']) && count($this->a['rs'])>0)
            $r .= $this->button("c=confirm('Сохранить MAP?');", 'savemap', '', '', 'Сохранить MAP');
        $r .= $this->button('', 'add_ent', '', '', 'Добавить СУЩНОСТЬ');
        return $r;
    }

    function cnf_oc() {
        $r = '';
        //$r .= print_r($_POST);
        if(isset($_POST['f']) && isset($_POST['v'])) {
            $this->a[$_POST['f']] = rawurldecode($_POST['v']);
        }

        return $r;
    }

    function oc() {
        $r = '';
        //$r .= print_r($_POST);
        if(isset($_POST['rk']) && isset($_POST['f']) && isset($_POST['v'])) {
            $this->a['rs']['rw'.$_POST['rk']]['d'][$_POST['f']] = rawurldecode($_POST['v']);
        }

        return $r;
    }



    function get_stp() {
        $r = '';
        $r .= '<select>';
        $r .= '<option value=""></option>';
        $r .= '<option value="1">1с</option>';
        $r .= '<option value="2">Битрикс24</option>';
        $r .= '</select>';
        return $r;
    }

    function create_tables() {
        $r = '';


        $rs = $this->oMDB->sel("SHOW TABLES LIKE 'map'");
        if(count($rs)==0) { //drop table map
            $q = "
          create table map (
            id int not null primary key auto_increment comment 'Кілт'
            , pd int comment 'Родитель'
            , nm1 varchar(192) comment 'Название1'
            , nm2 varchar(192) comment 'Название2'
            , tp varchar(192) comment 'ТИП'
            , dv1 varchar(192) comment 'ЗначениеПоУмолчанию1'
            , dv2 varchar(192) comment 'ЗначениеПоУмолчанию2'          
          ) comment '';
        ";
            $this->oMDB->qry($q); //, index (tp), foreign key (tp) references tps(id)
        }

        $rs = $this->oMDB->sel("SHOW TABLES LIKE 'oto'");
        if(count($rs)==0) {
            $q = "
          create table oto (
            id int not null primary key auto_increment comment 'Кілт'
            , e1 varchar(192)
            , e2 varchar(192)
            , k1 varchar(192)
            , k2 varchar(192)
          ) comment '';
        ";
            $this->oMDB->qry($q);
        }

        $rs = $this->oMDB->sel("SHOW TABLES LIKE 'log'");
        if(count($rs)==0) { //drop table log
            $q = "
          create table log (
            id int not null primary key auto_increment comment 'Кілт'
            , gettxt text
            , posttxt text
            , sfrom int
            , e varchar(192)
            , k varchar(192)
            , s int
            , sdt timestamp
          ) comment '';
        ";

            $q = "
          create table log (
            id int not null primary key auto_increment comment 'Кілт'
            , f varchar(10) comment 'Откуда'
            , e varchar(192) comment 'Сущность'
            , k varchar(192) comment 'Ключ'
            , s int comment 'Статус'
            , sdt timestamp  comment 'Время'
          ) comment '';
        ";
            $this->oMDB->qry($q);
        }

        $rs = $this->oMDB->sel("SHOW TABLES LIKE 'b24ce'");
        if(count($rs)==0) { //drop table map
            $q = "
              create table b24ce (
                id int not null primary key auto_increment comment 'Кілт'
                , cmd varchar(192)
                , nm varchar(192)                      
              ) comment '';
            ";
            $this->oMDB->qry($q); //, index (tp), foreign key (tp) references tps(id)

            $q = "insert into b24ce (id, cmd, nm) values (1, 'ONCRMPRODUCTADD', 'crm.product')";
            $this->oMDB->qry($q); $this->oMDB->qry('commit');
            $q = "insert into b24ce (id, cmd, nm) values (2, 'ONCRMPRODUCTUPDATE', 'crm.product')";
            $this->oMDB->qry($q); $this->oMDB->qry('commit');
            $q = "insert into b24ce (id, cmd, nm) values (3, 'ONCRMCONTACTADD', 'crm.contact')";
            $this->oMDB->qry($q); $this->oMDB->qry('commit');
            $q = "insert into b24ce (id, cmd, nm) values (3, 'ONCRMCONTACTUPDATE', 'crm.contact')";
            $this->oMDB->qry($q); $this->oMDB->qry('commit');
        }

        return $r;
    }





    function get_cnf_oc($nm) {
        $r = '';
        $r .= "onchange=\"var v=''; var res='';";
        $r .= "v=encodeURIComponent(this.value);";
        $r .= "res=ajx('?cmd=cnf_oc', 'f=$nm&v='+v);";
        $r .= "if(res!='') alert(res); else document.getElementById('".$this->snm."').innerHTML=ajx('?cmd=refr', '');\"";
        return $r;
    }

    function get_oc($rk, $nm) {
        $r = '';
        $r .= "onchange=\"var v=''; var res='';";
        $r .= "v=encodeURIComponent(this.value);";
        $r .= "res=ajx('?cmd=oc', 'rk=$rk&f=$nm&v='+v);";
        $r .= "if(res!='') alert(res); else document.getElementById('".$this->snm."').innerHTML=ajx('?cmd=refr', '');\"";
        return $r;
    }

    function cnf() {
        $r = '';
        if(isset($this->a['fn'])) {
            $r .= '<table>';
            $r .= '<tr><td>Файл JSON</td><td>'."<input type=\"text\" value=\"".$this->a['fn']."\" ".$this->get_cnf_oc('fn')."/>".'</td></tr>';
            $r .= '<tr><td colspan="2">'.$this->button("c=confirm('Сохранить в ".$this->a['fn']."?');", 'savejson', '', '', 'Сохранить');
            $r .= $this->button("c=confirm('Загрузить из ".$this->a['fn']."?');", 'loadjson', 'fn='.$this->a['fn'], '', 'Загрузить').'</td></tr>';
            $r .= '<tr><td>URL1C</td><td>'."<input type=\"text\" value=\"".$this->a['url1c']."\" ".$this->get_cnf_oc('url1c')."/>".'</td></tr>';
            $r .= '<tr><td>UN1C</td><td>'."<input type=\"text\" value=\"".$this->a['un1c']."\" ".$this->get_cnf_oc('un1c')."/>".'</td></tr>';
            $r .= '<tr><td>PW1C</td><td>'."<input type=\"text\" value=\"".$this->a['pw1c']."\" ".$this->get_cnf_oc('pw1c')."/>".'</td></tr>';
            $r .= '<tr><td>URLB24</td><td>'."<input type=\"text\" value=\"".$this->a['urlb24']."\" ".$this->get_cnf_oc('urlb24')."/>".'</td></tr>';

            $r .= '<tr><td>MySQL HOST</td><td>'."<input type=\"text\" value=\"".$this->a['host']."\" ".$this->get_cnf_oc('host')."/>".'</td></tr>';
            $r .= '<tr><td>MySQL UserName</td><td>'."<input type=\"text\" value=\"".$this->a['un']."\" ".$this->get_cnf_oc('un')."/>".'</td></tr>';
            $r .= '<tr><td>MySQL User Password</td><td>'."<input type=\"password\" value=\"".$this->a['pw']."\" ".$this->get_cnf_oc('pw')."/>".'</td></tr>';
            $r .= '<tr><td>MySQL DataBase</td><td>'."<input type=\"text\" value=\"".$this->a['db']."\" ".$this->get_cnf_oc('db')."/>".'</td></tr>';
            $r .= '<tr><td colspan="2">';
            if($this->a['con']==0)
                $r .= $this->button('', 'conn', '', '', 'Подключиться к базе MySQL');
            if($this->a['con']==1) {
                $r .= $this->button('', 'create_tables', '', '', 'Создать ТАБЛИЦЫ');
                $r .= $this->button("c=confirm('Загрузить из MAP?');", 'loadmap', '', '', 'Загрузить из MAP');
                $r .= $this->button("c=confirm('Синхронизировать?');", 'sync', '', '', 'Синхронизация');
            }
            $r .= '</td></tr>';

            $r .= '</table>';
        }
        return $r;
    }

    function add_field($pk, &$rk) {
        $r = array();

        $fs = $this->a['pf']['rw'.$pk]['fs1'];
        $nm1 = '';
        foreach($fs as $fn=>$f) {
            if($f['SL']==2) {
                $nm1 = $f['NM'];
            }
        }
        $fs = $this->a['pf']['rw'.$pk]['fs2'];
        $nm2 = '';
        foreach($fs as $fn=>$f) {
            if($f['SL']==2) {
                $nm2 = $f['NM'];
            }
        }

        $tp = 0;
        foreach($this->a['ft'] as $rknm=>$rw) {
            if($rw['sl']==1) {
                //$tp=$rw['id'];
                $tp=$rw['nm'];
            }
        }
        $this->set_ft();

        $rk++; $r['rw'.$rk] = $this->tmp_rw($pk, $rk, $nm1, $nm2, $tp);
        if($tp=='edit')
            $r['rw'.$rk]['e'] = 1;
        return $r;
    }

    function clr() {
        $r = '';
        $this->a = array();
        unset($_SESSION[$this->snm]);
        return $r;
    }

    function fn($fn) {
        $r = '';
        if(file_exists($fn)) {
            $this->a = json_decode(file_get_contents($fn), true);
        }
        $this->a['fn'] = $fn;
        return $r;
    }

    function get_fld($p_rk) {
        $r = '';
        $r .= '<table>';
        $r .= '<tbody>';
        foreach($this->a['rs'] as $rk=>$rw) {

            if($rw['rk']==$p_rk || $rw['pk']==$p_rk) {
                $r .= '<tr>';
                $r .= '<td>'.$this->get_tp($rw['d']['TP']).'</td>';
                if($rw['d']['TP']==13) {
                    $r .= '<td>'.$this->fk($rw['d']['NM']).'</td>';
                } else {
                    $r .= '<td><input type="text" value="'.$rw['d']['NM'].'"/></td>';
                }
                $r .= '</tr>';
            }
        }
        $r .= '</tbody>';
        $r .= '</table>';
        return $r;
    }

    function fk($v) {
        $r = '';

        $r .= '<select>';
        $r .= '<option value=""></option>';
        $r .= '<option value="-1">Ключ</option>';

        foreach($this->a['rs'] as $rk=>$rw) {
            if($rw['d']['TP']==1) {
                $r .= '<option value="'.$rw['rk'].'">'.$rw['d']['NM'].'</option>';
            }
        }
        $r .= '</select>';
        return $r;
    }

    function get_tb($pk, $show_ed=true) {
        $r = '';
        //$r .= '<table>';
        //$r .= '<tbody>';


        foreach($this->a['rs'] as $rk=>$rw) {
            /*if($showpk && $rw['rk']==$pk) {

            }*/

            if($rw['pk']==$pk) {
                $r .= '<tr>';
                $k = '';
                //if($rw['d']['TP']==-3)
                    //$k = '*';
                //$r .= '<td>';
                //if($k != '*')
                //    $r .= $this->button("c=confirm('".$rw['d']['NM1'].'='.$rw['d']['NM2']." Ключ?');", 'dokey', 'rknm='.$rk, '', '*');
                //else
                    //$r .= $k;
                //$r .= '</td>';
                //$r .= '<td>'.$k.$this->get_tp($rw['d']['TP']).'</td>'; //$rw['rk'].'->'.$rw['pk']
                if($show_ed) {
                    $r .= '<td><input type="text" name="'.$rk.'_obj" value="'.$rw['d']['NM1'].'" '.$this->get_oc($rw['rk'],'NM1').'/></td>';
                    $r .= '<td><input type="text" name="'.$rk.'_obj" value="'.$rw['d']['NM2'].'" '.$this->get_oc($rw['rk'],'NM2').'/></td>';
                } {
                    if($rw['d']['TP']=='key') {
                        $r .= '<th>'.$rw['d']['NM1'].'</th>';
                        $r .= '<th>'.$rw['d']['NM2'].'</th>';
                    } else {
                        $r .= '<td>'.$rw['d']['NM1'].'</td>';
                        $r .= '<td>'.$rw['d']['NM2'].'</td>';
                    }

                    //$r .= '<td>'.$this->get_tp($rw['d']['TP']).'</td>';
                    if(isset($rw['e']) && $rw['e']==1) {
                        $r .= '<td><input type="text" name="'.$rk.'_obj" value="'.$rw['d']['TP'].'" '.$this->get_oc($rw['rk'],'TP').'/>';
                        $r .= '<td><input type="text" name="'.$rk.'_obj" value="'.$rw['d']['DV1'].'" '.$this->get_oc($rw['rk'],'DV1').'/>';
                        $r .= '<td><input type="text" name="'.$rk.'_obj" value="'.$rw['d']['DV2'].'" '.$this->get_oc($rw['rk'],'DV2').'/>';
                    } else {
                        $r .= '<td>'.$rw['d']['TP'];
                        //$r .= $this->button("c=confirm('Изменить?');", 'rwtpedit', 'rknm='.$rk, '', '*');
                        $r .= '</td>';
                        $r .= '<td>'.$rw['d']['DV1'].'</td>';
                        $r .= '<td>'.$rw['d']['DV2'].'</td>';
                    }

                    $r .= '<td>';
                    $r .= $this->button("c=confirm('Удалить?');", 'del_field', 'rknm='.$rk, '', 'Удалить');

                    $r .= '</td>';
                }
                $r .= '</tr>';
                /*
                if($rw['d']['TP']<11) {
                  $r .= '<tr>';
                  $r .= '<td colspan="2">'.$this->get_tb($rw['rk'], true).'</td>';
                  $r .= '</tr>';
                } elseif($rw['d']['TP']==11) {
                  $r .= '<tr>';
                  $r .= '<td colspan="2">'.$this->get_fld($rw['rk']).'</td>';
                  $r .= '</tr>';
                }
                */
            }
        }



        //$r .= '</tbody>';
        //$r .= '</table>';
        return $r;
    }





    function get_tp($id) {
        $r = '';
        foreach($this->a['ft'] as $rknm=>$rw) {
            if($rw['id']==$id)
                $r = $rw['nm'];
        }
        return $r;
    }

    function get_tp1($id) {
        $r = '';
        switch($id) {
            case -1:
                $r .= 'Сущность';
                break;
            case -2:
                $r .= 'Строка';
                break;
            case -3:
                $r .= 'Ключ';
                break;
            case -4:
                $r .= 'Дата';
                break;
            case -5:
                $r .= 'Число';
                break;
            case 6:
                $r .= 'Метод ADD';
                break;
            case 7:
                //$r .= 'ADD(Б24->1С) Б24.Поле=1С.Ссылка';
                $r .= '1С.PKF';
                break;
            case 8:
                //$r .= 'ADD(1С->Б24) 1С.Реквизит=Б24.ID';
                $r .= 'Б24.PKF';
                break;
            case 9:
                //$r .= 'UPD DEL(Б24->1С) Б24.Поле=Статус';
                break;
            case 10:
                //$r .= 'UPD DEL(1С->Б24) 1С.Поле=Статус';
                break;
            case 11:
                $r .= '1С.Реквизит';
                break;
            case 12:
                $r .= 'Б24.Поле';
                break;
            case 13:
                $r .= 'FK(1->8)';
                break;
        }
        return $r;
    }


    function tmp_json() {
        $a = array();
        $a['rk'] = 0;
        $a['rs'] = $this->tmp_rs($a['rk']);
        //$a['rs'][] = $this->add_field($pk, 'Реквизит1', 'NM');
        $a['rs'] = array_merge($a['rs'], $this->add_field($pk, $a['rk']));
        //print_r($a);
        return $a;
    }

    function tmp_rw($pk, $rk, $nm1, $nm2, $tp) {
        $r = array();
        $r['pk'] = $pk;
        $r['rk'] = $rk;
        $r['d']['ID'] = '';
        $r['d']['NM1'] = $nm1;
        $r['d']['NM2'] = $nm2;
        $r['d']['TP'] = $tp;
        $r['d']['DV1'] = '';
        $r['d']['DV2'] = '';
        return $r;
    }

    function tmp_rs(&$rk) {
        $r = array();
        $pk = 0;
        $rk++; $r['rw'.$rk] = $this->tmp_rw($pk, $rk, '', '', 'ent');
        return $r;
    }
}
?>