<?php
session_start();

// Conexión a la base de datos
$servidor = "localhost";
$usuario = "root";
$clave = "";
$baseDeDatos = "pepelerias";
$enlace = mysqli_connect($servidor, $usuario, $clave, $baseDeDatos);

if (!$enlace) {
    die("Error de conexión: " . mysqli_connect_error());
}

// Inicializar carrito en sesión
if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

// Productos fijos
$productos_fijos = [
    ["Boligrafos", "Muy usados en escuela y oficina.", "https://http2.mlstatic.com/D_NQ_NP_797523-MLM73479939143_122023-O-lapiceros-boligrafos-finos-elegantes-tinta-gel-negro-clasico.webp", 45.00],
    ["Cuadernos / Libretas", "Para apuntes y tareas.", "https://st.depositphotos.com/1875497/3781/i/450/depositphotos_37810929-stock-photo-books-on-white.jpg", 70.00],
    ["Hojas de papel", "Papel bond y hojas sueltas.", "https://http2.mlstatic.com/D_NQ_NP_917417-MLU74232610634_022024-O.webp", 25.00],
    ["Lapices", "Lápices de grafito y escolares.", "https://m.media-amazon.com/images/I/61Vu16aIRVL._AC_UF350,350_QL80_.jpg", 15.00],
    ["Gomas de borrar", "Borradores escolares.", "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTfLm03fqnACjbhTfzJX9HIBBrUoNk7SX7hnSIgyEHJk4LyjRAwGdRjMLGgzw4oWIC2esA&usqp=CAU", 10.00],
    ["Pegamento / Adhesivos", "Pegamento en barra y líquido.", "https://http2.mlstatic.com/D_NQ_NP_896271-MLA95502947045_102025-O.webp", 20.00],
    ["Resaltadores / Marcadores", "Para estudio y apuntes.", "https://util.com.pe/cdn/shop/files/70_10-2-202.jpg?v=1753117774&width=1946", 30.00],
    ["Tijeras", "Recortar y actividades didacticas.", "https://www.latienditadelumina.com/cdn/shop/files/IMG_7462copia_1200x1200.jpg?v=1703963193", 35.00]

];

// Obtener productos de la base de datos
$productos_db = mysqli_query($enlace, "SELECT * FROM productos WHERE stock > 0 ORDER BY nombre");

// Procesar acciones del carrito
if (isset($_POST['accion'])) {
    switch ($_POST['accion']) {
        case 'agregar':
            $nombre = $_POST['nombre'];
            $precio = $_POST['precio'];
            
            // Buscar si ya existe en el carrito
            $encontrado = false;
            foreach ($_SESSION['carrito'] as &$item) {
                if ($item['nombre'] == $nombre) {
                    $item['cantidad']++;
                    $encontrado = true;
                    break;
                }
            }
            
            if (!$encontrado) {
                $_SESSION['carrito'][] = [
                    'nombre' => $nombre,
                    'precio' => $precio,
                    'cantidad' => 1
                ];
            }
            break;
            
        case 'eliminar':
            $nombre = $_POST['nombre'];
            $_SESSION['carrito'] = array_filter($_SESSION['carrito'], function($item) use ($nombre) {
                return $item['nombre'] != $nombre;
            });
            break;
            
        case 'cambiar_cantidad':
            $nombre = $_POST['nombre'];
            $cambio = $_POST['cambio'];
            
            foreach ($_SESSION['carrito'] as &$item) {
                if ($item['nombre'] == $nombre) {
                    $item['cantidad'] += $cambio;
                    if ($item['cantidad'] <= 0) {
                        $_SESSION['carrito'] = array_filter($_SESSION['carrito'], function($i) use ($nombre) {
                            return $i['nombre'] != $nombre;
                        });
                    }
                    break;
                }
            }
            break;
            
        case 'vaciar':
            $_SESSION['carrito'] = [];
            break;
    }
    
    // Redirigir para evitar reenvío del formulario
    header("Location: compra.php");
    exit;
}

