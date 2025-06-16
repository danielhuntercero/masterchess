<?php
session_start();
require_once 'config/db.php';

// Verificar sesión
if (!isset($_SESSION['usuario_id'])) {
    header('Location: index.php');
    exit();
}

// Obtener ID de usuario
$usuario_id = $_SESSION['usuario_id'];

// Manejar mensajes
$success = $_GET['success'] ?? '';
$error = $_GET['error'] ?? '';

// Obtener datos del usuario
try {
    $query = "SELECT id, nombre, email, rol, telefono, nivel_ajedrez, fecha_registro 
              FROM usuarios WHERE id = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$usuario_id]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$usuario) {
        header('Location: index.php?error=Usuario no encontrado');
        exit();
    }
} catch (PDOException $e) {
    die("Error al obtener datos del usuario: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Masterchess - Inicio</title>
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;700&display=swap" rel="stylesheet">
  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
   <!-- En la sección <head> -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="css/estilos.css">
  <style>
    .navbar-nav .nav-link {
      white-space: nowrap;
      padding: 0.5rem 0.8rem;
      font-size: 0.95rem;
    }
    .navbar-brand, .navbar-nav .nav-item {
      margin-right: 0.3rem;
    }
    @media (max-width: 992px) {
      .navbar-nav .nav-link {
        white-space: normal;
        padding: 0.5rem 1rem;
      }
    }
  </style>
</head>

<body>

  <!-- Banner -->
  <header class="banner mt-5 pt-5">
    <div class="container">
      <h1 class="display-4">¡Bienvenido a Masterchess!</h1>
      <p class="lead">Juntos, haremos que cada jugada cuente. ¡Adelante, el próximo movimiento es tuyo!</p>
    </div>
  </header>
<body>
    <!-- NAVBAR -->
     <nav class="navbar navbar-expand-lg navbar-custom fixed-top w-100">
    <div class="container">
      <a class="navbar-brand" href="index.php">&#9812;Inicio</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span></button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link" href="servicios.php">♖ Servicios</a></li>
          <li class="nav-item"><a class="nav-link" href="productos.php"><i class="bi bi-shop me-1"></i>Productos</a></li>
          <li class="nav-item"><a class="nav-link" href="torneos.php"><i class="bi bi-trophy me-1"></i>Torneos</a></li>
          <?php if(isset($_SESSION['usuario_id'])): ?>
            <li class="nav-item"><a class="nav-link" href="perfil.php"><i class="bi bi-person-circle me-1"></i>Mi Perfil</a></li>
            <?php if(isset($_SESSION['usuario_rol']) && $_SESSION['usuario_rol'] === 'admin'): ?>
              <li class="nav-item"><a class="nav-link" href="admin/index.php"><i class="bi bi-shield-lock me-1"></i>Admin</a></li>
            <?php endif; ?>
            <li class="nav-item"><a class="nav-link" href="logout.php"><i class="bi bi-box-arrow-right me-1"></i>Cerrar Sesión</a></li>
          <?php else: ?>
            <li class="nav-item"><a class="nav-link" href="#login-section"><i class="bi bi-box-arrow-in-right me-1"></i>Iniciar sesión</a></li>
          <?php endif; ?>
          <li class="nav-item"><a class="nav-link" href="contacto.php"><i class="bi bi-envelope me-1"></i>Contacto</a></li>
          <li class="nav-item"><a class="nav-link" href="sobre-nosotros.php"><i class="bi bi-info-circle me-1"></i>Sobre nosotros</a></li>
        </ul>
        <!-- Buscador siempre visible -->
        <form class="d-flex" role="search">
          <input class="form-control me-2" type="search" placeholder="Buscar" aria-label="Buscar">
          <button class="btn btn-light" type="submit"><i class="bi bi-search"></i></button>
        </form>
      </div>
    </div>
  </nav>

    <main class="container my-5">
        <!-- Mostrar mensajes -->
        <?php if ($success): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($success); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php endif; ?>

        <?php if ($error): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($error); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php endif; ?>

    

            <!-- Columna derecha - Configuración -->
            <div class="col-md-8">
                <section class="card shadow">
                    <div class="card-header bg-dark text-white">
                        <h3 class="h5 mb-0">⚙️ Configuración de Cuenta</h3>
                    </div>
                    <div class="card-body">
                        <form action="actualizar_perfil.php" method="POST">
                            <div class="mb-3">
                                <label class="form-label">Nombre</label>
                                <input type="text" class="form-control" value="<?php echo htmlspecialchars($usuario['nombre']); ?>" disabled>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($usuario['email']); ?>" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Teléfono</label>
                                <input type="text" class="form-control" name="telefono" value="<?php echo htmlspecialchars($usuario['telefono'] ?? ''); ?>">
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Nivel de Ajedrez</label>
                                <select class="form-select" name="nivel_ajedrez">
                                    <option value="principiante" <?= $usuario['nivel_ajedrez'] === 'principiante' ? 'selected' : '' ?>>Principiante</option>
                                    <option value="intermedio" <?= $usuario['nivel_ajedrez'] === 'intermedio' ? 'selected' : '' ?>>Intermedio</option>
                                    <option value="avanzado" <?= $usuario['nivel_ajedrez'] === 'avanzado' ? 'selected' : '' ?>>Avanzado</option>
                                    <option value="experto" <?= $usuario['nivel_ajedrez'] === 'experto' ? 'selected' : '' ?>>Experto</option>
                                </select>
                            </div>
                            
                            <hr>
                            
                            <h5 class="mb-3">Cambiar contraseña</h5>
                            
                            <div class="mb-3">
                                <label class="form-label">Nueva Contraseña (dejar en blanco para no cambiar)</label>
                                <input type="password" class="form-control" name="nueva_password" placeholder="Mínimo 8 caracteres">
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Confirmar Nueva Contraseña</label>
                                <input type="password" class="form-control" name="confirmar_password">
                            </div>
                            
                            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                        </form>
                    </div>
                </section>
            </div>
        </div>
    </main>

    <footer class="bg-dark text-white py-4 mt-5">
    <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center">
      <div class="mb-3 mb-md-0">
        &copy; 2025 Web Corporativa - Masterchess
      </div>
      <div>
        <a class="text-white text-decoration-none me-3" href="aviso-legal.php">
          <i class="bi bi-shield-lock me-1"></i>Aviso legal
        </a>
        <a class="text-white text-decoration-none me-3" href="contacto.php">
          <i class="bi bi-envelope me-1"></i>Contacto
        </a>
        <a class="text-white text-decoration-none" href="politica-privacidad.php">
          <i class="bi bi-lock me-1"></i>Política de privacidad
        </a>
      </div>
    </div>
  </footer>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Validación básica del formulario
        document.querySelector('form').addEventListener('submit', function(e) {
            const password = document.querySelector('input[name="nueva_password"]').value;
            const confirmPassword = document.querySelector('input[name="confirmar_password"]').value;
            
            if (password && password.length < 8) {
                alert('La contraseña debe tener al menos 8 caracteres');
                e.preventDefault();
                return false;
            }
            
            if (password !== confirmPassword) {
                alert('Las contraseñas no coinciden');
                e.preventDefault();
                return false;
            }
            
            return true;
        });
    </script>
</body>
</html>

  <!-- Bootstrap 5 JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <button class="btn btn-warning position-fixed bottom-0 end-0 m-4 rounded-circle" id="btn-top" title="Subir arriba">
    <i class="bi bi-arrow-up"></i>
  </button>

  <script src="js/script.js"></script>