<?php
	/*
	* Copyright (C) 2025 Жанибек Какимбаев
	*
	* Бұл бағдарлама GNU General Public License v3.0 (GPLv3) лицензиясы бойынша таратылады.
	* Лицензия мәтіні: https://www.gnu.org/licenses/gpl-3.0.html
	*/
class cSYNC {
    var $oMDB;
    var $rssync;
    var $a;
    var $provider1c;
    var $providerb24;

    function __construct() {

    }

    function init() {
        $r = '';
        $url = $this->a['url1c'];
        $un = $this->a['un1c'];
        $pw = $this->a['pw1c'];
        $this->provider1c = new cSOAP1C($url, $un, $pw);

        $url = $this->a['urlb24'];
        $this->providerb24 = new cWHBX24($url);

        $this->conn();
        return $r;
    }

    function loadjson($fn) {
        $r = '';
        $this->a = json_decode(file_get_contents($fn), true);
        //print_r($this->a);
        return $r;
    }

    function conn() {

        $this->oMDB->host = $this->a['host'];
        $this->oMDB->un = $this->a['un'];
        $this->oMDB->pw = $this->a['pw'];
        $this->oMDB->db = $this->a['db'];
        $this->oMDB->conn();
        $this->a['con'] = 1;
    }

    function lg() {
        $r = '';
        //Создание временной таблицы
        $q = 'create temporary table logn (id int, f varchar(10), e varchar(192), k varchar(192))';
        $this->oMDB->qry($q);
        //Фиксирование журнала изменений
        $q = 'update log set s=2 where s is null';
        $this->oMDB->qry($q);$this->oMDB->qry("commit");
        //Выбор фиксированных изменений
        $q = 'select id,f,e,k from log where s = 2';

        $q = 'insert into logn (id, f, e, k) 
                select id,f,ifnull((select nm from b24 where cmd=log.e),log.e),k 
                  from log where s = 2';
        $this->oMDB->qry($q);
        $this->oMDB->qry("commit");
        /*
        $rs = $this->oMDB->sel($q);
        foreach($rs as $rk=>$rw) {
            if($rw['F']=='b24') {
                //По Б24 получить название сущностей из команды
                $q = "insert into logn (id, f, e, k) values (".$rw['ID'].", '".$rw['F']."', (select nm from b24ce where cmd='".$rw['E']."'), '".$rw['K']."')";
            } else {
                $q = "insert into logn (id, f, e, k) values (".$rw['ID'].", '".$rw['F']."', '".$rw['E']."', '".$rw['K']."')";
            }
            $this->oMDB->qry($q);
            $this->oMDB->qry("commit");
        }*/

        //Выборка уникальных данных
        $q = 'select distinct f,e,k from logn';
        $rs = $this->oMDB->sel($q);

        $rssync = array();
        foreach($rs as $rk=>$rw) {
            $rwsync['d'] = $rw;
            $enm = '';
            switch($rw['F']) {
                case '1c':
                    $enm = $rw['E'];
                    $q = "select e2 e, k2 k from oto where e1='".$enm."' and k1='".$rw['K']."'";
                    break;
                case 'b24':
                    $enm = $rw['E']; //$this->get_b24_enm($rw['E']);
                    //echo 'enm='.$enm;
                    $q = "select e1 e, k1 k from oto where e2='".$enm."' and k2='".$rw['K']."'";
                    break;
            }
            $rs1 = $this->oMDB->sel($q);
            $cnt = count($rs1);
            if($cnt==0) { //Если в OTO нету ключа
                switch($rw['F']) {
                    case '1c':
                        $ff = 'e1, k1';
                        break;
                    case 'b24':
                        $ff = 'e2, k2';
                        break;
                }
                $q = "insert into oto ($ff) values ('".$enm."', '".$rw['K']."')";
                //echo $q;
                $this->oMDB->qry($q);
                $this->oMDB->qry("commit");
                $rwsync['cmd'] = 'add';
            } elseif($cnt==1) { //Если есть ключ
                $rw1 = $rs1[1];
                $rwsync['uv'] = $rw1['K'];
                if($rw1['K']=='') {
                    $rwsync['cmd'] = 'add';

                } else {
                    $rwsync['cmd'] = 'upd';

                }
            }
            $rssync[$rk] = $rwsync;

        }

        $this->rssync = $rssync;

        $q = 'update log set s=1 where s=2';
        $this->oMDB->qry($q);$this->oMDB->qry("commit");
        return $r;
    }

    function sync() {
        $this->lg();
        $this->ss();
    }

    function get_b24_enm($ecmd) {
        $r = '';
        switch($ecmd) {
            case 'ONCRMPRODUCTADD':
                $r = 'crm.product';
                break;
            case 'ONCRMPRODUCTUPDATE':
                $r = 'crm.product';
                break;
            default:
                $r = $ecmd;
        }
        return $r;
    }

