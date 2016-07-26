<?php
include('../class/mysql_crud.php');

$guid_pedido        = $_POST["pedido"];

$db = new Database();
$db->connect();
$db->update('lanc_pedidos',array('status'=>'8'),'guid='.$guid_pedido);
$res = $db->getResult();

$return = 1;


echo ($return);
