-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 21-03-2020 a las 18:09:26
-- Versión del servidor: 10.4.10-MariaDB
-- Versión de PHP: 7.3.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `letters`
--
CREATE DATABASE IF NOT EXISTS `letters` DEFAULT CHARACTER SET utf8 COLLATE utf8_spanish_ci;
USE `letters`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `doctors`
--

DROP TABLE IF EXISTS `doctors`;
CREATE TABLE `doctors` (
  `id` int(11) NOT NULL,
  `name` varchar(256) COLLATE utf8_spanish_ci NOT NULL DEFAULT 'Unknown',
  `user` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `password` varchar(256) COLLATE utf8_spanish_ci NOT NULL,
  `level` int(11) NOT NULL,
  `hospital` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `doctors`
--

INSERT INTO `doctors` (`id`, `name`, `user`, `password`, `level`, `hospital`) VALUES
(1, 'Jaime Altozano', 'jaltozano', '03ac674216f3e15c761ee1a5e255f067953623c8b388b4459e13f978d7c846f4', 0, 1),
(2, 'Javier Santaolalla', 'jsantaolalla', '03ac674216f3e15c761ee1a5e255f067953623c8b388b4459e13f978d7c846f4', 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `hospitals`
--

DROP TABLE IF EXISTS `hospitals`;
CREATE TABLE `hospitals` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `location` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `contact` varchar(256) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `hospitals`
--

INSERT INTO `hospitals` (`id`, `name`, `location`, `contact`) VALUES
(1, 'Hospital Universitario 12 de Octubre', 'Av. de Córdoba, s/n, 28041 Madrid', '913908000');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `internal_likes`
--

DROP TABLE IF EXISTS `internal_likes`;
CREATE TABLE `internal_likes` (
  `id` int(11) NOT NULL,
  `doctor` int(11) NOT NULL,
  `letter` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `letters`
--

DROP TABLE IF EXISTS `letters`;
CREATE TABLE `letters` (
  `id` int(11) NOT NULL,
  `writer` varchar(256) COLLATE utf8_spanish_ci NOT NULL,
  `letter` varchar(256) COLLATE utf8_spanish_ci NOT NULL,
  `date` datetime NOT NULL,
  `profile` int(11) NOT NULL,
  `public` tinyint(1) NOT NULL,
  `public_likes` int(11) NOT NULL,
  `image` varchar(500) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `profiles`
--

DROP TABLE IF EXISTS `profiles`;
CREATE TABLE `profiles` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `icon` varchar(250) COLLATE utf8_spanish_ci NOT NULL DEFAULT 'report_problem',
  `age` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `stats` varchar(255) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `profiles`
--

INSERT INTO `profiles` (`id`, `name`, `icon`, `age`, `stats`) VALUES
(1, 'Peques', 'child_care', 'De 1-6 años', 'Son peques'),
(2, 'Adolescentes', 'videogame_asset', 'De 13-17 años', 'Son menos peques'),
(3, 'Adultos', 'work', 'Mayores de 17 años', 'Son personas adultas');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `validated`
--

DROP TABLE IF EXISTS `validated`;
CREATE TABLE `validated` (
  `id` int(11) NOT NULL,
  `letter_id` int(11) NOT NULL,
  `hospital_id` int(11) NOT NULL,
  `validated` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `doctors`
--
ALTER TABLE `doctors`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hospital` (`hospital`);

--
-- Indices de la tabla `hospitals`
--
ALTER TABLE `hospitals`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `internal_likes`
--
ALTER TABLE `internal_likes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `doctor` (`doctor`),
  ADD KEY `letter` (`letter`);

--
-- Indices de la tabla `letters`
--
ALTER TABLE `letters`
  ADD PRIMARY KEY (`id`),
  ADD KEY `profile` (`profile`);

--
-- Indices de la tabla `profiles`
--
ALTER TABLE `profiles`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `validated`
--
ALTER TABLE `validated`
  ADD PRIMARY KEY (`id`),
  ADD KEY `letter_id` (`letter_id`),
  ADD KEY `hospital_id` (`hospital_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `doctors`
--
ALTER TABLE `doctors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `hospitals`
--
ALTER TABLE `hospitals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `internal_likes`
--
ALTER TABLE `internal_likes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `letters`
--
ALTER TABLE `letters`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `profiles`
--
ALTER TABLE `profiles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `validated`
--
ALTER TABLE `validated`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `doctors`
--
ALTER TABLE `doctors`
  ADD CONSTRAINT `doctors_ibfk_1` FOREIGN KEY (`hospital`) REFERENCES `hospitals` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `internal_likes`
--
ALTER TABLE `internal_likes`
  ADD CONSTRAINT `internal_likes_ibfk_1` FOREIGN KEY (`doctor`) REFERENCES `doctors` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `internal_likes_ibfk_2` FOREIGN KEY (`letter`) REFERENCES `letters` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `letters`
--
ALTER TABLE `letters`
  ADD CONSTRAINT `letters_ibfk_1` FOREIGN KEY (`profile`) REFERENCES `profiles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `validated`
--
ALTER TABLE `validated`
  ADD CONSTRAINT `validated_ibfk_1` FOREIGN KEY (`letter_id`) REFERENCES `letters` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `validated_ibfk_2` FOREIGN KEY (`hospital_id`) REFERENCES `hospitals` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
