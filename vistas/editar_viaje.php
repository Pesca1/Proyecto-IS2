<?php
  include("../php/verficar_sesion.php");
  include("../php/abrir_conexion.php");
  include("../php/utils.php");

  if(hasOldCalifications($conn, $_SESSION["user_id"])) {
    header("Location: /vistas/ver_viajes.php?pending_califications");
    exit();
  } else if(dbOcurrences($conn, "SELECT * FROM solicitud WHERE id_viaje=".$_POST["trip_id"]) > 0){
    header("Location: /vistas/ver_viajes.php?has_requests");
    exit();
  }
  setlocale(LC_MONETARY, 'es_AR');
  $trip_id = $_POST['trip_id'];
  $query = "SELECT * FROM viajes WHERE id_viaje='".$trip_id."tr'";
  $result = mysqli_query($conn, $query);
  $trip = mysqli_fetch_assoc($result);
  $register = $trip["patente"];
  $query = "SELECT * FROM vehiculo WHERE patente='$register'";
  $result = mysqli_query($conn, $query);
  $car = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html>
  <head> 
    <link href="/css/bootstrap.css" rel="stylesheet" type="text/css" >
    <link href="/css/index.css" rel="stylesheet" type="text/css">
    <link href="/css/listar_vehiculos.css" rel="stylesheet" type="text/css">
  </head>
  <body> 
    <?php
      include("header.php");
    ?><br><br>
    <div class="container">
      <h3>Editar viaje: <?= $trip["origen"] ?> --> <?= $trip["destino"] ?> del <?= date("d/m/Y H:i", strtotime($trip["fecha_hora"])); ?></h3>
      <form action="/php/editar_viaje.php?edit=true" id="trip_reg" method="post" autocomplete="off">
        <input name="trip_id" type="hidden" value="<?= $trip["id_viaje"] ?>"
      <?php
          $query = "SELECT * FROM vehiculo WHERE id_usuario='".$_SESSION["user_id"]."'";
          $result = mysqli_query($conn, $query);

          if($result){
            while($vehicle = mysqli_fetch_assoc($result)){
              ?>
            
          <label for="" class="form-check-label">Vehículo:</label><br><br>
          <input class="form-check-input" type="radio" name="car_plate" value="<?= $vehicle["patente"] ?>" <?php if ($vehicle["patente"] == $car["patente"]) echo "checked" ?>/>
          <?php echo($vehicle["marca"]." ".$vehicle["modelo"]) ?>
        
              <?php
            }
          }
        ?><br><br><br>
        <div class="form-row">
          <div class="form-group col-md-4">
            <div class="input-group mb-3">
              <div class="input-group-prepend">
                <span class="input-group-text">Origen:</span>
              </div>
              <input type="text" class="form-control" name="origin" required="required" value="<?php echo $trip['origen']; ?>">
            </div>         
          </div>
          <div class="form-group col-md-4">
            <div class="input-group mb-3">
              <div class="input-group-prepend">
                <span class="input-group-text">Destino:</span>
              </div>
              <input type="text" class="form-control" name="destination" required="required" value="<?php echo $trip['destino']; ?>">
            </div> 
          </div>
        </div>
          <?php
            $trip_day = date("d", strtotime($trip["fecha_hora"]));
            $trip_month = date("m", strtotime($trip["fecha_hora"]));
            $trip_hour = date("H", strtotime($trip["fecha_hora"]));
            $trip_minute = date("i", strtotime($trip["fecha_hora"]));
            $trip_hour_minuete = $trip_hour.":".$trip_minute;
            $duration_hours = floor($trip['duracion']);
            $duration_minutes = $trip['duracion'] - $duration_hours;
            if($duration_minutes > 0){
              $duration_minutes = $duration_minutes * 60;
            }
          ?>
        Duración:
        <div class="form-row">
          <div class="form-group col-md-2">
            <div class="input-group mb-3">
              <div class="input-group-prepend">
                <span class="input-group-text">Horas:</span>
              </div>
              <input type="number" min="0" value="<?php echo $duration_hours; ?>" class="form-control" name="duration_hours" required="required">
            </div>
          </div>
          <div class="form-group col-md-2">
            <div class="input-group mb-3">
              <div class="input-group-prepend">
                <span class="input-group-text">Minutos:</span>
              </div>
              <input type="number" min="0" max="59" value="<?php echo $duration_minutes; ?>" class="form-control" name="duration_minutes" required="required" >
            </div>
          </div>


          <div class="form-group col-md-4">
            <div class="input-group mb-3">
              <div class="input-group-prepend">
                <span class="input-group-text">Costo ($)</span>
              </div>
              <input type="text" class="form-control" name="price" required="required" value="<?php echo floatval($trip['costo']); ?>">
            </div>
          </div>
        </div>

        <div class="form-row">
          <div class="form-group col-md-4">
            <label for="destination">Fecha y hora</label> <br>
            Día:
            <select name="day" id="day">
              <?php
                for($i = 1; $i <= 31; $i++){
                  if ($trip_day == $i){
                    echo "<option selected>$i</option>";
                  }else
                    echo "<option>$i</option>";
                  
                }
                ?>
            </select>
            - Mes:
            <select name="month" id="month">
              <?php
                for($i = intval(date("n")); $i <= intval(date("n"))+1; $i++){
                  if ($trip_month == $i){
                    echo "<option selected>$i</option>";
                  }else
                  echo "<option>$i</option>";
                }
                ?>
            </select>
            - Hora:
              <input type="time" name="time" value=<?= $trip_hour_minuete ?> required="required"/>
          </div>
          <div class="form-group col-md-4">
            <label for="credit_card">Tarjeta:</label>
            <?php
              $query = "SELECT * FROM tarjetas WHERE id_usuario='".$_SESSION["user_id"]."'";
              $result = mysqli_query($conn, $query);
              
              $card_id = $trip["tarjeta"];
              $query2 = "SELECT * FROM tarjetas WHERE numero='$card_id'";
              $result2 = mysqli_query($conn, $query2);
              $trip_card = mysqli_fetch_assoc($result2);

              if($result){
                while($card = mysqli_fetch_assoc($result)){
            ?>
            <div class="option">
              <input type="radio" name="card" value="<?= $card["numero"] ?>" required="required" <?php if ($trip_card["numero"] == $card["numero"]) echo "checked" ?> />
              Código: <?= formatCard($card["numero"]) ?>
            </div>
            <?php
                }
              }
            ?>
          </div>
        </div>

        <div class="form-row">
          <div class="form-group col-md-4">
            <label for="description" >Descripción:</label><br>
            <textarea class="form-control" name="description" rows="5" cols="30" wrap="hard" style="resize: none" ><?php echo $trip['descripcion']; ?></textarea>
          </div>
        </div>
        <input type="submit" class="btn btn-primary" value="Guardar" id="submit"/>  
      </form>
    </div>


  </body>
  <?php 
    include("bootstrap.php"); 
    include("../php/cerrar_conexion.php");
  ?>
  <script src="/js/registrar_usuario.js"></script>
  <script src="/js/registrar_viaje.js"></script>
  </html>
