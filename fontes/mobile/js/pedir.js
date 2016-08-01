$(document).ready(function(){
  totaliza();

  $("#search").on( 'keyup', function () {
    var pesquisa      = $("#search").val();
    var categorias    = $(".categorias");
    var produtos      = $("#listaprodutos");
    var itemproduto   = $(".itempesquisa");

    categorias.hide();

    $(itemproduto, produtos).remove();

    $.ajax({
      url:("ajax/buscaprods.php"),
      type: "POST",
      data: "busca="+pesquisa,
      success:function(dados){
        $.each(dados, function(index){
          var len    = dados.length;
          for (var i=0; i < len; i++){
            var imageem     = ('"'+dados[index].imgproduto+'"');
            var descricaao  = ('"'+dados[index].descricao+'"');
            produtos.append("<li id='itempesquisa_'"+i+"' class='item-content itempesquisa'><img src='"+dados[index].imgproduto+"' width='44'></div><div class='item-inner'><div class='item-title-row'><div class='item-title'>"+dados[index].descricao+"</div><div class='item-after'><span href='#' onclick='adicionarCarrinho("+dados[index].guid+","+descricaao+","+dados[index].preco+","+imageem+")' class='button'><i class='material-icons color-icon'>add</i></span></div></div><div class='item-subtitle'>R$ "+dados[index].preco+"</div></div></li>");
          }
        });
      }})
  })


  $("#search").on( 'blur', function () {
    var categorias    = $(".categorias"); // Lista de categorias
    var produtos      = $("#listaprodutos");
    var itemproduto   = $(".itempesquisa");
    var pesquisas = $('#search').val();

    if (pesquisas.length < 1) {
      $(itemproduto, produtos).remove();
      categorias.show();
    }
  })
})

function mesmo(guid,valor){
  $.ajax({
    url:("ajax/mesmoitem.php"),
    type: "POST",
    data: "guidprod="+guid+"&guidpedido="+<?php echo $guid_pedido; ?>,
    success:function(dados){
      $("#idvaloresqtde_"+guid).html("R$ "+(valor.toFixed(2, '.', ','))+" ("+dados+")");
      totaliza();
    }})
}


function totaliza(){
  $.ajax({
    url:("ajax/totaliza.php"),
    type: "POST",
    data: "guidpedido="+<?php echo $guid_pedido; ?>+"&taxa="+<?php echo $taxa; ?>,
    success:function(dados){
      var valor = dados;
      $("#total").html("R$ "+(valor.toFixed(2)));
    }})
  }

function removeItem(guid, preco){
  var corrente     = $("#listacarrinho_"+guid);

  $.ajax({
    url:("ajax/removeitem.php"),
    type: "POST",
    data: "guidprod="+guid+"&guidpedido="+<?php echo $guid_pedido; ?>,
    success:function(dados){
      if (dados == 1) {
        mesmo(guid, preco);
        totaliza()
      } else {
        corrente.remove();
        totaliza()
      }
    },
    error: function(XMLHttpRequest, textStatus, errorThrown) {
    alert("Status: " + textStatus); alert("Error: " + errorThrown);
}
  })
}

function adicionarCarrinho(guid, nome, preco, imagem){
  var currentiten   = $("#listacarrinho_"+guid);
  var listacarrinho = $("#teste");

  $.ajax({
    url:("ajax/adicionacarrinho.php"),
    type: "POST",
    data: "guidprod="+guid+"&guidpedido="+<?php echo $guid_pedido; ?>,
    success:function(dados){
      if ($(currentiten, listacarrinho).length){
        mesmo(guid,preco);
      } else {
        if (dados == 1){
          listacarrinho.append("<li id='listacarrinho_"+guid+"'><div class='item-content'><div class='item-media'> <i class='icon my-icon'><img src='"+imagem+"' width='44'></i></div><div class='item-inner'><div class='item-title-row'><div class='item-title'>"+nome+"</div><div id='idvaloresqtde_"+guid+"' class='item-after'>R$ "+(preco.toFixed(2))+" (1)</div></div><div class='item-subtitle'><a href='#' onclick='removeItem("+guid+","+preco+")' class='button color-red'><i class='material-icons color-icon'>delete</i></a></div></div></div></li>");
          totaliza();
        }
        }
    }})
}

function cancelaPedido(){
  var pedido = <?php echo $guid_pedido ?>;

  $.ajax({
    url:("ajax/cancelapedido.php"),
    type: "POST",
    data: "guidpedido="+pedido,
    success:function(dados){
      if (dados == 1){
        location.href = 'index.php'
      }
    }
  })
}

function finalizaPedido(){
  var myApp = new Framework7({
    material: true
  });
  var mainView = myApp.addView('.view-main');
  var total   = totaliza();

  $.ajax({
    url:("ajax/totalitens.php"),
    type: "POST",
    data: "guidpedido="+<?php echo $guid_pedido; ?>,
    success:function(dados){
        if (dados == 1){
          location.href = 'finalizar.php'
        } else {
          myApp.addNotification({
          message: 'É necesário ter pelo menos um item no carrinho.',
          button: {
                      text: 'Fechar',
                  },
           });
        }
    }})
}
