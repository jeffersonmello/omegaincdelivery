<?php
ini_set( 'display_errors', true );
error_reporting(E_ALL & ~ E_NOTICE & ~ E_DEPRECATED);

include('../../class/mysql_crud.php');

$operacao = $_POST["operacao"];

$db = new Database();
$db->connect();

if ($operacao == 0)  {
  $db->update('adm_empresa',array('aberto'=>0),'padrao = 1');
  $res = $db->getResult();
} elseif ($operacao == 1) {
  $db->update('adm_empresa',array('aberto'=>1),'padrao = 1');
  $res = $db->getResult();
}

echo ($return);