// Calcular total
$total = 0;
foreach ($_SESSION['carrito'] as $item) {
    $total += $item['precio'] * $item['cantidad'];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Carrito de Compras</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        h1 {
            text-align: center;
            margin-bottom: 30px;
            color: #004439;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        th, td {
            padding: 15px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #004439;
            color: white;
        }

        .price {
            font-weight: bold;
            color: #2c3e50;
        }

        .total {
            font-size: 1.5em;
            font-weight: bold;
            margin-top: 30px;
            display: flex;
            justify-content: space-between;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
        }

        .total-price {
            color: #e74c3c;
        }

        .btn {
            padding: 10px 15px;
            font-size: 14px;
            color: white;
            background-color: #3498db;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }

        .btn:hover {
            background-color: #2980b9;
        }

        .btn-agregar {
            background-color: #28a745;
        }

        .btn-agregar:hover {
            background-color: #218838;
        }

        .btn-eliminar {
            background-color: #e74c3c;
        }

        .btn-eliminar:hover {
            background-color: #c0392b;
        }

        .productos-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 15px;
            margin-bottom: 30px;
        }

        .producto-card {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            border: 2px solid #e9ecef;
        }

        .producto-nombre {
            font-weight: bold;
            color: #004439;
            margin-bottom: 10px;
            font-size: 1.1em;
        }

        .producto-precio {
            font-size: 1.2em;
            color: #e74c3c;
            margin-bottom: 15px;
            font-weight: bold;
        }

        .producto-stock {
            color: #666;
            font-size: 0.9em;
            margin-bottom: 10px;
        }

        .producto-img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 10px;
            border: 2px solid #ddd;
        }

        .categoria-titulo {
            color: #004439;
            border-bottom: 2px solid #004439;
            padding-bottom: 10px;
            margin: 40px 0 20px 0;
            text-align: center;
        }

        nav {
            background-color: #e0e5e671;
            text-align: left;
            padding: 15px 20px;
            border-bottom: 1px solid #cffafcff;
            margin-bottom: 20px;
        }

        nav a {
            color: #556B2F;
            margin: 0 20px 0 0;
            text-decoration: none;
            font-weight: 500;
        }

        nav a:hover {
            color: #333333;
            text-decoration: underline;
        }

        .empty-cart {
            text-align: center;
            padding: 40px;
            color: #666;
            font-style: italic;
        }

        .quantity-form {
            display: inline;
        }

        .quantity-btn {
            padding: 5px 10px;
            background: #6c757d;
            color: white;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }

        .quantity-btn:hover {
            background: #5a6268;
        }

        .badge-db {
            background: #004439;
            color: white;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 0.7em;
            margin-left: 5px;
        }

        .badge-fijo {
            background: #6c757d;
            color: white;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 0.7em;
            margin-left: 5px;
        }
    </style>
