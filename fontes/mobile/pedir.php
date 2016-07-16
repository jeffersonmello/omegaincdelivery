<?php
ob_start();

ini_set( 'display_errors', true );
error_reporting(E_ALL & ~ E_NOTICE & ~ E_DEPRECATED);

session_start();
if (!isset($_SESSION['idBairro'])) {
        session_destroy();
       	header("Location: index.php"); exit;
}

setlocale(LC_MONETARY,"pt_BR", "ptb");

$taxa         = $_SESSION['taxaentrega'];
$nome_cliente = $_SESSION['nomecliente'];
$email_cliente= $_SESSION['emailcliente'];
$guid_bairro  = $_SESSION['idBairro'];
$guid_pedido  = $_SESSION['idPedido'];


include('class/mysql_crud.php');


$db = new Database();
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

    <script type="text/javascript">
    $(document).ready(function(){
      totaliza();

      $("#search").on( 'keyup', function () {
        var pesquisa      = $("#search").val();
        var categorias    = $(".categorias"); // Lista de categorias
        var produtos      = $("#listaprodutos");
        var itemproduto   = $(".itempesquisa");

        categorias.hide();

        $(itemproduto, produtos).remove();

        $.ajax({
          url:("ajax/buscaprods.php"),
          type: "POST",
          data: "busca="+pesquisa,
          success:function(dados){
            $.each(dados, function(index){
              var len    = dados.length;
              for (var i=0; i < len; i++){
                var imageem     = ('"'+dados[index].imgproduto+'"');
                var descricaao  = ('"'+dados[index].descricao+'"');
                produtos.append("<li id='itempesquisa_'"+i+"' class='item-content itempesquisa'><img src='"+dados[index].imgproduto+"' width='44'></div><div class='item-inner'><div class='item-title-row'><div class='item-title'>"+dados[index].descricao+"</div><div class='item-after'><span href='#' onclick='adicionarCarrinho("+dados[index].guid+","+descricaao+","+dados[index].preco+","+imageem+")' class='button'><i class='material-icons color-icon'>add</i></span></div></div><div class='item-subtitle'>R$ "+dados[index].preco+"</div></div></li>");
              }
            });
          }})
      })


      $("#search").on( 'blur', function () {
        var categorias    = $(".categorias"); // Lista de categorias
        var produtos      = $("#listaprodutos");
        var itemproduto   = $(".itempesquisa");
        var pesquisas = $('#search').val();

        if (pesquisas.length < 1) {
          $(itemproduto, produtos).remove();
          categorias.show();
        }
      })
    })

    function mesmo(guid,valor){
      $.ajax({
        url:("ajax/mesmoitem.php"),
        type: "POST",
        data: "guidprod="+guid+"&guidpedido="+<?php echo $guid_pedido; ?>,
        success:function(dados){
          $("#idvaloresqtde_"+guid).html("R$ "+(valor.toFixed(2))+" ("+dados+")");
          totaliza();
        }})
    }

    function totaliza(){
      $.ajax({
        url:("ajax/totaliza.php"),
        type: "POST",
        data: "guidpedido="+<?php echo $guid_pedido; ?>+"&taxa="+<?php echo $taxa; ?>,
        success:function(dados){
          var valor = dados;
          $("#total").html("R$ "+(valor.toFixed(2)));
        }})
      }

    function adicionarCarrinho(guid, nome, preco, imagem){
      var currentiten   = $("#listacarrinho_"+guid);
      var listacarrinho = $("#teste");
      var total         = $("#total");

      $.ajax({
        url:("ajax/adicionacarrinho.php"),
        type: "POST",
        data: "guidprod="+guid+"&guidpedido="+<?php echo $guid_pedido; ?>,
        success:function(dados){
          if ($(currentiten, listacarrinho).length){
            mesmo(guid,preco);
          } else {
            if (dados == 1){
              listacarrinho.append("<li id='listacarrinho_"+guid+"'><div class='item-content'><div class='item-media'> <i class='icon my-icon'><img src='"+imagem+"' width='44'></i></div><div class='item-inner'><div class='item-title'>"+nome+"</div><div id='idvaloresqtde_"+guid+"' class='item-after'>R$ "+(preco.toFixed(2))+" (1)</div></div></div></li>");
              totaliza();
            }
            }
        }})
    }



    </script>
  </head>
  <body>
    <!-- Status bar overlay for fullscreen mode-->
    <div class="statusbar-overlaply"></div>
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
            <div class="right"><a href="#" class="link icon-only"><i class="material-icons">arrow_forward</i></a></div>
          </div>
        </div>

        <!-- Pages, because we need fixed-through navbar and toolbar, it has additional appropriate classes-->
        <div class="pages navbar-through toolbar-through">
          <!-- Page, data-page contains page name-->
          <div data-page="index" class="page">
            <!-- Scrollable page content -->
            <div class="page-content">
              <br>
              <br>
              <br>
              <!-- Inicio do Conteudo -->

              <div class="card demo-card-header-pic">
                <div style="background-image:url(http://www.pizzaaltafrequencia.com.br/content/img/Logo_Delivery.png)" valign="bottom" class="card-header color-white no-border">Delivery Nome da Loja</div>
                <div class="card-content">
                  <div class="card-content-inner">

                    <div><iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3644.437152806504!2d-52.35454038490684!3d-24.015644884410168!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x94ed0b28ee9a9359%3A0x1c5dee983eeb3986!2sAv.+Bronislav+Wronski+-+Jardim+Aeroporto%2C+Campo+Mour%C3%A3o+-+PR%2C+87310-300!5e0!3m2!1spt-BR!2sbr!4v1459362655860" width="100%" height="150" frameborder="0" style="border:0"></iframe></div>

                  </div>
                </div>
              </div>

              <!-- Carrinho -->
              <div class="card">
                <div class="card-header"><i class="material-icons color-icon">shopping_cart</i> Meu Carrinho</div>
                <div class="card-content">
                  <div class="card-content-inner">
                    <?php
                      $taxaM = money_format('%n', $taxa);
                     ?>
                      Número do Pedido: <b><?php echo $guid_pedido?></b><br>
                      Taxa de Entrega: <b><?php echo $taxaM ?></b><br>
                      Total:  <b id="total"></b><br>
                  </div>

                  <div class="list-block">
                      <ul id="teste">
                      </ul>
                  </div>

                  </div>
                </div>

              <!-- Produtos -->
              <div class="card">
                <div class="card-header"><i class="fa fa-archive color-icon" aria-hidden="true"></i> Selecione seu pedido</div>
                <div class="card-content">
                  <div class="list-block accordion-list">
                      <ul>

                        <li class="item-content">
                          <div class="item-media"><i class="material-icons color-icon">search</i></div>
                          <div class="item-inner">
                            <div class="item-input">
                              <input type="text" id="search" name="search" placeholder="Buscar...">
                            </div>
                          </div>
                        </li>

                        <div class="list-block media-list">
                        <ul id="listaprodutos">
                        </ul>
                        </div>

              <?php
              $db->connect();
              $db->sql("SELECT * FROM cad_categorias");
              $res = $db->getResult();
              foreach($res as $output)
              {
                $guid_categoria = $output["guid"];
                $iconecategoria = $output["iconecategoria"];
                $nome_categoria = $output["descricao"];

                echo '<li class="accordion-item categorias">'
                     ,'<a href="" class="item-link item-content">'
                     ,'<div class="item-inner">';
                echo "<div class='item-title'>$iconecategoria $nome_categoria</div>";
                echo '</div>'
                    ,'</a>'
                    ,'<div class="accordion-item-content">'
                    ,'<div class="list-block media-list">'
                    ,'<ul>';

                  $db->connect();
                  $db->sql("SELECT a.guid as guidcategoria,
	                                 a.descricao as descricaocategoria,
	                                 a.iconecategoria as icone,
                                   b.guid as guidprod,
                                   b.guid_categoria,
	                                 b.imgproduto as imagem,
	                                 b.descricao as produto,
	                                 b.preco
                                   FROM cad_categorias AS a
                                   INNER JOIN cad_produtos AS b
                                   ON a.guid = b.guid_categoria WHERE a.guid = $guid_categoria");
                  $res = $db->getResult();
                  foreach($res as $output)
                  {
                    $categoria_guid           = $output["guidcategoria"];
                    $produto_guid             = $output["guidprod"];
                    $produto_imagem           = $output["imagem"];
                    $produto_nome             = $output["produto"];
                    $produto_preco            = $output["preco"];

                    $preco_value = $produto_preco;

                    $produto_preco = money_format('%n', $produto_preco);

                    echo '<li class="item-content">'
                        ,'<div class="item-media">';
                    echo "<img src='$produto_imagem' width='44'>";
                    echo '</div>'
                        ,'<div class="item-inner">'
                        ,'<div class="item-title-row">';
                   echo "<div class='item-title'>$produto_nome</div>";
                   echo "<div class='item-after'><span href='#' onclick='adicionarCarrinho($produto_guid,\"$produto_nome\",$preco_value,\"$produto_imagem\")' class='button'><i class='material-icons color-icon'>add</i></span></div>";
                   echo "</div>";
                   echo "<div class='item-subtitle'>$produto_preco</div>";
                   echo '</div>'
                      ,'</li>';

                  }

                  echo '</ul>'
                      ,'</div>'
                      ,'</div>';
                    }
               ?>

                      </ul>
                  </div>
                </div>
                </div>



              <!-- Fim do Conteudo -->
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
