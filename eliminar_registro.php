<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.html");
    exit;
}

include 'conexion.php';

$mensaje = "";

if (isset($_GET['eliminar_id'])) {
    $id = mysqli_real_escape_string($conexion, $_GET['eliminar_id']);
    $sql = "DELETE FROM productos WHERE id = '$id'";
    
    if (mysqli_query($conexion, $sql)) {
        $mensaje = "<div style='color: green; background: #d4edda; padding: 10px; border-radius: 5px;'>Producto eliminado correctamente!</div>";
    } else {
        $mensaje = "<div style='color: red; background: #f8d7da; padding: 10px; border-radius: 5px;'>Error al eliminar: " . mysqli_error($conexion) . "</div>";
    }
}

// Obtener productos para mostrar
$productos = mysqli_query($conexion, "SELECT * FROM productos ORDER BY nombre");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar Producto</title>
    <style>
        body {
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background-color: #90d5da46;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 1000px;
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
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #004439;
            color: white;
        }
        tr:hover {
            background-color: #f5f5f5;
        }
        .btn-eliminar {
            background: #dc3545;
            color: white;
            padding: 8px 15px;
            text-decoration: none;
            border-radius: 5px;
            border: none;
            cursor: pointer;
        }
        .btn-eliminar:hover {
            background: #c82333;
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
        .empty-message {
            text-align: center;
            color: #666;
            padding: 40px;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Eliminar Productos del Inventario</h2>
        
        <?php echo $mensaje; ?>
        
        <?php if (mysqli_num_rows($productos) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Precio</th>
                        <th>Stock</th>
                        <th>Categoría</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($producto = mysqli_fetch_assoc($productos)): ?>
                    <tr>
                        <td><?php echo $producto['id']; ?></td>
                        <td><?php echo htmlspecialchars($producto['nombre']); ?></td>
                        <td>$<?php echo number_format($producto['precio'], 2); ?></td>
                        <td><?php echo $producto['stock']; ?></td>
                        <td><?php echo htmlspecialchars($producto['categoria']); ?></td>
                        <td>
                            <a href="?eliminar_id=<?php echo $producto['id']; ?>" 
                               class="btn-eliminar" 
                               onclick="return confirm('¿Estás seguro de que quieres eliminar <?php echo addslashes($producto['nombre']); ?>?')">
                                Eliminar
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="empty-message">
                No hay productos en el inventario.
            </div>
        <?php endif; ?>
        
        <a href="menu_inventario.php" class="back-link">← Volver al Menú de Inventario</a>
    </div>
</body>
</html>

<?php mysqli_close($conexion); ?>