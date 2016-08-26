<?php
include('../../class/mysql_crud.php');


$db = new Database();
$db->connect();
$db->sql("SELECT * FROM lanc_pedidos WHERE status='1' and notificado='0'");
$res = $db->numRows();
if ($res >= 1){
  $res = $db->getResult();
  foreach ($res as $output) {
    $pedido = $output["guid"];
  }

  $db->update('lanc_pedidos', array('notificado'=>1), 'guid='.$pedido);
  $res = $db->getResult();
} else {
   $pedido = 0;
}

echo ($pedido);
