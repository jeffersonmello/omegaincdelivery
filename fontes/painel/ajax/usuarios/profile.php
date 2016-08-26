<?php
ini_set( 'display_errors', true );
error_reporting(E_ALL & ~ E_NOTICE & ~ E_DEPRECATED);

include('../../class/mysql_crud.php');

$guidupd          = $_POST["guidupdate"];
$nome             = $_POST["nome"];
$imagem           = $_POST["imagem"];

$db = new Database();
$db->connect();
$db->update('adm_usuarios',array('nome'=>$nome, 'imagem'=>$imagem),'guid='.$guidupd);
$res = $db->getResult();

echo ($return);
