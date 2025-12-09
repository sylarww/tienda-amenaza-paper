<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.html");
    exit;
}

include 'conexion.php';

// Obtener clientes con sus compras
$clientes = mysqli_query($conexion, "
    SELECT c.*, 
           COUNT(v.id) as total_compras,
           SUM(v.total) as total_gastado,
           MAX(v.fecha_venta) as ultima_compra
    FROM clientes c
    LEFT JOIN ventas v ON c.id = v.cliente_id
    GROUP BY c.id
    ORDER BY c.fecha_registro DESC
");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clientes y Ventas</title>
    <style>
        body {
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background-color: #90d5da46;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 1400px;
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
        .btn {
            background: #004439;
            color: white;
            padding: 8px 15px;
            text-decoration: none;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            margin: 2px;
        }
        .btn:hover {
            background: #00332a;
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
        .badge {
            background: #28a745;
            color: white;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 0.8em;
        }
        .customer-info {
            background: #e9f5ef;
            padding: 10px;
            border-radius: 5px;
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>👥 Gestión de Clientes y Ventas</h2>
        
        <?php 
        // Estadísticas
        $total_clientes = mysqli_num_rows($clientes);
        $sql_stats = mysqli_fetch_assoc(mysqli_query($conexion, "
            SELECT COUNT(*) as total_clientes,
                   SUM(total) as ventas_totales,
                   AVG(total) as promedio_venta
            FROM ventas
        "));
        ?>
        
        <div class="stats">
            <div class="stat-item">
                <span class="stat-number"><?php echo $total_clientes; ?></span>
                <span class="stat-label">Clientes Registrados</span>
            </div>
            <div class="stat-item">
                <span class="stat-number">$<?php echo number_format($sql_stats['ventas_totales'] ?: 0, 2); ?></span>
                <span class="stat-label">Ventas Totales</span>
            </div>
            <div class="stat-item">
                <span class="stat-number">$<?php echo number_format($sql_stats['promedio_venta'] ?: 0, 2); ?></span>
                <span class="stat-label">Promedio por Venta</span>
            </div>
        </div>

        <?php if ($total_clientes > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Cliente</th>
                        <th>Contacto</th>
                        <th>Edad</th>
                        <th>Compras</th>
                        <th>Total Gastado</th>
                        <th>Última Compra</th>
                        <th>Registro</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($cliente = mysqli_fetch_assoc($clientes)): ?>
                    <tr>
                        <td><strong>#<?php echo $cliente['id']; ?></strong></td>
                        <td>
                            <strong><?php echo htmlspecialchars($cliente['nombre']); ?></strong>
                        </td>
                        <td>
                            <div><?php echo htmlspecialchars($cliente['email']); ?></div>
                            <div style="font-size: 0.9em; color: #666;"><?php echo $cliente['telefono'] ?: 'Sin teléfono'; ?></div>
                        </td>
                        <td><?php echo $cliente['edad'] ?: 'N/A'; ?> años</td>
                        <td>
                            <span class="badge"><?php echo $cliente['total_compras']; ?> compras</span>
                        </td>
                        <td><strong>$<?php echo number_format($cliente['total_gastado'] ?: 0, 2); ?></strong></td>
                        <td>
                            <?php echo $cliente['ultima_compra'] ? date('d/m/Y H:i', strtotime($cliente['ultima_compra'])) : 'Sin compras'; ?>
                        </td>
                        <td><?php echo date('d/m/Y', strtotime($cliente['fecha_registro'])); ?></td>
                        <td>
                            <a href="detalle_cliente.php?id=<?php echo $cliente['id']; ?>" class="btn">Ver Detalles</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="empty-message">
                <h3>📭 No hay clientes registrados</h3>
                <p>No se han encontrado clientes en el sistema.</p>
                <p>Los clientes se registran automáticamente al realizar compras.</p>
            </div>
        <?php endif; ?>

        <div style="text-align: center; margin-top: 30px;">
            <a href="menu_inventario.php" class="back-link">← Volver al Menú de Inventario</a>
        </div>
    </div>
</body>
</html>

<?php mysqli_close($conexion); ?>