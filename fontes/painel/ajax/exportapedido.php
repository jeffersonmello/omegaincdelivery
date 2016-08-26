<?php
header('content-type: application/json; charset=utf-8');

ini_set( 'display_errors', true );
error_reporting(E_ALL & ~ E_NOTICE & ~ E_DEPRECATED);
setlocale(LC_MONETARY,"pt_BR", "ptb");

include('../class/mysql_crud.php');

$guid_pedido        = $_POST["pedido"];
$proxstatus         = $_POST["statusnext"];

$db = new Database();
$db->connect();
$db->sql("SELECT * FROM lanc_pedidos WHERE guid = $guid_pedido");
$res = $db->getResult();
foreach ($res as $output) {
  $nome       = $output["nome"];
  $email      = $output["email"];
  $bairro     = $output["bairro"];
  $token      = $output["token"];
  $cpf        = $output["cpf"];
  $status     = $output["status"];
  $total      = $output["total"];
  $data       = $output["data"];
  $pagamento  = $output["formaPagamento"];
}

  $comissao = ($total * (20/100));
  $comissao = money_format('%n', $comissao);
  $total = money_format('%n', $total);


if ($pagamento == 1) {
  $pagamento = "Dinheiro";
} else {
  $pagamento = "Cartao/Credito/Debito";
}

if ($satus == 3){
  $status = "Pronto";
} else if ($status == 4){
  $status = "Aguardando para busca";
} else if ($status == 5){
  $status = "Saiu para Entrega";
} else if ($status == 6){
  $status = "Entregue";
}

if ($proxstatus == 3){
  $proxstatus = "Pronto";
} else if ($proxstatus == 4){
  $proxstatus = "Aguardando para busca";
} else if ($proxstatus == 5){
  $proxstatus = "Saiu para Entrega";
} else if ($proxstatus == 6){
  $proxstatus = "Entregue";
}


$arquivo = "
<!doctype html>
<html>
<head>
<meta name='viewport' content='width=device-width'>
<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
<title>Delivery</title>
</head>

<body bgcolor='#f6f6f6'>

<!-- body -->
<table class='body-wrap' bgcolor='#f6f6f6'>
<tr>
<td></td>
<td class='container' bgcolor='#FFFFFF'>

<!-- content -->
<div class='content'>
<table>
<tr>
<td>
<h1>Dados de Pedido</h1><br>
<p>Status Anterior: <b>$status</b><br>
Status atual: $proxstatus<br>
Cliente: $nome<br>
Bairro: $bairro<br>
CPF: $cpf<br>
Data: $data<br>
Email $email<br>
Pagamenrto: $pagamento<br>
Número do seu Pedido: $guid_pedido <br>
Token do Pedido: $token <br>
Total: $total<br>
Comissao: $comissao
</p><br>
<br>


<br>


</p>
<!-- button -->
<table class='btn-primary' cellpadding='0' cellspacing='0' border='0'>
<tr>
<td>

</td>
</tr>
</table>
<!-- /button -->
<p>Delivery</p>
<p><a href='http://delivery.tk/'>www.delivery.com</a></p>
</td>
</tr>
</table>
</div>
<!-- /footer -->

</body>
</html>
";


// emails para quem será enviado o formulário
$emailenviar = "tico254@gmail.com";
$destino = $emailenviar;
$assunto = "Exportação Pedido";

// É necessário indicar que o formato do e-mail é html
$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
$headers .= 'From: delivery <delivery@delivery.com>';
//$headers .= "Bcc: $EmailPadrao\r\n";

$enviaremail = mail($destino, $assunto, $arquivo, $headers);
if($enviaremail){
  $return = 1;
} else {
  $return = 0;
}


echo json_encode($res);
