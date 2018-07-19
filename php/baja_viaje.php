<?php
  include("verficar_sesion.php");
  include("utils.php");

  include("abrir_conexion.php");
  $trip_id = $_POST['trip_id'];
  $user_id = $_SESSION['user_id'];
  $query = "SELECT * FROM viajes WHERE id_viaje='$trip_id'";
  $result = mysqli_query($conn, $query);
  $trip = mysqli_fetch_assoc($result);

  $query = "SELECT * FROM solicitud WHERE id_viaje='$trip_id' AND estado=" .ACCEPTED;
  if(($oc = dbOcurrences($conn, $query )) > 0 ){

    $result = mysqli_query($conn, $query);
    while($request = mysqli_fetch_assoc($result)){
      $query2 = "SELECT * FROM usuario WHERE id_usuario='".$request['id_pasajero']."'";
      $result2 = mysqli_query($conn, $query2);

      while($user = mysqli_fetch_assoc($result2)){
        $user_mail = $user['mail'];
        $sent = mail("$user_mail", "¡El viaje al que se ha postulado fue cancelado! Aventon", "Hola! Nos comunicamos para informarte  el viaje: \n ".$trip['origen']." con destino a ".$trip['destino']." con fecha de salida ".formatDate($trip['fecha_hora'])." \n fue cancelado. \n Equipo de Aventon ");
		    if(!$sent){
          header("Location: /vistas/ver_solicitudes.php?notification_error_cancel");
        }
      }
    }
    

  $query = "DELETE FROM solicitud WHERE id_viaje='$trip_id'";
  $result = mysqli_query($conn, $query);
  if ($trip['tipo'] == WEEKLY_TRIP){
    $query = "DELETE FROM viaje_semanal WHERE id_viaje='$trip_id'";
    $result = mysqli_query($conn, $query);
    
    if (!$result){
      header("location: /vistas/ver_viajes.php?db_error");
      exit();
    }
  }


    $query= "SELECT * FROM usuario WHERE id_usuario='$user_id' ";
    $result = mysqli_query($conn, $query);
    $user = mysqli_fetch_assoc($result);
    echo "gato";
    if ($user['promedio_puntuacion_conductor'] > 0){
      $average = $user['promedio_puntuacion_conductor'] - 1;
      $query = "UPDATE usuario SET promedio_puntuacion_conductor='$average' WHERE id_usuario='$user_id'";
      $result = mysqli_query($conn, $query);
    }
    $query = "DELETE FROM viajes WHERE id_viaje='$trip_id'";
    $result = mysqli_query($conn, $query);
    header("location: /vistas/ver_viajes.php?trip_removed_with_average");
    exit();

  } else {
    $query = "DELETE FROM solicitud WHERE id_viaje='$trip_id'";
    $result = mysqli_query($conn, $query);
    if ($trip['tipo'] == WEEKLY_TRIP){
      $query = "DELETE FROM viaje_semanal WHERE id_viaje='$trip_id'";
      $result = mysqli_query($conn, $query);
    
      if (!$result){
        header("location: /vistas/ver_viajes.php?db_error");
        exit();
      }
    }
    $query = "DELETE FROM viajes WHERE id_viaje='$trip_id'";
    $result = mysqli_query($conn, $query);
    header("location: /vistas/ver_viajes.php?trip_removed");
    exit();
  }
  include("cerrar_conexion.php");

?>
