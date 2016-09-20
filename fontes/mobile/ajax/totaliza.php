<?php
ini_set( 'display_errors', true );
error_reporting(E_ALL & ~ E_NOTICE & ~ E_DEPRECATED);

include('../class/mysql_crud.php');

$guid_pedido    = $_POST["guidpedido"];
$taxa           = $_POST["taxa"];
//$taxadesconto   = $_POST["taxadesconto"];

$db = new Database();
$db->connect();
$db->sql("SELECT * FROM lanc_listprodpedido WHERE guid_pedido = '$guid_pedido'");
$res = $db->getResult();
foreach ($res as $output) {
  $valor_produt = $output["valorproduto"];
  $total  = ($total + $valor_produt);
};


$total = ($total + $taxa);
//$taxadesconto = ($total * ($taxadesconto / 100));
//$total = ($total - $taxadesconto);
if(!isset($_SESSION))
session_start();
$_SESSION['totalpedio']     = $total;

echo ($total);
