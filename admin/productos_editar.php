<?php
// admin/productos_editar.php
require_once '../includes/header.php';
require_once '../config/db.php';

$mensaje = "";
$tipo_mensaje = "";
$producto = null;
$categorias = [];

// Obtener ID del producto desde la URL
$producto_id = filter_var($_GET['id'] ?? '', FILTER_VALIDATE_INT);

if ($producto_id === false) {
    $mensaje = "ID de producto no válido.";
    $tipo_mensaje = "danger";
} else {
    // Obtener categorías para el select
    try {
        $stmt_categorias = $pdo->query("SELECT id, nombre FROM categorias ORDER BY nombre ASC");
        $categorias = $stmt_categorias->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $mensaje = "Error al cargar las categorías: " . $e->getMessage();
        $tipo_mensaje = "danger";
    }

    // Si se envió el formulario de edición
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nombre = trim($_POST['nombre'] ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');
        $precio = filter_var($_POST['precio'] ?? '', FILTER_VALIDATE_FLOAT);
        $stock = filter_var($_POST['stock'] ?? '', FILTER_VALIDATE_INT);
        $categoria_id = filter_var($_POST['categoria_id'] ?? '', FILTER_VALIDATE_INT);
        $imagen_actual = $_POST['imagen_actual'] ?? ''; // Nombre de la imagen actual
        $imagen_nueva = $_FILES['imagen_nueva'] ?? null; // Archivo de imagen nuevo

        // Validaciones
        if (empty($nombre) || empty($descripcion) || $precio === false || $stock === false || $categoria_id === false) {
            $mensaje = "Todos los campos obligatorios deben ser completados y tener un formato válido.";
            $tipo_mensaje = "danger";
        } elseif ($precio < 0.01) {
            $mensaje = "El precio debe ser un valor positivo.";
            $tipo_mensaje = "danger";
        } elseif ($stock < 0) {
            $mensaje = "El stock no puede ser negativo.";
            $tipo_mensaje = "danger";
        } else {
            $nombre_imagen_bd = $imagen_actual; // Por defecto, mantener la imagen actual

            if ($imagen_nueva && $imagen_nueva['error'] === UPLOAD_ERR_OK) {
                $directorio_subida = '../uploads/productos/';
                if (!is_dir($directorio_subida)) {
                    mkdir($directorio_subida, 0777, true);
                }

                $nombre_archivo = basename($imagen_nueva['name']);
                $extension = pathinfo($nombre_archivo, PATHINFO_EXTENSION);
                $nombre_unico = uniqid('prod_', true) . '.' . $extension;
                $ruta_completa = $directorio_subida . $nombre_unico;

                $tipos_permitidos = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                if (!in_array($imagen_nueva['type'], $tipos_permitidos)) {
                    $mensaje = "Tipo de archivo no permitido. Solo se aceptan JPEG, PNG, GIF, WEBP.";
                    $tipo_mensaje = "danger";
                } elseif ($imagen_nueva['size'] > 5 * 1024 * 1024) { // 5 MB
                    $mensaje = "El archivo es demasiado grande. Máximo 5 MB.";
                    $tipo_mensaje = "danger";
                } elseif (!move_uploaded_file($imagen_nueva['tmp_name'], $ruta_completa)) {
                    $mensaje = "Error al subir la nueva imagen.";
                    $tipo_mensaje = "danger";
                } else {
                    // Eliminar imagen antigua si no es la por defecto y se subió una nueva
                    if (!empty($imagen_actual) && $imagen_actual != 'default.jpg' && file_exists($directorio_subida . $imagen_actual)) {
                        unlink($directorio_subida . $imagen_actual);
                    }
                    $nombre_imagen_bd = $nombre_unico;
                }
            }

            if (empty($mensaje)) { // Solo intentar actualizar si no hay errores de validación/subida
                try {
                    $stmt = $pdo->prepare("UPDATE productos SET nombre = ?, descripcion = ?, precio = ?, stock = ?, categoria_id = ?, imagen = ? WHERE id = ?");
                    $stmt->execute([$nombre, $descripcion, $precio, $stock, $categoria_id, $nombre_imagen_bd, $producto_id]);

                    $mensaje = "Producto actualizado correctamente.";
                    $tipo_mensaje = "success";
                    // Redirigir a la lista de productos para mostrar el mensaje
                    header("Location: productos.php?mensaje=" . urlencode($mensaje) . "&tipo=" . urlencode($tipo_mensaje));
                    exit();

                } catch (PDOException $e) {
                    // Si hay un error de DB y se había subido la imagen nueva, eliminarla
                    if ($nombre_imagen_bd != $imagen_actual && $nombre_imagen_bd != 'default.jpg' && file_exists($ruta_completa)) {
                        unlink($ruta_completa);
                    }
                    $mensaje = "Error al actualizar el producto: " . $e->getMessage();
                    $tipo_mensaje = "danger";
                }
            }
        }
    }

    // Cargar datos del producto existente para mostrar en el formulario
    // Esto se hace *después* del procesamiento POST para que los valores mostrados reflejen los POST si hay un error
    try {
        $stmt = $pdo->prepare("SELECT * FROM productos WHERE id = ?");
        $stmt->execute([$producto_id]);
        $producto = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$producto) {
            $mensaje = "Producto no encontrado.";
            $tipo_mensaje = "danger";
        }
    } catch (PDOException $e) {
        $mensaje = "Error al cargar los datos del producto: " . $e->getMessage();
        $tipo_mensaje = "danger";
    }
}
?>

