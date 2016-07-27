<?php
ini_set( 'display_errors', true );
error_reporting(E_ALL & ~ E_NOTICE & ~ E_DEPRECATED);

include('../class/mysql_crud.php');

$usuario  = $_POST["usuario"];
$senha    = md5($_POST["senha"]);

$db = new Database();
$db->connect();
$db->sql("SELECT * FROM adm_usuarios WHERE usuario = '$usuario' AND senha = '$senha' LIMIT 1");
$res = $db->getResult();
foreach ($res as $output) {
  $userid   = $output["guid"];
  $usernome = $output["nome"];
  $nivel    = $output["nivel"];
}
$res = $db->getResult();
$res = $db->numRows();

if ($res >= 1){
    echo 1;
    if(!isset($_SESSION))
    session_start();

    $_SESSION['usuarioID']    = $userid;
    $_SESSION['nomeUsuario']  = $usernome;
    $_SESSION['email']        = $login;
    $_SESSION['nivelUsuario'] = $nivel; 
  exit;
} else {
  echo 0;
}
