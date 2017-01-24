-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jan 25, 2017 at 12:07 AM
-- Server version: 10.1.13-MariaDB
-- PHP Version: 5.6.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `contempospazio`
--
CREATE DATABASE IF NOT EXISTS `contempospazio` DEFAULT CHARACTER SET utf8 COLLATE utf8_spanish_ci;
USE `contempospazio`;

-- --------------------------------------------------------

--
-- Table structure for table `gabinetes`
--

CREATE TABLE `gabinetes` (
  `id_gabi` int(10) UNSIGNED NOT NULL,
  `id_prov` int(10) UNSIGNED NOT NULL,
  `gabi_descripcion` varchar(200) COLLATE utf8_spanish_ci NOT NULL,
  `gabi_foto` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
  `gabi_fecha_reg` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Dumping data for table `gabinetes`
--

INSERT INTO `gabinetes` (`id_gabi`, `id_prov`, `gabi_descripcion`, `gabi_foto`, `gabi_fecha_reg`) VALUES
(1, 1, '9'' to 21'' WIDE 30'' HIGH, 1 DOOR 2 SHELVES 12'' DEEP', 'cd4af004dc171edf79ce85f506f5c0bd4e41dbcb.PNG', '2017-01-17 13:48:24'),
(2, 1, '9'' to 21'' WIDE 36'' HIGH, 1 DOOR 2 SHELVES 12'' DEEP', NULL, '2017-01-17 18:46:59');

-- --------------------------------------------------------

--
-- Table structure for table `gabinetes_prod`
--

CREATE TABLE `gabinetes_prod` (
  `id_gp` int(10) UNSIGNED NOT NULL,
  `id_gabi` int(10) UNSIGNED NOT NULL,
  `gp_codigo` varchar(10) COLLATE utf8_spanish_ci NOT NULL,
  `gp_gs` float(6,2) UNSIGNED DEFAULT '0.00',
  `gp_mgc` float(6,2) UNSIGNED DEFAULT '0.00',
  `gp_rbs` float(6,2) UNSIGNED DEFAULT '0.00',
  `gp_esms` float(6,2) UNSIGNED DEFAULT '0.00',
  `gp_ws` float(6,2) UNSIGNED DEFAULT '0.00',
  `gp_miw` float(6,2) UNSIGNED DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='codigos de productos de los gabinetes';

--
-- Dumping data for table `gabinetes_prod`
--

INSERT INTO `gabinetes_prod` (`id_gp`, `id_gabi`, `gp_codigo`, `gp_gs`, `gp_mgc`, `gp_rbs`, `gp_esms`, `gp_ws`, `gp_miw`) VALUES
(1, 1, 'W0930', 87.00, 99.00, 92.00, 109.00, 114.00, 107.00),
(2, 1, 'W1230', 101.00, 118.00, 108.00, 130.00, 134.00, 131.00),
(3, 1, 'W1530', 124.00, 138.00, 131.00, 152.00, 159.00, 151.00),
(4, 1, 'W1830', 131.00, 145.00, 138.00, 160.00, 170.00, 172.00),
(5, 1, 'W2130', 142.00, 158.00, 149.00, 174.00, 192.00, 192.00),
(6, 2, 'W0936', 101.00, 119.00, 106.00, 131.00, 136.00, 126.00),
(7, 2, 'W1236', 110.00, 135.00, 118.00, 149.00, 152.00, 152.00);

-- --------------------------------------------------------

--
-- Table structure for table `gp_colores`
--

CREATE TABLE `gp_colores` (
  `id_gpc` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='Colores y precios de los gabinetes';

-- --------------------------------------------------------

--
-- Table structure for table `proveedores`
--

CREATE TABLE `proveedores` (
  `id_prov` mediumint(8) UNSIGNED NOT NULL,
  `prov_eliminado` tinyint(1) NOT NULL DEFAULT '0',
  `prov_nombre` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `prov_telefono` varchar(11) COLLATE utf8_spanish_ci DEFAULT NULL,
  `prov_email` varchar(30) COLLATE utf8_spanish_ci DEFAULT NULL,
  `prov_logo` varchar(40) COLLATE utf8_spanish_ci DEFAULT NULL,
  `prov_fecha_reg` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='Proveedores';

--
-- Dumping data for table `proveedores`
--

INSERT INTO `proveedores` (`id_prov`, `prov_eliminado`, `prov_nombre`, `prov_telefono`, `prov_email`, `prov_logo`, `prov_fecha_reg`) VALUES
(1, 0, 'Kitchens by Us', NULL, NULL, NULL, '2017-01-17 13:13:23');

-- --------------------------------------------------------

--
-- Table structure for table `prov_colores`
--

CREATE TABLE `prov_colores` (
  `id_pcolor` int(10) UNSIGNED NOT NULL,
  `id_prov` int(10) UNSIGNED NOT NULL,
  `color_nombre` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
  `color_abrev` varchar(8) COLLATE utf8_spanish_ci NOT NULL,
  `color_foto` varchar(40) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='Colores de proveedores';

--
-- Dumping data for table `prov_colores`
--

INSERT INTO `prov_colores` (`id_pcolor`, `id_prov`, `color_nombre`, `color_abrev`, `color_foto`) VALUES
(1, 1, 'Ginger Spice', 'GS', ''),
(2, 1, 'Glaze Cherry', 'MGC', ''),
(3, 1, 'Burgundy Shaker', 'RBS', ''),
(4, 1, 'Shaker Espresso & Maple', 'ES & MS', ''),
(5, 1, 'White Shaker', 'WS', ''),
(6, 1, 'Ivory White', 'MIW', '');

-- --------------------------------------------------------

--
-- Table structure for table `usuarios`
--

CREATE TABLE `usuarios` (
  `id_user` int(10) UNSIGNED NOT NULL,
  `user_eliminado` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `user_nivel` varchar(1) COLLATE utf8_spanish_ci NOT NULL DEFAULT 'C',
  `user_estado` varchar(1) COLLATE utf8_spanish_ci NOT NULL DEFAULT 'A',
  `user_nombres` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `user_apellidos` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `user_email` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `user_pass` varchar(75) COLLATE utf8_spanish_ci NOT NULL,
  `user_telefono` varchar(11) COLLATE utf8_spanish_ci NOT NULL,
  `user_fecha_reg` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Dumping data for table `usuarios`
--

INSERT INTO `usuarios` (`id_user`, `user_eliminado`, `user_nivel`, `user_estado`, `user_nombres`, `user_apellidos`, `user_email`, `user_pass`, `user_telefono`, `user_fecha_reg`) VALUES
(1, 0, 'A', 'A', 'Orlando', 'Perez', 'Orlando@p4d.com.ve', '$2y$10$pDinWl1DsxdpjxqfuiRsvOYZQ4AKpMoTZQW9olQgERXePNhJPJrMC', '32342342342', '2017-01-24 22:43:12'),
(2, 0, 'A', 'A', 'Nombre', 'Apellido', 'Ascas@asc.com', '$2y$10$IBr045hkdRVQm6NAomH9k.ZtJA5cSZkrKvAJzhTAj/wl7tKuaWTS.', '231312', '2017-01-24 22:43:12');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `gabinetes`
--
ALTER TABLE `gabinetes`
  ADD PRIMARY KEY (`id_gabi`);

--
-- Indexes for table `gabinetes_prod`
--
ALTER TABLE `gabinetes_prod`
  ADD PRIMARY KEY (`id_gp`);

--
-- Indexes for table `gp_colores`
--
ALTER TABLE `gp_colores`
  ADD PRIMARY KEY (`id_gpc`);

--
-- Indexes for table `proveedores`
--
ALTER TABLE `proveedores`
  ADD PRIMARY KEY (`id_prov`);

--
-- Indexes for table `prov_colores`
--
ALTER TABLE `prov_colores`
  ADD PRIMARY KEY (`id_pcolor`);

--
-- Indexes for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `gabinetes`
--
ALTER TABLE `gabinetes`
  MODIFY `id_gabi` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `gabinetes_prod`
--
ALTER TABLE `gabinetes_prod`
  MODIFY `id_gp` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `gp_colores`
--
ALTER TABLE `gp_colores`
  MODIFY `id_gpc` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `proveedores`
--
ALTER TABLE `proveedores`
  MODIFY `id_prov` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `prov_colores`
--
ALTER TABLE `prov_colores`
  MODIFY `id_pcolor` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_user` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
