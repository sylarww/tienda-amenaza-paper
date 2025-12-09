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
    $email = mysqli_real_escape_string($conexion, $_POST['email']);
    $telefono = mysqli_real_escape_string($conexion, $_POST['telefono']);
    $edad = mysqli_real_escape_string($conexion, $_POST['edad']);

    // Verificar si el email ya existe
    $check_email = mysqli_query($conexion, "SELECT id FROM clientes WHERE email = '$email'");
    
    if (mysqli_num_rows($check_email) > 0) {
        $mensaje = "<div style='color: red; background: #f8d7da; padding: 10px; border-radius: 5px;'>Error: El email ya está registrado.</div>";
    } else {
        $sql = "INSERT INTO clientes (nombre, email, telefono, edad) 
                VALUES ('$nombre', '$email', '$telefono', '$edad')";

        if (mysqli_query($conexion, $sql)) {
            $mensaje = "<div style='color: green; background: #d4edda; padding: 10px; border-radius: 5px;'>Cliente registrado correctamente!</div>";
        } else {
            $mensaje = "<div style='color: red; background: #f8d7da; padding: 10px; border-radius: 5px;'>Error: " . mysqli_error($conexion) . "</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Cliente</title>
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
        <h2>➕ Registrar Nuevo Cliente</h2>
        
        <?php echo $mensaje; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label for="nombre">Nombre completo:</label>
                <input type="text" id="nombre" name="nombre" required>
            </div>
            
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            
            <div class="form-group">
                <label for="telefono">Teléfono:</label>
                <input type="tel" id="telefono" name="telefono">
            </div>
            
            <div class="form-group">
                <label for="edad">Edad:</label>
                <input type="number" id="edad" name="edad" min="1" max="120">
            </div>
            
            <button type="submit" class="btn">Registrar Cliente</button>
        </form>
        
        <a href="ver_clientes.php" class="back-link">← Volver a Clientes</a>
    </div>
</body>
</html>