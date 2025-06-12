<?php
// admin/usuarios_crear.php
require_once '../includes/header.php'; // Incluye el header y la verificación de admin
require_once '../config/db.php';     // Conexión a la base de datos

// Solo los administradores pueden crear usuarios desde aquí
if ($_SESSION['usuario_rol'] !== 'admin') {
    header("Location: index.php?mensaje=" . urlencode("Acceso denegado. No tienes permisos para crear usuarios.") . "&tipo=" . urlencode("danger"));
    exit();
}

$mensaje = "";
$tipo_mensaje = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $rol = $_POST['rol'] ?? 'cliente'; // Por defecto 'cliente' si no se envía
    $telefono = trim($_POST['telefono'] ?? '');
    $nivel_ajedrez = $_POST['nivel_ajedrez'] ?? null;

    // Validaciones
    if (empty($nombre) || empty($email) || empty($password)) {
        $mensaje = "Nombre, Email y Contraseña son campos obligatorios.";
        $tipo_mensaje = "danger";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $mensaje = "El formato del email no es válido.";
        $tipo_mensaje = "danger";
    } elseif (strlen($password) < 6) { // Ejemplo: mínimo 6 caracteres para la contraseña
        $mensaje = "La contraseña debe tener al menos 6 caracteres.";
        $tipo_mensaje = "danger";
    } else {
        try {
            // Verificar si el email ya existe
            $stmt_check_email = $pdo->prepare("SELECT COUNT(*) FROM usuarios WHERE email = ?");
            $stmt_check_email->execute([$email]);
            if ($stmt_check_email->fetchColumn() > 0) {
                $mensaje = "El email ya está registrado. Por favor, utiliza otro.";
                $tipo_mensaje = "danger";
            } else {
                // Hashear la contraseña antes de guardarla
                $password_hashed = password_hash($password, PASSWORD_DEFAULT);

                // Insertar el nuevo usuario en la base de datos
                $stmt = $pdo->prepare(
                    "INSERT INTO usuarios (nombre, email, password, rol, telefono, nivel_ajedrez, fecha_registro)
                     VALUES (?, ?, ?, ?, ?, ?, NOW())" // NOW() para la fecha de registro actual
                );
                $stmt->execute([$nombre, $email, $password_hashed, $rol, $telefono, $nivel_ajedrez]);

                $mensaje = "Usuario creado correctamente.";
                $tipo_mensaje = "success";

                // Redirigir al listado de usuarios después de crear
                header("Location: usuarios.php?mensaje=" . urlencode($mensaje) . "&tipo=" . urlencode($tipo_mensaje));
                exit();
            }
        } catch (PDOException $e) {
            $mensaje = "Error al crear el usuario: " . $e->getMessage();
            $tipo_mensaje = "danger";
        }
    }
}
?>

<h1 class="mb-4"><i class="bi bi-person-plus-fill me-3"></i>Crear Nuevo Usuario</h1>

<?php
// Mostrar el mensaje si existe (tanto si viene de la redirección como si es por error en el POST)
if (!empty($mensaje) && !isset($_GET['mensaje'])) { // El !isset($_GET['mensaje']) evita duplicar el mensaje si ya viene por URL
    echo '<div class="alert alert-' . $tipo_mensaje . ' alert-dismissible fade show" role="alert">' . htmlspecialchars($mensaje) . '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
} else if (isset($_GET['mensaje'])) { // Si el mensaje viene de una redirección
    echo '<div class="alert alert-' . htmlspecialchars($_GET['tipo']) . ' alert-dismissible fade show" role="alert">' . htmlspecialchars($_GET['mensaje']) . '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
}
?>

<form action="usuarios_crear.php" method="POST">
    <div class="mb-3">
        <label for="nombre" class="form-label">Nombre:</label>
        <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo htmlspecialchars($_POST['nombre'] ?? ''); ?>" required>
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">Email:</label>
        <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required>
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Contraseña:</label>
        <input type="password" class="form-control" id="password" name="password" required minlength="6">
        <small class="form-text text-muted">Mínimo 6 caracteres.</small>
    </div>
    <div class="mb-3">
        <label for="rol" class="form-label">Rol:</label>
        <select class="form-select" id="rol" name="rol" required>
            <option value="cliente" <?php echo (($_POST['rol'] ?? '') == 'cliente') ? 'selected' : ''; ?>>Cliente</option>
            <option value="profesor" <?php echo (($_POST['rol'] ?? '') == 'profesor') ? 'selected' : ''; ?>>Profesor</option>
            <option value="admin" <?php echo (($_POST['rol'] ?? '') == 'admin') ? 'selected' : ''; ?>>Administrador</option>
        </select>
    </div>
    <div class="mb-3">
        <label for="telefono" class="form-label">Teléfono (opcional):</label>
        <input type="text" class="form-control" id="telefono" name="telefono" value="<?php echo htmlspecialchars($_POST['telefono'] ?? ''); ?>">
    </div>
    <div class="mb-3">
        <label for="nivel_ajedrez" class="form-label">Nivel de Ajedrez (opcional):</label>
        <select class="form-select" id="nivel_ajedrez" name="nivel_ajedrez">
            <option value="">Selecciona un nivel</option>
            <option value="principiante" <?php echo (($_POST['nivel_ajedrez'] ?? '') == 'principiante') ? 'selected' : ''; ?>>Principiante</option>
            <option value="intermedio" <?php echo (($_POST['nivel_ajedrez'] ?? '') == 'intermedio') ? 'selected' : ''; ?>>Intermedio</option>
            <option value="avanzado" <?php echo (($_POST['nivel_ajedrez'] ?? '') == 'avanzado') ? 'selected' : ''; ?>>Avanzado</option>
            <option value="experto" <?php echo (($_POST['nivel_ajedrez'] ?? '') == 'experto') ? 'selected' : ''; ?>>Experto</option>
        </select>
    </div>
    <button type="submit" class="btn btn-primary"><i class="bi bi-person-plus me-2"></i>Crear Usuario</button>
    <a href="usuarios.php" class="btn btn-secondary"><i class="bi bi-x-circle me-2"></i>Cancelar</a>
</form>

<?php
require_once '../includes/footer.php';
?>