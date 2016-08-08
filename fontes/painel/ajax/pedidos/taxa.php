<?php
include('../../class/mysql_crud.php');

$bairro = $_POST["bairro"];

$db = new Database();
$db->connect();
$db->sql("SELECT * FROM atd_bairros WHERE descricao = '$bairro' LIMIT 1");
$res = $db->getResult();
foreach ($res as $output) {
  $valortaxa = $output["taxaEntrega"];
}

echo ($valortaxa);
