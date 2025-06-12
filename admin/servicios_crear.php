<?php
// admin/servicios_crear.php
require_once '../includes/header.php';
require_once '../config/db.php';

$mensaje = "";
$tipo_mensaje = "";
$profesores = []; // Para el dropdown de profesores

// Obtener la lista de usuarios con rol 'profesor'
try {
    $stmt_profesores = $pdo->prepare("SELECT id, nombre FROM usuarios WHERE rol = 'profesor' ORDER BY nombre ASC");
    $stmt_profesores->execute();
    $profesores = $stmt_profesores->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $mensaje = "Error al cargar la lista de profesores: " . $e->getMessage();
    $tipo_mensaje = "danger";
}

// Inicializar variables para el formulario para evitar errores de "Undefined array key"
$nombre = htmlspecialchars($_POST['nombre'] ?? '');
$descripcion = htmlspecialchars($_POST['descripcion'] ?? '');
$precio_hora = htmlspecialchars($_POST['precio_hora'] ?? '');
$profesor_id = htmlspecialchars($_POST['profesor_id'] ?? '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recoger y sanear los datos del formulario
    $nombre_recibido = trim($_POST['nombre'] ?? '');
    $descripcion_recibida = trim($_POST['descripcion'] ?? '');
    $precio_hora_recibido = filter_var($_POST['precio_hora'] ?? null, FILTER_VALIDATE_FLOAT); // Puede ser null
    $profesor_id_recibido = filter_var($_POST['profesor_id'] ?? null, FILTER_VALIDATE_INT); // Puede ser null

    // Validaciones
    if (empty($nombre_recibido)) {
        $mensaje = "El nombre del servicio es obligatorio.";
        $tipo_mensaje = "danger";
    } elseif ($precio_hora_recibido === false || ($precio_hora_recibido !== null && $precio_hora_recibido < 0)) {
        $mensaje = "El precio por hora no es válido.";
        $tipo_mensaje = "danger";
    } elseif ($profesor_id_recibido === false && !is_null($_POST['profesor_id']) && $_POST['profesor_id'] !== '') { // Verifica si se envió algo que no es INT
         $mensaje = "El profesor seleccionado no es válido.";
         $tipo_mensaje = "danger";
    }
    else {
        try {
            $stmt = $pdo->prepare("INSERT INTO servicios (nombre, descripcion, precio_hora, profesor_id) VALUES (?, ?, ?, ?)");
            // Si profesor_id_recibido es 0 o vacío (del select "Selecciona un profesor"), lo convertimos a NULL
            $profesor_final = ($profesor_id_recibido === 0 || $profesor_id_recibido === null) ? null : $profesor_id_recibido;

            $stmt->execute([$nombre_recibido, $descripcion_recibida, $precio_hora_recibido, $profesor_final]);

            $mensaje = "Servicio creado correctamente.";
            $tipo_mensaje = "success";
            // Redirigir a servicios.php con el mensaje
            header("Location: servicios.php?mensaje=" . urlencode($mensaje) . "&tipo=" . urlencode($tipo_mensaje));
            exit();
        } catch (PDOException $e) {
            $mensaje = "Error al crear el servicio: " . $e->getMessage();
            $tipo_mensaje = "danger";
        }
    }
}
?>

<h1 class="mb-4"><i class="bi bi-tools me-3"></i>Añadir Nuevo Servicio</h1>

<?php
// Mostrar el mensaje de error o éxito si no se redirigió
if (!empty($mensaje) && !isset($_GET['mensaje'])) {
    echo '<div class="alert alert-' . $tipo_mensaje . ' alert-dismissible fade show" role="alert">' . htmlspecialchars($mensaje) . '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
}
?>

<form action="servicios_crear.php" method="POST">
    <div class="mb-3">
        <label for="nombre" class="form-label">Nombre del Servicio:</label>
        <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo $nombre; ?>" required>
    </div>
    <div class="mb-3">
        <label for="descripcion" class="form-label">Descripción (opcional):</label>
        <textarea class="form-control" id="descripcion" name="descripcion" rows="3"><?php echo $descripcion; ?></textarea>
    </div>
    <div class="mb-3">
        <label for="precio_hora" class="form-label">Precio por Hora (€) (opcional):</label>
        <input type="number" step="0.01" class="form-control" id="precio_hora" name="precio_hora" value="<?php echo $precio_hora; ?>" min="0">
    </div>
    <div class="mb-3">
        <label for="profesor_id" class="form-label">Profesor Asignado (opcional):</label>
        <select class="form-select" id="profesor_id" name="profesor_id">
            <option value="">Selecciona un profesor</option>
            <?php foreach ($profesores as $prof): ?>
                <option value="<?php echo htmlspecialchars($prof['id']); ?>" <?php echo ($prof['id'] == $profesor_id) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($prof['nombre']); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <button type="submit" class="btn btn-primary"><i class="bi bi-save me-2"></i>Guardar Servicio</button>
    <a href="servicios.php" class="btn btn-secondary"><i class="bi bi-x-circle me-2"></i>Cancelar</a>
</form>

<?php
require_once '../includes/footer.php';
?>