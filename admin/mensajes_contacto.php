<?php
// admin/mensajes_contacto.php
require_once '../includes/header.php';
require_once '../config/db.php';

$mensaje = "";
$tipo_mensaje = "";

// Lógica para eliminar un mensaje
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $mensaje_id = filter_var($_GET['id'], FILTER_VALIDATE_INT);

    if ($mensaje_id === false) {
        $mensaje = "ID de mensaje no válido.";
        $tipo_mensaje = "danger";
    } else {
        try {
            $stmt = $pdo->prepare("DELETE FROM mensajes_contacto WHERE id = ?");
            $stmt->execute([$mensaje_id]);

            if ($stmt->rowCount() > 0) {
                $mensaje = "Mensaje eliminado correctamente.";
                $tipo_mensaje = "success";
            } else {
                $mensaje = "Mensaje no encontrado o no se pudo eliminar.";
                $tipo_mensaje = "danger";
            }
        } catch (PDOException $e) {
            $mensaje = "Error al eliminar el mensaje: " . $e->getMessage();
            $tipo_mensaje = "danger";
        }
    }
    header("Location: mensajes_contacto.php?mensaje=" . urlencode($mensaje) . "&tipo=" . urlencode($tipo_mensaje));
    exit();
}

// Lógica para marcar un mensaje como leído/no leído (opcional)
if (isset($_GET['action']) && $_GET['action'] === 'toggle_read' && isset($_GET['id'])) {
    $mensaje_id = filter_var($_GET['id'], FILTER_VALIDATE_INT);
    if ($mensaje_id === false) {
        $mensaje = "ID de mensaje no válido.";
        $tipo_mensaje = "danger";
    } else {
        try {
            // Obtener el estado actual
            $stmt_get_status = $pdo->prepare("SELECT leido FROM mensajes_contacto WHERE id = ?");
            $stmt_get_status->execute([$mensaje_id]);
            $current_status = $stmt_get_status->fetchColumn();

            $new_status = ($current_status == 0) ? 1 : 0; // Alternar estado

            $stmt_update = $pdo->prepare("UPDATE mensajes_contacto SET leido = ? WHERE id = ?");
            $stmt_update->execute([$new_status, $mensaje_id]);

            if ($stmt_update->rowCount() > 0) {
                $mensaje = "Mensaje marcado como " . ($new_status ? "leído" : "no leído") . ".";
                $tipo_mensaje = "info";
            } else {
                $mensaje = "No se pudo actualizar el estado del mensaje.";
                $tipo_mensaje = "warning";
            }
        } catch (PDOException $e) {
            $mensaje = "Error al actualizar el estado del mensaje: " . $e->getMessage();
            $tipo_mensaje = "danger";
        }
    }
    header("Location: mensajes_contacto.php?mensaje=" . urlencode($mensaje) . "&tipo=" . urlencode($tipo_mensaje));
    exit();
}


// Lógica para obtener todos los mensajes
$mensajes = [];
try {
    $stmt = $pdo->query("SELECT id, nombre, email, asunto, mensaje, fecha_envio, leido FROM mensajes_contacto ORDER BY fecha_envio DESC");
    $mensajes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $mensaje = "Error al cargar los mensajes: " . $e->getMessage();
    $tipo_mensaje = "danger";
}

// Cargar mensajes pasados por la URL (después de una redirección)
if (isset($_GET['mensaje']) && isset($_GET['tipo'])) {
    $mensaje = htmlspecialchars($_GET['mensaje']);
    $tipo_mensaje = htmlspecialchars($_GET['tipo']);
}
?>

<h1 class="mb-4"><i class="bi bi-chat-dots-fill me-3"></i>Gestión de Mensajes de Contacto</h1>

<div class="table-responsive">
    <table class="table table-striped table-hover align-middle">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Email</th>
                <th>Asunto</th>
                <th>Mensaje</th>
                <th>Fecha Envío</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($mensajes) > 0): ?>
                <?php foreach ($mensajes as $msg): ?>
                    <tr class="<?php echo ($msg['leido'] == 0) ? 'fw-bold table-primary' : ''; ?>"> <td><?php echo htmlspecialchars($msg['id']); ?></td>
                        <td><?php echo htmlspecialchars($msg['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($msg['email']); ?></td>
                        <td><?php echo htmlspecialchars($msg['asunto']); ?></td>
                        <td><?php echo htmlspecialchars(mb_strimwidth($msg['mensaje'], 0, 50, "...")); ?></td> <td><?php echo htmlspecialchars(date('d/m/Y H:i', strtotime($msg['fecha_envio']))); ?></td>
                        <td>
                            <?php if ($msg['leido']): ?>
                                <span class="badge bg-success">Leído</span>
                            <?php else: ?>
                                <span class="badge bg-warning text-dark">No Leído</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="mensajes_ver.php?id=<?php echo $msg['id']; ?>" class="btn btn-sm btn-info me-2" title="Ver Detalle"><i class="bi bi-eye"></i></a>
                            <a href="mensajes_contacto.php?action=toggle_read&id=<?php echo $msg['id']; ?>" class="btn btn-sm <?php echo ($msg['leido'] == 0) ? 'btn-secondary' : 'btn-light text-dark border'; ?>" title="<?php echo ($msg['leido'] == 0) ? 'Marcar como Leído' : 'Marcar como No Leído'; ?>"><i class="bi bi-check-circle<?php echo ($msg['leido'] == 0) ? '' : '-fill'; ?>"></i></a>
                            <a href="mensajes_contacto.php?action=delete&id=<?php echo $msg['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro de que quieres eliminar este mensaje?');" title="Eliminar"><i class="bi bi-trash"></i></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="8" class="text-center">No hay mensajes de contacto.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php
require_once '../includes/footer.php';
?>