</head>
<body>
    <header>
        <nav>
            <a href="index.php">INICIO</a> 
            <a href="productos.php">CATALOGO ILUSTRATIVO</a>
            <a href="compra.php">COMPRA</a>
            <a href="login.html">INVENTARIO</a>
        </nav>
    </header>

    <h1>Carrito de Compras</h1>

    <div class="container">
        <!-- Tabla de productos en el carrito -->
        <table>
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Precio Unitario</th>
                    <th>Cantidad</th>
                    <th>Total</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($_SESSION['carrito'])): ?>
                    <tr>
                        <td colspan="5" class="empty-cart">
                            <p>🛒 Tu carrito está vacío</p>
                            <p>Agrega productos desde las secciones de abajo</p>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($_SESSION['carrito'] as $item): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item['nombre']); ?></td>
                        <td class="price">$<?php echo number_format($item['precio'], 2); ?></td>
                        <td>
                            <form method="POST" action="" class="quantity-form">
                                <input type="hidden" name="accion" value="cambiar_cantidad">
                                <input type="hidden" name="nombre" value="<?php echo $item['nombre']; ?>">
                                <input type="hidden" name="cambio" value="-1">
                                <button type="submit" class="quantity-btn">-</button>
                            </form>
                            
                            <?php echo $item['cantidad']; ?>
                            
                            <form method="POST" action="" class="quantity-form">
                                <input type="hidden" name="accion" value="cambiar_cantidad">
                                <input type="hidden" name="nombre" value="<?php echo $item['nombre']; ?>">
                                <input type="hidden" name="cambio" value="1">
                                <button type="submit" class="quantity-btn">+</button>
                            </form>
                        </td>
                        <td class="price">$<?php echo number_format($item['precio'] * $item['cantidad'], 2); ?></td>
                        <td>
                            <form method="POST" action="" style="display: inline;">
                                <input type="hidden" name="accion" value="eliminar">
                                <input type="hidden" name="nombre" value="<?php echo $item['nombre']; ?>">
                                <button type="submit" class="btn btn-eliminar">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Total -->
        <div class="total">
            <span>Total a Pagar:</span>
            <span class="total-price">$<?php echo number_format($total, 2); ?></span>
        </div>

        <!-- Botones de acción -->
        <div style="text-align: center; margin: 20px 0;">
            <?php if (!empty($_SESSION['carrito'])): ?>
                <a href="finalizar_compra.php" class="btn" style="background: #28a745;">✅ Finalizar Compra</a>
            <?php endif; ?>
            
            <form method="POST" action="" style="display: inline;">
                <input type="hidden" name="accion" value="vaciar">
                <button type="submit" class="btn" style="background: #6c757d;">🗑️ Vaciar Carrito</button>
            </form>
        </div>

        <!-- PRODUCTOS FIJOS -->
        <h2 class="categoria-titulo">Productos Principales</h2>
        <div class="productos-grid">
            <?php foreach($productos_fijos as $producto): ?>
                <div class="producto-card">
                    <?php if(!empty($producto[2])): ?>
                        <img src="<?php echo $producto[2]; ?>" alt="<?php echo $producto[0]; ?>" class="producto-img">
                    <?php endif; ?>
                    <div class="producto-nombre">
                        <?php echo $producto[0]; ?>
                        <span class="badge-fijo">FIJO</span>
                    </div>
                    <div class="producto-descripcion" style="font-size: 0.9em; color: #666; margin-bottom: 10px;">
                        <?php echo $producto[1]; ?>
                    </div>
                    <div class="producto-precio">$<?php echo number_format($producto[3], 2); ?></div>
                    
                    <form method="POST" action="">
                        <input type="hidden" name="accion" value="agregar">
                        <input type="hidden" name="nombre" value="<?php echo $producto[0]; ?>">
                        <input type="hidden" name="precio" value="<?php echo $producto[3]; ?>">
                        <button type="submit" class="btn btn-agregar">➕ Agregar al Carrito</button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- PRODUCTOS DE LA BASE DE DATOS -->
        <?php if(mysqli_num_rows($productos_db) > 0): ?>
            <h2 class="categoria-titulo">Productos del Inventario</h2>
            <div class="productos-grid">
                <?php while($producto_bd = mysqli_fetch_assoc($productos_db)): ?>
                    <div class="producto-card">
                        <div class="producto-nombre">
                            <?php echo htmlspecialchars($producto_bd['nombre']); ?>
                            <span class="badge-db">INVENTARIO</span>
                        </div>
                        <div class="producto-descripcion" style="font-size: 0.9em; color: #666; margin-bottom: 10px;">
                            <?php echo htmlspecialchars($producto_bd['descripcion']); ?>
                        </div>
                        <div class="producto-precio">$<?php echo number_format($producto_bd['precio'], 2); ?></div>
                        <div class="producto-stock">Stock: <?php echo $producto_bd['stock']; ?> unidades</div>
                        
                        <form method="POST" action="">
                            <input type="hidden" name="accion" value="agregar">
                            <input type="hidden" name="nombre" value="<?php echo $producto_bd['nombre']; ?>">
                            <input type="hidden" name="precio" value="<?php echo $producto_bd['precio']; ?>">
                            <button type="submit" class="btn btn-agregar">➕ Agregar al Carrito</button>
                        </form>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php endif; ?>
    </div>

    <?php mysqli_close($enlace); ?>
</body>
</html>