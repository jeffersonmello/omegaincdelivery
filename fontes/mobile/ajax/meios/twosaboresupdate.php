<?php
ini_set( 'display_errors', true );
error_reporting(E_ALL & ~ E_NOTICE & ~ E_DEPRECATED);

setlocale(LC_MONETARY,"pt_BR", "ptb");

include('../../class/mysql_crud.php');

$guid_prod          = $_POST["guidprodtemp"];
$nome               = $_POST["nome"];
$preco              = $_POST["preco"];


$db = new Database();
$db->connect();
$db->update('temp_prods',array('descricao'=>$nome, 'preco'=>$preco),'guid='.$guid_prod);
$res = $db->getResult();

echo 1;
