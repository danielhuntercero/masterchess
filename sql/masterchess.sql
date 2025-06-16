
DROP DATABASE IF EXISTS masterchess;
CREATE DATABASE masterchess;
USE masterchess;

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `masterchess`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carritos`
--

CREATE TABLE `carritos` (
  `id` int NOT NULL,
  `usuario_id` int NOT NULL,
  `fecha_creacion` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `id` int NOT NULL,
  `nombre` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `descripcion` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id`, `nombre`, `descripcion`) VALUES
(1, 'Tableros y piezas', 'Tableros y piezas de ajedrez de diferentes materiales'),
(2, 'Software', 'Programas y aplicaciones para ajedrez'),
(3, 'Libros', 'Material educativo sobre ajedrez'),
(5, 'Accesorios', 'Accesorios para los productos de la pagina ya existentes');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalles_pedido`
--

CREATE TABLE `detalles_pedido` (
  `id` int NOT NULL,
  `pedido_id` int NOT NULL,
  `producto_id` int NOT NULL,
  `cantidad` int NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inscripciones`
--

CREATE TABLE `inscripciones` (
  `id` int NOT NULL,
  `usuario_id` int NOT NULL,
  `torneo_id` int DEFAULT NULL,
  `servicio_id` int DEFAULT NULL,
  `fecha_inscripcion` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `inscripciones`
--

INSERT INTO `inscripciones` (`id`, `usuario_id`, `torneo_id`, `servicio_id`, `fecha_inscripcion`) VALUES
(1, 3, 1, NULL, '2025-06-10 19:35:57'),
(2, 3, NULL, 1, '2025-06-10 19:35:57');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `items_carrito`
--

CREATE TABLE `items_carrito` (
  `id` int NOT NULL,
  `carrito_id` int NOT NULL,
  `producto_id` int NOT NULL,
  `cantidad` int NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mensajes_contacto`
--

CREATE TABLE `mensajes_contacto` (
  `id` int NOT NULL,
  `nombre` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `asunto` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `mensaje` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `fecha` datetime DEFAULT CURRENT_TIMESTAMP,
  `leido` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `mensajes_contacto`
--

INSERT INTO `mensajes_contacto` (`id`, `nombre`, `email`, `asunto`, `mensaje`, `fecha`, `leido`) VALUES
(2, 'Iago', 'x@masterchess.com', 'hola', '123333333333333333333333333333333333333333', '2025-06-15 18:43:36', 1),
(5, 'Iago', 'x@masterchess.com', 'hola', 'qqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqq', '2025-06-16 19:56:05', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
  `id` int NOT NULL,
  `usuario_id` int NOT NULL,
  `fecha` datetime DEFAULT CURRENT_TIMESTAMP,
  `total` decimal(10,2) NOT NULL,
  `estado` enum('pendiente','procesando','enviado','entregado','cancelado') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'pendiente',
  `direccion_envio` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int NOT NULL,
  `nombre` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `descripcion` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `precio` decimal(10,2) NOT NULL,
  `stock` int NOT NULL DEFAULT '0',
  `categoria_id` int DEFAULT NULL,
  `imagen` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `nombre`, `descripcion`, `precio`, `stock`, `categoria_id`, `imagen`, `fecha_creacion`) VALUES
(8, 'Libro \"Estrategias Avanzadas\"', 'Análisis de partidas históricas y técnicas para jugadores intermedios/avanzados.', 29.99, 12, 3, 'prod_68506b624ebb90.35695292.png', '2025-06-16 17:56:27'),
(9, 'Juego de Piezas de Vidrio', 'Piezas translúcidas con diseño moderno, incluye estuche acolchado.', 45.99, 7, 1, 'default.jpg', '2025-06-16 17:57:43'),
(10, 'Software \"Ajedrez 3D Pro\"', 'Simulador 3D con gráficos realistas y modo realidad virtual (compatible con Oculus).', 39.99, 10, 2, 'default.jpg', '2025-06-16 17:58:10'),
(11, 'Tablero Plegable de Tela', 'Tablero ligero y resistente, fácil de guardar. Incluye piezas plásticas.', 19.99, 25, 1, 'default.jpg', '2025-06-16 17:58:49'),
(12, 'Bolsa de Transporte para Pieza', 'Bolsa acolchada con compartimentos para piezas y tablero. Material impermeable.', 22.50, 30, 5, 'default.jpg', '2025-06-16 18:01:35'),
(13, 'Libro \"Finales Magistrales\"', 'Clásico de Capablanca: 100 finales comentados paso a paso para dominar el juego.', 27.99, 18, 3, 'default.jpg', '2025-06-16 18:02:15'),
(14, 'Set de Ajedrez de Mármol', 'Piezas y tablero esculpidos en mármol italiano, peso equilibrado para torneos.', 149.99, 5, 1, 'default.jpg', '2025-06-16 18:02:47'),
(15, 'Reloj Analógico de Arena', 'Reloj clásico de doble esfera con temporizador mecánico, estilo vintage.', 29.99, 12, 1, 'default.jpg', '2025-06-16 18:03:08'),
(16, 'Ajedrez Portátil para Viaje', 'Set compacto (15x15 cm) con piezas imantadas y caja metálica resistente.', 42.99, 15, 1, 'default.jpg', '2025-06-16 18:03:39'),
(17, 'Curso en DVD \"Tácticas Rápidas\"', '5 horas de vídeo con jaques mates en 3 jugadas y trampas típicas.', 15.99, 20, 2, 'default.jpg', '2025-06-16 18:04:10'),
(18, 'Piezas Iluminadas (LED)', 'Piezas con base LED (7 colores), ideal para partidas nocturnas o exhibiciones.', 89.99, 8, 1, 'default.jpg', '2025-06-16 18:04:31'),
(19, 'Tablero Electrónico con IA', 'Tablero inteligente que sugiere jugadas y conecta a plataformas online (Stockfish integrado).', 199.99, 6, 1, 'default.jpg', '2025-06-16 18:04:58'),
(20, 'Libro \"Aperturas Modernas\"', 'Actualizado 2023: cubre variantes hipermodernas y sistemas híbridos', 31.99, 10, 3, 'default.jpg', '2025-06-16 18:05:22'),
(21, 'Soporte para Reloj de Ajedrez', 'Soporte ajustable para relojes DGT o analógicos, base antideslizante.', 12.99, 40, 5, 'default.jpg', '2025-06-16 18:05:39'),
(22, 'Software ChessMaster 15', 'Programa con 100 niveles de IA, lecciones interactivas y base de datos de 2M partidas.', 49.99, 15, 2, 'default.jpg', '2025-06-16 18:16:51'),
(23, 'Tablero de Viaje Magnético', 'Tablero plegable con piezas imantadas, perfecto para jugar en movimiento.', 34.99, 20, 1, 'default.jpg', '2025-06-16 18:17:22'),
(24, 'Aperturas de Ajedrez para Principiantes', 'Guía ilustrada con las 50 aperturas esenciales y errores comunes a evitar.', 24.99, 20, 3, 'default.jpg', '2025-06-16 18:17:59'),
(25, 'Reloj Digital DGT 2010', 'Reloj oficial de torneos con pantalla LCD y múltiples modos de tiempo.', 59.99, 8, 1, 'prod_68506f2ae804a2.44573012.png', '2025-06-16 18:18:28'),
(26, 'Piezas Staunton N°5', 'Piezas profesionales torneadas en ébano y boj, estándar FIDE para competiciones.', 129.99, 10, 1, 'prod_68506d7fd47793.53488193.png', '2025-06-16 18:18:51'),
(27, 'Tablero de Madera Clásico', 'Tablero artesanal de roble con piezas talladas a mano, ideal para coleccionistas.', 89.99, 15, 1, 'prod_68506cc3b96f88.46003780.png', '2025-06-16 18:19:16');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicios`
--

CREATE TABLE `servicios` (
  `id` int NOT NULL,
  `nombre` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `descripcion` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `precio_hora` decimal(10,2) DEFAULT NULL,
  `profesor_id` int DEFAULT NULL,
  `imagen` varchar(255) COLLATE utf8mb4_general_ci DEFAULT 'default.jpg'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `servicios`
--

INSERT INTO `servicios` (`id`, `nombre`, `descripcion`, `precio_hora`, `profesor_id`, `imagen`) VALUES
(1, 'Clase para principiantes', 'Aprende los fundamentos del ajedrez', 25.00, 3, 'default.jpg'),
(2, 'Análisis de partidas', 'Mejora tu juego con análisis experto', 40.00, 3, 'default.jpg'),
(3, 'Preparación para torneos', 'Estrategias avanzadas para competición', 35.00, 3, 'default.jpg'),
(5, 'Clase avanzada de finales', 'Dominio de finales de peones, torres y alfiles contrarios. Incluye 20 ejercicios prácticos.', 45.00, 3, NULL),
(6, 'Entrenamiento personalizado', 'Sesiones 1-a-1 con seguimiento semanal y acceso a biblioteca exclusiva de recursos.', 50.00, 3, NULL),
(7, 'Simulador de Torneos', 'Partidas cronometradas con jugadores de nivel similar y análisis post-partida en grupo.', 30.00, 3, NULL),
(8, 'Clase para Niños', 'Método pedagógico con cuentos y juegos. Certificado de progreso mensual.', 20.00, 3, NULL),
(9, 'Análisis de Rivales', 'Estudio de partidas de tu próximo oponente en torneos locales/nacionales.', 55.00, 3, NULL),
(10, 'Taller de Aperturas', 'Deep dive en la Ruy López, Siciliana y India de Rey. Incluye dossier con líneas clave', 40.00, 3, NULL),
(11, 'Psicología en Ajedrez', 'Técnicas para manejar la ansiedad, bloqueos creativos y presión en partidas largas.', 60.00, 3, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `torneos`
--

CREATE TABLE `torneos` (
  `id` int NOT NULL,
  `nombre` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `fecha` datetime NOT NULL,
  `precio_insc` decimal(10,2) DEFAULT '0.00',
  `descripcion` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `max_participantes` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `torneos`
--

INSERT INTO `torneos` (`id`, `nombre`, `fecha`, `precio_insc`, `descripcion`, `max_participantes`) VALUES
(1, 'Torneo Invierno 2025', '2025-12-15 10:00:00', 15.00, NULL, 20),
(2, 'Abierto MasterChess', '2026-01-20 09:30:00', 0.00, 'Torneo Abierto oficial de la comunidad MasterChess', 250),
(4, 'Torneo Relámpago', '2025-07-10 16:00:00', 5.00, '', 200),
(5, 'Abierto de otoño', '2025-09-15 17:00:00', 0.00, 'Torneo Abierto para clasificación a los torneos internacionales', 500),
(6, 'Campeonato Juvenil', '2025-10-20 15:00:00', 5.00, 'Campeonato juvenil para principiantes', 150);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int NOT NULL,
  `nombre` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `rol` enum('admin','cliente','profesor') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'cliente',
  `telefono` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nivel_ajedrez` enum('principiante','intermedio','avanzado','experto') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `fecha_registro` datetime DEFAULT CURRENT_TIMESTAMP,
  `fecha_ultimo_login` datetime DEFAULT NULL,
  `token_recuperacion` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `expiracion_token` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `email`, `password`, `rol`, `telefono`, `nivel_ajedrez`, `fecha_registro`, `fecha_ultimo_login`, `token_recuperacion`, `expiracion_token`) VALUES
(1, 'Admin Master', 'admin@masterchess.com', '$2y$10$sxYBObfVOAUpKWbiQKWIn.TVUSCG8fC./HXg6YWXUnBh.aACWw2MW', 'admin', '', 'experto', '2025-06-10 19:35:57', '2025-06-16 19:57:15', NULL, NULL),
(2, 'Iago', 'iago@masterchess.com', '$2y$10$37m8NV3eWkIQqN5rMH3Dset4wh2JNW5Eu9rR/bHDesc.DekLF.1iS', 'admin', NULL, 'experto', '2025-06-10 19:35:57', '2025-06-10 19:45:08', NULL, NULL),
(3, 'GM Pérez', 'profesor@masterchess.com', '$2y$10$CgU.pifoKlvH4Et4zPslkeoThqA5iy6y.iBbfduBEXnboFRaZLs9G', 'profesor', NULL, 'experto', '2025-06-10 19:35:57', NULL, NULL, NULL),
(4, 'Ana García', 'ana@email.com', '$2y$10$tkn7XDLLJDl3VvEJp.nam.28GA3ECQTd4RZEkObz/DjVBMFp2qYGW', 'cliente', NULL, 'intermedio', '2025-06-10 19:35:57', NULL, NULL, NULL),
(5, 'Carlos López', 'carlos@email.com', '$2y$10$QRSYo9IAygIJoOTUXCb9yOeEpvJYe1tZWuvWB0PIRsMyudA3Kp3VG', 'cliente', NULL, 'principiante', '2025-06-10 19:35:57', NULL, NULL, NULL),
(6, 'ab', 'ab@masterchess.com', '$2y$10$AjCF.LuNRNPTuQr8leH93OFnMNZjQGxtodEff9MVh9hvcQpEeVDLu', 'cliente', '', 'principiante', '2025-06-10 19:41:10', '2025-06-16 19:53:51', NULL, NULL),
(7, 'profe 1', 'profe1@masterchess.com', '$2y$10$a3t6K1lCxXkPYBpey7zACu7x8ybLn7E2LEvFUwE99TUDWlHsQ.2H6', 'profesor', '', 'avanzado', '2025-06-10 20:16:57', '2025-06-10 20:17:06', NULL, NULL),
(10, 'dani', 'p@masterchess.com', '$2y$10$gZxOmmS5wdfrVWC5uR6EB.Kbc0AylpFcnITWBwBsXrP0wOkX.NU12', 'cliente', NULL, NULL, '2025-06-16 19:57:09', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `valoraciones`
--

CREATE TABLE `valoraciones` (
  `id` int NOT NULL,
  `producto_id` int DEFAULT NULL,
  `usuario_id` int DEFAULT NULL,
  `puntuacion` tinyint NOT NULL,
  `comentario` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `fecha` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_pedidos`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vista_pedidos` (
`id` int
,`cliente` varchar(100)
,`fecha` datetime
,`total` decimal(10,2)
,`estado` enum('pendiente','procesando','enviado','entregado','cancelado')
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_productos_disponibles`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vista_productos_disponibles` (
`id` int
,`nombre` varchar(100)
,`precio` decimal(10,2)
,`categoria` varchar(50)
);

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_pedidos`
--
DROP TABLE IF EXISTS `vista_pedidos`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_pedidos`  AS SELECT `p`.`id` AS `id`, `u`.`nombre` AS `cliente`, `p`.`fecha` AS `fecha`, `p`.`total` AS `total`, `p`.`estado` AS `estado` FROM (`pedidos` `p` join `usuarios` `u` on((`p`.`usuario_id` = `u`.`id`))) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_productos_disponibles`
--
DROP TABLE IF EXISTS `vista_productos_disponibles`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_productos_disponibles`  AS SELECT `p`.`id` AS `id`, `p`.`nombre` AS `nombre`, `p`.`precio` AS `precio`, `c`.`nombre` AS `categoria` FROM (`productos` `p` join `categorias` `c` on((`p`.`categoria_id` = `c`.`id`))) WHERE (`p`.`stock` > 0) ;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `carritos`
--
ALTER TABLE `carritos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `detalles_pedido`
--
ALTER TABLE `detalles_pedido`
  ADD PRIMARY KEY (`id`),
  ADD KEY `producto_id` (`producto_id`),
  ADD KEY `idx_detalles_pedido` (`pedido_id`,`producto_id`);

--
-- Indices de la tabla `inscripciones`
--
ALTER TABLE `inscripciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `torneo_id` (`torneo_id`),
  ADD KEY `servicio_id` (`servicio_id`);

--
-- Indices de la tabla `items_carrito`
--
ALTER TABLE `items_carrito`
  ADD PRIMARY KEY (`id`),
  ADD KEY `carrito_id` (`carrito_id`),
  ADD KEY `producto_id` (`producto_id`);

--
-- Indices de la tabla `mensajes_contacto`
--
ALTER TABLE `mensajes_contacto`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_pedido_usuario` (`usuario_id`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_producto_categoria` (`categoria_id`);

--
-- Indices de la tabla `servicios`
--
ALTER TABLE `servicios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `profesor_id` (`profesor_id`);

--
-- Indices de la tabla `torneos`
--
ALTER TABLE `torneos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indices de la tabla `valoraciones`
--
ALTER TABLE `valoraciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `producto_id` (`producto_id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `carritos`
--
ALTER TABLE `carritos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `detalles_pedido`
--
ALTER TABLE `detalles_pedido`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `inscripciones`
--
ALTER TABLE `inscripciones`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `items_carrito`
--
ALTER TABLE `items_carrito`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `mensajes_contacto`
--
ALTER TABLE `mensajes_contacto`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT de la tabla `servicios`
--
ALTER TABLE `servicios`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `torneos`
--
ALTER TABLE `torneos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `valoraciones`
--
ALTER TABLE `valoraciones`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `carritos`
--
ALTER TABLE `carritos`
  ADD CONSTRAINT `carritos_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `detalles_pedido`
--
ALTER TABLE `detalles_pedido`
  ADD CONSTRAINT `detalles_pedido_ibfk_1` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`),
  ADD CONSTRAINT `detalles_pedido_ibfk_2` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`);

--
-- Filtros para la tabla `inscripciones`
--
ALTER TABLE `inscripciones`
  ADD CONSTRAINT `inscripciones_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `inscripciones_ibfk_2` FOREIGN KEY (`torneo_id`) REFERENCES `torneos` (`id`),
  ADD CONSTRAINT `inscripciones_ibfk_3` FOREIGN KEY (`servicio_id`) REFERENCES `servicios` (`id`);

--
-- Filtros para la tabla `items_carrito`
--
ALTER TABLE `items_carrito`
  ADD CONSTRAINT `items_carrito_ibfk_1` FOREIGN KEY (`carrito_id`) REFERENCES `carritos` (`id`),
  ADD CONSTRAINT `items_carrito_ibfk_2` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`);

--
-- Filtros para la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `pedidos_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `productos_ibfk_1` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`);

--
-- Filtros para la tabla `servicios`
--
ALTER TABLE `servicios`
  ADD CONSTRAINT `servicios_ibfk_1` FOREIGN KEY (`profesor_id`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `valoraciones`
--
ALTER TABLE `valoraciones`
  ADD CONSTRAINT `valoraciones_ibfk_1` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`),
  ADD CONSTRAINT `valoraciones_ibfk_2` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);
COMMIT;

ALTER TABLE mensajes_contacto
ADD COLUMN usuario_id INT NULL,
ADD COLUMN respondido BOOLEAN DEFAULT FALSE,
ADD COLUMN admin_asignado_id INT NULL,
ADD COLUMN fecha_respuesta DATETIME,
ADD COLUMN respuesta TEXT,
ADD COLUMN prioridad ENUM('baja', 'normal', 'alta', 'urgente') DEFAULT 'normal',
ADD COLUMN categoria ENUM('consulta', 'soporte', 'queja', 'sugerencia', 'otros') DEFAULT 'consulta',
ADD COLUMN ip_origen VARCHAR(45);

-- Relaciones con la tabla usuarios
ALTER TABLE mensajes_contacto
ADD CONSTRAINT fk_mensaje_usuario FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE SET NULL,
ADD CONSTRAINT fk_mensaje_admin FOREIGN KEY (admin_asignado_id) REFERENCES usuarios(id) ON DELETE SET NULL;

-- ========================================
-- FUNCIONES PERSONALIZADAS
-- ========================================

DELIMITER //

-- Función para calcular total del carrito
CREATE FUNCTION calcular_total_carrito(carritoID INT) RETURNS DECIMAL(10,2)
READS SQL DATA
DETERMINISTIC
BEGIN
    DECLARE total DECIMAL(10,2) DEFAULT 0.00;

    SELECT COALESCE(SUM(p.precio * i.cantidad), 0.00)
    INTO total
    FROM items_carrito i
    JOIN productos p ON p.id = i.producto_id
    WHERE i.carrito_id = carritoID;

    RETURN total;
END //

-- Función para promedio de valoraciones
CREATE FUNCTION promedio_valoraciones(prodID INT) RETURNS DECIMAL(3,2)
READS SQL DATA
DETERMINISTIC
BEGIN
    DECLARE promedio DECIMAL(3,2) DEFAULT 0.00;

    SELECT COALESCE(AVG(puntuacion), 0.00)
    INTO promedio
    FROM valoraciones
    WHERE producto_id = prodID;

    RETURN promedio;
END //

DELIMITER ;

-- ========================================
-- PROCEDIMIENTOS ALMACENADOS
-- ========================================

DELIMITER //

-- Procedimiento: Crear pedido desde carrito
CREATE PROCEDURE sp_crear_pedido(
    IN p_usuario_id INT,
    IN p_direccion_envio TEXT
)
BEGIN
    DECLARE v_carrito_id INT;
    DECLARE v_total DECIMAL(10,2);
    DECLARE v_pedido_id INT;
    DECLARE done INT DEFAULT FALSE;
    DECLARE v_producto_id INT;
    DECLARE v_cantidad INT;
    DECLARE v_precio DECIMAL(10,2);

    -- Cursor para items del carrito
    DECLARE cur_items CURSOR FOR
        SELECT ic.producto_id, ic.cantidad, p.precio
        FROM items_carrito ic
        JOIN productos p ON ic.producto_id = p.id
        WHERE ic.carrito_id = v_carrito_id;

    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;

    START TRANSACTION;

    -- Obtener carrito del usuario
    SELECT id INTO v_carrito_id
    FROM carritos
    WHERE usuario_id = p_usuario_id
    LIMIT 1;

    IF v_carrito_id IS NULL THEN
        ROLLBACK;
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'No se encontró carrito para el usuario';
    END IF;

    -- Calcular total
    SET v_total = calcular_total_carrito(v_carrito_id);

    IF v_total = 0 THEN
        ROLLBACK;
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'El carrito está vacío';
    END IF;

    -- Crear pedido
    INSERT INTO pedidos (usuario_id, total, direccion_envio)
    VALUES (p_usuario_id, v_total, p_direccion_envio);

    SET v_pedido_id = LAST_INSERT_ID();

    -- Transferir items del carrito al pedido
    OPEN cur_items;
    read_loop: LOOP
        FETCH cur_items INTO v_producto_id, v_cantidad, v_precio;
        IF done THEN
            LEAVE read_loop;
        END IF;

        INSERT INTO detalles_pedido (pedido_id, producto_id, cantidad, precio_unitario)
        VALUES (v_pedido_id, v_producto_id, v_cantidad, v_precio);
    END LOOP;
    CLOSE cur_items;

    -- Limpiar carrito
    DELETE FROM items_carrito WHERE carrito_id = v_carrito_id;

    COMMIT;

    SELECT v_pedido_id AS pedido_id, v_total AS total;
END //

DELIMITER ;

-- ========================================
-- TRIGGERS (DISPARADORES)
-- ========================================

DELIMITER //

-- Trigger: Reducir stock al crear detalle de pedido
CREATE TRIGGER trg_reducir_stock
AFTER INSERT ON detalles_pedido
FOR EACH ROW
BEGIN
    UPDATE productos
    SET stock = stock - NEW.cantidad
    WHERE id = NEW.producto_id;
END //

-- Trigger: Alerta por stock bajo
CREATE TRIGGER trg_stock_bajo
AFTER UPDATE ON productos
FOR EACH ROW
BEGIN
    IF NEW.stock < 5 AND NEW.stock != OLD.stock THEN
        INSERT INTO mensajes_contacto (nombre, email, asunto, mensaje)
        VALUES ('Sistema', 'sistema@masterchess.com', 'Stock bajo',
                CONCAT('El producto "', NEW.nombre, '" tiene solo ', NEW.stock, ' unidades en stock.'));
    END IF;
END //

-- ========================================
-- VISTAS BASADAS EN SUBCONSULTAS
-- ========================================

-- Vista: Productos con precio superior al promedio
CREATE VIEW vista_productos_caros AS
SELECT *
FROM productos
WHERE precio > (
    SELECT AVG(precio)
    FROM productos
);

-- Vista: Usuarios con al menos un pedido
CREATE VIEW vista_usuarios_con_pedidos AS
SELECT *
FROM usuarios
WHERE id IN (
    SELECT usuario_id
    FROM pedidos
    GROUP BY usuario_id
);

-- Vista: Clientes que han gastado más de 100€
CREATE VIEW vista_clientes_top_gasto AS
SELECT nombre, (
    SELECT SUM(total)
    FROM pedidos p
    WHERE p.usuario_id = u.id
) AS total_gastado
FROM usuarios u
WHERE (
    SELECT SUM(total)
    FROM pedidos p
    WHERE p.usuario_id = u.id
) > 100;

-- Vista: Productos con valoración media superior a 4
CREATE VIEW vista_productos_bien_valorados AS
SELECT *
FROM productos
WHERE id IN (
    SELECT producto_id
    FROM valoraciones
    GROUP BY producto_id
    HAVING AVG(puntuacion) > 4
);

-- Vista: Categorías con más de 3 productos activos
CREATE VIEW vista_categorias_populares AS
SELECT *
FROM categorias
WHERE id IN (
    SELECT categoria_id
    FROM productos
    WHERE activo = TRUE
    GROUP BY categoria_id
    HAVING COUNT(*) > 3
);
DELIMITER ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
