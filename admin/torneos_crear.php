<?php
// admin/torneos_crear.php
require_once '../includes/header.php';
require_once '../config/db.php';

$mensaje = "";
$tipo_mensaje = "";

// Inicializar variables para el formulario para evitar errores de "Undefined array key"
// y para mantener los valores si hay un error de validación
$nombre = htmlspecialchars($_POST['nombre'] ?? '');
$fecha = htmlspecialchars($_POST['fecha'] ?? ''); // Formato YYYY-MM-DDTHH:MM
$precio_insc = htmlspecialchars($_POST['precio_insc'] ?? '');
$descripcion = htmlspecialchars($_POST['descripcion'] ?? '');
$max_participantes = htmlspecialchars($_POST['max_participantes'] ?? '');


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recoger y sanear los datos del formulario
    // trim() elimina espacios en blanco al inicio y final
    // htmlspecialchars() convierte caracteres especiales a entidades HTML para prevenir XSS al mostrar en el formulario
    $nombre_recibido = trim($_POST['nombre'] ?? '');
    $fecha_recibida = trim($_POST['fecha'] ?? '');
    $precio_insc_recibido = filter_var($_POST['precio_insc'] ?? 0, FILTER_VALIDATE_FLOAT); // Default 0 si no se envía o es inválido
    $descripcion_recibida = trim($_POST['descripcion'] ?? '');
    $max_participantes_recibido = filter_var($_POST['max_participantes'] ?? null, FILTER_VALIDATE_INT); // Null si no se envía o es inválido


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
        // Formatear la fecha para la base de datos (DATETIME)
        // El input type="datetime-local" envía el formato "YYYY-MM-DDTHH:MM"
        // MySQL espera "YYYY-MM-DD HH:MM:SS" o "YYYY-MM-DD HH:MM"
        $fecha_db = str_replace('T', ' ', $fecha_recibida); // Reemplaza 'T' por un espacio

        try {
            $stmt = $pdo->prepare("INSERT INTO torneos (nombre, fecha, precio_insc, descripcion, max_participantes) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$nombre_recibido, $fecha_db, $precio_insc_recibido, $descripcion_recibida, $max_participantes_recibido]);

            $mensaje = "Torneo creado correctamente.";
            $tipo_mensaje = "success";
            // Redirigir para evitar reenvío del formulario y mostrar el mensaje
            header("Location: torneos.php?mensaje=" . urlencode($mensaje) . "&tipo=" . urlencode($tipo_mensaje));
            exit();
        } catch (PDOException $e) {
            $mensaje = "Error al crear el torneo: " . $e->getMessage();
            $tipo_mensaje = "danger";
        }
    }
}
?>

<h1 class="mb-4"><i class="bi bi-trophy-fill me-3"></i>Añadir Nuevo Torneo</h1>

<?php
// Mostrar el mensaje de error o éxito si no se redirigió
if (!empty($mensaje) && !isset($_GET['mensaje'])) { // Solo mostrar aquí si no viene de una redirección
    echo '<div class="alert alert-' . $tipo_mensaje . ' alert-dismissible fade show" role="alert">' . htmlspecialchars($mensaje) . '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
}
?>

<form action="torneos_crear.php" method="POST">
    <div class="mb-3">
        <label for="nombre" class="form-label">Nombre del Torneo:</label>
        <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo $nombre; ?>" required>
    </div>
    <div class="mb-3">
        <label for="fecha" class="form-label">Fecha y Hora:</label>
        <input type="datetime-local" class="form-control" id="fecha" name="fecha" value="<?php echo $fecha; ?>" required>
    </div>
    <div class="mb-3">
        <label for="precio_insc" class="form-label">Precio de Inscripción (€):</label>
        <input type="number" step="0.01" class="form-control" id="precio_insc" name="precio_insc" value="<?php echo $precio_insc; ?>" min="0">
    </div>
    <div class="mb-3">
        <label for="descripcion" class="form-label">Descripción (opcional):</label>
        <textarea class="form-control" id="descripcion" name="descripcion" rows="3"><?php echo $descripcion; ?></textarea>
    </div>
    <div class="mb-3">
        <label for="max_participantes" class="form-label">Máx. Participantes (opcional):</label>
        <input type="number" class="form-control" id="max_participantes" name="max_participantes" value="<?php echo $max_participantes; ?>" min="0">
    </div>
    <button type="submit" class="btn btn-primary"><i class="bi bi-save me-2"></i>Guardar Torneo</button>
    <a href="torneos.php" class="btn btn-secondary"><i class="bi bi-x-circle me-2"></i>Cancelar</a>
</form>

<?php
require_once '../includes/footer.php';
?>