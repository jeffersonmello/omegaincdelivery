<!DOCTYPE HTML>
<?php
ob_start();// Inicia Buffer



session_start(); 	//A seção deve ser iniciada em todas as páginas
if (!isset($_SESSION['usuarioID'])) {		//Verifica se há seções
        session_destroy();						//Destroi a seção por segurança
        header("Location: index.php"); exit;	//Redireciona o visitante para login
}
// Inclui a classe de CRUD mysql
include('class/mysql_crud.php');

// Cria o objeto Database
$db = new Database();

// Dados do usuario logado
$id_usuario     = $_SESSION["usuarioID"];

// Pega valores do usuario
$db->connect();
$db->sql("SELECT * FROM adm_usuarios WHERE guid = $id_usuario LIMIT 1");
$res = $db->getResult();
foreach ($res as $output) {
        $nome_usuario 	= $output["nome"];
        $login_usuario 	= $output["usuario"];
        $nivel_usuario  = $output["nivel"];
        $imagem_usuario = $output["imagem"];
}

// Configurações da página
$nivel_pagina    = 1;

// Rotina de verificação nivel de usuario
if ($nivel_usuario < $nivel_pagina){
        header("Location: 403.html"); exit;
}

?>
<html>
<head>
        <title>Omega Inc. | Delivery Dashboard</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="keywords" content="Minimal Responsive web template, Bootstrap Web Templates, Flat Web Templates, Android Compatible web template,
        Smartphone Compatible web template, free webdesigns for Nokia, Samsung, LG, SonyEricsson, Motorola web design" />

        <script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
        <link href="css/bootstrap.min.css" rel='stylesheet' type='text/css' />

        <!-- Custom Theme files -->
        <link href="css/style.css" rel='stylesheet' type='text/css' />
        <link href="css/font-awesome.css" rel="stylesheet">
        <script src="js/jquery.min.js"> </script>

        <!-- Mainly scripts -->
        <script src="js/jquery.metisMenu.js"></script>
        <script src="js/jquery.slimscroll.min.js"></script>

        <!-- Custom and plugin javascript -->
        <link href="css/custom.css" rel="stylesheet">
        <script src="js/custom.js"></script>
        <script src="js/screenfull.js"></script>

        <!--Toastr-->
        <script src="js/toastr.min.js"></script>
        <link href="css/toastr.min.css" rel="stylesheet"/>

        <script type="text/javascript">
        $(function () {
                $('#supported').text('Supported/allowed: ' + !!screenfull.enabled);
                if (!screenfull.enabled) {
                        return false;
                }

                $('#toggle').click(function () {
                        screenfull.toggle($('#container')[0]);
                });

        });

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
        </script>

        <!----->

        <!--pie-chart--->
        <script src="js/pie-chart.js" type="text/javascript"></script>
        <script type="text/javascript">
        $(document).ready(function () {
                $('#demo-pie-1').pieChart({
                        barColor: '#3bb2d0',
                        trackColor: '#eee',
                        lineCap: 'round',
                        lineWidth: 8,
                        onStep: function (from, to, percent) {
                                $(this.element).find('.pie-value').text(Math.round(percent) + '%');
                        }
                });

                $('#demo-pie-2').pieChart({
                        barColor: '#fbb03b',
                        trackColor: '#eee',
                        lineCap: 'butt',
                        lineWidth: 8,
                        onStep: function (from, to, percent) {
                                $(this.element).find('.pie-value').text(Math.round(percent) + '%');
                        }
                });

                $('#demo-pie-3').pieChart({
                        barColor: '#ed6498',
                        trackColor: '#eee',
                        lineCap: 'square',
                        lineWidth: 8,
                        onStep: function (from, to, percent) {
                                $(this.element).find('.pie-value').text(Math.round(percent) + '%');
                        }
                });


        });
        </script>

        <!--skycons-icons-->
        <script src="js/skycons.js"></script>
        <!--//skycons-icons-->

