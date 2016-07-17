<?php
header('content-type: application/json; charset=utf-8');

ini_set( 'display_errors', true );
error_reporting(E_ALL & ~ E_NOTICE & ~ E_DEPRECATED);

include('../class/mysql_crud.php');

$guid_pedido        = $_POST["pedido"];
$cliente_nome       = $_POST["nome"];
$cliente_email      = $_POST["email"];
$endereco           = $_POST["endereco"];
$numero_residencia  = $_POST["numero"];
$pagamento          = $_POST["formapagamento"];
$entregar           = $_POST["retirarloja"];
$observacao         = $_POST["observacao"];

if ($pagamento == 'Dinheiro') {
  $pagamento = 0;
} else {
  $pagamento = 1;
}

if ($entregar == 'Não, Entregar'){
  $entregar = 1;
} else {
  $entregar = 0;
}


$db = new Database();
$db->connect();
$db->update('lanc_pedidos',array('nome'=>$cliente_nome,'email'=>$cliente_email,'endereco'=>$endereco,'entregar'=>$entregar,'formaPagamento'=>$pagamento,'numero'=>$numero_residencia,'observacao'=>$observacao,'status'=>'2'),'guid='.$guid_pedido);
$res = $db->getResult();


echo ($return);
