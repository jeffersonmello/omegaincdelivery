function searchRel(){
  var datainicial = $("#datainicial");
  var datafinal   = $("#datafinal");

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

  if ((datainicial.val()).length < 1){
    toastr.warning('O campo Data Incial é Obrigatório', 'Atenção');
    datainicial.focus();
  } else if ((datafinal.val()).length < 1){
    toastr.warning('O campo Data Final é Obrigatório', 'Atenção');
    datafinal.focus();
  } else {

    datainicial = datainicial.val();
    datafinal   = datafinal.val();


    $.ajax({
      url: "ajax/relatorio/rel_venda.php",
      type: "POST",
      data: "datainicial="+datainicial+"&datafinal="+datafinal,
      success: function(dados){
        var linha = 10;
        var total = 0;
        var doc = new jsPDF();


        pageHeight= doc.internal.pageSize.height;

        doc.setFontSize(14);
        doc.text(10, 10, ("Número Pedido"));
        doc.text(60, 10, ("Cliente"));
        doc.text(120, 10, ("Data"));
        doc.text(160, 10, ("Valor do Pedido"));

        $.each(dados, function(index, dado){
          var guidpedido    = dado.guid;
          var nomecliente   = dado.nome;
          var datapedido    = dado.data;
          var valortotal    = dado.total;
          var requiredPages = 4;

          valortotal	= accounting.formatMoney(valortotal, "", 2, ".", ",");
          datapedido	= moment(datapedido).format('DD/MM/YYYY');

          /*for(var i = 0; i < requiredPages; i++){
            doc.addPage();
            //doc.text(20, 100, 'Some Text.');
          }*/

          doc.text(10, (linha+10), (guidpedido));
          doc.text(60, (linha+10), (nomecliente));
          doc.text(120, (linha+10), (datapedido));
          doc.text(160, (linha+10), ("R$ " + valortotal));


          valortotal = parseFloat(valortotal);
          total      = (total + valortotal);

          linha = linha + 10;
        });

        total	= accounting.formatMoney(total, "R$ ", 2, ".", ",");

        doc.text(160, (linha+10), "Total");
        doc.text(160, (linha+20), (total));
        var string = doc.output('dataurlnewwindow');
      }
      ,
      error: function(XMLHttpRequest, textStatus, errorThrown) {
        alert("Status: " + textStatus); alert("Error: " + errorThrown);
        console.log(arguments);
      }
    })
  }
}
