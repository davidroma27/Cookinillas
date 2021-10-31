-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 31-10-2021 a las 22:49:13
-- Versión del servidor: 5.7.31
-- Versión de PHP: 7.3.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `cookinillas`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ingredientes`
--

DROP TABLE IF EXISTS `ingredientes`;
CREATE TABLE IF NOT EXISTS `ingredientes` (
  `nombre` varchar(15) NOT NULL,
  PRIMARY KEY (`nombre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `recetas`
--

DROP TABLE IF EXISTS `recetas`;
CREATE TABLE IF NOT EXISTS `recetas` (
  `id_receta` int(3) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(50) NOT NULL,
  `imagen` varchar(128) NOT NULL,
  `tiempo` int(4) NOT NULL,
  `pasos` varchar(8192) NOT NULL,
  `alias` varchar(15) NOT NULL,
  PRIMARY KEY (`id_receta`),
  KEY `alias` (`alias`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `receta_fav`
--

DROP TABLE IF EXISTS `receta_fav`;
CREATE TABLE IF NOT EXISTS `receta_fav` (
  `id_rec_fav` int(3) NOT NULL AUTO_INCREMENT,
  `id_receta` int(3) DEFAULT NULL,
  `alias` varchar(15) NOT NULL,
  PRIMARY KEY (`id_rec_fav`),
  KEY `id_receta` (`id_receta`),
  KEY `alias` (`alias`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `receta_ingrediente`
--

DROP TABLE IF EXISTS `receta_ingrediente`;
CREATE TABLE IF NOT EXISTS `receta_ingrediente` (
  `id_rec_ing` int(3) NOT NULL AUTO_INCREMENT,
  `id_receta` int(3) NOT NULL,
  `nombre` varchar(15) NOT NULL,
  `cantidad` varchar(10) NOT NULL,
  PRIMARY KEY (`id_rec_ing`),
  KEY `id_receta` (`id_receta`),
  KEY `nombre` (`nombre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE IF NOT EXISTS `usuarios` (
  `alias` varchar(15) NOT NULL,
  `password` varchar(128) NOT NULL,
  `email` varchar(60) NOT NULL,
  PRIMARY KEY (`alias`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `recetas`
--
ALTER TABLE `recetas`
  ADD CONSTRAINT `recetas_ibfk_1` FOREIGN KEY (`alias`) REFERENCES `usuarios` (`alias`) ON DELETE NO ACTION;

--
-- Filtros para la tabla `receta_fav`
--
ALTER TABLE `receta_fav`
  ADD CONSTRAINT `receta_fav_ibfk_1` FOREIGN KEY (`id_receta`) REFERENCES `recetas` (`id_receta`) ON DELETE CASCADE,
  ADD CONSTRAINT `receta_fav_ibfk_2` FOREIGN KEY (`alias`) REFERENCES `usuarios` (`alias`);

--
-- Filtros para la tabla `receta_ingrediente`
--
ALTER TABLE `receta_ingrediente`
  ADD CONSTRAINT `receta_ingrediente_ibfk_1` FOREIGN KEY (`id_receta`) REFERENCES `recetas` (`id_receta`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `receta_ingrediente_ibfk_2` FOREIGN KEY (`nombre`) REFERENCES `ingredientes` (`nombre`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
