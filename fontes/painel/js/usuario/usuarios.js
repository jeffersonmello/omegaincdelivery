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
  var campousuario  = $("#usuario");
  var camposenha    = $("#senha");
  var camaponome    = $("#nome");
  var camponivel    = $("#nivel");
  var selected      = $("#selectedOption");


  if (operacao == "Novo"){
    titulomodal.html("Cadastro de Usuário");
    selected.hide();
    campoguid.hide();
    botaosalvar.show();
    botaoeditar.hide();
    $('#formUsuarios')[0].reset();
    modall.modal('show');
  } else
  if (operacao == "editar"){
    $.ajax({
      url:"ajax/usuarios/populate_usuario.php",
      type:"POST",
      data:"guid="+guid,
      success: function (dados){
        $.each(dados, function(index){
          var guidusuario				= dados[index].guid;
          var usuario       		= dados[index].usuario;
          var senha      		    = dados[index].senha;
          var nome              = dados[index].nome;
          var nivel             = dados[index].nivel;
          var niveltext         = 0;

          $('#formUsuarios')[0].reset();
          if (nivel == 1) {
            niveltext = "1 - Usuário Comun";
            $("#u1").hide();
          } else if (nivel == 2) {
            niveltext = "2 - Operador de Pedidos";
            $("#u2").hide();
          } else if (nivel == 3) {
            niveltext = "3 - Administrador";
            $("#u3").hide();
          } else {
            niveltext = "Nenhum nível selecionado";
          }
          selected.show();
          selected.html(niveltext);
          selected.val(nivel);

          inputguid.val(guidusuario);
          campousuario.val(usuario);
          camposenha.val(senha);
          camaponome.val(nome);


        })
        titulomodal.html("Atualizando Usuário "+(campousuario.val()));
        campoguid.hide();
        botaosalvar.hide();
        botaoeditar.show();
        modall.modal('show');
      }
    });
  }
}


function salvar(operacao, guid){
  var campousuario			= $("#usuario").val();
  var camposenha    		= $("#senha").val();
  var camaponome     		= $("#nome").val();
  var camponivel        = $("#nivel").val();
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

  if (campousuario.length < 1){
    toastr.warning('O Campo Usuário não pode ficar em branco', 'Atenção');
    $("#usuario").focus();
  } else if (camposenha.length < 1) {
    toastr.warning('O Campo Senha não pode ficar em branco', 'Atenção');
    $("#senha").focus();
  }
  else if (camaponome.length < 1) {
    toastr.warning('O Campo Nome não pode ficar em branco', 'Atenção');
    $("#nome").focus();
  }
  else if (camponivel.length < 1) {
    toastr.warning('O Campo Nível de Acesso não pode ficar em branco', 'Atenção');
    $("#nivel").focus();
  }
   else {

    if (operacao == 3){
      var deleteresult			 = confirm("Deseja deletar ?");
      if (deleteresult == true) {
        $.ajax({
          url:"ajax/usuarios/usuario.php",
          type:"POST",
          data:"usuario="+campousuario+"&senha="+camposenha+"&nome="+camaponome+"&nivel="+camponivel+"&guid="+guid+"&operacao="+operacao,
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
        url:"ajax/usuarios/usuario.php",
        type:"POST",
        data:"usuario="+campousuario+"&senha="+camposenha+"&nome="+camaponome+"&nivel="+camponivel+"&guid="+guid+"&operacao="+operacao+"&guidupdate="+guidupdate,
        success: function (result){
          if (result == 4) {
            toastr.warning('Já Existe um usuario cadastrado com esse nome de usuario', 'Atenção');
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
