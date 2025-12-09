<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.html");
    exit;
}

include 'conexion.php';

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
    $descripcion = mysqli_real_escape_string($conexion, $_POST['descripcion']);
    $precio = mysqli_real_escape_string($conexion, $_POST['precio']);
    $stock = mysqli_real_escape_string($conexion, $_POST['stock']);
    $categoria = mysqli_real_escape_string($conexion, $_POST['categoria']);

    $sql = "INSERT INTO productos (nombre, descripcion, precio, stock, categoria) 
            VALUES ('$nombre', '$descripcion', '$precio', '$stock', '$categoria')";

    if (mysqli_query($conexion, $sql)) {
        $mensaje = "<div style='color: green; background: #d4edda; padding: 10px; border-radius: 5px;'>Producto insertado correctamente!</div>";
    } else {
        $mensaje = "<div style='color: red; background: #f8d7da; padding: 10px; border-radius: 5px;'>Error: " . mysqli_error($conexion) . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insertar Producto</title>
    <style>
        body {
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background-color: #90d5da46;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #004439;
            text-align: center;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #333;
        }
        input, textarea, select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
        }
        .btn {
            background: #004439;
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        .btn:hover {
            background: #00332a;
        }
        .back-link {
            display: inline-block;
            margin-top: 20px;
            color: #004439;
            text-decoration: none;
        }
        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Insertar Nuevo Producto</h2>
        
        <?php echo $mensaje; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label for="nombre">Nombre del Producto:</label>
                <input type="text" id="nombre" name="nombre" required>
            </div>
            
            <div class="form-group">
                <label for="descripcion">Descripción:</label>
                <textarea id="descripcion" name="descripcion" rows="3"></textarea>
            </div>
            
            <div class="form-group">
                <label for="precio">Precio:</label>
                <input type="number" id="precio" name="precio" step="0.01" min="0" required>
            </div>
            
            <div class="form-group">
                <label for="stock">Stock:</label>
                <input type="number" id="stock" name="stock" min="0" required>
            </div>
            
            <div class="form-group">
                <label for="categoria">Categoría:</label>
                <select id="categoria" name="categoria" required>
                    <option value="">Selecciona una categoría</option>
                    <option value="Escritura">Escritura</option>
                    <option value="Papelería">Papelería</option>
                    <option value="Adhesivos">Adhesivos</option>
                    <option value="Herramientas">Herramientas</option>
                    <option value="Oficina">Oficina</option>
                </select>
            </div>
            
            <button type="submit" class="btn">Insertar Producto</button>
        </form>
        
        <a href="menu_inventario.php" class="back-link">← Volver al Menú de Inventario</a>
    </div>
</body>
</html>