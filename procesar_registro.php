<?php
// procesar_registro.php
session_start();
require_once 'config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Validaciones
    $errores = [];
    
    if (empty($nombre)) {
        $errores[] = "El nombre es obligatorio.";
    }
    
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errores[] = "El email no es válido.";
    }
    
    if (empty($password)) {
        $errores[] = "La contraseña es obligatoria.";
    } elseif (strlen($password) < 8) {
        $errores[] = "La contraseña debe tener al menos 8 caracteres.";
    }
    
    if ($password !== $confirm_password) {
        $errores[] = "Las contraseñas no coinciden.";
    }

    if (empty($errores)) {
        try {
            // Verificar si el email ya existe
            $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
            $stmt->execute([$email]);
            
            if ($stmt->fetch()) {
                $errores[] = "Este email ya está registrado.";
            } else {
                // Hash de la contraseña
                $password_hash = password_hash($password, PASSWORD_DEFAULT);
                
                // Insertar nuevo usuario (solo como cliente)
                $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, email, password, rol, fecha_registro) VALUES (?, ?, ?, 'cliente', NOW())");
                $result = $stmt->execute([$nombre, $email, $password_hash]);
                
                if ($result) {
                    // Obtener el ID del nuevo usuario
                    $usuario_id = $pdo->lastInsertId();
                    
                    // Iniciar sesión automáticamente después del registro
                    $_SESSION['usuario_id'] = $usuario_id;
                    $_SESSION['usuario_nombre'] = $nombre;
                    $_SESSION['usuario_rol'] = 'cliente';
                    
                    header("Location: index.php?success=Registro exitoso. ¡Bienvenido!");
                    exit();
                } else {
                    $errores[] = "Error al registrar el usuario.";
                }
            }
        } catch (PDOException $e) {
            $errores[] = "Error de base de datos: " . $e->getMessage();
        }
    }
    
    // Si hay errores, redirigir con los mensajes
    if (!empty($errores)) {
        $error_string = implode("<br>", $errores);
        header("Location: index.php?error=" . urlencode($error_string) . "#login-section");
        exit();
    }
} else {
    header("Location: index.php");
    exit();
}
?>