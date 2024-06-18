-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 18-06-2024 a las 23:17:01
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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial_ventas`
--

CREATE TABLE `historial_ventas` (
  `ID` int(11) NOT NULL,
  `Cliente` varchar(100) NOT NULL,
  `Direccion` varchar(255) DEFAULT NULL,
  `Cantidad` int(11) DEFAULT NULL,
  `Fecha` date DEFAULT NULL,
  `Estado` varchar(20) NOT NULL DEFAULT 'Pendiente',
  `IDP` int(11) NOT NULL,
  `Total` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `Foto` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `aceptado` varchar(2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `usuario`, `nombre`, `correo_electronico`, `telefono`, `contraseña_usuario`, `id_tipos`, `codigo_recuperacion`, `aceptado`) VALUES
(32, 'emanuel11', 'Emanuel', 'emanuelherrera832@gmail.com', '3176677602', 'cc2aa911d81435e96c1ca78fb80dc2c0cd084ee3', 1, NULL, 'si'),
(33, 'yaya', 'Dayanna', 'saavedr@gmail.com', '4076604', '618dcdfb0cd9ae4481164961c4796dd8e3930c8d', 2, NULL, 'si'),
(34, 'Lucy12', 'Lucia', 'olacalderon12@gmail.com', '3204076604', 'f04a8305cf42ecb7bd5b110adab57ce9f68af30c', 3, NULL, 'si'),
(35, 'jevl', 'Jhon', 'jhon@sena.edu.co', '1234556', 'ab874467a7d1ff5fc71a4ade87dc0e098b458aae', 2, NULL, 'si'),
(37, 'ema321', 'Ema', 'emamerca832@gmail.com', '3177569157', '533958a2148aa8a3466fa3a8340d51e28294f41d', 2, NULL, 'si'),
(38, 'NN14', 'NN', 'NN@gmail.com', '1414', 'fbce66f99c809283638f344ecb3d50674ea64189', 4, NULL, 'si'),
(39, 'Estebas23', 'Etebitas', 'estebas@gamil.com', '2323', 'f37be93b674e3dcd988cba4a7cf66879468c3b35', 5, NULL, 'si');

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
  ADD KEY `fk_idp` (`IDP`);

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
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `IDP` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tipos_usuarios`
--
ALTER TABLE `tipos_usuarios`
  MODIFY `id_tipos` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `historial_ventas`
--
ALTER TABLE `historial_ventas`
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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
