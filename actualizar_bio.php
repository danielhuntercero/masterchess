<?php
session_start();
require_once 'config/db.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: index.php');
    exit();
}

// Validar y sanitizar la biografía
$bio = filter_input(INPUT_POST, 'bio', FILTER_SANITIZE_STRING) ?? '';
$usuario_id = $_SESSION['usuario_id'];

// Validar longitud máxima
if (strlen($bio) > 500) {
    header('Location: perfil.php?error=La biografía no puede exceder los 500 caracteres');
    exit();
}

try {
    $query = "UPDATE usuarios SET bio = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $bio, $usuario_id);
    
    if ($stmt->execute()) {
        header('Location: perfil.php?success=Biografía actualizada correctamente');
    } else {
        header('Location: perfil.php?error=Error al actualizar la biografía');
    }
} catch (Exception $e) {
    error_log("Error al actualizar biografía: " . $e->getMessage());
    header('Location: perfil.php?error=Error en el servidor al actualizar la biografía');
}
exit();