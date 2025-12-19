-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 20-12-2025 a las 00:47:55
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
-- Estructura de tabla para la tabla `cuotas_prestamo`
--

CREATE TABLE `cuotas_prestamo` (
  `id` int(11) NOT NULL,
  `prestamo_id` int(11) NOT NULL,
  `numero_cuota` int(11) NOT NULL,
  `monto` decimal(10,2) NOT NULL,
  `fecha_vencimiento` date NOT NULL,
  `estado` enum('pendiente','pagada') DEFAULT 'pendiente'
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
  `tasa_interes` decimal(5,2) DEFAULT NULL,
  `plazo_meses` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `productos_financieros`
--

INSERT INTO `productos_financieros` (`id`, `nombre`, `descripción`, `tasa_interes`, `plazo_meses`) VALUES
(1, 'Préstamo Personal', 'Préstamo de libre destino', '45.50', 36);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `solicitudes_prestamos`
--

CREATE TABLE `solicitudes_prestamos` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `monto_solicitado` decimal(10,2) NOT NULL,
  `estado` enum('pendiente','aprobado','rechazado') NOT NULL,
  `tipo_empleo` varchar(50) DEFAULT NULL,
  `ingresos_mensuales` decimal(10,2) DEFAULT NULL,
  `motivo_prestamo` text DEFAULT NULL,
  `plazo_meses` int(11) DEFAULT NULL,
  `fecha_solicitud` timestamp NOT NULL DEFAULT current_timestamp(),
  `monto_total` decimal(10,2) DEFAULT NULL,
  `cuota_mensual` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `solicitudes_prestamos`
--

INSERT INTO `solicitudes_prestamos` (`id`, `usuario_id`, `producto_id`, `monto_solicitado`, `estado`, `tipo_empleo`, `ingresos_mensuales`, `motivo_prestamo`, `plazo_meses`, `fecha_solicitud`, `monto_total`, `cuota_mensual`) VALUES
(2, 18, 1, '75000.00', 'rechazado', 'relacion_independiente', '341212.00', 'a', 12, '2025-12-19 22:18:33', NULL, NULL),
(3, 18, 1, '321.00', 'aprobado', 'relacion_independiente', '12000000.00', 'Porque sí', 36, '2025-12-19 23:19:41', '5578.98', '154.97'),
(4, 19, 1, '1213.00', 'aprobado', 'relacion_independiente', '123123.00', '12', 12, '2025-12-19 23:23:50', '7835.98', '653.00');

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
  `fecha_nacimiento` date NOT NULL,
  `rol` enum('admin','usuario') NOT NULL DEFAULT 'usuario'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `email`, `contrasena`, `saldo`, `apellido`, `dni`, `telefono`, `provincia`, `fecha_nacimiento`, `rol`) VALUES
(18, 'Ezequiel Matias', 'Pmnavarro1969@gmail.com', '$2y$10$tw230nsoXnl1RFcwzjrzH.QeEQT6UYQiLlc3enyz/pdao2VbN27Zi', '0.00', 'Sosa', '46752695', '1159139848', 'Buenos Aires', '2005-06-07', 'admin'),
(19, 'Camila ', 'camybelensosa@gmail.com', '$2y$10$UCs2JB30/yoAkEqztFldEeKHua9lqnwFRc6xIoIlU1diMeA49mP52', '0.00', 'Sosa', '48181435', '1159139848', 'Buenos Aires', '2002-02-07', 'usuario');

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
-- Indices de la tabla `cuotas_prestamo`
--
ALTER TABLE `cuotas_prestamo`
  ADD PRIMARY KEY (`id`),
  ADD KEY `prestamo_id` (`prestamo_id`);

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
-- AUTO_INCREMENT de la tabla `cuotas_prestamo`
--
ALTER TABLE `cuotas_prestamo`
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `solicitudes_prestamos`
--
ALTER TABLE `solicitudes_prestamos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `transacciones`
--
ALTER TABLE `transacciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `cuentas_bancarias`
--
ALTER TABLE `cuentas_bancarias`
  ADD CONSTRAINT `cuentas_bancarias_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `cuotas_prestamo`
--
ALTER TABLE `cuotas_prestamo`
  ADD CONSTRAINT `cuotas_prestamo_ibfk_1` FOREIGN KEY (`prestamo_id`) REFERENCES `solicitudes_prestamos` (`id`);

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
