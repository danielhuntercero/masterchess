<?php
// admin/usuarios.php
require_once '../includes/header.php'; // Incluye el header y la verificación de admin
require_once '../config/db.php';     // Conexión a la base de datos

$mensaje = "";
$tipo_mensaje = "";

// Lógica para eliminar un usuario
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $usuario_id = filter_var($_GET['id'], FILTER_VALIDATE_INT);

    if ($usuario_id === false) {
        $mensaje = "ID de usuario no válido.";
        $tipo_mensaje = "danger";
    } else {
        try {
            // 1. Evitar que un administrador se elimine a sí mismo
            if ($_SESSION['usuario_id'] == $usuario_id) {
                $mensaje = "No puedes eliminar tu propia cuenta mientras estás logueado.";
                $tipo_mensaje = "danger";
            } else {
                // VERIFICACIONES IMPORTANTES ANTES DE ELIMINAR:
                // a) Verificar si el usuario tiene servicios asignados como profesor
                $stmt_check_servicios = $pdo->prepare("SELECT COUNT(*) FROM servicios WHERE profesor_id = ?");
                $stmt_check_servicios->execute([$usuario_id]);
                $servicios_asociados = $stmt_check_servicios->fetchColumn();

                // b) Verificar si el usuario tiene inscripciones
                $stmt_check_inscripciones = $pdo->prepare("SELECT COUNT(*) FROM inscripciones WHERE usuario_id = ?");
                $stmt_check_inscripciones->execute([$usuario_id]);
                $inscripciones_asociadas = $stmt_check_inscripciones->fetchColumn();

                // c) Verificar si el usuario tiene pedidos
                $stmt_check_pedidos = $pdo->prepare("SELECT COUNT(*) FROM pedidos WHERE usuario_id = ?");
                $stmt_check_pedidos->execute([$usuario_id]);
                $pedidos_asociados = $stmt_check_pedidos->fetchColumn();

                // d) Verificar si el usuario tiene carritos
                $stmt_check_carritos = $pdo->prepare("SELECT COUNT(*) FROM carritos WHERE usuario_id = ?");
                $stmt_check_carritos->execute([$usuario_id]);
                $carritos_asociados = $stmt_check_carritos->fetchColumn();

                // e) Verificar si el usuario tiene valoraciones
                $stmt_check_valoraciones = $pdo->prepare("SELECT COUNT(*) FROM valoraciones WHERE usuario_id = ?");
                $stmt_check_valoraciones->execute([$usuario_id]);
                $valoraciones_asociadas = $stmt_check_valoraciones->fetchColumn();


                if ($servicios_asociados > 0) {
                    $mensaje = "No se puede eliminar el usuario porque tiene " . $servicios_asociados . " servicio(s) asignado(s) como profesor. Por favor, reasigna o elimina esos servicios primero.";
                    $tipo_mensaje = "danger";
                } elseif ($inscripciones_asociadas > 0) {
                    $mensaje = "No se puede eliminar el usuario porque tiene " . $inscripciones_asociadas . " inscripción(es) asociada(s). Por favor, elimina esas inscripciones primero.";
                    $tipo_mensaje = "danger";
                } elseif ($pedidos_asociados > 0) {
                    $mensaje = "No se puede eliminar el usuario porque tiene " . $pedidos_asociados . " pedido(s) asociado(s). Por favor, elimina esos pedidos (y sus detalles) primero.";
                    $tipo_mensaje = "danger";
                } elseif ($carritos_asociados > 0) {
                    // Si un carrito tiene items_carrito, también deben ser eliminados primero
                    // O se puede eliminar el carrito y sus items en cascada si la FK lo permite.
                    // Para simplificar aquí, solo avisamos.
                    $mensaje = "No se puede eliminar el usuario porque tiene un carrito de compras asociado. Por favor, vacía o elimina el carrito primero.";
                    $tipo_mensaje = "danger";
                } elseif ($valoraciones_asociadas > 0) {
                     $mensaje = "No se puede eliminar el usuario porque tiene " . $valoraciones_asociadas . " valoración(es) asociada(s). Por favor, elimina esas valoraciones primero.";
                     $tipo_mensaje = "danger";
                }
                else {
                    // Si no hay dependencias, proceder con la eliminación
                    $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = ?");
                    $stmt->execute([$usuario_id]);
                    $mensaje = "Usuario eliminado correctamente.";
                    $tipo_mensaje = "success";
                }
            }
        } catch (PDOException $e) {
            $mensaje = "Error al eliminar el usuario: " . $e->getMessage();
            $tipo_mensaje = "danger";
        }
    }
    header("Location: usuarios.php?mensaje=" . urlencode($mensaje) . "&tipo=" . urlencode($tipo_mensaje));
    exit();
}

