<?php
// admin/torneos_editar.php
require_once '../includes/header.php';
require_once '../config/db.php';

$mensaje = "";
$tipo_mensaje = "";
$torneo_a_editar = null;

// Obtener ID del torneo desde la URL
$torneo_id = filter_var($_GET['id'] ?? '', FILTER_VALIDATE_INT);

if ($torneo_id === false) {
    $mensaje = "ID de torneo no válido.";
    $tipo_mensaje = "danger";
} else {
    // Cargar datos del torneo existente
    try {
        $stmt = $pdo->prepare("SELECT * FROM torneos WHERE id = ?");
        $stmt->execute([$torneo_id]);
        $torneo_a_editar = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$torneo_a_editar) {
            $mensaje = "Torneo no encontrado.";
            $tipo_mensaje = "danger";
        }
    } catch (PDOException $e) {
        $mensaje = "Error al cargar los datos del torneo: " . $e->getMessage();
        $tipo_mensaje = "danger";
    }

    // Si se envió el formulario de edición
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $torneo_a_editar) {
        $nombre_recibido = trim($_POST['nombre'] ?? '');
        $fecha_recibida = trim($_POST['fecha'] ?? '');
        $precio_insc_recibido = filter_var($_POST['precio_insc'] ?? 0, FILTER_VALIDATE_FLOAT);
        $descripcion_recibida = trim($_POST['descripcion'] ?? '');
        $max_participantes_recibido = filter_var($_POST['max_participantes'] ?? null, FILTER_VALIDATE_INT);

        // Validaciones
        if (empty($nombre_recibido)) {
            $mensaje = "El nombre del torneo es obligatorio.";
            $tipo_mensaje = "danger";
        } elseif (empty($fecha_recibida)) {
            $mensaje = "La fecha y hora del torneo son obligatorias.";
            $tipo_mensaje = "danger";
        } elseif ($precio_insc_recibido === false || $precio_insc_recibido < 0) {
            $mensaje = "El precio de inscripción no es válido.";
            $tipo_mensaje = "danger";
        } elseif ($max_participantes_recibido === false || ($max_participantes_recibido !== null && $max_participantes_recibido < 0)) {
            $mensaje = "El número máximo de participantes no es válido.";
            $tipo_mensaje = "danger";
        } else {
            $fecha_db = str_replace('T', ' ', $fecha_recibida); // Formatear para DB

            try {
                $stmt = $pdo->prepare("UPDATE torneos SET nombre = ?, fecha = ?, precio_insc = ?, descripcion = ?, max_participantes = ? WHERE id = ?");
                $stmt->execute([$nombre_recibido, $fecha_db, $precio_insc_recibido, $descripcion_recibida, $max_participantes_recibido, $torneo_id]);

                $mensaje = "Torneo actualizado correctamente.";
                $tipo_mensaje = "success";
                // Recargar los datos del torneo para mostrar la actualización
                $stmt = $pdo->prepare("SELECT * FROM torneos WHERE id = ?");
                $stmt->execute([$torneo_id]);
                $torneo_a_editar = $stmt->fetch(PDO::FETCH_ASSOC);

                // Redirigir con mensaje para que se vea la actualización y evitar reenvío de formulario
                header("Location: torneos.php?mensaje=" . urlencode($mensaje) . "&tipo=" . urlencode($tipo_mensaje));
                exit();

            } catch (PDOException $e) {
                $mensaje = "Error al actualizar el torneo: " . $e->getMessage();
                $tipo_mensaje = "danger";
            }
        }
    }
}
?>

<h1 class="mb-4"><i class="bi bi-pencil-square me-3"></i>Editar Torneo</h1>

<?php
// Mostrar el mensaje de error o éxito
if (!empty($mensaje) && !isset($_GET['mensaje'])) {
    echo '<div class="alert alert-' . $tipo_mensaje . ' alert-dismissible fade show" role="alert">' . htmlspecialchars($mensaje) . '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
}
?>

<?php if ($torneo_a_editar): ?>
    <form action="torneos_editar.php?id=<?php echo htmlspecialchars($torneo_a_editar['id']); ?>" method="POST">
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre del Torneo:</label>
            <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo htmlspecialchars($torneo_a_editar['nombre']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="fecha" class="form-label">Fecha y Hora:</label>
            <input type="datetime-local" class="form-control" id="fecha" name="fecha" value="<?php echo htmlspecialchars(date('Y-m-d\TH:i', strtotime($torneo_a_editar['fecha']))); ?>" required>
        </div>
        <div class="mb-3">
            <label for="precio_insc" class="form-label">Precio de Inscripción (€):</label>
            <input type="number" step="0.01" class="form-control" id="precio_insc" name="precio_insc" value="<?php echo htmlspecialchars($torneo_a_editar['precio_insc']); ?>" min="0">
        </div>
        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción (opcional):</label>
            <textarea class="form-control" id="descripcion" name="descripcion" rows="3"><?php echo htmlspecialchars($torneo_a_editar['descripcion'] ?? ''); ?></textarea>
        </div>
        <div class="mb-3">
            <label for="max_participantes" class="form-label">Máx. Participantes (opcional):</label>
            <input type="number" class="form-control" id="max_participantes" name="max_participantes" value="<?php echo htmlspecialchars($torneo_a_editar['max_participantes'] ?? ''); ?>" min="0">
        </div>
        <button type="submit" class="btn btn-primary"><i class="bi bi-save me-2"></i>Actualizar Torneo</button>
        <a href="torneos.php" class="btn btn-secondary"><i class="bi bi-x-circle me-2"></i>Cancelar</a>
    </form>
<?php else: ?>
    <p>No se pudo cargar el torneo para editar.</p>
<?php endif; ?>

<?php
require_once '../includes/footer.php';
?>