<?php
// admin/usuarios_editar.php
require_once '../includes/header.php'; // Incluye el header y la verificación de admin
require_once '../config/db.php';     // Conexión a la base de datos

$mensaje = "";
$tipo_mensaje = "";
$usuario_a_editar = null;

// Obtener ID del usuario desde la URL
$usuario_id = filter_var($_GET['id'] ?? '', FILTER_VALIDATE_INT);

if ($usuario_id === false) {
    $mensaje = "ID de usuario no válido.";
    $tipo_mensaje = "danger";
} else {
    // Si se envió el formulario de edición
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nombre = trim($_POST['nombre'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $rol = $_POST['rol'] ?? 'cliente';
        $telefono = trim($_POST['telefono'] ?? '');
        $nivel_ajedrez = $_POST['nivel_ajedrez'] ?? null;
        $password_nueva = $_POST['password_nueva'] ?? ''; // La nueva contraseña, si se cambia

        // Validaciones
        if (empty($nombre) || empty($email)) {
            $mensaje = "Nombre y Email son campos obligatorios.";
            $tipo_mensaje = "danger";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $mensaje = "El formato del email no es válido.";
            $tipo_mensaje = "danger";
        } elseif (!empty($password_nueva) && strlen($password_nueva) < 6) {
            $mensaje = "La nueva contraseña debe tener al menos 6 caracteres.";
            $tipo_mensaje = "danger";
        } else {
            try {
                // Verificar si el email ya existe para otro usuario
                $stmt_check_email = $pdo->prepare("SELECT COUNT(*) FROM usuarios WHERE email = ? AND id != ?");
                $stmt_check_email->execute([$email, $usuario_id]);
                if ($stmt_check_email->fetchColumn() > 0) {
                    $mensaje = "El email ya está registrado para otro usuario.";
                    $tipo_mensaje = "danger";
                } else {
                    $sql = "UPDATE usuarios SET nombre = ?, email = ?, rol = ?, telefono = ?, nivel_ajedrez = ? ";
                    $params = [$nombre, $email, $rol, $telefono, $nivel_ajedrez];

                    if (!empty($password_nueva)) {
                        $hashed_password = password_hash($password_nueva, PASSWORD_DEFAULT);
                        $sql .= ", password = ? ";
                        $params[] = $hashed_password;
                    }

                    $sql .= "WHERE id = ?";
                    $params[] = $usuario_id;

                    $stmt = $pdo->prepare($sql);
                    $stmt->execute($params);

                    $mensaje = "Usuario actualizado correctamente.";
                    $tipo_mensaje = "success";
                    // Redirigir a la lista de usuarios para mostrar el mensaje
                    header("Location: usuarios.php?mensaje=" . urlencode($mensaje) . "&tipo=" . urlencode($tipo_mensaje));
                    exit();
                }
            } catch (PDOException $e) {
                $mensaje = "Error al actualizar el usuario: " . $e->getMessage();
                $tipo_mensaje = "danger";
            }
        }
    }

    // Cargar datos del usuario existente para mostrar en el formulario
    // Esto se hace *después* del procesamiento POST para que los valores mostrados reflejen los POST si hay un error
    try {
        $stmt = $pdo->prepare("SELECT id, nombre, email, rol, telefono, nivel_ajedrez FROM usuarios WHERE id = ?");
        $stmt->execute([$usuario_id]);
        $usuario_a_editar = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$usuario_a_editar) {
            $mensaje = "Usuario no encontrado.";
            $tipo_mensaje = "danger";
        }
    } catch (PDOException $e) {
        $mensaje = "Error al cargar los datos del usuario: " . $e->getMessage();
        $tipo_mensaje = "danger";
    }
}
?>

<h1 class="mb-4"><i class="bi bi-person-circle me-3"></i>Editar Usuario</h1>

<?php if ($usuario_a_editar): ?>
    <form action="usuarios_editar.php?id=<?php echo htmlspecialchars($usuario_id); ?>" method="POST">
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre Completo:</label>
            <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo htmlspecialchars($_POST['nombre'] ?? $usuario_a_editar['nombre']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email:</label>
            <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($_POST['email'] ?? $usuario_a_editar['email']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="password_nueva" class="form-label">Nueva Contraseña (dejar en blanco para no cambiar):</label>
            <input type="password" class="form-control" id="password_nueva" name="password_nueva">
            <div class="form-text">Mínimo 6 caracteres si se cambia.</div>
        </div>
        <div class="mb-3">
            <label for="rol" class="form-label">Rol:</label>
            <select class="form-select" id="rol" name="rol" required>
                <option value="cliente" <?php echo ((isset($_POST['rol']) && $_POST['rol'] == 'cliente') || (!isset($_POST['rol']) && $usuario_a_editar['rol'] == 'cliente')) ? 'selected' : ''; ?>>Cliente</option>
                <option value="profesor" <?php echo ((isset($_POST['rol']) && $_POST['rol'] == 'profesor') || (!isset($_POST['rol']) && $usuario_a_editar['rol'] == 'profesor')) ? 'selected' : ''; ?>>Profesor</option>
                <option value="admin" <?php echo ((isset($_POST['rol']) && $_POST['rol'] == 'admin') || (!isset($_POST['rol']) && $usuario_a_editar['rol'] == 'admin')) ? 'selected' : ''; ?>>Administrador</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="telefono" class="form-label">Teléfono (opcional):</label>
            <input type="text" class="form-control" id="telefono" name="telefono" value="<?php echo htmlspecialchars($_POST['telefono'] ?? $usuario_a_editar['telefono']); ?>">
        </div>
        <div class="mb-3">
            <label for="nivel_ajedrez" class="form-label">Nivel de Ajedrez (opcional):</label>
            <select class="form-select" id="nivel_ajedrez" name="nivel_ajedrez">
                <option value="">Selecciona un nivel</option>
                <option value="principiante" <?php echo ((isset($_POST['nivel_ajedrez']) && $_POST['nivel_ajedrez'] == 'principiante') || (!isset($_POST['nivel_ajedrez']) && $usuario_a_editar['nivel_ajedrez'] == 'principiante')) ? 'selected' : ''; ?>>Principiante</option>
                <option value="intermedio" <?php echo ((isset($_POST['nivel_ajedrez']) && $_POST['nivel_ajedrez'] == 'intermedio') || (!isset($_POST['nivel_ajedrez']) && $usuario_a_editar['nivel_ajedrez'] == 'intermedio')) ? 'selected' : ''; ?>>Intermedio</option>
                <option value="avanzado" <?php echo ((isset($_POST['nivel_ajedrez']) && $_POST['nivel_ajedrez'] == 'avanzado') || (!isset($_POST['nivel_ajedrez']) && $usuario_a_editar['nivel_ajedrez'] == 'avanzado')) ? 'selected' : ''; ?>>Avanzado</option>
                <option value="experto" <?php echo ((isset($_POST['nivel_ajedrez']) && $_POST['nivel_ajedrez'] == 'experto') || (!isset($_POST['nivel_ajedrez']) && $usuario_a_editar['nivel_ajedrez'] == 'experto')) ? 'selected' : ''; ?>>Experto</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary"><i class="bi bi-save me-2"></i>Actualizar Usuario</button>
        <a href="usuarios.php" class="btn btn-secondary"><i class="bi bi-x-circle me-2"></i>Cancelar</a>
    </form>
<?php else: ?>
    <p class="alert alert-warning">No se pudo cargar el usuario para editar o el ID no es válido.</p>
<?php endif; ?>

<?php
require_once '../includes/footer.php';
?>