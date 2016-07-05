<?php
header('content-type: application/json; charset=utf-8');
include('../class/mysql_crud.php');

$bairro = $_POST["br"];

$db = new Database();
$db->connect();
$db->sql("SELECT * FROM atd_bairros WHERE descricao = '$bairro'");
$res = $db->getResult();
foreach ($res as $output) {
  $guidbairro   = $output["guid"];
  $valorentrega = $output["taxaEntrega"];
}
$res = $db->getResult();
$res = $db->numRows();


if ($res >= 1) {
      echo 1;
      if(!isset($_SESSION)) 	//verifica se há sessão aberta
		  session_start();		//Inicia seção
		  //Abrindo seções
	  	$_SESSION['idBairro']    = $guidbairro;
	  	$_SESSION['taxaentrega']  = $valorentrega;
		exit;
} else
{
  echo 0;
}
