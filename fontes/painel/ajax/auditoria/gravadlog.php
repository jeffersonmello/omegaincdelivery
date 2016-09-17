<?php
ini_set( 'display_errors', true );
error_reporting(E_ALL & ~ E_NOTICE & ~ E_DEPRECATED);

include('../../class/mysql_crud.php');

$acao             = $_POST["acao"];
$usuario_nome     = $_POST["nomeusuario"];
$usuario_guid     = $_POST["guidusuario"];
$datahora         = date("Y-m-d H:i:s");

$db = new Database();
$db->connect();
$db->insert('log_auditoria',array('acao'=>$acao, 'guidusuario'=>$usuario_guid, 'nomeusuario'=>$usuario_nome, 'datahora'=>$datahora));

echo 1;
