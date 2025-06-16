<?php
session_start();
require_once 'config/db.php'; // Incluye la configuración de la base de datos

// Manejo de filtros y ordenación
$categoria_filtro = $_GET['categoria'] ?? '';
$precio_max = $_GET['precio'] ?? '';
$orden = $_GET['orden'] ?? 'recientes';

// Construcción de la consulta SQL
$query = "SELECT p.*, c.nombre AS categoria_nombre FROM productos p JOIN categorias c ON p.categoria_id = c.id WHERE 1=1";

// Aplicar filtros
if (!empty($categoria_filtro)) {
    $query .= " AND p.categoria_id = :categoria_id";
}
if (!empty($precio_max) && is_numeric($precio_max)) {
    $query .= " AND p.precio <= :precio_max";
}

// Aplicar ordenación
switch ($orden) {
    case 'precio-asc':
        $query .= " ORDER BY p.precio ASC";
        break;
    case 'precio-desc':
        $query .= " ORDER BY p.precio DESC";
        break;
    case 'nombre-asc':
        $query .= " ORDER BY p.nombre ASC";
        break;
    default: // recientes
        $query .= " ORDER BY p.fecha_creacion DESC";
}

// Preparar y ejecutar la consulta
$stmt = $pdo->prepare($query);

if (!empty($categoria_filtro)) {
    $stmt->bindParam(':categoria_id', $categoria_filtro, PDO::PARAM_INT);
}
if (!empty($precio_max) && is_numeric($precio_max)) {
    $stmt->bindParam(':precio_max', $precio_max, PDO::PARAM_INT);
}

$stmt->execute();
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Obtener categorías para el filtro
$categorias = $pdo->query("SELECT * FROM categorias")->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Masterchess - Productos</title>
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
    /* Estilos añadidos para mejorar la visualización de productos */
    .product-card {
      min-height: 450px;
      transition: transform 0.3s ease;
    }
    .product-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    .product-img-container {
      height: 200px;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 15px;
    }
    .product-img {
      max-height: 100%;
      max-width: 100%;
      object-fit: contain;
    }
    .product-description {
      overflow: hidden;
      text-overflow: ellipsis;
      display: -webkit-box;
      -webkit-line-clamp: 3;
      -webkit-box-orient: vertical;
      min-height: 72px;
    }
    .sticky-aside {
      position: sticky;
      top: 80px;
      height: fit-content;
      max-height: calc(100vh - 100px);
      overflow-y: auto;
    }
    .categoria-titulo {
      color: #2c3e50;
      font-weight: 600;
    }
  </style>
