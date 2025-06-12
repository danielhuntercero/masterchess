<?php
session_start();

// Si el usuario no está autenticado, redirigir al login
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Panel de Administración</title>
</head>
<body>
    <h1>Bienvenido al Panel de Administración</h1>
    <p>¡Has iniciado sesión correctamente!</p>
    <p>Tu rol es: <?php echo htmlspecialchars($_SESSION['usuario_rol']); ?></p>
    <p><a href="logout.php">Cerrar Sesión</a></p>
</body>
</html>