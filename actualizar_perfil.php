<?php
session_start();
require_once 'config/db.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: index.php');
    exit();
}

$usuario_id = $_SESSION['usuario_id'];
$errors = [];

// Validar y sanitizar datos
$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
$telefono = filter_input(INPUT_POST, 'telefono', FILTER_SANITIZE_STRING);
$nivel_ajedrez = filter_input(INPUT_POST, 'nivel_ajedrez', FILTER_SANITIZE_STRING);
$nueva_password = $_POST['nueva_password'] ?? '';
$confirmar_password = $_POST['confirmar_password'] ?? '';

// Validar email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "El email no es válido";
}

// Validar teléfono si existe
if (!empty($telefono) && !preg_match('/^[0-9\s\+\-\(\)]{6,20}$/', $telefono)) {
    $errors[] = "El teléfono no es válido";
}

// Validar contraseña si se proporciona
if (!empty($nueva_password)) {
    if (strlen($nueva_password) < 8) {
        $errors[] = "La contraseña debe tener al menos 8 caracteres";
    } elseif ($nueva_password !== $confirmar_password) {
        $errors[] = "Las contraseñas no coinciden";
    }
}

if (empty($errors)) {
    try {
        $query = "UPDATE usuarios SET email = ?, telefono = ?, nivel_ajedrez = ?";
        $params = [$email, $telefono, $nivel_ajedrez];
        
        if (!empty($nueva_password)) {
            $hashed_password = password_hash($nueva_password, PASSWORD_DEFAULT);
            $query .= ", password = ?";
            $params[] = $hashed_password;
        }
        
        $query .= " WHERE id = ?";
        $params[] = $usuario_id;
        
        $stmt = $pdo->prepare($query);
        $stmt->execute($params);
        
        $_SESSION['usuario_email'] = $email;
        header('Location: perfil.php?success=Perfil actualizado correctamente');
        exit();
    } catch (PDOException $e) {
        error_log("Error al actualizar perfil: " . $e->getMessage());
        $errors[] = "Error al actualizar el perfil. Por favor, inténtelo más tarde.";
    }
}

if (!empty($errors)) {
    $_SESSION['update_errors'] = $errors;
    header('Location: perfil.php?error=' . urlencode(implode("<br>", $errors)));
    exit();
}