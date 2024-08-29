-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 14-08-2024 a las 07:35:34
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
-- Estructura de tabla para la tabla `detalles_compra`
--

CREATE TABLE `detalles_compra` (
  `ID` int(11) NOT NULL,
  `id_historial` int(11) NOT NULL,
  `IDP` int(11) NOT NULL,
  `Cantidad` int(11) NOT NULL,
  `Precio` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(1, 32, 'Efectivo', 4, '2024-06-24 05:41:39', 'Entregado', 13, 40.00),
(3, 39, 'Efectivo', 5, '2024-06-24 06:01:48', 'Entregado', 12, 40.00),
(4, 39, 'Efectivo', 1, '2024-06-24 06:07:31', 'Entregado', 12, 40.00),
(5, 37, 'Tarjeta', 4, '2024-06-24 14:53:06', 'Entregado', 11, 7.76),
(6, 37, 'Tarjeta', 2, '2024-06-24 18:40:37', 'Entregado', 13, 4.20),
(7, 37, 'Efectivo', 5, '2024-06-24 21:26:26', 'Entregado', 12, 15.00),
(8, 37, 'Efectivo', 1, '2024-06-25 13:08:00', 'Entregado', 29, 7.00),
(9, 37, 'Efectivo', 2, '2024-06-25 13:11:27', 'Entregado', 17, 16.00),
(10, 37, 'Efectivo', 2, '2024-06-25 13:37:11', 'Entregado', 14, 4.00),
(11, 37, 'Efectivo', 2, '2024-06-25 13:37:20', 'Entregado', 21, 12.00),
(12, 37, 'Efectivo', 2, '2024-06-25 13:38:27', 'Entregado', 16, 8.00),
(13, 41, 'Tarjeta', 6, '2024-06-25 13:44:54', 'Entregado', 18, 240.00),
(14, 41, 'Efectivo', 2, '2024-06-25 13:52:42', 'Entregado', 14, 4.00),
(15, 41, 'Efectivo', 1, '2024-06-25 13:52:49', 'Entregado', 16, 4.00),
(16, 37, 'Efectivo', 1, '2024-06-26 08:23:46', 'Entregado', 21, 6.00),
(17, 37, 'Efectivo', 1, '2024-06-26 08:23:46', 'Entregado', 13, 2.10),
(20, 41, 'Efectivo', 1, '2024-06-27 00:38:30', 'Entregado', 13, 2.10),
(21, 41, 'Efectivo', 1, '2024-06-27 00:40:31', 'Pendiente', 13, 3.00),
(22, 32, 'Efectivo', 1, '2024-06-27 04:21:14', 'Entregado', 13, 40.00),
(26, 32, 'Efectivo', 1, '2024-06-27 04:35:28', 'Entregado', 13, 40.00),
(27, 32, 'Efectivo', 1, '2024-06-27 04:36:59', 'Entregado', 13, 40.00),
(29, 32, 'Efectivo', 1, '2024-06-27 04:41:36', 'Entregado', 13, 40.00),
(30, 32, 'Efectivo', 1, '2024-06-27 04:43:45', 'Entregado', 13, 40.00),
(34, 32, 'Efectivo', 1, '2024-06-27 04:48:46', 'Entregado', 13, 40.00),
(41, 37, 'Efectivo', 1, '2024-06-27 13:17:03', 'Entregado', 12, 3.00),
(42, 45, 'Efectivo', 10, '2024-06-27 14:25:44', 'Pendiente', 28, 20.00),
(43, 45, 'Efectivo', 5, '2024-06-27 14:25:56', 'Pendiente', 12, 15.00),
(44, 37, 'Efectivo', 4, '2024-06-27 14:40:58', 'Pendiente', 16, 16.00),
(45, 37, 'Efectivo', 11, '2024-06-27 14:41:08', 'Pendiente', 26, 38.50),
(46, 39, 'Efectivo', 1, '2024-07-13 23:40:16', 'Entregado', 12, 40.00),
(48, 39, 'Efectivo', 1, '2024-07-13 17:47:18', 'Entregado', 38, 40.00),
(49, 39, 'Efectivo', 1, '2024-07-13 23:40:16', 'Entregado', 39, 40.00),
(50, 39, 'Efectivo', 1, '2024-07-13 23:40:16', 'Entregado', 19, 40.00),
(51, 39, 'Efectivo', 1, '2024-07-13 23:40:16', 'Entregado', 28, 40.00),
(52, 39, 'Efectivo', 1, '2024-07-16 10:57:09', 'Entregado', 12, 40.00),
(53, 39, 'Efectivo', 1, '2024-07-16 10:57:09', 'Entregado', 38, 40.00),
(54, 39, 'Efectivo', 1, '2024-07-16 10:57:09', 'Entregado', 11, 40.00),
(55, 39, 'Efectivo', 1, '2024-07-16 11:27:23', 'Entregado', 19, 40.00),
(56, 39, 'Efectivo', 1, '2024-07-16 11:27:23', 'Entregado', 21, 40.00),
(57, 39, 'Efectivo', 1, '2024-07-16 11:27:23', 'Entregado', 18, 40.00),
(58, 39, 'Efectivo', 1, '2024-07-19 11:43:27', 'Entregado', 38, 40.00),
(59, 39, 'Efectivo', 1, '2024-08-05 20:56:18', 'Entregado', 13, 40.00),
(60, 39, 'Efectivo', 1, '2024-08-05 20:56:18', 'Entregado', 16, 40.00),
(61, 44, 'Efectivo', 1, '2024-07-19 12:28:11', 'Pendiente', 17, 8.00),
(62, 44, 'Efectivo', 1, '2024-07-19 12:28:11', 'Pendiente', 18, 40.00),
(63, 44, 'Efectivo', 7, '2024-07-19 12:29:15', 'Pendiente', 18, 280.00),
(64, 48, 'Efectivo', 1, '2024-07-19 13:02:47', 'Pendiente', 38, 8.70),
(65, 48, 'Efectivo', 1, '2024-07-19 13:02:47', 'Entregado', 11, 1.94),
(66, 37, 'Efectivo', 1, '2024-07-22 22:23:39', 'Pendiente', 14, 2.00),
(67, 37, 'Tarjeta', 2, '2024-08-04 02:26:27', 'Pendiente', 12, 6.00),
(68, 39, 'Transferencia', 1, '2024-07-24 02:17:28', 'Entregado', 12, 40.00),
(69, 43, 'Efectivo', 1, '2024-07-24 11:33:44', 'Pendiente', 33, 2.50),
(70, 43, 'Efectivo', 6, '2024-07-24 11:33:44', 'Pendiente', 16, 24.00),
(71, 43, 'Tarjeta', 1, '2024-07-24 11:33:44', 'Pendiente', 39, 6.00),
(72, 43, 'Efectivo', 1, '2024-07-24 11:33:44', 'Pendiente', 12, 3.00),
(73, 43, 'Efectivo', 4, '2024-07-24 11:33:44', 'Pendiente', 12, 12.00),
(74, 43, 'Efectivo', 2, '2024-07-24 11:33:44', 'Pendiente', 39, 12.00),
(75, 43, 'Efectivo', 2, '2024-07-24 11:33:44', 'Pendiente', 16, 8.00),
(76, 43, 'Efectivo', 2, '2024-07-24 11:33:44', 'Pendiente', 24, 2.00),
(77, 37, 'Efectivo', 1, '2024-07-24 15:27:40', 'Entregado', 12, 3.00),
(78, 37, 'Efectivo', 1, '2024-07-24 15:27:48', 'Entregado', 12, 3.00),
(79, 37, 'Transferencia', 1, '2024-08-04 02:26:27', 'Pendiente', 12, 3.00),
(80, 32, 'Efectivo', 1, '2024-07-29 14:30:09', 'Entregado', 11, 40.00),
(81, 56, 'Efectivo', 1, '2024-07-30 11:36:12', 'Entregado', 13, 2.10),
(82, 56, 'Efectivo', 1, '2024-07-30 11:36:12', 'Entregado', 38, 8.70),
(84, 37, 'Efectivo', 1, '2024-08-04 02:26:27', 'Pendiente', 13, 2.10),
(85, 37, 'Efectivo', 1, '2024-08-04 02:28:56', 'Pendiente', 13, 2.10),
(86, 37, 'Efectivo', 1, '2024-08-04 02:29:50', 'Pendiente', 13, 2.10),
(87, 37, 'Efectivo', 1, '2024-08-04 02:43:01', 'Pendiente', 13, 2.10),
(88, 37, 'Efectivo', 1, '2024-08-04 02:43:23', 'Pendiente', 13, 2.10),
(89, 37, 'Efectivo', 1, '2024-08-04 02:48:20', 'Pendiente', 13, 2.10),
(90, 37, 'Efectivo', 1, '2024-08-04 02:48:32', 'Pendiente', 13, 2.10),
(91, 37, 'Efectivo', 1, '2024-08-04 02:48:58', 'Pendiente', 38, 8.70),
(92, 37, 'Efectivo', 1, '2024-08-04 02:49:08', 'Pendiente', 38, 8.70),
(93, 37, 'Efectivo', 1, '2024-08-04 02:49:42', 'Pendiente', 23, 5.00),
(94, 37, 'Efectivo', 1, '2024-08-04 02:49:42', 'Pendiente', 22, 7.00),
(95, 37, 'Efectivo', 1, '2024-08-04 02:49:42', 'Pendiente', 19, 10.00),
(96, 37, 'Efectivo', 1, '2024-08-04 02:50:16', 'Pendiente', 13, 2.10),
(97, 37, 'Efectivo', 1, '2024-08-04 02:53:17', 'Pendiente', 38, 8.70),
(101, 39, 'Efectivo', 1, '2024-08-05 20:56:18', 'Entregado', 33, 40.00),
(103, 39, 'Efectivo', 20, '2024-08-05 20:59:46', 'Entregado', 40, 40.00),
(104, 32, 'Efectivo', 7, '2024-08-06 02:53:22', 'Entregado', 23, 40.00),
(105, 32, 'Efectivo', 20, '2024-08-06 02:53:22', 'Entregado', 55, 40.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `imagenes`
--

CREATE TABLE `imagenes` (
  `id` int(11) NOT NULL,
  `imagen` varchar(255) NOT NULL,
  `tipo` varchar(255) NOT NULL,
  `fecha_subida` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `imagenes`
--

INSERT INTO `imagenes` (`id`, `imagen`, `tipo`, `fecha_subida`) VALUES
(7, 'descuento1.png', 'carrusel', '2024-08-14 05:26:45'),
(8, 'descuento2.png', 'carrusel', '2024-08-14 05:27:07'),
(9, 'descuento3.png', 'carrusel', '2024-08-14 05:27:15'),
(10, 'descuento4.png', 'carrusel', '2024-08-14 05:27:25'),
(11, 'descuento5.png', 'carrusel', '2024-08-14 05:27:41'),
(13, 'anun1.png', 'promocion', '2024-08-14 05:28:48'),
(14, 'aunu2.png', 'promocion', '2024-08-14 05:29:14');

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
  `fecha_de_entrega` timestamp NOT NULL DEFAULT current_timestamp(),
  `usuario_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`IDP`, `Nombre`, `Precio`, `Categoria`, `Cantidad`, `Descripcion`, `Foto`, `Estado`, `fecha_de_entrega`, `usuario_id`) VALUES
(11, 'yogo yogo', 3.50, 'Lacteos', 53, 'Se produce por la fermentación de la leche.', 'la-yogurt yogo yogo.png', 'Aceptado', '2024-04-23 18:07:31', NULL),
(12, 'kumis', 3.00, 'Lacteos', 36, 'Es un producto vacuno derivado y hecho a partir de kéfir de leche.', 'la-KUMIS-ALPINA_F.png', 'Aceptado', '2024-06-24 03:27:04', NULL),
(13, 'Leche', 2.10, 'Lacteos', 5, 'Es el producto de la secreción de las glándulas de las hembras mamíferas', '34127.png', 'Aceptado', '2024-06-24 03:31:14', NULL),
(14, 'Jabón de baño Protex', 2.00, 'Aseo', 40, 'Jabón antibacterial que ayuda a proteger la piel contra bacterias.', 'Protex-Jabonn-Limpieza-Profunda-BARRA-120-GR.jpg', 'Aceptado', '2024-06-25 03:11:01', NULL),
(16, 'Crema dental Colgate Triple Acción', 4.00, 'Aseo', 22, 'Pasta dental que combate las caries, blanquea los dientes y refresca el aliento.', 'colgate.jpg', 'Aceptado', '2024-06-25 03:18:46', NULL),
(17, 'Desodorante Rexona Men o Women', 8.00, 'Aseo', 50, 'Desodorante en aerosol o roll-on que proporciona protección duradera contra el sudor y el mal olor.', 'rexona no te abandona.webp', 'Aceptado', '2024-06-25 03:19:59', NULL),
(18, 'Pañales Huggies Active Sec', 40.00, 'Aseo', 39, 'Pañales desechables para bebés que ofrecen absorción y protección durante horas.', 'pañales.jpg', 'Aceptado', '2024-06-25 03:21:07', NULL),
(19, 'Detergente en polvo Ariel', 10.00, 'Aseo', 47, 'Detergente para ropa que elimina manchas difíciles y deja las prendas limpias y frescas.', 'detegente ariel.webp', 'Aceptado', '2024-06-25 03:22:23', NULL),
(21, 'Acondicionador Pantene', 6.00, 'Aseo', 49, 'Acondicionador que nutre el cabello y lo deja suave y manejable.', 'acodicionador pantene.jpg', 'Aceptado', '2024-06-25 03:34:54', NULL),
(22, 'Papel higiénico Familia Suave', 7.00, 'Aseo', 47, ' Papel higiénico suave y absorbente', 'familia-4-rollos.webp', 'Aceptado', '2024-06-25 03:37:56', NULL),
(23, 'Toallas húmedas Pampers Baby Fresh', 5.00, 'Aseo', 40, 'Toallitas húmedas para bebés que limpian suavemente la piel sensible.', 'toallitas-humedas.jpg', 'Aceptado', '2024-06-25 03:39:28', NULL),
(24, 'Agua cristal', 1.00, 'Bebidas', 50, 'Una botella de agua es un recipiente diseñado para contener y transportar agua potable.', 'Botella-Agua-CRISTAL-.jpg', 'Aceptado', '2024-06-25 03:48:19', NULL),
(25, 'Cerveza aguila lata', 4.00, 'Bebidas', 50, 'Es una de las cervezas más populares y emblemáticas de Colombia, producida por la empresa Bavaria, parte del grupo AB InBev. ', 'cerveza-aguila.png', 'Aceptado', '2024-06-25 03:51:57', NULL),
(26, 'Cerveza poker lata', 3.50, 'Bebidas', -27, 'Es una cerveza colombiana de tipo lager, que se caracteriza por ser refrescante y ligera, con un sabor suave y un perfil equilibrado entre amargor y dulzor.', 'POKERr.jpg', 'Aceptado', '2024-06-25 03:53:52', NULL),
(27, 'Cerveza club colombia lata', 4.00, 'Bebidas', 50, 'Es otra reconocida cerveza colombiana, que también se encuentra disponible en lata. ', 'cerveza_club_colombia_dorada_330ml.webp', 'Aceptado', '2024-06-25 03:55:59', NULL),
(28, 'Coca-Cola', 2.00, 'Bebidas', 50, 'Es una bebida carbonatada mundialmente reconocida, producida por The Coca-Cola Company. ', 'coca-cola-250-ml-01.jpg', 'Aceptado', '2024-06-25 04:00:14', NULL),
(29, 'Monster Energy ', 7.00, 'Bebidas', 49, 'Es una bebida energética muy popular que se comercializa en lata.', 'monster-energy.jpg', 'Aceptado', '2024-06-25 04:10:50', NULL),
(30, 'Pepsi', 3.00, 'Bebidas', 50, 'Es una bebida carbonatada de cola conocida mundialmente, producida por PepsiCo.', 'PEPSI.jpg', 'Aceptado', '2024-06-25 04:13:30', NULL),
(31, 'Speed Max', 2.00, 'Bebidas', 50, 'Es una bebida energética que se comercializa en lata y es popular en Colombia.', NULL, 'No aceptado', '2024-06-25 04:14:48', NULL),
(32, 'Speed Max', 2.00, 'Bebidas', 50, 'Es una bebida energética que se comercializa en lata y es popular en Colombia.', 'speed.jpg', 'Aceptado', '2024-06-25 04:17:15', NULL),
(33, 'Arroz blanco', 2.50, 'Granos', 49, 'Grano básico y fundamental en la dieta colombiana, utilizado como acompañamiento en muchos platos.', 'Arroz-Blanco-_a.webp', 'Aceptado', '2024-06-25 11:50:55', NULL),
(34, 'Frijol cargamanto', 3.00, 'Granos', 50, 'Tipo de frijol grande, de color blanco, típico en la cocina colombiana para hacer sopas y estofados.6.000 pesos por kilogramo', 'frijol-cargamanto-blanco.png', 'Aceptado', '2024-06-25 12:14:50', NULL),
(36, 'Maíz trillado', 2.50, 'Granos', 50, 'Tipo de maíz partido y secado, utilizado en la preparación de arepas y otros platos tradicionales colombianos.5.000 pesos por kilogramo.', 'maiz trillado.jpeg', 'Aceptado', '2024-06-25 12:19:32', NULL),
(37, 'Trigo', 3.00, 'Granos', 50, 'Cereal fundamental en la producción de harina para panadería y pastelería. 6.000 pesos por kilogramo.', 'trigo.jpg', 'Aceptado', '2024-06-25 12:22:03', NULL),
(38, 'Queso fresco', 8.70, 'Lacteos', 40, 'Queso blanco y suave, de textura cremosa, utilizado en arepas, empanadas y como complemento en muchos platos colombianos.', 'queso.jpeg', 'Aceptado', '2024-06-25 12:27:59', NULL),
(39, 'Mantequilla', 6.00, 'Lacteos', 50, 'Producto lácteo obtenido de la nata de la leche, utilizado para untar en panes y en la cocina.', 'mantequilla.webp', 'Aceptado', '2024-06-25 12:29:06', NULL),
(40, 'De todito', 7.90, 'Granos', 0, 'Mezcla de papa, plátano y  chicharrón sabor natural.', 'queso.jpeg', 'Aceptado', '2024-06-25 15:34:39', NULL),
(46, 'Zanahoria', 1.00, 'Vegetales', 20, 'Es un muy buen alimento para mejorar la vista ', 'ZANAHORIA.png', 'Aceptado', '2024-06-26 17:06:31', NULL),
(53, 'Mango', 1.50, 'Frutas', 15, 'Mango es bueno para los jugos.', 'Mango-Tommy-Atkins.jpg', 'Pendiente', '2024-06-27 12:18:33', NULL),
(54, 'papitas', 5.00, 'nuevo<3', 200, 'las papitas ricas de papito', '20231012_090611.jpg', 'Pendiente', '2024-07-19 12:21:11', NULL),
(55, 'agua', 2.00, 'Bebidas', 0, 'agua es agua', 'agua.txt', 'Aceptado', '2024-07-30 11:52:52', NULL),
(56, 'Mango', 1.50, 'Frutas', 25, '', 'Mango-Tommy-Atkins.jpg', 'Pendiente', '2024-08-06 04:03:32', 34),
(57, 'D-Todito', 3.00, 'Embutidos', 25, 'De Todito BBQ es un snack que contiene platanitos, papas y chicharrón americano con un toque picante en tu boca.', 'detodito-fritolay-mix-x-165-g-1.webp', 'Pendiente', '2024-08-06 04:10:41', 34);

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
  `id_usuario` int(11) DEFAULT NULL,
  `id_proveedor` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `solicitudes`
--

INSERT INTO `solicitudes` (`id_solicitud`, `nombre`, `categoria`, `cantidad`, `descripcion`, `estado`, `fecha_solicitud`, `id_usuario`, `id_proveedor`) VALUES
(1, 'Chocolatinas jet', 'Lacteos', 50, '', 'Entregado', '2024-06-24 01:03:15', 39, NULL),
(12, 'mango', 'fruta', 15, '', 'Entregado', '2024-06-27 12:16:03', 41, NULL),
(16, 'Ema', NULL, NULL, 'ayudame', 'Listo', '2024-06-27 13:03:51', 37, NULL),
(17, 'Emanuel', NULL, NULL, 'afafafaf', 'Listo', '2024-06-27 13:17:31', 37, NULL),
(18, 'Esteban 11', NULL, NULL, 'color mal', 'Listo', '2024-07-30 11:37:22', 56, NULL),
(19, 'agua', 'Bebidas', 20, '', 'Entregado', '2024-07-30 11:48:59', 41, NULL),
(20, 'coco', 'Frutas', 20, 'nose', 'Entregado', '2024-08-02 11:37:12', 41, 52),
(21, 'nesquik', 'Bebidas', 20, '', 'Entregado', '2024-08-06 03:48:36', 41, 34);

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
(37, 'ema321', 'Ema Tosteles', 'emamercado832@gmail.com', '3177569159', '5f6955d227a320c7f1f6c7da2a6d96a851a8118f', 2, NULL, 'si', 'photo_2024-06-22_18-36-48.jpg'),
(39, 'Estebas23', 'Etebitas', 'estebas@gamil.com', '2323234', 'f37be93b674e3dcd988cba4a7cf66879468c3b35', 4, NULL, 'si', NULL),
(40, 'ema11', 'Ema', 'Arameho832@gamil.com', '3176677602', '011c945f30ce2cbafc452f39840f025693339c42', 2, NULL, 'si', NULL),
(41, 'KK17', 'Kakaroto', 'Ka13@gamil.com', '320', 'af2941a60e26a34c22aac84a3165a175b835f1e3', 5, NULL, 'si', NULL),
(42, 'jevl2', 'Jhon', 'Kaka14@gamil.com', '1234234', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 4, NULL, 'si', NULL),
(43, 'Esteban12', 'Esteban', 'Esteban12@gmail.com', '3135553', '618dcdfb0cd9ae4481164961c4796dd8e3930c8d', 1, NULL, 'si', NULL),
(44, 'Giorgi15', 'Giorgi', 'Giorgi15@gmail.com', '31343434', '7581f9f7cb4e2c129cf3994be96f620fca5eb4c0', 1, NULL, 'si', NULL),
(45, 'jhon3', 'Jhon', 'jhon@gmail.com', '311456', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 2, NULL, 'si', NULL),
(48, 'estebann', 'este', 'este@gmail.com', '3214943428', '656178bcd875725daa7308e458436739fee21b7a', 3, NULL, 'si', NULL),
(51, 'Manuela', 'Manuela', 'Eho832@gamil.com', '3176677602', '5a1135177de8ff3baab8315b705936ed679e503b', 2, NULL, 'si', NULL),
(52, 'coco57', 'coco', 'shanel@gmail.com', '3204076604', '34762cecf96e793ca6b8800a4ca9f37a5ce29961', 3, NULL, 'si', NULL),
(53, 'estebandido', 'Esteban33', 'esteban111@gmail.com', '1212', '8cb2237d0679ca88db6464eac60da96345513964', 2, NULL, 'si', NULL),
(54, 'david12', 'david', 'david11@gmail.com', '3204076605', '618dcdfb0cd9ae4481164961c4796dd8e3930c8d', 3, NULL, 'si', NULL),
(55, 'gg34', 'gg', 'nanim2110@gmail.com', '2121', 'c55c508614dd2a3e2ca2a00250dc33fb924a7244', 2, NULL, 'si', NULL),
(56, 'esteban11', 'Emanuel', 'Arameho832@gamil.com', '3176677602', '011c945f30ce2cbafc452f39840f025693339c42', 2, NULL, 'si', NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`Nombre_Categoria`);

--
-- Indices de la tabla `detalles_compra`
--
ALTER TABLE `detalles_compra`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `id_historial` (`id_historial`),
  ADD KEY `IDP` (`IDP`);

--
-- Indices de la tabla `historial_ventas`
--
ALTER TABLE `historial_ventas`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `fk_idp` (`IDP`),
  ADD KEY `fk_historial_usuarios` (`id_usuario`);

--
-- Indices de la tabla `imagenes`
--
ALTER TABLE `imagenes`
  ADD PRIMARY KEY (`id`);

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
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `fk_id_proveedor` (`id_proveedor`);

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
-- AUTO_INCREMENT de la tabla `detalles_compra`
--
ALTER TABLE `detalles_compra`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `historial_ventas`
--
ALTER TABLE `historial_ventas`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=106;

--
-- AUTO_INCREMENT de la tabla `imagenes`
--
ALTER TABLE `imagenes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `IDP` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT de la tabla `promociones`
--
ALTER TABLE `promociones`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de la tabla `solicitudes`
--
ALTER TABLE `solicitudes`
  MODIFY `id_solicitud` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de la tabla `tipos_usuarios`
--
ALTER TABLE `tipos_usuarios`
  MODIFY `id_tipos` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `detalles_compra`
--
ALTER TABLE `detalles_compra`
  ADD CONSTRAINT `detalles_compra_ibfk_1` FOREIGN KEY (`id_historial`) REFERENCES `historial_ventas` (`ID`),
  ADD CONSTRAINT `detalles_compra_ibfk_2` FOREIGN KEY (`IDP`) REFERENCES `productos` (`IDP`);

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
  ADD CONSTRAINT `fk_id_proveedor` FOREIGN KEY (`id_proveedor`) REFERENCES `usuarios` (`id_usuario`),
  ADD CONSTRAINT `solicitudes_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
