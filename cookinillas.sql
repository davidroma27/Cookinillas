/**
 * file: view/cookinillas.sql
 *
 * Database schema for Cookinillas app
 *
 * @author drmartinez
 */

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

/*Creación de tablas*/

CREATE TABLE IF NOT EXISTS `USUARIOS` (

    `alias` varchar(15) NOT NULL,
    `password` varchar(128) NOT NULL,
    `email` varchar(60) NOT NULL,

    PRIMARY KEY (`alias`)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `INGREDIENTES` (

    `nombre` varchar(15) NOT NULL,

    PRIMARY KEY (`nombre`)
)ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `RECETAS` (

    `id_receta` int(3) AUTO_INCREMENT,
    `titulo` varchar(50) NOT NULL,
    `imagen` varchar(128) NOT NULL,
    `tiempo` int(4) NOT NULL,
    `pasos` varchar(8192) NOT NULL,
    `alias` varchar(15) NOT NULL,

    PRIMARY KEY (`id_receta`),
    FOREIGN KEY (`alias`)
        REFERENCES USUARIOS(`alias`) ON DELETE NO ACTION
)ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `RECETA_INGREDIENTE` (

    `id_rec_ing` int(3) AUTO_INCREMENT,
    `id_receta` int(3) NOT NULL,
    `nombre` varchar(15) NOT NULL,
    `cantidad` varchar(10)  NOT NULL,

    PRIMARY KEY(`id_rec_ing`),
    FOREIGN KEY (`id_receta`)
        REFERENCES RECETAS(`id_receta`) ON UPDATE CASCADE,
    FOREIGN KEY (`nombre`)
        REFERENCES INGREDIENTES(`nombre`) ON UPDATE CASCADE
)ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `RECETA_FAV` (

    `id_rec_fav` int(3) AUTO_INCREMENT,
    `id_receta` int(3),
    `alias` varchar(15) NOT NULL,

    PRIMARY KEY(`id_rec_fav`),
    FOREIGN KEY (`id_receta`)
        REFERENCES RECETAS(`id_receta`),
    FOREIGN KEY (`alias`)
        REFERENCES USUARIOS(`alias`)
)ENGINE=InnoDB;

INSERT INTO `USUARIOS` (`alias`, `password`, `email`) VALUES ('jprobles', '3989da4eb832867da1eb82598a780c37', 'jacinto@gmail.com');
INSERT INTO `USUARIOS` (`alias`, `password`, `email`) VALUES ('jsribera', '18f854b217d390ef7916cfeb407b8b74', 'julia@gmail.com');
INSERT INTO `USUARIOS` (`alias`, `password`, `email`) VALUES ('agramos', 'aa87ddc5b4c24406d26ddad771ef44b0', 'alfonso@gmail.com');
INSERT INTO `USUARIOS` (`alias`, `password`, `email`) VALUES ('sgmartinez', '9772370be8dae4e7e0118e8648667407', 'sara@gmail.com');

INSERT INTO `INGREDIENTES` (`nombre`) VALUES ('Pollo');
INSERT INTO `INGREDIENTES` (`nombre`) VALUES ('Huevos');
INSERT INTO `INGREDIENTES` (`nombre`) VALUES ('Patatas');
INSERT INTO `INGREDIENTES` (`nombre`) VALUES ('Harina');
INSERT INTO `INGREDIENTES` (`nombre`) VALUES ('Pasta');

INSERT INTO `RECETAS` (`id_receta`, `titulo`, `imagen`, `tiempo`, `pasos`, `alias`) VALUES ('1','Pollo empanado al limon','../view/img/recipe1.jpg','30','Pasos a realizar para elaborar pollo al limon...', 'jprobles');
INSERT INTO `RECETAS` (`id_receta`, `titulo`, `imagen`, `tiempo`, `pasos`, `alias`) VALUES ('2','Lasaña boloñesa','../view/img/recipe2.jpg','60','Pasos a realizar para elaborar lasaña boloñesa...', 'agramos');
INSERT INTO `RECETAS` (`id_receta`, `titulo`, `imagen`, `tiempo`, `pasos`, `alias`) VALUES ('3','Lomo de cerdo con salsa de setas','../view/img/recipe3.jpg','60','Pasos a realizar para elaborar lomo de cerdo con salsa de setas...', 'sgmartinez');
INSERT INTO `RECETAS` (`id_receta`, `titulo`, `imagen`, `tiempo`, `pasos`, `alias`) VALUES ('4','Rollitos de jamon y queso','../view/img/recipe4.jpg','40','Pasos a realizar para elaborar rollitos de jamon y queso...', 'jprobles');

INSERT INTO `RECETA_INGREDIENTE` (`id_rec_ing`, `id_receta`, `nombre`, `cantidad`) VALUES ('1','1','Pollo','500g');
INSERT INTO `RECETA_INGREDIENTE` (`id_rec_ing`, `id_receta`, `nombre`, `cantidad`) VALUES ('2','1','Huevos','3');
INSERT INTO `RECETA_INGREDIENTE` (`id_rec_ing`, `id_receta`, `nombre`, `cantidad`) VALUES ('3','2','Pasta','300g');
INSERT INTO `RECETA_INGREDIENTE` (`id_rec_ing`, `id_receta`, `nombre`, `cantidad`) VALUES ('4','3','Harina','200g');

INSERT INTO `RECETA_FAV` (`id_rec_fav`, `id_receta`, `alias`) VALUES ('1','1','jprobles');
INSERT INTO `RECETA_FAV` (`id_rec_fav`, `id_receta`, `alias`) VALUES ('2','1','agramos');
INSERT INTO `RECETA_FAV` (`id_rec_fav`, `id_receta`, `alias`) VALUES ('3','2','jprobles');
INSERT INTO `RECETA_FAV` (`id_rec_fav`, `id_receta`, `alias`) VALUES ('4','3','jprobles');