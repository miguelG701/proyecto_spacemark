-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 05-07-2024 a las 17:56:13
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.1.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `spacemark`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `Nombre_Categoria` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`Nombre_Categoria`) VALUES
('Aseo'),
('Bebidas'),
('Embutidos'),
('Frutas'),
('Granos'),
('Lacteos'),
('nnn'),
('nuevo<3'),
('Vegetales');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial_ventas`
--

CREATE TABLE `historial_ventas` (
  `ID` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `Metodo_pago` varchar(50) DEFAULT NULL,
  `Cantidad` int(11) DEFAULT NULL,
  `Fecha` timestamp NULL DEFAULT current_timestamp(),
  `Estado` varchar(20) NOT NULL DEFAULT 'Pendiente',
  `IDP` int(11) NOT NULL,
  `Total` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `historial_ventas`
--

INSERT INTO `historial_ventas` (`ID`, `id_usuario`, `Metodo_pago`, `Cantidad`, `Fecha`, `Estado`, `IDP`, `Total`) VALUES
(1, 32, 'Efectivo', 4, '2024-06-24 05:41:39', 'Entregado', 13, 13.60),
(3, 39, 'Efectivo', 5, '2024-06-24 06:01:48', 'Entregado', 12, 7.50),
(4, 39, 'Efectivo', 1, '2024-06-24 06:07:31', 'Entregado', 12, 3.00),
(5, 37, 'Tarjeta', 2, '2024-06-24 14:53:06', 'Entregado', 11, 5.00),
(6, 37, 'Tarjeta', 2, '2024-06-24 18:40:37', 'Entregado', 13, 4.20),
(7, 37, 'Efectivo', 5, '2024-06-24 21:26:26', 'Entregado', 12, 15.00),
(8, 37, 'Efectivo', 1, '2024-06-25 13:08:00', 'Entregado', 29, 7.00),
(9, 37, 'Efectivo', 2, '2024-06-25 13:11:27', 'Entregado', 17, 16.00),
(10, 37, 'Efectivo', 2, '2024-06-25 13:37:11', 'Entregado', 14, 4.00),
(11, 37, 'Efectivo', 2, '2024-06-25 13:37:20', 'Entregado', 21, 12.00),
(12, 37, 'Efectivo', 2, '2024-06-25 13:38:27', 'Pendiente', 16, 8.00),
(13, 41, 'Tarjeta', 6, '2024-06-25 13:44:54', 'Pendiente', 18, 240.00),
(14, 41, 'Efectivo', 2, '2024-06-25 13:52:42', 'Entregado', 14, 4.00),
(15, 41, 'Efectivo', 1, '2024-06-25 13:52:49', 'Pendiente', 16, 4.00),
(16, 37, 'Efectivo', 1, '2024-06-26 08:23:46', 'Pendiente', 21, 6.00),
(17, 37, 'Efectivo', 1, '2024-06-26 16:10:35', 'Pendiente', 13, 3.40),
(20, 41, 'Efectivo', 1, '2024-06-27 00:38:30', 'Pendiente', 13, 3.00),
(21, 41, 'Efectivo', 1, '2024-06-27 00:40:31', 'Pendiente', 13, 3.00),
(22, 32, 'Efectivo', 1, '2024-06-27 04:21:14', 'Pendiente', 13, 3.00),
(26, 32, 'Efectivo', 1, '2024-06-27 04:35:28', 'Pendiente', 13, 3.00),
(27, 32, 'Efectivo', 1, '2024-06-27 04:36:59', 'Pendiente', 13, 3.00),
(29, 32, 'Efectivo', 1, '2024-06-27 04:41:36', 'Pendiente', 13, 3.00),
(30, 32, 'Efectivo', 1, '2024-06-27 04:43:45', 'Pendiente', 13, 3.00),
(34, 32, 'Efectivo', 1, '2024-06-27 04:48:46', 'Pendiente', 13, 3.00),
(41, 37, 'Efectivo', 1, '2024-06-27 13:17:03', 'Pendiente', 12, 3.00),
(42, 45, 'Efectivo', 10, '2024-06-27 14:25:44', 'Pendiente', 28, 20.00),
(43, 45, 'Efectivo', 5, '2024-06-27 14:25:56', 'Pendiente', 12, 15.00),
(44, 37, 'Efectivo', 4, '2024-06-27 14:40:58', 'En Carrito', 16, 16.00),
(45, 37, 'Efectivo', 11, '2024-06-27 14:41:08', 'En Carrito', 26, 38.50);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ingresos_usuarios`
--

