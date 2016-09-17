<?php
header('content-type: application/json; charset=utf-8');

ini_set( 'display_errors', true );
error_reporting(E_ALL & ~ E_NOTICE & ~ E_DEPRECATED);

include('../class/mysql_crud.php');

$nome   = $_POST["nome"];
$preco  = $_POST["guidpedido"];

$db = new Database();
$db->connect();
$db->insert('tem_prods',array('guid_produto'=>$guid_produto,'guid_pedido'=>$guid_pedido));
$res = $db->getResult();



echo json_encode($res);
