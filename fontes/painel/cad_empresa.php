<!DOCTYPE HTML>
<?php
ob_start();

session_start();
if (!isset($_SESSION['usuarioID'])) {
	session_destroy();
	header("Location: index.php"); exit;
}

// Dados do usuario logado
$id_usuario 		= $_SESSION["usuarioID"];
$nome_usuario 	= $_SESSION["nomeUsuario"];
$login_usuario 	= $_SESSION["email"];
$nivel_usuario  = $_SESSION["nivelUsuario"];



ini_set( 'display_errors', true );
error_reporting(E_ALL & ~ E_NOTICE & ~ E_DEPRECATED);


include('class/mysql_crud.php');


$db = new Database();
?>
<html>
<head>
	<title>Omega Inc. | Delivery | Empresa</title>

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
	<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
	<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet"/>

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

	<script src="js/empresa/empresas.min.js"></script>

	<script type="text/javascript">
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
					</section>

					<div class=" navbar-left-right">

					</div>
					<div class="clearfix"> </div>
				</div>


				<!-- Brand and toggle get grouped for better mobile display -->

				<!-- Collect the nav links, forms, and other content for toggling -->
				<div class="drop-men" >
					<ul class=" nav_1">

						<li class="dropdown">
							<a href="#" class="dropdown-toggle dropdown-at" data-toggle="dropdown"><span class=" name-caret">Rackham<i class="caret"></i></span><img src="images/wo.jpg"></a>
							<ul class="dropdown-menu " role="menu">
								<li><a href="profile.html"><i class="fa fa-user"></i>Edit Profile</a></li>
								<li><a href="inbox.html"><i class="fa fa-envelope"></i>Inbox</a></li>
								<li><a href="calendar.html"><i class="fa fa-calendar"></i>Calender</a></li>
								<li><a href="inbox.html"><i class="fa fa-clipboard"></i>Tasks</a></li>
							</ul>
						</li>

					</ul>
				</div><!-- /.navbar-collapse -->
				<div class="clearfix">

				</div>

				<div  class="navbar-default sidebar" role="navigation">
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
							<span>Empresas </span>
						</h2>
					</div>
					<!--//banner-->
					<!--faq-->
					<br>

					<div class="banner">
						<h2>Empresas <button type="button" onclick="openModal('Novo',0)" class="btn btn-primary btn-sm pull-right">Novo</button><br></h2>
					</div>

					<div class="blank">

						<div class="blank-page">
							<div id="divcat">

							</div>

							<div id="modal" class="modal fade">
								<div class="modal-dialog" role="document">
									<div class="modal-content">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-label="Close">
												<span aria-hidden="true">&times;</span>
											</button>
											<h4 class="modal-title" id="titulomodal">Cadastro de Empresa</h4>
										</div>
										<div class="modal-body">
											<form id="formEmpresas">

												<fieldset id="campoguid" class="form-group">
													<label>GUID</label>
													<input type="text" class="form-control" id="guid" name="guid" placeholder="">
												</fieldset>


												<fieldset class="form-group">
													<label>Nome da Empresa</label>
													<input type="text" class="form-control" id="nome" name="nome" placeholder="Nome da Empresa">
												</fieldset>

												<fieldset class="form-group">
													<label>CNPJ</label>
													<input type="text" onkeyup="formatacnpj();" maxlength="18" class="form-control" id="cnpj" name="cnpj">
												</fieldset>

												<fieldset class="form-group">
													<label>Inscrição Estadual</label>
													<input type="text" class="form-control" maxlength="20" id="inscestd" name="inscesdt">
												</fieldset>

												<fieldset class="form-group">
													<label>Telefone</label>
													<input type="text" class="form-control" id="telefone" name="telefone" placeholder="Telefone da Empresa">
												</fieldset>

												<fieldset class="form-group">
													<label>E-mail</label>
													<input type="email" class="form-control" id="email" name="email" placeholder="E-mail da empresa">
												</fieldset>

												<fieldset class="form-group">
													<label>Endereço</label>
													<input type="text" class="form-control" id="endereco" name="endereco" placeholder="Endereço da empresa">
												</fieldset>

												<fieldset class="form-group">
													<label>Empresa Padrão</label>
													<select class="form-control" id="padrao">
														<option id="selectedOption" selected></option>
														<option id="u1" value="1">Sim</option>
														<option id="u2" value="2">Não</option>
													</select>
												</fieldset>

											</form>
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
											<button id="botaosalvar" type="button" onclick="salvar(1, 0)"class="btn btn-primary" hidden="">Salvar</button>
											<button id="botaoatualizar" type="button" onclick="salvar(2, 0)"class="btn btn-primary" hidden="">Salvar</button>
										</div>
									</div><!-- /.modal-content -->
								</div><!-- /.modal-dialog -->
							</div><!-- /.modal -->



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