CREATE TABLE `ingresos_usuarios` (
  `id_ingreso` int(11) NOT NULL,
  `id_tipos` int(11) DEFAULT NULL,
  `id_usuario` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Disparadores `ingresos_usuarios`
--
DELIMITER $$
CREATE TRIGGER `registrar_ingreso` AFTER INSERT ON `ingresos_usuarios` FOR EACH ROW BEGIN
    INSERT INTO ingresos_respaldo (id_ingreso, id_usuario, id_tipos, hora_accedio)
    VALUES (NEW.id_ingreso, NEW.id_usuario, NEW.id_tipos, NOW());
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `IDP` int(11) NOT NULL,
  `Nombre` varchar(255) NOT NULL,
  `Precio` decimal(10,2) NOT NULL,
  `Categoria` varchar(255) DEFAULT NULL,
  `Cantidad` int(11) DEFAULT NULL,
  `Descripcion` text DEFAULT NULL,
  `Foto` varchar(255) DEFAULT NULL,
  `Estado` varchar(20) NOT NULL DEFAULT 'Pendiente',
  `fecha_de_entrega` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`IDP`, `Nombre`, `Precio`, `Categoria`, `Cantidad`, `Descripcion`, `Foto`, `Estado`, `fecha_de_entrega`) VALUES
