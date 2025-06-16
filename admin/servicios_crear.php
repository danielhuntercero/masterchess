<?php
// admin/servicios_crear.php
// Agrega esto al inicio de servicios_crear.php, justo después de los requires
$upload_dir = '../uploads/servicios/';

// Crear el directorio si no existe
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0777, true); // Crea directorios recursivamente
}
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

// Inicializar variables para el formulario
$nombre = htmlspecialchars($_POST['nombre'] ?? '');
$descripcion = htmlspecialchars($_POST['descripcion'] ?? '');
$precio_hora = htmlspecialchars($_POST['precio_hora'] ?? '');
$profesor_id = htmlspecialchars($_POST['profesor_id'] ?? '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recoger y sanear los datos del formulario
    $nombre_recibido = trim($_POST['nombre'] ?? '');
    $descripcion_recibida = trim($_POST['descripcion'] ?? '');
    $precio_hora_recibido = filter_var($_POST['precio_hora'] ?? null, FILTER_VALIDATE_FLOAT);
    $profesor_id_recibido = filter_var($_POST['profesor_id'] ?? null, FILTER_VALIDATE_INT);
    
    // Procesar la imagen subida
    $imagen_nombre = null;
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $extension = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
        $extensiones_permitidas = ['jpg', 'jpeg', 'png', 'gif'];
        
        if (in_array(strtolower($extension), $extensiones_permitidas)) {
            $imagen_nombre = uniqid() . '.' . $extension;
            $ruta_destino = "../uploads/servicios/" . $imagen_nombre;
            
            if (!move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta_destino)) {
                $mensaje = "Error al subir la imagen.";
                $tipo_mensaje = "danger";
            }
        } else {
            $mensaje = "Formato de imagen no permitido. Use JPG, JPEG, PNG o GIF.";
            $tipo_mensaje = "danger";
        }
    }

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
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO servicios (nombre, descripcion, precio_hora, profesor_id, imagen) VALUES (?, ?, ?, ?, ?)");
            $profesor_final = ($profesor_id_recibido === 0 || $profesor_id_recibido === null) ? null : $profesor_id_recibido;
            
            $stmt->execute([$nombre_recibido, $descripcion_recibida, $precio_hora_recibido, $profesor_final, $imagen_nombre]);

            $mensaje = "Servicio creado correctamente.";
            $tipo_mensaje = "success";
            // Redirigir a servicios.php con el mensaje
            header("Location: servicios.php?mensaje=" . urlencode($mensaje) . "&tipo=" . urlencode($tipo_mensaje));
            exit();
        } catch (PDOException $e) {
            // Eliminar la imagen si hubo error al guardar en BD
            if ($imagen_nombre && file_exists("../uploads/servicios/" . $imagen_nombre)) {
                unlink("../uploads/servicios/" . $imagen_nombre);
            }
            
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

<form action="servicios_crear.php" method="POST" enctype="multipart/form-data">
    <div class="mb-3">
        <label for="nombre" class="form-label">Nombre del Servicio:</label>
        <input type="text" class="form-control" id="nombre" name="nombre" value="<?= $nombre ?>" required>
    </div>
    <div class="mb-3">
        <label for="descripcion" class="form-label">Descripción (opcional):</label>
        <textarea class="form-control" id="descripcion" name="descripcion" rows="3"><?= $descripcion ?></textarea>
    </div>
    <div class="mb-3">
        <label for="precio_hora" class="form-label">Precio por Hora (€) (opcional):</label>
        <input type="number" step="0.01" class="form-control" id="precio_hora" name="precio_hora" value="<?= $precio_hora ?>" min="0">
    </div>
    <div class="mb-3">
        <label for="profesor_id" class="form-label">Profesor Asignado (opcional):</label>
        <select class="form-select" id="profesor_id" name="profesor_id">
            <option value="">Selecciona un profesor</option>
            <?php foreach ($profesores as $prof): ?>
                <option value="<?= htmlspecialchars($prof['id']) ?>" <?= ($prof['id'] == $profesor_id) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($prof['nombre']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="mb-3">
        <label for="imagen" class="form-label">Imagen del Servicio (opcional):</label>
        <input type="file" class="form-control" id="imagen" name="imagen" accept="image/*">
        <div class="form-text">Formatos permitidos: JPG, JPEG, PNG, GIF. Tamaño máximo: 2MB.</div>
    </div>
    <button type="submit" class="btn btn-primary"><i class="bi bi-save me-2"></i>Guardar Servicio</button>
    <a href="servicios.php" class="btn btn-secondary"><i class="bi bi-x-circle me-2"></i>Cancelar</a>
</form>

<?php
require_once '../includes/footer.php';
?>