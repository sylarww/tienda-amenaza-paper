
<?php
include 'conexion.php';

$sql = "SELECT * FROM articulos ORDER BY nombre";
$resultado = $conexion->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Artículos</title>
</head>
<body>
    <h2>Lista de Artículos</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Precio</th>
            <th>Stock</th>
            <th>Acciones</th>
        </tr>
        <?php while($fila = $resultado->fetch_assoc()): ?>
        <tr>
            <td><?php echo $fila['id']; ?></td>
            <td><?php echo $fila['nombre']; ?></td>
            <td><?php echo $fila['precio']; ?></td>
            <td><?php echo $fila['stock']; ?></td>
            <td>
                <a href="eliminar_articulo.php?id=<?php echo $fila['id']; ?>" 
                   onclick="return confirm('¿Estás seguro de eliminar este artículo?')">
                   Eliminar
                </a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
    <br>
    <a href="index.html">Volver al Inicio</a>
</body>
</html>

<?php
$conexion->close();
?>