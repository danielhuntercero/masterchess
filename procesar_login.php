<?php
// procesar_login.php
session_start();
require_once 'config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        header("Location: index.php?error=Por favor, introduce tu email y contraseña.");
        exit();
    }

    try {
        $stmt = $pdo->prepare("SELECT id, nombre, email, password, rol FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario && password_verify($password, $usuario['password'])) {
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nombre'] = $usuario['nombre'];
            $_SESSION['usuario_rol'] = $usuario['rol'];

            // Actualizar la fecha del último login
            $stmt_update_login = $pdo->prepare("UPDATE usuarios SET fecha_ultimo_login = NOW() WHERE id = ?");
            $stmt_update_login->execute([$usuario['id']]);

            // Redirección a la página principal para todos los usuarios
            header("Location: index.php");
            exit();
        } else {
            header("Location: index.php?error=Credenciales incorrectas.");
            exit();
        }
    } catch (PDOException $e) {
        header("Location: index.php?error=Error en el sistema. Por favor, intenta más tarde.");
        exit();
    }
}

header("Location: index.php");
exit();
?>