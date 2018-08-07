-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 21, 2018 at 01:09 AM
-- Server version: 10.1.25-MariaDB
-- PHP Version: 7.1.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u487508290_aaaaa`
--

-- --------------------------------------------------------

--
-- Table structure for table `ascientos_disponibles`
--

CREATE TABLE `ascientos_disponibles` (
  `id` int(11) NOT NULL,
  `cantidad` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `ascientos_disponibles`
--

INSERT INTO `ascientos_disponibles` (`id`, `cantidad`) VALUES
(1, '1'),
(2, '2'),
(3, '3'),
(4, '4'),
(5, 'N/A');

-- --------------------------------------------------------

--
-- Table structure for table `calificaciones`
--

CREATE TABLE `calificaciones` (
  `id` int(11) NOT NULL,
  `nombre` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `calificaciones`
--

INSERT INTO `calificaciones` (`id`, `nombre`) VALUES
(1, 'Muy mala'),
(2, 'Mala'),
(3, 'Normal'),
(4, 'Buena'),
(5, 'Muy buena');

-- --------------------------------------------------------

--
-- Table structure for table `empleados`
--

CREATE TABLE `empleados` (
  `id` int(11) NOT NULL,
  `email` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `perfil` int(11) NOT NULL,
  `suspendido` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `empleados`
--

INSERT INTO `empleados` (`id`, `email`, `password`, `perfil`, `suspendido`) VALUES
(1, 'encargado001@remiseria.com', '25021544', 2, 0),
(2, 'cliente01@remiseria.com', '45678921', 1, 0),
(3, 'cliente02@remiseria.com', '42512354', 1, 0),
(4, 'suspendido001@remiseria.com', 'suspendido001@remiseria.com', 1, 1),
(5, 'remiserio001@remiseria.com', '111', 3, 0),
(13, 'remiserio002@remiseria.com', '293874', 3, 0),
(15, 'remiserio003@remiseria.com', '11111111111111111111', 3, 0),
(16, 'asfdsad', 'asdasd', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `encuestas`
--

CREATE TABLE `encuestas` (
  `id` int(11) NOT NULL,
  `comportamiento_conductor` int(11) DEFAULT NULL,
  `conversacion_conductor` int(11) DEFAULT NULL,
  `puntualidad_conductor` int(11) DEFAULT NULL,
  `limpieza_vehiculo` int(11) DEFAULT NULL,
  `estado_vehiculo` int(11) DEFAULT NULL,
  `duracion_viaje` int(11) DEFAULT NULL,
  `calificacion_servicio` int(11) DEFAULT NULL,
  `recomendaria_servicio` tinyint(1) DEFAULT '0',
  `foto01` varchar(200) COLLATE utf8_unicode_ci DEFAULT 'placeholder',
  `foto02` varchar(200) COLLATE utf8_unicode_ci DEFAULT 'placeholder',
  `foto03` varchar(200) COLLATE utf8_unicode_ci DEFAULT 'placeholder',
  `viaje` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `encuestas`
--

INSERT INTO `encuestas` (`id`, `comportamiento_conductor`, `conversacion_conductor`, `puntualidad_conductor`, `limpieza_vehiculo`, `estado_vehiculo`, `duracion_viaje`, `calificacion_servicio`, `recomendaria_servicio`, `foto01`, `foto02`, `foto03`, `viaje`) VALUES
(1, 5, 2, 4, 5, 3, 5, 4, 1, 'test01', 'test02', 'test03', 2),
(2, 4, 3, 2, 5, 4, 1, 3, 1, 'Test04', 'Test05', 'Test06', 10);

-- --------------------------------------------------------

--
-- Table structure for table `estado_viaje`
--

CREATE TABLE `estado_viaje` (
  `id` int(11) NOT NULL,
  `estado` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `estado_viaje`
--

INSERT INTO `estado_viaje` (`id`, `estado`) VALUES
(1, 'Solicitado'),
(2, 'Realizado'),
(3, 'Cargando el pago'),
(4, 'Cuenta corriente'),
(5, 'Cancelado');

-- --------------------------------------------------------

--
-- Table structure for table `medios_de_pago`
--

CREATE TABLE `medios_de_pago` (
  `id` int(11) NOT NULL,
  `nombre` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `medios_de_pago`
--

INSERT INTO `medios_de_pago` (`id`, `nombre`) VALUES
(1, 'Efectivo'),
(2, 'Tarjeta'),
(3, 'Cuenta corriente');

-- --------------------------------------------------------

--
-- Table structure for table `nivel_comodidad`
--

CREATE TABLE `nivel_comodidad` (
  `id` int(11) NOT NULL,
  `nombre` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `nivel_comodidad`
--

INSERT INTO `nivel_comodidad` (`id`, `nombre`) VALUES
(1, 'Bajo'),
(2, 'Medio'),
(3, 'Alto'),
(4, 'N/A');

-- --------------------------------------------------------

--
-- Table structure for table `perfiles`
--

CREATE TABLE `perfiles` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `perfiles`
--

INSERT INTO `perfiles` (`id`, `nombre`) VALUES
(1, 'cliente'),
(2, 'encargado'),
(3, 'remisero');

-- --------------------------------------------------------

--
-- Table structure for table `vehiculos`
--

CREATE TABLE `vehiculos` (
  `id` int(11) NOT NULL,
  `id_remisero` int(11) DEFAULT NULL,
  `nivel_comodidad` int(11) DEFAULT NULL,
  `ascientos_disponibles` int(11) DEFAULT NULL,
  `suspendido` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `vehiculos`
--

INSERT INTO `vehiculos` (`id`, `id_remisero`, `nivel_comodidad`, `ascientos_disponibles`, `suspendido`) VALUES
(1, 5, 1, 3, 1),
(2, 5, 2, 3, 0),
(3, 5, 3, 4, 0),
(4, 13, 2, 2, 0),
(5, 13, 1, 1, 0),
(7, 13, 3, 3, 0),
(9, 15, 3, 3, 0);

-- --------------------------------------------------------

--
-- Table structure for table `viajes`
--

CREATE TABLE `viajes` (
  `id` int(11) NOT NULL,
  `estado_viaje` int(11) NOT NULL DEFAULT '1',
  `id_chofer` int(11) DEFAULT NULL,
  `id_cliente` int(11) NOT NULL,
  `fecha_hora_viaje` datetime NOT NULL,
  `origen` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `destino` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `medio_de_pago` int(11) NOT NULL,
  `comodidad_solicitada` int(11) DEFAULT '4',
  `cantidad_de_ascientos_solicitados` int(11) DEFAULT '5',
  `costo` decimal(20,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `viajes`
--

INSERT INTO `viajes` (`id`, `estado_viaje`, `id_chofer`, `id_cliente`, `fecha_hora_viaje`, `origen`, `destino`, `medio_de_pago`, `comodidad_solicitada`, `cantidad_de_ascientos_solicitados`, `costo`) VALUES
(1, 1, 5, 2, '2018-06-11 14:52:38', 'Caballito', 'La Plata', 1, 3, 4, NULL),
(2, 2, 5, 2, '2018-06-11 14:54:46', 'Caballito', 'La Plata', 2, 3, 4, '1042.40'),
(3, 4, 5, 2, '2018-06-11 14:54:46', 'Caballito', 'La Plata', 3, 2, 3, '43.11'),
(4, 3, 5, 2, '2018-06-11 14:54:46', 'Caballito', 'La Plata', 3, 1, 2, '652.20'),
(5, 1, 13, 2, '2018-06-11 14:54:46', 'Caballito', 'La Plata', 1, 1, 2, '0.00'),
(6, 5, 13, 2, '2018-06-11 14:54:46', 'Caballito', 'La Plata', 1, 2, 3, '0.00'),
(7, 1, NULL, 3, '2018-06-12 21:31:10', 'La plata', 'Caballito', 1, 4, 5, '0.00'),
(8, 1, NULL, 3, '2018-06-12 21:33:06', 'La plata', 'Caballito', 2, 4, 5, '0.00'),
(10, 2, 5, 2, '2018-06-11 14:54:46', 'Tigre', 'Lugano', 1, 4, 5, '562.21');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ascientos_disponibles`
--
ALTER TABLE `ascientos_disponibles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `calificaciones`
--
ALTER TABLE `calificaciones`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `empleados`
--
ALTER TABLE `empleados`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `email_2` (`email`),
  ADD KEY `perfil` (`perfil`);

--
-- Indexes for table `encuestas`
--
ALTER TABLE `encuestas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `comportamiento_conductor` (`comportamiento_conductor`),
  ADD KEY `conversacion_conductor` (`conversacion_conductor`),
  ADD KEY `puntualidad_conductor` (`puntualidad_conductor`),
  ADD KEY `limpieza_vehiculo` (`limpieza_vehiculo`),
  ADD KEY `estado_vehiculo` (`estado_vehiculo`),
  ADD KEY `duracion_viaje` (`duracion_viaje`),
  ADD KEY `calificacion_servicio` (`calificacion_servicio`),
  ADD KEY `viaje` (`viaje`);

