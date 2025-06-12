<?php
// admin/pedidos_ver.php
require_once '../includes/header.php';
require_once '../config/db.php';

$pedido = null;
$detalles_pedido = [];
$mensaje = "";
$tipo_mensaje = "";

$pedido_id = filter_var($_GET['id'] ?? '', FILTER_VALIDATE_INT);

if ($pedido_id === false) {
    $mensaje = "ID de pedido no válido.";
    $tipo_mensaje = "danger";
} else {
    try {
        // Obtener datos del pedido
        $stmt_pedido = $pdo->prepare("
            SELECT 
                p.id, 
                p.usuario_id, 
                u.nombre AS nombre_usuario, 
                u.email AS email_usuario, 
                p.fecha_pedido, 
                p.total, 
                p.estado,
                p.direccion_envio,
                p.metodo_pago
            FROM pedidos p
            JOIN usuarios u ON p.usuario_id = u.id
            WHERE p.id = ?
        ");
        $stmt_pedido->execute([$pedido_id]);
        $pedido = $stmt_pedido->fetch(PDO::FETCH_ASSOC);

        if (!$pedido) {
            $mensaje = "Pedido no encontrado.";
            $tipo_mensaje = "danger";
        } else {
            // Obtener detalles de los productos del pedido
            $stmt_detalles = $pdo->prepare("
                SELECT 
                    dp.cantidad, 
                    dp.precio_unitario, 
                    prod.nombre AS nombre_producto,
                    prod.imagen AS imagen_producto
                FROM detalles_pedido dp
                JOIN productos prod ON dp.producto_id = prod.id
                WHERE dp.pedido_id = ?
            ");
            $stmt_detalles->execute([$pedido_id]);
            $detalles_pedido = $stmt_detalles->fetchAll(PDO::FETCH_ASSOC);
        }

    } catch (PDOException $e) {
        $mensaje = "Error al cargar los detalles del pedido: " . $e->getMessage();
        $tipo_mensaje = "danger";
    }
}
?>

<h1 class="mb-4"><i class="bi bi-receipt me-3"></i>Detalles del Pedido #<?php echo htmlspecialchars($pedido_id); ?></h1>

<?php if ($pedido): ?>
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-dark text-white">
            <h5 class="card-title mb-0">Información del Pedido</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>ID de Pedido:</strong> <?php echo htmlspecialchars($pedido['id']); ?></p>
                    <p><strong>Usuario:</strong> <a href="usuarios_editar.php?id=<?php echo htmlspecialchars($pedido['usuario_id']); ?>"><?php echo htmlspecialchars($pedido['nombre_usuario']); ?></a> (<?php echo htmlspecialchars($pedido['email_usuario']); ?>)</p>
                    <p><strong>Fecha del Pedido:</strong> <?php echo htmlspecialchars(date('d/m/Y H:i', strtotime($pedido['fecha_pedido']))); ?></p>
                    <p><strong>Total del Pedido:</strong> <strong class="text-success"><?php echo htmlspecialchars(number_format($pedido['total'], 2, ',', '.')) . ' €'; ?></strong></p>
                </div>
                <div class="col-md-6">
                    <p><strong>Estado:</strong>
                        <?php
                            $badge_class = '';
                            switch ($pedido['estado']) {
                                case 'pendiente': $badge_class = 'bg-warning text-dark'; break;
                                case 'procesando': $badge_class = 'bg-info'; break;
                                case 'enviado': $badge_class = 'bg-primary'; break;
                                case 'entregado': $badge_class = 'bg-success'; break;
                                case 'cancelado': $badge_class = 'bg-danger'; break;
                                default: $badge_class = 'bg-secondary'; break;
                            }
                        ?>
                        <span class="badge <?php echo $badge_class; ?> fs-6"><?php echo htmlspecialchars(ucfirst($pedido['estado'])); ?></span>
                    </p>
                    <p><strong>Dirección de Envío:</strong> <?php echo htmlspecialchars($pedido['direccion_envio'] ?? 'N/A'); ?></p>
                    <p><strong>Método de Pago:</strong> <?php echo htmlspecialchars($pedido['metodo_pago'] ?? 'N/A'); ?></p>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-dark text-white">
            <h5 class="card-title mb-0">Productos del Pedido</h5>
        </div>
        <div class="card-body">
            <?php if (count($detalles_pedido) > 0): ?>
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Imagen</th>
                                <th>Producto</th>
                                <th>Cantidad</th>
                                <th>Precio Unitario</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($detalles_pedido as $detalle): ?>
                                <tr>
                                    <td>
                                        <?php if (!empty($detalle['imagen_producto'])): ?>
                                            <img src="../uploads/productos/<?php echo htmlspecialchars($detalle['imagen_producto']); ?>" alt="Imagen de <?php echo htmlspecialchars($detalle['nombre_producto']); ?>" class="img-thumbnail" style="width: 50px; height: auto;">
                                        <?php else: ?>
                                            <img src="../uploads/productos/default.jpg" alt="Sin imagen" class="img-thumbnail" style="width: 50px; height: auto;">
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($detalle['nombre_producto']); ?></td>
                                    <td><?php echo htmlspecialchars($detalle['cantidad']); ?></td>
                                    <td><?php echo htmlspecialchars(number_format($detalle['precio_unitario'], 2, ',', '.')) . ' €'; ?></td>
                                    <td><?php echo htmlspecialchars(number_format($detalle['cantidad'] * $detalle['precio_unitario'], 2, ',', '.')) . ' €'; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="text-center">No hay productos en este pedido.</p>
            <?php endif; ?>
        </div>
        <div class="card-footer text-end">
            <a href="pedidos.php" class="btn btn-secondary"><i class="bi bi-arrow-left me-2"></i>Volver a Pedidos</a>
        </div>
    </div>
<?php else: ?>
    <p class="alert alert-warning">No se pudo cargar el pedido o el ID no es válido.</p>
<?php endif; ?>

<?php
require_once '../includes/footer.php';
?>