<?php
ini_set( 'display_errors', true );
error_reporting(E_ALL & ~ E_NOTICE & ~ E_DEPRECATED);

include('../class/mysql_crud.php');

$guid_produto = $_POST["guidprod"];
$guid_pedido  = $_POST["guidpedido"];
$preco  = $_POST["preco"];

$db = new Database();
$db->connect();
$db->insert('lanc_listprodpedido',array('guid_produto'=>$guid_produto,'guid_pedido'=>$guid_pedido, 'valorproduto'=>$preco));
$res = $db->getResult();


$return = 1;

echo ($return);
