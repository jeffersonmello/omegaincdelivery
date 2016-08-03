<?php
ini_set( 'display_errors', true );
error_reporting(E_ALL & ~ E_NOTICE & ~ E_DEPRECATED);

include('../../class/mysql_crud.php');

$guid             = $_POST["guid"];
$guidupd          = $_POST["guidupdate"];
$usuario          = $_POST["usuario"];
$senha            = md5($_POST["senha"]);
$nome             = $_POST["nome"];
$nivel            = $_POST["nivel"];
$operacao         = $_POST["operacao"];

$db = new Database();
$db->connect();

if ($operacao == 1) {
  $db->sql("SELECT * FROM adm_usuarios WHERE usuario = '$usuario'");
  $ress = $db->getResult();
  $ress = $db->numRows();

  if ($ress >= 1){
    $return = 4;
  } else {
    $db->insert('adm_usuarios',array('usuario'=>$usuario, 'senha'=>$senha, 'nome'=>$nome, 'nivel'=>$nivel));
    $res = $db->getResult();
  }

} else if ($operacao == 2) {
  $db->connect();
  $db->update('adm_usuarios',array('usuario'=>$usuario, 'senha'=>$senha, 'nome'=>$nome, 'nivel'=>$nivel),'guid='.$guidupd);
  $res = $db->getResult();
} elseif ($operacao == 3) {
  $db->connect();
  $db->delete('adm_usuarios','guid='.$guid);
  $res = $db->getResult();
  $return = 1;
}


echo ($return);
