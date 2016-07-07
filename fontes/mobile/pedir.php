<?php
ob_start();// Inicia Buffer

// Funções para não exibir alguns erros de conexao
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
    <script   src="https://code.jquery.com/jquery-3.0.0.min.js"   integrity="sha256-JmvOoLtYsmqlsWxa7mDSLMwa6dZ9rrIdtrrVYRnDRH0="   crossorigin="anonymous"></script>

    <!--Toastr-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet"/>

    <!--DataTables-->
    <script src="https://cdn.datatables.net/1.10.11/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.11/js/dataTables.bootstrap.min.js"></script>

    <!--Import Google Icon Font-->
    <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!--Import Fontawesome Icon Font-->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-T8Gy5hrqNKT+hzMclPo118YTQO6cYprQmhrYwIiQ/3axmI1hQomh7Ud2hPOy8SP1" crossorigin="anonymous">

    <script type="text/javascript">
    $(document).ready(function(){
      $("#search").onkeypress = function(){
        $(".categorias").hide();
        var pesquisa = $("#search").val();
        $.ajax({
          url:("ajax/verifica_abertofechado.php"),
          type: "POST",
          data: "busca="+pesquisa,
          success:function(dados){
            $.each(dados, function(index){
              $("#listaprodutos").append("<li id='itempesquisa' class='item-content'><img src='"+dados[index].imgproduto+"' width='44'></div><div class='item-inner'><div class='item-title-row'><div class='item-title'>"+dados[index].descricao+"</div>")
            });
          }})
      }

    })
    </script>
  </head>
  <body>
    <!-- Status bar overlay for fullscreen mode-->
    <div class="statusbar-overlay"></div>
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
            <div class="right"></div>
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
                <div style="background-image:url(https://mir-s3-cdn-cf.behance.net/project_modules/disp/6bb33613489499.562747b282f8d.png)" valign="bottom" class="card-header color-white no-border">Delivery Nome da Loja</div>
                <div class="card-content">
                  <div class="card-content-inner">

                    <div><iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3644.437152806504!2d-52.35454038490684!3d-24.015644884410168!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x94ed0b28ee9a9359%3A0x1c5dee983eeb3986!2sAv.+Bronislav+Wronski+-+Jardim+Aeroporto%2C+Campo+Mour%C3%A3o+-+PR%2C+87310-300!5e0!3m2!1spt-BR!2sbr!4v1459362655860" width="100%" height="150" frameborder="0" style="border:0"></iframe></div>

                    <div class="list-block">
                          <ul>
                            <li>
                              <a href="#" class="item-link item-content">
                                <div class="item-media"><i class="icon icon-f7"></i></div>
                                <div class="item-inner">
                                  <div class="item-title">Estamos:</div>
                                </div>
                              </a>
                            </li>

                          </ul>
                        </div>
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
                      Taxa de Entrega: R$ <?php echo $taxaM; ?>

                  </div>
                  </div>
                </div>

              <!-- Produtos -->
              <div class="card">
                <div class="card-header"><i class="fa fa-archive color-icon" aria-hidden="true"></i> Selecione seu pedido</div>
                <div class="card-content">
                  <div class="list-block accordion-list">
                      <ul id="listaprodutos">

                        <li class="item-content">
                          <div class="item-media"><i class="material-icons color-icon">search</i></div>
                          <div class="item-inner">
                            <div class="item-input">
                              <input type="text" id="search" name="search" placeholder="Buscar..." required>
                            </div>
                          </div>
                        </li>


                               <li class="item-content">
                                 <div class="item-media"><i class="icon icon-f7"></i></div>
                                 <div class="item-inner">
                                   <div class="item-title">Item title</div>
                                   <div class="item-after">Label</div>
                                 </div>
                               </li>


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

                    $produto_preco = money_format('%n', $produto_preco);

                    echo '<li class="item-content">'
                        ,'<div class="item-media">';
                    echo "<img src='$produto_imagem' width='44'>";
                    echo '</div>'
                        ,'<div class="item-inner">'
                        ,'<div class="item-title-row">';
                   echo "<div class='item-title'>$produto_nome</div>";
                   echo "</div>";
                   echo "<div class='item-subtitle'>R$ $produto_preco</div>";
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
