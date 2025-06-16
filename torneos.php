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
      <h1 class="display-4">¡Bienvenido a Torneos Masterchess!</h1>
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
  <div class="container-fluid mt-4">
    <div class="row g-4">
      <!-- ASIDE IZQUIERDO: Ranking y estadísticas -->
      <aside class="col-md-3">
        <section class="mb-4 bg-light p-3 rounded shadow-sm">
          <h2 class="h4 text-center mb-3"><i class="bi bi-trophy-fill me-2"></i>Ranking de Jugadores</h2>
          <ol class="list-group list-group-numbered">
            <li class="list-group-item d-flex justify-content-between align-items-start">
              <div class="ms-2 me-auto">
                <div class="fw-bold">Mikhail Ivanov</div>
                <span class="badge bg-success rounded-pill">ELO: 2450</span>
              </div>
              <span class="badge bg-primary">3 torneos</span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-start">
              <div class="ms-2 me-auto">
                <div class="fw-bold">Anna Petrov</div>
                <span class="badge bg-success rounded-pill">ELO: 2380</span>
              </div>
              <span class="badge bg-primary">2 torneos</span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-start">
              <div class="ms-2 me-auto">
                <div class="fw-bold">Sergei Kuznetsov</div>
                <span class="badge bg-success rounded-pill">ELO: 2320</span>
              </div>
              <span class="badge bg-primary">4 torneos</span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-start">
              <div class="ms-2 me-auto">
                <div class="fw-bold">Elena Smirnova</div>
                <span class="badge bg-success rounded-pill">ELO: 2280</span>
              </div>
              <span class="badge bg-primary">1 torneo</span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-start">
              <div class="ms-2 me-auto">
                <div class="fw-bold">Dmitri Volkov</div>
                <span class="badge bg-success rounded-pill">ELO: 2250</span>
              </div>
              <span class="badge bg-primary">2 torneos</span>
            </li>
          </ol>
          <a href="#" class="btn btn-outline-success w-100 mt-3">Ver ranking completo</a>
        </section>

        <section class="bg-light p-3 rounded shadow-sm mb-3">
          <h2 class="h4 text-center mb-3"><i class="bi bi-graph-up me-2"></i>Estadísticas</h2>
          <div class="text-center mb-3">
            <div class="display-4">24</div>
            <div class="text-muted">Torneos organizados</div>
          </div>
          <div class="text-center mb-3">
            <div class="display-4">156</div>
            <div class="text-muted">Jugadores registrados</div>
          </div>
          <div class="text-center">
            <div class="display-4">€1,250</div>
            <div class="text-muted">Premio mayor</div>
          </div>
        </section>
      </aside>

      <!-- CONTENIDO PRINCIPAL -->
      <div class="col-md-6">
        <main class="container my-5">
          <h2 class="text-center mb-4">🎯 Torneos Masterchess</h2>

          <?php
          // Conexión a la base de datos y obtención de torneos
          require_once 'config/db.php';
          
          $torneos = [];
          try {
              $stmt = $pdo->query("SELECT * FROM torneos WHERE fecha >= CURDATE() ORDER BY fecha ASC");
              $torneos = $stmt->fetchAll(PDO::FETCH_ASSOC);
          } catch (PDOException $e) {
              echo '<div class="alert alert-danger">Error al cargar los torneos: ' . htmlspecialchars($e->getMessage()) . '</div>';
          }
          ?>

          <!-- Listado de Torneos -->
          <section class="mb-5">
            <h3 class="mb-4">Próximos Torneos</h3>
            
            <?php if (count($torneos) > 0): ?>
              <div class="row g-4">
                <?php foreach ($torneos as $torneo): ?>
                  <div class="col-12">
                    <div class="card h-100 shadow-sm">
                      <div class="card-header bg-success text-white d-flex justify-content-between">
                        <h4 class="card-title mb-0"><?php echo htmlspecialchars($torneo['nombre']); ?></h4>
                        <span class="badge bg-light text-dark">
                          <?php echo htmlspecialchars(date('d/m/Y', strtotime($torneo['fecha']))); ?>
                        </span>
                      </div>
                      <div class="card-body">
                        <div class="row">
                          <div class="col-md-6">
                            <p><i class="bi bi-clock"></i> <strong>Hora:</strong> <?php echo htmlspecialchars(date('H:i', strtotime($torneo['fecha']))); ?></p>
                            <p><i class="bi bi-cash-stack"></i> <strong>Precio:</strong> <?php echo htmlspecialchars(number_format($torneo['precio_insc'], 2, ',', '.')); ?> €</p>
                          </div>
                          <div class="col-md-6">
                            <p><i class="bi bi-people"></i> <strong>Plazas:</strong> 
                              <?php 
                                $inscritos = $pdo->query("SELECT COUNT(*) FROM inscripciones WHERE torneo_id = {$torneo['id']}")->fetchColumn();
                                echo htmlspecialchars($inscritos) . '/' . htmlspecialchars($torneo['max_participantes'] ?? '∞'); 
                              ?>
                            </p>
                            <p><i class="bi bi-award"></i> <strong>Premio:</strong> <?php echo htmlspecialchars($torneo['premio'] ?? 'Trofeo + Medalla'); ?></p>
                          </div>
                        </div>
                        <p class="card-text mt-2"><?php echo htmlspecialchars($torneo['descripcion']); ?></p>
                      </div>
                      <div class="card-footer bg-light d-flex justify-content-between">
                        <a href="torneo-detalle.php?id=<?php echo $torneo['id']; ?>" class="btn btn-outline-success">Más información</a>
                        <a href="registro.php?torneo=<?php echo $torneo['id']; ?>" class="btn btn-primary">Inscribirse</a>
                      </div>
                    </div>
                  </div>
                <?php endforeach; ?>
              </div>
            <?php else: ?>
              <div class="alert alert-info">
                Actualmente no hay torneos programados. ¡Vuelve pronto para nuevas oportunidades!
              </div>
            <?php endif; ?>
          </section>

          <!-- Sección de emparejamiento -->
          <section class="bg-light p-4 rounded shadow-sm mb-5">
            <h3 class="mb-3"><i class="bi bi-people-fill"></i> Emparejamiento de Jugadores</h3>
            <form id="form-torneo" class="row g-3">
              <div class="col-md-6">
                <label for="jugador1" class="form-label">Jugador 1</label>
                <input type="text" class="form-control" id="jugador1" required>
              </div>
              <div class="col-md-6">
                <label for="jugador2" class="form-label">Jugador 2</label>
                <input type="text" class="form-control" id="jugador2" required>
              </div>
              <div class="col-12 text-center">
                <button type="submit" class="btn btn-success mt-3">Iniciar duelo</button>
              </div>
            </form>
          </section>

          <section id="resultados-torneo" class="bg-white p-4 rounded shadow-sm">
            <h3 class="mb-3"><i class="bi bi-trophy"></i> Resultados Recientes</h3>
            <div class="table-responsive">
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th>Torneo</th>
                    <th>Ganador</th>
                    <th>Resultado</th>
                    <th>Fecha</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>Torneo Primavera</td>
                    <td>Mikhail Ivanov</td>
                    <td>3-1</td>
                    <td>15/05/2024</td>
                  </tr>
                  <tr>
                    <td>Abierto de Verano</td>
                    <td>Anna Petrov</td>
                    <td>2.5-1.5</td>
                    <td>22/06/2024</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </section>
        </main>
      </div>

      <!-- ASIDE DERECHO: Próximos eventos y patrocinadores -->
      <aside class="col-md-3">
        <div class="sticky-top pt-3">
          <section class="bg-light p-3 rounded shadow-sm mb-3">
            <h2 class="h4 text-center mb-3"><i class="bi bi-calendar-event me-2"></i>Calendario</h2>
            <div class="list-group">
              <a href="#" class="list-group-item list-group-item-action">
                <div class="d-flex w-100 justify-content-between">
                  <h5 class="mb-1">Torneo Relámpago</h5>
                  <small class="text-muted">10/07</small>
                </div>
                <p class="mb-1">Modalidad blitz (5+0)</p>
              </a>
              <a href="#" class="list-group-item list-group-item-action">
                <div class="d-flex w-100 justify-content-between">
                  <h5 class="mb-1">Abierto de Otoño</h5>
                  <small class="text-muted">15/09</small>
                </div>
                <p class="mb-1">Clásico (90+30)</p>
              </a>
              <a href="#" class="list-group-item list-group-item-action">
                <div class="d-flex w-100 justify-content-between">
                  <h5 class="mb-1">Campeonato Juvenil</h5>
                  <small class="text-muted">20/10</small>
                </div>
                <p class="mb-1">Sub-18</p>
              </a>
            </div>
            <a href="#" class="btn btn-outline-success w-100 mt-3">Ver calendario completo</a>
          </section>

          <section class="bg-light p-3 rounded shadow-sm mb-3">
            <h2 class="h4 text-center mb-3"><i class="bi bi-star-fill me-2"></i>Patrocinadores</h2>
            <div class="text-center mb-3">
              <img src="IMG/patrocinador1.png" alt="Patrocinador" class="img-fluid mb-2" style="max-height: 60px;">
              <p class="small">Oficial Chess Equipment</p>
            </div>
            <div class="text-center">
              <img src="IMG/patrocinador2.png" alt="Patrocinador" class="img-fluid mb-2" style="max-height: 60px;">
              <p class="small">Mind Sports Academy</p>
            </div>
          </section>

          <section class="bg-light p-3 rounded shadow-sm">
            <h2 class="h4 text-center mb-3"><i class="bi bi-chat-square-text me-2"></i>Testimonios</h2>
            <div class="card mb-3">
              <div class="card-body">
                <blockquote class="blockquote mb-0">
                  <p>"La mejor organización de torneos que he visto. ¡Volveré seguro!"</p>
                  <footer class="blockquote-footer">Carlos, <cite>ELO 2100</cite></footer>
                </blockquote>
              </div>
            </div>
            <div class="card">
              <div class="card-body">
                <blockquote class="blockquote mb-0">
                  <p>"Ambiente competitivo pero muy amigable. Perfecto para mejorar."</p>
                  <footer class="blockquote-footer">Ana, <cite>ELO 1950</cite></footer>
                </blockquote>
              </div>
            </div>
          </section>
        </div>
      </aside>
    </div>
  </div>

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
  <script src="js/utilidades.js"></script>
  <script src="js/torneo.js"></script>
</body>
</html>