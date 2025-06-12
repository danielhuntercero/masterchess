<?php
// admin/productos_crear.php
require_once '../includes/header.php';
require_once '../config/db.php';

$mensaje = "";
$tipo_mensaje = "";
$categorias = [];

// Obtener categorías para el select
try {
    $stmt_categorias = $pdo->query("SELECT id, nombre FROM categorias ORDER BY nombre ASC");
    $categorias = $stmt_categorias->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $mensaje = "Error al cargar las categorías: " . $e->getMessage();
    $tipo_mensaje = "danger";
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    $precio = filter_var($_POST['precio'] ?? '', FILTER_VALIDATE_FLOAT);
    $stock = filter_var($_POST['stock'] ?? '', FILTER_VALIDATE_INT);
    $categoria_id = filter_var($_POST['categoria_id'] ?? '', FILTER_VALIDATE_INT);
    $imagen = $_FILES['imagen'] ?? null; // Obtener el archivo de imagen

    // Validaciones básicas del lado del servidor
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
        $nombre_imagen_bd = 'default.jpg'; // Imagen por defecto si no se sube ninguna

        if ($imagen && $imagen['error'] === UPLOAD_ERR_OK) {
            $directorio_subida = '../uploads/productos/';
            // Crear el directorio si no existe
            if (!is_dir($directorio_subida)) {
                mkdir($directorio_subida, 0777, true);
            }

            $nombre_archivo = basename($imagen['name']);
            $extension = pathinfo($nombre_archivo, PATHINFO_EXTENSION);
            $nombre_unico = uniqid('prod_', true) . '.' . $extension; // Generar nombre único
            $ruta_completa = $directorio_subida . $nombre_unico;

            // Validar tipo de archivo
            $tipos_permitidos = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            if (!in_array($imagen['type'], $tipos_permitidos)) {
                $mensaje = "Tipo de archivo no permitido. Solo se aceptan JPEG, PNG, GIF, WEBP.";
                $tipo_mensaje = "danger";
            } elseif ($imagen['size'] > 5 * 1024 * 1024) { // 5 MB
                $mensaje = "El archivo es demasiado grande. Máximo 5 MB.";
                $tipo_mensaje = "danger";
            } elseif (!move_uploaded_file($imagen['tmp_name'], $ruta_completa)) {
                $mensaje = "Error al subir la imagen.";
                $tipo_mensaje = "danger";
            } else {
                $nombre_imagen_bd = $nombre_unico;
            }
        }

        if (empty($mensaje)) { // Solo intentar insertar si no hay errores previos
            try {
                $stmt = $pdo->prepare("INSERT INTO productos (nombre, descripcion, precio, stock, categoria_id, imagen) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->execute([$nombre, $descripcion, $precio, $stock, $categoria_id, $nombre_imagen_bd]);

                $mensaje = "Producto añadido correctamente.";
                $tipo_mensaje = "success";
                // Redirigir para evitar reenvío del formulario y mostrar mensaje en la lista
                header("Location: productos.php?mensaje=" . urlencode($mensaje) . "&tipo=" . urlencode($tipo_mensaje));
                exit();

            } catch (PDOException $e) {
                // Si hay un error de DB y se había subido la imagen, eliminarla
                if ($nombre_imagen_bd != 'default.jpg' && file_exists($ruta_completa)) {
                    unlink($ruta_completa);
                }
                $mensaje = "Error al añadir el producto: " . $e->getMessage();
                $tipo_mensaje = "danger";
            }
        }
    }
}
?>

<h1 class="mb-4"><i class="bi bi-plus-circle me-3"></i>Añadir Nuevo Producto</h1>

<form action="productos_crear.php" method="POST" enctype="multipart/form-data">
    <div class="mb-3">
        <label for="nombre" class="form-label">Nombre del Producto:</label>
        <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo htmlspecialchars($_POST['nombre'] ?? ''); ?>" required>
    </div>
    <div class="mb-3">
        <label for="descripcion" class="form-label">Descripción:</label>
        <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required><?php echo htmlspecialchars($_POST['descripcion'] ?? ''); ?></textarea>
    </div>
    <div class="mb-3">
        <label for="precio" class="form-label">Precio (€):</label>
        <input type="number" step="0.01" class="form-control" id="precio" name="precio" value="<?php echo htmlspecialchars($_POST['precio'] ?? ''); ?>" required min="0.01">
    </div>
    <div class="mb-3">
        <label for="stock" class="form-label">Stock:</label>
        <input type="number" class="form-control" id="stock" name="stock" value="<?php echo htmlspecialchars($_POST['stock'] ?? ''); ?>" required min="0">
    </div>
    <div class="mb-3">
        <label for="categoria_id" class="form-label">Categoría:</label>
        <select class="form-select" id="categoria_id" name="categoria_id" required>
            <option value="">Selecciona una categoría</option>
            <?php foreach ($categorias as $cat): ?>
                <option value="<?php echo htmlspecialchars($cat['id']); ?>" <?php echo ((isset($_POST['categoria_id']) && $_POST['categoria_id'] == $cat['id']) ? 'selected' : ''); ?>>
                    <?php echo htmlspecialchars($cat['nombre']); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="mb-3">
        <label for="imagen" class="form-label">Imagen del Producto (opcional):</label>
        <input type="file" class="form-control" id="imagen" name="imagen" accept="image/*">
        <div class="form-text">Formatos permitidos: JPG, PNG, GIF, WEBP. Tamaño máximo: 5MB.</div>
    </div>
    <button type="submit" class="btn btn-primary"><i class="bi bi-save me-2"></i>Guardar Producto</button>
    <a href="productos.php" class="btn btn-secondary"><i class="bi bi-x-circle me-2"></i>Cancelar</a>
</form>

<?php
require_once '../includes/footer.php';
?>