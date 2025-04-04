<?php
	/*
	* Copyright (C) 2025 Жанибек Какимбаев
	*
	* Бұл бағдарлама GNU General Public License v3.0 (GPLv3) лицензиясы бойынша таратылады.
	* Лицензия мәтіні: https://www.gnu.org/licenses/gpl-3.0.html
	*/
require_once('iProviderInterface.php');
require_once('cSOAP1C.php');

$a = array();
$a['host'] = 'tirlik.kz';
$a['link'] = '/ugs/sivital/WebHook.php?tp=1c';
$txt = json_encode($a);
file_put_contents('d1c.json', $txt);
/*
$url = 'https://1cdb.sivital.kz/Sivital2022/ws/wsj.1cws?wsdl';
$un = 'bitrix24';
$pw = 'Vi420Siv$r';
$provider1c = new cSOAP1C($url, $un, $pw);
$fs = array();
$fs['f1']['NM'] = 'Наименование';
$fs['f1']['TP'] = 'Строка';
$fs['f1']['SL'] = 1;
$fs['f1']['CMD'] = '';
$fs['f2']['NM'] = 'ЮрФизЛицо';
$fs['f2']['TP'] = 'Перечисление.ЮрФизЛицо';
$fs['f2']['SL'] = 1;
$fs['f2']['CMD'] = '';

$rw['d']['Наименование'] = 'test';
$rw['d']['ЮрФизЛицо'] = 'ФизЛицо';
$a = $provider1c->add('Справочник.Контрагенты', $fs, $rw, 0);
print_r($a);
*/
?>