<?php
header('content-type: application/json; charset=utf-8');

ini_set( 'display_errors', true );
error_reporting(E_ALL & ~ E_NOTICE & ~ E_DEPRECATED);

include('../../class/mysql_crud.php');

$nome   = $_POST["nome"];
$preco  = $_POST["preco"];


$db = new Database();
$db->connect();
$db->insert('temp_prods',array('descricao'=>$nome,'preco'=>$preco));
$res = $db->getResult();



echo json_encode($res);
