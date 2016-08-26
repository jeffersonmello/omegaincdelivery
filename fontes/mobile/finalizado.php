<?php
ob_start();

ini_set( 'display_errors', true );
error_reporting(E_ALL & ~ E_NOTICE & ~ E_DEPRECATED);

session_start();
session_destroy();

setlocale(LC_MONETARY,"pt_BR", "ptb");

$taxa         = $_SESSION['taxaentrega'];
$nome_cliente = $_SESSION['nomecliente'];
$email_cliente= $_SESSION['emailcliente'];
$guid_bairro  = $_SESSION['idBairro'];
$guid_pedido  = $_SESSION['idPedido'];
$endereco     = $_SESSION['endereco'];
$total        = $_SESSION['totalpedio'];

$total = money_format('%n', $total);


include('class/mysql_crud.php');
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
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
  <script type="text/javascript" src="js/qrcode.js"></script>
  <script src="js/searchOrder.min.js"></script>


  <!--Import Google Icon Font-->
  <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

  <!--Import Fontawesome Icon Font-->
  <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-T8Gy5hrqNKT+hzMclPo118YTQO6cYprQmhrYwIiQ/3axmI1hQomh7Ud2hPOy8SP1" crossorigin="anonymous">


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
                    <div class="login-screen-title"><i class="material-icons color-green">check_circle</i> Pedido Realizado com Sucesso !</div>


                    <!-- end form -->
                    <form action="" method="post">
                      <div class="list-block">
                        <ul>

                          <li class="item-content">
                            <div class="item-media"><i class="material-icons color-icon">check_circle</i></div>
                            <div class="item-inner">
                              <div class="item-input">
                                <input type="text" value="#<?php echo $guid_pedido?>" id="pedido" name="pedido" placeholder="pedido" required disabled>
                              </div>
                            </div>
                          </li>

                          <li class="item-content">
                            <div class="item-media"><i class="material-icons color-icon">attach_money</i></div>
                            <div class="item-inner">
                              <div class="item-input">
                                <input type="text" value="<?php echo $total?>" id="total" disabled>
                              </div>
                            </div>
                          </li>

                        </ul>
                        <div id="qrcode" style="width:100px; height:100px; margin-top:15px;"></div>
                        <script type="text/javascript">
                        $(document).ready(function(){
                          makeCode();
                        });

                        var qrcode = new QRCode(document.getElementById("qrcode"), {
                          width : 100,
                          height : 100
                        });

                        function makeCode () {
                          var elText = "oi"
                          qrcode.makeCode(elText.value);
                        }
                        </script>
                      </div>

                    </form>
                    <br>
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
