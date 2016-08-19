<?php
ini_set( 'display_errors', true );
error_reporting(E_ALL & ~ E_NOTICE & ~ E_DEPRECATED);

$diretorio = "http://cdn.kingofeletro.com.br/";

   if(isset($_FILES['file']))
   {
      date_default_timezone_set("Brazil/East");

      $ext = strtolower(substr($_FILES['file']['name'],-4));
      $new_name = date("Y.m.d-H.i.s") . $ext;
      $dir = '../../../../cdn/';

      move_uploaded_file($_FILES['file']['tmp_name'], $dir.$new_name);
	  $filenew = ($new_name);
	}

echo $diretorio.$filenew;
