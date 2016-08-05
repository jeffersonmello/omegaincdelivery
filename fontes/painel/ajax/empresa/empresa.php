<?php
ini_set( 'display_errors', true );
error_reporting(E_ALL & ~ E_NOTICE & ~ E_DEPRECATED);

include('../../class/mysql_crud.php');

$guid             = $_POST["guid"];
$guidupd          = $_POST["guidupdate"];
$nome             = $_POST["nome"];
$cnpj             = $_POST["cnpj"];
$insc             = $_POST["insc"];
$telefone         = $_POST["telefone"];
$email            = $_POST["email"];
$endereco         = $_POST["endereco"];
$padrao           = $_POST["padrao"];
$operacao         = $_POST["operacao"];
$return           = 0;


$db = new Database();
$db->connect();

if ($operacao == 1) {

  if ($padrao == 1) {
  $db->sql("SELECT * FROM adm_empresa WHERE padrao = 1");
  $ress = $db->getResult();
  $ress = $db->numRows();

  if ($ress >= 1){
    $return = 4;
  }

} else {
    $db->insert('adm_empresa',array('nome'=>$nome, 'cnpj'=>$cnpj, 'inscricaoestd'=>$insc, 'telefone'=>$telefone, 'email'=>$telefone, 'endereco'=>$endereco, 'padrao'=>$padrao));
    $res = $db->getResult();
  }

} else if ($operacao == 2) {
  if ($padrao == 1) {
  $db->sql("SELECT * FROM adm_empresa WHERE padrao = 1");
  $ress = $db->getResult();
  $ress = $db->numRows();

  if ($ress >= 1){
    $return = 4;
  }

} else {
  $db->connect();
  $db->update('adm_empresa',array('usuario'=>$usuario, 'senha'=>$senha, 'nome'=>$nome, 'nivel'=>$nivel),'guid='.$guidupd);
  $res = $db->getResult();
}
} elseif ($operacao == 3) {
  $db->connect();
  $db->delete('adm_empresa','guid='.$guid);
  $res = $db->getResult();
  $return = 1;
}


echo ($return);
