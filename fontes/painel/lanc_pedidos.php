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
$nivel_pagina    = 2;

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
	<title>Omega Inc. | Delivery | Pedidos</title>

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

	<!--pdf -->
	<script src="js/jspdf.min.js"></script>

	<!--Pedidos JS -->
	<script src="js/pedidos/pedidos.js"></script>
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
							<span>Pedidos</span>
							<div class="hidden">
								<audio id="audio"  src="" controls="controls" align=""> </audio>
							</div>
						</h2>
					</div>
					<!--//banner-->
					<!--faq-->
					<br>
					<div class="blank">

						<div class="blank-page">

							<!-- Nav tabs -->
							<ul class="nav nav-tabs" id="Mytab" role="tablist">
								<li class="nav-item">
									<a class="nav-link active" onclick="reloadtableAll();" data-toggle="tab" href="#pedidosabertos" role="tab">Pedidos Abertos</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" onclick="reloadtableAll();" data-toggle="tab" href="#producao" role="tab">Em Produção</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" onclick="reloadtableAll();" data-toggle="tab" href="#prontos" role="tab">Prontos</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" onclick="reloadtableAll();" data-toggle="tab" href="#retirada" role="tab">Aguardando Retirada</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" onclick="reloadtableAll();" data-toggle="tab" href="#saiuentrega" role="tab">Saiu para Entrega</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" onclick="reloadtableAll();" data-toggle="tab" href="#concluidos" role="tab">Concluidos</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" onclick="reloadtableAll();" data-toggle="tab" href="#cancelados" role="tab">Cancelados</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" onclick="reloadtableAll();" data-toggle="tab" href="#devolvidos" role="tab">Devolvidos</a>
								</li>
							</ul>

							<!-- Tab panes -->
							<div class="tab-content">
								<div class="tab-pane active" id="pedidosabertos" role="tabpanel">
									<div class="banner">
										<h2><b>Pedidos Abertos</b></h2>
									</div>

									<div id="divcat">

									</div>
								</div>

								<div class="tab-pane" id="producao" role="tabpanel">
									<div class="banner">
										<h2><b>Pedidos Em Produção </b></h2>
									</div>

									<div id="producaodiv">

									</div>
								</div>

								<div class="tab-pane" id="prontos" role="tabpanel">
									<div class="banner">
										<h2>Prontos <b>Pedido saiu da cozinha, está pronto</b></h2>
									</div>

									<div id="prontoss">

									</div>
								</div>

								<div class="tab-pane" id="retirada" role="tabpanel">
									<div class="banner">
										<h2>Aguardando Retirada <b>Cliente vai vim buscar</b></h2>
									</div>

									<div id="agretirada">

									</div>
								</div>

								<div class="tab-pane" id="saiuentrega" role="tabpanel">
									<div class="banner">
										<h2>Saidos Para Entrega</h2>
									</div>

									<div id="entregadiv">

									</div>
								</div>

								<div class="tab-pane" id="concluidos" role="tabpanel">
									<div class="banner">
										<h2>Entregues</h2>
									</div>

									<div id="entregues">

									</div>
								</div>

								<div class="tab-pane" id="cancelados" role="tabpanel">
									<div class="banner">
										<h2>Cancelados</h2>
									</div>

									<div id="canceladosdiv">

									</div>
								</div>

								<div class="tab-pane" id="devolvidos" role="tabpanel">
									<div class="banner">
										<h2>Devolvidos</h2>
									</div>

									<div id="devolvidosdiv">

									</div>
								</div>

							</div>



							<div id="modal" class="modal fade">
								<div class="modal-dialog" role="document">
									<div class="modal-content">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-label="Close">
												<span aria-hidden="true">&times;</span>
											</button>
											<h4 class="modal-title" id="titulomodal">Pedidos</h4>
										</div>
										<div class="modal-body">

											<!-- Nav tabs -->
											<ul class="nav nav-tabs" role="tablist">
												<li class="nav-item">
													<a class="nav-link active" data-toggle="tab" href="#pedido" role="tab">Pedido</a>
												</li>
												<li class="nav-item">
													<a class="nav-link" data-toggle="tab" href="#endereco" role="tab">Endereço/Cliente</a>
												</li>
												<li class="nav-item">
													<a class="nav-link" data-toggle="tab" href="#pagamento" role="tab">Pagamento/Obs</a>
												</li>
												<li class="nav-item">
													<a class="nav-link" data-toggle="tab" href="#itens" role="tab">Itens do Pedido</a>
												</li>
											</ul>


											<!-- Tab panes -->
											<div class="tab-content">
												<div class="tab-pane active" id="pedido" role="tabpanel">
													<form id="formPedido">
														<fieldset id="campoguid" class="form-group">
															<label>GUID</label>
															<input type="text" class="form-control" id="guid" name="guid" placeholder="">
														</fieldset>

														<fieldset class="form-group">
															<label>Status</label>
															<select class="form-control" id="cat">
																<option id="optionSelected" selected='selected' value=""></option>
																<option id="u2" value="2">Em Produção</option>
																<option id="u3" value="3">Pronto</option>
																<option id="u4" value="4">Aguardando Retirada</option>
																<option id="u5" value="5">Saiu Para Entrega</option>
																<option id="u6" value="6">Entregue</option>
																<option id="u7" value="7">Cliente não Estava</option>
																<option id="u8" value="8">Cancelados</option>
																<option id="u9" value="9">Devolvido</option>
															</select>
															<small class="form-text text-muted">Altere o status do pedido do cliente</small>
														</fieldset>

														<fieldset class="form-group">
															<label>Token</label>
															<input class="form-control" id="token" type="text"  disabled>
															<small class="form-text text-muted">Token para que o cliente consulte o status do pedido</small>
														</fieldset>

														<fieldset class="form-group">
															<label>Data do Pedido</label>
															<input class="form-control" id="data" type="text"  disabled>
														</fieldset>

														<fieldset class="form-group">
															<div class="form-group">
																<label>Valor total do Pedido</label>
																<div class="input-group">
																	<div class="input-group-addon">R$</div>
																	<input class="form-control" id="total" type="text"  disabled>
																</div>
															</div>
														</fieldset>
													</form>
												</div>
												<div class="tab-pane" id="endereco" role="tabpanel">
													<form id="formEndereco">

														<fieldset class="form-group">
															<label>Endereço</label>
															<input class="form-control" id="enderecoo" type="text"  disabled>
														</fieldset>

														<fieldset class="form-group">
															<label>Número</label>
															<input class="form-control" id="numero" type="text"  disabled>
														</fieldset>

														<fieldset class="form-group">
															<label>Bairro</label>
															<input class="form-control" id="bairro" type="text"  disabled>
														</fieldset>

														<fieldset class="form-group">
															<label>Cliente</label>
															<input class="form-control" id="nome" type="text"  disabled>
														</fieldset>

														<fieldset class="form-group">
															<label>Telefone</label>
															<input class="form-control" id="telefone" type="text"  disabled>
														</fieldset>

													</form>
												</div>
												<div class="tab-pane" id="pagamento" role="tabpanel">
													<form id="formaPagamento">

														<fieldset class="form-group">
															<label>Forma de Pagamento</label>
															<input class="form-control" id="pagamentoO" type="text"  disabled>
														</fieldset>

														<fieldset class="form-group">
															<label>Troco para</label>
															<input class="form-control" id="troco" type="text"  disabled>
														</fieldset>

														<fieldset class="form-group">
															<label>Observação</label>
															<textarea class="form-control" id="obss" type="text"  disabled rows="3"></textarea>
														</fieldset>

														<fieldset class="form-group">
															<label>Entregar ?</label>
															<input class="form-control" id="entregarR" type="text"  disabled>
														</fieldset>

													</form>
												</div>

												<div class="tab-pane" id="itens" role="tabpanel">
													<ul id="listprods" class="list-group list-group-alternate">
													</ul>

													<fieldset class="form-group">
														<label>Total</label>
														<input class="form-control" id="total2" type="text"  disabled>
													</fieldset>
												</div>
											</div>


										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
											<button type="button" class="btn btn-secondary" onclick="getprint()">Imprimir</button>
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
