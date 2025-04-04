<?php
	/*
	* Copyright (C) 2025 Жанибек Какимбаев
	*
	* Бұл бағдарлама GNU General Public License v3.0 (GPLv3) лицензиясы бойынша таратылады.
	* Лицензия мәтіні: https://www.gnu.org/licenses/gpl-3.0.html
	*/
class cSOAP1C implements iProviderInterface
{
    var $idc;
    var $url;
    var $un;
    var $pw;
    var $oMAP;

    function __construct($url, $un, $pw) {
        $this->url = $url;
        $this->un = $un;
        $this->pw = $pw;
        $this->idc = $this->Connect1C();
    }

    function Connect1C(){
        if (!function_exists('is_soap_fault')){
            print 'Не настроен web сервер. Не найден модуль php-soap.';
            return false;
        }
        $opts = array(
            'ssl'=>array(
                'verify_peer'=>false,
                'verify_peer_name'=>false,
                'allow_self_signed' => true
            ),
            'https' => array(
                'curl_verify_ssl_peer'  => false,
                'curl_verify_ssl_host'  => false,
                'user_agent' => 'PHPSoapClient',
                'allow_self_signed' => true
            )
        );
        try {
            $Клиент1С = new SoapClient($this->url,
                array('login'          => $this->un, //bitrix24
                    'password'       => $this->pw, //Vi420Siv$r
                    'soap_version'   => SOAP_1_2,
                    'cache_wsdl'     => WSDL_CACHE_NONE, //WSDL_CACHE_MEMORY, //, WSDL_CACHE_NONE, WSDL_CACHE_DISK or WSDL_CACHE_BOTH
                    'exceptions'     => true,
                    'trace'          => 1,
                    'cache_wsdl'     => 0,
                    //'location'       => 'http://localhost/soap/ws1.1cws?wsdl',
                    'verifypeer'     => false,
                    'verifyhost'     => false,
                    'stream_context' => stream_context_create($opts),
                    'classmap' => array('AcsEmployeeSaveData' => "AcsEmployeeSaveData")
                ));
        }catch(SoapFault $e) {
            //trigger_error('Ошибка подключения или внутренняя ошибка сервера. Не удалось связаться с базой 1С.', E_ERROR);
            var_dump($e);
        }
        //echo 'Раз<br>';
        if (is_soap_fault($Клиент1С)){
            //trigger_error('Ошибка подключения или внутренняя ошибка сервера. Не удалось связаться с базой 1С.', E_ERROR);
            return false;
        }
        return $Клиент1С;
    }

    function GetData1c($ar){
        $ret1c = '';
        if (is_object($this->idc)){

            try {
                $txt = json_encode($ar);
                $par = array('jsontxt' => $txt);
                //var_dump($par);
                $ret1c = $this->idc->IO($par);
            } catch (SoapFault $e) {
                echo "ОЩИБКА!!! </br>";
                var_dump($e);
                print_r($ar);exit;
            }
        }
        else{
            echo 'Не удалося подключиться к 1С<br>';
            var_dump($this->idc);
        }
        //var_dump($ret1c->return);

        return json_decode($ret1c->return, true);
        //return array_values(json_decode($ret1c->return, false));
    }

    function GetPost($get, $post) {
        $this->oMAP->qry("insert into log (gettxt, posttxt) values ('".print_r($get, true)."','".print_r($post, true)."')");
        $this->oMAP->qry("commit");
    }

    function lst($ent, $fs, $uv) {
        $a = array();
        $a['ent'] = $ent;
        $a['cmd'] = 'list';
        $a['uv'] = $uv;
        $a['sql'] = $this->get_sql($ent, $fs);
        //echo $a['sql'];
        $a['sql'] = base64_encode($a['sql']);
        //if($sql!='')
        //$a['sql'] = base64_encode($sql);
        $a['dn'] = 0;
        $a['fs'] = $fs;
        $a['rsmv'] = 0;
        $a['rs'] = array();
        $a = $this->GetData1c($a);

        return $a;
    }

    function upd($ent, $uv, $fs, $rw, $dn) {
        $a = array();
        $a['ent'] = $ent;
        $a['cmd'] = 'upd';
        $a['uv'] = $uv;
        $a['dn'] = $dn;
        $a['fs'] = $fs;
        $a['rs'] = array();
        $a['rs']['rw1'] = $rw;
        $a = $this->GetData1c($a);
        return $a;
    }

    function add($ent, $fs, $rw, $dn) {
        $a = array();
        $a['ent'] = $ent;
        $a['cmd'] = 'add';
        $a['uv'] = '';
        $a['dn'] = $dn;
        $a['fs'] = $fs;
        $a['rs'] = array();
        $a['rs']['rw1'] = $rw;
        $a = $this->GetData1c($a);
        return $a;
    }

