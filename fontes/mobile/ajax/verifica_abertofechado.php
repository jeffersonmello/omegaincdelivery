<?php
header('content-type: application/json; charset=utf-8');
include('../class/mysql_crud.php');


$db = new Database();
$db->connect();
$db->sql("SELECT * FROM adm_empresa WHERE padrao = 1");
$res = $db->getResult();
foreach ($res as $output) {
  $empresa_aberto      = $output["aberto"];
}
$res = $db->getResult();

if ($empresa_aberto == 1) {
      echo 1;
		exit;
} else
{
  echo 0;
}
