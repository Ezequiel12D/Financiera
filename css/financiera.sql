-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 11-12-2025 a las 21:38:42
-- Versión del servidor: 10.4.24-MariaDB
-- Versión de PHP: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `financiera`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cuentas_bancarias`
--

CREATE TABLE `cuentas_bancarias` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `numero_cuenta` varchar(20) NOT NULL,
  `saldo` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empleados`
--

CREATE TABLE `empleados` (
  `id` int(11) NOT NULL,
  `dni` varchar(15) NOT NULL,
  `nombre_usuario` varchar(255) NOT NULL,
  `contrasena` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `empleados`
--

INSERT INTO `empleados` (`id`, `dni`, `nombre_usuario`, `contrasena`) VALUES
(1, '46752695', 'ezequiel', 'ezequiel.sosa.et26@gmail.com');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos_financieros`
--

CREATE TABLE `productos_financieros` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `descripción` text DEFAULT NULL,
  `tasa_interés` decimal(5,2) NOT NULL,
  `plazo_meses` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `solicitudes_prestamos`
--

CREATE TABLE `solicitudes_prestamos` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `monto_solicitado` decimal(10,2) NOT NULL,
  `estado` enum('pendiente','aprobado','rechazado') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `transacciones`
--

CREATE TABLE `transacciones` (
  `id` int(11) NOT NULL,
  `cuenta_id` int(11) NOT NULL,
  `tipo` enum('depósito','retiro','transferencia') NOT NULL,
  `monto` decimal(10,2) NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `contrasena` varchar(255) DEFAULT NULL,
  `saldo` decimal(10,2) NOT NULL,
  `apellido` varchar(255) NOT NULL,
  `dni` varchar(20) NOT NULL,
  `telefono` varchar(20) NOT NULL,
  `provincia` varchar(50) NOT NULL,
  `fecha_nacimiento` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `email`, `contrasena`, `saldo`, `apellido`, `dni`, `telefono`, `provincia`, `fecha_nacimiento`) VALUES
(15, 'Ezequiel Matias', 'patatososa12@gmail.com', '$2y$10$CqKbLQCDJc3WQf4o.wYRBOwRTR2xLvRWTADwpnH7IyIu3sYMskKfe', '0.00', 'Pérez', '46752695', '1159139848', 'Buenos Aires', '2005-06-07'),
(16, 'Ezequiel Matias', 'Ezequiel.sosa.et26@gmail.com', '$2y$10$O4WYluE8XlNvK/PskG930uBC4Obp/JGprdXuezma.99grdR37ZMs6', '0.00', 'Sosa', '46752695', '1159139848', 'Buenos Aires', '2005-06-07'),
(17, 'Ezequiel Matias', 'camybelensosa@gmail.com', '$2y$10$BSEVzTygM2kPFWhb/kfN9.z7FLlgZ/VtqWudScT9rV4aIO9UpcPB2', '0.00', 'Sosa', '46752695', '1159139848', 'Buenos Aires', '2001-08-08');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cuentas_bancarias`
--
ALTER TABLE `cuentas_bancarias`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `numero_cuenta` (`numero_cuenta`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `empleados`
--
ALTER TABLE `empleados`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `productos_financieros`
--
ALTER TABLE `productos_financieros`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `solicitudes_prestamos`
--
ALTER TABLE `solicitudes_prestamos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `producto_id` (`producto_id`);

--
-- Indices de la tabla `transacciones`
--
ALTER TABLE `transacciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cuenta_id` (`cuenta_id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `cuentas_bancarias`
--
ALTER TABLE `cuentas_bancarias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `empleados`
--
ALTER TABLE `empleados`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `productos_financieros`
--
ALTER TABLE `productos_financieros`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `solicitudes_prestamos`
--
ALTER TABLE `solicitudes_prestamos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `transacciones`
--
ALTER TABLE `transacciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `cuentas_bancarias`
--
ALTER TABLE `cuentas_bancarias`
  ADD CONSTRAINT `cuentas_bancarias_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `solicitudes_prestamos`
--
ALTER TABLE `solicitudes_prestamos`
  ADD CONSTRAINT `solicitudes_prestamos_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `solicitudes_prestamos_ibfk_2` FOREIGN KEY (`producto_id`) REFERENCES `productos_financieros` (`id`);

--
-- Filtros para la tabla `transacciones`
--
ALTER TABLE `transacciones`
  ADD CONSTRAINT `transacciones_ibfk_1` FOREIGN KEY (`cuenta_id`) REFERENCES `cuentas_bancarias` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
