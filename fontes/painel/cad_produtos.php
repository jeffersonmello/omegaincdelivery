<!DOCTYPE HTML>
<?php
ob_start();// Inicia Buffer

session_start(); 	//A seção deve ser iniciada em todas as páginas
if (!isset($_SESSION['usuarioID'])) {		//Verifica se há seções
	session_destroy();						//Destroi a seção por segurança
	header("Location: index.php"); exit;	//Redireciona o visitante para login
}

// Dados do usuario logado
$id_usuario 		= $_SESSION["usuarioID"];
$nome_usuario 	= $_SESSION["nomeUsuario"];
$login_usuario 	= $_SESSION["email"];
$nivel_usuario  = $_SESSION["nivelUsuario"];


// Funções para não exibir alguns erros de conexao
ini_set( 'display_errors', true );
error_reporting(E_ALL & ~ E_NOTICE & ~ E_DEPRECATED);

// Inclui a classe de CRUD mysql
include('class/mysql_crud.php');

// Cria o objeto Database
$db = new Database();
?>
<html>
<head>
	<title>Omega Inc. | Delivery | Produtos</title>

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


	<script>
	$(document).ready(function(){
		reloadtable();
	})

	function reloadtable(){
		var searchfield = $("#searchBar").val();

		if (searchfield.length < 1) {
			$('#divcat').load('ajax/produtos/tab_produtos.php', function(){
				setTimeout(reloadtable, 5000);
			});
		}
	}

	function openModal(operacao, guid){
		var modall 				= $('#modal');
		var titulomodal		= $("#titulomodal");
		var campoguid			= $("#campoguid");
		var botaosalvar		= $("#botaosalvar");
		var botaoeditar		= $("#botaoatualizar");

		var inputguid					= $("#guid");
		var campocategoria		= $("#categoria");
		var campodescricao		= $("#descricao");
		var campoimagem				= $("#img");
		var subdesc						= $("#subdesc");
		var selected					= $("#optionSelected");
		var preco							= $("#preco");

		if (operacao == "editar"){
			$.ajax({
				url:"ajax/produtos/populate_produto.php",
				type:"POST",
				data:"guid="+guid,
				success: function (dados){
					$.each(dados, function(index){
						var guid_categoria		= dados[index].guid_categoria;
						var descricaoproduto	= dados[index].descricao;
						var imagemproduto			= dados[index].imgproduto;
						var subdescricao			= dados[index].subdescricao;
						var precoproduto			= dados[index].preco;
						var guid_produto			= dados[index].guid;

						$.ajax({
							url:"ajax/produtos/getCategoria.php",
							type:"POST",
							data:"guid="+guid_categoria,
							success: function (dados){
								$.each(dados, function(index){
									var categorianame = dados[index].descricao;

									selected.val(guid_categoria);
									selected.html(categorianame);
									campocategoria.find('#option_'+guid_categoria).remove();
								});
							}
						});

						inputguid.val(guid_produto);
						campodescricao.val(descricaoproduto);
						campoimagem.val(imagemproduto);
						subdesc.val(subdescricao);
						preco.val(precoproduto);
					})
					titulomodal.html("Atualizando Produto");
					campoguid.hide();
					botaoeditar.show();
					botaosalvar.hide();
					inputguid.val(guid);
					modall.modal('show');
				}
			});
		} else if (operacao == "salvar") {
			$('#formCategoria')[0].reset();
			titulomodal.html("Novo Produto");
			campoguid.hide();
			botaoeditar.hide();
			botaosalvar.show();
			selected.hide();
			modall.modal('show');
		}
	}

	function salvar(operacao, guid){
		var campocategoria		= $("#cat").val();
		var campodescricao		= $("#descricao").val();
		var campoimagem				= $("#img").val();
		var subdesc						= $("#subdesc").val();
		var preco							= $("#preco").val();
		var guidd 						= $("#guid").val();

		$.ajax({
			url:"ajax/produtos/produto.php",
			type:"POST",
			data:"descricao="+campodescricao+"&imagem="+campoimagem+"&subdesc="+subdesc+"&categoria="+campocategoria+"&operacao="+operacao+"&guid="+guidd+"&preco="+preco,
			success: function (result){
				$('#modal').modal('hide');
				if (result == 1) {
					toastr.success('Registro Deletado com Sucesso', 'OK')
				} else {
					toastr.success('Registro Salvo com Sucesso', 'OK')
				}
				reloadtable();
			}
		})
	}

	function search(){
		var table = $('#produtos').DataTable();

		$('#searchBar').on( 'keyup', function () {
			table.search( this.value ).draw();
		})
	}

	function previewFile(){
		var preview = document.querySelector('img[id=imageview]');
		var file    = document.querySelector('input[type=file]').files[0]; //sames as here
		var reader  = new FileReader();
		var imgee		= document.querySelector('input[type=file]').files[0]['name'];

		reader.onloadend = function () {
			preview.src = reader.result;
		}

		if (file) {
			reader.readAsDataURL(file);
		} else {
			preview.src = "";
			$("#img").val(imgee);
		}
	}

	$(function () {
		$('#supported').text('Supported/allowed: ' + !!screenfull.enabled);

		if (!screenfull.enabled) {
			return false;
		}



		$('#toggle').click(function () {
			screenfull.toggle($('#container')[0]);
		});



	});
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
						<input id="searchBar" type="text"  onkeyup="search()" placeholder="Pesquisar...">
						<i class="fa fa-search"></i>
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
						<ul class="nav" id="side-menu">
							<ul class="nav" id="side-menu">

								<li>
									<a href="dashboard.php" class=" hvr-bounce-to-right"><i class="fa fa-dashboard nav_icon "></i><span class="nav-label">Dashboards</span> </a>
								</li>

								<li>
									<a href="#" class=" hvr-bounce-to-right"><i class="fa fa-plus-square nav_icon"></i> <span class="nav-label">Cadastros</span><span class="fa arrow"></span></a>
									<ul class="nav nav-second-level">
										<li><a href="cad_categoria.php" class=" hvr-bounce-to-right"> <i class="fa fa-indent nav_icon"></i>Cadastro de Categorias</a></li>

										<li><a href="cad_produtos.php" class=" hvr-bounce-to-right"><i class="fa fa-indent nav_icon"></i>Cadastro de Produtos</a></li>

										<li><a href="cad_usuarios.php" class=" hvr-bounce-to-right"><i class="fa fa-indent nav_icon"></i>Cadastro de Usuários</a></li>

										<li><a href="cad_empresa.php" class=" hvr-bounce-to-right"><i class="fa fa-indent nav_icon"></i>Empresa</a></li>
									</ul>
								</li>

								<li>
									<a href="#" class=" hvr-bounce-to-right"><i class="fa fa-paper-plane nav_icon"></i> <span class="nav-label">Lançamentos</span><span class="fa arrow"></span></a>
									<ul class="nav nav-second-level">
										<li><a href="lanc_pedidos.php" class=" hvr-bounce-to-right"> <i class="fa fa-indent nav_icon"></i>Pedidos</a></li>

										<li><a href="lanc_pedidos.php" class=" hvr-bounce-to-right"> <i class="fa fa-indent nav_icon"></i>Abrir/Fechar Estabelecimento</a></li>
									</ul>
								</li>

								<li>
									<a href="#" class=" hvr-bounce-to-right"><i class="fa fa-cog nav_icon"></i> <span class="nav-label">Settings</span><span class="fa arrow"></span></a>
									<ul class="nav nav-second-level">
										<li><a href="sair.php" class=" hvr-bounce-to-right"><i class="fa fa-sign-out nav_icon"></i>Sair</a></li>
									</ul>
								</li>
							</ul>
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
							<span>Cadastro de Produtos</span>
						</h2>
					</div>
					<!--//banner-->
					<!--faq-->
					<br>

					<div class="banner">
						<h2>Produtos <button type="button" onclick="openModal('salvar',0)" class="btn btn-primary btn-sm pull-right">Novo</button><br></h2>
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
											<h4 class="modal-title" id="titulomodal">Cadastro de Produtos</h4>
										</div>
										<div class="modal-body">
											<form id="formCategoria">
												<fieldset id="campoguid" class="form-group">
													<label for="exampleInputEmail1">GUID</label>
													<input type="text" class="form-control" id="guid" name="guid" placeholder="">
												</fieldset>

												<fieldset class="form-group">
													<label for="exampleInputEmail1">Categoria</label>
													<select class="form-control" id="cat">
														<?php
														$db->connect();
														$db->select('cad_categorias');
														$res = $db->getResult();
														foreach ($res as $output) {
															$guid_categoria		= $output["guid"];
															$desc_categoria		= $output["descricao"];

															echo "<option id='option_'+$guid_categoria' value='$guid_categoria'>$desc_categoria</option>";
														}
														?>
														<option id="optionSelected" selected='selected' value=""></option>
													</select>
												</fieldset>

												<fieldset class="form-group">
													<label for="exampleInputEmail1">Imagem</label>
													<input type="text" class="form-control" id="img" name="img" placeholder="Diretorio da Imagem">
													<label class="custom-file">
														<input type="file" id="imgfile" onchange="previewFile()" class="custom-file-input">
														<span class="custom-file-control"></span>
													</label>
												</fieldset>
												<div id="imagefield">
													<img id="imageview" src="" height="200" alt="Image preview...">
												</div>
												<fieldset class="form-group">
													<label for="exampleInputEmail1">Descrição</label>
													<input type="text" class="form-control" id="descricao" name="descricao" placeholder="Descrição/Nome do Produto">
												</fieldset>

												<fieldset class="form-group">
													<label for="exampleInputEmail1">Subdescrição</label>
													<input type="text" class="form-control" id="subdesc" name="subdesc" placeholder="Subdescrição do Produto">
												</fieldset>

												<fieldset class="form-group">
													<label for="exampleInputEmail1">Preço</label>
													<input type="text" class="form-control" id="preco" name="preco" placeholder="Preço do Produto">
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
