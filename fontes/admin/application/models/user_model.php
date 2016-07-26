<?php
Class User_model extends CI_Model
{
 function login($usuario, $senha)
 {
   $this -> db -> select('guid, usuario, senha');
   $this -> db -> from('adm_usuarios');
   $this -> db -> where('usuario', $usuario);
   $this -> db -> where('senha', MD5($senha));
   $this -> db -> limit(1);

   $query = $this -> db -> get();

   if($query -> num_rows() == 1)
   {
     return $query->result();
   }
   else
   {
     return false;
   }
 }
}
?>
