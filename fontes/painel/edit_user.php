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

// Funções para não exibir alguns erros de conexao
ini_set( 'display_errors', true );
error_reporting(E_ALL & ~ E_NOTICE & ~ E_DEPRECATED);
?>
<html>
<head>
	<title>Omega Inc. | Delivery | Bairros</title>

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
	<script src="js/bootstrap.min.js"> </script>

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

	<!--DataTables-->
	<script src="https://cdn.datatables.net/1.10.11/js/jquery.dataTables.min.js"></script>
	<script src="https://cdn.datatables.net/1.10.11/js/dataTables.bootstrap.min.js"></script>

	<script src="https://cdn.datatables.net/plug-ins/1.10.12/api/fnReloadAjax.js"></script>

	<!--Import Google Icon Font-->
	<link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

	<!--MomentJS -->
	<script src="http://momentjs.com/downloads/moment.min.js"></script>

	<!--AccountJS -->
	<script src="js/accounting.min.js"></script>

	<script src="js/usuario/profile.min.js"></script>
	<script type="text/javascript">
	$(document).ready(function(){
		$("#campoguid").hide();
	})

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

					<div class=" navbar-left-right">
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
					</div>
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

				<div  class="navbar-default sidebar" role="navigation">
					<div class="sidebar-nav navbar-collapse">
						<ul class="nav" id="side-menu">
							<?php
							include('class/menu.php');
							?>
						</ul>
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
							<span>Meu perfil </span>
						</h2>
					</div>
					<!--//banner-->
					<!--faq-->
					<br>

					<div class="banner">
						<h2>Meu Perfil</h2>
					</div>

					<div class="blank">

						<div class="blank-page">
							<!--grid-->
							<div class="grid-form">
								<div class="grid-form1">
									<h3 id="forms-example" class="">Meu Perfil</h3>
									<form id="formUsuarios">

										<fieldset id="campoguid" class="form-group">
											<label>GUID</label>
											<input type="text" value="<?php echo $id_usuario?>" class="form-control" id="guid" name="guid" placeholder="">
										</fieldset>

										<fieldset class="form-group">
											<label>Imagem</label>
											<input type="text" class="form-control" id="img" name="img" placeholder="Diretorio da Imagem">
											<input id="sortpicture" type="file" name="sortpic" accept="image/*" />
											<button type="button" class="btn btn-secondary" id="upload">Upload</button>
										</fieldset>

										<div id="imagefield">
											<img id="imageview" src="<?php echo $imagem_usuario ?>" height="200" alt="...">
										</div>


										<fieldset class="form-group">
											<label>Usuário</label>
											<input type="text" value="<?php echo $login_usuario ?>" class="form-control" id="usuario" name="usuario" placeholder="Nome de usuário para acesso" disabled>
										</fieldset>


										<fieldset class="form-group">
											<label>Nome</label>
											<input type="text" value="<?php echo $nome_usuario?>" class="form-control" id="nome"  placeholder="Seu Nome">
										</fieldset>

										<button id="botaoatualizar" type="button" onclick="salvar(2, 0)"class="btn btn-primary" hidden="">Salvar</button>
									</form>
								</div>
								<!----->

								<script type="text/javascript">
								$('#upload').on('click', function() {
									var file_data = $('#sortpicture').prop('files')[0];
									var form_data = new FormData();
									form_data.append('file', file_data);
									$.ajax({
										url: 'ajax/uploadFile.php',
										dataType: 'text',
										cache: false,
										contentType: false,
										processData: false,
										data: form_data,
										type: 'post',
										success: function(php_script_response){
											$("#imageview").attr('src', php_script_response);
											$("#img").val(php_script_response);
										}
									});
								});
								</script>

								<div class="copy">
									<p> &copy; 2016 Omega Inc. All Rights Reserved | Design by <a href="http://w3layouts.com/" target="_blank">W3layouts</a> </p>	    </div>
								</div>


							</div>
							<div class="clearfix"> </div>
						</div>

						<!---->
						<!--scrolling js-->
						<script src="js/jquery.nicescroll.js"></script>
						<script src="js/scripts.js"></script>
						<!--//scrolling js-->
					</body>
					</html>
