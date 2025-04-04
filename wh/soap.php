<?php
	/*
	* Copyright (C) 2025 Жанибек Какимбаев
	*
	* Бұл бағдарлама GNU General Public License v3.0 (GPLv3) лицензиясы бойынша таратылады.
	* Лицензия мәтіні: https://www.gnu.org/licenses/gpl-3.0.html
	*/
error_reporting(E_ALL);
ini_set('display_errors','On');

ini_set('default_socket_timeout', 10000);
ini_set('soap.wsdl_cache_enabled', 0 );
ini_set('soap.wsdl_cache_ttl', 0);
require_once('iProviderInterface.php');
require_once('cSOAP1C.php');
$r = '';
//$url = 'https://toricompany.kz/Sivital2022/ws/wsj.1cws?wsdl';
$url = 'https://1cdb.sivital.kz/Sivital2022/ws/wsj.1cws?wsdl';
$un = 'bitrix24';
$pw = 'Vi420Siv$r';

//$url = 'http://localhost/T20220804/ws/wsj.1cws?wsdl';
//$un = '';
//$pw = '';
//http://localhost/psp/sivital/Provider1C/soap.php?cmd=ents

if(isset($_GET['cmd'])) {
    $oSOAP1C = new cSOAP1C($url, $un, $pw);
    $obj = '';
    if(isset($_GET['obj']))
        $obj = $_GET['obj'];
    $fsjson = '';
    if(isset($_POST['fsjson'])) {
        $fsjson = $_POST['fsjson'];
    }
    $uv = '';
    if(isset($_GET['uv'])) {
        $uv = $_GET['uv'];
    }
    $sv = '';
    if(isset($_POST['sv'])) {
        $sv = $_POST['sv'];
    }
    $r .= $oSOAP1C->soap($_GET['cmd'], $obj, $fsjson, $uv, $sv);
    unset($oSOAP1C);
}

if(isset($_GET['fields'])) {
    $oSOAP1C = new cSOAP1C($url, $un, $pw);
    $ents = 'Справочник.Номенклатура
    ,Документ.СчетНаОплатуПокупателю
    ,Документ.СчетФактураВыданный
    ,Документ.СчетФактураПолученный
    ,Справочник.Склады
    ,Документ.ИнвентаризацияТоваровНаСкладе
    ,Документ.ОприходованиеТоваров
    ,Документ.КомплектацияНоменклатуры
    ,Документ.ПеремещениеТоваров
    ,Документ.СписаниеТоваров
    ,Документ.РеализацияТоваровУслуг
    ,Справочник.Контрагенты';
    $r .= "<form method='post'>";
    $r .= "<textarea rows='10' cols='50' name='objs'>".$ents."</textarea>";
    $r .= "<button type='submit'>Показать</button>";
    $r .= "</form>";
    if(isset($_POST['objs']))
        $r .= $oSOAP1C->fss($_POST['objs'], 1);
    unset($oSOAP1C);
}

echo $r;
/*
$ent = 'Справочник.Номенклатура';
$oSOAP1C = new cSOAP1C($url, $un, $pw);
$a = $oSOAP1C->efs($ent);
$a = $oSOAP1C->lst($ent, $a['fs'], '8ec4fa8c-08e3-11ed-80eb-000c2992c9c5');
print_r($a);*/