(11, 'yogo yogo', 1.94, 'Lacteos', 55, 'Se produce por la fermentación de la leche.', 'la-yogurt yogo yogo.png', 'Aceptado', '2024-04-23 18:07:31'),
(12, 'kumis', 3.00, 'Lacteos', 11, 'Es un producto vacuno derivado y hecho a partir de kéfir de leche.', 'la-KUMIS-ALPINA_F.png', 'Aceptado', '2024-06-24 03:27:04'),
(13, 'Leche', 2.10, 'Lacteos', 51, 'Es el producto de la secreción de las glándulas de las hembras mamíferas', '34127.png', 'Aceptado', '2024-06-24 03:31:14'),
(14, 'Jabón de baño Protex', 2.00, 'Aseo', 48, 'Jabón antibacterial que ayuda a proteger la piel contra bacterias.', 'Protex-Jabonn-Limpieza-Profunda-BARRA-120-GR.jpg', 'Aceptado', '2024-06-25 03:11:01'),
(16, 'Crema dental Colgate Triple Acción', 4.00, 'Aseo', 50, 'Pasta dental que combate las caries, blanquea los dientes y refresca el aliento.', 'colgate.jpg', 'Aceptado', '2024-06-25 03:18:46'),
(17, 'Desodorante Rexona Men o Women', 8.00, 'Aseo', 49, 'Desodorante en aerosol o roll-on que proporciona protección duradera contra el sudor y el mal olor.', 'rexona no te abandona.webp', 'Aceptado', '2024-06-25 03:19:59'),
(18, 'Pañales Huggies Active Sec', 40.00, 'Aseo', 39, 'Pañales desechables para bebés que ofrecen absorción y protección durante horas.', 'pañales.jpg', 'Aceptado', '2024-06-25 03:21:07'),
(19, 'Detergente en polvo Ariel', 10.00, 'Aseo', 50, 'Detergente para ropa que elimina manchas difíciles y deja las prendas limpias y frescas.', 'detegente ariel.webp', 'Aceptado', '2024-06-25 03:22:23'),
(21, 'Acondicionador Pantene', 6.00, 'Aseo', 49, 'Acondicionador que nutre el cabello y lo deja suave y manejable.', 'acodicionador pantene.jpg', 'Aceptado', '2024-06-25 03:34:54'),
(22, 'Papel higiénico Familia Suave', 7.00, 'Aseo', 50, ' Papel higiénico suave y absorbente', 'familia-4-rollos.webp', 'Aceptado', '2024-06-25 03:37:56'),
(23, 'Toallas húmedas Pampers Baby Fresh', 5.00, 'Aseo', 50, 'Toallitas húmedas para bebés que limpian suavemente la piel sensible.', 'toallitas-humedas.jpg', 'Aceptado', '2024-06-25 03:39:28'),
(24, 'Agua cristal', 1.00, 'Bebidas', 50, 'Una botella de agua es un recipiente diseñado para contener y transportar agua potable.', 'Botella-Agua-CRISTAL-.jpg', 'Aceptado', '2024-06-25 03:48:19'),
(25, 'Cerveza aguila lata', 4.00, 'Bebidas', 50, 'Es una de las cervezas más populares y emblemáticas de Colombia, producida por la empresa Bavaria, parte del grupo AB InBev. ', 'cerveza-aguila.png', 'Aceptado', '2024-06-25 03:51:57'),
(26, 'Cerveza poker lata', 3.50, 'Bebidas', 50, 'Es una cerveza colombiana de tipo lager, que se caracteriza por ser refrescante y ligera, con un sabor suave y un perfil equilibrado entre amargor y dulzor.', 'POKERr.jpg', 'Aceptado', '2024-06-25 03:53:52'),
(27, 'Cerveza club colombia lata', 4.00, 'Bebidas', 50, 'Es otra reconocida cerveza colombiana, que también se encuentra disponible en lata. ', 'cerveza_club_colombia_dorada_330ml.webp', 'Aceptado', '2024-06-25 03:55:59'),
(28, 'Coca-Cola', 2.00, 'Bebidas', 50, 'Es una bebida carbonatada mundialmente reconocida, producida por The Coca-Cola Company. ', 'coca-cola-250-ml-01.jpg', 'Aceptado', '2024-06-25 04:00:14'),
(29, 'Monster Energy ', 7.00, 'Bebidas', 49, 'Es una bebida energética muy popular que se comercializa en lata.', 'monster-energy.jpg', 'Aceptado', '2024-06-25 04:10:50'),
(30, 'Pepsi', 3.00, 'Bebidas', 50, 'Es una bebida carbonatada de cola conocida mundialmente, producida por PepsiCo.', 'PEPSI.jpg', 'Aceptado', '2024-06-25 04:13:30'),
(31, 'Speed Max', 2.00, 'Bebidas', 50, 'Es una bebida energética que se comercializa en lata y es popular en Colombia.', NULL, 'No aceptado', '2024-06-25 04:14:48'),
(32, 'Speed Max', 2.00, 'Bebidas', 50, 'Es una bebida energética que se comercializa en lata y es popular en Colombia.', 'speed.jpg', 'Aceptado', '2024-06-25 04:17:15'),
(33, 'Arroz blanco', 2.50, 'Granos', 50, 'Grano básico y fundamental en la dieta colombiana, utilizado como acompañamiento en muchos platos.', 'Arroz-Blanco-_a.webp', 'Aceptado', '2024-06-25 11:50:55'),
(34, 'Frijol cargamanto', 3.00, 'Granos', 50, 'Tipo de frijol grande, de color blanco, típico en la cocina colombiana para hacer sopas y estofados.6.000 pesos por kilogramo', 'frijol-cargamanto-blanco.png', 'Aceptado', '2024-06-25 12:14:50'),
(36, 'Maíz trillado', 2.50, 'Granos', 50, 'Tipo de maíz partido y secado, utilizado en la preparación de arepas y otros platos tradicionales colombianos.5.000 pesos por kilogramo.', 'maiz trillado.jpeg', 'Aceptado', '2024-06-25 12:19:32'),
(37, 'Trigo', 3.00, 'Granos', 50, 'Cereal fundamental en la producción de harina para panadería y pastelería. 6.000 pesos por kilogramo.', 'trigo.jpg', 'Aceptado', '2024-06-25 12:22:03'),
(38, 'Queso fresco', 8.70, 'Lacteos', 50, 'Queso blanco y suave, de textura cremosa, utilizado en arepas, empanadas y como complemento en muchos platos colombianos.', 'queso.jpeg', 'Aceptado', '2024-06-25 12:27:59'),
(39, 'Mantequilla', 6.00, 'Lacteos', 50, 'Producto lácteo obtenido de la nata de la leche, utilizado para untar en panes y en la cocina.', 'mantequilla.webp', 'Aceptado', '2024-06-25 12:29:06'),
(40, 'De todito', 7.90, 'Granos', 20, 'Mezcla de papa, plátano y  chicharrón sabor natural.', 'queso.jpeg', 'Aceptado', '2024-06-25 15:34:39'),
(46, 'Zanahoria', 1.00, 'Vegetales', 20, 'Es un muy buen alimento para mejorar la vista ', 'ZANAHORIA.png', 'Aceptado', '2024-06-26 17:06:31'),
(53, 'Mango', 1.50, 'Frutas', 15, 'Mango es bueno para los jugos.', 'Mango-Tommy-Atkins.jpg', 'Pendiente', '2024-06-27 12:18:33');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `promociones`
--

