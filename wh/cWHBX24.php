<?php
/*
	* Copyright (C) 2025 Жанибек Какимбаев
	*
	* Бұл бағдарлама GNU General Public License v3.0 (GPLv3) лицензиясы бойынша таратылады.
	* Лицензия мәтіні: https://www.gnu.org/licenses/gpl-3.0.html
	*/
class cWHBX24 implements iProviderInterface {
    var $url;

    function __construct($url) {
        $this->url = $url;
    }

    public function GetPost($get, $post) {

    }

    public function efs($ent) {
        $r = array();
        $r = $this->executeREST($ent.'.fields', []);
        /*
        if(isset($data['result'])) { //$data['error']
          //$r = $data['result'];
          foreach($data['result'] as $fn=>$f) {
            $r[$fn]['NM'] = $fn;
            $r[$fn]['TP'] = $f['type'];
            $r[$fn]['TT'] = $f['title'];
            $r[$fn]['SL'] = 1;
            $r[$fn]['CMP'] = '';
          }
        } else {
            $r = false;
        }
        */
        return $r;
    }

    public function lst($ent, $fs, $sl) {
        $filter = array();
        $select = '';
        /*
        foreach($fs as $fn=>$f) {

          if($f['CMP']!='') {
            $filter[$f['CMP'].$fn] = '';
          }

          if($f['SL']!='') {
            if($select!='')
              $select .= ',';
            $select .= $f['NM'];
          }
        }
        */
        $data = $this->executeREST($ent.'.list', ['filter' => $fs, 'select'=>$sl]);
        return $data;
    }

    public function upd($ent, $uv, $fs, $rw, $dn) {
        $data = $this->executeREST($ent.'.update', ['ID'=>$uv, 'fields' => $fs]);
        return $data;
    }

    public function add($ent, $fs, $rw, $dn) {
        $data = $this->executeREST($ent.'.add', ['fields' => $fs]);
        return $data;
    }

    public function del($uv) {

    }

    function executeREST($method, $params) {
        //executeREST('task.checklistitem.add', array($task['result'], array('TITLE' => 'Оставить Петровича в покое до понедельника')));

        $queryUrl = $this->url.$method.'.json';
        $queryData = http_build_query($params);

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_HEADER => 0,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $queryUrl,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $queryData,
        ));

        $result = curl_exec($curl);
        curl_close($curl);
        //print_r($result);
        //echo $queryUrl;
        return json_decode($result, true);

    }
}
?>