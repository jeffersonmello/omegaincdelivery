$(document).ready(function(){
  reloadtableAll();
  reloadtableAll();
  verificaNovoPedido();
})

function reloadtableAll(){
  $('#divcat').load('ajax/pedidos/tab_pedidosAbertos.php', function(){});
  $('#producaodiv').load('ajax/pedidos/tab_pedidosEmProducao.php', function(){});
  $('#entregadiv').load('ajax/pedidos/tab_pedidosEntrega.php', function(){});
  $('#entregues').load('ajax/pedidos/tab_pedidosEntregues.php', function(){});
  $('#prontoss').load('ajax/pedidos/tab_prontos.php', function(){});
  $('#agretirada').load('ajax/pedidos/tab_agretirada.php', function(){});
  $('#canceladosdiv').load('ajax/pedidos/tab_cancelados.php', function(){});
  $('#devolvidosdiv').load('ajax/pedidos/tab_devolvidos.php', function(){});
}

function reloadtableAllPedido(){
  $('#divcat').load('ajax/pedidos/tab_pedidosAbertos.php', function(){});
}


document.addEventListener('DOMContentLoaded', function () {
  if (Notification.permission !== "granted")
  Notification.requestPermission();
});

function getprint(){
  var pedido = $("#guid").val();
  inprimir(pedido);
}

function getItemQtde(produto, pedido, callback){
  return $.ajax({
    url:  "ajax/pedidos/mesmo.php",
    type: "POST",
    data: "produto="+produto+"&pedido="+pedido,
    async: false,
    success: function(dados){
      callback(dados);
    },
    error: function(XMLHttpRequest, textStatus, errorThrown) {
      alert("Status: " + textStatus); alert("Error: " + errorThrown);
      console.log(arguments);
      console.log(produto, pedido);
    }
  });
}

function inprimir(guid){
  $.ajax({
    url: "ajax/pedidos/populate_pedidoAberto.php",
    type: "POST",
    data: "guid="+guid,
    success: function (dados){
      $.each(dados, function(index, dado){
        var guidPedido				= dado.guid;
        var statusPedido			= dado.status;
        var nomePedido				= dado.nome;
        var enderecoPedido		= dado.endereco;
        var totalPedido				= dado.total;
        var dataPedido				= dado.data;
        var telefonePedido		= dado.telefone;
        var numerocasaPedido	= dado.numero;
        var formaPagamento		= dado.formaPagamento;
        var observacaoPedido	= dado.observacao;
        var cpfClientePedido	= dado.cpf;
        var entregarPedido		= dado.entregar;
        var tokenPedido				= dado.token;
        var bairroPedido			= dado.bairro;
        var pagamentotext 		= 0;

        if (statusPedido == 1) {
          var statuspedidotext = "Processando";
        }

        if (formaPagamento == 0) {
          pagamentotext = "Dinheiro";
        } else {
          pagamentotext = "Cartão/Crédito/Débito";
        }

        totalPedido	= accounting.formatMoney(totalPedido, "", 2, ".", ",");
        dataPedido	= moment(dataPedido).format('DD/MM/YYYY');

        var doc = new jsPDF('p', 'mm', [150, 80]);

        doc.setFontSize(8);
        doc.text(1, 10, ("Número Pedido: "+guidPedido));
        doc.text(1, 14, ("Cliente: "+nomePedido));
        doc.text(1, 18, ("Endereco: "+enderecoPedido));
        doc.text(1, 22, ("Número: "+numerocasaPedido));
        doc.text(1, 26, ("Telefone: "+telefonePedido));
        doc.text(1, 30, ("Pagamento: "+pagamentotext));
        doc.text(1, 34, ("---------------------------------------------------------------------------------"));


        $.ajax({
          url:"ajax/pedidos/addprodstolist.php",
          type:"POST",
          data:"pedido="+guidPedido,
          success: function (dados){
            var linha				   = 34;
            var currentproduto = 0;
            var calls = [];

            $.each(dados, function(index, dado){
              var preco				   = dado.valorproduto;
              var produto        = dado.guidproduto;
              var nomeproduto    = dado.nomeproduto;
              var qtde           = 0;
              var pedido         = guidPedido;

              getItemQtde(produto, pedido, function(output) {
                qtde = output;
              });

              currentproduto     = produto;

              preco = accounting.formatMoney(preco, "R$ ", 2, ".", ",");

              linha = linha + 4;
              doc.text(1, (linha), ((dado.nomeproduto)+" | Preço: "+ preco + " Qtde: "+ qtde));
            });

            doc.text(1, (linha+4), ("---------------------------------------------------------------------------------"));
            doc.text(1, (linha+8), ("Total do Pedido: R$ "+ totalPedido));
            var string = doc.output('dataurlnewwindow');
          },
          error: function(XMLHttpRequest, textStatus, errorThrown) {
            alert("Status: " + textStatus); alert("Error: " + errorThrown);
            console.log(arguments);
          }
        });
        //doc.save('Test.pdf');
      });
    }
  })
}

