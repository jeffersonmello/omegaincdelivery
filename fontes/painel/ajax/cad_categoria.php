<?php
ini_set( 'display_errors', true );
error_reporting(E_ALL & ~ E_NOTICE & ~ E_DEPRECATED);

include('../class/mysql_crud.php');

$descricao  = $_POST["descricao"];
$icone      = $_POST["icone"];
$operacao   = $_POST["operacao"];
$guid       = $_POST["guid"];
$guiddelete = $_POST["guidd"];

$db = new Database();
$db->connect();

if ($operacao == 1) {
  $db->insert('cad_categorias',array('descricao'=>$descricao,'iconecategoria'=>$icone));
  $res = $db->getResult();
} else if ($operacao == 2) {
  $db->connect();
  $db->update('cad_categorias',array('descricao'=>$descricao, 'iconecategoria'=>$icone),'guid='.$guid);
  $res = $db->getResult();
} elseif ($operacao == 3) {
  $db->connect();
  $db->delete('cad_categorias','guid='.$guiddelete);
  $res = $db->getResult();
  $return = 1;
}


echo ($return);
