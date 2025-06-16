<?php
// admin/servicios.php
require_once '../includes/header.php';
require_once '../config/db.php';

$mensaje = "";
$tipo_mensaje = "";

// Lógica para eliminar un servicio
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $servicio_id = filter_var($_GET['id'], FILTER_VALIDATE_INT);

    if ($servicio_id === false) {
        $mensaje = "ID de servicio no válido.";
        $tipo_mensaje = "danger";
    } else {
        try {
            // Verificar si hay inscripciones asociadas a este servicio
            $stmt_check_inscripciones = $pdo->prepare("SELECT COUNT(*) FROM inscripciones WHERE servicio_id = ?");
            $stmt_check_inscripciones->execute([$servicio_id]);
            $inscripciones_existentes = $stmt_check_inscripciones->fetchColumn();

            if ($inscripciones_existentes > 0) {
                $mensaje = "No se puede eliminar el servicio porque tiene inscripciones asociadas.";
                $tipo_mensaje = "danger";
            } else {
                // Obtener nombre de la imagen para eliminarla
                $stmt_get_image = $pdo->prepare("SELECT imagen FROM servicios WHERE id = ?");
                $stmt_get_image->execute([$servicio_id]);
                $imagen = $stmt_get_image->fetchColumn();
                
                // Eliminar el servicio
                $stmt = $pdo->prepare("DELETE FROM servicios WHERE id = ?");
                $stmt->execute([$servicio_id]);
                
                // Eliminar la imagen asociada si existe
                if ($imagen && file_exists("../uploads/servicios/" . $imagen)) {
                    unlink("../uploads/servicios/" . $imagen);
                }
                
                $mensaje = "Servicio eliminado correctamente.";
                $tipo_mensaje = "success";
            }
        } catch (PDOException $e) {
            $mensaje = "Error al eliminar el servicio: " . $e->getMessage();
            $tipo_mensaje = "danger";
        }
    }
    // Redirigir para limpiar la URL y mostrar el mensaje
    header("Location: servicios.php?mensaje=" . urlencode($mensaje) . "&tipo=" . urlencode($tipo_mensaje));
    exit();
}

// Obtener todos los servicios de la base de datos, incluyendo el nombre del profesor
$servicios = [];
try {
    $stmt = $pdo->query("SELECT s.*, u.nombre AS profesor_nombre
                          FROM servicios s
                          LEFT JOIN usuarios u ON s.profesor_id = u.id
                          ORDER BY s.nombre ASC");
    $servicios = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $mensaje = "Error al cargar los servicios: " . $e->getMessage();
    $tipo_mensaje = "danger";
}
?>

<h1 class="mb-4"><i class="bi bi-tools me-3"></i>Gestión de Servicios</h1>

<a href="servicios_crear.php" class="btn btn-success mb-3"><i class="bi bi-plus-circle me-2"></i>Añadir Nuevo Servicio</a>

<?php
// El mensaje global ya se muestra por header.php si viene de una redirección
if (!empty($mensaje) && !isset($_GET['mensaje'])) {
    echo '<div class="alert alert-' . $tipo_mensaje . ' alert-dismissible fade show" role="alert">' . htmlspecialchars($mensaje) . '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
}
?>

<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th>Imagen</th>
                <th>ID</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Precio por Hora</th>
                <th>Profesor</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($servicios) > 0): ?>
                <?php foreach ($servicios as $servicio): ?>
                    <tr>
                        <td>
                            <?php if (!empty($servicio['imagen'])): ?>
                                <img src="../uploads/servicios/<?= htmlspecialchars($servicio['imagen']) ?>" alt="<?= htmlspecialchars($servicio['nombre']) ?>" style="max-width: 80px; max-height: 80px;" class="img-thumbnail">
                            <?php else: ?>
                                <span class="text-muted">Sin imagen</span>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($servicio['id']) ?></td>
                        <td><?= htmlspecialchars($servicio['nombre']) ?></td>
                        <td><?= htmlspecialchars(substr($servicio['descripcion'] ?? '', 0, 50)) . (strlen($servicio['descripcion'] ?? '') > 50 ? '...' : '') ?></td>
                        <td><?= htmlspecialchars(number_format($servicio['precio_hora'] ?? 0, 2, ',', '.')) . ' €' ?></td>
                        <td><?= htmlspecialchars($servicio['profesor_nombre'] ?? 'N/A') ?></td>
                        <td>
                            <a href="servicios_editar.php?id=<?= $servicio['id'] ?>" class="btn btn-sm btn-primary me-2"><i class="bi bi-pencil"></i> Editar</a>
                            <a href="servicios.php?action=delete&id=<?= $servicio['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro de que quieres eliminar este servicio? Las inscripciones relacionadas no serán eliminadas.');"><i class="bi bi-trash"></i> Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" class="text-center">No hay servicios registrados.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php
require_once '../includes/footer.php';
?>