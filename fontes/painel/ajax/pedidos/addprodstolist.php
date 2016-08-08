<?php
header('content-type: application/json; charset=utf-8');

ini_set( 'display_errors', true );
error_reporting(E_ALL & ~ E_NOTICE & ~ E_DEPRECATED);

include('../../class/mysql_crud.php');

$guid_pedido  = $_POST["pedido"];

$db = new Database();
$db->connect();
$db->sql("SELECT a.guid as guidproduto,
                 a.descricao as nomeproduto,
                 a.preco as valorproduto,
                 b.guid as guidlan,
                 b.guid_produto,
                 b.guid_pedido
                 FROM cad_produtos AS a
                 INNER JOIN lanc_listprodpedido AS b
                 ON a.guid = b.guid_produto WHERE guid_pedido = '$guid_pedido'");
$res = $db->getResult();

echo json_encode($res);
