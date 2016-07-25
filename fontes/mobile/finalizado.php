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


include('class/mysql_crud.php');
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


                </ul>
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
