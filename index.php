<!DOCTYPE html>
<html>
  <head>
    <title>Aventón</title>
    <meta charset= "UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="/css/bootstrap.css" rel="stylesheet" type="text/css">
    <link href="/css/index.css" rel="stylesheet" type="text/css">
  </head>
  <body>
    <div id="header">
      <h2>Aventón</h2>
    </div>
    <div id="body">
      <h1>Bienvenido a Aventón!</h1>
      <div class="form_container">
        <h2>Iniciar sesión</h2>
        <form>
          <h3>Ingrese su mail:</h3>
          <input type="email" name="mail">
          <br>
          <h3>Ingrese su contraseña:</h3>
          <input type="password" name="password">
          <br><br>
          <input type="submit" text="Iniciar Sesión">
          <br><br>
          <a href="/php/recuperar_contraseña.php">Olvide mi contraseña!</a>
        </form>
      </div>
      <div class="form_container">
        <h2>Crear una cuenta</h2>
        <form action="/php/registrar_usuario.php" method="post">
          <h3>Ingrese su nombre completo:</h3>
          <input type="text" name="name">
          <br>
          <h3>Ingrese su mail:</h3>
          <input type="email" name="mail">
          <br>
          <h3>Ingrese su fecha de nacimiento:</h3>
          Día:
          <select name="birth_day">
            <?php
              for($i = 1; $i <= 31; $i++){
                echo "<option>$i</option>";
              }
              ?>
          </select>
           - Mes:
          <select name="birth_month">
            <?php
              for($i = 1; $i <= 12; $i++){
                echo "<option>$i</option>";
              }
            ?>
          </select>
           - Año:
          <select name="birth_year">
            <?php
              for($i = 2000; $i >= 1940; $i--){
                echo "<option>$i</option>";
              }
            ?>
          </select>
          <h3>Ingrese su contraseña:</h3>
          <input type="password" name="password">
          <br>
          <h3>Ingrese su contraseña nuevamente:</h3>
          <input type="password" name="password_confirmation">
          <br>
          <br>
          <input type="submit" text="Iniciar Sesión">
        </form>
      </div>
    </div>
    <div id="footer">
      <a id="help_link"  href="/php/ayuda.php">Ayuda y Contacto</a>
    </div>
  </body>
  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</html>
