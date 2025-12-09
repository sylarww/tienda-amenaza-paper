<?php
$servidor = "localhost";
$usuario = "root";
$clave = "";
$baseDeDatos = "pepelerias";

$conexion = mysqli_connect($servidor, $usuario, $clave, $baseDeDatos);

if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}

// Opcional: para caracteres especiales
mysqli_set_charset($conexion, "utf8");
?>