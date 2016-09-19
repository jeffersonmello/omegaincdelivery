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
    //getPromotion();
  

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

      function removeItemMeio(guid){
        var corrente     = $("#listacarrinhomeia_"+guid);


        $.ajax({
          url:("ajax/meios/removeitenmeio.php"),
          type: "POST",
          data: "guidprod="+guid+"&guidpedido="+<?php echo $guid_pedido; ?>,
          success:function(dados){
            var myApp = new Framework7({material: true,
              modalTitle: 'Delivery',});

              myApp.showIndicator();
              localStorage.setItem("contitensmeio", 0);
              localStorage.setItem("guidprodtemp", 0);
              localStorage.setItem("nomeprod", 0);
              corrente.remove();

              setTimeout(function () {
                myApp.hideIndicator();
              }, 2000);
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
            data: "guidprod="+guid+"&guidpedido="+<?php echo $guid_pedido; ?>+"&preco="+preco,
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
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
              alert("Status: " + textStatus); alert("Error: " + errorThrown);
            }})
          }

          function adicionarMeio(guid, nome, preco, imagem){
            var currentiten   = $("#listacarrinho_"+guid);
            var listacarrinho = $("#carrinhomeias");
            var contitensmeio = localStorage.getItem("contitensmeio");
            var guidprodtemp  = localStorage.getItem("guidprodtemp");
            var nomeigualprod = localStorage.getItem("nomeprod");
            var myApp = new Framework7({material: true,
              modalTitle: 'Delivery',});

            if ((contitensmeio == null) || (contitensmeio == 0)) {


                myApp.showIndicator();
                $.ajax({
                  url:("ajax/meios/twosabores.php"),
                  type: "POST",
                  data: "preco="+preco+"&nome="+nome,
                  success:function(dados){
                    localStorage.setItem("contitensmeio", 1);
                    localStorage.setItem("guidprodtemp", dados);
                    localStorage.setItem("nomeprod", nome);
                    localStorage.setItem("precoprod", preco);
                    preco = accounting.formatMoney(preco, "", 2, ".", ",");
                    listacarrinho.append("<li id='listacarrinhomeia_"+dados+"'><div class='item-content'><div class='item-media'> <i class='icon my-icon'><img class='circular' src='"+imagem+"' width='44'></i></div><div class='item-inner'><div class='item-title-row'><div class='item-title nome-iten-meio'>Meia "+nome+"</div><div id='idvaloresqtde_"+guid+"' class='item-after'>R$ "+preco+"</div></div><div class='item-subtitle'><a href='#' onclick='removeItemMeio("+dados+")' class='button color-red'><i class='material-icons color-icon'>delete</i></a></div></div></div></li>");
                  }
                })
                setTimeout(function () {
                  myApp.hideIndicator();
                }, 2000);
              }   else if ((contitensmeio == 1) && (nomeigualprod != nome)) {
                  myApp.showIndicator();

                  var nomelastprod  = localStorage.getItem("nomeprod");
                  var precolastprod = localStorage.getItem("precoprod");

                  var nomeprodtemp  = ("Meia " + nomelastprod + " e Meia " + nome);
                  for (i = 25; i > 1; i++){
                    var proximoEspaco = nomeprodtemp.substring(i, (i + 1));

                    if (proximoEspaco == " "){
                      var textoCortado = nomeprodtemp.substring(0, i);
                      i = 0;
                    }
                  }
                  nomeprodtemp      = (textoCortado + "...");
                  precolastprod     = parseFloat(precolastprod);
                  var precoprodtemp = ((precolastprod + preco));
                  precoprodtemp     = precoprodtemp + 0.5 + 0.5;
                  guidprodtemp      = parseInt(guidprodtemp);
                  localStorage.setItem('tamanhoanterior', 0)


                  $.ajax({
                    url:("ajax/meios/twosaboresupdate.php"),
                    type: "POST",
                    data: "preco="+precoprodtemp+"&nome="+nomeprodtemp+"&guidprodtemp="+guidprodtemp,
                    success:function(dados){
                      detTamanho(guidprodtemp, nomeprodtemp, precoprodtemp,"https://cdn1.iconfinder.com/data/icons/streamline-time/60/cell-18-2-240.png", 1);
                      localStorage.clear();
                      //myApp.closeModal(popup);
                      listacarrinho.empty();
                        var $$ = Dom7;

                        $$('.close-popup').trigger('click');
                      },
                      error: function(XMLHttpRequest, textStatus, errorThrown) {
                        alert("Status: " + textStatus); alert("Error: " + errorThrown);
                      }
                    })
                    setTimeout(function () {
                      myApp.hideIndicator();
                    }, 2000);
                  } else if (nomeigualprod == nome){
                      myApp.alert('Não é permitido adicionar 2 sabores iguais');
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

                  function detTamanho(guid, nome, preco, imagem, deftamanho){

                    if (deftamanho == 1){
                    myApp.modal({
                      title:  'Escolha o tamanho da sua Pizza.',
                      text: '6 Pedaços é o valor atual, 8 Pedaços é acrescentado 1 Real a mais, 12 Pedaços é acrescentado 5 Reais a mais.',
                      buttons: [
                        {
                          text: '6 Pedaços',
                          onClick: function() {
                            nome = (nome + " - 6 Pedaços");
                            adicionarCarrinho(guid, nome, preco, imagem);
                          }
                        },
                        {
                          text: '8 Pedaços',
                          onClick: function() {
                            nome = (nome + " - 8 Pedaços");
                            preco = (preco + 1);
                            adicionarCarrinho(guid, nome, preco, imagem)
                          }
                        },
                        {
                          text: '12 Pedaços',
                          bold: true,
                          onClick: function() {
                            nome = (nome + " - 12 Pedaços");
                            preco = (preco + 5);
                            adicionarCarrinho(guid, nome, preco, imagem)
                          }
                        },
                      ]
                    })
                  } else {
                    adicionarCarrinho(guid, nome, preco, imagem);
                  }
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
                    <div id="popup-check" class="popup popup-check">
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
                        <p>Monte Sua Pizza de 2 Sabores. ATENÇÃO, ao selecionar tamanhos diferentes será considerado o tamanho maior.</p>
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
                                  $preco2      = ($produto_preco / 2);

                                  $preco_value = money_format('%n', $preco_value);


                                  echo "<li class='item-content' id='$produto_guid'>";
                                  echo "<div class='item-media'  href='#' onclick='viewimage(\"$produto_imagem\", \"$produto_nome\", \"$subdescricao\");'>";
                                  echo "<img class='circular' src='$produto_imagem' width='44'>";
                                  echo '</div>'
                                  ,'<div class="item-inner">'
                                  ,'<div class="item-title-row">';
                                  echo "<div class='item-title'>Meia $produto_nome</div>";
                                  echo "<div class='item-after'><span href='#' id='buttonADD' onclick='adicionarMeio($produto_guid,\"$produto_nome\",$preco2,\"$produto_imagem\")' class='button'><i class='material-icons color-icon'>add</i></span></div>";
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
                                    Desconto: <b><?php echo $taxadesconto ?>%</b><br>
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
                                        b.definetamanho,
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
                                          $tamanho                  = $output["definetamanho"];

                                          $preco_value = $produto_preco;

                                          $produto_preco = money_format('%n', $produto_preco);

                                          echo '<li class="item-content">';
                                          echo "<div class='item-media'  href='#' onclick='viewimage(\"$produto_imagem\", \"$produto_nome\", \"$subdescricao\");'>";
                                          echo "<img class='circular' src='$produto_imagem' width='44'>";
                                          echo '</div>'
                                          ,'<div class="item-inner">'
                                          ,'<div class="item-title-row">';
                                          echo "<div class='item-title'>$produto_nome</div>";
                                          echo "<div class='item-after'><span href='#' id='buttonADD' onclick='detTamanho($produto_guid,\"$produto_nome\",$preco_value,\"$produto_imagem\", $tamanho)' class='button'><i class='material-icons color-icon'>add</i></span></div>";
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
