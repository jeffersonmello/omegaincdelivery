$(document).ready(function(){
  totaliza();

  var texto = $("#subdesc").text();

  for (i = 25; i > 1; i++){
    var proximoEspaco = texto.substring(i, (i + 1));

    if (proximoEspaco == " "){
      var textoCortado = texto.substring(0, i);
      i = 0;
    }
  }

  $("#subdesc").html(textoCortado + "...");

  //TODO Ainda está duplicando os itens na pesquisa, mas pelo menos não mais de duas vezes, acredito que seja pelo evento keyup, se fosse keypress nao daria problemas
  $("#search").on( 'keyup', function () {
    var pesquisa      = $("#search").val();
    var categorias    = $(".categorias");
    var produtos      = $("#listaprodutos");
    var itemproduto   = $(".itempesquisa");
    var myApp = new Framework7({material: true});

    categorias.hide();

    $(itemproduto, produtos).remove();

    $.ajax({
      url:("ajax/buscaprods.php"),
      type: "POST",
      data: "busca="+pesquisa,
      success:function(dados){
        var i = 0;

        myApp.showIndicator();
        $.each(dados, function(index, dado){
          i = i + 1 ;

          var imageem     = ('"'+ dado.imgproduto +'"');
          var descricaao  = ('"'+ dado.descricao +'"');
          var preeco      = dado.preco;
          preeco = accounting.formatMoney(preeco, "R$ ", 2, ".", ",");
          produtos.append("<li id='itempesquisa_'"+i+"' class='item-content itempesquisa'><img class='circular' src='"+dado.imgproduto+"' width='44'></div><div class='item-inner'><div class='item-title-row'><div class='item-title'>"+dado.descricao+"</div><div class='item-after'><span href='#' onclick='adicionarCarrinho("+dado.guid+","+descricaao+","+dado.preco+","+imageem+")' class='button'><i class='material-icons color-icon'>add</i></span></div></div><div class='item-subtitle'>"+preeco+"</div></div></li>");

        });
        setTimeout(function () {
          myApp.hideIndicator();
        }, 1000);
      }})
    })


    $("#search").on( 'blur', function () {
      var categorias    = $(".categorias"); // Lista de categorias
      var produtos      = $("#listaprodutos");
      var itemproduto   = $(".itempesquisa");
      var pesquisas     = $('#search').val();

      if (pesquisas.length < 1) {
        $(itemproduto, produtos).remove();
        categorias.show();
      }
    })
  })


  function viewimage(imagem, titulo, subdescricao){
    var myApp = new Framework7({material: true,});
    var modal = myApp.modal({
      title: titulo,
      text: subdescricao,
      afterText:  '<div class="swiper-container" style="width: auto; margin:5px -15px -15px">'+
      '<div class="swiper-pagination"></div>'+
      '<div class="swiper-wrapper">'+
      '<div><img class="circular-view" src="'+imagem+'" height="150" style="display:block"></div>' +
      '</div>'+
      '</div>',
      buttons: [
        {
          text: 'Fechar'
        }
      ]
    })
  }