<h1 class="mb-4"><i class="bi bi-pencil-square me-3"></i>Editar Producto</h1>

<?php if ($producto): ?>
    <form action="productos_editar.php?id=<?php echo htmlspecialchars($producto_id); ?>" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre del Producto:</label>
            <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo htmlspecialchars($_POST['nombre'] ?? $producto['nombre']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción:</label>
            <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required><?php echo htmlspecialchars($_POST['descripcion'] ?? $producto['descripcion']); ?></textarea>
        </div>
        <div class="mb-3">
            <label for="precio" class="form-label">Precio (€):</label>
            <input type="number" step="0.01" class="form-control" id="precio" name="precio" value="<?php echo htmlspecialchars($_POST['precio'] ?? $producto['precio']); ?>" required min="0.01">
        </div>
        <div class="mb-3">
            <label for="stock" class="form-label">Stock:</label>
            <input type="number" class="form-control" id="stock" name="stock" value="<?php echo htmlspecialchars($_POST['stock'] ?? $producto['stock']); ?>" required min="0">
        </div>
        <div class="mb-3">
            <label for="categoria_id" class="form-label">Categoría:</label>
            <select class="form-select" id="categoria_id" name="categoria_id" required>
                <option value="">Selecciona una categoría</option>
                <?php foreach ($categorias as $cat): ?>
                    <option value="<?php echo htmlspecialchars($cat['id']); ?>" <?php echo ((isset($_POST['categoria_id']) && $_POST['categoria_id'] == $cat['id']) || (!isset($_POST['categoria_id']) && $cat['id'] == $producto['categoria_id'])) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($cat['nombre']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="imagen_nueva" class="form-label">Imagen del Producto (dejar en blanco para mantener la actual):</label>
            <input type="file" class="form-control" id="imagen_nueva" name="imagen_nueva" accept="image/*">
            <?php if (!empty($producto['imagen'])): ?>
                <small class="form-text text-muted d-block mt-2">Imagen actual: <br>
                    <img src="../uploads/productos/<?php echo htmlspecialchars($producto['imagen']); ?>" alt="Imagen actual" class="img-thumbnail mt-2" style="width: 150px; height: auto;">
                </small>
                <input type="hidden" name="imagen_actual" value="<?php echo htmlspecialchars($producto['imagen']); ?>">
            <?php else: ?>
                <small class="form-text text-muted">No hay imagen actual.</small>
                <input type="hidden" name="imagen_actual" value="default.jpg">
            <?php endif; ?>
            <div class="form-text">Formatos permitidos: JPG, PNG, GIF, WEBP. Tamaño máximo: 5MB.</div>
        </div>
        <button type="submit" class="btn btn-primary"><i class="bi bi-save me-2"></i>Actualizar Producto</button>
        <a href="productos.php" class="btn btn-secondary"><i class="bi bi-x-circle me-2"></i>Cancelar</a>
    </form>
<?php else: ?>
    <p class="alert alert-warning">No se pudo cargar el producto para editar o el ID no es válido.</p>
<?php endif; ?>

<?php
require_once '../includes/footer.php';
?>