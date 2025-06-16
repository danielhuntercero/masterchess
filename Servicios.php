<?php
session_start();
require_once 'config/db.php'; // Incluye la configuración de la base de datos

// Consulta para obtener los servicios
try {
    $stmt = $pdo->query("SELECT s.*, u.nombre AS profesor_nombre
                         FROM servicios s
                         LEFT JOIN usuarios u ON s.profesor_id = u.id
                         ORDER BY s.nombre ASC");
    $servicios = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error_servicios = "Error al cargar los servicios: " . $e->getMessage();
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
<header class="banner mt-5 pt-5">
  <div class="container">
    <h1 class="display-4">¡Nuestros Servicios!</h1>
    <p class="lead">Juntos, haremos que cada jugada cuente. ¡Adelante, el próximo movimiento es tuyo!</p>
  </div>
</header>

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

<main class="container-fluid my-5">
  <div class="row g-4">
    <aside class="col-md-2">
      <section class="mb-4" id="categorias-servicios">
        <h2 class="h5 text-center">Categorías</h2>
        <ul class="list-group mb-4">
          <li class="list-group-item">Clases</li>
          <li class="list-group-item">Entrenamiento</li>
          <li class="list-group-item">Análisis de partidas</li>
          <li class="list-group-item">Eventos especiales</li>
        </ul>
        <h2 class="h5 text-center">Novedades</h2>
        <ul class="list-group">
          <li class="list-group-item">Nuevas sesiones de coaching online</li>
          <li class="list-group-item">Simultánea contra Gran Maestro</li>
        </ul>
      </section>
    </aside>

    <div class="col-md-8">
      <section aria-labelledby="servicios-lista">
        <h2 id="servicios-lista" class="text-center mb-4">Listado de Servicios</h2>

        <?php if (isset($error_servicios)): ?>
          <div class="alert alert-danger"><?= htmlspecialchars($error_servicios) ?></div>
        <?php elseif (empty($servicios)): ?>
          <div class="alert alert-info">No hay servicios disponibles en este momento.</div>
        <?php else: ?>
          <div class="row row-cols-1 row-cols-md-3 g-4">
            <?php foreach ($servicios as $servicio): ?>
              <article class="col">
                <div class="card h-100 shadow-sm">
                  <?php if (!empty($servicio['imagen'])): ?>
                    <img src="uploads/servicios/<?= htmlspecialchars($servicio['imagen']) ?>" 
                         class="card-img-top p-3" 
                         alt="<?= htmlspecialchars($servicio['nombre']) ?>"
                         style="height: 200px; object-fit: contain;">
                  <?php else: ?>
                    <img src="img/default-service.png" 
                         class="card-img-top p-3" 
                         alt="Servicio de ajedrez"
                         style="height: 200px; object-fit: contain;">
                  <?php endif; ?>
                  <div class="card-body">
                    <h5 class="card-title fw-bold"><?= htmlspecialchars($servicio['nombre']) ?></h5>
                    <p class="card-text text-muted small"><?= htmlspecialchars($servicio['descripcion']) ?></p>
                    <?php if (!empty($servicio['precio_hora'])): ?>
                      <p class="card-text precio fw-bold text-primary h5 mb-0">
                        <?= number_format($servicio['precio_hora'], 2) ?> €/hora
                      </p>
                    <?php endif; ?>
                    <?php if (!empty($servicio['profesor_nombre'])): ?>
                      <p class="card-text text-muted small">
                        Profesor: <?= htmlspecialchars($servicio['profesor_nombre']) ?>
                      </p>
                    <?php endif; ?>
                    <div class="d-flex justify-content-between align-items-center mt-3">
                      <a href="contacto.php?servicio=<?= urlencode($servicio['nombre']) ?>" 
                         class="btn btn-primary btn-sm">
                        Más información
                      </a>
                    </div>
                  </div>
                </div>
              </article>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </section>
    </div>

    <aside class="col-md-2">
      <div class="sticky-top pt-3">
        <section class="bg-light p-3 rounded shadow-sm mb-3">
          <h2 class="h5 text-center">Consejos rápidos</h2>
          <ul class="list-group mb-3">
            <li class="list-group-item">Practica 15 minutos diarios.</li>
            <li class="list-group-item">Analiza tus derrotas para aprender.</li>
            <li class="list-group-item">No subestimes los finales de partida.</li>
          </ul>
          <h2 class="h5 text-center">Testimonios</h2>
          <blockquote class="blockquote">
            <p class="mb-0">"Las clases personalizadas cambiaron mi forma de ver el ajedrez."</p>
            <footer class="blockquote-footer">Lucía, alumna</footer>
          </blockquote>
        </section>
        
        <div class="card mb-3">
          <div class="card-body text-center">
            <h5 class="card-title">Oferta Especial</h5>
            <p class="card-text">¡Primera clase gratis!</p>
          </div>
        </div>
      </div>
    </aside>
  </div>

  <div class="entrada mb-3 container mt-5">
    <h5>¿Te ha gustado los servicios?</h5>
    <button class="like-btn btn btn-sm btn-outline-primary">
      ❤️ Me gusta (<span class="like-count">0</span>)
    </button>
    <button class="toggle-comments-btn btn btn-sm btn-outline-secondary">
      Mostrar comentarios
    </button>
    <div class="comentarios mt-2 d-none">
      <p>¡Sois geniales!</p>
      <p>Gracias por el apoyo al ajedrez.</p>
    </div>
  </div>

  <form id="form-comentario" class="mt-4 container">
    <label for="nuevo-comentario" class="form-label">Añadir comentario general:</label>
    <input type="text" id="nuevo-comentario" class="form-control mb-2" required />
    <button type="submit" class="btn btn-success btn-sm">Añadir</button>
  </form>

  <ul id="lista-comentarios" class="list-group mt-2 container"></ul>
</main>

<footer class="bg-dark text-white py-4 mt-5">
  <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center">
    <div class="mb-3 mb-md-0">
      &copy; 2025 Web Corporativa - Masterchess
    </div>
    <div>
      <a href="aviso-legal.php" class="text-white text-decoration-none me-3">Aviso legal</a>
      <a href="contacto.php" class="text-white text-decoration-none me-3">Contacto</a>
      <a href="politica-privacidad.php" class="text-white text-decoration-none">Política de privacidad</a>
    </div>
  </div>
</footer>

  <!-- Bootstrap 5 JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <button class="btn btn-warning position-fixed bottom-0 end-0 m-4 rounded-circle" id="btn-top" title="Subir arriba">
    <i class="bi bi-arrow-up"></i>
  </button>

  <script src="js/script.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.