<script>
$(document).ready(function(){
  $('#tabledevolvido').DataTable({
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

echo '<table id="tabledevolvido" class="table table-hover table-bordered">'
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
$db->sql("SELECT * FROM lanc_pedidos WHERE status='9'");
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

  $data = date("d-m-Y", strtotime($data));

  echo "<tr>";
  echo "<td width='15px'>$guid</td>";
  echo "<td>$nome</td>";
  echo "<td>$telefone</td>";
  echo "<td>$numero</td>";
  echo "<td>$total</td>";
  echo "<td>$data</td>";
  echo "<td>Devolvido</td>";
  echo "<td width='65px'>
  <button type='button' onclick='openModal(\"editar\",". $guid .")' class='btn btn-secondary btn-xs'><i class='material-icons'>remove_red_eye</i></button>
  <button type='button' onclick='salvar(3,". $guid .")' class='btn btn-secondary btn-xs'><i class='material-icons'>delete</i></button>
  <button type='button' onclick='inprimir(". $guid .")' target='_blank' class='btn btn-secondary btn-xs'><i class='material-icons'>print</i></button>
  </td>";
  echo "</tr>";
}

echo '</tbody>
</table>
</div>
</div>
';

?>
