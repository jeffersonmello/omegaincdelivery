function login(){
  var usuario = $("#usuario").val();
  var senha   = $("#senha").val();
  $.ajax({
    url:"ajax/login.php",
    type:"POST",
    data: "usuario="+usuario+"&senha="+senha,
    success: function (result){
      if(result==1){
        location.href='dashboard.php'
      }else{
        toastr.error('Usuario ou Senha Incorretos', 'Erro!')
      }
    }
  })
  return false;
}