CREATE TABLE `promociones` (
  `ID` int(11) NOT NULL,
  `ID_Producto` int(11) DEFAULT NULL,
  `Descuento` decimal(5,2) DEFAULT NULL,
  `Fecha_Inicio` date DEFAULT NULL,
  `Fecha_Final` date DEFAULT NULL,
  `Estado` varchar(20) DEFAULT 'Activa',
  `Precio_Normal` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `solicitudes`
--

CREATE TABLE `solicitudes` (
  `id_solicitud` int(11) NOT NULL,
  `nombre` varchar(255) DEFAULT NULL,
  `categoria` varchar(255) DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `estado` varchar(20) DEFAULT 'Pendiente',
  `fecha_solicitud` timestamp NOT NULL DEFAULT current_timestamp(),
  `id_usuario` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `solicitudes`
--

INSERT INTO `solicitudes` (`id_solicitud`, `nombre`, `categoria`, `cantidad`, `descripcion`, `estado`, `fecha_solicitud`, `id_usuario`) VALUES
(1, 'Chocolatinas jet', 'Lacteos', 50, '', 'Entregado', '2024-06-24 01:03:15', 39),
(12, 'mango', 'fruta', 15, '', 'Pendiente', '2024-06-27 12:16:03', 41),
(15, 'ema', NULL, NULL, 'ayudame', 'En proceso', '2024-06-27 12:40:08', 37),
(16, 'Ema', NULL, NULL, 'ayudame', 'Pendiente', '2024-06-27 13:03:51', 37),
(17, 'Emanuel', NULL, NULL, 'afafafaf', 'Pendiente', '2024-06-27 13:17:31', 37);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipos_usuarios`
--

CREATE TABLE `tipos_usuarios` (
  `id_tipos` int(11) NOT NULL,
  `nom_tipos` varchar(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipos_usuarios`
--

INSERT INTO `tipos_usuarios` (`id_tipos`, `nom_tipos`) VALUES
(1, 'Administrad'),
(2, 'Cliente'),
(3, 'Proveedor'),
(4, 'Empleado'),
(5, 'Gerente');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `usuario` varchar(20) DEFAULT NULL,
  `nombre` varchar(20) DEFAULT NULL,
  `correo_electronico` varchar(30) DEFAULT NULL,
  `telefono` varchar(15) DEFAULT NULL,
  `contraseña_usuario` varchar(100) DEFAULT NULL,
  `id_tipos` int(11) DEFAULT NULL,
  `codigo_recuperacion` varchar(255) DEFAULT NULL,
  `aceptado` varchar(2) DEFAULT NULL,
  `Foto` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `usuario`, `nombre`, `correo_electronico`, `telefono`, `contraseña_usuario`, `id_tipos`, `codigo_recuperacion`, `aceptado`, `Foto`) VALUES
(32, 'emanuel11', 'Emanuel', 'emanuelherrera832@gmail.com', '3176677602', 'cc2aa911d81435e96c1ca78fb80dc2c0cd084ee3', 1, NULL, 'si', NULL),
(33, 'yaya', 'Dayanna', 'saavedr@gmail.com', '4076604', '618dcdfb0cd9ae4481164961c4796dd8e3930c8d', 4, NULL, 'si', NULL),
(34, 'Lucy12', 'Lucia', 'olacaldero12@gmail.com', '32012138', 'f04a8305cf42ecb7bd5b110adab57ce9f68af30c', 3, NULL, 'si', NULL),
(37, 'ema321', 'Ema Tostole', 'emamercado832@gmail.com', '3177569159', '5f6955d227a320c7f1f6c7da2a6d96a851a8118f', 2, NULL, 'si', 'photo_2024-06-22_18-36-48.jpg'),
(39, 'Estebas23', 'Etebitas', 'estebas@gamil.com', '2323234', 'f37be93b674e3dcd988cba4a7cf66879468c3b35', 4, NULL, 'si', NULL),
(40, 'ema11', 'Ema', 'Arameho832@gamil.com', '3176677602', '011c945f30ce2cbafc452f39840f025693339c42', 2, NULL, 'si', NULL),
(41, 'KK17', 'Kakaroto', 'Ka13@gamil.com', '320', 'af2941a60e26a34c22aac84a3165a175b835f1e3', 5, NULL, 'si', NULL),
(42, 'jevl2', 'Jhon', 'Kaka14@gamil.com', '1234234', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 4, NULL, 'si', NULL),
(43, 'Esteban12', 'Esteban', 'Esteban12@gmail.com', '3135553', '618dcdfb0cd9ae4481164961c4796dd8e3930c8d', 1, NULL, 'si', NULL),
(44, 'Giorgi15', 'Giorgi', 'Giorgi15@gmail.com', '31343434', '7581f9f7cb4e2c129cf3994be96f620fca5eb4c0', 1, NULL, 'si', NULL),
(45, 'jhon3', 'Jhon', 'jhon@gmail.com', '311456', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 2, NULL, 'si', NULL),
(46, 'jevl', 'Dayanna', 'nanim2110@gmail.com', '1212', '618dcdfb0cd9ae4481164961c4796dd8e3930c8d', 4, NULL, 'no', NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`Nombre_Categoria`);

--
-- Indices de la tabla `historial_ventas`
--
ALTER TABLE `historial_ventas`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `fk_idp` (`IDP`),
  ADD KEY `fk_historial_usuarios` (`id_usuario`);

--
-- Indices de la tabla `ingresos_usuarios`
--
ALTER TABLE `ingresos_usuarios`
  ADD PRIMARY KEY (`id_ingreso`),
  ADD KEY `id_tipos` (`id_tipos`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`IDP`),
  ADD KEY `fk_categoria` (`Categoria`);

--
-- Indices de la tabla `promociones`
--
ALTER TABLE `promociones`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `ID_Producto` (`ID_Producto`);

--
-- Indices de la tabla `solicitudes`
--
ALTER TABLE `solicitudes`
  ADD PRIMARY KEY (`id_solicitud`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `tipos_usuarios`
--
ALTER TABLE `tipos_usuarios`
  ADD PRIMARY KEY (`id_tipos`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD KEY `fk_id_tipos` (`id_tipos`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `historial_ventas`
--
ALTER TABLE `historial_ventas`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `IDP` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT de la tabla `promociones`
--
ALTER TABLE `promociones`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `solicitudes`
--
ALTER TABLE `solicitudes`
  MODIFY `id_solicitud` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `tipos_usuarios`
--
ALTER TABLE `tipos_usuarios`
  MODIFY `id_tipos` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `historial_ventas`
--
ALTER TABLE `historial_ventas`
  ADD CONSTRAINT `fk_historial_usuarios` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`),
  ADD CONSTRAINT `fk_idp` FOREIGN KEY (`IDP`) REFERENCES `productos` (`IDP`);

--
-- Filtros para la tabla `ingresos_usuarios`
--
ALTER TABLE `ingresos_usuarios`
  ADD CONSTRAINT `ingresos_usuarios_ibfk_1` FOREIGN KEY (`id_tipos`) REFERENCES `tipos_usuarios` (`id_tipos`),
  ADD CONSTRAINT `ingresos_usuarios_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`);

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `fk_categoria` FOREIGN KEY (`Categoria`) REFERENCES `categorias` (`Nombre_Categoria`);

--
-- Filtros para la tabla `promociones`
--
ALTER TABLE `promociones`
  ADD CONSTRAINT `promociones_ibfk_1` FOREIGN KEY (`ID_Producto`) REFERENCES `productos` (`IDP`);

--
-- Filtros para la tabla `solicitudes`
--
ALTER TABLE `solicitudes`
  ADD CONSTRAINT `solicitudes_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
