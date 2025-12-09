
<?php
include 'compra.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $stock = $_POST['stock'];
    
    $sql = "INSERT INTO articulos (nombre, descripcion, precio, stock) 
            VALUES (?, ?, ?, ?)";
    
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ssdi", $nombre, $descripcion, $precio, $stock);
    
    if ($stmt->execute()) {
        echo "Artículo insertado correctamente.";
    } else {
        echo "Error: " . $stmt->error;
    }
    
    $stmt->close();
    $conexion->close();
}
?>
<br>
<a href="1.php">Insertar otro artículo</a>
<br>
<a href="index.html">Volver al Inicio</a>
