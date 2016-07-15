<?php
header('content-type: application/json; charset=utf-8');

ini_set( 'display_errors', true );
error_reporting(E_ALL & ~ E_NOTICE & ~ E_DEPRECATED);

include('../class/mysql_crud.php');

$guid_produto = $_POST["guidprod"];
$guid_pedido  = $_POST["guidpedido"];

$db = new Database();
$db->connect();
$db->insert('lanc_listprodpedido',array('guid_produto'=>$guid_produto,'guid_pedido'=>$guid_pedido));
$res = $db->getResult();

$return = 1;

echo ($return);
