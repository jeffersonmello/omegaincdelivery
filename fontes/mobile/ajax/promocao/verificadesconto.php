<?php
ini_set( 'display_errors', true );
error_reporting(E_ALL & ~ E_NOTICE & ~ E_DEPRECATED);

include('../../class/mysql_crud.php');

$db = new Database();
$db->connect();
$db->sql("SELECT * FROM tab_desconto WHERE ativo = 1 LIMIT 1");
$res = $db->numRows();

if ($res >= 1){
  echo 1;
} else {
  echo 0;
}