    function efs($ent) {
        $a = array();
        $a['ent'] = $ent;
        $a['dn'] = 0;
        $a['cmd'] = 'fields';
        $a['fs'] = array();
        $a['rsmv'] = 0;
        $a['rs'] = array();
        $a = $this->GetData1c($a);

        return $a;
    }

    public function del($uv)
    {

    }





    function get_rsr($rs, $fs, $d) {
        $r = '';
        //print_r($fs);
        $r .= '<table>';

        $r .= '<thead>';
        $r .= '<tr>';

        $smrw = array();
        $i = 0;
        foreach($rs as $rk=>$rw) {
            $i++;
        }
        $r .= '<th>'.$i.'</th>';
        foreach($fs as $fn=>$f) {
            if($f['SL']==1) {
                $r .= '<th>'.$f['NM'].'</th>';
                $smrw[$f['NM']] = 0;
            }
        }
        $r .= '</tr>';

        //Қорытынды
        foreach($rs as $rk=>$rw) {
            foreach($fs as $fn=>$f) {
                if($f['SL']==1) {
                    if($d=='') {
                        if(is_numeric($rw[$f['NM']]))
                            $smrw[$f['NM']] += $rw[$f['NM']];
                    } else {
                        if(is_numeric($rw[$d][$f['NM']]))
                            $smrw[$f['NM']] += $rw[$d][$f['NM']];
                    }
                }
            }

            $r .= '</tr>';
        }

        $r .= '<tr>';
        $r .= '<th></th>';
        foreach($fs as $fn=>$f) {
            if($f['SL']==1)
                $r .= '<th>'.$smrw[$f['NM']].'</th>';
        }
        $r .= '</tr>';


        $r .= '</thead>';


        $r .= '<tbody>';
        $i = 0;
        foreach($rs as $rk=>$rw) {
            $i++;
            $r .= '<tr>';
            $r .= '<td>'.$i.'</td>';
            foreach($fs as $fn=>$f) {
                if($f['SL']==1) {
                    if($d=='')
                        $r .= '<td>'.$rw[$f['NM']].'</td>';
                    else
                        $r .= '<td>'.$rw[$d][$f['NM']].'</td>';
                }
            }

            $r .= '</tr>';
        }



        $r .= '</tbody>';


        $r .= '</table>';
        return $r;
    }

    function get_fsr($fs) {
        $r = '';
        $r .= '<table>';
        $r .= '';
        $r .= '';
        $i = 0;
        foreach($fs as $fn=>$f) {
            $i++;
            $r .= '<tr><th>'.$i.'</th><th>'.$f['NM'].'</th></tr>';
        }
        $r .= '';
        $r .= '</table>';
        return $r;
    }

    function get_sql($ent, $fs) {
        $r = '';
        $sfs = '';
        $wfs = '';
        $i = 0;
        $j = 0;
        foreach($fs as $fn=>$f) {
            if($f['SL']==1) {
                $i++;
                if($i>1)
                    $sfs .= ',';

                $sfs .= 't.'.$f['NM'];
            }
            if($f['CMP']!='') {
                $j++;
                if($j==1)
                    $wfs .= ' where ';
                if($j>1)
                    $wfs .= ' and ';
                if($f['TP']=='uv') {
                    $wfs .= 'Строка(УникальныйИдентификатор(t.'.$f['NM'].'))='.$f['CMP'];
                } else {
                    $wfs .= 't.'.$f['NM'].' '.$f['CMP'];
                }
            }

        }
        $r .= 'select '.$sfs.' from '.$ent.' as t '.$wfs;
        return $r;
    }



    function get_ent_list() {
        $a = array();
        $a['ent'] = 'ent_list';
        $a['cmd'] = 'ent_list';
        $a['dn'] = 0;
        $a['rsmv'] = 0;
        $a['rs'] = array();
        $fs = array();
        $fs['f1']['NM'] = 'OBJ';
        $fs['f1']['TP'] = 'varchar';
        $fs['f1']['SL'] = 1;
        $fs['f1']['CMP'] = '';
        //$fs['f2']['NM'] = 'NM2';
        //$fs['f2']['TP'] = 'varchar';
        //$fs['f3']['NM'] = 'TP';
        //$fs['f3']['TP'] = 'varchar';
        //$fs['f4']['NM'] = 'OBJ';
        //$fs['f4']['TP'] = 'varchar';
        $a['fs'] = $fs;
        $a = $this->GetData1c($a); //print_r($a['rs']);

        return $a;
    }



