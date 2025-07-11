<?php
session_start();
require_once 'config/db.php';
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
      <h1 class="display-4">¡Un poco sobre Nosotros!</h1>
      <p class="lead">Juntos, haremos que cada jugada cuente. ¡Adelante, el próximo movimiento es tuyo!</p>
    </div>
  </header>

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

  <!-- MAIN: Grid con asides e historia -->
  <main class="container my-5">
    <div class="row">
      <!-- ASIDE IZQUIERDO: Valores, premios, visión -->
      <aside class="col-md-3 mb-4" aria-labelledby="valores-premios-vision">
        <section class="mb-4" id="valores-premios-vision">
          <h2 class="h5 text-center">Nuestros valores</h2>
          <ul class="list-group mb-4">
            <li class="list-group-item">Pasión por el ajedrez</li>
            <li class="list-group-item">Compromiso educativo</li>
            <li class="list-group-item">Inclusión y diversidad</li>
          </ul>
          <h2 class="h5 text-center">Premios y reconocimientos</h2>
          <ul class="list-group mb-4">
            <li class="list-group-item">Premio Club Joven 2023</li>
            <li class="list-group-item">Reconocimiento FIDE Innovación</li>
          </ul>
          <h2 class="h5 text-center">Nuestra visión</h2>
          <p class="small text-center">
            Democratizar el ajedrez y acercarlo a todas las edades y niveles.
          </p>
        </section>
      </aside>

      <!-- CENTRO: Historia, misión, filosofía -->
      <div class="col-md-6">
        <section aria-labelledby="historia-mision-filosofia">
          <h2 id="historia-mision-filosofia" class="text-center mb-4">Quiénes somos</h2>
          <article class="mb-4 p-4 bg-light rounded shadow-sm">
            <h3>Historia</h3>
            <p>Masterchess nace en 2015 como la iniciativa de un grupo de amigos apasionados por el ajedrez que buscaban
              compartir su amor por este juego milenario. Con el tiempo, el proyecto ha crecido hasta convertirse en una
              comunidad abierta a todos los niveles.</p>
          </article>
          <article class="mb-4 p-4 bg-white rounded shadow-sm">
            <h3>Misión</h3>
            <p>Nuestra misión es fomentar el pensamiento estratégico y el desarrollo personal a través del ajedrez,
              ofreciendo recursos de calidad para jugadores de todas las edades.</p>
          </article>
          <article class="mb-4 p-4 bg-light rounded shadow-sm">
            <h3>Filosofía</h3>
            <p>Creemos en la educación, el respeto y la mejora continua como motores fundamentales para el crecimiento
              personal y colectivo, tanto en el tablero como fuera de él.</p>
          </article>
        </section>
      </div>

      <!-- ASIDE DERECHO: Equipo, contacto rápido, testimonios -->
      <aside class="col-md-3 mb-4" aria-labelledby="equipo-contacto-testimonios">
        <section class="mb-4" id="equipo-contacto-testimonios">
          <h2 class="h5 text-center">Nuestro equipo</h2>
          <ul class="list-group mb-4">
            <li class="list-group-item">Sergio P. - Fundador</li>
            <li class="list-group-item">Lucía G. - Coach principal</li>
            <li class="list-group-item">Antonio R. - Torneos y eventos</li>
          </ul>
          <h2 class="h5 text-center">Contacto rápido</h2>
          <ul class="list-group mb-4">
            <li class="list-group-item">Email: info.masterchess@gmail.com</li>
            <li class="list-group-item">Teléfono: +34 123 456 789</li>
          </ul>
          <h2 class="h5 text-center">Testimonios</h2>
          <blockquote class="blockquote">
            <p class="mb-0">“Gracias a Masterchess descubrí un nuevo mundo y grandes amigos.”</p>
            <footer class="blockquote-footer">Juan, socio fundador</footer>
          </blockquote>
        </section>
      </aside>
    </div>

    <!-- Sección de comentarios e interacción -->
    <div class="entrada mb-3">
      <h5>¿Te ha gustado conocernos?</h5>
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

    <form id="form-comentario" class="mt-4">
      <label for="nuevo-comentario" class="form-label">Añadir comentario general:</label>
      <input type="text" id="nuevo-comentario" class="form-control mb-2" required />
      <button type="submit" class="btn btn-success btn-sm">Añadir</button>
    </form>

    <ul id="lista-comentarios" class="list-group mt-2"></ul>
  </main>

  <!-- FOOTER -->
  <footer class="bg-dark text-white py-4 mt-5">
    <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center">
      <div class="mb-3 mb-md-0">
        &copy; 2025 Web Corporativa - Masterchess
      </div>
      <div>
        <a href="aviso-legal.html" class="text-white text-decoration-none me-3">Aviso legal</a>
        <a href="contacto.html" class="text-white text-decoration-none me-3">Contacto</a>
        <a href="politica-privacidad.html" class="text-white text-decoration-none">Política de privacidad</a>
      </div>
    </div>
  </footer>

    <!-- Bootstrap 5 JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <button class="btn btn-warning position-fixed bottom-0 end-0 m-4 rounded-circle" id="btn-top" title="Subir arriba">
    <i class="bi bi-arrow-up"></i>
  </button>

  <script src="js/script.js"></script>
  <script src="js/comentarios.js"></script>
  <script src="js/utilidades.js"></script>
  <script src="js/sobre-nosotros.js"></script>
  <script src="js/servicios-sobrenosotros.js"></script>


</body>

</html>
