$(document).ready(function(){
  reloadtable();
  reloadtable();

})

function formatacnpj(){
  var cnpj = $("#cnpj");

  cnpj.val(
    cnpj.val()
    .replace(/\D/g, '')
    .replace(/^(\d{2})(\d{3})?(\d{3})?(\d{4})?(\d{2})?/, "$1.$2.$3/$4-$5"));
  }

  function reloadtable(){
    $('#divcat').load('ajax/empresa/tab_empresas.php', function(){
    });
  }

  function openModal(operacao, guid){
    var modall 				= $('#modal');
    var titulomodal		= $("#titulomodal");
    var campoguid			= $("#campoguid");
    var botaoeditar		= $("#botaoatualizar");
    var botaosalvar		= $("#botaosalvar");

    var inputguid			= $("#guid");
    var camponome     = $("#nome");
    var campocnpj     = $("#cnpj");
    var campoinscestd = $("#inscestd");
    var campotelefone = $("#telefone");
    var campoemail    = $("#email");
    var campoend      = $("#endreco");
    var campopadrao   = $("#padrao");
    var selected      = $("#selectedOption");


    if (operacao == "Novo"){
      titulomodal.html("Cadastro de Empresa");
      selected.hide();
      campoguid.hide();
      botaosalvar.show();
      botaoeditar.hide();
      $('#formEmpresas')[0].reset();
      modall.modal('show');
    } else
    if (operacao == "editar"){
      $.ajax({
        url:"ajax/empresa/populate_empresa.php",
        type:"POST",
        data:"guid="+guid,
        success: function (dados){
          $.each(dados, function(index){
            var guidempresa				= dados[index].guid;
            var nome       		    = dados[index].nome;
            var cnpj       		    = dados[index].cnpj;
            var insc       		    = dados[index].inscricaoestd;
            var telefone   		    = dados[index].telefone;
            var email      		    = dados[index].email;
            var endereco   		    = dados[index].endereco;
            var padrao     		    = dados[index].padrao;
            var padraotext        = 0;

            $('#formEmpresas')[0].reset();
            if (padrao == 1) {
              padraotext = "Sim";
              $("#u1").hide();
            } else if (padrao == 2) {
              padraotext = "Não";
              $("#u2").hide();
            };

            selected.show();
            selected.html(padraotext);
            selected.val(padrao);

            inputguid.val(guidempresa);
            camponome.val(nome);
            campocnpj.val(cnpj);
            campoinscestd.val(insc);
            campotelefone.val(telefone);
            campoemail.val(email);
            campoend.val(endereco);
            campopadrao.val(padrao);

          })
          titulomodal.html("Atualizando Empresa "+(camponome.val()));
          campoguid.hide();
          botaosalvar.hide();
          botaoeditar.show();
          modall.modal('show');
        }
      });
    }
  }


  function salvar(operacao, guid){
    var camponome     = $("#nome").val();
    var campocnpj     = $("#cnpj").val();
    var campoinscestd = $("#inscestd").val();
    var campotelefone = $("#telefone").val();
    var campoemail    = $("#email").val();
    var campoend      = $("#endreco").val();
    var campopadrao   = $("#padrao").val();
    var selected      = $("#selectedOption").val();
    var guidupdate		= $("#guid").val();


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

    if (camponome.length < 1){
      toastr.warning('O Campo Nome da Empresa não pode ficar em branco', 'Atenção');
      $("#nome").focus();
    } else if (campopadrao.length < 1) {
      toastr.warning('O Campo Padrão não pode ficar em branco', 'Atenção');
      $("#padrao").focus();
    }
    else {

      if (operacao == 3){
        var deleteresult = confirm("Deseja deletar ?");
        if (deleteresult == true) {
          $.ajax({
            url:"ajax/empresa/empresa.php",
            type:"POST",
            data:"guid="+guid+"&operacao="+operacao,
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
          url:"ajax/empresa/empresa.php",
          type:"POST",
          data:"nome="+camponome+"&cnpj="+campocnpj+"&insc="+campoinscestd+"&telefone="+campotelefone+"&email="+campoemail+"&endereco="+campoend+"&padrao="+campopadrao+"&guid="+guid+"&operacao="+operacao+"&guidupdate="+guidupdate,
          success: function (result){
            if (result == 4) {
              toastr.warning('Já Existe uma Empresa Padrão cadastrada, Não é permitido ter duas empresas padrão', 'Atenção');
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
