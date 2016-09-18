<?php
include('../../class/mysql_crud.php');

$guid_produto = $_POST["guidprod"];
$guid_pedido  = $_POST["guidpedido"];

$db = new Database();
$db->connect();
$db->sql("DELETE FROM lanc_listprodpedido WHERE guid_pedido = $guid_pedido AND guid_produto = $guid_produto LIMIT 1");

echo 1;
