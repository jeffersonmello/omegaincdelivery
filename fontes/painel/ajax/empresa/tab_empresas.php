<script>
$(document).ready(function(){
  $('#empresas').DataTable({
    "language": {
      "sEmptyTable": "Nenhum registro encontrado",
      "sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
      "sInfoEmpty": "Mostrando 0 até 0 de 0 registros",
      "sInfoFiltered": "(Filtrados de _MAX_ registros)",
      "sInfoPostFix": "",
      "sInfoThousands": ".",
      "sLengthMenu": "_MENU_ resultados/página",
      "sLoadingRecords": "Carregando...",
      "sProcessing": "Processando...",
      "sZeroRecords": "Nenhum registro encontrado",
      "sSearch": "Pesquisar",
      "oPaginate": {
        "sNext": "Próximo",
        "sPrevious": "Anterior",
        "sFirst": "Primeiro",
        "sLast": "Último"
      },
      "oAria": {
        "sSortAscending": ": Ordenar colunas de forma ascendente",
        "sSortDescending": ": Ordenar colunas de forma descendente"
      }
    },
     "iDisplayLength": 5,
    'sDom': '<"top"f>rt<"bottom"p>i' ,
  });
})
</script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap4.min.css"/>

<?php
ini_set( 'display_errors', true );
error_reporting(E_ALL & ~ E_NOTICE & ~ E_DEPRECATED);

include('../../class/mysql_crud.php');

$db = new Database();

echo '<table id="empresas" class="table table-hover table-bordered">'
,'<thead class="thead-default">'
,'<tr>'
,'<th width="15px" >#</th>'
,'<th>Nome</th>'
,'<th>CNPJ</th>'
,'<th>Telefone</th>'
,'<th>E-mail</th>'
,'<th>Padrão</th>'
,'<th width="50px">Ações</th>'
,'</tr>'
,'</thead>'
,'<tbody>';

$db->connect();
$db->sql("SELECT * FROM adm_empresa");
$res = $db->getResult();
foreach ($res as $output) {
  $guid           = $output["guid"];
  $nome           = $output["nome"];
  $cnpj           = $output["cnpj"];
  $telefone       = $output["telefone"];
  $email          = $output["email"];
  $padrao         = $output["padrao"];

  echo "<tr>";
  echo "<td width='15px'>$guid</td>";
  echo "<td>$nome</td>";
  echo "<td>$cnpj</td>";
  echo "<td>$telefone</td>";
  echo "<td>$email</td>";
  echo "<td>$padrao</td>";
  echo "<td width='65px'>
  <button type='button' onclick='openModal(\"editar\",". $guid .")' class='btn btn-secondary btn-xs'><i class='material-icons'>mode_edit</i></button>
  <button type='button' onclick='salvar(3,". $guid .")'class='btn btn-secondary btn-xs'><i class='material-icons'>delete</i></button>
  </td>";
  echo "</tr>";
}

echo '</tbody>
</table>
</div>
</div>
';

?>
