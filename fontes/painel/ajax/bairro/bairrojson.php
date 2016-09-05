<?php
header('content-type: application/json; charset=utf-8');

ini_set( 'display_errors', true );
error_reporting(E_ALL & ~ E_NOTICE & ~ E_DEPRECATED);

include('../../class/mysql_crud.php');


$db = new Database();
$db->connect();
$db->sql("SELECT * FROM atd_bairros");
$res = $db->getResult();

echo json_encode($res);
