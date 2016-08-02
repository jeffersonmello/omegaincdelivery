<?php
ini_set( 'display_errors', true );
error_reporting(E_ALL & ~ E_NOTICE & ~ E_DEPRECATED);

include('../../class/mysql_crud.php');

$guid             = $_POST["guid"];
$guidupd             = $_POST["guidupdate"];
$descricao        = $_POST["descricao"];
$taxa             = $_POST["taxa"];
$operacao         = $_POST["operacao"];

$db = new Database();
$db->connect();

if ($operacao == 1) {
  $db->sql("SELECT * FROM atd_bairros WHERE descricao = '$descricao'");
  $ress = $db->getResult();
  $ress = $db->numRows();

  if ($ress >= 1){
    $return = 4;
  } else {
    $db->insert('atd_bairros',array('descricao'=>$descricao, 'taxaEntrega'=>$taxa));
    $res = $db->getResult();
  }

} else if ($operacao == 2) {
  $db->connect();
  $db->update('atd_bairros',array('descricao'=>$descricao, 'taxaEntrega'=>$taxa),'guid='.$guidupd);
  $res = $db->getResult();
} elseif ($operacao == 3) {
  $db->connect();
  $db->delete('atd_bairros','guid='.$guid);
  $res = $db->getResult();
  $return = 1;
}


echo ($return);
