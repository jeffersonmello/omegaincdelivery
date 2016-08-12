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
$endereco     = $_SESSION['endereco'];
$mydate       = getDate(date("U"));
$data         = ("$mydate[year]-$mydate[mon]-$mydate[mday]");
$total        = $_SESSION['totalpedio'];

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
    <script src="js/searchOrder.min.js"></script>

    <script src="http://digitalbush.com/wp-content/uploads/2014/10/jquery.maskedinput.js"></script>

    <!--Import Google Icon Font-->
    <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!--Import Fontawesome Icon Font-->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-T8Gy5hrqNKT+hzMclPo118YTQO6cYprQmhrYwIiQ/3axmI1hQomh7Ud2hPOy8SP1" crossorigin="anonymous">

    <script type="text/javascript">
    $(document).ready(function(){
        $("#datafield").hide();
        jQuery("input.telefone")
      .mask("(99) 9999-9999?9")
      .focusout(function (event) {
          var target, phone, element;
          target = (event.currentTarget) ? event.currentTarget : event.srcElement;
          phone = target.value.replace(/\D/g, '');
          element = $(target);
          element.unmask();
          if(phone.length > 10) {
              element.mask("(99) 99999-999?9");
          } else {
              element.mask("(99) 9999-9999?9");
          }
      });
    })

    function validarCPF( cpf ){
      var filtro = /^\d{3}.\d{3}.\d{3}-\d{2}$/i;

      var myApp = new Framework7({
        material: true
      });
      var mainView = myApp.addView('.view-main');
      var campocpf = $("#cpf");

      if(!filtro.test(cpf))
      {
        myApp.addNotification({
        message: 'CPF Inválido',
        button: {
                    text: 'Fechar',
                },
         });
         campocpf.focus();
         campocpf.val("");
        return false;
      }

      cpf = remove(cpf, ".");
      cpf = remove(cpf, "-");

      if(cpf.length != 11 || cpf == "00000000000" || cpf == "11111111111" ||
        cpf == "22222222222" || cpf == "33333333333" || cpf == "44444444444" ||
        cpf == "55555555555" || cpf == "66666666666" || cpf == "77777777777" ||
        cpf == "88888888888" || cpf == "99999999999")
      {
        myApp.addNotification({
        message: 'CPF Inválido',
        button: {
                    text: 'Fechar',
                },
         });
         campocpf.focus();
         campocpf.val("");
        return false;
       }

      soma = 0;
      for(i = 0; i < 9; i++)
      {
        soma += parseInt(cpf.charAt(i)) * (10 - i);
      }

      resto = 11 - (soma % 11);
      if(resto == 10 || resto == 11)
      {
        resto = 0;
      }
      if(resto != parseInt(cpf.charAt(9))){
        myApp.addNotification({
        message: 'CPF Inválido',
        button: {
                    text: 'Fechar',
                },
         });
         campocpf.focus();
         campocpf.val("");
        return false;
      }

      soma = 0;
      for(i = 0; i < 10; i ++)
      {
        soma += parseInt(cpf.charAt(i)) * (11 - i);
      }
      resto = 11 - (soma % 11);
      if(resto == 10 || resto == 11)
      {
        resto = 0;
      }

      if(resto != parseInt(cpf.charAt(10))){
        myApp.addNotification({
        message: 'CPF Inválido',
        button: {
                    text: 'Fechar',
                },
         });
         campocpf.focus();
         campocpf.val("");
        return false;
      }

      return true;
     }

    function remove(str, sub) {
      i = str.indexOf(sub);
      r = "";
      if (i == -1) return str;
      {
        r += str.substring(0,i) + remove(str.substring(i + sub.length), sub);
      }

      return r;
    }

    /**
       * MASCARA ( mascara(o,f) e execmascara() ) CRIADAS POR ELCIO LUIZ
       * elcio.com.br
       */
    function mascara(o,f){
      v_obj=o
      v_fun=f
      setTimeout("execmascara()",1)
    }

    function execmascara(){
      v_obj.value=v_fun(v_obj.value)
    }

    function cpf_mask(v){
      v=v.replace(/\D/g,"")                 //Remove tudo o que não é dígito
      v=v.replace(/(\d{3})(\d)/,"$1.$2")    //Coloca ponto entre o terceiro e o quarto dígitos
      v=v.replace(/(\d{3})(\d)/,"$1.$2")    //Coloca ponto entre o setimo e o oitava dígitos
      v=v.replace(/(\d{3})(\d)/,"$1-$2")   //Coloca ponto entre o decimoprimeiro e o decimosegundo dígitos
      return v
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

      // dados
      var numeroPedido      = $("#pedido").val();
      var nomecliente       = $("#nome").val();
      var emailcliente      = $("#email").val();
      var endereco          = $("#rua").val();
      var numeroResidencia  = $("#numero").val();
      var formaPgto         = $("#formapagamento").val();
      var retirarLoja       = $("#check").val();
      var observacao        = $("#obs").val();
      var hoje              = $("#data").val();
      var cpf               = $("#cpf").val();
      var telefone          = $("#telefone").val();


      if (cpf.length < 9) {
        myApp.addNotification({
        message: 'CPF Inválido',
        button: {
                    text: 'Fechar',
                },
         });
      } else if (numeroPedido.length < 1) {
        myApp.addNotification({
        message: 'Pedido Inválido',
        button: {
                    text: 'Fechar',
                },
         });
      } else if (nomecliente.length < 1) {
        myApp.addNotification({
        message: 'Preencha o campo Nome',
        button: {
                    text: 'Fechar',
                },
         });
      } else if (emailcliente.length < 1) {
        myApp.addNotification({
        message: 'Preencha o campo E-mail',
        button: {
                    text: 'Fechar',
                },
         });
      } else if (endereco.length < 1) {
        myApp.addNotification({
        message: 'Preencha o endereço',
        button: {
                    text: 'Fechar',
                },
         });
      }  else if (numeroResidencia.length < 1) {
        myApp.addNotification({
        message: 'Preencha o campo Número da Residencia',
        button: {
                    text: 'Fechar',
                },
         });
      } else if (formaPgto.length < 1) {
        myApp.addNotification({
        message: 'Selecione a forma de pagamento',
        button: {
                    text: 'Fechar',
                },
         });
      } else if (retirarLoja.length < 1) {
        myApp.addNotification({
        message: 'Selecione a entrega ou retirada na loja',
        button: {
                    text: 'Fechar',
                },
         });
      } else {
        $.ajax({
          url:"ajax/finalizapedido.php",
          type:"POST",
          data: "endereco="+endereco+"&nome="+nomecliente+"&email="+emailcliente+"&numero="+numeroResidencia+"&pedido="+numeroPedido+"&formapagamento="+formaPgto+"&retirarloja="+retirarLoja+"&observacao="+observacao+"&hoje="+hoje+"&cpf="+cpf+"&telefone="+telefone+"&total="+<?php echo $total ?>,
            success: function (result){
              if (result == 1){
                location.href='finalizado.php'
              } else {
                myApp.addNotification({
                message: 'Erro desconhecido',
                button: {
                            text: 'Fechar',
                        },
                 });
              }
            }
        })
      }
    }

    </script>
  </head>
  <body>
    <!-- Status bar overlay for fullscreen mode-->
    <div class="statusbar-overlay"></div>
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

    <!-- Views-->
    <div class="views">
      <div class="view view-main">

        <!-- Top Navbar-->
        <div class="navbar">
          <div class="navbar-inner">
            <div class="left"><a href="#" class="link icon-only open-panel"> <i class="icon icon-bars"></i></a></div>
            <div class="center">Finalizar</div>
              <div class="right"></div>
          </div>
        </div>

        <!-- Pages, because we need fixed-through navbar and toolbar, it has additional appropriate classes-->
        <div class="pages navbar-through toolbar-through">
          <!-- Page, data-page contains page name-->
          <div data-page="index" class="page">
            <!-- Scrollable page content-->
            <div class="page-content">
             <div class="login-screen modal-in">
      <!-- Default view-page layout -->
      <div class="view">
        <div class="page">

          <br>
          <br>

          <!-- page-content has additional login-screen content -->
          <div class="page-content login-screen-content">
            <div class="login-screen-title">Finalizar Pedido</div>


            <!-- end form -->
            <form action="" method="post">
              <div class="list-block">
                <ul>

                  <li class="item-content">
                    <div class="item-media"><i class="material-icons color-icon">check_circle</i></div>
                    <div class="item-inner">
                      <div class="item-input">
                        <input type="text" value="<?php echo $guid_pedido?>" id="pedido" name="pedido" placeholder="pedido" required disabled>
                      </div>
                    </div>
                  </li>


                  <li class="item-content">
                    <div class="item-media"><i class="material-icons color-icon">person</i></div>
                    <div class="item-inner">
                      <div class="item-input">
                        <input type="text" value="<?php echo $nome_cliente?>" id="nome" name="nome" placeholder="Seu Nome" required>
                      </div>
                    </div>
                  </li>

                  <li class="item-content">
                    <div class="item-media"><i class="material-icons color-icon">email</i></div>
                    <div class="item-inner">
                      <div class="item-input">
                        <input type="email" value="<?php echo $email_cliente?>" id="email" name="email" placeholder="Seu Email" required>
                      </div>
                    </div>
                  </li>

                  <li class="item-content">
                    <div class="item-media"><i class="material-icons color-icon">perm_contact_calendar</i></div>
                    <div class="item-inner">
                      <div class="item-input">
                        <input type="text"  id="cpf" name="cpf" placeholder="CPF" onblur="javascript: validarCPF(this.value);" onkeyup="javascript: mascara(this, cpf_mask);">
                      </div>
                    </div>
                  </li>

                  <li class="item-content">
                    <div class="item-media"><i class="material-icons color-icon">phone</i></div>
                    <div class="item-inner">
                      <div class="item-input">
                        <input type="text" class="telefone" id="telefone" name="telefone" placeholder="Seu telefone">
                      </div>
                    </div>
                  </li>

                  <li class="item-content">
                    <div class="item-media"><i class="material-icons color-icon">location_on</i></div>
                    <div class="item-inner">
                      <div class="item-input">
                        <input type="text" value="<?php echo $endereco?>" id="rua" name="Rua" placeholder="Seu Endereço" disabled>
                      </div>
                    </div>
                  </li>

                  <li class="item-content">
                    <div class="item-media"><i class="material-icons color-icon">home</i></div>
                    <div class="item-inner">
                      <div class="item-input">
                        <input type="text" value="" id="numero" name="numero" placeholder="Número da sua residência" required>
                      </div>
                    </div>
                  </li>

                  <li class="item-content">
                    <div class="item-media"><i class="material-icons color-icon">payment</i></div>
                    <div class="item-inner">
                      <div class="item-title label">Forma de Pagamento</div>
                      <div class="item-input">
                        <select id="formapagamento">
                          <option>Dinheiro</option>
                          <option>Cartão de Crédito/Débito</option>
                        </select>
                      </div>
                    </div>
                  </li>


                  <li class="item-content">
                    <div class="item-media"><i class="material-icons color-icon">business</i></div>
                    <div class="item-inner">
                      <div class="item-title label">Retirar na Loja</div>
                      <div class="item-input">
                        <select id="check">
                          <option>Não, Entregar</option>
                          <option>Sim, vou retirar na loja</option>
                        </select>
                      </div>
                    </div>
                  </li>

                  <li class="item-content" id="datafield">
                    <div class="item-media"><i class="material-icons color-icon">calendar</i></div>
                    <div class="item-inner">
                      <div class="item-input">
                        <input type="text" value="<?php echo $data?>" id="data" name="data" placeholder="data">
                      </div>
                    </div>
                  </li>

                  <li class="item-content">
                    <div class="item-media"><i class="material-icons color-icon">note</i></div>
                    <div class="item-inner">
                      <div class="item-input">
                        <textarea id="obs" class="resizable" placeholder="Observação"></textarea>
                      </div>
                    </div>
                  </li>

                </ul>
              </div>

              <div class="card">
                <div class="card-content">
                  <div class="card-content-inner">
                    <center><img src="http://mobile.kingofeletro.com.br/delivery/css/bandeiras.png"></center>
                  </div>
                </div>
              </div>

            </form>
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



            <br>
           </div>

        </div>
      </div>
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
