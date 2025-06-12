<?php
// admin/torneos.php
require_once '../includes/header.php';
require_once '../config/db.php';

$mensaje = "";
$tipo_mensaje = "";

// Lógica para eliminar un torneo
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $torneo_id = filter_var($_GET['id'], FILTER_VALIDATE_INT);

    if ($torneo_id === false) {
        $mensaje = "ID de torneo no válido.";
        $tipo_mensaje = "danger";
    } else {
        try {
            // Primero, verificar si hay inscripciones asociadas a este torneo
            $stmt_check_inscripciones = $pdo->prepare("SELECT COUNT(*) FROM inscripciones WHERE torneo_id = ?");
            $stmt_check_inscripciones->execute([$torneo_id]);
            $inscripciones_existentes = $stmt_check_inscripciones->fetchColumn();

            if ($inscripciones_existentes > 0) {
                $mensaje = "No se puede eliminar el torneo porque tiene inscripciones asociadas.";
                $tipo_mensaje = "danger";
            } else {
                $stmt = $pdo->prepare("DELETE FROM torneos WHERE id = ?");
                $stmt->execute([$torneo_id]);
                $mensaje = "Torneo eliminado correctamente.";
                $tipo_mensaje = "success";
            }
        } catch (PDOException $e) {
            $mensaje = "Error al eliminar el torneo: " . $e->getMessage();
            $tipo_mensaje = "danger";
        }
    }
    // Redirigir para limpiar la URL y mostrar el mensaje
    header("Location: torneos.php?mensaje=" . urlencode($mensaje) . "&tipo=" . urlencode($tipo_mensaje));
    exit();
}

// Obtener todos los torneos de la base de datos
$torneos = [];
try {
    $stmt = $pdo->query("SELECT * FROM torneos ORDER BY fecha DESC");
    $torneos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $mensaje = "Error al cargar los torneos: " . $e->getMessage();
    $tipo_mensaje = "danger";
}
?>

<h1 class="mb-4"><i class="bi bi-trophy-fill me-3"></i>Gestión de Torneos</h1>

<a href="torneos_crear.php" class="btn btn-success mb-3"><i class="bi bi-plus-circle me-2"></i>Añadir Nuevo Torneo</a>

<?php
// El mensaje global ya se muestra por header.php si viene de una redirección
// Si hay un mensaje de error que se generó en esta página (ej. al cargar los torneos)
if (!empty($mensaje) && !isset($_GET['mensaje'])) {
    echo '<div class="alert alert-' . $tipo_mensaje . ' alert-dismissible fade show" role="alert">' . htmlspecialchars($mensaje) . '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
}
?>

<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Fecha y Hora</th>
                <th>Precio Inscripción</th>
                <th>Máx. Participantes</th>
                <th>Descripción</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($torneos) > 0): ?>
                <?php foreach ($torneos as $torneo): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($torneo['id']); ?></td>
                        <td><?php echo htmlspecialchars($torneo['nombre']); ?></td>
                        <td><?php echo htmlspecialchars(date('d/m/Y H:i', strtotime($torneo['fecha']))); ?></td>
                        <td><?php echo htmlspecialchars(number_format($torneo['precio_insc'], 2, ',', '.')) . ' €'; ?></td>
                        <td><?php echo htmlspecialchars($torneo['max_participantes'] ?? 'Ilimitado'); ?></td>
                        <td><?php echo htmlspecialchars(substr($torneo['descripcion'], 0, 50)) . (strlen($torneo['descripcion']) > 50 ? '...' : ''); ?></td>
                        <td>
                            <a href="torneos_editar.php?id=<?php echo $torneo['id']; ?>" class="btn btn-sm btn-primary me-2"><i class="bi bi-pencil"></i> Editar</a>
                            <a href="torneos.php?action=delete&id=<?php echo $torneo['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro de que quieres eliminar este torneo? Todas las inscripciones relacionadas también serán eliminadas si la base de datos lo permite.');"><i class="bi bi-trash"></i> Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" class="text-center">No hay torneos registrados.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php
require_once '../includes/footer.php';
?>