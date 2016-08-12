<?php
ob_start();

ini_set( 'display_errors', true );
error_reporting(E_ALL & ~ E_NOTICE & ~ E_DEPRECATED);

session_start();
session_destroy();

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
  <script   src="js/jquery-3.0.0.min.js"></script>

  <script src="js/searchOrder.min.js"></script>

  <!--Import Google Icon Font-->
  <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

  <!--Import Fontawesome Icon Font-->
  <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-T8Gy5hrqNKT+hzMclPo118YTQO6cYprQmhrYwIiQ/3axmI1hQomh7Ud2hPOy8SP1" crossorigin="anonymous">
  <script type="text/javascript">
  $(function(){

    $('input').on('focus',function(){
      if(!$('#content').hasClass('kbactive')){
        addClass();
      }
    });

    $('input').on('blur',function(){
      removeClass();
    });

    function addClass(){ //Adds keyboard active class
      $('#content').addClass('kbactive');
    }

    function removeClass(){ //Removes keyboard active class
      $('#content').removeClass('kbactive');
    }

  })

  </script>
</head>
<body>
  <!-- Status bar overlay for fullscreen mode-->
  <div class="statusbar-overlay"></div>
  <!-- Panels overlay-->
  <div class="panel-overlay"></div>
  <!-- Left panel with reveal effect-->
  <div class="panel panel-left panel-reveal">
    <div class="navbar">
      <div class="navbar-inner">
        <div class="left"></div>
        <div class="center">Menu</div>
        <div class="right"></div>
      </div>
    </div>

    <div class="list-block">
      <ul>
        <li>
          <a href="#" data-popup=".popup-check" class="item-link open-popup">
            <div class="item-inner">
              <div class="item-title">Consultar Pedido</div>
            </div>
          </a>
        </li>
        <li>
          <a href="#" data-popup=".popup-about" class="item-link open-popup">
            <div class="item-inner">
              <div class="item-title">Sobre</div>
            </div>
          </a>
        </li>
      </ul>
    </div>
  </div>

  <!-- About Popup -->
  <div class="popup popup-about">
    <div class="content-block">
      <p>Sobre o Aplicativo</p>
      <p><a href="#" class="close-popup">Fechar</a></p>
      <p>Informaões da empresa que administra o aplicativo. <br>
        Sobre a empresa <br>
      </p>
      <p>Desenvolvimento</p>
      <p>Este aplicativo foi desenvolvido por Copyright Omega Inc. Todos os direitos Reservados<br>
      </p>
    </div>
  </div>

  <!-- Check Popup -->
  <div class="popup popup-check">
    <div class="content-block">
      <p>Consultar Pedido</p>
      <p><a href="#" onclick="clearTimeLine()" class="close-popup">Fechar</a></p>
      <div class="list-block">
        <ul>

          <li class="item-content">
            <div class="item-media"><i class="material-icons color-icon">search</i></div>
            <div class="item-inner">
              <div class="item-input">
                <input type="text"  id="numeropedido" placeholder="Numero do Pedido"  required>
              </div>
            </div>
          </li>

          <li class="item-content">
            <div class="item-media"><i class="material-icons color-icon">lock_outline</i></div>
            <div class="item-inner">
              <div class="item-input">
                <input type="text"  id="token" placeholder="Token"  required>
              </div>
            </div>
          </li>

          <li>
            <p class="buttons-row">
              <a type="submit" href="" onclick="searchOrder()"  class="button button-fill button-raised color-green">Pesquisar</a>
            </p>
          </li>
        </ul>
      </div>
      <div id="container">
      </div>
    </div>
  </div>

  <!-- Views-->
  <div class="views">
    <div class="view view-main mainprincipal">

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
                    <form action="." method="get">
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
                            <div class="item-inner">
                              <div class="item-input">
                                <p class="buttons-row">
                                  <a href="" onclick="" class="button button-fill button-raised color-bluegray">Usar Minha Localização</a>
                                </p>
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
                              <a type="submit" href="" onclick="verificaBairro()"  class="button button-fill button-raised color-green">Pronto</a>
                            </p>
                          </li>
                        </ul>
                      </div>
                    </form><!-- fim login page -->

                    <br>

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

    <!--delivery Js-->
    <script type="text/javascript" src="js/delivery.min.js" ></script>
  </body>
  </html>