--
-- Indexes for table `estado_viaje`
--
ALTER TABLE `estado_viaje`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `medios_de_pago`
--
ALTER TABLE `medios_de_pago`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `nivel_comodidad`
--
ALTER TABLE `nivel_comodidad`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `perfiles`
--
ALTER TABLE `perfiles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indexes for table `vehiculos`
--
ALTER TABLE `vehiculos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_remisero` (`id_remisero`),
  ADD KEY `nivel_comodidad` (`nivel_comodidad`),
  ADD KEY `ascientos_disponibles` (`ascientos_disponibles`);

--
-- Indexes for table `viajes`
--
ALTER TABLE `viajes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `medio_de_pago` (`medio_de_pago`),
  ADD KEY `comodidad_solicitada` (`comodidad_solicitada`),
  ADD KEY `cantidad_de_ascientos_solicitados` (`cantidad_de_ascientos_solicitados`),
  ADD KEY `viajes_ibfk_1` (`estado_viaje`),
  ADD KEY `viajes_ibfk_2` (`id_chofer`),
  ADD KEY `viajes_ibfk_3` (`id_cliente`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ascientos_disponibles`
--
ALTER TABLE `ascientos_disponibles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `calificaciones`
--
ALTER TABLE `calificaciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `empleados`
--
ALTER TABLE `empleados`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT for table `encuestas`
--
ALTER TABLE `encuestas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `estado_viaje`
--
ALTER TABLE `estado_viaje`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `medios_de_pago`
--
ALTER TABLE `medios_de_pago`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `nivel_comodidad`
--
ALTER TABLE `nivel_comodidad`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `perfiles`
--
ALTER TABLE `perfiles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `vehiculos`
--
ALTER TABLE `vehiculos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `viajes`
--
ALTER TABLE `viajes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `empleados`
--
ALTER TABLE `empleados`
  ADD CONSTRAINT `empleados_ibfk_3` FOREIGN KEY (`perfil`) REFERENCES `perfiles` (`id`);

--
-- Constraints for table `encuestas`
--
ALTER TABLE `encuestas`
  ADD CONSTRAINT `encuestas_ibfk_1` FOREIGN KEY (`comportamiento_conductor`) REFERENCES `calificaciones` (`id`),
  ADD CONSTRAINT `encuestas_ibfk_2` FOREIGN KEY (`conversacion_conductor`) REFERENCES `calificaciones` (`id`),
  ADD CONSTRAINT `encuestas_ibfk_3` FOREIGN KEY (`puntualidad_conductor`) REFERENCES `calificaciones` (`id`),
  ADD CONSTRAINT `encuestas_ibfk_4` FOREIGN KEY (`limpieza_vehiculo`) REFERENCES `calificaciones` (`id`),
  ADD CONSTRAINT `encuestas_ibfk_5` FOREIGN KEY (`estado_vehiculo`) REFERENCES `calificaciones` (`id`),
  ADD CONSTRAINT `encuestas_ibfk_6` FOREIGN KEY (`duracion_viaje`) REFERENCES `calificaciones` (`id`),
  ADD CONSTRAINT `encuestas_ibfk_7` FOREIGN KEY (`calificacion_servicio`) REFERENCES `calificaciones` (`id`),
  ADD CONSTRAINT `encuestas_ibfk_8` FOREIGN KEY (`viaje`) REFERENCES `viajes` (`id`);

--
-- Constraints for table `vehiculos`
--
ALTER TABLE `vehiculos`
  ADD CONSTRAINT `vehiculos_ibfk_1` FOREIGN KEY (`id_remisero`) REFERENCES `empleados` (`id`),
  ADD CONSTRAINT `vehiculos_ibfk_2` FOREIGN KEY (`nivel_comodidad`) REFERENCES `nivel_comodidad` (`ID`),
  ADD CONSTRAINT `vehiculos_ibfk_3` FOREIGN KEY (`ascientos_disponibles`) REFERENCES `ascientos_disponibles` (`ID`);

--
-- Constraints for table `viajes`
--
ALTER TABLE `viajes`
  ADD CONSTRAINT `viajes_ibfk_1` FOREIGN KEY (`estado_viaje`) REFERENCES `estado_viaje` (`ID`),
  ADD CONSTRAINT `viajes_ibfk_2` FOREIGN KEY (`id_chofer`) REFERENCES `empleados` (`id`),
  ADD CONSTRAINT `viajes_ibfk_3` FOREIGN KEY (`id_cliente`) REFERENCES `empleados` (`id`),
  ADD CONSTRAINT `viajes_ibfk_4` FOREIGN KEY (`medio_de_pago`) REFERENCES `medios_de_pago` (`ID`),
  ADD CONSTRAINT `viajes_ibfk_5` FOREIGN KEY (`comodidad_solicitada`) REFERENCES `nivel_comodidad` (`ID`),
  ADD CONSTRAINT `viajes_ibfk_6` FOREIGN KEY (`cantidad_de_ascientos_solicitados`) REFERENCES `ascientos_disponibles` (`ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
