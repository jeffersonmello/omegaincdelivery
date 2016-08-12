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
$data               = $_POST["hoje"];
$cpf                = $_POST["cpf"];
$telefone           = $_POST["telefone"];
$total              = $_POST["total"];
$token              = rand(111111111, 999999999);

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
$db->update('lanc_pedidos',array('token'=>$token, 'nome'=>$cliente_nome,'email'=>$cliente_email,'endereco'=>$endereco,'entregar'=>$entregar,'formaPagamento'=>$pagamento,'numero'=>$numero_residencia,'observacao'=>$observacao,'status'=>'1','data'=>$data, 'cpf'=>$cpf, 'telefone'=>$telefone, 'total'=>$total),'guid='.$guid_pedido);
$res = $db->getResult();

$arquivo = "
<!doctype html>
<html>
<head>
<meta name='viewport' content='width=device-width'>
<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
<title>Delivery</title>
<style>
/* -------------------------------------
GLOBAL
------------------------------------- */
* {
  font-family: Roboto, Noto, Helvetica, Arial, sans-serif;
  font-size: 100%;
  line-height: 1.6em;
  margin: 0;
  padding: 0;
}

img {
  max-width: 600px;
  width: 100%;
}

body {
  -webkit-font-smoothing: antialiased;
  height: 100%;
  -webkit-text-size-adjust: none;
  width: 100% !important;
}


/* -------------------------------------
ELEMENTS
------------------------------------- */
a {
  color: #348eda;
}

.btn-primary {
  Margin-bottom: 10px;
  width: auto !important;
}

.btn-primary td {
  background-color: #348eda;
  border-radius: 25px;
  font-family: 'Helvetica Neue', Helvetica, Arial, 'Lucida Grande', sans-serif;
  font-size: 14px;
  text-align: center;
  vertical-align: top;
}

.btn-primary td a {
  background-color: #348eda;
  border: solid 1px #348eda;
  border-radius: 25px;
  border-width: 10px 20px;
  display: inline-block;
  color: #ffffff;
  cursor: pointer;
  font-weight: bold;
  line-height: 2;
  text-decoration: none;
}

.last {
  margin-bottom: 0;
}

.first {
  margin-top: 0;
}

.padding {
  padding: 10px 0;
}


/* -------------------------------------
BODY
------------------------------------- */
table.body-wrap {
  padding: 20px;
  width: 100%;
}

table.body-wrap .container {
  border: 1px solid #f0f0f0;
}


/* -------------------------------------
FOOTER
------------------------------------- */
table.footer-wrap {
  clear: both !important;
  width: 100%;
}

.footer-wrap .container p {
  color: #666666;
  font-size: 12px;

}

table.footer-wrap a {
  color: #999999;
}


/* -------------------------------------
TYPOGRAPHY
------------------------------------- */
h1,
h2,
h3 {
  color: #111111;
  font-family: 'Helvetica Neue', Helvetica, Arial, 'Lucida Grande', sans-serif;
  font-weight: 200;
  line-height: 1.2em;
  margin: 40px 0 10px;
}

h1 {
  font-size: 36px;
}
h2 {
  font-size: 28px;
}
h3 {
  font-size: 22px;
}

p,
ul,
ol {
  font-size: 14px;
  font-weight: normal;
  margin-bottom: 10px;
}

ul li,
ol li {
  margin-left: 5px;
  list-style-position: inside;
}

/* ---------------------------------------------------
RESPONSIVENESS
------------------------------------------------------ */

/* Set a max-width, and make it display as block so it will automatically stretch to that width, but will also shrink down on a phone or something */
.container {
  clear: both !important;
  display: block !important;
  Margin: 0 auto !important;
  max-width: 600px !important;
}

/* Set the padding on the td rather than the div for Outlook compatibility */
.body-wrap .container {
  padding: 20px;
}

/* This should also be a block element, so that it will fill 100% of the .container */
.content {
  display: block;
  margin: 0 auto;
  max-width: 600px;
}

/* Let's make sure tables in the content area are 100% wide */
.content table {
  width: 100%;
}

</style>
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
<h1>Pedido Realizado no Aplicativo Delivery</h1><br>
<p><b>Olá recebemos o seu pedido !</b></p>
<p>Seu pedido realizado através do aplicativo foi recebido<br>
O Estado atual do seu pedido é: <b>Processando</b><br>
Número do seu Pedido: $guid_pedido <br>
Token do Pedido: $token
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
<p><a href='http://pristonspnbr.tk/'>www.delivery.com</a></p>
</td>
</tr>
</table>
</div>
<!-- /footer -->

</body>
</html>
";


// emails para quem será enviado o formulário
$emailenviar = $cliente_email;
$destino = $emailenviar;
$assunto = "Pedido Através do Aplicativo";

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


$return = 1;


echo ($return);
