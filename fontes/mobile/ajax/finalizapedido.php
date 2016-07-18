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

$arquivo = "
   <style type='text/css'>
   body {
   margin:0px;
   font-family:Verdane;
   font-size:12px;
   color: #666666;
   }
   a{
   color: #666666;
   text-decoration: none;
   }
   a:hover {
   color: #FF0000;
   text-decoration: none;
   }
   </style>
   <html>
       <p>
       O seu pedido através do aplicativo delivery foi efetuado com sucesso, o estado atual do seu pedido é :<br>
       Em processamento.
       <br>
       <br>
       Obrigado por utilizar o aplicativo.
       </p>
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
