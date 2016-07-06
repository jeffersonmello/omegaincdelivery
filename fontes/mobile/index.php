<?php
ob_start();


ini_set( 'display_errors', true );
error_reporting(E_ALL & ~ E_NOTICE & ~ E_DEPRECATED);

?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no, minimal-ui">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <title>Omega Inc - Delivery</title>
    <!-- Path to Framework7 Library CSS-->
    <link rel="stylesheet" href="css/framework7.material.min.css">
    <link rel="stylesheet" href="css/framework7.material.colors.min.css">

    <!-- Path to your custom app styles-->
    <link rel="stylesheet" href="css/delivery.css">

    <!--jquery-->
    <script   src="https://code.jquery.com/jquery-3.0.0.min.js"   integrity="sha256-JmvOoLtYsmqlsWxa7mDSLMwa6dZ9rrIdtrrVYRnDRH0="   crossorigin="anonymous"></script>

    <!--Toastr-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet"/>

    <!--DataTables-->
    <script src="https://cdn.datatables.net/1.10.11/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.11/js/dataTables.bootstrap.min.js"></script>

    <!--Import Google Icon Font-->
    <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!--Import Fontawesome Icon Font-->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-T8Gy5hrqNKT+hzMclPo118YTQO6cYprQmhrYwIiQ/3axmI1hQomh7Ud2hPOy8SP1" crossorigin="anonymous">

    <script type="text/javascript">
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

            function verificaBairro(){
              var myApp = new Framework7({
                material: true
              });
              var mainView = myApp.addView('.view-main');

             var ende   = $("#bairro").val();
             var nome   = $("#nome").val();
             var email  = $("#email").val();
             var cepV   = $("#cep").val();

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
               data: "br="+ende,
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
    </script>

  </head>
  <body>
    <!-- Status bar overlay for fullscreen mode-->
    <div class="statusbar-overlay"></div>
    <!-- Panels overlay-->
    <div class="panel-overlay"></div>
    <!-- Left panel with reveal effect-->
    <div class="panel panel-left panel-reveal">
      <div class="content-block">
        <p>Left panel content goes here</p>
      </div>
    </div>

    <!-- Views-->
    <div class="views">
      <div class="view view-main">

        <!-- Top Navbar-->
        <div class="navbar">
          <div class="navbar-inner">
            <div class="left"><a href="#" class="link icon-only open-panel"> <i class="icon icon-bars"></i></a></div>
            <div class="center">Delivery</div>
            <div class="right"></div>
          </div>
        </div>

        <!-- Pages, because we need fixed-through navbar and toolbar, it has additional appropriate classes-->
        <div class="pages navbar-through toolbar-through">
          <!-- Page, data-page contains page name-->
          <div data-page="index" class="page">
            <!-- Scrollable page content-->
            <div class="page-content">
             <div class="login-screen modal-in">
      <!-- Default view-page layout -->
      <div class="view">
        <div class="page">

          <br>
          <br>

          <!-- page-content has additional login-screen content -->
          <div class="page-content login-screen-content">
            <div class="login-screen-title">Delivery</div>


            <!-- Login form -->
            <form action="" method="post">
              <div class="list-block">
                <ul>

                  <li class="item-content">
                    <div class="item-media"><i class="material-icons color-icon">near_me</i></div>
                    <div class="item-inner">
                      <div class="item-input">
                        <input type="text"  id="cep" name="cep" placeholder="CEP"  maxlength="8" required autofocus>
                      </div>
                    </div>
                    </li>

                    <li class="item-content">
                      <div class="item-media"><i class="material-icons color-icon">location_on</i></div>
                      <div class="item-inner">
                        <div class="item-input">
                          <input style="color: green" type="text" id="endereco" name="endereco" placeholder="" disabled>
                        </div>
                      </div>
                      </li>

                  <li class="item-content">
                    <div class="item-media"><i class="material-icons color-icon">person</i></div>
                    <div class="item-inner">
                      <div class="item-input">
                        <input type="text" id="nome" name="nome" placeholder="Seu Nome" required>
                      </div>
                    </div>
                  </li>

                  <li class="item-content">
                    <div class="item-media"><i class="material-icons color-icon">email</i></div>
                    <div class="item-inner">
                      <div class="item-input">
                        <input type="email" id="email" name="email" placeholder="Seu Email" required>
                      </div>
                    </div>
                  </li>

                  <input name="cep" type="hidden" id="cep" value="" size="15" maxlength="8" />
                  <input name="rua" type="hidden" id="rua" size="60" />
                  <input name="bairro" type="hidden" id="bairro" class="form-control" size="60" />
                  <input name="cidade" type="hidden" id="cidade" size="60" />

                </ul>
              </div>
              <div class="list-block">
                <ul>
                  <li>
                    <p class="buttons-row">
                      <a href="" onclick="verificaBairro()"  class="button button-fill button-raised color-green">Pronto</a>
                    </p>
                  </li>
                </ul>
              </div>
            </form><!-- fim login page -->

            <br>

            <div class="content-block-title">Delivery</div>
                <div class="content-block">
                  <div class="content-block-inner">
                    <p class="color-icon">
                      Horário de Atendimento: Ter. à Dom. das 18h as 00h.<br>
                      Telefone:<br>
                      Email:
                    </p>
                  </div>
                </div>

           </div>
        </div>
      </div>
  </div>
</div>
</div>
</div>

    </div>
    <!-- Path to Framework7 Library JS-->
    <script type="text/javascript" src="js/framework7.min.js"></script>
    <!-- Path to your app js-->
    <script type="text/javascript" src="js/my-app.js"></script>
  </body>
</html>
