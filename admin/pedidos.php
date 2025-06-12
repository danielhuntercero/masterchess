<?php
// admin/pedidos.php
require_once '../includes/header.php';
require_once '../config/db.php';

$mensaje = "";
$tipo_mensaje = "";

// Lógica para eliminar un pedido (con sus detalles)
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $pedido_id = filter_var($_GET['id'], FILTER_VALIDATE_INT);

    if ($pedido_id === false) {
        $mensaje = "ID de pedido no válido.";
        $tipo_mensaje = "danger";
    } else {
        try {
            $pdo->beginTransaction(); // Iniciar transacción

            // Eliminar los detalles del pedido primero
            $stmt_detalles = $pdo->prepare("DELETE FROM detalles_pedido WHERE pedido_id = ?");
            $stmt_detalles->execute([$pedido_id]);

            // Luego eliminar el pedido principal
            $stmt_pedido = $pdo->prepare("DELETE FROM pedidos WHERE id = ?");
            $stmt_pedido->execute([$pedido_id]);

            $pdo->commit(); // Confirmar la transacción

            if ($stmt_pedido->rowCount() > 0) {
                $mensaje = "Pedido eliminado correctamente (incluyendo sus detalles).";
                $tipo_mensaje = "success";
            } else {
                $mensaje = "Pedido no encontrado o no se pudo eliminar.";
                $tipo_mensaje = "danger";
            }
        } catch (PDOException $e) {
            $pdo->rollBack(); // Revertir la transacción si hay un error
            $mensaje = "Error al eliminar el pedido: " . $e->getMessage();
            $tipo_mensaje = "danger";
        }
    }
    header("Location: pedidos.php?mensaje=" . urlencode($mensaje) . "&tipo=" . urlencode($tipo_mensaje));
    exit();
}

// Lógica para cambiar el estado de un pedido
if (isset($_GET['action']) && $_GET['action'] === 'change_status' && isset($_GET['id']) && isset($_GET['new_status'])) {
    $pedido_id = filter_var($_GET['id'], FILTER_VALIDATE_INT);
    $new_status = $_GET['new_status'];

    // Validar el estado
    $estados_validos = ['pendiente', 'procesando', 'enviado', 'entregado', 'cancelado'];
    if ($pedido_id === false || !in_array($new_status, $estados_validos)) {
        $mensaje = "Parámetros de cambio de estado no válidos.";
        $tipo_mensaje = "danger";
    } else {
        try {
            $stmt = $pdo->prepare("UPDATE pedidos SET estado = ? WHERE id = ?");
            $stmt->execute([$new_status, $pedido_id]);

            if ($stmt->rowCount() > 0) {
                $mensaje = "Estado del pedido actualizado a '" . ucfirst($new_status) . "'.";
                $tipo_mensaje = "info";
            } else {
                $mensaje = "Pedido no encontrado o el estado ya era el mismo.";
                $tipo_mensaje = "warning";
            }
        } catch (PDOException $e) {
            $mensaje = "Error al actualizar el estado del pedido: " . $e->getMessage();
            $tipo_mensaje = "danger";
        }
    }
    header("Location: pedidos.php?mensaje=" . urlencode($mensaje) . "&tipo=" . urlencode($tipo_mensaje));
    exit();
}


// Lógica para obtener todos los pedidos
$pedidos = [];
try {
    $stmt = $pdo->query("
        SELECT 
            p.id, 
            p.usuario_id, 
            u.nombre AS nombre_usuario, 
            p.fecha_pedido, 
            p.total, 
            p.estado,
            p.direccion_envio,
            p.metodo_pago
        FROM pedidos p
        JOIN usuarios u ON p.usuario_id = u.id
        ORDER BY p.fecha_pedido DESC
    ");
    $pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $mensaje = "Error al cargar los pedidos: " . $e->getMessage();
    $tipo_mensaje = "danger";
}

// Cargar mensajes pasados por la URL (después de una redirección)
if (isset($_GET['mensaje']) && isset($_GET['tipo'])) {
    $mensaje = htmlspecialchars($_GET['mensaje']);
    $tipo_mensaje = htmlspecialchars($_GET['tipo']);
}
?>

<h1 class="mb-4"><i class="bi bi-cart-check-fill me-3"></i>Gestión de Pedidos</h1>

<div class="table-responsive">
    <table class="table table-striped table-hover align-middle">
        <thead class="table-dark">
            <tr>
                <th>ID Pedido</th>
                <th>Usuario</th>
                <th>Fecha</th>
                <th>Total</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($pedidos) > 0): ?>
                <?php foreach ($pedidos as $pedido): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($pedido['id']); ?></td>
                        <td><a href="usuarios_editar.php?id=<?php echo htmlspecialchars($pedido['usuario_id']); ?>"><?php echo htmlspecialchars($pedido['nombre_usuario']); ?></a></td>
                        <td><?php echo htmlspecialchars(date('d/m/Y H:i', strtotime($pedido['fecha_pedido']))); ?></td>
                        <td><?php echo htmlspecialchars(number_format($pedido['total'], 2, ',', '.')) . ' €'; ?></td>
                        <td>
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
                            <span class="badge <?php echo $badge_class; ?>"><?php echo htmlspecialchars(ucfirst($pedido['estado'])); ?></span>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="pedidos_ver.php?id=<?php echo $pedido['id']; ?>" class="btn btn-sm btn-info" title="Ver Detalles"><i class="bi bi-eye"></i></a>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" id="dropdownEstado_<?php echo $pedido['id']; ?>" data-bs-toggle="dropdown" aria-expanded="false" title="Cambiar Estado">
                                        <i class="bi bi-arrow-repeat"></i>
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownEstado_<?php echo $pedido['id']; ?>">
                                        <li><h6 class="dropdown-header">Cambiar a:</h6></li>
                                        <li><a class="dropdown-item" href="pedidos.php?action=change_status&id=<?php echo $pedido['id']; ?>&new_status=pendiente">Pendiente</a></li>
                                        <li><a class="dropdown-item" href="pedidos.php?action=change_status&id=<?php echo $pedido['id']; ?>&new_status=procesando">Procesando</a></li>
                                        <li><a class="dropdown-item" href="pedidos.php?action=change_status&id=<?php echo $pedido['id']; ?>&new_status=enviado">Enviado</a></li>
                                        <li><a class="dropdown-item" href="pedidos.php?action=change_status&id=<?php echo $pedido['id']; ?>&new_status=entregado">Entregado</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item text-danger" href="pedidos.php?action=change_status&id=<?php echo $pedido['id']; ?>&new_status=cancelado">Cancelado</a></li>
                                    </ul>
                                </div>
                                <a href="pedidos.php?action=delete&id=<?php echo $pedido['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro de que quieres eliminar este pedido y todos sus detalles?');" title="Eliminar"><i class="bi bi-trash"></i></a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="text-center">No hay pedidos registrados.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php
require_once '../includes/footer.php';
?>