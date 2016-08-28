<?php
header('content-type: application/json; charset=utf-8');

ini_set( 'display_errors', true );
error_reporting(E_ALL & ~ E_NOTICE & ~ E_DEPRECATED);


include('../../class/mysql_crud.php');

$datainicial     = $_POST["datainicial"];
$datafinal       = $_POST["datafinal"];


$db = new Database();
$db->connect();
$db->sql("SELECT * FROM lanc_pedidos WHERE data >= '$datainicial' AND data <= '$datafinal'");
$res = $db->getResult();

echo json_encode($res);
