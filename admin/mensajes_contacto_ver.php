<?php
// admin/mensajes_contacto_ver.php
require_once '../config/db.php';

$mensaje_contacto = null;
$mensaje = "";
$tipo_mensaje = "";

// Obtener ID del mensaje
$mensaje_id = filter_var($_GET['id'] ?? '', FILTER_VALIDATE_INT);

if ($mensaje_id === false || $mensaje_id <= 0) {
    header('Location: mensajes_contacto.php');
    exit();
}

try {
    // Obtener el mensaje
    $stmt = $pdo->prepare("SELECT * FROM mensajes_contacto WHERE id = ?");
    $stmt->execute([$mensaje_id]);
    $mensaje_contacto = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$mensaje_contacto) {
        header('Location: mensajes_contacto.php');
        exit();
    }

    // Marcar como leído si no lo está
    if (!$mensaje_contacto['leido']) {
        $update_stmt = $pdo->prepare("UPDATE mensajes_contacto SET leido = 1 WHERE id = ?");
        $update_stmt->execute([$mensaje_id]);
    }
} catch (PDOException $e) {
    header('Location: mensajes_contacto.php');
    exit();
}

// Mostrar mensajes de éxito/error
if (isset($_GET['mensaje'])) {
    $mensaje = htmlspecialchars($_GET['mensaje']);
    $tipo_mensaje = htmlspecialchars($_GET['tipo'] ?? 'info');
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver Mensaje - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link href="../assets/css/admin.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1 class="mb-4"><i class="bi bi-envelope-open me-2"></i> Detalle del Mensaje</h1>

        <?php if ($mensaje): ?>
            <div class="alert alert-<?= $tipo_mensaje ?> alert-dismissible fade show" role="alert">
                <?= $mensaje ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="card shadow-lg">
            <div class="card-header bg-primary text-white">
                <h2><?= htmlspecialchars($mensaje_contacto['asunto']) ?></h2>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p><strong>De:</strong> <?= htmlspecialchars($mensaje_contacto['nombre']) ?></p>
                        <p><strong>Email:</strong> <?= htmlspecialchars($mensaje_contacto['email']) ?></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Fecha:</strong> <?= date('d/m/Y H:i', strtotime($mensaje_contacto['fecha'])) ?></p>
                        <p><strong>Estado:</strong> 
                            <span class="badge <?= $mensaje_contacto['leido'] ? 'bg-success' : 'bg-warning' ?>">
                                <?= $mensaje_contacto['leido'] ? 'Leído' : 'No leído' ?>
                            </span>
                        </p>
                    </div>
                </div>
                
                <hr>
                
                <div class="message-content p-3 bg-light rounded">
                    <?= nl2br(htmlspecialchars($mensaje_contacto['mensaje'])) ?>
                </div>
            </div>
            <div class="card-footer">
                <div class="d-flex justify-content-between">
                    <a href="mensajes_contacto.php" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Volver
                    </a>
                    <div>
                        <a href="mensajes_contacto.php?action=toggle_read&id=<?= $mensaje_contacto['id'] ?>" 
                           class="btn <?= $mensaje_contacto['leido'] ? 'btn-outline-warning' : 'btn-outline-success' ?> me-2">
                            <i class="bi bi-check-circle"></i> 
                            <?= $mensaje_contacto['leido'] ? 'Marcar como no leído' : 'Marcar como leído' ?>
                        </a>
                        <a href="mensajes_contacto.php?action=delete&id=<?= $mensaje_contacto['id'] ?>" 
                           class="btn btn-danger"
                           onclick="return confirm('¿Estás seguro de eliminar este mensaje?')">
                            <i class="bi bi-trash"></i> Eliminar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>