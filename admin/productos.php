<?php
// admin/productos.php
require_once '../includes/header.php'; // Incluye el encabezado y la verificación de sesión
require_once '../config/db.php';     // Asegúrate de que la conexión a la BD está disponible

$mensaje = "";
$tipo_mensaje = "";

// Lógica para eliminar un producto
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $producto_id = filter_var($_GET['id'], FILTER_VALIDATE_INT);

    if ($producto_id === false) {
        $mensaje = "ID de producto no válido.";
        $tipo_mensaje = "danger";
    } else {
        try {
            // Primero, verificar si el producto está en detalles_pedido o items_carrito para evitar errores de FK
            // Asumo que tienes estas tablas. Si no, puedes omitir esta verificación.
            $stmt_check_pedido = $pdo->prepare("SELECT COUNT(*) FROM detalles_pedido WHERE producto_id = ?");
            $stmt_check_pedido->execute([$producto_id]);
            $en_pedidos = $stmt_check_pedido->fetchColumn();

            $stmt_check_carrito = $pdo->prepare("SELECT COUNT(*) FROM items_carrito WHERE producto_id = ?");
            $stmt_check_carrito->execute([$producto_id]);
            $en_carrito = $stmt_check_carrito->fetchColumn();

            if ($en_pedidos > 0 || $en_carrito > 0) {
                $mensaje = "No se puede eliminar el producto porque está asociado a pedidos o carritos existentes.";
                $tipo_mensaje = "warning"; // Cambiado a warning para ser menos agresivo que danger
            } else {
                // Si la imagen existe, intentar eliminarla del servidor
                $stmt_img = $pdo->prepare("SELECT imagen FROM productos WHERE id = ?");
                $stmt_img->execute([$producto_id]);
                $imagen_a_eliminar = $stmt_img->fetchColumn();

                $stmt = $pdo->prepare("DELETE FROM productos WHERE id = ?");
                $stmt->execute([$producto_id]);

                if ($stmt->rowCount() > 0) {
                    $mensaje = "Producto eliminado correctamente.";
                    $tipo_mensaje = "success";
                    // Eliminar la imagen del servidor si no es la imagen por defecto y existe
                    if (!empty($imagen_a_eliminar) && $imagen_a_eliminar != 'default.jpg' && file_exists('../uploads/productos/' . $imagen_a_eliminar)) {
                        unlink('../uploads/productos/' . $imagen_a_eliminar);
                    }
                } else {
                    $mensaje = "Producto no encontrado o no se pudo eliminar.";
                    $tipo_mensaje = "danger";
                }
            }
        } catch (PDOException $e) {
            $mensaje = "Error al eliminar el producto: " . $e->getMessage();
            $tipo_mensaje = "danger";
        }
    }
    // Redirigir para evitar reenvío del formulario/GET después de la acción
    header("Location: productos.php?mensaje=" . urlencode($mensaje) . "&tipo=" . urlencode($tipo_mensaje));
    exit();
}

// Lógica para obtener todos los productos (y su categoría)
$productos = [];
try {
    $stmt = $pdo->query("SELECT p.*, c.nombre AS categoria_nombre FROM productos p JOIN categorias c ON p.categoria_id = c.id ORDER BY p.id DESC");
    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $mensaje = "Error al cargar los productos: " . $e->getMessage();
    $tipo_mensaje = "danger";
}

// Cargar mensajes pasados por la URL (después de una redirección)
if (isset($_GET['mensaje']) && isset($_GET['tipo'])) {
    $mensaje = htmlspecialchars($_GET['mensaje']);
    $tipo_mensaje = htmlspecialchars($_GET['tipo']);
}
?>

<h1 class="mb-4"><i class="bi bi-box-seam me-3"></i>Gestión de Productos</h1>

<a href="productos_crear.php" class="btn btn-success mb-4"><i class="bi bi-plus-circle me-2"></i> Añadir Nuevo Producto</a>

<div class="table-responsive">
    <table class="table table-striped table-hover align-middle">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Imagen</th>
                <th>Nombre</th>
                <th>Categoría</th>
                <th>Precio</th>
                <th>Stock</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($productos) > 0): ?>
                <?php foreach ($productos as $producto): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($producto['id']); ?></td>
                        <td>
                            <?php if (!empty($producto['imagen'])): ?>
                                <img src="../uploads/productos/<?php echo htmlspecialchars($producto['imagen']); ?>" alt="Imagen de <?php echo htmlspecialchars($producto['nombre']); ?>" class="img-thumbnail product-image-thumbnail">
                            <?php else: ?>
                                <img src="../uploads/productos/default.jpg" alt="Sin imagen" class="img-thumbnail product-image-thumbnail">
                            <?php endif; ?>
                        </td>
                        <td><?php echo htmlspecialchars($producto['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($producto['categoria_nombre']); ?></td>
                        <td><?php echo htmlspecialchars(number_format($producto['precio'], 2, ',', '.')) . ' €'; ?></td>
                        <td><?php echo htmlspecialchars($producto['stock']); ?></td>
                        <td>
                            <a href="productos_editar.php?id=<?php echo $producto['id']; ?>" class="btn btn-sm btn-primary me-2" title="Editar"><i class="bi bi-pencil"></i></a>
                            <a href="productos.php?action=delete&id=<?php echo $producto['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro de que quieres eliminar este producto?');" title="Eliminar"><i class="bi bi-trash"></i></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" class="text-center">No hay productos registrados.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php
require_once '../includes/footer.php';
?>