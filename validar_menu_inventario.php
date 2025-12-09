<?php
// Iniciar la sesión de PHP
session_start();

// Simular credenciales válidas (puedes reemplazarlas con una consulta a base de datos)
$usuario_valido = "amenaza";
$contrasena_valida = "silar"; // En un entorno real, las contraseñas deben estar encriptadas

// Recoger datos del formulario
$usuario_ingresado = $_POST['usuario'] ?? '';
$contrasena_ingresada = $_POST['contrasena'] ?? '';

// Verificar credenciales
if ($usuario_ingresado === $usuario_valido && $contrasena_ingresada === $contrasena_valida) {
    // Credenciales correctas, establecer variable de sesión
    $_SESSION['loggedin'] = true;
    $_SESSION['usuario'] = $usuario_ingresado;
    
    // Redirigir al menú principal
    header("Location: Menu_inventario.php");
    exit;
} else {
    // Credenciales incorrectas, mostrar mensaje de error
    echo "Usuario o contraseña incorrectos.";
    echo "<br><a href='login.html'>Volver a Intentarlo</a>";
}
?>
