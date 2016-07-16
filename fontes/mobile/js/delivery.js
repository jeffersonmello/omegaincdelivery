$(document).ready(function(){
  document.getElementById("cep").onkeypress = function(e) {
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
            $.getScript("http://www.toolsweb.com.br/webservice/clienteWebService.php?cep="+consulta+"&formato=javascript", function(){

            rua=unescape(resultadoCEP.logradouro)
            bairro=unescape(resultadoCEP.bairro)
            cidade=unescape(resultadoCEP.cidade)
            uf=unescape(resultadoCEP.uf)

            $("#rua").val(rua)
            $("#bairro").val(bairro)
            $("#cidade").val(cidade)
            $("#endereco").val('Rua '+rua+', '+bairro+', '+cidade)
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
     } else if (email.length < 1) {
       myApp.addNotification({
               message: 'Preencha o campo email.',
               button: {
                           text: 'Fechar',
                       },
           });
     } else {

     $.ajax({
       url:"ajax/verifica_bairro.php",
       type:"POST",
       data: "br="+ende+"&nome="+nome+"&email="+email+"&endereco="+endereco,
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
