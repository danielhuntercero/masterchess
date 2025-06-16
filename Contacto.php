<?php
session_start();
require_once 'config/db.php'; // Incluye la configuración de la base de datos

// Procesar el formulario de contacto
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $asunto = filter_input(INPUT_POST, 'asunto', FILTER_SANITIZE_STRING);
    $mensaje = filter_input(INPUT_POST, 'mensaje', FILTER_SANITIZE_STRING);
    
    $errores = [];
    
    if (empty($nombre)) {
        $errores[] = "El nombre es obligatorio.";
    }
    
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errores[] = "El correo electrónico no es válido.";
    }
    
    if (empty($asunto)) {
        $errores[] = "El asunto es obligatorio.";
    }
    
    if (empty($mensaje) || strlen($mensaje) < 10) {
        $errores[] = "El mensaje debe tener al menos 10 caracteres.";
    }
    
    if (empty($errores)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO mensajes_contacto (nombre, email, asunto, mensaje, fecha) 
                                  VALUES (?, ?, ?, ?, NOW())");
            $stmt->execute([$nombre, $email, $asunto, $mensaje]);
            
            $_SESSION['mensaje_exito'] = "Mensaje enviado correctamente. Te responderemos pronto.";
            header("Location: contacto.php");
            exit();
        } catch (PDOException $e) {
            $errores[] = "Error al enviar el mensaje. Por favor, inténtalo más tarde.";
            error_log("Error en contacto.php: " . $e->getMessage());
        }
    }
    
    if (!empty($errores)) {
        $_SESSION['errores_contacto'] = $errores;
        $_SESSION['datos_contacto'] = [
            'nombre' => $nombre,
            'email' => $email,
            'asunto' => $asunto,
            'mensaje' => $mensaje
        ];
        header("Location: contacto.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Masterchess - Contacto</title>
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;700&display=swap" rel="stylesheet">
  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
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
    .bg-chess-dark {
      background-color: #2c3e50;
    }
    .bg-chess-green {
      background-color: #2e8b57;
    }
    .btn-chess-green {
      background-color: #2e8b57;
      color: white;
    }
    .btn-chess-green:hover {
      background-color: #3cb371;
      color: white;
    }
  </style>
</head>

<body>
  <!-- Banner -->
  <header class="banner mt-5 pt-5">
    <div class="container">
      <h1 class="display-4">¡Contáctese con Masterchess!</h1>
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

  <!-- MAIN: Grid con asides y formulario de contacto -->
  <main class="container my-5">
    <!-- Mostrar mensajes de éxito o error -->
    <?php if (isset($_SESSION['mensaje_exito'])): ?>
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= $_SESSION['mensaje_exito'] ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
      <?php unset($_SESSION['mensaje_exito']); ?>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['errores_contacto'])): ?>
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Error:</strong>
        <ul class="mb-0">
          <?php foreach ($_SESSION['errores_contacto'] as $error): ?>
            <li><?= $error ?></li>
          <?php endforeach; ?>
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
      <?php unset($_SESSION['errores_contacto']); ?>
    <?php endif; ?>

    <div class="row">
      <!-- ASIDE IZQUIERDO: Redes, ubicación, FAQs -->
      <aside class="col-md-3 mb-4" aria-labelledby="redes-faq-ubicacion">
        <section class="mb-4" id="redes-faq-ubicacion">
          <div class="card shadow-sm">
            <div class="card-header bg-chess-green text-white">
              <h2 class="h5 mb-0 text-center"><i class="bi bi-people-fill me-2"></i>Redes Sociales</h2>
            </div>
            <div class="card-body">
              <ul class="list-group list-group-flush mb-4">
                <li class="list-group-item d-flex align-items-center">
                  <i class="bi bi-instagram me-2 text-danger"></i>
                  <a href="#" class="text-decoration-none">@masterchess</a>
                </li>
                <li class="list-group-item d-flex align-items-center">
                  <i class="bi bi-facebook me-2 text-primary"></i>
                  <a href="#" class="text-decoration-none">/masterchessclub</a>
                </li>
                <li class="list-group-item d-flex align-items-center">
                  <i class="bi bi-twitter me-2 text-info"></i>
                  <a href="#" class="text-decoration-none">@masterchess_es</a>
                </li>
              </ul>
            </div>
          </div>

          <div class="card shadow-sm mt-4">
            <div class="card-header bg-chess-green text-white">
              <h2 class="h5 mb-0 text-center"><i class="bi bi-question-circle me-2"></i>FAQs</h2>
            </div>
            <div class="card-body">
              <div class="accordion" id="accordionFAQ">
                <div class="accordion-item">
                  <h3 class="accordion-header" id="headingOne">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" 
                            data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                      ¿Dónde estamos?
                    </button>
                  </h3>
                  <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" 
                       data-bs-parent="#accordionFAQ">
                    <div class="accordion-body">
                      Calle Jaque al Rey 123, Ciudad Caballo de Troya
                    </div>
                  </div>
                </div>
                <div class="accordion-item">
                  <h3 class="accordion-header" id="headingTwo">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" 
                            data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                      ¿Cómo contactar rápido?
                    </button>
                  </h3>
                  <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" 
                       data-bs-parent="#accordionFAQ">
                    <div class="accordion-body">
                      WhatsApp: +34 123 456 789
                    </div>
                  </div>
                </div>
                <div class="accordion-item">
                  <h3 class="accordion-header" id="headingThree">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" 
                            data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                      ¿Horario de atención?
                    </button>
                  </h3>
                  <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" 
                       data-bs-parent="#accordionFAQ">
                    <div class="accordion-body">
                      Lunes a Viernes: 10:00 - 20:00<br>
                      Sábado: 10:00 - 14:00<br>
                      Domingo: Cerrado
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="card shadow-sm mt-4">
            <div class="card-header bg-chess-green text-white">
              <h2 class="h5 mb-0 text-center"><i class="bi bi-geo-alt me-2"></i>Ubicación</h2>
            </div>
            <div class="card-body text-center">
              <button class="btn btn-chess-green mb-3" data-bs-toggle="modal" data-bs-target="#modalMapa">
                <i class="bi bi-map me-2"></i>Ver Mapa Completo
              </button>
              
              <div class="ratio ratio-4x3">
                <iframe
                  src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d5895.545668905154!2d-13.895125057542277!3d28.52431360973807!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xc47c811ff0c67fb%3A0x2fd2009b940cee18!2sParque%20Tecnol%C3%B3gico%20de%20Fuerteventura%20SA%2C%20MP!5e0!3m2!1ses!2ses!4v1731859799368!5m2!1ses!2ses"
                  allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
              </div>
            </div>
          </div>
        </section>
      </aside>

      <!-- CENTRO: Formulario de contacto y datos -->
      <div class="col-md-6">
        <section aria-labelledby="contacto-form">
          <div class="card shadow-sm">
            <div class="card-header bg-chess-green text-white">
              <h2 id="contacto-form" class="h4 mb-0 text-center">
                <i class="bi bi-envelope me-2"></i>Formulario de Contacto
              </h2>
            </div>
            <div class="card-body">
              <form method="POST" action="contacto.php">
                <div class="mb-3">
                  <label for="nombre" class="form-label">Nombre completo</label>
                  <input type="text" class="form-control" id="nombre" name="nombre" required
                         value="<?= isset($_SESSION['datos_contacto']['nombre']) ? htmlspecialchars($_SESSION['datos_contacto']['nombre']) : '' ?>">
                </div>
                <div class="mb-3">
                  <label for="email" class="form-label">Correo electrónico</label>
                  <input type="email" class="form-control" id="email" name="email" required
                         value="<?= isset($_SESSION['datos_contacto']['email']) ? htmlspecialchars($_SESSION['datos_contacto']['email']) : '' ?>">
                </div>
                <div class="mb-3">
                  <label for="asunto" class="form-label">Asunto</label>
                  <input type="text" class="form-control" id="asunto" name="asunto" required
                         value="<?= isset($_SESSION['datos_contacto']['asunto']) ? htmlspecialchars($_SESSION['datos_contacto']['asunto']) : '' ?>">
                </div>
                <div class="mb-3">
                  <label for="mensaje" class="form-label">Mensaje</label>
                  <textarea class="form-control" id="mensaje" name="mensaje" rows="5" required><?= isset($_SESSION['datos_contacto']['mensaje']) ? htmlspecialchars($_SESSION['datos_contacto']['mensaje']) : '' ?></textarea>
                </div>
                <button type="submit" class="btn btn-chess-green w-100">
                  <i class="bi bi-send me-2"></i>Enviar mensaje
                </button>
              </form>
              <?php unset($_SESSION['datos_contacto']); ?>
            </div>
          </div>

          <div class="card shadow-sm mt-4">
            <div class="card-header bg-chess-green text-white">
              <h2 id="info-contacto" class="h4 mb-0 text-center">
                <i class="bi bi-info-circle me-2"></i>Otros medios de contacto
              </h2>
            </div>
            <div class="card-body">
              <ul class="list-group list-group-flush">
                <li class="list-group-item d-flex align-items-center">
                  <i class="bi bi-telephone me-3 text-chess-green"></i>
                  <div>
                    <h5 class="mb-1">Teléfono</h5>
                    <p class="mb-0">+34 123 456 789</p>
                  </div>
                </li>
                <li class="list-group-item d-flex align-items-center">
                  <i class="bi bi-envelope me-3 text-chess-green"></i>
                  <div>
                    <h5 class="mb-1">Email</h5>
                    <p class="mb-0">info.masterchess@gmail.com</p>
                  </div>
                </li>
                <li class="list-group-item d-flex align-items-center">
                  <i class="bi bi-geo-alt me-3 text-chess-green"></i>
                  <div>
                    <h5 class="mb-1">Dirección</h5>
                    <p class="mb-0">Calle Jaque al Rey 123, Ciudad Caballo de Troya</p>
                  </div>
                </li>
              </ul>
            </div>
          </div>
        </section>
      </div>

      <!-- ASIDE DERECHO: Horarios y testimonios -->
      <aside class="col-md-3 mb-4" aria-labelledby="horarios-testimonios">
        <section class="mb-4" id="horarios-testimonios">
          <div class="card shadow-sm">
            <div class="card-header bg-chess-green text-white">
              <h2 class="h5 mb-0 text-center"><i class="bi bi-clock me-2"></i>Horario de atención</h2>
            </div>
            <div class="card-body">
              <ul class="list-group list-group-flush">
                <li class="list-group-item d-flex justify-content-between align-items-center">
                  <span>Lunes a Viernes</span>
                  <span class="badge bg-chess-green rounded-pill">10:00 - 20:00</span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                  <span>Sábado</span>
                  <span class="badge bg-chess-green rounded-pill">10:00 - 14:00</span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                  <span>Domingo</span>
                  <span class="badge bg-secondary rounded-pill">Cerrado</span>
                </li>
              </ul>
            </div>
          </div>

          <div class="card shadow-sm mt-4">
            <div class="card-header bg-chess-green text-white">
              <h2 class="h5 mb-0 text-center"><i class="bi bi-chat-quote me-2"></i>Testimonios</h2>
            </div>
            <div class="card-body">
              <div id="testimoniosCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                  <div class="carousel-item active">
                    <blockquote class="blockquote">
                      <p class="mb-0">"El equipo de Masterchess resolvió mi duda en minutos."</p>
                      <footer class="blockquote-footer mt-2">Elena, clienta</footer>
                    </blockquote>
                  </div>
                  <div class="carousel-item">
                    <blockquote class="blockquote">
                      <p class="mb-0">"Excelente atención al cliente, muy profesionales."</p>
                      <footer class="blockquote-footer mt-2">Carlos, cliente habitual</footer>
                    </blockquote>
                  </div>
                  <div class="carousel-item">
                    <blockquote class="blockquote">
                      <p class="mb-0">"Rápida respuesta y solución a mi problema."</p>
                      <footer class="blockquote-footer mt-2">Miguel, nuevo cliente</footer>
                    </blockquote>
                  </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#testimoniosCarousel" data-bs-slide="prev">
                  <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                  <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#testimoniosCarousel" data-bs-slide="next">
                  <span class="carousel-control-next-icon" aria-hidden="true"></span>
                  <span class="visually-hidden">Next</span>
                </button>
              </div>
            </div>
          </div>

          <div class="card shadow-sm mt-4">
            <div class="card-header bg-chess-green text-white">
              <h2 class="h5 mb-0 text-center"><i class="bi bi-headset me-2"></i>Atención al cliente</h2>
            </div>
            <div class="card-body text-center">
              <p>¿Necesitas ayuda inmediata?</p>
              <a href="https://wa.me/34123456789" class="btn btn-success">
                <i class="bi bi-whatsapp me-2"></i>Chat por WhatsApp
              </a>
            </div>
          </div>
        </section>
      </aside>
    </div>
  </main>

  <!-- Modal Mapa -->
  <div class="modal fade" id="modalMapa" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Nuestra ubicación</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body p-0">
          <div class="ratio ratio-16x9">
            <iframe
              src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d5895.545668905154!2d-13.895125057542277!3d28.52431360973807!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xc47c811ff0c67fb%3A0x2fd2009b940cee18!2sParque%20Tecnol%C3%B3gico%20de%20Fuerteventura%20SA%2C%20MP!5e0!3m2!1ses!2ses!4v1731859799368!5m2!1ses!2ses"
              allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
          <a href="https://maps.google.com/?q=Calle+Jaque+al+Rey+123,Ciudad+Caballo+de+Troya" 
             class="btn btn-chess-green" target="_blank">
            <i class="bi bi-geo-alt me-2"></i>Abrir en Google Maps
          </a>
        </div>
      </div>
    </div>
  </div>

  <!-- FOOTER -->
  <footer class="bg-dark text-white py-4 mt-5">
    <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center">
      <div class="mb-3 mb-md-0">
        &copy; 2025 Web Corporativa - Masterchess
      </div>
      <div>
        <a href="aviso-legal.php" class="text-white text-decoration-none me-3">
          <i class="bi bi-shield-lock me-1"></i>Aviso legal
        </a>
        <a href="contacto.php" class="text-white text-decoration-none me-3">
          <i class="bi bi-envelope me-1"></i>Contacto
        </a>
        <a href="politica-privacidad.php" class="text-white text-decoration-none">
          <i class="bi bi-lock me-1"></i>Política de privacidad
        </a>
      </div>
    </div>
  </footer>

  <!-- Bootstrap 5 JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <!-- Font Awesome JS -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
  <button class="btn btn-warning position-fixed bottom-0 end-0 m-4 rounded-circle" id="btn-top" title="Subir arriba">
    <i class="bi bi-arrow-up"></i>
  </button>
 

  <script src="js/script.js"></script>
  <script src="js/comentarios.js"></script>
  <script src="js/utilidades.js"></script>
  <script src="js/contacto.js"></script>
</body>
</html>