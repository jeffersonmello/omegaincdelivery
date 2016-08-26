$(document).ready(function(){
  document.getElementById("cep").onkeyup = function(e) {
    var chr = String.fromCharCode(e.which);
    if ("1234567890qwertyuioplkjhgfdsazxcvbnmQWERTYUIOPLKJHGFDSAZXCVBNM".indexOf(chr) < 0)
    return false;
  };

  $("#cep").blur(function(){
    $("#rua").val("...")
    $("#bairro").val("...")
    $("#endereco").val("Procurando seu endereço...")
    $("#cidade").val("...")
    $("#uf").val("...")

    consulta = $("#cep").val()
    $.getJSON("//viacep.com.br/ws/"+ consulta +"/json/?callback=?", function(dados) {

      rua=(dados.logradouro);
      bairro=(dados.bairro);
      cidade=(dados.localidade);
      uf=(dados.uf);

      $("#rua").val(rua)
      $("#bairro").val(bairro)
      $("#cidade").val(cidade)
      $("#endereco").val(rua+', '+bairro+', '+cidade)
      $("#uf").val(uf)
    });
  });
});


var timeout = setTimeout(verifica, 2000);
function verifica(){
  $.ajax({
    url:("ajax/verifica_abertofechado.php"),
    type: "POST",
    success:function(data){
      if(data!=1){
        location.href='fechados.html'
      }
    }})
    timeout = setTimeout(verifica, 2000);
  }

  $("#email").keydown(function(event) {
    if (event.which == 13) {
        event.preventDefault();
        verifica();
    }
});


  function validateEmail(email) {
    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
  }

  function verificaBairro(){
    var myApp = new Framework7({
      material: true
    });
    var mainView = myApp.addView('.view-main');

    var ende     = $("#bairro").val();
    var nome     = $("#nome").val();
    var email    = $("#email").val();
    var cepV     = $("#cep").val();
    var endereco = $("#endereco").val();
    var status   = 0;

    if (cepV.length < 8) {
      myApp.addNotification({
        message: 'CEP Inválido.',
        button: {
          text: 'Fechar',
        },
      });
    } else if (nome.length < 1) {
      myApp.addNotification({
        message: 'Preencha o campo Nome.',
        button: {
          text: 'Fechar',
        },
      });
    } else if (!(validateEmail(email))) {
      myApp.addNotification({
        message: 'E-mail Inválido.',
        button: {
          text: 'Fechar',
        },
      });
    } else {

      $.ajax({
        url:"ajax/verifica_bairro.php",
        type:"POST",
        data: "br="+ende+"&nome="+nome+"&email="+email+"&endereco="+endereco+"&status="+status,
        success: function (result){
          if(result==1){
            location.href='pedir.php'
          }else{

            myApp.addNotification({
              message: 'Desculpe, não entregamos neste bairro.',
              button: {
                text: 'Fechar',
              },
            });
          }
        }
      })
      return false;
    }
  }

  
