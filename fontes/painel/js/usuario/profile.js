function salvar(operacao, guid){
  var camaponome     		= $("#nome").val();
  var guidupdate				= $("#guid").val();
  var campoimagem       = $("#img").val();

  toastr.options = {
    "closeButton": true,
    "debug": false,
    "newestOnTop": false,
    "progressBar": true,
    "positionClass": "toast-top-right",
    "preventDuplicates": false,
    "onclick": null,
    "showDuration": "300",
    "hideDuration": "1000",
    "timeOut": "5000",
    "extendedTimeOut": "1000",
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut"
  };

  if (camaponome.length < 1) {
    toastr.warning('O Campo Nome não pode ficar em branco', 'Atenção');
  } else {
    $.ajax({
      url:"ajax/usuarios/profile.php",
      type:"POST",
      data:"guidupdate="+guidupdate+"&imagem="+campoimagem+"&nome="+camaponome,
      success: function (result){
        toastr.success('Dados Atualizados', 'OK')
      }
    })
  }}


  $(function () {
    $('#supported').text('Supported/allowed: ' + !!screenfull.enabled);
    if (!screenfull.enabled) {
      return false;
    }
    $('#toggle').click(function () {
      screenfull.toggle($('#container')[0]);
    });

  });
