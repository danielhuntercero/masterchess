<?php
// admin/servicios_editar.php
require_once '../includes/header.php';
require_once '../config/db.php';

$mensaje = "";
$tipo_mensaje = "";
$servicio_a_editar = null;
$profesores = [];

// Obtener la lista de usuarios con rol 'profesor'
try {
    $stmt_profesores = $pdo->prepare("SELECT id, nombre FROM usuarios WHERE rol = 'profesor' ORDER BY nombre ASC");
    $stmt_profesores->execute();
    $profesores = $stmt_profesores->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $mensaje = "Error al cargar la lista de profesores: " . $e->getMessage();
    $tipo_mensaje = "danger";
}

// Obtener ID del servicio desde la URL
$servicio_id = filter_var($_GET['id'] ?? '', FILTER_VALIDATE_INT);

if ($servicio_id === false) {
    $mensaje = "ID de servicio no válido.";
    $tipo_mensaje = "danger";
} else {
    // Cargar datos del servicio existente
    try {
        $stmt = $pdo->prepare("SELECT * FROM servicios WHERE id = ?");
        $stmt->execute([$servicio_id]);
        $servicio_a_editar = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$servicio_a_editar) {
            $mensaje = "Servicio no encontrado.";
            $tipo_mensaje = "danger";
        }
    } catch (PDOException $e) {
        $mensaje = "Error al cargar los datos del servicio: " . $e->getMessage();
        $tipo_mensaje = "danger";
    }

    // Si se envió el formulario de edición
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $servicio_a_editar) {
        $nombre_recibido = trim($_POST['nombre'] ?? '');
        $descripcion_recibida = trim($_POST['descripcion'] ?? '');
        $precio_hora_recibido = filter_var($_POST['precio_hora'] ?? null, FILTER_VALIDATE_FLOAT);
        $profesor_id_recibido = filter_var($_POST['profesor_id'] ?? null, FILTER_VALIDATE_INT);

        // Validaciones
        if (empty($nombre_recibido)) {
            $mensaje = "El nombre del servicio es obligatorio.";
            $tipo_mensaje = "danger";
        } elseif ($precio_hora_recibido === false || ($precio_hora_recibido !== null && $precio_hora_recibido < 0)) {
            $mensaje = "El precio por hora no es válido.";
            $tipo_mensaje = "danger";
        } elseif ($profesor_id_recibido === false && !is_null($_POST['profesor_id']) && $_POST['profesor_id'] !== '') {
            $mensaje = "El profesor seleccionado no es válido.";
            $tipo_mensaje = "danger";
        }
        else {
            try {
                $stmt = $pdo->prepare("UPDATE servicios SET nombre = ?, descripcion = ?, precio_hora = ?, profesor_id = ? WHERE id = ?");
                $profesor_final = ($profesor_id_recibido === 0 || $profesor_id_recibido === null) ? null : $profesor_id_recibido;

                $stmt->execute([$nombre_recibido, $descripcion_recibida, $precio_hora_recibido, $profesor_final, $servicio_id]);

                $mensaje = "Servicio actualizado correctamente.";
                $tipo_mensaje = "success";
                // Recargar los datos del servicio para mostrar la actualización
                $stmt = $pdo->prepare("SELECT * FROM servicios WHERE id = ?");
                $stmt->execute([$servicio_id]);
                $servicio_a_editar = $stmt->fetch(PDO::FETCH_ASSOC);

                // Redirigir con mensaje para que se vea la actualización y evitar reenvío de formulario
                header("Location: servicios.php?mensaje=" . urlencode($mensaje) . "&tipo=" . urlencode($tipo_mensaje));
                exit();

            } catch (PDOException $e) {
                $mensaje = "Error al actualizar el servicio: " . $e->getMessage();
                $tipo_mensaje = "danger";
            }
        }
    }
}
?>

<h1 class="mb-4"><i class="bi bi-pencil-square me-3"></i>Editar Servicio</h1>

<?php
// Mostrar el mensaje de error o éxito
if (!empty($mensaje) && !isset($_GET['mensaje'])) {
    echo '<div class="alert alert-' . $tipo_mensaje . ' alert-dismissible fade show" role="alert">' . htmlspecialchars($mensaje) . '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
}
?>

<?php if ($servicio_a_editar): ?>
    <form action="servicios_editar.php?id=<?php echo htmlspecialchars($servicio_a_editar['id']); ?>" method="POST">
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre del Servicio:</label>
            <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo htmlspecialchars($servicio_a_editar['nombre']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción (opcional):</label>
            <textarea class="form-control" id="descripcion" name="descripcion" rows="3"><?php echo htmlspecialchars($servicio_a_editar['descripcion'] ?? ''); ?></textarea>
        </div>
        <div class="mb-3">
            <label for="precio_hora" class="form-label">Precio por Hora (€) (opcional):</label>
            <input type="number" step="0.01" class="form-control" id="precio_hora" name="precio_hora" value="<?php echo htmlspecialchars($servicio_a_editar['precio_hora'] ?? ''); ?>" min="0">
        </div>
        <div class="mb-3">
            <label for="profesor_id" class="form-label">Profesor Asignado (opcional):</label>
            <select class="form-select" id="profesor_id" name="profesor_id">
                <option value="">Selecciona un profesor</option>
                <?php foreach ($profesores as $prof): ?>
                    <option value="<?php echo htmlspecialchars($prof['id']); ?>" <?php echo ($prof['id'] == $servicio_a_editar['profesor_id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($prof['nombre']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary"><i class="bi bi-save me-2"></i>Actualizar Servicio</button>
        <a href="servicios.php" class="btn btn-secondary"><i class="bi bi-x-circle me-2"></i>Cancelar</a>
    </form>
<?php else: ?>
    <p>No se pudo cargar el servicio para editar.</p>
<?php endif; ?>

<?php
require_once '../includes/footer.php';
?>