</head>

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

    <!-- Banner -->
    <header class="banner mt-5 pt-5">
        <div class="container">
            <h1 class="display-4">¡Nuestros Productos!</h1>
            <p class="lead">Juntos, haremos que cada jugada cuente. ¡Adelante, el próximo movimiento es tuyo!</p>
        </div>
    </header>

    <!-- MAIN: Grid con asides y productos -->
    <div class="container-fluid mt-4">
        <div class="row g-4">
            <!-- ASIDE IZQUIERDO: Filtros, categorías, novedades -->
            <aside class="col-md-2">
                <div class="sticky-aside">
                    <section class="mb-4" id="filtros-categorias">
                        <h2 class="h5 text-center">Filtrar por</h2>
                        <form method="GET" action="productos.php">
                            <div class="mb-3">
                                <label for="categoria" class="form-label">Categoría:</label>
                                <select class="form-select" id="categoria" name="categoria">
                                    <option value="">Todas</option>
                                    <?php foreach ($categorias as $cat): ?>
                                        <option value="<?= $cat['id'] ?>" <?= $categoria_filtro == $cat['id'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($cat['nombre']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="precio" class="form-label">Precio máximo:</label>
                                <input type="number" class="form-control" id="precio" name="precio" min="0" step="1" value="<?= htmlspecialchars($precio_max) ?>">
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Filtrar</button>
                            <?php if (!empty($categoria_filtro) || !empty($precio_max)): ?>
                                <a href="productos.php" class="btn btn-outline-secondary w-100 mt-2">Limpiar filtros</a>
                            <?php endif; ?>
                        </form>
                        <hr>
                        <h2 class="h5 text-center">Novedades</h2>
                        <ul class="list-group">
                            <li class="list-group-item">Nuevo set profesional 2025</li>
                            <li class="list-group-item">Edición limitada: Rey de Ébano</li>
                        </ul>

                        <div class="card producto-card text-center mb-4 mt-3">
                            <span class="badge bg-danger position-absolute top-0 start-0 m-2">¡-20%!</span>
                            <div class="product-img-container">
                                <img src="img/imag-4.png" class="product-img" alt="Tablero profesional">
                            </div>
                            <div class="card-body">
                                <h5 class="card-title">Tablero profesional</h5>
                                <p class="card-text small">Madera natural. Ideal para torneos y coleccionistas.</p>
                                <p class="text-primary fw-bold">89,99 €</p>
                                <a href="#" class="btn btn-outline-primary btn-sm">Ver detalles</a>
                            </div>
                        </div>
                    </section>
                </div>
            </aside>

            <!-- SECCIÓN PRINCIPAL DE PRODUCTOS -->
            <div class="col-md-8">
                <section aria-labelledby="productos" class="productos-container">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2 id="productos" class="display-5 mb-0">Catálogo de Productos</h2>
                        <div class="dropdown">
                            <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="ordenarDropdown" data-bs-toggle="dropdown">
                                Ordenar por: <?= ucfirst(str_replace('-', ' ', $orden)) ?>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="?<?= http_build_query(array_merge($_GET, ['orden' => 'recientes'])) ?>">Recientes</a></li>
                                <li><a class="dropdown-item" href="?<?= http_build_query(array_merge($_GET, ['orden' => 'precio-asc'])) ?>">Precio: Menor a Mayor</a></li>
                                <li><a class="dropdown-item" href="?<?= http_build_query(array_merge($_GET, ['orden' => 'precio-desc'])) ?>">Precio: Mayor a Menor</a></li>
                                <li><a class="dropdown-item" href="?<?= http_build_query(array_merge($_GET, ['orden' => 'nombre-asc'])) ?>">Nombre: A-Z</a></li>
                            </ul>
                        </div>
                    </div>
                    
                    <?php if (empty($productos)): ?>
                        <div class="alert alert-info text-center">
                            No se encontraron productos con los filtros aplicados.
                            <a href="productos.php" class="alert-link">Ver todos los productos</a>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Agrupar productos por categoría de manera eficiente -->
                    <?php
                    $productos_por_categoria = [];
                    foreach ($productos as $producto) {
                        $productos_por_categoria[$producto['categoria_nombre']][] = $producto;
                    }
                    
                    foreach ($productos_por_categoria as $categoria_nombre => $productos_categoria):
                    ?>
                        <h3 class="mt-5 mb-4 categoria-titulo border-bottom pb-2">
                            <?= htmlspecialchars($categoria_nombre) ?>
                        </h3>
                        <div class="row row-cols-1 row-cols-md-3 g-4">
                            <?php foreach ($productos_categoria as $producto): ?>
                                <!-- Producto -->
                                <article class="col">
                                    <div class="card h-100 shadow-sm product-card">
                                        <div class="product-img-container">
                                            <img src="uploads/productos/<?= htmlspecialchars($producto['imagen']) ?>" 
                                                 class="product-img" 
                                                 alt="<?= htmlspecialchars($producto['nombre']) ?>">
                                        </div>
                                        <div class="card-body d-flex flex-column">
                                            <h5 class="card-title fw-bold"><?= htmlspecialchars($producto['nombre']) ?></h5>
                                            <p class="card-text text-muted small product-description">
                                                <?= htmlspecialchars($producto['descripcion']) ?>
                                            </p>
                                            <div class="d-flex justify-content-between align-items-center mt-auto">
                                                <p class="card-text precio fw-bold text-primary h5 mb-0">
                                                    <?= number_format($producto['precio'], 2) ?> €
                                                </p>
                                                <a href="producto-detalle.php?id=<?= htmlspecialchars($producto['id']) ?>" 
                                                   class="btn btn-primary btn-sm">
                                                    Ver detalles
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </article>
                            <?php endforeach; ?>
                        </div> <!-- Cierra el row -->
                    <?php endforeach; ?>
                    
                    <!-- Paginación -->
                    <nav aria-label="Paginación de productos" class="mt-5">
                        <ul class="pagination justify-content-center">
                            <li class="page-item disabled">
                                <a class="page-link" href="#" tabindex="-1">Anterior</a>
                            </li>
                            <li class="page-item active"><a class="page-link" href="#">1</a></li>
                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                            <li class="page-item">
                                <a class="page-link" href="#">Siguiente</a>
                            </li>
                        </ul>
                    </nav>
                </section>
            </div>

            <!-- ASIDE DERECHO -->
            <aside class="col-md-2">
                <div class="sticky-aside">
                    <section class="bg-light p-3 rounded shadow-sm mb-3">
                        <h2 class="h5 text-center">Recomendaciones</h2>
                        <ul class="list-group mb-3">
                            <li class="list-group-item">Set de piezas más vendido 2024</li>
                            <li class="list-group-item">Tablero oficial FIDE</li>
                        </ul>
                        <h2 class="h5 text-center">Testimonios</h2>
                        <blockquote class="blockquote">
                            <p class="mb-0">"El mejor material de ajedrez que he comprado."</p>
                            <footer class="blockquote-footer">Carlos, cliente habitual</footer>
                        </blockquote>
                    </section>
                    
                    <div class="card mb-3">
                        <div class="card-body text-center">
                            <h5 class="card-title">Oferta Especial</h5>
                            <p class="card-text">¡Envía gratis en compras superiores a 50€!</p>
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </div>

    <!-- Productos destacados -->
    <section class="container my-5">
        <h2 class="text-center mb-4">🛍️ Productos Destacados</h2>
        <div class="d-flex justify-content-end gap-2 mb-3">
            <button id="btn-prev" class="btn btn-outline-primary btn-sm">⬅️ Anterior</button>
            <button id="btn-next" class="btn btn-outline-primary btn-sm">Siguiente ➡️</button>
        </div>

        <div class="slider-productos d-flex overflow-auto gap-3 pb-3">
            <div class="card flex-shrink-0">
                <img src="img/imag-1.png" class="card-img-top" alt="Producto 1">
                <div class="card-body">
                    <h5 class="card-title">Tablero Profesional</h5>
                    <p class="card-text">Diseño clásico de madera con acabados premium.</p>
                </div>
            </div>
            <div class="card flex-shrink-0">
                <img src="img/imag-2.png" class="card-img-top" alt="Producto 2">
                <div class="card-body">
                    <h5 class="card-title">Reloj Digital</h5>
                    <p class="card-text">Cronómetro oficial con múltiples modos de juego.</p>
                </div>
            </div>
            <div class="card flex-shrink-0">
                <img src="img/imag-3.png" class="card-img-top" alt="Producto 3">
                <div class="card-body">
                    <h5 class="card-title">Piezas de Torneo</h5>
                    <p class="card-text">Plomadas, grandes y resistentes.</p>
                </div>
            </div>
            <div class="card flex-shrink-0">
                <img src="img/imag-4.png" class="card-img-top" alt="Producto 4">
                <div class="card-body">
                    <h5 class="card-title">Bolsa de Transporte</h5>
                    <p class="card-text">Protege tu set de ajedrez en todo momento.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
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
    <script src="js/utilidades.js"></script>
    <script src="js/slider.js"></script>
    <script src="js/producto.js"></script>
    <script src="js/comentarios.js"></script>
    <script src="js/slider-productos.js"></script>
</body>
</html>