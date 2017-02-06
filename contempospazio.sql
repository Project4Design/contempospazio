-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Feb 06, 2017 at 05:09 AM
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
-- Table structure for table `configuracion`
--

CREATE TABLE `configuracion` (
  `id_config` int(10) UNSIGNED NOT NULL,
  `config_tax` float(5,2) NOT NULL DEFAULT '0.00' COMMENT 'Impuesto',
  `config_earnings` float(5,2) NOT NULL DEFAULT '0.00',
  `config_regular_work` float(5,2) NOT NULL DEFAULT '0.00',
  `config_big_work` float(5,2) NOT NULL DEFAULT '0.00' COMMENT 'Mano de obra',
  `config_delivery` float(5,2) UNSIGNED NOT NULL DEFAULT '0.00',
  `config_shipment` float(5,2) NOT NULL DEFAULT '0.00',
  `config_discount` float(5,2) UNSIGNED NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='Variables de configuracion global del sistema';

--
-- Dumping data for table `configuracion`
--

INSERT INTO `configuracion` (`id_config`, `config_tax`, `config_earnings`, `config_regular_work`, `config_big_work`, `config_delivery`, `config_shipment`, `config_discount`) VALUES
(1, 7.00, 50.00, 50.00, 60.00, 10.00, 5.00, 50.00);

-- --------------------------------------------------------

--
-- Table structure for table `fregaderos`
--

CREATE TABLE `fregaderos` (
  `id_fregadero` int(2) UNSIGNED NOT NULL,
  `id_fc` int(1) UNSIGNED NOT NULL,
  `id_fm` int(1) NOT NULL,
  `freg_nombre` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `freg_forma` tinyint(1) NOT NULL,
  `freg_costo` float(7,2) UNSIGNED NOT NULL,
  `freg_foto` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
  `freg_fecha_reg` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Dumping data for table `fregaderos`
--

INSERT INTO `fregaderos` (`id_fregadero`, `id_fc`, `id_fm`, `freg_nombre`, `freg_forma`, `freg_costo`, `freg_foto`, `freg_fecha_reg`) VALUES
(1, 3, 4, 'Fregadero', 2, 20.00, '9194be35616cc644d1a409a323196517c7928af5.png', '2017-02-03 03:36:06');

-- --------------------------------------------------------

--
-- Table structure for table `fregaderos_colores`
--

CREATE TABLE `fregaderos_colores` (
  `id_fc` int(1) UNSIGNED NOT NULL,
  `fc_nombre` varchar(30) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Dumping data for table `fregaderos_colores`
--

INSERT INTO `fregaderos_colores` (`id_fc`, `fc_nombre`) VALUES
(1, 'Azul'),
(3, 'Rojo'),
(4, 'Verde'),
(5, 'Negro'),
(6, 'Violeta'),
(7, 'Cyan'),
(8, 'Nuevo'),
(9, 'New'),
(10, 'Otro');

-- --------------------------------------------------------

--
-- Table structure for table `fregaderos_materiales`
--

CREATE TABLE `fregaderos_materiales` (
  `id_fm` int(1) UNSIGNED NOT NULL,
  `fm_nombre` varchar(30) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Dumping data for table `fregaderos_materiales`
--

INSERT INTO `fregaderos_materiales` (`id_fm`, `fm_nombre`) VALUES
(1, 'Porcelana'),
(2, 'Vidrio'),
(3, 'Ceramica'),
(4, 'Otro'),
(5, 'C'),
(6, 'Cascasc');

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
-- Table structure for table `productos`
--

CREATE TABLE `productos` (
  `id_producto` int(2) NOT NULL,
  `prod_categoria` tinyint(1) UNSIGNED NOT NULL,
  `prod_nombre` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `prod_costo` float(6,2) NOT NULL,
  `prod_foto` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
  `prod_fecha_reg` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

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
-- Table structure for table `topes`
--

CREATE TABLE `topes` (
  `id_tope` int(2) UNSIGNED NOT NULL,
  `id_tc` int(1) UNSIGNED NOT NULL,
  `id_tm` int(1) UNSIGNED NOT NULL,
  `tope_nombre` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `tope_costo` float(7,2) UNSIGNED NOT NULL,
  `tope_foto` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
  `tope_fecha_reg` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Dumping data for table `topes`
--

INSERT INTO `topes` (`id_tope`, `id_tc`, `id_tm`, `tope_nombre`, `tope_costo`, `tope_foto`, `tope_fecha_reg`) VALUES
(3, 5, 6, 'top', 15.00, NULL, '2017-02-06 03:06:38');

-- --------------------------------------------------------

--
-- Table structure for table `topes_colores`
--

CREATE TABLE `topes_colores` (
  `id_tc` int(1) UNSIGNED NOT NULL,
  `tc_nombre` varchar(30) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Dumping data for table `topes_colores`
--

INSERT INTO `topes_colores` (`id_tc`, `tc_nombre`) VALUES
(5, 'Crimson'),
(6, 'White');

-- --------------------------------------------------------

--
-- Table structure for table `topes_materiales`
--

CREATE TABLE `topes_materiales` (
  `id_tm` int(1) UNSIGNED NOT NULL,
  `tm_nombre` varchar(30) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Dumping data for table `topes_materiales`
--

INSERT INTO `topes_materiales` (`id_tm`, `tm_nombre`) VALUES
(6, 'Marbel'),
(7, 'Granite');

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
-- Indexes for table `configuracion`
--
ALTER TABLE `configuracion`
  ADD PRIMARY KEY (`id_config`);

--
-- Indexes for table `fregaderos`
--
ALTER TABLE `fregaderos`
  ADD PRIMARY KEY (`id_fregadero`);

--
-- Indexes for table `fregaderos_colores`
--
ALTER TABLE `fregaderos_colores`
  ADD PRIMARY KEY (`id_fc`);

--
-- Indexes for table `fregaderos_materiales`
--
ALTER TABLE `fregaderos_materiales`
  ADD PRIMARY KEY (`id_fm`);

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
-- Indexes for table `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id_producto`);

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
-- Indexes for table `topes`
--
ALTER TABLE `topes`
  ADD PRIMARY KEY (`id_tope`);

--
-- Indexes for table `topes_colores`
--
ALTER TABLE `topes_colores`
  ADD PRIMARY KEY (`id_tc`);

--
-- Indexes for table `topes_materiales`
--
ALTER TABLE `topes_materiales`
  ADD PRIMARY KEY (`id_tm`);

--
-- Indexes for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `configuracion`
--
ALTER TABLE `configuracion`
  MODIFY `id_config` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `fregaderos`
--
ALTER TABLE `fregaderos`
  MODIFY `id_fregadero` int(2) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `fregaderos_colores`
--
ALTER TABLE `fregaderos_colores`
  MODIFY `id_fc` int(1) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `fregaderos_materiales`
--
ALTER TABLE `fregaderos_materiales`
  MODIFY `id_fm` int(1) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `gabinetes`
--
ALTER TABLE `gabinetes`
  MODIFY `id_gabi` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `gabinetes_prod`
--
ALTER TABLE `gabinetes_prod`
  MODIFY `id_gp` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT for table `productos`
--
ALTER TABLE `productos`
  MODIFY `id_producto` int(2) NOT NULL AUTO_INCREMENT;
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
-- AUTO_INCREMENT for table `topes`
--
ALTER TABLE `topes`
  MODIFY `id_tope` int(2) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `topes_colores`
--
ALTER TABLE `topes_colores`
  MODIFY `id_tc` int(1) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `topes_materiales`
--
ALTER TABLE `topes_materiales`
  MODIFY `id_tm` int(1) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_user` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
