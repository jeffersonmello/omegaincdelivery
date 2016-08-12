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

	<!--MomentJS -->
	<script src="http://momentjs.com/downloads/moment.min.js"></script>

	<!--AccountJS -->
	<script src="js/accounting.min.js"></script>

	<!--pdf -->
	<script src="js/jspdf.min.js"></script>

	<script type="text/javascript">
	$(document).ready(function(){
		reloadtable();
		reloadtable();
		verificaNovoPedido();
	})

	function reloadtable(){
		$('#divcat').load('ajax/pedidos/tab_pedidosAbertos.php', function(){});
		$('#producaodiv').load('ajax/pedidos/tab_pedidosEmProducao.php', function(){});
		$('#entregadiv').load('ajax/pedidos/tab_pedidosEntrega.php', function(){});
		$('#entregues').load('ajax/pedidos/tab_pedidosEntregues.php', function(){});
	}


	document.addEventListener('DOMContentLoaded', function () {
		if (Notification.permission !== "granted")
		Notification.requestPermission();
	});

	function inprimir(guid){
		$.ajax({
			url: "ajax/pedidos/populate_pedidoAberto.php",
			type: "POST",
			data: "guid="+guid,
			success: function (dados){
				$.each(dados, function(index, dado){
					var guidPedido				= dado.guid;
					var statusPedido			= dado.status;
					var nomePedido				= dado.nome;
					var enderecoPedido		= dado.endereco;
					var totalPedido				= dado.total;
					var dataPedido				= dado.data;
					var telefonePedido		= dado.telefone;
					var numerocasaPedido	= dado.numero;
					var formaPagamento		= dado.formaPagamento;
					var observacaoPedido	= dado.observacao;
					var cpfClientePedido	= dado.cpf;
					var entregarPedido		= dado.entregar;
					var tokenPedido				= dado.token;
					var bairroPedido			= dado.bairro;
					var pagamentotext 		= 0;

					if (statusPedido == 1) {
						var statuspedidotext = "Processando";
					}

					if (formaPagamento == 0) {
						pagamentotext = "Dinheiro";
					} else {
						pagamentotext = "Cartão/Crédito/Débito";
					}

					totalPedido	= accounting.formatMoney(totalPedido, "", 2, ".", ",");
					//totalPedido	= parseFloat(totalPedido).toFixed(2);
					dataPedido	= moment(dataPedido).format('DD/MM/YYYY');

					var doc = new jsPDF();

					doc.setFontSize(8);
					doc.text(70, 10, ("Número Pedido: "+guidPedido));
					doc.text(70, 14, ("Cliente: "+nomePedido));
					doc.text(70, 18, ("Endereco: "+enderecoPedido));
					doc.text(70, 22, ("Número: "+numerocasaPedido));
					doc.text(70, 26, ("Telefone: "+telefonePedido));
					doc.text(70, 30, ("Pagamento: "+pagamentotext));
					doc.text(70, 34, ("--------------------------------------------------------------------------"));


					$.ajax({
						url:"ajax/pedidos/addprodstolist.php",
						type:"POST",
						data:"pedido="+guidPedido,
						success: function (dados){
							var linha				= 34;

							$.each(dados, function(index, dado){
								var preco				= dado.valorproduto;

								preco = accounting.formatMoney(preco, "R$ ", 2, ".", ",");

								linha = linha + 4;
								doc.text(70, (linha), ((dado.nomeproduto)+" | Preço: "+preco));
							});

							doc.text(70, (linha+4), ("--------------------------------------------------------------------------"));
							doc.text(70, (linha+8), ("Total do Pedido: R$ "+ totalPedido));
							var string = doc.output('dataurlnewwindow');
						},
						error: function(XMLHttpRequest, textStatus, errorThrown) {
							alert("Status: " + textStatus); alert("Error: " + errorThrown);
							console.log(arguments);
						}
					});
					//doc.save('Test.pdf');
				});
			}
		})
	}

	function showAll(){
		$("#u2").show();
		$("#u3").show();
		$("#u4").show();
		$("#u5").show();
		$("#u6").show();
		$("#u7").show();
		$("#u8").show();
		$("#u9").show();
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
		var campototal2				= $("#total2");


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
							var statuspedidotext = "Processando"
						} else if (statusPedido == 2) {
							statuspedidotext = "Em Produção";
							showAll();
							$("#u2").hide();
						} else if (statusPedido == 3) {
							statuspedidotext = "Pronto";
							showAll();
							$("#u3").hide();
						} else if (statusPedido == 4 ) {
							statuspedidotext = "Aguardando para busca";
							showAll();
							$("#u4").hide();
						} else if (statusPedido == 5) {
							statuspedidotext = "Saiu para Entrega";
							showAll();
							$("#u5").hide();
						} else if (statusPedido == 6) {
							statuspedidotext = "Entegue";
							showAll();
							$("#u6").hide();
						} else if (statusPedido == 7) {
							statuspedidotext = "Cliente não estava";
							showAll();
							$("#u7").hide();
						} else if (statusPedido == 8) {
							statuspedidotext = "Cancelado";
							showAll();
							$("#u8").hide();
						} else if (statusPedido == 9) {
							statuspedidotext = "Devolvido";
							showAll();
							$("#u9").hide();
						}

						if (formaPagamento == 0) {
							pagamentotext = "Dinheiro";
						} else {
							pagamentotext = "Cartão/Crédito/Débito";
						}

						totalPedido	= accounting.formatMoney(totalPedido, "", 2, ".", ",");
						//totalPedido	= parseFloat(totalPedido).toFixed(2);
						dataPedido	= moment(dataPedido).format('DD/MM/YYYY');

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
						campototal2.val(totalPedido);
					})
					titulomodal.html("Pedido #"+guid);
					campoguid.hide();
					botaoeditar.show();
					inputguid.val(guid);
					addProdtolist(guid, (campobairro.val()));
					modall.modal('show');
				}
			});
		}
	}

	function notifyMe(pedido) {
		if (!Notification) {
			alert('Desktop notifications not available in your browser. Try Chromium.');
			return;
		}

		if (Notification.permission !== "granted")
		Notification.requestPermission();
		else {
			var notification = new Notification(('Novo Pedido #'+pedido), {
				icon: 'https://cdn0.iconfinder.com/data/icons/shop-payment-vol-4/128/shop-65-512.png',
				body: ("Novo Pedido Realiazdo Número: "+pedido),
			});

			var player = document.getElementById('audio');
			var musica = "http://mobile.kingofeletro.com.br/android/painel/sound.mp3";

			player.src = musica;
			player.play();

			notification.onclick = function () {
				window.focus()
			};
			toastr.options = {
				"closeButton": true,
				"debug": true,
				"newestOnTop": true,
				"progressBar": true,
				"positionClass": "toast-top-right",
				"preventDuplicates": true,
				"onclick": null,
				"showDuration": "300",
				"hideDuration": "1000",
				"timeOut": "60000",
				"extendedTimeOut": "1000",
				"showEasing": "swing",
				"hideEasing": "linear",
				"showMethod": "fadeIn",
				"hideMethod": "fadeOut"
			};
			toastr["info"]("Pedido Recebido para visualizar clique <div><button type='button' onclick='openModal(\"editar\", "+pedido+");' class='btn btn-outline-info btn-sm'>Aqui</button>", "Novo Pedido")
		}
	}

	function salvar(operacao, guid){
		var categoria 			  = $("#cat").val();
		var guidupdate				= $("#guid").val();

		$.ajax({
			url:"ajax/pedidos/pedido.php",
			type:"POST",
			data: "guidupdate="+guidupdate+"&categoria="+categoria+"&operacao="+operacao,
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

	var timeout = setTimeout(verificaNovoPedido, 2000);
	function verificaNovoPedido(){
		$.ajax({
			url: "ajax/pedidos/verificaNovoPedido.php",
			type: "POST",
			success: function (dados){
				if (dados != 0){
					notifyMe(dados);
					reloadtable();
				}
			}
		})
		timeout = setTimeout(verificaNovoPedido, 2000);
	}

	function mesmo(guidProd, guidPedido){
		var campqtde =  $("#qtdeListProd_"+guidProd);

		$.ajax({
			url: "ajax/pedidos/mesmo.php",
			type: "POST",
			data: "produto="+guidProd+"&pedido="+guidPedido,
			success: function (dados){
				campqtde.html(dados);
			}
		})
	}

	// Função que retorna o valor da taxa de entrega
	function getBairroTax(bairro){
		var listaprodutos = $("#listprods");

		$.ajax({
			url:	"ajax/pedidos/taxa.php",
			type:	"POST",
			data: "bairro="+bairro,
			success: function (dados){
				var taxa = dados;

				taxa = accounting.formatMoney(taxa, "R$ ", 2, ".", ",");
				listaprodutos.append(' <li class="list-group-item">Taxa de Entrega: '+taxa+' </li>');
			}
		})

	}

	// Função que adiciona os produtos na lista do modal
	// guidPedido: Número do pedido para identificar os produtos vinculados a ele
	// TODO: falta adicionar como ultimo li a taxa de entrega, para retornar a taxa de entrega deve se criar outra função
	// getBairroTax
	function addProdtolist(guidPedido, bairro){
		var listaprodutos = $("#listprods");

		$.ajax({
			url:"ajax/pedidos/addprodstolist.php",
			type:"POST",
			data:"pedido="+guidPedido,
			success: function (dados){
				listaprodutos.empty();

				$.each(dados, function(index){
					var len    			= dados.length;
					var preco				= dados[index].valorproduto;

					preco = accounting.formatMoney(preco, "R$ ", 2, ".", ",");


					for (var i=0; i <= len; i++){
						var guidprod		= dados[index].guidproduto;
						var currentiten = $("#idLIProd_"+guidprod);

						if ($(currentiten, listaprodutos).length){
							mesmo(guidprod, guidPedido)
						} else {
							listaprodutos.append('<li id="idLIProd_'+guidprod+'" class="list-group-item">'+dados[index].nomeproduto+' | Preço: '+preco+'<span class="badge" id="qtdeListProd_'+guidprod+'" >1</span></li>');
						}
					}
				});
				getBairroTax(bairro);
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				alert("Status: " + textStatus); alert("Error: " + errorThrown);
				console.log(arguments);
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
							<ul class="nav nav-tabs" role="tablist">
								<li class="nav-item">
									<a class="nav-link active" onclick="reloadtable();" data-toggle="tab" href="#pedidosabertos" role="tab">Pedidos Abertos</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" onclick="reloadtable();" data-toggle="tab" href="#producao" role="tab">Em Produção</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" onclick="reloadtable();" data-toggle="tab" href="#saiuentrega" role="tab">Saiu para Entrega</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" onclick="reloadtable();" data-toggle="tab" href="#concluidos" role="tab">Concluidos</a>
								</li>
							</ul>

							<!-- Tab panes -->
							<div class="tab-content">
								<div class="tab-pane active" id="pedidosabertos" role="tabpanel">
									<div class="banner">
										<h2>Pedidos Abertos <b>Para Entregar</b></h2>
									</div>

									<div id="divcat">

									</div>
								</div>
								<div class="tab-pane" id="producao" role="tabpanel">
									<div class="banner">
										<h2>Pedidos Em Produção <b>Para Entregar</b></h2>
									</div>

									<div id="producaodiv">

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
															<label>Observação</label>
															<textarea class="form-control" id="obss" type="text"  disabled rows="3"></textarea>
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
