<?php
ini_set( 'display_errors', true );
error_reporting(E_ALL & ~ E_NOTICE & ~ E_DEPRECATED);

include('../../class/mysql_crud.php');

$status           = $_POST["categoria"];
$guid             = $_POST["guidupdate"];
$operacao         = $_POST["operacao"];

$db = new Database();
$db->connect();

if ($operacao == 2) {
  $db->connect();
  $db->update('lanc_pedidos',array('status'=>$status),'guid='.$guid);
  $res = $db->getResult();
} elseif ($operacao == 3) {
  $db->connect();
  $db->delete('lanc_pedidos','guid='.$guid);
  $res = $db->getResult();
  $return = 1;
}


echo ($return);
