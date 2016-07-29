<script>
$(document).ready(function(){
  $('#pedidosAbertos').DataTable({
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

echo '<table id="pedidosAbertos" class="table table-hover table-bordered">'
,'<thead class="thead-default">'
,'<tr>'
,'<th width="15px" >#</th>'
,'<th>Nome</th>'
,'<th>Telefone</th>'
,'<th>Número</th>'
,'<th>Total</th>'
,'<th>Data</th>'
,'<th>Status</th>'
,'<th width="50px">Ações</th>'
,'</tr>'
,'</thead>'
,'<tbody>';

$db->connect();
$db->sql("SELECT * FROM lanc_pedidos WHERE status='1'");
$res = $db->getResult();
foreach ($res as $output) {
  $guid           = $output["guid"];
  $nome           = $output["nome"];
  $telefone       = $output["telefone"];
  $endereco       = $output["endereco"];
  $numero         = $output["numero"];
  $total          = $output["total"];
  $data           = $output["data"];

  setlocale(LC_MONETARY,"pt_BR", "ptb");
  $total = money_format('%n', $total);

  echo "<tr>";
  echo "<td width='15px'>$guid</td>";
  echo "<td>$nome</td>";
  echo "<td>$telefone</td>";
  echo "<td>$numero</td>";
  echo "<td>$total</td>";
  echo "<td>$data</td>";
  echo "<td>Processando</td>";
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