// Obtener todos los usuarios
$usuarios = [];
try {
    $stmt = $pdo->query("SELECT id, nombre, email, rol, telefono, nivel_ajedrez, fecha_registro, fecha_ultimo_login FROM usuarios ORDER BY fecha_registro DESC");
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $mensaje = "Error al cargar los usuarios: " . $e->getMessage();
    $tipo_mensaje = "danger";
}
?>

<div class="card mb-4 shadow-sm">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <h2 class="mb-0"><i class="bi bi-people-fill me-2"></i>Gestión de Usuarios</h2>
        <a href="usuarios_crear.php" class="btn btn-light btn-sm"><i class="bi bi-plus-circle me-2"></i>Añadir Nuevo Usuario</a>
    </div>
    <div class="card-body">
        <?php
        // El mensaje global ya se muestra por header.php si viene de una redirección
        if (!empty($mensaje) && !isset($_GET['mensaje'])) { // Solo mostrar aquí si no viene de una redirección
            echo '<div class="alert alert-' . $tipo_mensaje . ' alert-dismissible fade show" role="alert">' . htmlspecialchars($mensaje) . '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
        }
        ?>
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Rol</th>
                        <th>Teléfono</th>
                        <th>Nivel Ajedrez</th>
                        <th>Fecha Registro</th>
                        <th>Último Login</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($usuarios) > 0): ?>
                        <?php foreach ($usuarios as $usuario): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($usuario['id']); ?></td>
                                <td><?php echo htmlspecialchars($usuario['nombre']); ?></td>
                                <td><?php echo htmlspecialchars($usuario['email']); ?></td>
                                <td><span class="badge <?php
                                    if ($usuario['rol'] == 'admin') echo 'bg-danger';
                                    elseif ($usuario['rol'] == 'profesor') echo 'bg-info text-dark';
                                    else echo 'bg-secondary';
                                ?>"><?php echo htmlspecialchars(ucfirst($usuario['rol'])); ?></span></td>
                                <td><?php echo htmlspecialchars($usuario['telefono'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($usuario['nivel_ajedrez'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($usuario['fecha_registro']); ?></td>
                                <td><?php echo htmlspecialchars($usuario['fecha_ultimo_login'] ?? 'Nunca'); ?></td>
                                <td>
                                    <a href="usuarios_editar.php?id=<?php echo $usuario['id']; ?>" class="btn btn-sm btn-primary me-2" title="Editar"><i class="bi bi-pencil"></i></a>
                                    <?php if ($usuario['id'] != $_SESSION['usuario_id']): // No permitir eliminar el propio usuario logueado ?>
                                        <a href="usuarios.php?action=delete&id=<?php echo $usuario['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro de que quieres eliminar este usuario? Esto puede afectar a registros relacionados como pedidos, inscripciones, etc. y se recomienda verificar las dependencias antes de eliminar.');" title="Eliminar"><i class="bi bi-trash"></i></a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="9" class="text-center">No hay usuarios registrados.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
require_once '../includes/footer.php'; // Incluye el footer
?>