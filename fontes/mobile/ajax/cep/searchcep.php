<?php
header('content-type: application/json; charset=utf-8');

ini_set( 'display_errors', true );
error_reporting(E_ALL & ~ E_NOTICE & ~ E_DEPRECATED);

include('../../class/mysql_crud.php');

$endereco = $_POST["rua"];


$db = new Database();
$db->connect();
$db->sql("SELECT CONCAT(logradouro, ' ', rua) as rua,
                  cep,
                  bairro
                  FROM campomouraodb WHERE rua LIKE '%$endereco%' OR logradouro LIKE '%$endereco%' LIMIT 10");
$res = $db->getResult();


echo json_encode($res);