</head>
<body>
        <div id="wrapper">

                <!----->
                <nav class="navbar-default navbar-static-top" role="navigation">
                        <div class="navbar-header">
                                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                                        <span class="sr-only">Toggle navigation</span>
                                        <span class="icon-bar"></span>
                                        <span class="icon-bar"></span>
                                        <span class="icon-bar"></span>
                                </button>
                                <h1> <a class="navbar-brand" href="dashboard.php">Delivery</a></h1>
                        </div>
                        <div class=" border-bottom">
                                <div class="full-left">
                                        <section class="full-top">
                                                <button id="toggle"><i class="fa fa-arrows-alt"></i></button>
                                        </section>
                                        <form class=" navbar-left-right">
                                                <input type="text"  value="Search..." onfocus="this.value = '';" onblur="if (this.value == '') {this.value = 'Search...';}">
                                                <input type="submit" value="" class="fa fa-search">
                                                <?php
                                                $db->connect();
                                                $db->sql("SELECT * FROM adm_empresa WHERE padrao = 1");
                                                $res = $db->getResult();
                                                foreach ($res as $output) {
                                                        $abertofechado = $output["aberto"];
                                                }
                                                if ($abertofechado == 1){
                                                        echo '<button type="button" onclick="abrirfechar(0);" class="btn btn-danger">Fechar Loja</button>';
                                                } else {
                                                        echo '<button type="button" onclick="abrirfechar(1);" class="btn btn-success">Abrir Loja</button>';
                                                }
                                                ?>
                                        </form>
                                        <div class="clearfix"> </div>
                                </div>


                                <!-- Brand and toggle get grouped for better mobile display -->

                                <!-- Collect the nav links, forms, and other content for toggling -->
                                <div class="drop-men" >
                                        <ul class=" nav_1">

                                                <li class="dropdown">
                                                        <a href="#" class="dropdown-toggle dropdown-at" data-toggle="dropdown"><span class=" name-caret"><?php echo $nome_usuario ?><i class="caret"></i></span><img width="60px" height="60px" src="<?php
                                                        if ($imagem_usuario != "" ){
                                                                echo $imagem_usuario;
                                                        } else {
                                                                echo ("images/default.png") ;
                                                        } ?>"></a>
                                                        <ul class="dropdown-menu " role="menu">
                                                                <li><a href="edit_user.php"><i class="fa fa-user"></i>Editar usuario</a></li>
                                                        </ul>
                                                </li>

                                        </ul>
                                </div><!-- /.navbar-collapse -->
                                <div class="clearfix">

                                </div>

                                <div class="navbar-default sidebar" role="navigation">
                                        <div class="sidebar-nav navbar-collapse">
                                                <?php
                                                include('class/menu.php');
                                                ?>
                                        </div>
                                </div>
                        </nav>
                        <div id="page-wrapper" class="gray-bg dashbard-1">
                                <div class="content-main">

                                        <!--banner-->
                                        <div class="banner">

                                                <h2>
                                                        <a href="dashboard.php">Home</a>
                                                        <i class="fa fa-angle-right"></i>
                                                        <span>Dashboard</span>
                                                </h2>
                                        </div>
                                        <!--//banner-->
                                        <!--content-->
                                        <div class="content-top">


                                                <div class="col-md-4">
                                                        <div class="content-top-1">
                                                                <div class="col-md-6 top-content">
                                                                        <h5>Ped. Mês</h5>
                                                                        <?php
                                                                                $data = getdate();
                                                                                $mes  = $data[mon];
                                                                                $ano  = $data[year];
                                                                                $dataincial = ("01-"+$mes+"-"+$ano);
                                                                                $datafinal  = ("31-"+$mes+"-"+$ano);
                                                                                $db->connect();
                                                                                $db->sql("SELECT * FROM lanc_pedidos WHERE status = 6 AND data >= $data AND data <= $datafinal ");
                                                                                $res = $db->numRows();

                                                                                if ($res > 100) {
                                                                                        $percent = ($res/100);
                                                                                } else {
                                                                                        $percent = $res;
                                                                                }

                                                                                echo "<label>$res</label>";
                                                                         ?>
                                                                </div>
                                                                <div class="col-md-6 top-content1">
                                                                        <div id="demo-pie-1" class="pie-title-center" data-percent="<?php echo $percent; ?>"> <span class="pie-value"></span> </div>
                                                                </div>
                                                                <div class="clearfix"> </div>
                                                        </div>

                                                </div>
                                                <div class="col-md-4">

                                                        <div class="content-top-1">
                                                                <div class="col-md-6 top-content">
                                                                        <h5>Ped. Ret. na Loja</h5>
                                                                        <?php
                                                                                $db->connect();
                                                                                $db->sql("SELECT * FROM lanc_pedidos WHERE status = 6 AND entregar = 1");
                                                                                $ress = $db->numRows();

                                                                                if ($ress > 100) {
                                                                                        $percent = ($ress/100);
                                                                                } else {
                                                                                        $percent = $ress;
                                                                                }

                                                                                echo "<label>$ress</label>";
                                                                         ?>
                                                                </div>
                                                                <div class="col-md-6 top-content1">
                                                                        <div id="demo-pie-2" class="pie-title-center" data-percent="<?php echo $percent; ?>"> <span class="pie-value"></span> </div>
                                                                </div>
                                                                <div class="clearfix"> </div>
                                                        </div>

                                                </div>

                                                <div class="col-md-4">
                                                        <div class="content-top-1">
                                                                <div class="col-md-6 top-content">
                                                                        <h5>Pedidos Cancelados</h5>
                                                                        <?php
                                                                                $db->connect();
                                                                                $db->sql("SELECT * FROM lanc_pedidos WHERE status = 8");
                                                                                $ress = $db->numRows();

                                                                                if ($ress > 100) {
                                                                                        $percent = ($ress/100);
                                                                                } else {
                                                                                        $percent = $ress;
                                                                                }

                                                                                echo "<label>$percent</label>";
                                                                         ?>
                                                                </div>
                                                                <div class="col-md-6 top-content1">
                                                                        <div id="demo-pie-3" class="pie-title-center" data-percent="<?php echo $ress ?>"> <span class="pie-value"></span> </div>
                                                                </div>
                                                                <div class="clearfix"> </div>
                                                        </div>
                                                </div>

                                                <div class="clearfix"> </div>
                                        </div>

                                </div>
                        </div>
                        <div class="clearfix"> </div>
                </div>
                <!--//content-->



                <!---->
                <div class="copy">
                        <p> &copy; 2016 Minimal. All Rights Reserved | Design by <a href="http://w3layouts.com/" target="_blank">W3layouts</a> </p>
                </div>
        </div>
        <div class="clearfix"> </div>
</div>
</div>
<!---->
<!--scrolling js-->
<script src="js/jquery.nicescroll.js"></script>
<script src="js/scripts.js"></script>
<!--//scrolling js-->
<script src="js/bootstrap.min.js"> </script>
</body>
</html>