    function get_fs_json($ent) {
        $r = '';
        $a = $this->efs(trim($ent), 0);
        $r = json_encode($a['fs'], JSON_UNESCAPED_UNICODE);
        return $r;
    }




    function cmd_ents($hr) {
        $r = '';
        $a = $this->get_ent_list();
        $i = 0;
        $j = 0;
        $r .= '<table>';
        foreach($a['rs'] as $rk=>$rw) {
            $ar = explode('.', $rw['d']['OBJ']);
            if($ar[0]=='Справочник' || $ar[0]=='Документ') {
                $i++;
                $href = str_replace('[obj]', $rw['d']['OBJ'], $hr);
                $r .= '<tr><td>'.$i.'</td><td>'.$rw['cnt'].'</td><td><a target="_blank" href="'.$href.'">'.$rw['d']['OBJ'].'('.$rw['d']['OBJSYN'].')'.'</a></td></tr>';
            }else {
                $j++;
                $r .= '<tr><td>'.$j.'</td><td>'.$rw['cnt'].'</td><td>'.$rw['d']['OBJ'].'('.$rw['d']['OBJV'].')'.'</td></tr>';
            }
        }
        $r .= '</table>';
        return $r;
    }

    function cmd_list_sf($obj, $hradd) {
        $r = '';
        $r .= '<form method="post">';
        $r .= '<br/>FS<br/><textarea name="fsjson">'.$this->get_fs_json($obj).'</textarea>';
        $r .= '<br/><button type="submit">SELECT</button>';
        $r .= '</form>';
        $href = str_replace('[obj]', $obj, $hradd);
        $r .= "<a target=\"_blank\" href=\"".$href."\" onclick=\"return confirm('Добавить?')\">|||[+]|||</a>";

        return $r;
    }

    function fss($objs, $tg=0) {
        $r = array();
        $ar = explode(',', $objs);
        foreach ($ar as $ak=>$obj) {
            $a = $this->efs(trim($obj));
            //$fs_json = $this->get_fs_json($obj);
            //$a = $this->get_list($obj, '', $fs_json, '');
            foreach($a['fs'] as $fn=>$f) {
                $r[$obj] = $a['fs'];
            }
        }


        if($tg==1) {

            $s = '<table>';
            $s .= '<thead>';
            $s .= '<tr>';
            $s .= '<th>№С</th><th>Сущность 1с</th><th>№П</th><th>Реквизит(Поле)</th><th>Тип</th>';
            $s .= '</tr>';
            $s .= '</thead>';
            $s .= '<tbody>';
            $i = 0;
            foreach ($r as $ent=>$fld) {
                $i++;
                $j=0;
                foreach ($fld as $fn=>$a) {
                    $j++;
                    $s .= '<tr><td>'.$i.'</td><td>'.$ent.'</td><td>'.$j.'</td><td>'.$a['NM'].'</td><td>'.$a['TP'].'</td></tr>';
                }
            }
            $s .= '</tbody>';
            $s .= '</table>';
            $r = $s;
        }

        return $r;
    }

    function cmd_list($obj, $fsjson, $hrupd) {
        $r = '';
        $fs = json_decode($_POST['fsjson'], true);
        //print_r($fs);
        $a = $this->lst($obj, $fs, '');
        $r .= '<table>';
        $r .= '<tr><th>'.count($a['rs']).'</th>';
        foreach($a['fs'] as $fn=>$f) {
            $r .= '<th>'.$f['NM'].'</th>';
        }
        $r .= '</tr>';
        $i = 0;
        foreach($a['rs'] as $rk=>$rw) {
            $i++;
            $href = $hrupd;
            $href = str_replace('[obj]', $obj, $href);
            $href = str_replace('[uv]', $rw['d']['Ссылка'], $href);
            $r .= '<tr><th><a target="_blank" href="'.$href.'">|||['.$i.']|||</a></th>';
            foreach($a['fs'] as $fn=>$f) {
                if(isset($rw['d'][$f['NM']]))
                    $r .= '<td>'.$rw['d'][$f['NM']].'</td>';
            }
            $r .= '</tr>';
        }
        $r .= '</table>';
        return $r;
    }

