<?php
// admin/categorias.php
require_once '../includes/header.php';
require_once '../config/db.php';

$mensaje = "";
$tipo_mensaje = "";

// Lógica para eliminar una categoría
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $categoria_id = filter_var($_GET['id'], FILTER_VALIDATE_INT);

    if ($categoria_id === false) {
        $mensaje = "ID de categoría no válido.";
        $tipo_mensaje = "danger";
    } else {
        try {
            // Verificar si hay productos asociados a esta categoría
            $stmt_check_productos = $pdo->prepare("SELECT COUNT(*) FROM productos WHERE categoria_id = ?");
            $stmt_check_productos->execute([$categoria_id]);
            $productos_asociados = $stmt_check_productos->fetchColumn();

            if ($productos_asociados > 0) {
                $mensaje = "No se puede eliminar la categoría porque tiene productos asociados. Elimina o reasigna los productos primero.";
                $tipo_mensaje = "warning";
            } else {
                $stmt = $pdo->prepare("DELETE FROM categorias WHERE id = ?");
                $stmt->execute([$categoria_id]);

                if ($stmt->rowCount() > 0) {
                    $mensaje = "Categoría eliminada correctamente.";
                    $tipo_mensaje = "success";
                } else {
                    $mensaje = "Categoría no encontrada o no se pudo eliminar.";
                    $tipo_mensaje = "danger";
                }
            }
        } catch (PDOException $e) {
            $mensaje = "Error al eliminar la categoría: " . $e->getMessage();
            $tipo_mensaje = "danger";
        }
    }
    header("Location: categorias.php?mensaje=" . urlencode($mensaje) . "&tipo=" . urlencode($tipo_mensaje));
    exit();
}

// Lógica para obtener todas las categorías
$categorias = [];
try {
    $stmt = $pdo->query("SELECT id, nombre, descripcion FROM categorias ORDER BY nombre ASC");
    $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $mensaje = "Error al cargar las categorías: " . $e->getMessage();
    $tipo_mensaje = "danger";
}

// Cargar mensajes pasados por la URL (después de una redirección)
if (isset($_GET['mensaje']) && isset($_GET['tipo'])) {
    $mensaje = htmlspecialchars($_GET['mensaje']);
    $tipo_mensaje = htmlspecialchars($_GET['tipo']);
}
?>

<h1 class="mb-4"><i class="bi bi-tags-fill me-3"></i>Gestión de Categorías</h1>

<a href="categorias_crear.php" class="btn btn-success mb-4"><i class="bi bi-plus-circle me-2"></i> Añadir Nueva Categoría</a>

<div class="table-responsive">
    <table class="table table-striped table-hover align-middle">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($categorias) > 0): ?>
                <?php foreach ($categorias as $categoria): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($categoria['id']); ?></td>
                        <td><?php echo htmlspecialchars($categoria['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($categoria['descripcion']); ?></td>
                        <td>
                            <a href="categorias_editar.php?id=<?php echo $categoria['id']; ?>" class="btn btn-sm btn-primary me-2" title="Editar"><i class="bi bi-pencil"></i></a>
                            <a href="categorias.php?action=delete&id=<?php echo $categoria['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro de que quieres eliminar esta categoría? Esto eliminará también los productos asociados a ella si la FK es CASCADE.');" title="Eliminar"><i class="bi bi-trash"></i></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" class="text-center">No hay categorías registradas.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php
require_once '../includes/footer.php';
?>