<?php
header('content-type: application/json; charset=utf-8');

ini_set( 'display_errors', true );
error_reporting(E_ALL & ~ E_NOTICE & ~ E_DEPRECATED);

include('../class/mysql_crud.php');

$guid_pedido  = $_POST["guidpedido"];
$taxa  = $_POST["taxa"];

$db = new Database();
$db->connect();
$db->sql("SELECT a.guid as guidproduto,
  a.preco as valorproduto,
  b.guid as guidlan,
  b.guid_produto,
  b.guid_pedido
  FROM cad_produtos AS a
  INNER JOIN lanc_listprodpedido AS b
  ON a.guid = b.guid_produto
  WHERE guid_pedido = '$guid_pedido'");
  $res = $db->getResult();
  foreach ($res as $output) {
    $valor_produt = $output["valorproduto"];
    $total  = ($total + $valor_produt);
  }


  $db->sql("SELECT a.guid AS guidproduto, a.preco AS valorproduto, b.guid AS guidlan, b.guid_produto, b.guid_pedido
    FROM temp_prods AS a
    INNER JOIN lanc_listprodpedido AS b ON a.guid = b.guid_produto
    WHERE guid_pedido ='$guid_pedido'");
    $res = $db->getResult();
    foreach ($res as $output) {
      $valor_produt = $output["valorproduto"];
      $total2  = ($total2 + $valor_produt);
    }



    $total = ($total + $taxa + $total2);
    if(!isset($_SESSION))
    session_start();
    $_SESSION['totalpedio']     = $total;

    echo ($total);
