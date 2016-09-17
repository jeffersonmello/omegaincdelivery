function ceptobox(cep){
  var campocep        = $("#cep");
  var listaenderecos  = $("#listaresultados");
  var campopesqrua    = $("#nomedarua");

  campocep.val(cep);
  campocep.focus();
  listaenderecos.empty();
  campopesqrua.val('');
}

function getCEP(addres){
  var camporetorno    = $("#enderecocep");
  var listaenderecos  = $("#listaresultados");
  var enderecoisitem  = $(".enderecolista");
  var myApp = new Framework7({material: true});

  listaenderecos.empty();

  $.ajax({
    url: "ajax/cep/searchcep.php",
    type: "POST",
    data: "rua="+addres,
    success: function(dados){
      var i = 0;

      myApp.showIndicator();
      $.each(dados, function(index, dado){
        i = i + 1;
        var cep         = dado.cep;
        var endereco    = dado.rua;
        var bairro      = dado.bairro;

        cep = cep.replace(/\.|\-/g, '');

        listaenderecos.append("<li id='enderecolista_" + i + "' class='enderecolista' onclick='ceptobox(\"" + cep + "\")' ><a href='#'  class='close-popup'><div class='item-inner'><div class='item-title'>" + endereco + ", " + bairro + ", " + cep + "</div></div></a></li>");

      });
      setTimeout(function () {
        myApp.hideIndicator();
    }, 1000);
    }
  });
}
