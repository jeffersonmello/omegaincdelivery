<script>
$(document).ready(function(){
  $('#produtos').DataTable({
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

include('../class/mysql_crud.php');

$db = new Database();

echo '<table id="categorias" class="table table-hover table-bordered">'
,'<thead class="thead-default">'
,'<tr>'
,'<th width="15px" >#</th>'
,'<th>Categoria</th>'
,'<th>Descrição</th>'
,'<th>Subdescrição</th>'
,'<th>Imagem</th>'
,'<th width="50px">Ações</th>'
,'</tr>'
,'</thead>'
,'<tbody>';

$db->connect();
$db->select('cad_produtos');
$res = $db->getResult();
foreach ($res as $output) {
  $guid           = $output["guid"];
  $guidcategoria  = $output["guid_categoria"];
  $imagem         = $output["imgproduto"];
  $descricao      = $output["descricao"];
  $subdescricao   = $output["subdescricao"];

  $db->sql("SELECT * FROM cad_categorias WHERE guid = $guidcategoria LIMIT 1");
  $res = $db->getResult();
  foreach ($res as $output) {
    $categoria = $output["descricao"];
  }

  echo "<tr>";
  echo "<td width='15px'>$guid</td>";
  echo "<td>$categoria</td>";
  echo "<td>$descricao</td>";
  echo "<td>$subdescricao</td>";
  echo "<td>$imagem</td>";
  echo "<td width='50px'>
  <button type='button' onclick='openModal(\"editar\",". $guid .")' class='btn btn-secondary btn-sm'><i class='material-icons'>mode_edit</i></button>
  <button type='button' onclick='salvar(3,". $guid .")'class='btn btn-secondary btn-sm'><i class='material-icons'>delete</i></button>
  </td>";
  echo "</tr>";
}

echo '</tbody>
</table>
</div>
</div>
';

?>
