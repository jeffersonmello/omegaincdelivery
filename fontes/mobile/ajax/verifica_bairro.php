<?php
header('content-type: application/json; charset=utf-8');
include('../class/mysql_crud.php');

$bairro = $_POST["br"];
$nome   = $_POST["nome"];
$email  = $_POST["email"];

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
      if(!isset($_SESSION))
		  session_start();
	  	$_SESSION['idBairro']     = $guidbairro;
	  	$_SESSION['taxaentrega']  = $valorentrega;
      $_SESSION['nomecliente']  = $nome;
      $_SESSION['emailcliente'] = $email;

      $db->connect();
      $db->insert('lanc_pedidos',array('nome'=>$nome,'email'=>$email, 'bairro'=>$bairro));
      $res = $db->getResult();
      foreach ($res as $output) {
          $guid_pedido = $output["guid"];
      }
      $_SESSION['idPedido']   = $guid_pedido;
		exit;
} else
{
  echo 0;
}
