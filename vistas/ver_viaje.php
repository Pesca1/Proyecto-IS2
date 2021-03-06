<?php
  include("../php/verficar_sesion.php");
  include("../php/abrir_conexion.php");
  include("../php/utils.php");

  if(isset($_GET["id"]) && ($_GET["id"] != "")){
    if(dbOcurrences($conn, "SELECT * FROM viajes WHERE id_viaje='".$_GET["id"]."' AND id_usuario='".$_SESSION["user_id"]."'") == 0){
      header("Location: /vistas/ver_viajes.php");
    }
  } else {
    header("Location: /vistas/ver_viajes.php");
  }
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8"/>
    <title>Aventón</title>
    <link href="/css/bootstrap.css" rel="stylesheet" type="text/css" >
    <link href="/css/index.css" rel="stylesheet" type="text/css">
    <link href="/css/ver_viaje.css" rel="stylesheet" type="text/css">
  </head>
  <body>
    <?php
      include("header.php");
      $trip_id = $_GET["id"];
      $query = "SELECT * FROM viajes WHERE id_viaje='$trip_id'";
      $result = mysqli_query($conn, $query);
      $trip = mysqli_fetch_assoc($result);
      $query = "SELECT * FROM vehiculo WHERE patente='".$trip["patente"]."'";
      $result = mysqli_query($conn, $query);
      $car = mysqli_fetch_assoc($result);
      $query = "SELECT * FROM solicitud WHERE id_viaje='$trip_id' AND estado=".ACCEPTED;
      $requests = mysqli_query($conn, $query);
      $n_request = mysqli_num_rows($requests);
      $remaining_seats = $car["asientos"] - $n_request;
      $price = $trip["costo"];
    ?>
    <div id="body">
      <h1>Viaje <?= $trip["origen"]." --> ".$trip["destino"] ?></h1>
      <div id="trip_info">
        <h3>Información</h3>
        Fecha y hora: <?= formatDate($trip["fecha_hora"]) ?>
        <br>
        Duración: <?= printTime($trip["duracion"]) ?>
        <br>
        Vehículo: <?= $car["marca"]." ".$car["modelo"] ?>
        <br>
        Tipo de viaje: <?= ($trip["tipo"] == ONE_TIME_TRIP)?"Ocasional":"Semanal" ?>
        <br>
        Costo por persona: $<?= round($price) ?>
        <?php if($trip["descripcion"] != ""){ ?>
        <br>
        Descripción: 
        <div id="trip-description">
          <?= $trip["descripcion"] ?>
        </div>
        <?php } ?>
      </div>
      <div id="passengers">
        <h3>Pasajeros</h3>
        <?php
          if($n_request == 0){
            echo "No hay pasajeros todavía.";
          } else {
            while($request = mysqli_fetch_assoc($requests)){
              $query = "SELECT * FROM usuario WHERE id_usuario='".$request["id_pasajero"]."'";
              $passenger = mysqli_fetch_assoc(mysqli_query($conn, $query));
              echo "<div class='user'><div class='info'>";
              echo "<strong>".$passenger["nombre"]." ".$passenger["apellido"];
              echo "</strong><br>".$passenger["mail"];
              echo "</div><img src='/img/profile_users/".$passenger["foto_perfil"]."' class='profile_picture'/><br>";
              echo "</div>";
            }
          }
          if($remaining_seats == 0){
            echo "<strong>No hay mas lugares disponibles.</strong>";
          } else {
            echo "<strong>Quedan $remaining_seats lugares disponibles.</strong>";
          }
        ?>
        <br>
        <br>
        <form action="/vistas/ver_solicitudes.php">
          <input type="hidden" name="trip_id" value=<?= $trip["id_viaje"] ?>>
          <button type="send" class="btn btn-primary">
            Ver solicitudes <span class="badge badge-light"><?= countRequests($trip["id_viaje"], $conn) ?></span>
          </button>
        </form>
      </div>
      <div id="questions">
        <h3>Preguntas y Respuestas</h3>
        <?php
          $query = "SELECT * FROM pregunta WHERE id_viaje='$trip_id'";
          $questions = mysqli_query($conn, $query);
          if(mysqli_num_rows($questions) == 0){
            echo "Aún no hay preguntas para este viaje.";
          } else {
            while($question = mysqli_fetch_assoc($questions)){
              $query = "SELECT * FROM usuario WHERE id_usuario='".$question["id_usuario"]."'";
              $user = mysqli_fetch_assoc(mysqli_query($conn, $query));
              include("_comment_driver.php");
            }
          }
        ?>
        
      </div>
      <?php
        if($trip["tipo"]==WEEKLY_TRIP){
          echo "<div id='dates'><h3>Fechas</h3><table>";
          $query = "SELECT * FROM viaje_semanal WHERE id_viaje=$trip_id";
          $dates = mysqli_query($conn, $query);
          while($date = mysqli_fetch_assoc($dates)){
            $obj = new DateTime($date["fecha_hora"]);
            $weekDay = $days[$obj->format("N")];
            echo "<tr><td> - ".$weekDay." ".$obj->format("d/m")."</td></tr>";
          }
          echo "</table></div>";
        }
       ?>
    </div>
    
    <?php
      include("footer.php");
    ?>
  </body>
  <?php 
    include("bootstrap.php"); 
    include("../php/cerrar_conexion.php");
  ?>
  <script src="/js/registrar_usuario.js"></script>
</html>