// Mostra todas as opções de status do dropbox do menu
function showAll(){
  $("#u2").show();
  $("#u3").show();
  $("#u4").show();
  $("#u5").show();
  $("#u6").show();
  $("#u7").show();
  $("#u8").show();
  $("#u9").show();
}


function openModal(operacao, guid){
  var modall 				= $('#modal');
  var titulomodal		= $("#titulomodal");
  var campoguid			= $("#campoguid");
  var botaoeditar		= $("#botaoatualizar");
  var selected			= $("#optionSelected");

  var inputguid					= $("#guid");
  var campostatus				= $("#cat");
  var campotoken				= $("#token");
  var campodata					= $("#data");
  var campototal				= $("#total");
  var campoendereco			= $("#enderecoo");
  var camponumero				= $("#numero");
  var campobairro				= $("#bairro");
  var camponome					= $("#nome");
  var campotelefone			= $("#telefone");
  var campopagamento		= $("#pagamentoO");
  var campoobs					= $("#obss");
  var campototal2				= $("#total2");
  var campoentregar     = $("#entregarR");
  var campotroco        = $("#troco");


  if (operacao == "editar"){
    $.ajax({
      url:"ajax/pedidos/populate_pedidoAberto.php",
      type:"POST",
      data:"guid="+guid,
      success: function (dados){
        $.each(dados, function(index, dado){
          var guidPedido				= dado.guid;
          var statusPedido			= dado.status;
          var nomePedido				= dado.nome;
          var enderecoPedido		= dado.endereco;
          var totalPedido				= dado.total;
          var dataPedido				= dado.data;
          var telefonePedido		= dado.telefone;
          var numerocasaPedido	= dado.numero;
          var formaPagamento		= dado.formaPagamento;
          var observacaoPedido	= dado.observacao;
          var cpfClientePedido	= dado.cpf;
          var entregarPedido		= dado.entregar;
          var tokenPedido				= dado.token;
          var bairroPedido			= dado.bairro;
          var pagamentotext 		= 0;
          var entregarText      = 0;
          var troco             = dado.troco;

          if (entregarPedido == 1){
            $("#u4").hide();
            entregarText = "Sim, Entegar";
          } else {
            $("#u4").show();
            entregarText = "Não, Virá Retirar na loja";
          }

          if (statusPedido == 1) {
            var statuspedidotext = "Processando"
          } else if (statusPedido == 2) {
            statuspedidotext = "Em Produção";
            showAll();
            $("#u2").hide();
          } else if (statusPedido == 3) {
            statuspedidotext = "Pronto";
            showAll();
            $("#u3").hide();
          } else if (statusPedido == 4 ) {
            statuspedidotext = "Aguardando para busca";
            showAll();
            $("#u4").hide();
          } else if (statusPedido == 5) {
            statuspedidotext = "Saiu para Entrega";
            showAll();
            $("#u5").hide();
          } else if (statusPedido == 6) {
            statuspedidotext = "Entegue";
            showAll();
            $("#u6").hide();
          } else if (statusPedido == 7) {
            statuspedidotext = "Cliente não estava";
            showAll();
            $("#u7").hide();
          } else if (statusPedido == 8) {
            statuspedidotext = "Cancelado";
            showAll();
            $("#u8").hide();
          } else if (statusPedido == 9) {
            statuspedidotext = "Devolvido";
            showAll();
            $("#u9").hide();
          }

          if (formaPagamento == 0) {
            pagamentotext = "Dinheiro";
          } else {
            pagamentotext = "Cartão/Crédito/Débito";
          }

          totalPedido	= accounting.formatMoney(totalPedido, "", 2, ".", ",");
          troco	= accounting.formatMoney(troco, "R$ ", 2, ".", ",");
          //totalPedido	= parseFloat(totalPedido).toFixed(2);
          dataPedido	= moment(dataPedido).format('DD/MM/YYYY');

          $('#formPedido')[0].reset();
          $('#formEndereco')[0].reset();
          $('#formaPagamento')[0].reset();

          selected.val(statusPedido);
          selected.html(statuspedidotext);
          //totalPedido = (totalPedido.toFixed(2));
          campotroco.val(troco);
          campototal.val(totalPedido);
          campotoken.val(tokenPedido);
          campodata.val(dataPedido);
          campoendereco.val(enderecoPedido);
          camponumero.val(numerocasaPedido);
          campobairro.val(bairroPedido);
          camponome.val(nomePedido);
          campotelefone.val(telefonePedido);
          campopagamento.val(pagamentotext);
          campoobs.val(observacaoPedido);
          campoentregar.val(entregarText);
          campototal2.val(totalPedido);
        })
        titulomodal.html("Pedido #"+guid);
        campoguid.hide();
        botaoeditar.show();
        inputguid.val(guid);
        addProdtolist(guid, (campobairro.val()));
        modall.modal('show');
      }
    });
  }
}

