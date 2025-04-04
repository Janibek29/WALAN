#!/usr/bin/php
<?php
$input = file_get_contents("php://stdin");
file_put_contents('pems.msg', $input);
echo $input;
?>
