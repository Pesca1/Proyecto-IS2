<!DOCTYPE html>
<html>
  <head> 
    <link href="/css/bootstrap.css" rel="stylesheet" type="text/css" >
    <link href="/css/index.css" rel="stylesheet" type="text/css">
    <link href="/css/listar_vehiculos.css" rel="stylesheet" type="text/css">
  </head>
  <body> 
    <?php
      include("../php/verficar_sesion.php");
      include("../php/abrir_conexion.php");
      include("../php/utils.php");
      include("header.php");
    ?>
    <div id="body"> 
      <h1>Mis viajes</h1>
      <?php
        $pending = false;
        $id = $_SESSION["user_id"];
        $sql = "SELECT * FROM viajes WHERE id_usuario='$id'";
        $result = mysqli_query($conn, $sql);
        if($result){
          if($trip = mysqli_fetch_assoc($result)){
            while($trip){
              if(isPendingTrip($conn, $trip)){ $pending= true;?>
          
      <div class="vehicle">
        <div class="vehicle-info">
          <h3><?= $trip["origen"] ?> --> <?= $trip["destino"] ?></h3>
          <?=
            ($trip["tipo"] == WEEKLY_TRIP)?"<strong>Viaje recurrente</strong> - Comienza el ":"";
          ?>
          <?= date("d/m/Y H:i", strtotime($trip["fecha_hora"])); ?> - Duración:
          <?php
            echo printTime($trip["duracion"]).".";
          ?>
	        <br>
          <br>
          <a class="btn btn-primary" href="/vistas/ver_viaje.php?id=<?= $trip["id_viaje"]?>">Ver detalles</a>  
	        <form class="" action="<?= ($trip["tipo"] == WEEKLY_TRIP)? "/vistas/editar_viaje_semanal.php": "/vistas/editar_viaje.php" ?>" method="post">
            <input type="hidden" name="trip_id" value="<?= $trip["id_viaje"]; ?>">
            <button class="btn" name="">Modificar Viaje</button>
          </form>
	        <form class="" action="/php/baja_viaje.php" method="post">
            <input type="hidden" name="trip_id" value="<?= $trip["id_viaje"]; ?>">
            <button class="btn btn-danger <?= (haveRequest($conn, $trip['id_viaje']))? "delete_trip_with_request" : "delete_trip" ?>" name="">Cancelar viaje</button>
          </form>
          <form action="/vistas/ver_solicitudes.php">
            <input type="hidden" name="trip_id" value=<?= $trip["id_viaje"] ?>>
            <button type="send" class="btn btn-success">
              Ver solicitudes <span class="badge badge-light"><?= countRequests($trip["id_viaje"], $conn) ?></span>
            </button>
          </form>
        </div>
      </div>

      <?php
              }
              $trip = mysqli_fetch_assoc($result);
            }
          }
        } else {
          echo "Hubo un error al conectarse con la base de datos. <br> Por favor, intentelo nuevamente mas tarde.";
        }
        if(!$pending){
          echo "<h2>No hay ningun viaje registrado!</h2><br>";
        }
      ?>
      <button class="btn btn-success"><a href="/vistas/seleccion_viaje.php">Crear Viaje</a></button>
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
  <script src="/js/listar_viajes.js"></script>
  <?php
    get_success("trip_removed_with_average", "Viaje eliminado exitósamente y puntaje actualizado");
    get_success("trip_removed", "Viaje eliminado exitósamente");
    get_success("reg_success", "Viaje creado con éxito!");
    get_success("edit_success", "Viaje editado con éxito!");
    get_error("no_car", "Debe registrar un auto para poder crear un viaje!");
    get_error("date_trip_error", "No se ha creado el viaje, la fecha ya ha pasado");
    get_error("no_card", "Debe registrar una tarjeta para poder crear un viaje!");
    get_error("pending_califications", "Tiene una calificación pendiente, por favor, califique al usuario antes de crear/modificar otro viaje.");
    get_error("db_error", "Ocurrió un error en la base de datos, por favor, inténtelo nuevamente mas tarde.");
    get_error("date_error", "Usted tiene un viaje programado para la fecha ingresada.");
    get_error("expired_card", "La tarjeta elegida expira antes de la fecha del viaje, por favor, seleccione otra tarjeta.");
    get_error("has_requests", "No es posible modificar el viaje, ya que tiene solicitudes.");
  ?>
</html>
