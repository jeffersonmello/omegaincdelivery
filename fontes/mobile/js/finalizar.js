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
