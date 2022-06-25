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

/*Crear la base de datos borrandola si ya existiera*/

DROP DATABASE IF EXISTS `cookinillas`;
CREATE DATABASE `cookinillas` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;

/*Seleccionamos para usar*/

USE `cookinillas`;

/*Damos permiso de uso y borramos el usuario que queremos crear por si existe*/

/*GRANT USAGE ON * . * TO `cookinillas`@`localhost`;
	DROP USER `cookinillas`@`localhost`;*/

/*Creamos el usuario y le damos password,damos permiso de uso y damos permisos sobre la base de datos*/

CREATE USER IF NOT EXISTS `cookinillas`@`localhost` IDENTIFIED BY 'cookinillasTSW';
GRANT USAGE ON *.* TO `cookinillas`@`localhost` REQUIRE NONE WITH MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0;
GRANT ALL PRIVILEGES ON `cookinillas`.* TO `cookinillas`@`localhost` WITH GRANT OPTION;

--
-- Base de datos: `cookinillas`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE IF NOT EXISTS `usuarios` (
    `alias` varchar(20) NOT NULL,
    `password` varchar(128) NOT NULL,
    `email` varchar(60) NOT NULL,
    PRIMARY KEY (`alias`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ingredientes`
--

DROP TABLE IF EXISTS `ingredientes`;
CREATE TABLE IF NOT EXISTS `ingredientes` (
  `id_ingr` int(3) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(20) NOT NULL,
  PRIMARY KEY (`id_ingr`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `recetas`
--

DROP TABLE IF EXISTS `recetas`;
CREATE TABLE IF NOT EXISTS `recetas` (
  `id_receta` int(3) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(100) NOT NULL,
  `imagen` varchar(128),
  `tiempo` int(4) NOT NULL,
  `pasos` varchar(8192) NOT NULL,
  `alias` varchar(20) NOT NULL,
  `nlikes` int(4) UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`id_receta`),
  FOREIGN KEY (`alias`) REFERENCES usuarios(`alias`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `receta_fav`
--

DROP TABLE IF EXISTS `receta_fav`;
CREATE TABLE IF NOT EXISTS `receta_fav` (
  `id_receta` int(3) NOT NULL,
  `alias` varchar(20) NOT NULL,
  PRIMARY KEY (`alias`, `id_receta`),
  FOREIGN KEY (`alias`) REFERENCES usuarios(`alias`) ON DELETE CASCADE,
  FOREIGN KEY (`id_receta`) REFERENCES recetas(`id_receta`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `receta_ingrediente`
--

DROP TABLE IF EXISTS `receta_ingrediente`;
CREATE TABLE IF NOT EXISTS `receta_ingrediente` (
  `id_receta` int(3) NOT NULL,
  `id_ingr` int(3) NOT NULL,
  `cantidad` varchar(20) NOT NULL,
  PRIMARY KEY (`id_receta`, `id_ingr`),
  FOREIGN KEY (`id_receta`) REFERENCES recetas(`id_receta`) ON DELETE CASCADE,
  FOREIGN KEY (`id_ingr`)  REFERENCES ingredientes(`id_ingr`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Trigger para añadir likes
--
DELIMITER //
CREATE TRIGGER addlike AFTER INSERT ON receta_fav
FOR EACH ROW
BEGIN
    DECLARE numlikes INT unsigned;
    SELECT COUNT(*) INTO numlikes FROM receta_fav WHERE receta_fav.id_receta = new.id_receta;
    UPDATE recetas SET nlikes = numlikes WHERE recetas.id_receta = new.id_receta;
END; //

-- --------------------------------------------------------

--
-- Trigger para eliminar likes
--
DELIMITER //
CREATE TRIGGER removeLike AFTER DELETE ON receta_fav
FOR EACH ROW
BEGIN
    DECLARE numlikes INT unsigned;
    SELECT COUNT(*) INTO numlikes FROM receta_fav WHERE receta_fav.id_receta = old.id_receta;
    UPDATE recetas SET nlikes = numlikes WHERE recetas.id_receta = old.id_receta;
END; //
