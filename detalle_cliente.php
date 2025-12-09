<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.html");
    exit;
}

include 'conexion.php';

if (!isset($_GET['id'])) {
    header("Location: ver_clientes.php");
    exit;
}

$cliente_id = mysqli_real_escape_string($conexion, $_GET['id']);
$cliente = mysqli_fetch_assoc(mysqli_query($conexion, "SELECT * FROM clientes WHERE id = '$cliente_id'"));

if (!$cliente) {
    header("Location: ver_clientes.php");
    exit;
}

// Obtener ventas del cliente
$ventas = mysqli_query($conexion, "
    SELECT v.*, 
           COUNT(dv.id) as total_productos,
           GROUP_CONCAT(CONCAT(dv.producto_nombre, ' (', dv.cantidad, ' unidades)') SEPARATOR ', ') as productos
    FROM ventas v
    LEFT JOIN detalle_venta dv ON v.id = dv.venta_id
    WHERE v.cliente_id = '$cliente_id'
    GROUP BY v.id
    ORDER BY v.fecha_venta DESC
");

// Obtener estadísticas del cliente
$stats = mysqli_fetch_assoc(mysqli_query($conexion, "
    SELECT COUNT(*) as total_ventas,
           SUM(total) as total_gastado,
           AVG(total) as promedio_venta,
           MIN(fecha_venta) as primera_compra
    FROM ventas 
    WHERE cliente_id = '$cliente_id'
"));
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle del Cliente</title>
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
        .cliente-header {
            background: #f8f9fa;
            padding: 25px;
            border-radius: 8px;
            margin-bottom: 30px;
            border-left: 4px solid #004439;
        }
        .cliente-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }
        .info-item {
            text-align: center;
        }
        .info-label {
            font-weight: bold;
            color: #004439;
            display: block;
        }
        .info-value {
            font-size: 1.1em;
        }
        .stats {
            background: #e9f5ef;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
        }
        .stat-item {
            text-align: center;
        }
        .stat-number {
            font-size: 24px;
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
            padding: 40px 20px;
            font-style: italic;
            background: #f8f9fa;
            border-radius: 8px;
            margin: 20px 0;
        }
        .venta-id {
            font-weight: bold;
            color: #004439;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>📋 Detalles del Cliente</h2>
        
        <div class="cliente-header">
            <h3><?php echo htmlspecialchars($cliente['nombre']); ?></h3>
            <div class="cliente-info">
                <div class="info-item">
                    <span class="info-label">Email:</span>
                    <span class="info-value"><?php echo htmlspecialchars($cliente['email']); ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Teléfono:</span>
                    <span class="info-value"><?php echo $cliente['telefono'] ?: 'No registrado'; ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Edad:</span>
                    <span class="info-value"><?php echo $cliente['edad'] ? $cliente['edad'] . ' años' : 'No registrada'; ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Registro:</span>
                    <span class="info-value"><?php echo date('d/m/Y', strtotime($cliente['fecha_registro'])); ?></span>
                </div>
            </div>
        </div>

        <?php if ($stats['total_ventas'] > 0): ?>
            <div class="stats">
                <div class="stat-item">
                    <span class="stat-number"><?php echo $stats['total_ventas']; ?></span>
                    <span class="stat-label">Compras Realizadas</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">$<?php echo number_format($stats['total_gastado'], 2); ?></span>
                    <span class="stat-label">Total Gastado</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">$<?php echo number_format($stats['promedio_venta'], 2); ?></span>
                    <span class="stat-label">Promedio por Compra</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number"><?php echo date('d/m/Y', strtotime($stats['primera_compra'])); ?></span>
                    <span class="stat-label">Primera Compra</span>
                </div>
            </div>

            <h3>Historial de Compras</h3>
            <table>
                <thead>
                    <tr>
                        <th>Venta ID</th>
                        <th>Fecha</th>
                        <th>Total</th>
                        <th>Productos Comprados</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($venta = mysqli_fetch_assoc($ventas)): ?>
                    <tr>
                        <td class="venta-id">#<?php echo $venta['id']; ?></td>
                        <td><?php echo date('d/m/Y H:i', strtotime($venta['fecha_venta'])); ?></td>
                        <td><strong>$<?php echo number_format($venta['total'], 2); ?></strong></td>
                        <td><?php echo $venta['productos'] ?: 'No hay detalles'; ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="empty-message">
                <h3>📭 No hay compras registradas</h3>
                <p>Este cliente aún no ha realizado ninguna compra.</p>
                <p>Las compras aparecerán aquí una vez que se realicen.</p>
            </div>
        <?php endif; ?>

        <div style="text-align: center; margin-top: 30px;">
            <a href="ver_clientes.php" class="back-link">← Volver a Lista de Clientes</a>
        </div>
    </div>
</body>
</html>

<?php mysqli_close($conexion); ?>