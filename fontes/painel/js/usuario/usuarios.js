$(document).ready(function(){
  reloadtable();
  reloadtable();

})

function reloadtable(){
  $('#divcat').load('ajax/usuarios/tab_usuarios.php', function(){
  });
}

function openModal(operacao, guid){
  var modall 				= $('#modal');
  var titulomodal		= $("#titulomodal");
  var campoguid			= $("#campoguid");
  var botaoeditar		= $("#botaoatualizar");
  var botaosalvar		= $("#botaosalvar");

  var inputguid			= $("#guid");
  var campobairro		= $("#descricao");
  var campotaxa			= $("#preco");


  if (operacao == "Novo"){
    titulomodal.html("Cadastro de Bairro");
    campoguid.hide();
    botaosalvar.show();
    botaoeditar.hide();
    $('#formBairro')[0].reset();
    modall.modal('show');
  } else
  if (operacao == "editar"){
    $.ajax({
      url:"ajax/bairro/populate_bairro.php",
      type:"POST",
      data:"guid="+guid,
      success: function (dados){
        $.each(dados, function(index){
          var guidbairro				= dados[index].guid;
          var descricaobairro		= dados[index].descricao;
          var taxaentregabairro = dados[index].taxaEntrega;

          taxaentregabairro	= accounting.formatMoney(taxaentregabairro, "", 2, ".", ",");

          $('#formBairro')[0].reset();
          inputguid.val(guidbairro);
          campobairro.val(descricaobairro);
          campotaxa.val(taxaentregabairro);

        })
        titulomodal.html("Atualizando Bairro "+(campobairro.val()));
        campoguid.hide();
        botaosalvar.hide();
        botaoeditar.show();
        modall.modal('show');
      }
    });
  }
}

function replacedot(){
  var preco = $("#preco").val();
  preco = preco.replace(",", ".");
  $("#preco").val(preco);
};

function salvar(operacao, guid){
  var campotaxa					= $("#preco").val();
  var campodescricao		= $("#descricao").val();
  var guidupdate				= $("#guid").val();

  toastr.options = {
    "closeButton": true,
    "debug": false,
    "newestOnTop": false,
    "progressBar": true,
    "positionClass": "toast-top-right",
    "preventDuplicates": false,
    "onclick": null,
    "showDuration": "300",
    "hideDuration": "1000",
    "timeOut": "5000",
    "extendedTimeOut": "1000",
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut"
  };

  if (campodescricao.length < 1){
    toastr.warning('O Campo Nome do Bairro não pode ficar em branco', 'Atenção');
    $("#descricao").focus();
  } else if (campotaxa.length < 1) {
    toastr.warning('O Campo Taxa não pode ficar em branco', 'Atenção');
    $("#preco").focus();
  } else {

    if (operacao == 3){
      var deleteresult			 = confirm("Deseja deletar ?");
      if (deleteresult == true) {
        $.ajax({
          url:"ajax/bairro/bairros.php",
          type:"POST",
          data:"descricao="+campodescricao+"&taxa="+campotaxa+"&guid="+guid+"&operacao="+operacao,
          success: function (result){
            $('#modal').modal('hide');
            if (result == 1) {
              toastr.success('Registro Deletado com Sucesso', 'OK')
              reloadtable();
            }
          }
        })
      }
    } else {
      $.ajax({
        url:"ajax/bairro/bairros.php",
        type:"POST",
        data:"descricao="+campodescricao+"&taxa="+campotaxa+"&guid="+guid+"&operacao="+operacao+"&guidupdate="+guidupdate,
        success: function (result){
          if (result == 4) {
            toastr.warning('Já Existe um registro cadastrado para este bairro', 'Atenção');
            $("#descricao").focus();
          } else {
            toastr.success('Registro Salvo com Sucesso', 'OK')
            $('#modal').modal('hide');
          }
          reloadtable();
        }
      })
    }
  }
}

$(function () {
  $('#supported').text('Supported/allowed: ' + !!screenfull.enabled);

  if (!screenfull.enabled) {
    return false;
  }

  $('#toggle').click(function () {
    screenfull.toggle($('#container')[0]);
  });

});