function notifyMe(pedido) {
  if (!Notification) {
    alert('Desktop notifications not available in your browser. Try Chromium.');
    return;
  }

  if (Notification.permission !== "granted")
  Notification.requestPermission();
  else {
    var notification = new Notification(('Novo Pedido #'+pedido), {
      icon: 'https://cdn0.iconfinder.com/data/icons/shop-payment-vol-4/128/shop-65-512.png',
      body: ("Novo Pedido Realiazdo Número: "+pedido),
    });

    var player = document.getElementById('audio');
    var musica = "http://mobile.kingofeletro.com.br/android/painel/sound.mp3";

    player.src = musica;
    player.play();

    notification.onclick = function () {
      window.focus()
    };
    toastr.options = {
      "closeButton": true,
      "debug": true,
      "newestOnTop": true,
      "progressBar": true,
      "positionClass": "toast-top-right",
      "preventDuplicates": true,
      "onclick": null,
      "showDuration": "300",
      "hideDuration": "1000",
      "timeOut": "60000",
      "extendedTimeOut": "1000",
      "showEasing": "swing",
      "hideEasing": "linear",
      "showMethod": "fadeIn",
      "hideMethod": "fadeOut"
    };
    toastr["info"]("Pedido Recebido, Num: "+ pedido +" para visualizar clique <div><button type='button' onclick='openModal(\"editar\", "+pedido+");' class='btn btn-outline-info btn-sm'>Aqui</button>", "Novo Pedido")
  }
}

// Função que enviara dados de pedidos prontos, saidos para entrega, e concluidos
// Função ira enviar email e também irá gerar um arquivo txt
function exportaPedido(pedido, status){
  //alert(pedido);
  $.ajax({
    url:"ajax/exportapedido.php",
    type:"POST",
    data: "pedido="+pedido+"&statusnext="+status,
    success: function (result){
      console.log(result);
    }
  })
}

