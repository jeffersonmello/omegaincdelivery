function searchOrder(){
  var ordernumber = $("#numeropedido").val();
  var tokennumber = $("#token").val();
  var myApp = new Framework7({
    material: true
  });
  var mainView = myApp.addView('.view-main');

  if ( ordernumber.length < 1 ){
    myApp.alert('Número do pedido é obrigatório', 'Número do Pedido Não Informado');
  } else if ( tokennumber.length < 1 ) {
    myApp.alert('Token do pedido é obrigatório', 'Token do Pedido Não Informado');
  } else {

    $.ajax({
      url:  "ajax/searchOrder_returntimeline.php",
      type: "POST",
      data: "order="+ordernumber+"&token="+tokennumber,
      success: function(dados){
        if (dados != 0){
          $('#container').load(dados, function(){});
          $("#container").show();
        } else {
          myApp.alert('Pedido não encontrado', 'Erro');
        }
      }
    })
  }
}

function clearTimeLine(){
  var ordernumber = $("#numeropedido");
  var tokennumber = $("#token");
  var conatainer  = $("#container");

  ordernumber.val('');
  tokennumber.val('');

  conatainer.hide();
}
