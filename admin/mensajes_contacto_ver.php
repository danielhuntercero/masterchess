<?php
// admin/mensajes_ver.php
require_once '../includes/header.php';
require_once '../config/db.php';

$mensaje_contacto = null;
$mensaje = "";
$tipo_mensaje = "";

$mensaje_id = filter_var($_GET['id'] ?? '', FILTER_VALIDATE_INT);

if ($mensaje_id === false) {
    $mensaje = "ID de mensaje no válido.";
    $tipo_mensaje = "danger";
} else {
    try {
        $stmt = $pdo->prepare("SELECT id, nombre, email, asunto, mensaje, fecha_envio, leido FROM mensajes_contacto WHERE id = ?");
        $stmt->execute([$mensaje_id]);
        $mensaje_contacto = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$mensaje_contacto) {
            $mensaje = "Mensaje no encontrado.";
            $tipo_mensaje = "danger";
        } else {
            // Marcar como leído si aún no lo está
            if ($mensaje_contacto['leido'] == 0) {
                $stmt_update = $pdo->prepare("UPDATE mensajes_contacto SET leido = 1 WHERE id = ?");
                $stmt_update->execute([$mensaje_id]);
                // No mostrar un mensaje de éxito aquí, ya que el usuario vino a ver el mensaje
                $mensaje_contacto['leido'] = 1; // Actualizar el estado en la variable para mostrarlo correctamente
            }
        }
    } catch (PDOException $e) {
        $mensaje = "Error al cargar el mensaje: " . $e->getMessage();
        $tipo_mensaje = "danger";
    }
}
?>

<h1 class="mb-4"><i class="bi bi-chat-text-fill me-3"></i>Ver Mensaje</h1>

<?php if ($mensaje_contacto): ?>
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="card-title mb-0"><i class="bi bi-envelope-fill me-2"></i>Asunto: <?php echo htmlspecialchars($mensaje_contacto['asunto']); ?></h5>
        </div>
        <div class="card-body">
            <p><strong>De:</strong> <?php echo htmlspecialchars($mensaje_contacto['nombre']); ?> &lt;<?php echo htmlspecialchars($mensaje_contacto['email']); ?>&gt;</p>
            <p><strong>Fecha:</strong> <?php echo htmlspecialchars(date('d/m/Y H:i', strtotime($mensaje_contacto['fecha_envio']))); ?></p>
            <hr>
            <p class="card-text"><?php echo nl2br(htmlspecialchars($mensaje_contacto['mensaje'])); ?></p>
            <hr>
            <p><strong>Estado:</strong>
                <?php if ($mensaje_contacto['leido']): ?>
                    <span class="badge bg-success">Leído</span>
                <?php else: ?>
                    <span class="badge bg-warning text-dark">No Leído</span>
                <?php endif; ?>
            </p>
        </div>
        <div class="card-footer text-end">
            <a href="mensajes_contacto.php?action=toggle_read&id=<?php echo $mensaje_contacto['id']; ?>" class="btn btn-outline-secondary me-2">
                <i class="bi bi-check-circle<?php echo ($mensaje_contacto['leido'] == 0) ? '' : '-fill'; ?>"></i> <?php echo ($mensaje_contacto['leido'] == 0) ? 'Marcar como Leído' : 'Marcar como No Leído'; ?>
            </a>
            <a href="mensajes_contacto.php?action=delete&id=<?php echo $mensaje_contacto['id']; ?>" class="btn btn-danger me-2" onclick="return confirm('¿Estás seguro de que quieres eliminar este mensaje?');"><i class="bi bi-trash me-2"></i>Eliminar Mensaje</a>
            <a href="mensajes_contacto.php" class="btn btn-secondary"><i class="bi bi-arrow-left me-2"></i>Volver a Mensajes</a>
        </div>
    </div>
<?php else: ?>
    <p class="alert alert-warning">No se pudo cargar el mensaje o el ID no es válido.</p>
<?php endif; ?>

<?php
require_once '../includes/footer.php';
?>