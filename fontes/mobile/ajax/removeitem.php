<?php

include('../class/mysql_crud.php');

$guid_produto = $_POST["guidprod"];
$guid_pedido  = $_POST["guidpedido"];

$db = new Database();
$db->connect();
$db->sql("DELETE FROM lanc_listprodpedido WHERE guid_pedido = $guid_pedido AND guid_produto = $guid_produto LIMIT 1");
//$db->delete('lanc_listprodpedido','guid_pedido='$guid_pedido, 'guid_produto='$guid_produto);
$res = $db->getResult();
$db->disconnect();

$db->connect();
$db->sql("SELECT * FROM lanc_listprodpedido WHERE guid_pedido = '$guid_pedido' AND guid_produto = '$guid_produto'");
$ress = $db->getResult();
$ress = $db->numRows();

if ($ress >= 1) {
  echo 1;
} else {
  echo 0;
}
