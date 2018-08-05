-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 25-04-2018 a las 06:01:50
-- Versión del servidor: 10.1.16-MariaDB
-- Versión de PHP: 7.0.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `angular`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `partidas`
--

CREATE TABLE `partidas` (
  `id` int(11) NOT NULL,
  `datos` varchar(8192) DEFAULT NULL,
  `juego` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `clave` varchar(255) NOT NULL,
  `cuit` int(11) NOT NULL,
  `sexo` varchar(1) NOT NULL DEFAULT 'M',
  `gano` varchar(64) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `email`, `clave`, `cuit`, `sexo`, `gano`) VALUES
(1, 'admin', 'admin', '1234', 7000971, 'M', NULL),
(2, 'Pepe Argento', 'pepe_arg@gg.org', '1234', 25741632, 'M', 'ganador'),
(3, 'Juana de Arco', 'joan_d_arc@gg.net', '1234', 34876024, 'f', NULL),
(4, 'Fefe', 'fef_1@gg.net', '1234', 654789321, 'm', 'No'),
(5, 'ultima sepia', 'ultima@server.net', '1234', 65478903, 'f', 'No'),
(6, 'Chavelo', 'Nievas', '1234', 6497582, 'm', 'No'),
(7, 'Scooby Doo papa', 'papascooby@gg.lol', '1234', 654789321, 'm', 'No');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `partidas`
--
ALTER TABLE `partidas`
  ADD KEY `id` (`id`),
  ADD KEY `juego` (`juego`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`),
  ADD UNIQUE KEY `id_2` (`id`,`email`),
  ADD KEY `id` (`id`,`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `partidas`
--
ALTER TABLE `partidas`
  ADD CONSTRAINT `partidas_ibfk_1` FOREIGN KEY (`id`) REFERENCES `usuarios` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
