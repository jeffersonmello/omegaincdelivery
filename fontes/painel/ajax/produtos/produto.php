<?php
ini_set( 'display_errors', true );
error_reporting(E_ALL & ~ E_NOTICE & ~ E_DEPRECATED);

include('../../class/mysql_crud.php');

$guid_categoria   = $_POST["categoria"];
$descricao        = $_POST["descricao"];
$subdescricao     = $_POST["subdesc"];
$imagem           = $_POST["imagem"];
$preco            = $_POST["preco"];
$guid             = $_POST["guid"];
$operacao         = $_POST["operacao"];
$indisponivel     = $_POST["indisponivel"];

$db = new Database();
$db->connect();

if ($operacao == 1) {
  $db->insert('cad_produtos',array('guid_categoria'=>$guid_categoria, 'descricao'=>$descricao,'subdescricao'=>$subdescricao, 'imgproduto'=>$imagem, 'preco'=>$preco, 'indisponivel'=>$indisponivel));
  $res = $db->getResult();
} else if ($operacao == 2) {
  $db->connect();
  $db->update('cad_produtos',array('guid_categoria'=>$guid_categoria, 'descricao'=>$descricao,'subdescricao'=>$subdescricao, 'imgproduto'=>$imagem, 'preco'=>$preco, 'indisponivel'=>$indisponivel),'guid='.$guid);
  $res = $db->getResult();
} elseif ($operacao == 3) {
  $db->connect();
  $db->delete('cad_produtos','guid='.$guid);
  $res = $db->getResult();
  $return = 1;
}


echo ($return);
