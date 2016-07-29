<script>
$(document).ready(function(){
  $('#categorias').DataTable({
    "language": {
      "sEmptyTable": "Nenhum registro encontrado",
      "sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
      "sInfoEmpty": "Mostrando 0 até 0 de 0 registros",
      "sInfoFiltered": "(Filtrados de _MAX_ registros)",
      "sInfoPostFix": "",
      "sInfoThousands": ".",
      "sLengthMenu": "_MENU_ resultados por página",
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
    "bLengthChange": false,
    'sDom': '<"top">rt<"bottom"lp><"clear">' ,
  });
})
</script>

<?php
ini_set( 'display_errors', true );
error_reporting(E_ALL & ~ E_NOTICE & ~ E_DEPRECATED);

include('../../class/mysql_crud.php');

$db = new Database();

echo '<table id="categorias" class="table table-hover table-bordered">'
,'<thead class="thead-default">'
,'<tr>'
,'<th width="15px" >#</th>'
,'<th>Descrição</th>'
,'<th width="24px">Icone</th>'
,'<th>Ações</th>'
,'</tr>'
,'</thead>'
,'<tbody>';

$db->connect();
$db->select('cad_categorias');
$res = $db->getResult();
foreach ($res as $output) {
  $guid = $output["guid"];
  $descricao = $output["descricao"];
  $icone 	= $output["iconecategoria"];

  echo "<tr>";
  echo "<td width='15px'>$guid</td>";
  echo "<td>$descricao</td>";
  echo "<td width='24px'>$icone</td>";
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
