<?php
ini_set( 'display_errors', true );
error_reporting(E_ALL & ~ E_NOTICE & ~ E_DEPRECATED);

include('../class/mysql_crud.php');

$pedido = $_POST["order"];
$token  = $_POST["token"];
$return = 0;

$db = new Database();
$db->connect();
$db->sql("SELECT * FROM lanc_pedidos WHERE guid = '$pedido' AND token = '$token' ");
$res = $db->numRows();

if ($res >= 1){
  $res = $db->getResult();
  foreach ($res as $output) {
    $status = $output["status"];
  }

  if ($status == 1){
    $return = "class/processando.html";
  } elseif ($status == 2) {
    $return = "class/producao.html";
  } elseif ($status == 3) {
    $return = "class/pronto.html";
  } elseif ($status == 4) {
    $return = "class/aguardandoretirada.html";
  } elseif ($status == 5) {
    $return = "class/saiuentrega.html";
  } elseif ($status == 6) {
    $return = "class/entregue.html";
  } elseif ($status == 7) {
    $return = "class/clientnestava.html";
  } elseif ($status == 8) {
    $return = "class/cancelado.html";
  } elseif ($status == 9) {
    $return = "class/devolvido.html";
  }

} else {
  $return = 0;
}


echo ($return);
