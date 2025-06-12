<?php
// admin/categorias_crear.php
require_once '../includes/header.php';
require_once '../config/db.php';

$mensaje = "";
$tipo_mensaje = "";

// Inicializar variables para mantener los valores en el formulario si hay un error
// Esto es importante para que los campos no se borren si la validación falla
$nombre = htmlspecialchars($_POST['nombre'] ?? '');
$descripcion = htmlspecialchars($_POST['descripcion'] ?? '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Los datos ya están limpios y precargados en las variables $nombre y $descripcion
    // que se inicializaron arriba usando $_POST ?? ''

    if (empty($nombre)) {
        $mensaje = "El nombre de la categoría es obligatorio.";
        $tipo_mensaje = "danger";
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO categorias (nombre, descripcion) VALUES (?, ?)");
            $stmt->execute([$nombre, $descripcion]);

            $mensaje = "Categoría creada correctamente.";
            $tipo_mensaje = "success";
            // Redirigir a categorias.php con el mensaje
            header("Location: categorias.php?mensaje=" . urlencode($mensaje) . "&tipo=" . urlencode($tipo_mensaje));
            exit();
        } catch (PDOException $e) {
            $mensaje = "Error al crear la categoría: " . $e->getMessage();
            $tipo_mensaje = "danger";
        }
    }
    // Si hay un error (mensaje y tipo_mensaje se establecieron), la página se recargará
    // y header.php mostrará el mensaje si ya está incluido en él.
    // Si no, el mensaje se mostrará al final de este script.
}
?>

<h1 class="mb-4"><i class="bi bi-plus-circle me-3"></i>Añadir Nueva Categoría</h1>

<?php /*
if (!empty($mensaje)) {
    echo '<div class="alert alert-' . $tipo_mensaje . ' alert-dismissible fade show" role="alert">' . htmlspecialchars($mensaje) . '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
}
*/ ?>

<form action="categorias_crear.php" method="POST">
    <div class="mb-3">
        <label for="nombre" class="form-label">Nombre de la Categoría:</label>
        <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo $nombre; ?>" required>
    </div>
    <div class="mb-3">
        <label for="descripcion" class="form-label">Descripción (opcional):</label>
        <textarea class="form-control" id="descripcion" name="descripcion" rows="3"><?php echo $descripcion; ?></textarea>
    </div>
    <button type="submit" class="btn btn-primary"><i class="bi bi-save me-2"></i>Guardar Categoría</button>
    <a href="categorias.php" class="btn btn-secondary"><i class="bi bi-x-circle me-2"></i>Cancelar</a>
</form>

<?php
require_once '../includes/footer.php';
?>