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
      <h1 class="display-4">¡Bienvenido a Masterchess!</h1>
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

  <!-- MAIN semántico con asides y centro -->
  <main class="container my-5">
    <div class="row">
      <!-- ASIDE IZQUIERDO: Noticias y promociones -->
      <aside class="col-md-3 mb-4" aria-labelledby="noticias-promos">
        <section class="mb-4" id="noticias-promos">
          <h2 class="h5 text-center">Noticias</h2>
          <ul class="list-group mb-4">
            <li class="list-group-item">¡Nuevo torneo este mes!</li>
            <li class="list-group-item">Descuentos en libros de ajedrez</li>
          </ul>
          <h2 class="h5 text-center">Promociones</h2>
          <ul class="list-group">
            <li class="list-group-item">-10% en tableros hasta el viernes</li>
            <li class="list-group-item">Clases gratis para nuevos usuarios</li>
          </ul>

          <div class="card equipo-card text-center mb-4">
            <img src="IMG/sergiop.png" class="card-img-top mx-auto mt-3 rounded-circle shadow" alt="Sergio, fundador">
            <div class="card-body">
              <h5 class="card-title mb-1">Sergio P.</h5>
              <p class="text-muted mb-1">Fundador y entrenador</p>
              <p class="card-text">Apasionado del ajedrez, especialista en torneos y formación juvenil.</p>
            </div>
          </div>
        </section>

        <!-- Slider de promociones -->
        <section class="container my-5">
          <h2 class="text-center mb-4"><i class="bi bi-megaphone me-2"></i>Promociones y eventos</h2>
          <div id="carouselEventos" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner rounded shadow-sm">
              <div class="carousel-item active">
                <img src="img/imag-3.png" class="d-block w-100" alt="Evento 1">
                <div class="carousel-caption d-none d-md-block">
                  <h5>Gran Torneo Masterchess</h5>
                  <p>Participa y gana premios increíbles.</p>
                </div>
              </div>
              <div class="carousel-item">
                <img src="img/imag-1.png" class="d-block w-100" alt="Evento 2">
                <div class="carousel-caption d-none d-md-block">
                  <h5>Clases magistrales</h5>
                  <p>Aprende de los grandes maestros internacionales.</p>
                </div>
              </div>
              <div class="carousel-item">
                <img src="img/imag-2.png" class="d-block w-100" alt="Evento 3">
                <div class="carousel-caption d-none d-md-block">
                  <h5>Descuentos exclusivos</h5>
                  <p>Solo por esta semana, ¡no te lo pierdas!</p>
                </div>
              </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselEventos" data-bs-slide="prev">
              <span class="carousel-control-prev-icon"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselEventos" data-bs-slide="next">
              <span class="carousel-control-next-icon"></span>
            </button>
          </div>
        </section>
      </aside>

      <!-- CENTRO: Secciones principales -->
      <div class="col-md-6">
        <!-- Mostrar mensaje de bienvenida si el usuario está logueado -->
        <?php if(isset($_SESSION['usuario_id'])): ?>
        <div class="alert alert-success mb-4">
          Bienvenido, <?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?>!
        </div>
        <?php endif; ?>

        <!-- Servicios destacados -->
        <section aria-labelledby="servicios" class="mb-5">
          <h2 class="text-center mb-4" id="servicios">
            <i class="bi bi-chess-board me-2"></i>Nuestros Servicios
          </h2>
          <div class="row row-cols-1 row-cols-md-3 g-4">
            <article class="col">
              <a class="text-decoration-none text-dark" href="servicios.php">
                <div class="card h-100">
                  <img alt="Clases Personalizadas" class="card-img-top" src="img/imag-1.png" />
                  <div class="card-body">
                    <h5 class="card-title">
                      <i class="bi bi-person-video2 me-1"></i>Clases Personalizadas
                    </h5>
                    <p class="card-text">
                      Para principiantes y avanzados, con métodos adaptados a tu ritmo.
                    </p>
                  </div>
                </div>
              </a>
            </article>
            <article class="col">
              <a class="text-decoration-none text-dark" href="servicios.php">
                <div class="card h-100">
                  <img alt="Torneos y Eventos" class="card-img-top" src="img/imag-2.png" />
                  <div class="card-body">
                    <h5 class="card-title">
                      <i class="bi bi-trophy me-1"></i>Torneos y Eventos
                    </h5>
                    <p class="card-text">
                      Participa en competiciones para desafiarte y conocer a otros jugadores.
                    </p>
                  </div>
                </div>
              </a>
            </article>
            <article class="col">
              <a class="text-decoration-none text-dark" href="servicios.php">
                <div class="card h-100">
                  <img alt="Análisis de Partidas" class="card-img-top" src="img/imag-3.png" />
                  <div class="card-body">
                    <h5 class="card-title">
                      <i class="bi bi-clipboard-data me-1"></i>Análisis de Partidas
                    </h5>
                    <p class="card-text">
                      Expertos que desglosan tus partidas para mejorar tu estrategia.
                    </p>
                  </div>
                </div>
              </a>
            </article>
          </div>
        </section>
        
        <!-- Sección de Login/Registro (solo visible si no está logueado) -->
        <?php if(!isset($_SESSION['usuario_id'])): ?>
        <section id="login-section" class="mb-5 bg-light p-4 rounded shadow-sm">
          <h2 class="text-center mb-4"><i class="bi bi-box-arrow-in-right me-2"></i>Iniciar Sesión</h2>
          
          <?php if(isset($_GET['error'])): ?>
          <div class="alert alert-danger"><?php echo htmlspecialchars($_GET['error']); ?></div>
          <?php endif; ?>
          
          <?php if(isset($_GET['success'])): ?>
          <div class="alert alert-success"><?php echo htmlspecialchars($_GET['success']); ?></div>
          <?php endif; ?>
          
          <form action="procesar_login.php" method="POST">
            <div class="mb-3">
              <label for="email" class="form-label">Email:</label>
              <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
              <label for="password" class="form-label">Contraseña:</label>
              <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary w-100"><i class="bi bi-box-arrow-in-right me-2"></i>Entrar</button>
          </form>
          
          <div class="text-center mt-3">
            <button class="btn btn-link" type="button" data-bs-toggle="collapse" data-bs-target="#registro-form">
              <i class="bi bi-person-plus me-1"></i>¿No tienes cuenta? Regístrate aquí
            </button>
          </div>
          
          <div class="collapse mt-3" id="registro-form">
            <div class="card card-body">
              <h3 class="h5 text-center mb-3"><i class="bi bi-person-plus me-2"></i>Registro de Cliente</h3>
              <form action="procesar_registro.php" method="POST">
                <div class="mb-3">
                  <label for="nombre" class="form-label">Nombre completo:</label>
                  <input type="text" class="form-control" id="nombre" name="nombre" required>
                </div>
                <div class="mb-3">
                  <label for="email_registro" class="form-label">Email:</label>
                  <input type="email" class="form-control" id="email_registro" name="email" required>
                </div>
                <div class="mb-3">
                  <label for="password_registro" class="form-label">Contraseña:</label>
                  <input type="password" class="form-control" id="password_registro" name="password" required>
                </div>
                <div class="mb-3">
                  <label for="confirm_password" class="form-label">Confirmar Contraseña:</label>
                  <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                </div>
                <button type="submit" class="btn btn-success w-100"><i class="bi bi-person-plus me-2"></i>Registrarse</button>
              </form>
            </div>
          </div>
        </section>
        <?php endif; ?>

        <!-- Productos destacados -->
        <section aria-labelledby="productos" class="mb-5">
          <h2 class="text-center mb-4" id="productos">
            <i class="bi bi-shop me-2"></i>Productos Destacados
          </h2>
          <div class="row row-cols-1 row-cols-md-3 g-4">
            <article class="col">
              <a class="text-decoration-none text-dark" href="productos.php">
                <div class="card">
                  <img alt="Tableros y piezas" class="card-img-top" src="img/imag-4.png" />
                  <div class="card-body">
                    <h5 class="card-title">
                      <i class="bi bi-grid-3x3 me-1"></i>Tableros y piezas
                    </h5>
                    <p class="card-text">
                      Madera de alta calidad.
                    </p>
                  </div>
                </div>
              </a>
            </article>
            <article class="col">
              <a class="text-decoration-none text-dark" href="productos.php">
                <div class="card">
                  <img alt="Software Chess" class="card-img-top" src="img/imag-5.png" />
                  <div class="card-body">
                    <h5 class="card-title">
                      <i class="bi bi-laptop me-1"></i>Software Chess
                    </h5>
                    <p class="card-text">
                      Inteligencia artificial de última generación.
                    </p>
                  </div>
                </div>
              </a>
            </article>
            <article class="col">
              <a class="text-decoration-none text-dark" href="productos.php">
                <div class="card">
                  <img alt="Libros de Ajedrez" class="card-img-top" src="img/imag-6.png" />
                  <div class="card-body">
                    <h5 class="card-title">
                      <i class="bi bi-book me-1"></i>Libros de Ajedrez
                    </h5>
                    <p class="card-text">
                      Desarrolla un pensamiento analítico.
                    </p>
                  </div>
                </div>
              </a>
            </article>
          </div>
        </section>
        
        <!-- Sobre nosotros breve -->
        <section aria-labelledby="sobre-nosotros" class="mb-5 bg-light p-4 rounded shadow-sm">
          <h2 class="text-center mb-4" id="sobre-nosotros">
            <i class="bi bi-info-circle me-2"></i>Sobre Nosotros
          </h2>
          <p class="text-center">
            Somos un equipo apasionado de ajedrez con amplia experiencia en
            competiciones, enseñanza y eventos.
          </p>
          <div class="text-center">
            <a class="btn btn-primary mt-2" href="sobre-nosotros.php">
              <i class="bi bi-info-circle me-2"></i>Conócenos más
            </a>
          </div>
        </section>
        
        <!-- Contacto breve -->
        <section aria-labelledby="contacto" class="bg-white p-4 rounded shadow-sm">
          <h2 class="text-center mb-4" id="contacto">
            <i class="bi bi-envelope me-2"></i>Contacto
          </h2>
          <p class="text-center">
            Puedes contactarnos a través de los siguientes medios:
          </p>
          <ul class="list-unstyled text-center">
            <li>
              <i class="bi bi-geo-alt me-2"></i>Dirección: Calle Jaque al Rey 123, Ciudad Caballo de Troya
            </li>
            <li>
              <i class="bi bi-telephone me-2"></i>Teléfono: +34 123 456 789
            </li>
            <li>
              <i class="bi bi-envelope me-2"></i>Email: info.masterchess@gmail.com
            </li>
          </ul>
          <div class="text-center">
            <a class="btn btn-outline-primary" href="contacto.php">
              <i class="bi bi-envelope me-2"></i>Ir al formulario de contacto
            </a>
          </div>
        </section>
      </div>

      <!-- ASIDE DERECHO: Recomendaciones y testimonios -->
      <aside aria-labelledby="recomendaciones-testimonios" class="col-md-3 mb-4">
        <section class="mb-4" id="recomendaciones-testimonios">
          <h2 class="h5 text-center">
            <i class="bi bi-star me-2"></i>Recomendaciones
          </h2>
          <ul class="list-group mb-4">
            <li class="list-group-item">
              <i class="bi bi-book me-2"></i>Libro destacado: "El arte del mate"
            </li>
            <li class="list-group-item">
              <i class="bi bi-laptop me-2"></i>Software Chess Pro recomendado
            </li>
          </ul>
          <h2 class="h5 text-center">
            <i class="bi bi-chat-quote me-2"></i>Testimonios
          </h2>
          <div class="card testimonial-card mb-4 p-3">
            <div class="d-flex align-items-center mb-3">
              <img alt="Testimonio" class="rounded-circle me-3" src="img/imag-10.png" />
              <div>
                <strong>
                  Ana G.
                </strong>
                <div class="text-muted">
                  Jugadora y alumna
                </div>
              </div>
            </div>
            <blockquote class="blockquote mb-0">
              <p>
                "Gracias a Masterchess, mejoré mi juego en 3 meses."
              </p>
            </blockquote>
          </div>
        </section>
      </aside>
    </div>
  </main>

  <!-- FOOTER -->
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

  <!-- Bootstrap 5 JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <button class="btn btn-warning position-fixed bottom-0 end-0 m-4 rounded-circle" id="btn-top" title="Subir arriba">
    <i class="bi bi-arrow-up"></i>
  </button>

  <script src="js/script.js"></script>
  <script src="js/slider-productos.js"></script>
</body>
</html>