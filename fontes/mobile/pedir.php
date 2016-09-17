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

$taxa             = $_SESSION['taxaentrega'];
$nome_cliente     = $_SESSION['nomecliente'];
$email_cliente    = $_SESSION['emailcliente'];
$guid_bairro      = $_SESSION['idBairro'];
$guid_pedido      = $_SESSION['idPedido'];
$countitensmeios  = (isset($_SESSION['countitensmeios'])) ? (int)$_GET['countitensmeios'] : 0;
$guidprodtemp     = (isset($_SESSION['guidprodtemp'])) ? (int)$_GET['guidprodtemp'] : 0;


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
  <link rel="stylesheet" href="css/delivery.min.css">

  <!--jquery-->
  <script   src="js/jquery-3.0.0.min.js"></script>
  <script src="js/searchOrder.min.js"></script>


  <!--Import Google Icon Font-->
  <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

  <!--Import Fontawesome Icon Font-->
  <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-T8Gy5hrqNKT+hzMclPo118YTQO6cYprQmhrYwIiQ/3axmI1hQomh7Ud2hPOy8SP1" crossorigin="anonymous">

  <!--MomentJS -->
  <script src="http://momentjs.com/downloads/moment.min.js"></script>

  <!--AccountJS -->
  <script src="js/accounting.min.js"></script>

  <script src="js/pedir/pedir.min.js" charset="utf-8"></script>

  <script type="text/javascript">
  function mesmo(guid,valor){
    $.ajax({
      url:("ajax/mesmoitem.php"),
      type: "POST",
      data: "guidprod="+guid+"&guidpedido="+<?php echo $guid_pedido; ?>,
      success:function(dados){
        valor = accounting.formatMoney(valor, "R$ ", 2, ".", ",")
        $("#idvaloresqtde_"+guid).html((valor)+" ("+dados+")");
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
          valor = accounting.formatMoney(valor, "R$ ", 2, ".", ",")
          $("#total").html(valor);
        }})
      }

      function removeItem(guid, preco){
        var corrente     = $("#listacarrinho_"+guid);

        $.ajax({
          url:("ajax/removeitem.php"),
          type: "POST",
          data: "guidprod="+guid+"&guidpedido="+<?php echo $guid_pedido; ?>,
          success:function(dados){
            if (dados == 1) {
              mesmo(guid, preco);
              totaliza()
            } else {
              corrente.remove();
              totaliza()
            }
          },
          error: function(XMLHttpRequest, textStatus, errorThrown) {
            alert("Status: " + textStatus); alert("Error: " + errorThrown);
          }
        })
      }

      function adicionarCarrinho(guid, nome, preco, imagem){
        var currentiten   = $("#listacarrinho_"+guid);
        var listacarrinho = $("#teste");
        $.ajax({
          url:("ajax/adicionacarrinho.php"),
          type: "POST",
          data: "guidprod="+guid+"&guidpedido="+<?php echo $guid_pedido; ?>,
          success:function(dados){
            if ($(currentiten, listacarrinho).length){
              mesmo(guid,preco);
            } else {
              if (dados == 1){
                preco = accounting.formatMoney(preco, "", 2, ".", ",");
                listacarrinho.append("<li id='listacarrinho_"+guid+"'><div class='item-content'><div class='item-media'> <i class='icon my-icon'><img class='circular' src='"+imagem+"' width='44'></i></div><div class='item-inner'><div class='item-title-row'><div class='item-title'>"+nome+"</div><div id='idvaloresqtde_"+guid+"' class='item-after'>R$ "+preco+" (1)</div></div><div class='item-subtitle'><a href='#' onclick='removeItem("+guid+","+preco+")' class='button color-red'><i class='material-icons color-icon'>delete</i></a></div></div></div></li>");
                totaliza();
              }
            }
          }})
        }

        function adicionarMeio(guid, nome, preco, imagem){
          var currentiten   = $("#listacarrinho_"+guid);
          var listacarrinho = $("#carrinhomeias");
          var contitensmeio = localStorage.getItem("contitensmeio");
          var guidprodtemp =  localStorage.getItem("guidprodtemp");

          alert(contitensmeio);
          alert(guidprodtemp);

          if (contitensmeio == null) {
          $.ajax({
            url:("ajax/meios/twosabores.php"),
            type: "POST",
            data: "preco="+preco+"&nome="+nome,
            success:function(dados){
                  preco = accounting.formatMoney(preco, "", 2, ".", ",");
                  listacarrinho.append("<li id='listacarrinhomeia_"+guid+"'><div class='item-content'><div class='item-media'> <i class='icon my-icon'><img class='circular' src='"+imagem+"' width='44'></i></div><div class='item-inner'><div class='item-title-row'><div class='item-title'>"+nome+"</div><div id='idvaloresqtde_"+guid+"' class='item-after'>R$ "+preco+" (1)</div></div><div class='item-subtitle'><a href='#' onclick='removeItem("+guid+","+preco+")' class='button color-red'><i class='material-icons color-icon'>delete</i></a></div></div></div></li>");
              }
            })
          }
          }

        function cancelaPedido(){
          var pedido = <?php echo $guid_pedido ?>;

          $.ajax({
            url:("ajax/cancelapedido.php"),
            type: "POST",
            data: "guidpedido="+pedido,
            success:function(dados){
              if (dados == 1){
                location.href = 'index.php'
              }
            }
          })
        }

        function finalizaPedido(){
          var myApp = new Framework7({
            material: true
          });
          var mainView = myApp.addView('.view-main');
          var total   = totaliza();

          $.ajax({
            url:("ajax/totalitens.php"),
            type: "POST",
            data: "guidpedido="+<?php echo $guid_pedido; ?>,
            success:function(dados){
              if (dados == 1){
                location.href = 'finalizar.php'
              } else {
                myApp.addNotification({
                  message: 'É necesário ter pelo menos um item no carrinho.',
                  button: {
                    text: 'Fechar',
                  },
                });
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
            <div class="navbar">
              <div class="navbar-inner">
                <div class="left"></div>
                <div class="center">Menu</div>
                <div class="right"></div>
              </div>
            </div>

            <div class="list-block">
              <ul>
                <li>
                  <a href="#" data-popup=".popup-check" class="item-link open-popup">
                    <div class="item-inner">
                      <div class="item-title">Consultar Pedido</div>
                    </div>
                  </a>
                </li>
                <li>
                  <a href="#" data-popup=".popup-about" class="item-link open-popup">
                    <div class="item-inner">
                      <div class="item-title">Sobre</div>
                    </div>
                  </a>
                </li>
              </ul>
            </div>
          </div>

          <!-- About Popup -->
          <div class="popup popup-about">
            <div class="content-block">
              <p>Sobre o Aplicativo</p>
              <p><a href="#" class="close-popup">Fechar</a></p>
              <p>Informaões da empresa que administra o aplicativo. <br>
                Sobre a empresa <br>
              </p>
              <p>Desenvolvimento</p>
              <p>Este aplicativo foi desenvolvido por Copyright Omega Inc. Todos os direitos Reservados<br>
              </p>
            </div>
          </div>

          <!-- Check Popup -->
          <div class="popup popup-check">
            <div class="content-block">
              <p>Consultar Pedido</p>
              <p><a href="#" onclick="clearTimeLine()" class="close-popup">Fechar</a></p>
              <div class="list-block">
                <ul>

                  <li class="item-content">
                    <div class="item-media"><i class="material-icons color-icon">search</i></div>
                    <div class="item-inner">
                      <div class="item-input">
                        <input type="text"  id="numeropedido" placeholder="Numero do Pedido"  required>
                      </div>
                    </div>
                  </li>

                  <li class="item-content">
                    <div class="item-media"><i class="material-icons color-icon">lock_outline</i></div>
                    <div class="item-inner">
                      <div class="item-input">
                        <input type="text"  id="token" placeholder="Token"  required>
                      </div>
                    </div>
                  </li>

                  <li>
                    <p class="buttons-row">
                      <a type="submit" href="" onclick="searchOrder()"  class="button button-fill button-raised color-green">Pesquisar</a>
                    </p>
                  </li>
                </ul>
              </div>
              <div id="container">
              </div>
            </div>
          </div>

          <!-- 2 Sabores Popup -->
          <div class="popup popup-2sabores">
            <div class="content-block">
              <p>Monte Sua Pizza de 2 Sabores</p>
              <p><a href="#" class="close-popup">Fechar</a></p>
              <p>
                <div class="list-block accordion-list">
                  <div class="list-block">
                    <ul id="carrinhomeias">
                    </ul>
                  </div>
                  <ul id="listageralmeias">
                    <?php
                    $db->connect();
                    $db->sql("SELECT * FROM cad_categorias WHERE twosaborescat = 1");
                    $res = $db->getResult();
                    foreach($res as $output)
                    {
                      $guid_categoria = $output["guid"];
                      $iconecategoria = $output["iconecategoria"];
                      $nome_categoria = $output["descricao"];

                      echo '<li class="accordion-item categorias-meias">'
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
                        a.twosaborescat,
                        b.guid as guidprod,
                        b.guid_categoria,
                        b.subdescricao,
                        b.imgproduto as imagem,
                        b.descricao as produto,
                        b.indisponivel,
                        b.twosabores,
                        b.preco
                        FROM cad_categorias AS a
                        INNER JOIN cad_produtos AS b
                        ON a.guid = b.guid_categoria WHERE a.guid = $guid_categoria AND b.indisponivel != 1 AND a.twosaborescat = 1 AND b.twosabores = 1");
                        $res = $db->getResult();
                        foreach($res as $output)
                        {
                          $categoria_guid           = $output["guidcategoria"];
                          $produto_guid             = $output["guidprod"];
                          $produto_imagem           = $output["imagem"];
                          $produto_nome             = $output["produto"];
                          $produto_preco            = $output["preco"];
                          $subdescricao             = $output["subdescricao"];

                          $preco_value = ($produto_preco / 2);

                          $preco_value = money_format('%n', $preco_value);


                          echo "<li class='item-content' id='$produto_guid'>";
                          echo "<div class='item-media'  href='#' onclick='viewimage(\"$produto_imagem\", \"$produto_nome\", \"$subdescricao\");'>";
                          echo "<img class='circular' src='$produto_imagem' width='44'>";
                          echo '</div>'
                          ,'<div class="item-inner">'
                          ,'<div class="item-title-row">';
                          echo "<div class='item-title'>Meia $produto_nome</div>";
                          echo "<div class='item-after'><span href='#' id='buttonADD' onclick='adicionarMeio($produto_guid,\"$produto_nome\",$produto_preco,\"$produto_imagem\")' class='button'><i class='material-icons color-icon'>add</i></span></div>";
                          echo "</div>";
                          echo "<div class='item-subtitle'>$preco_value</div>";
                          echo "<div id='subdesc' class='item-text'>$subdescricao</div>";
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
                </p>
              </div>
            </div>

            <!-- Views-->
            <div class="views">
              <div class="view view-main mainprincipal">

                <!-- Top Navbar-->
                <div class="navbar">
                  <div class="navbar-inner">
                    <div class="left"><a href="#" class="link icon-only open-panel"> <i class="icon icon-bars"></i></a></div>
                    <div class="center">Pedido</div>
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
                        <div class="card-content-block">
                          <div class="card-content-inner">

                            <div ><iframe id="iframe" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3644.437152806504!2d-52.35454038490684!3d-24.015644884410168!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x94ed0b28ee9a9359%3A0x1c5dee983eeb3986!2sAv.+Bronislav+Wronski+-+Jardim+Aeroporto%2C+Campo+Mour%C3%A3o+-+PR%2C+87310-300!5e0!3m2!1spt-BR!2sbr!4v1459362655860" width="100%" height="150" frameborder="0" style="border:0" disabled></iframe></div>

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
                            Número do Pedido: <b>#<?php echo $guid_pedido?></b><br>
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
                                  b.subdescricao,
                                  b.imgproduto as imagem,
                                  b.descricao as produto,
                                  b.indisponivel,
                                  b.preco
                                  FROM cad_categorias AS a
                                  INNER JOIN cad_produtos AS b
                                  ON a.guid = b.guid_categoria WHERE a.guid = $guid_categoria AND b.indisponivel != 1");
                                  $res = $db->getResult();
                                  foreach($res as $output)
                                  {
                                    $categoria_guid           = $output["guidcategoria"];
                                    $produto_guid             = $output["guidprod"];
                                    $produto_imagem           = $output["imagem"];
                                    $produto_nome             = $output["produto"];
                                    $produto_preco            = $output["preco"];
                                    $subdescricao             = $output["subdescricao"];

                                    $preco_value = $produto_preco;

                                    $produto_preco = money_format('%n', $produto_preco);

                                    echo '<li class="item-content">';
                                    echo "<div class='item-media'  href='#' onclick='viewimage(\"$produto_imagem\", \"$produto_nome\", \"$subdescricao\");'>";
                                    echo "<img class='circular' src='$produto_imagem' width='44'>";
                                    echo '</div>'
                                    ,'<div class="item-inner">'
                                    ,'<div class="item-title-row">';
                                    echo "<div class='item-title'>$produto_nome</div>";
                                    echo "<div class='item-after'><span href='#' id='buttonADD' onclick='adicionarCarrinho($produto_guid,\"$produto_nome\",$preco_value,\"$produto_imagem\")' class='button'><i class='material-icons color-icon'>add</i></span></div>";
                                    echo "</div>";
                                    echo "<div class='item-subtitle'>$produto_preco</div>";
                                    echo "<div id='subdesc' class='item-text'>$subdescricao</div>";
                                    echo '</div>'
                                    ,'</li>';

                                  }

                                  echo '</ul>'
                                  ,'</div>'
                                  ,'</div>';
                                }
                                ?>



                              </ul>
                              <a href="#" data-popup=".popup-2sabores" class="item-link open-popup">
                                <div class="item-content">
                                  <div class="item-media"><i class="material-icons color-icon">local_pizza</i></div>
                                  <div class="item-inner">
                                    <div class="item-title">Pizzas 2 Sabores</div>
                                    <div class="item-after">Monte sua Pizza de dois Sabores</div>
                                  </div>
                                </div>
                              </a>
                            </div>
                          </div>
                        </div>


                        <div class="list-block inset">
                          <ul>
                            <li>
                              <p><a href="#" onclick="finalizaPedido()" class="button button-fill color-green">Finalizar</a></p>
                            </li>
                            <li>
                              <p><a href="#" onclick="cancelaPedido()" class="button button-fill color-red">Cancelar</a></p>
                            </li>
                          </ul>
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
