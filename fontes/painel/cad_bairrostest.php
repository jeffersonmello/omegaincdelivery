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
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap4.min.css"/>

	<script src="https://cdn.datatables.net/plug-ins/1.10.12/api/fnReloadAjax.js"></script>
	<script src="https://cdn.datatables.net/plug-ins/1.10.12/api/processing().js"></script>

	<!--Import Google Icon Font-->
	<link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

	<!--MomentJS -->
	<script src="http://momentjs.com/downloads/moment.min.js"></script>

	<!--AccountJS -->
	<script src="js/accounting.min.js"></script>

	<script src="js/bairro/bairro.min.js"></script>
	<script type="text/javascript">
	$(document).ready(function(){
		$.ajax({
			url: "ajax/bairro/bairrojson.php",
			type: "POST",
			success: function(dados){
				var newData = $.map(dados, function(el) { return el });
				var editar = ('"'+'Editar'+'"');

				$('#example').DataTable({
					data: newData,
					columns: [
						{"data": "guid"},
						{"data": "descricao"},
						{"data": "taxaEntrega"},
						{"<button type='button' onclick='openModal("+editar+", guid)' class='btn btn-secondary btn-xs'><i class='material-icons'>mode_edit</i></button> <button type='button' onclick='salvar(3, guid)'class='btn btn-secondary btn-xs'><i class='material-icons'>delete</i></button>"}
					],
					"language": {
						"sEmptyTable": "Nenhum registro encontrado",
						"sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
						"sInfoEmpty": "Mostrando 0 até 0 de 0 registros",
						"sInfoFiltered": "(Filtrados de _MAX_ registros)",
						"sInfoPostFix": "",
						"sInfoThousands": ".",
						"sLengthMenu": "_MENU_ resultados/página",
						"sLoadingRecords": "Carregando...",
						"sProcessing": "Processando...",
						"sZeroRecords": "Nenhum registro encontrado",
						"sSearch": "Pesquisar",
						"oPaginate": {
							"sNext": "Próximo",
							"sPrevious": "Anterior",
							"sFirst": "Primeiro",
							"sLast": "Último"
						},
						"oAria": {
							"sSortAscending": ": Ordenar colunas de forma ascendente",
							"sSortDescending": ": Ordenar colunas de forma descendente"
						}
					},
					"iDisplayLength": 5,
					'sDom': '<"top"f>rt<"bottom"p>i',
					processing: true
				});
			}
		});

		reloadtable();
	})

	function reloadtable(){
		$.ajax({
			url: "ajax/bairro/bairrojson.php",
			type: "POST",
			success: function(dados){
				var newData = $.map(dados, function(el) { return el });
				var editar = ('"'+'Editar'+'"');



			var table =	$('#example').DataTable({
					data: newData,
					columns: [
						{"data": "guid"},
						{"data": "descricao"},
						{"data": "taxaEntrega"},
						{"<button type='button' onclick='openModal("+editar+", guid)' class='btn btn-secondary btn-xs'><i class='material-icons'>mode_edit</i></button> <button type='button' onclick='salvar(3, guid)'class='btn btn-secondary btn-xs'><i class='material-icons'>delete</i></button>"}
					],
					"language": {
						"sEmptyTable": "Nenhum registro encontrado",
						"sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
						"sInfoEmpty": "Mostrando 0 até 0 de 0 registros",
						"sInfoFiltered": "(Filtrados de _MAX_ registros)",
						"sInfoPostFix": "",
						"sInfoThousands": ".",
						"sLengthMenu": "_MENU_ resultados/página",
						"sLoadingRecords": "Carregando...",
						"sProcessing": "Processando...",
						"sZeroRecords": "Nenhum registro encontrado",
						"sSearch": "Pesquisar",
						"oPaginate": {
							"sNext": "Próximo",
							"sPrevious": "Anterior",
							"sFirst": "Primeiro",
							"sLast": "Último"
						},
						"oAria": {
							"sSortAscending": ": Ordenar colunas de forma ascendente",
							"sSortDescending": ": Ordenar colunas de forma descendente"
						}
					},
					"iDisplayLength": 5,
					'sDom': '<"top"f>rt<"bottom"p>i',
					processing: true
				});

				table.processing( true );
			}
		});
	})
	}


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
							<span>Bairros </span>
						</h2>
					</div>
					<!--//banner-->
					<!--faq-->
					<br>

					<div class="banner">
						<h2>Bairros <button type="button" onclick="openModal('Novo',0)" class="btn btn-primary btn-sm pull-right">Novo</button><br></h2>
					</div>

					<div class="blank">

						<div class="blank-page">
							<div id="divcat">
								<table id="example" class="table table-hover table-bordered">
									<thead class="thead-default">
										<tr><th>#</th><th>Descrição</th><th>Taxa de Entrega</th><th>Ações</th>
										</thead>

										<tbody>

										</tbody>
									</table>
								</div>

								<div id="modal" class="modal fade">
									<div class="modal-dialog" role="document">
										<div class="modal-content">
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal" aria-label="Close">
													<span aria-hidden="true">&times;</span>
												</button>
												<h4 class="modal-title" id="titulomodal">Cadastro de Bairros</h4>
											</div>
											<div class="modal-body">
												<form id="formBairro">
													<fieldset id="campoguid" class="form-group">
														<label>GUID</label>
														<input type="text" class="form-control" id="guid" name="guid" placeholder="">
													</fieldset>

													<fieldset id="pesquisabairro" class="form-group">
														<label>Pesquisar Bairro por CEP</label>
														<input style="color: green" type="text" class="form-control" id="cep" name="cep" placeholder="Digite o CEP">
													</fieldset>


													<fieldset class="form-group">
														<label for="exampleInputEmail1">Nome do Bairro</label>
														<input type="text" class="form-control" id="descricao" name="descricao" placeholder="Nome do Bairro">
													</fieldset>

													<fieldset class="form-group">
														<div class="form-group">
															<label for="exampleInputEmail1">Taxa de Entrega</label>
															<div class="input-group">
																<div class="input-group-addon">R$</div>
																<input type="text" onkeypress="replacedot();"  class="form-control" id="preco" name="preco" placeholder="Taxa de Entrega">
															</div>
														</div>
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
