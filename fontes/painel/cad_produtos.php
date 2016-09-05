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
	<script src="js/toastr.min.js"></script>
	<link href="css/toastr.min.css" rel="stylesheet"/>

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
		var checkindis				= $("#indisponivel");

		if (operacao == "editar"){
			$.ajax({
				url:"ajax/produtos/populate_produto.php",
				type:"POST",
				data:"guid="+guid,
				success: function (dados){
					$.each(dados, function(index, dado){
						var guid_categoria		= dado.guid_categoria;
						var descricaoproduto	= dado.descricao;
						var imagemproduto			= dado.imgproduto;
						var subdescricao			= dado.subdescricao;
						var precoproduto			= dado.preco;
						var guid_produto			= dado.guid;
						var indisponivel 			= dado.indisponivel;

						$.ajax({
							url:"ajax/produtos/getCategoria.php",
							type:"POST",
							data:"guid="+guid_categoria,
							success: function (dados){
								$.each(dados, function(index, dado){
									var categorianame = dado.descricao;

									selected.val(guid_categoria);
									selected.html(categorianame);
									campocategoria.find('#option_'+guid_categoria).remove();
								});
							}
						});

						if (indisponivel == 0){
							checkindis.prop( "checked", false );
						} else {
							checkindis.prop( "checked", true );
						}

						$("#imageview").attr('src', imagemproduto);
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
			$("#imageview").attr('src', '');
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
		var checkindis				= $("#indisponivel");

		if (checkindis.is(":checked")) {
			checkindis = 1;
		} else {
			checkindis = 0;
		}

		$.ajax({
			url:"ajax/produtos/produto.php",
			type:"POST",
			data:"descricao="+campodescricao+"&imagem="+campoimagem+"&subdesc="+subdesc+"&categoria="+campocategoria+"&operacao="+operacao+"&guid="+guidd+"&preco="+preco+"&indisponivel="+checkindis,
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

							<div id="modal" class="modal fade bd-example-modal-lg">
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
													<label>GUID</label>
													<input type="text" class="form-control" id="guid" name="guid" placeholder="">
												</fieldset>

												<fieldset class="form-group">
													<label>Categoria</label>
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
													<label>Imagem</label>
													<input type="text" class="form-control" id="img" name="img" placeholder="Diretorio da Imagem">
													<input id="sortpicture" type="file" name="sortpic" accept="image/*" />
													<button type="button" class="btn btn-secondary" id="upload">Upload</button>
												</fieldset>

												<div id="imagefield">
													<img id="imageview" src="" height="200" alt="...">
												</div>

												<fieldset class="form-group">
													<label>Descrição</label>
													<input type="text" class="form-control" id="descricao" name="descricao" placeholder="Descrição/Nome do Produto">
												</fieldset>

												<fieldset class="form-group">
													<label>Subdescrição</label>
													<input type="text" class="form-control" id="subdesc" name="subdesc" placeholder="Subdescrição do Produto">
												</fieldset>

												<fieldset class="form-group">
													<label>Preço</label>
													<input type="text" class="form-control" id="preco" name="preco" placeholder="Preço do Produto">
												</fieldset>

												<fieldset class="form-group">
													<label>Indisponível</label>
													<div class="checkbox">
														<label>
															<input id="indisponivel" type="checkbox"> Marque se o Produto Está Indisponível
														</label>
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
