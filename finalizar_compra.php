<?php
session_start();

// Verificar si hay productos en el carrito
if (empty($_SESSION['carrito'])) {
    header("Location: compra.php");
    exit;
}

// Conexión a la base de datos
$servidor = "localhost";
$usuario = "root";
$clave = "";
$baseDeDatos = "pepelerias";
$enlace = mysqli_connect($servidor, $usuario, $clave, $baseDeDatos);

if (!$enlace) {
    die("Error de conexión: " . mysqli_connect_error());
}

// Obtener clientes existentes para el selector
$clientes_result = mysqli_query($enlace, "SELECT * FROM clientes ORDER BY nombre");

// Procesar compra
$mensaje_exito = "";
$mensaje_error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['finalizar_compra'])) {
    $cliente_id = mysqli_real_escape_string($enlace, $_POST['cliente_id']);
    
    // Verificar que se seleccionó un cliente válido
    if (empty($cliente_id)) {
        $mensaje_error = "Error: Debes seleccionar un cliente.";
    } else {
        // Verificar que el cliente existe
        $check_cliente = mysqli_query($enlace, "SELECT id FROM clientes WHERE id = '$cliente_id'");
        if (mysqli_num_rows($check_cliente) == 0) {
            $mensaje_error = "Error: El cliente seleccionado no existe.";
        } else {
            $total_venta = 0;
            
            // Calcular total
            foreach ($_SESSION['carrito'] as $producto) {
                $total_venta += $producto['precio'] * $producto['cantidad'];
            }
            
            // Registrar venta
            $sql_venta = "INSERT INTO ventas (cliente_id, total) VALUES ('$cliente_id', '$total_venta')";
            
            if (mysqli_query($enlace, $sql_venta)) {
                $venta_id = mysqli_insert_id($enlace);
                $detalles_ok = true;
                
                // Registrar detalles de venta
                foreach ($_SESSION['carrito'] as $producto) {
                    $producto_nombre = mysqli_real_escape_string($enlace, $producto['nombre']);
                    $precio = $producto['precio'];
                    $cantidad = $producto['cantidad'];
                    $subtotal = $precio * $cantidad;
                    $cliente_nombre = 'nombre_cliente';
                    // Insertar detalle de venta
                    $sql_detalle = "INSERT INTO detalle_venta (venta_id, cliente_id, nombre_cliente, producto_nombre, precio_unitario, cantidad, subtotal) 
               VALUES ('$venta_id', '$cliente_id', '$cliente_nombre', '$producto_nombre', '$precio', '$cantidad', '$subtotal')";
                    
                    if (!mysqli_query($enlace, $sql_detalle)) {
                        $detalles_ok = false;
                        $mensaje_error = "Error al registrar detalles: " . mysqli_error($enlace);
                        break;
                    }
                    
                    // Actualizar stock (solo para productos que existen en la BD)
                    $sql_update_stock = "UPDATE productos SET stock = stock - $cantidad 
                                       WHERE nombre = '$producto_nombre' AND stock >= $cantidad";
                    mysqli_query($enlace, $sql_update_stock);
                }
                
                if ($detalles_ok) {
                    // Vaciar carrito y mostrar éxito
                    $_SESSION['carrito'] = [];
                    $mensaje_exito = "¡Compra registrada exitosamente!<br>
                                     Venta ID: <strong>$venta_id</strong><br>
                                     Total: <strong>$" . number_format($total_venta, 2) . "</strong>";
                }
            } else {
                $mensaje_error = "Error al registrar la venta: " . mysqli_error($enlace);
            }
        }
    }
}

