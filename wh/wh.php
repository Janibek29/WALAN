<?php
	/*
	* Copyright (C) 2025 Жанибек Какимбаев
	*
	* Бұл бағдарлама GNU General Public License v3.0 (GPLv3) лицензиясы бойынша таратылады.
	* Лицензия мәтіні: https://www.gnu.org/licenses/gpl-3.0.html
	*/
error_reporting(E_ALL);
ini_set('display_errors','On');

require_once('cMDB.php');
require_once('cMAP.php');
require_once('cSYNC.php');

require_once('iProviderInterface.php');
require_once('cSOAP1C.php');
require_once('cWHBX24.php');


function redirect_url($url) {
    $a = explode('?',$_SERVER['REQUEST_URI']);
    $gt = '';
    if(isset($a[1]))
        $gt = $a[1];
    $curlHandle = curl_init($url.'?'.$gt);
    $post = http_build_query($_POST);
    curl_setopt($curlHandle, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curlHandle, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($curlHandle ,CURLOPT_POST, 1); //sizeof($post)
    curl_setopt($curlHandle, CURLOPT_POSTFIELDS, $post);
    curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);

    $curlResponse = curl_exec($curlHandle);
    curl_close($curlHandle);
}

function getpost() {
    $r = '';
    $r .= print_r($_GET, true);
    $r .= "\r\n";
    $r .= print_r($_POST, true);

    file_put_contents('wh.txt', $r);
}

getpost();
$tp = '';
if(isset($_GET['tp'])) {
    $tp = $_GET['tp'];

    $oMAP = new cMAP();
    $oMAP->oMDB = new cMDB('', '', '', '');

    $oMAP->ss = false;

    $e = '';
    $k = '';
    switch($tp) {
        case '1c':
            if(isset($_POST['obj'])) {
                $e = $_POST['obj'];
                $k = $_POST['uv'];
            }
            break;
        case 'b24':
            if(isset($_POST['event'])) {
                $e = $_POST['event'];
                $k = $_POST['data']['FIELDS']['ID'];
            }

            break;
    }
    $oMAP->loadjson('map.json');

    if($tp=='1c' && !($e=='Справочник.Номенклатура'
            || $e=='Справочник.Контрагенты'
        )) {
        $tp = '';
    }

    $oMAP->wh($tp, $e, $k);
    unset($oMAP);
}
/*
//Синхронизация
if(isset($_GET['sync'])) {
    $oSYNC = new cSYNC();
    $oSYNC->loadjson('map.json');
    $oSYNC->oMDB = new cMDB('', '', '', '');
    $oSYNC->init();
    $oSYNC->sync();
    unset($oSYNC);
}
*/
//redirect_url('http://10.0.0.7/ugs/sivital/WebHookLocal.php');
//redirect_url('https://ugstools.kz/sivital/WebHook.php');
//redirect_url('https://da18-151-236-192-54.ngrok.io/sivital/webhook.php');
//redirect_url('https://d789-151-236-192-54.ngrok.io/sivital/webhook.php');


?>