function enviaEmail(pedido, status){
  $.ajax({
    url:"ajax/pedidos/enviaemail.php",
    type:"POST",
    data: "pedido="+pedido+"&statusnext="+status,
    success: function (result){
      console.log(result);
    }
  })

}

function salvar(operacao, guid){
  var categoria 			  = $("#cat").val();
  var guidupdate				= $("#guid").val();

  exportaPedido(guidupdate, categoria);
  enviaEmail(guidupdate, categoria);
  //alert(guidupdate);

  $.ajax({
    url:"ajax/pedidos/pedido.php",
    type:"POST",
    data: "guidupdate="+guidupdate+"&categoria="+categoria+"&operacao="+operacao,
    success: function (result){
      $('#modal').modal('hide');
      if (result == 1) {
        toastr.success('Registro Deletado com Sucesso', 'OK')
      } else {
        toastr.success('Registro Salvo com Sucesso', 'OK')
      }
      reloadtableAll();
    }
  })
}

function abrirfechar(operacao){
  $.ajax({
    url:"ajax/abre_fecha/abrefecha.php",
    type:"POST",
    data:"operacao="+operacao,
    success: function (dados){
      location.reload();
    }
  })
}

var timeout = setTimeout(verificaNovoPedido, 2000);
function verificaNovoPedido(){
  $.ajax({
    url: "ajax/pedidos/verificaNovoPedido.php",
    type: "POST",
    success: function (dados){
      if (dados != 0){
        notifyMe(dados);
        reloadtableAllPedido();
      }
    }
  })
  timeout = setTimeout(verificaNovoPedido, 2000);
}

function mesmo(guidProd, guidPedido){
  var campqtde =  $("#qtdeListProd_"+guidProd);

  $.ajax({
    url: "ajax/pedidos/mesmo.php",
    type: "POST",
    data: "produto="+guidProd+"&pedido="+guidPedido,
    success: function (dados){
      campqtde.html(dados);
    }
  })
}

// Função que retorna o valor da taxa de entrega
function getBairroTax(bairro){
  var listaprodutos = $("#listprods");

  $.ajax({
    url:	"ajax/pedidos/taxa.php",
    type:	"POST",
    data: "bairro="+bairro,
    success: function (dados){
      var taxa = dados;

      taxa = accounting.formatMoney(taxa, "R$ ", 2, ".", ",");
      listaprodutos.append(' <li class="list-group-item">Taxa de Entrega: '+taxa+' </li>');
    }
  })

}

// Função que adiciona os produtos na lista do modal
// guidPedido: Número do pedido para identificar os produtos vinculados a ele
// TODO: falta adicionar como ultimo li a taxa de entrega, para retornar a taxa de entrega deve se criar outra função
// getBairroTax
function addProdtolist(guidPedido, bairro){
  var listaprodutos = $("#listprods");

  $.ajax({
    url:"ajax/pedidos/addprodstolist.php",
    type:"POST",
    data:"pedido="+guidPedido,
    success: function (dados){
      listaprodutos.empty();

      $.each(dados, function(index){
        var len    			= dados.length;
        var preco				= dados[index].valorproduto;

        preco = accounting.formatMoney(preco, "R$ ", 2, ".", ",");


        for (var i=0; i <= len; i++){
          var guidprod		= dados[index].guidproduto;
          var currentiten = $("#idLIProd_"+guidprod);

          if ($(currentiten, listaprodutos).length){
            mesmo(guidprod, guidPedido)
          } else {
            listaprodutos.append('<li id="idLIProd_'+guidprod+'" class="list-group-item">'+dados[index].nomeproduto+' | Preço: '+preco+'<span class="badge" id="qtdeListProd_'+guidprod+'" >1</span></li>');
          }
        }
      });
      getBairroTax(bairro);
    },
    error: function(XMLHttpRequest, textStatus, errorThrown) {
      alert("Status: " + textStatus); alert("Error: " + errorThrown);
      console.log(arguments);
    }
  })
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
