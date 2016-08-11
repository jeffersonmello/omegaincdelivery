function searchOrder(){
  var ordernumber = $("#numeropedido").val();
  var tokennumber = $("#token").val();

  $.ajax({
    url:  "ajax/searchOrder_returntimeline.php",
    type: "POST",
    data: "order="+ordernumber+"&token="+tokennumber,
    success: function(dados){
      if (dados != 0){
        $('#container').load(dados, function(){});
      }
    }
  })
}