// Calcular total actual del carrito
$total_carrito = 0;
foreach ($_SESSION['carrito'] as $item) {
    $total_carrito += $item['precio'] * $item['cantidad'];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finalizar Compra</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        h1 {
            text-align: center;
            color: #004439;
            margin-bottom: 30px;
        }

        .resumen-compra {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
            border-left: 4px solid #004439;
        }

        .producto-item {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #ddd;
        }

        .total-final {
            font-size: 1.3em;
            font-weight: bold;
            text-align: right;
            margin-top: 15px;
            color: #e74c3c;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #333;
        }

        .form-group select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            box-sizing: border-box;
        }

        .btn {
            padding: 12px 25px;
            font-size: 16px;
            color: white;
            background-color: #28a745;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }

        .btn:hover {
            background-color: #218838;
        }

        .btn-cancelar {
            background-color: #6c757d;
        }

        .btn-cancelar:hover {
            background-color: #5a6268;
        }

        .mensaje-exito {
            background: #d4edda;
            color: #155724;
            padding: 20px;
            border-radius: 5px;
            text-align: center;
            margin-bottom: 20px;
            border: 1px solid #c3e6cb;
        }

        .mensaje-error {
            background: #f8d7da;
            color: #721c24;
            padding: 20px;
            border-radius: 5px;
            text-align: center;
            margin-bottom: 20px;
            border: 1px solid #f5c6cb;
        }

        .acciones {
            text-align: center;
            margin-top: 30px;
        }

        .cliente-info {
            background: #e9f5ef;
            padding: 15px;
            border-radius: 5px;
            margin: 10px 0;
        }

        .cliente-option {
            padding: 8px;
            margin: 2px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>✅ Finalizar Compra</h1>

        <?php if (!empty($mensaje_exito)): ?>
            <div class="mensaje-exito">
                <h3>¡Compra Exitosa!</h3>
                <p><?php echo $mensaje_exito; ?></p>
                <div class="acciones">
                    <a href="compra.php" class="btn">🛒 Realizar otra compra</a>
                    <a href="ver_clientes.php" class="btn btn-cancelar">👥 Ver Clientes</a>
                </div>
            </div>
        <?php else: ?>
            
            <?php if (!empty($mensaje_error)): ?>
                <div class="mensaje-error">
                    <h3>Error</h3>
                    <p><?php echo $mensaje_error; ?></p>
                </div>
            <?php endif; ?>

            <!-- Resumen de la compra -->
            <div class="resumen-compra">
                <h3>📋 Resumen de tu compra</h3>
                <?php foreach ($_SESSION['carrito'] as $item): ?>
                    <div class="producto-item">
                        <span>
                            <strong><?php echo htmlspecialchars($item['nombre']); ?></strong><br>
                            <small>Cantidad: <?php echo $item['cantidad']; ?> x $<?php echo number_format($item['precio'], 2); ?></small>
                        </span>
                        <span>$<?php echo number_format($item['precio'] * $item['cantidad'], 2); ?></span>
                    </div>
                <?php endforeach; ?>
                <div class="total-final">
                    Total a Pagar: $<?php echo number_format($total_carrito, 2); ?>
                </div>
            </div>

            <!-- Formulario para seleccionar cliente -->
            <form method="POST" action="">
                <input type="hidden" name="finalizar_compra" value="1">
                
                <div class="form-group">
                    <label for="cliente_id">Seleccionar Cliente que realiza la compra:</label>
                    <select id="cliente_id" name="cliente_id" required>
                        <option value="">-- Selecciona un cliente --</option>
                        <?php if (mysqli_num_rows($clientes_result) > 0): ?>
                            <?php while($cliente = mysqli_fetch_assoc($clientes_result)): ?>
                                <option value="<?php echo $cliente['id']; ?>" class="cliente-option">
                                    <?php echo htmlspecialchars($cliente['nombre']); ?> 
                                    - <?php echo htmlspecialchars($cliente['email']); ?>
                                    <?php echo $cliente['edad'] ? ' - ' . $cliente['edad'] . ' años' : ''; ?>
                                </option>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <option value="">No hay clientes registrados</option>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="cliente-info">
                    <p><strong>💡 Información importante:</strong></p>
                    <ul>
                        <li>Selecciona el cliente que realizará la compra</li>
                        <li>La compra quedará registrada en el historial del cliente</li>
                        <li>El stock de productos se actualizará automáticamente</li>
                        <li>Si el cliente no existe, <a href="registrar_cliente.php" style="color: #004439;">regístralo primero aquí</a></li>
                    </ul>
                </div>

                <div class="acciones">
                    <button type="submit" class="btn">✅ Confirmar y Registrar Compra</button>
                    <a href="compra.php" class="btn btn-cancelar">❌ Cancelar y Volver</a>
                </div>
            </form>

        <?php endif; ?>
    </div>

    <?php mysqli_close($enlace); ?>
</body>
</html>