    function ss() {
        //print_r($this->rssync);
        foreach($this->rssync as $rkrwsync=>$rw) {

            //Определение поля
            switch($rw['d']['F']) {
                case '1c':
                    $f = 'nm1';
                    break;
                case 'b24':
                    $f = 'nm2';
                    break;
            }

            //Получение Сущности из MAP
            $q = "select id, pd, nm1, nm2 from map where $f='".$rw['d']['E']."'";
            $rsp = $this->oMDB->sel($q);
            $cnt = count($rsp);
            if($cnt ==1) { //Если сущность найдена
                $rwp = $rsp[1];
                $q = "select id, pd, nm1, nm2, tp, dv1, dv2 from map where pd='".$rwp['ID']."'";
                $fs = $this->oMDB->sel($q);

                //Получение Ключевого поля из MAP
                $fk = '';
                foreach($fs as $k=>$f) {
                    if($f['TP']=='key') {
                        $fk = $f['NM1'];
                        unset($fs[$k]);
                    }
                }
                if($fk!='') { //Если ключевое поле найдено
                    switch($rw['cmd']) {
                        case 'add':
                            echo $this->add($rw, $fs, $rwp);
                            break;
                        case 'upd':
                            echo $this->upd($rw, $fs, $rwp);
                            break;
                    }
                }
            }


        }
    }

    function get_fs_1c($fs) {
        $fs1c = array();
        foreach($fs as $k=>$f) {
            $f1c['NM'] = $f['NM1'];
            $tp = '';
            switch ($f['TP']) {
                case 'varchar':
                    $tp = 'Строка';
                    break;
                case 'key':
                    $tp = 'uv';
                    break;
                case 'number':
                    $tp = 'Число';
                    break;
                case 'date':
                    $tp = 'Дата';
                    break;
                default:
                    $tp = $f['TP'];
            }
            $f1c['TP'] = $tp;
            $f1c['SL'] = 1;
            $f1c['CMP'] = '';
            $fs1c['f'.$k] = $f1c;
        }
        return $fs1c;
    }

    function get_fs_b24($fs) {
        $sl = array();
        foreach($fs as $k=>$f) {
            $sl[] = $f['NM2'];
        }
        return $sl;
    }

    function get_rw_1c($fs, $rwd) {
        $rwt = array();
        foreach($fs as $k=>$f) {
            if($f['NM2']=='') {
                $v = $f['DV1'];
            } else {
                $v = $rwd[$f['NM2']];
                if($v=='')
                    $v = $f['DV1'];
            }

            $rwt['d'][$f['NM1']] = $v;
        }
        return $rwt;
    }

    function get_rw_b24($fs, $rwd) {
        $fsb24 = array();
        foreach($fs as $k=>$f) {
            $fsb24[$f['NM2']] = $rwd['d'][$f['NM1']];
        }
        return $fsb24;
    }

    function upd_oto($f, $rwp, $newk, $k) {
        $r = '';
        switch($f) {
            case '1c':
                $q = "update oto set e2='".$rwp['NM2']."', k2='$newk' where e1='".$rwp['NM1']."' and k1='$k'";
                break;
            case 'b24':
                $q = "update oto set e1='".$rwp['NM1']."', k1='$newk' where e2='".$rwp['NM2']."' and k2='$k'";
                break;
        }
        $this->oMDB->qry($q);
        $this->oMDB->qry("commit");
        $r = $newk;
        return $r;
    }

    function add($rw, $fs, $rwp) {
        $r = '';
        switch($rw['d']['F']) {
            case '1c':
                $a = $this->provider1c->lst($rwp['NM1'], $this->get_fs_1c($fs), $rw['d']['K']);
                $a = $this->providerb24->add($rwp['NM2'], $this->get_rw_b24($fs, $a['rs']['rw1']), array(), 1);
                if(isset($a['result'])) {
                    $r = $this->upd_oto($rw['d']['F'], $rwp, $a['result'], $rw['d']['K']);
                }
                break;
            case 'b24':
                $fs1c = array();
                $a = $this->providerb24->lst($rwp['NM2'], ['ID'=>$rw['d']['K']], $this->get_fs_b24($fs));
                $dn = 1;
                /*
                $ar = explode('.', $rwp['NM1']);
                if($ar[0]=='Документ')
                    $dn = 1;*/
                $a = $this->provider1c->add($rwp['NM1'], $this->get_fs_1c($fs), $this->get_rw_1c($fs, $a['result'][0]), $dn);
                if($a['uv']!='') {
                    $r = $this->upd_oto($rw['d']['F'], $rwp, $a['uv'], $rw['d']['K']);
                }
                break;
        }

        return $r;
    }

    function upd($rw, $fs, $rwp) {
        $r = '';
        switch($rw['d']['F']) {
            case '1c':
                $a = $this->provider1c->lst($rwp['NM1'], $this->get_fs_1c($fs), $rw['d']['K']);
                $a = $this->providerb24->upd($rwp['NM2'], $rw['uv'], $this->get_rw_b24($fs, $a['rs']['rw1']), array(), 1);
                $r = $rw['uv'];
                break;
            case 'b24':
                $a = $this->providerb24->lst($rwp['NM2'], ['ID'=>$rw['d']['K']], $this->get_fs_b24($fs));
                $dn = 1;
                /*
                $ar = explode('.', $rwp['NM1']);
                if($ar[0]=='Документ')
                    $dn = 1;*/
                $a = $this->provider1c->upd($rwp['NM1'], $rw['uv'], $this->get_fs_1c($fs), $this->get_rw_1c($fs, $a['result'][0]), $dn);
                $r = $a['uv'];
                break;
        }
        return $r;
    }



}
?>