-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 11-06-2024 a las 15:41:49
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.2.4

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
-- Estructura de tabla para la tabla `producto`
--

CREATE TABLE `producto` (
  `id_prod` int(15) NOT NULL,
  `nombre_prod` varchar(100) NOT NULL,
  `precio_prod` int(25) NOT NULL,
  `tipologia_prod` varchar(100) NOT NULL,
  `des_det_prod` varchar(500) NOT NULL,
  `img_prod` longblob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

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
(2, 'Usuario'),
(3, 'Proveedor'),
(4, 'Gerente'),
(5, 'Empleado'),
(6, 'Cliente');

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
(32, 'emanuel11', 'Emanuel', 'emanuelherrera832@gmail.com', '3176677602', '6216f8a75fd5bb3d5f22b6f9958cdede3fc086c2', 1, NULL, 'si'),
(33, 'yaya', 'Dayanna', 'saavedr@gmail.com', '4076604', '618dcdfb0cd9ae4481164961c4796dd8e3930c8d', 2, NULL, 'si'),
(34, 'Lucy12', 'Lucia', 'olacalderon12@gmail.com', '3204076604', '7419673903e9bfd912a49fdce8f409519602d30f', 3, NULL, 'si'),
(35, 'jevl', 'Jhon', 'jhon@sena.edu.co', '1234556', 'ab874467a7d1ff5fc71a4ade87dc0e098b458aae', 2, NULL, 'si');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `ingresos_usuarios`
--
ALTER TABLE `ingresos_usuarios`
  ADD PRIMARY KEY (`id_ingreso`),
  ADD KEY `id_tipos` (`id_tipos`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `producto`
--
ALTER TABLE `producto`
  ADD PRIMARY KEY (`id_prod`);

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
-- AUTO_INCREMENT de la tabla `producto`
--
ALTER TABLE `producto`
  MODIFY `id_prod` int(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `tipos_usuarios`
--
ALTER TABLE `tipos_usuarios`
  MODIFY `id_tipos` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `ingresos_usuarios`
--
ALTER TABLE `ingresos_usuarios`
  ADD CONSTRAINT `ingresos_usuarios_ibfk_1` FOREIGN KEY (`id_tipos`) REFERENCES `tipos_usuarios` (`id_tipos`),
  ADD CONSTRAINT `ingresos_usuarios_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`);

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `fk_id_tipos` FOREIGN KEY (`id_tipos`) REFERENCES `tipos_usuarios` (`id_tipos`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
