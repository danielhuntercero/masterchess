<?php
// admin/index.php
require_once '../includes/header.php'; // Incluye el encabezado y la verificación de sesión
require_once '../config/db.php';     // Conexión a la base de datos

// Inicializar contadores
$total_usuarios = 0;
$total_productos = 0;
$total_pedidos = 0;
$total_servicios = 0;
$total_torneos = 0;
$total_mensajes = 0; // Si tienes una tabla de mensajes de contacto

try {
    // Obtener el total de usuarios
    if ($_SESSION['usuario_rol'] === 'admin') { // Solo el admin puede ver el total de usuarios
        $stmt_usuarios = $pdo->query("SELECT COUNT(*) AS total FROM usuarios");
        $total_usuarios = $stmt_usuarios->fetchColumn();
    }

    // Obtener el total de productos (accesible por admin)
    if ($_SESSION['usuario_rol'] === 'admin') {
        $stmt_productos = $pdo->query("SELECT COUNT(*) AS total FROM productos");
        $total_productos = $stmt_productos->fetchColumn();
    }

    // Obtener el total de pedidos (accesible por admin)
    if ($_SESSION['usuario_rol'] === 'admin') {
        $stmt_pedidos = $pdo->query("SELECT COUNT(*) AS total FROM pedidos");
        $total_pedidos = $stmt_pedidos->fetchColumn();
    }
    
    // Obtener el total de servicios (accesible por admin y profesor)
    if ($_SESSION['usuario_rol'] === 'admin' || $_SESSION['usuario_rol'] === 'profesor') {
        $stmt_servicios = $pdo->query("SELECT COUNT(*) AS total FROM servicios");
        $total_servicios = $stmt_servicios->fetchColumn();
    }

    // Obtener el total de torneos (accesible por admin y profesor)
    if ($_SESSION['usuario_rol'] === 'admin' || $_SESSION['usuario_rol'] === 'profesor') {
        $stmt_torneos = $pdo->query("SELECT COUNT(*) AS total FROM torneos");
        $total_torneos = $stmt_torneos->fetchColumn();
    }

    // Obtener el total de mensajes (accesible solo por admin)
    if ($_SESSION['usuario_rol'] === 'admin') {
        $stmt_mensajes = $pdo->query("SELECT COUNT(*) AS total FROM mensajes_contacto"); // Asegúrate de que el nombre de la tabla sea correcto
        $total_mensajes = $stmt_mensajes->fetchColumn();
    }

} catch (PDOException $e) {
    // Manejar el error de la base de datos si es necesario
    $mensaje = "Error al cargar las estadísticas: " . $e->getMessage();
    $tipo_mensaje = "danger";
}
?>

<h1 class="display-4 mb-4">Bienvenido al Panel de Administración</h1>
<p class="lead">Desde aquí puedes gestionar todos los aspectos de tu plataforma MasterChess.</p>
<hr class="my-4">

<?php
// Mostrar el mensaje si existe
if (!empty($mensaje)) {
    echo '<div class="alert alert-' . $tipo_mensaje . ' alert-dismissible fade show" role="alert">' . htmlspecialchars($mensaje) . '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
}
?>

<div class="row">
    <?php if ($_SESSION['usuario_rol'] === 'admin'): ?>
        <div class="col-md-4 mb-4">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title"><i class="bi bi-people-fill me-2"></i>Total Usuarios</h5>
                            <p class="card-text fs-2"><?php echo $total_usuarios; ?></p>
                        </div>
                        <i class="bi bi-person-circle fs-1"></i>
                    </div>
                    <a href="usuarios.php" class="text-white stretched-link text-decoration-none">Ver Detalles</a>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title"><i class="bi bi-box-seam-fill me-2"></i>Total Productos</h5>
                            <p class="card-text fs-2"><?php echo $total_productos; ?></p>
                        </div>
                        <i class="bi bi-box-fill fs-1"></i>
                    </div>
                    <a href="productos.php" class="text-white stretched-link text-decoration-none">Ver Detalles</a>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card text-white bg-info">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title"><i class="bi bi-cart-fill me-2"></i>Total Pedidos</h5>
                            <p class="card-text fs-2"><?php echo $total_pedidos; ?></p>
                        </div>
                        <i class="bi bi-receipt fs-1"></i>
                    </div>
                    <a href="pedidos.php" class="text-white stretched-link text-decoration-none">Ver Detalles</a>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card text-white bg-secondary">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title"><i class="bi bi-envelope-fill me-2"></i>Mensajes de Contacto</h5>
                            <p class="card-text fs-2"><?php echo $total_mensajes; ?></p>
                        </div>
                        <i class="bi bi-chat-dots-fill fs-1"></i>
                    </div>
                    <a href="mensajes_contacto.php" class="text-white stretched-link text-decoration-none">Ver Mensajes</a>
                </div>
            </div>
        </div>
    <?php endif; // Fin del bloque solo para admin ?>

    <?php if ($_SESSION['usuario_rol'] === 'admin' || $_SESSION['usuario_rol'] === 'profesor'): ?>
        <div class="col-md-4 mb-4">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title"><i class="bi bi-bookmark-star-fill me-2"></i>Total Servicios</h5>
                            <p class="card-text fs-2"><?php echo $total_servicios; ?></p>
                        </div>
                        <i class="bi bi-book-fill fs-1"></i>
                    </div>
                    <a href="servicios.php" class="text-white stretched-link text-decoration-none">Ver Servicios</a>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card text-white bg-danger">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title"><i class="bi bi-trophy-fill me-2"></i>Total Torneos</h5>
                            <p class="card-text fs-2"><?php echo $total_torneos; ?></p>
                        </div>
                        <i class="bi bi-globe-americas fs-1"></i>
                    </div>
                    <a href="torneos.php" class="text-white stretched-link text-decoration-none">Ver Torneos</a>
                </div>
            </div>
        </div>
    <?php endif; // Fin del bloque para admin y profesor ?>

</div>

<?php
require_once '../includes/footer.php'; // Incluye el pie de página
?>