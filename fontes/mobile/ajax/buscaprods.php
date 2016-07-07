<?php
header('content-type: application/json; charset=utf-8');

ini_set( 'display_errors', true );
error_reporting(E_ALL & ~ E_NOTICE & ~ E_DEPRECATED);

include('../class/mysql_crud.php');

$busca = $_POST["busca"];

$db = new Database();
$db->connect();
$db->sql("SELECT * FROM cad_produtos WHERE descricao LIKE '%$busca%' ");
$res = $db->getResult();

echo json_encode($res);