    function cmd_rw_form($fs, $rw, $uv) {
        $r = '';
        //print_r($rw);
        $r .= '<form method="post">';
        $r .= '<table>';
        foreach($fs as $fn=>$f) {
            if($f['TP']=='Число'
                ||$f['TP']=='Строка'
                ||$f['TP']=='varchar'
                ||$f['TP']=='Дата'
            ) {
                if($f['NM']!='Код') {
                    if($uv=='') {
                        $v = '';
                        if($f['TP']=='Дата') {
                            $v = date('Y-m-d h:i:s');
                        }
                    } else {
                        $v = $rw['d'][$f['NM']];
                    }
                    $r .= '<tr><td>'.$f['NM'].'</td><td><input name="'.$fn.'" type="text" value="'.$v.'"/><td></tr>';
                }
            }
        }
        $r .= '</table>';
        $r .= "<button name=\"sv\" type=\"submit\" onclick=\"return confirm('Сохранить?')\">Сохранить</button>";
        $r .= '</form>';

        //print_r($rw['it']);

        foreach($rw['it'] as $irk => $irw) {
            $r .= '<table>';
            $r .= '<thead>';
            $r .= '<tr>';
            $r .= '<th>';
            $r .= $irk.' '.$irw['nm'];
            $r .= '</th>';
            foreach($irw['fs'] as $ifn=>$if) {
                $r .= '<th>';
                $r .= $if['NM'];
                $r .= '</th>';
            }
            $r .= '</tr>';
            $r .= '</thead>';
            $r .= '<tbody>';
            $i = 0;
            foreach($irw['rs'] as $rk=>$rw) {
                $r .= '<tr>';
                $r .= '<td>';
                $i++;
                $r .= $i;
                $r .= '</td>';
                foreach($irw['fs'] as $ifn=>$if) {
                    $r .= '<td>';
                    $r .= $rw['d'][$if['NM']];
                    $r .= '</td>';
                }
                $r .= '</tr>';
            }
            $r .= '</tbody>';
            $r .= '</table>';
        }
        return $r;
    }

    function cmd_sv_post($obj, $uv, $fs, $dn, $hl) {
        $r = '';
        foreach($fs as $fn=>$f) {
            if($f['TP']=='Число'
                ||$f['TP']=='Строка'
                ||$f['TP']=='varchar'
                ||$f['TP']=='Дата'
            ) {
                if(isset($_POST[$fn]) && $_POST[$fn]!='') {
                    if($f['NM']!='Код') {

                        if($f['TP']=='Дата') {
                            $v = str_replace('-', '', $_POST[$fn]);
                            $v = str_replace('T', '', $v);
                            $v = str_replace(':', '', $v);
                            $v = str_replace(' ', '', $v);
                        } else {
                            $v = $_POST[$fn];
                        }
                        $rw['d'][$f['NM']] = $v;
                    }
                }
            }
        }
        if($uv=='') {
            $a = $this->get_add($obj, $fs, $rw, $dn);
            $uv = $a['uv'];
        } else {
            print_r($rw);
            $this->get_upd($obj, $uv, $fs, $rw, $dn);
        }
        $href = $hl;
        $href = str_replace('[obj]', $obj, $href);
        $href = str_replace('[uv]', $uv, $href);
        //Header('Location: '.$href);
        return $r;
    }

    function get_it($ent) {
        $a = array();
        $a['ent'] = $ent;
        $a['cmd'] = 'it';
        $a['fs'] = array();
        $a['rs'] = array();
        $a['it'] = array();
        $a = $this->GetData($a);
        return $a;
    }


    function soap($cmd, $obj, $fsjson, $uv, $sv) {
        $r = '';

        switch($cmd) {
            case 'it';
                $a = $this->get_it($obj);
                print_r($a);
                break;
            case 'ents';
                $r .= $this->cmd_ents("?cmd=list&obj=[obj]");
                break;
            case 'list';
                if($obj!='') {
                    $r .= $this->cmd_list_sf($obj, "?cmd=add&obj=[obj]");
                    if($fsjson!='') {
                        $r .= $this->cmd_list($obj, $fsjson, "?cmd=upd&obj=[obj]&uv=[uv]");
                    }
                }
                break;
            case 'add111';
                if($obj!='') {
                    $a = $this->getFields($obj, 0);
                    $fs = $a['fs'];
                    $rw = array();
                    if($sv==1) {
                        $this->cmd_sv_post($obj, $uv, $fs, 2, "?cmd=upd&obj=[obj]&uv=[uv]");
                    }

                    $r .= $this->cmd_rw_form($fs, $rw, '');

                }
                break;
            case 'upd111';
                if($uv!='') {
                    $a = $this->getFields($obj, 0);
                    $fs = $a['fs'];
                    $rw = array();
                    if($sv==1) {
                        $this->cmd_sv_post($obj, $uv, $fs, 2, "?cmd=upd&obj=[obj]&uv=[uv]");
                    }

                    $a = $this->getData($obj, $a['fs'], $uv);
                    $rw = $a['rs']['rw1'];

                    //print_r($rw);
                    $r .= $this->cmd_rw_form($fs, $rw, $_GET['uv']);
                }
                break;
        }


        return $r;
    }
}

?>