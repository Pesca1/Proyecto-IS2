function dayChecked(){
  var checked = false;
  $(".day").each(function(e){
    if($(this).prop("checked")){
      checked = true;
    }
  });
  return checked;
}

function weekCheck(){
  var weeks = $("#weeks").val();
  var today = new Date().getDay();
  if(weeks == 1){
    for(var i = 7; i > today; i--){
      if($("#day"+i).prop("checked")){
        return true;
      }
    }
  }
  return false;
}

$("#trip_reg").submit(function(e){
  if(parseInt($("#price").val()) <= 0){
    show_error("El monto debe ser mayor a $0");
    return false;
  } else if(!dayChecked()){
    show_error("Seleccione uno o más días para su viaje");
    return false;
  } else if(!weekCheck()){
    show_error("Si el viaje solo comprende esta semana, seleccione días posteriores al día de hoy");
    return false;
  }
});
