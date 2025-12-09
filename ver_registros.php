<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.html");
    exit;
}

include 'conexion.php';

// Búsqueda por PHP
$busqueda = "";
if (isset($_GET['buscar']) && !empty($_GET['buscar'])) {
    $busqueda = mysqli_real_escape_string($conexion, $_GET['buscar']);
    $where = "WHERE nombre LIKE '%$busqueda%' OR descripcion LIKE '%$busqueda%' OR categoria LIKE '%$busqueda%'";
} else {
    $where = "";
}

// Obtener productos
$productos = mysqli_query($conexion, "SELECT * FROM productos $where ORDER BY id DESC");

// Obtener estadísticas
$totalProductos = mysqli_num_rows($productos);
$sqlStats = mysqli_query($conexion, "SELECT COUNT(*) as total, SUM(stock) as total_stock FROM productos");
$stats = mysqli_fetch_assoc($sqlStats);

// Obtener valor total del inventario
$sqlTotalValue = mysqli_query($conexion, "SELECT SUM(precio * stock) as total_value FROM productos");
$totalValue = mysqli_fetch_assoc($sqlTotalValue);

// Contar productos con stock bajo
$lowStock = mysqli_query($conexion, "SELECT COUNT(*) as low FROM productos WHERE stock < 5");
$lowCount = mysqli_fetch_assoc($lowStock)['low'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver Registros - Inventario</title>
    <style>
        body {
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background-color: #90d5da46;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #004439;
            text-align: center;
            margin-bottom: 30px;
        }
        .search-form {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
            text-align: center;
            border-left: 4px solid #004439;
        }
        .search-form input {
            padding: 10px;
            width: 300px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-right: 10px;
        }
        .search-form button {
            padding: 10px 20px;
            background: #004439;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .search-form button:hover {
            background: #00332a;
        }
        .stats {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            border-left: 4px solid #004439;
        }
        .stat-item {
            text-align: center;
            padding: 15px;
        }
        .stat-number {
            font-size: 28px;
            font-weight: bold;
            color: #004439;
            display: block;
        }
        .stat-label {
            color: #666;
            font-size: 14px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #004439;
            color: white;
            font-weight: 600;
        }
        tr:hover {
            background-color: #f8f9fa;
        }
        .stock-low {
            color: #dc3545;
            font-weight: bold;
            background-color: #ffe6e6;
            padding: 4px 8px;
            border-radius: 4px;
        }
        .stock-medium {
            color: #ffc107;
            font-weight: bold;
            background-color: #fff3cd;
            padding: 4px 8px;
            border-radius: 4px;
        }
        .stock-good {
            color: #28a745;
            font-weight: bold;
            background-color: #e6f4ea;
            padding: 4px 8px;
            border-radius: 4px;
        }
        .price {
            font-weight: bold;
            color: #2c3e50;
        }
        .back-link {
            display: inline-block;
            margin-top: 25px;
            color: #004439;
            text-decoration: none;
            padding: 10px 20px;
            border: 2px solid #004439;
            border-radius: 5px;
            transition: all 0.3s ease;
        }
        .back-link:hover {
            background: #004439;
            color: white;
            text-decoration: none;
        }
        .empty-message {
            text-align: center;
            color: #666;
            padding: 60px 20px;
            font-style: italic;
            background: #f8f9fa;
            border-radius: 8px;
            margin: 20px 0;
        }
        .actions {
            text-align: center;
            margin: 20px 0;
        }
        .btn-export {
            background: #28a745;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            margin-left: 10px;
            border: none;
            cursor: pointer;
        }
        .btn-export:hover {
            background: #218838;
        }
        .categoria-badge {
            background: #e9ecef;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
            color: #495057;
        }
        .alert-low {
            background: #f8d7da;
            color: #721c24;
            padding: 10px 15px;
            border-radius: 5px;
            margin: 10px 0;
            border-left: 4px solid #dc3545;
        }
        .results-info {
            background: #e9f5ef;
            padding: 10px 15px;
            border-radius: 5px;
            margin: 10px 0;
            font-size: 0.9em;
        }
        @media (max-width: 768px) {
            .container {
                padding: 15px;
            }
            table {
                font-size: 14px;
            }
            th, td {
                padding: 8px 10px;
            }
            .stats {
                grid-template-columns: 1fr;
            }
            .search-form input {
                width: 100%;
                margin-bottom: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>📋 Inventario Completo de Productos</h2>
        
        <!-- Formulario de búsqueda -->
        <div class="search-form">
            <form method="GET" action="">
                <input type="text" name="buscar" value="<?php echo htmlspecialchars($busqueda); ?>" 
                       placeholder="Buscar por nombre, descripción o categoría...">
                <button type="submit">🔍 Buscar</button>
                <?php if (!empty($busqueda)): ?>
                    <a href="ver_registros.php" style="color: #004439; margin-left: 10px;">❌ Limpiar búsqueda</a>
                <?php endif; ?>
            </form>
        </div>

        <!-- Estadísticas -->
        <div class="stats">
            <div class="stat-item">
                <span class="stat-number"><?php echo $stats['total'] ?: 0; ?></span>
                <span class="stat-label">Productos Totales</span>
            </div>
            <div class="stat-item">
                <span class="stat-number"><?php echo $stats['total_stock'] ?: 0; ?></span>
                <span class="stat-label">Unidades en Stock</span>
            </div>
            <div class="stat-item">
                <span class="stat-number">$<?php echo number_format($totalValue['total_value'] ?: 0, 2); ?></span>
                <span class="stat-label">Valor Total Inventario</span>
            </div>
        </div>

        <!-- Alerta de stock bajo -->
        <?php if ($lowCount > 0): ?>
            <div class="alert-low">
                ⚠️ <strong>Alerta:</strong> <?php echo $lowCount; ?> producto(s) con stock bajo (menos de 5 unidades)
            </div>
        <?php endif; ?>

        <!-- Información de resultados -->
        <?php if (!empty($busqueda)): ?>
            <div class="results-info">
                🔍 <strong>Resultados de búsqueda para:</strong> "<?php echo htmlspecialchars($busqueda); ?>"
                <br><em>Se encontraron <?php echo $totalProductos; ?> producto(s)</em>
            </div>
        <?php endif; ?>

        <!-- Acciones -->
        <div class="actions">
            <a href="menu_inventario.php" class="back-link">← Volver al Menú</a>
            <button class="btn-export" onclick="window.print()">🖨️ Imprimir Reporte</button>
        </div>

        <!-- Tabla de productos -->
        <?php if ($totalProductos > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Producto</th>
                        <th>Descripción</th>
                        <th>Precio</th>
                        <th>Stock</th>
                        <th>Categoría</th>
                        <th>Fecha Registro</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($producto = mysqli_fetch_assoc($productos)): 
                        // Determinar clase de stock
                        $stockClass = '';
                        if ($producto['stock'] < 5) {
                            $stockClass = 'stock-low';
                        } elseif ($producto['stock'] < 15) {
                            $stockClass = 'stock-medium';
                        } else {
                            $stockClass = 'stock-good';
                        }
                    ?>
                    <tr>
                        <td><strong>#<?php echo $producto['id']; ?></strong></td>
                        <td>
                            <strong><?php echo htmlspecialchars($producto['nombre']); ?></strong>
                        </td>
                        <td><?php echo htmlspecialchars($producto['descripcion'] ?: 'Sin descripción'); ?></td>
                        <td class="price">$<?php echo number_format($producto['precio'], 2); ?></td>
                        <td>
                            <span class="<?php echo $stockClass; ?>">
                                <?php echo $producto['stock']; ?> unidades
                            </span>
                        </td>
                        <td>
                            <span class="categoria-badge"><?php echo htmlspecialchars($producto['categoria']); ?></span>
                        </td>
                        <td><?php echo date('d/m/Y H:i', strtotime($producto['fecha_creacion'])); ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

            <!-- Resumen final -->
            <div style="margin-top: 20px; padding: 15px; background: #e9f5ef; border-radius: 5px;">
                <strong>Resumen:</strong> 
                <?php if (empty($busqueda)): ?>
                    Mostrando <?php echo $totalProductos; ?> producto(s) en el inventario.
                <?php else: ?>
                    Mostrando <?php echo $totalProductos; ?> producto(s) que coinciden con la búsqueda.
                <?php endif; ?>
                
                <?php if ($lowCount > 0): ?>
                    <span style="color: #dc3545; margin-left: 15px;">
                        ⚠️ <?php echo $lowCount; ?> producto(s) con stock bajo
                    </span>
                <?php endif; ?>
            </div>

        <?php else: ?>
            <div class="empty-message">
                <?php if (empty($busqueda)): ?>
                    <h3>📭 No hay productos en el inventario</h3>
                    <p>No se han encontrado productos registrados en el sistema.</p>
                    <p>Puedes <a href="insertar_registro.php" style="color: #004439;">agregar el primer producto</a> para comenzar.</p>
                <?php else: ?>
                    <h3>🔍 No se encontraron resultados</h3>
                    <p>No hay productos que coincidan con "<strong><?php echo htmlspecialchars($busqueda); ?></strong>".</p>
                    <p>Intenta con otros términos de búsqueda o <a href="ver_registros.php" style="color: #004439;">ver todos los productos</a>.</p>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <!-- Enlace de regreso -->
        <div style="text-align: center; margin-top: 30px;">
            <a href="menu_inventario.php" class="back-link">← Volver al Menú de Inventario</a>
        </div>
    </div>
</body>
</html>

<?php 
// Cerrar conexión
mysqli_close($conexion);
?>