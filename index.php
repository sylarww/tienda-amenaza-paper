<?php
$servidor = "localhost";
$usuario = "root";
$clave ="";
$baseDeDatos = "pepelerias";
$enlace = mysqli_connect($servidor,$usuario,$clave,$baseDeDatos);
?>
<DOCTYPE html>
    <html>
        <head>
            <meta charset="utf-8">
      <title style="font-family: 'Times New Roman', Times, serif;">Papeleria: Amenaza Paper</title>

</head>
<body>
    <?php
$img_boligrafo = "imagenes/bobo.jpg";
$img_cuaderno = "imagenes/cuade.jpg";
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Papelería AMENAZA PAPER</title>
<style>
  body {
    margin: 0;
    font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
    background-color: #64f51146;
    color: #333333;
    justify-content: center;
    align-items: center;
  }
  header {
    background-color: #e9f5efff;
    color: #51abffff;
    padding: 25px 20px;
    text-align: center;
    position: relative;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    border-bottom: 1px solid #d1f7eb52;
  }
  header img.logo {
    position: static;
    width: 150px;
    vertical-align: middle;
    margin-right: 15px;
  }
  header .search {
    position: absolute;
    top: 25px;
    right: 20px;
  }
  nav {
    background-color: #e0e5e671;
    text-align: left;
    padding: 15px 20px;
    border-bottom: 1px solid #cffafcff;
  }
  nav a {
    color: #556B2F;
    margin: 0 20px 0 0;
    text-decoration: none;
    font-weight: 500;
    transition: color 0.3s;
  }
  nav a:hover {
    color: #333333;
    text-decoration: underline;
  }
  .carousel {
    display: flex;
    justify-content: center;
    margin: 40px 0;
  }
  .carousel img {
    width: 250px;
    height: auto;
    border: 1px solid #A9B29C;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
    border-radius: 8px;
  }
  footer {
    background-color: #cce0b0ff;
    color: #333333;
    display: flex;
    justify-content: space-around;
    padding: 30px 20px;
    font-size: 14px;
    border-top: none;
    text-shadow: none;
  }
  footer div {
    max-width: 30%;
  }
  .iconos {
    margin-top: 15px;
  }
  .iconos img {
    width: 24px;
    margin-right: 10px;
  }
    form {
   display: block; 
   margin: 0 auto; /* Importante para centrar */
  width: 300px; /* Define un ancho fijo */
   padding: 20px;
   background-color: white;
   border-radius: 8px;
   box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
  h1 {
   text-align: center; /* Centra el título dentro del formulario */
     }
   input[type="text"],
    input[type="submit"],
    input[type="reset"] {
     width: 90%;
     padding: 10px;
     margin-bottom: 10px;
     box-sizing: border-box;
      /*  <div class="search">
      <input type="text" placeholder="Buscar">
    </div>*/
            border: 1px solid #ccc;
            border-radius: 4px;
        }
</style>


</head>
<body bgcolor="black">

  <header>
    <img src="https://colimareptiles.com/wp-content/uploads/2024/06/Squamata_02-1024x682.jpg" alt="Logos" class="logo">
    <h1>Papelería: Amenaza Paper</h1>
    <p>Menaza masa la casa</p>
  </header>

  <nav>
   <a href="index.php" target="_blank"> INICIO </a> 
    <a href="productos.php">CATALOGO ILUSTRATIVO</a>
    <a href="compra.php">COMPRA</a>
    <a href="login.html">INVENTARIO</a>
  </nav>

  <div class="carousel">
    <img src="https://m.media-amazon.com/images/I/71Svc9POaUL.jpg" alt="boligrafo">
  </div>

  <div class="carousel">
    <img src="https://www.20milproductos.com/blog/wp-content/uploads/2024/05/cuadernos-corporativos-1.jpg" alt="cuaderno">
  </div>
<!----------------------------------------------------- 
<div class="search">
      <input type="text" placeholder="Buscar">
    </div>
  <div class="form-container">
    <form action="" name="papeleria" method="post">
      <h1>Ingresa la informacion</h1>

      <input type="text" name="id" placeholder="id">
      <input type="text" name="nombre" placeholder="nombre">
      <input type="text" name="direccion" placeholder="direccion">
      <input type="text" name="telefono" placeholder="telefono">
      <input type="text" name="formatodepago" placeholder="forma de pago"> 
          
      <input type="submit" name="registro" value="Registrar">
      <input type="reset" value="Limpiar">
    </form>
  </div>
      ----------------------------------------------------------------------->
  <footer>
    <div>
      <h3>CONTACTO</h3>
      <p>Amenaza oficial<br>
         numero: 56786767<br>
         gmail: amenazalacompañia@gmail.com<br>
         Dirección: Calle pablo picapizarrines mz 3 lt 6<br>
         C.P. 6767, Ecateponk</p>
         <br>
      <h3>INFORMACION </h3>
      <p>Horario de:<br>
         Lunes a Viernes de 10:00 a.m. a 5:00 p.m.<br>
         Sábados de 11:00 a.m. a 2:00 p.m.<br>
     </p>
    </div>
  </footer>

</body>
</html>
<?php

  if(isset($_POST['registro'])){

      $id = $_POST['id'];
      $nombre = $_POST['nombre'];
      $direccion = $_POST['direccion'];
      $telefono = $_POST['telefono'];
      $formadepago = $_POST['formadepago'];
      
      $insertarDatos = "INSERT INTO inventario VALUES ('$id','$nombre','$direccion','$telefono','$formadepago')";
      
      $ejecutarInsertar = mysqli_query ($enlace,$insertarDatos);
  }
?>