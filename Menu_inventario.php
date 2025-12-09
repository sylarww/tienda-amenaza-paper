<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.html");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menú Inventario</title>
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background-color: #90d5da46;
            color: #0000006b;
            padding: 20px;
        }
        header {
            background-color: #e9f5efff;
            color: #000000ff;
            padding: 25px 20px;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            border-bottom: 1px solid #0b0f0e52;
            margin-bottom: 20px;
        }
        .menu-container {
            max-width: 800px;
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
        .menu-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }
        .menu-card {
            background: #f8f9fa;
            padding: 25px;
            border-radius: 8px;
            text-align: center;
            text-decoration: none;
            color: #333;
            border: 2px solid #004439;
            transition: all 0.3s ease;
        }
        .menu-card:hover {
            background: #004439;
            color: white;
            transform: translateY(-5px);
        }
        .logout {
            text-align: center;
            margin-top: 30px;
        }
        .btn-logout {
            background: #dc3545;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
        }
        .btn-logout:hover {
            background: #c82333;
        }
    </style>
</head>
<body>
    <header>
        <h1>Sistema de Inventario - Papelería</h1>
    </header>

    <div class="menu-container">
        <h2>Bienvenido, <?php echo htmlspecialchars($_SESSION['usuario']); ?>!</h2>
        <p>Selecciona la tarea a realizar:</p>
        
        <div class="menu-grid">
            <a href="insertar_registro.php" class="menu-card">
                <h3>➕ Insertar Registro</h3>
                <p>Agregar nuevo producto al inventario</p>
            </a>
            
            <a href="eliminar_registro.php" class="menu-card">
                <h3>🗑️ Eliminar Registro</h3>
                <p>Remover producto del inventario</p>
            </a>
            
            <a href="ver_registros.php" class="menu-card">
                <h3>👁️ Ver Registros</h3>
                <p>Consultar todos los productos</p>
            </a>
            <a href="registrar_clientes.php" class="menu-card">
                <h3>🧑registar Clientes</h3>
                <p>añade clientes</p>
            </a>
            <a href="index.php" class="menu-card">
                <h3>🏠 Página Principal</h3>
                <p>Volver al inicio</p>
            </a>
            <!-- En la sección del grid, agrega esta nueva tarjeta: -->
            <a href="ver_clientes.php" class="menu-card">
           <h3>👥 Ver Clientes</h3>
          <p>Gestionar clientes y ver historial de compras</p>
            </a>
        </div>

        
        
        <p style="text-align: center; margin-top: 20px; color: #666; font-size: 0.9em;">
            Este contenido es solo para desarrolladores, administradores, y equipo de servicio técnico.
        </p>
    </div>
</body>
</html>