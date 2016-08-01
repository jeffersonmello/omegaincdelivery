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
		reloadtable();
	})

	function reloadtable(){
		var searchfield = $("#searchBar").val();

		if (searchfield.length < 1) {
			$('#divcat').load('ajax/pedidos/tab_pedidosAbertos.php', function(){
			});
		}
	}

	function dataformatada(data){
    var dia = data.getDate();
    if (dia.toString().length == 1)
      dia = "0"+dia;
    var mes = data.getMonth()+1;
    if (mes.toString().length == 1)
      mes = "0"+mes;
    var ano = data.getFullYear();
    return dia+"/"+mes+"/"+ano;
}

	function openModal(operacao, guid){
		var modall 				= $('#modal');
		var titulomodal		= $("#titulomodal");
		var campoguid			= $("#campoguid");
		var botaoeditar		= $("#botaoatualizar");
		var selected			= $("#optionSelected");

		var inputguid					= $("#guid");
		var campostatus				= $("#cat");
		var campotoken				= $("#token");
		var campodata					= $("#data");
		var campototal				= $("#total");
		var campoendereco			= $("#enderecoo");
		var camponumero				= $("#numero");
		var campobairro				= $("#bairro");
		var camponome					= $("#nome");
		var campotelefone			= $("#telefone");
		var campopagamento		= $("#pagamentoO");
		var campoobs					= $("#obss");


		if (operacao == "editar"){
			$.ajax({
				url:"ajax/pedidos/populate_pedidoAberto.php",
				type:"POST",
				data:"guid="+guid,
				success: function (dados){
					$.each(dados, function(index){
						var guidPedido				= dados[index].guid;
						var statusPedido			= dados[index].status;
						var nomePedido				= dados[index].nome;
						var enderecoPedido		= dados[index].endereco;
						var totalPedido				= dados[index].total;
						var dataPedido				= dados[index].data;
						var telefonePedido		= dados[index].telefone;
						var numerocasaPedido	= dados[index].numero;
						var formaPagamento		= dados[index].formaPagamento;
						var observacaoPedido	= dados[index].observacao;
						var cpfClientePedido	= dados[index].cpf;
						var entregarPedido		= dados[index].entregar;
						var tokenPedido				= dados[index].token;
						var bairroPedido			= dados[index].bairro;
						var pagamentotext 		= 0;

						if (statusPedido == 1) {
							var statuspedidotext = "Processando";
						}

						if (formaPagamento == 0) {
							pagamentotext = "Dinheiro";
						} else {
							pagamentotext = "Cartão/Crédito/Débito";
						}

						totalPedido	= parseFloat(totalPedido).toFixed(2);
						dataPedido	= dataformatada(dataPedido);

						$('#formPedido')[0].reset();
						$('#formEndereco')[0].reset();
						$('#formaPagamento')[0].reset();

						selected.val(statusPedido);
						selected.html(statuspedidotext);
						//totalPedido = (totalPedido.toFixed(2));
						campototal.val(totalPedido);
						campotoken.val(tokenPedido);
						campodata.val(dataPedido);
						campoendereco.val(enderecoPedido);
						camponumero.val(numerocasaPedido);
						campobairro.val(bairroPedido);
						camponome.val(nomePedido);
						campotelefone.val(telefonePedido);
						campopagamento.val(pagamentotext);
						campoobs.val(observacaoPedido);
					})
					titulomodal.html("Pedido #"+guid);
					campoguid.hide();
					botaoeditar.show();
					inputguid.val(guid);
					modall.modal('show');
				}
			});
		}
	}

	function salvar(operacao, guid){
		var campocategoria		= $("#cat").val();
		var campodescricao		= $("#descricao").val();
		var campoimagem				= $("#img").val();
		var subdesc						= $("#subdesc").val();
		var preco							= $("#preco").val();

		$.ajax({
			url:"ajax/produtos/produto.php",
			type:"POST",
			data:"descricao="+campodescricao+"&imagem="+campoimagem+"&subdesc="+subdesc+"&categoria="+campocategoria+"&operacao="+operacao+"&guid="+guid+"&preco="+preco,
			success: function (result){
				alert(campocategoria);
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
		var table = $('#pedidosAbertos').DataTable();

		$('#searchBar').on( 'keyup', function () {
			table.search( this.value ).draw();
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
							<span>Pedidos</span>
						</h2>
					</div>
					<!--//banner-->
					<!--faq-->
					<br>

					<div class="banner">
						<h2>Pedidos Abertos</h2>
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
																<option value="2">Em Produção</option>
																<option value="3">Pronto</option>
																<option value="4">Aguardando Retirada</option>
																<option value="5">Saiu Para Entrega</option>
																<option value="6">Entregue</option>
																<option value="7">Cliente não Estava</option>
																<option value="8">Cancelados</option>
																<option value="9">Devolvido</option>
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
															<label>Observação</label>
															<textarea class="form-control" id="obss" type="text"  disabled rows="3"></textarea>
														</fieldset>

													</form>
												</div>

												<div class="tab-pane" id="itens" role="tabpanel">

												</div>
											</div>


										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
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
