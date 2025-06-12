<?php
// admin/categorias_editar.php
require_once '../includes/header.php';
require_once '../config/db.php';

$mensaje = "";
$tipo_mensaje = "";
$categoria = null;

$categoria_id = filter_var($_GET['id'] ?? '', FILTER_VALIDATE_INT);

if ($categoria_id === false) {
    $mensaje = "ID de categoría no válido.";
    $tipo_mensaje = "danger";
} else {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nombre = trim($_POST['nombre'] ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');

        if (empty($nombre)) {
            $mensaje = "El nombre de la categoría es obligatorio.";
            $tipo_mensaje = "danger";
        } else {
            try {
                $stmt = $pdo->prepare("UPDATE categorias SET nombre = ?, descripcion = ? WHERE id = ?");
                $stmt->execute([$nombre, $descripcion, $categoria_id]);

                $mensaje = "Categoría actualizada correctamente.";
                $tipo_mensaje = "success";
                header("Location: categorias.php?mensaje=" . urlencode($mensaje) . "&tipo=" . urlencode($tipo_mensaje));
                exit();
            } catch (PDOException $e) {
                $mensaje = "Error al actualizar la categoría: " . $e->getMessage();
                $tipo_mensaje = "danger";
            }
        }
    }

    // Cargar datos de la categoría para el formulario
    try {
        $stmt = $pdo->prepare("SELECT id, nombre, descripcion FROM categorias WHERE id = ?");
        $stmt->execute([$categoria_id]);
        $categoria = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$categoria) {
            $mensaje = "Categoría no encontrada.";
            $tipo_mensaje = "danger";
        }
    } catch (PDOException $e) {
        $mensaje = "Error al cargar los datos de la categoría: " . $e->getMessage();
        $tipo_mensaje = "danger";
    }
}
?>

<h1 class="mb-4"><i class="bi bi-pencil-square me-3"></i>Editar Categoría</h1>

<?php if ($categoria): ?>
    <form action="categorias_editar.php?id=<?php echo htmlspecialchars($categoria_id); ?>" method="POST">
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre de la Categoría:</label>
            <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo htmlspecialchars($_POST['nombre'] ?? $categoria['nombre']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción (opcional):</label>
            <textarea class="form-control" id="descripcion" name="descripcion" rows="3"><?php echo htmlspecialchars($_POST['descripcion'] ?? $categoria['descripcion']); ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary"><i class="bi bi-save me-2"></i>Actualizar Categoría</button>
        <a href="categorias.php" class="btn btn-secondary"><i class="bi bi-x-circle me-2"></i>Cancelar</a>
    </form>
<?php else: ?>
    <p class="alert alert-warning">No se pudo cargar la categoría para editar o el ID no es válido.</p>
<?php endif; ?>

<?php
require_once '../includes/footer.php';
?>