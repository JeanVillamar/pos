-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 18-12-2024 a las 20:23:26
-- Versión del servidor: 10.4.21-MariaDB
-- Versión de PHP: 8.0.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `pos`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `admins`
--

CREATE TABLE `admins` (
  `id_admin` int(11) NOT NULL,
  `email_admin` text COLLATE utf8_spanish_ci DEFAULT NULL,
  `password_admin` text COLLATE utf8_spanish_ci DEFAULT NULL,
  `rol_admin` text COLLATE utf8_spanish_ci DEFAULT NULL,
  `permissions_admin` text COLLATE utf8_spanish_ci DEFAULT NULL,
  `token_admin` text COLLATE utf8_spanish_ci DEFAULT NULL,
  `token_exp_admin` text COLLATE utf8_spanish_ci DEFAULT NULL,
  `status_admin` int(11) DEFAULT 1,
  `title_admin` text COLLATE utf8_spanish_ci DEFAULT NULL,
  `symbol_admin` text COLLATE utf8_spanish_ci DEFAULT NULL,
  `font_admin` text COLLATE utf8_spanish_ci DEFAULT NULL,
  `color_admin` text COLLATE utf8_spanish_ci DEFAULT NULL,
  `back_admin` text COLLATE utf8_spanish_ci DEFAULT NULL,
  `scode_admin` text COLLATE utf8_spanish_ci DEFAULT NULL,
  `chatgpt_admin` text COLLATE utf8_spanish_ci DEFAULT NULL,
  `date_created_admin` date DEFAULT NULL,
  `date_updated_admin` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `admins`
--

INSERT INTO `admins` (`id_admin`, `email_admin`, `password_admin`, `rol_admin`, `permissions_admin`, `token_admin`, `token_exp_admin`, `status_admin`, `title_admin`, `symbol_admin`, `font_admin`, `color_admin`, `back_admin`, `scode_admin`, `chatgpt_admin`, `date_created_admin`, `date_updated_admin`) VALUES
(1, 'superadmin@pos.com', '$2a$07$azybxcags23425sdg23sdeanQZqjaf6Birm2NvcYTNtJw24CsO5uq', 'superadmin', '{\"todo\":\"on\"}', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE3MzQzODg5OTYsImV4cCI6MTczNDQ3NTM5NiwiZGF0YSI6eyJpZCI6IjEiLCJlbWFpbCI6InN1cGVyYWRtaW5AcG9zLmNvbSJ9fQ.0RLmu5SFA6O0_gyhawKd-MCfFZm_jSN6BQxOfBA-I-M', '1734475396', 1, 'POS SYSTEM', '<i class=\"bi bi-cart-check-fill\"></i>', '<link rel=\"preconnect\" href=\"https://fonts.googleapis.com\">\r\n<link rel=\"preconnect\" href=\"https://fonts.gstatic.com\" crossorigin>\r\n<link href=\"https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&display=swap\" rel=\"stylesheet\">', '#611be4', 'http://cms.pos.com/views/assets/files/6760a08e6d34e6.png', 'w958zu', NULL, '2024-12-16', '2024-12-16 22:43:16');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `columns`
--

CREATE TABLE `columns` (
  `id_column` int(11) NOT NULL,
  `id_module_column` int(11) DEFAULT 0,
  `title_column` text COLLATE utf8_spanish_ci DEFAULT NULL,
  `alias_column` text COLLATE utf8_spanish_ci DEFAULT NULL,
  `type_column` text COLLATE utf8_spanish_ci DEFAULT NULL,
  `matrix_column` text COLLATE utf8_spanish_ci DEFAULT NULL,
  `visible_column` int(11) DEFAULT 1,
  `date_created_column` date DEFAULT NULL,
  `date_updated_column` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `columns`
--

INSERT INTO `columns` (`id_column`, `id_module_column`, `title_column`, `alias_column`, `type_column`, `matrix_column`, `visible_column`, `date_created_column`, `date_updated_column`) VALUES
(1, 2, 'rol_admin', 'rol', 'select', 'superadmin,admin,editor', 1, '2024-12-16', '2024-12-16 21:46:24'),
(2, 2, 'permissions_admin', 'permisos', 'object', '', 1, '2024-12-16', '2024-12-16 21:46:24'),
(3, 2, 'email_admin', 'email', 'email', '', 1, '2024-12-16', '2024-12-16 21:46:24'),
(4, 2, 'password_admin', 'pass', 'password', '', 0, '2024-12-16', '2024-12-16 21:46:24'),
(5, 2, 'token_admin', 'token', 'text', '', 0, '2024-12-16', '2024-12-16 21:46:24'),
(6, 2, 'token_exp_admin', 'expiración', 'text', '', 0, '2024-12-16', '2024-12-16 21:46:24'),
(7, 2, 'status_admin', 'estado', 'boolean', '', 1, '2024-12-16', '2024-12-16 21:46:24'),
(8, 2, 'title_admin', 'título', 'text', '', 0, '2024-12-16', '2024-12-16 21:46:25'),
(9, 2, 'symbol_admin', 'simbolo', 'text', '', 0, '2024-12-16', '2024-12-16 21:46:25'),
(10, 2, 'font_admin', 'tipografía', 'text', '', 0, '2024-12-16', '2024-12-16 21:46:25'),
(11, 2, 'color_admin', 'color', 'text', '', 0, '2024-12-16', '2024-12-16 21:46:25'),
(12, 2, 'back_admin', 'fondo', 'text', '', 0, '2024-12-16', '2024-12-16 21:46:25'),
(13, 2, 'scode_admin', 'seguridad', 'text', '', 0, '2024-12-16', '2024-12-16 21:46:25'),
(14, 4, 'title_office', 'Sucursales', 'text', NULL, 1, '2024-12-17', '2024-12-16 23:17:24'),
(15, 4, 'address_office', 'Dirección', 'text', NULL, 1, '2024-12-17', '2024-12-16 23:17:24'),
(16, 4, 'phone_office', 'Teléfono', 'text', NULL, 1, '2024-12-17', '2024-12-16 23:17:24');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `files`
--

CREATE TABLE `files` (
  `id_file` int(11) NOT NULL,
  `id_folder_file` int(11) DEFAULT 0,
  `name_file` text COLLATE utf8_spanish_ci DEFAULT NULL,
  `extension_file` text COLLATE utf8_spanish_ci DEFAULT NULL,
  `type_file` text COLLATE utf8_spanish_ci DEFAULT NULL,
  `size_file` double DEFAULT 0,
  `link_file` text COLLATE utf8_spanish_ci DEFAULT NULL,
  `thumbnail_vimeo_file` text COLLATE utf8_spanish_ci DEFAULT NULL,
  `id_mailchimp_file` text COLLATE utf8_spanish_ci DEFAULT NULL,
  `date_created_file` date DEFAULT NULL,
  `date_updated_file` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `files`
--

INSERT INTO `files` (`id_file`, `id_folder_file`, `name_file`, `extension_file`, `type_file`, `size_file`, `link_file`, `thumbnail_vimeo_file`, `id_mailchimp_file`, `date_created_file`, `date_updated_file`) VALUES
(1, 1, '674dfdf7195d735', 'png', 'image/png', 918215, 'http://cms.pos.com/views/assets/files/6760a08e6d34e6.png', NULL, NULL, '2024-12-16', '2024-12-16 21:50:06');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `folders`
--

CREATE TABLE `folders` (
  `id_folder` int(11) NOT NULL,
  `name_folder` text COLLATE utf8_spanish_ci DEFAULT NULL,
  `size_folder` text COLLATE utf8_spanish_ci DEFAULT NULL,
  `total_folder` double DEFAULT 0,
  `max_upload_folder` text COLLATE utf8_spanish_ci DEFAULT NULL,
  `url_folder` text COLLATE utf8_spanish_ci DEFAULT NULL,
  `keys_folder` text COLLATE utf8_spanish_ci DEFAULT NULL,
  `date_created_folder` date DEFAULT NULL,
  `date_updated_folder` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `folders`
--

INSERT INTO `folders` (`id_folder`, `name_folder`, `size_folder`, `total_folder`, `max_upload_folder`, `url_folder`, `keys_folder`, `date_created_folder`, `date_updated_folder`) VALUES
(1, 'Server', '200000000000', 918215, '500000000', 'http://cms.pos.com', NULL, '2024-12-16', '2024-12-16 21:50:06');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `modules`
--

CREATE TABLE `modules` (
  `id_module` int(11) NOT NULL,
  `id_page_module` int(11) DEFAULT 0,
  `type_module` text COLLATE utf8_spanish_ci DEFAULT NULL,
  `title_module` text COLLATE utf8_spanish_ci DEFAULT NULL,
  `suffix_module` text COLLATE utf8_spanish_ci DEFAULT NULL,
  `content_module` text COLLATE utf8_spanish_ci DEFAULT NULL,
  `width_module` int(11) DEFAULT 100,
  `editable_module` int(11) DEFAULT 1,
  `date_created_module` date DEFAULT NULL,
  `date_updated_module` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `modules`
--

INSERT INTO `modules` (`id_module`, `id_page_module`, `type_module`, `title_module`, `suffix_module`, `content_module`, `width_module`, `editable_module`, `date_created_module`, `date_updated_module`) VALUES
(1, 2, 'breadcrumbs', 'Administradores', NULL, NULL, 100, 1, '2024-12-16', '2024-12-16 21:46:23'),
(2, 2, 'tables', 'admins', 'admin', NULL, 100, 0, '2024-12-16', '2024-12-16 21:46:23'),
(3, 4, 'breadcrumbs', 'sucursales', '', '', 100, 1, '2024-12-17', '2024-12-16 23:10:34'),
(4, 4, 'tables', 'offices', 'office', '', 100, 1, '2024-12-17', '2024-12-16 23:17:23');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `offices`
--

CREATE TABLE `offices` (
  `id_office` int(11) NOT NULL,
  `title_office` text COLLATE utf8_spanish_ci DEFAULT NULL,
  `address_office` text COLLATE utf8_spanish_ci DEFAULT NULL,
  `phone_office` text COLLATE utf8_spanish_ci DEFAULT NULL,
  `date_created_office` date DEFAULT NULL,
  `date_updated_office` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `offices`
--

INSERT INTO `offices` (`id_office`, `title_office`, `address_office`, `phone_office`, `date_created_office`, `date_updated_office`) VALUES
(1, 'Sucursal+Pueblo+Lindo', 'Calle+24+%23+23+45', '6043214576', '2024-12-17', '2024-12-16 23:19:41'),
(2, 'Sucursal+Colinas+del+Monte', 'Calle+67+%23+45+67', '6043218798', '2024-12-17', '2024-12-16 23:21:03'),
(3, 'Sucursal+Valles', 'Calle+30+%23+98+56', '6043211234', '2024-12-17', '2024-12-16 23:21:23');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pages`
--

CREATE TABLE `pages` (
  `id_page` int(11) NOT NULL,
  `title_page` text COLLATE utf8_spanish_ci DEFAULT NULL,
  `url_page` text COLLATE utf8_spanish_ci DEFAULT NULL,
  `icon_page` text COLLATE utf8_spanish_ci DEFAULT NULL,
  `type_page` text COLLATE utf8_spanish_ci DEFAULT NULL,
  `order_page` int(11) DEFAULT 1,
  `date_created_page` date DEFAULT NULL,
  `date_updated_page` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `pages`
--

INSERT INTO `pages` (`id_page`, `title_page`, `url_page`, `icon_page`, `type_page`, `order_page`, `date_created_page`, `date_updated_page`) VALUES
(1, 'POS', 'pos', 'bi bi-house-door-fill', 'modules', 1, '2024-12-16', '2024-12-16 23:04:36'),
(2, 'Admins', 'admins', 'bi bi-person-fill-gear', 'modules', 2, '2024-12-16', '2024-12-16 23:04:36'),
(3, 'Archivos', 'archivos', 'bi bi-file-earmark-image', 'custom', 4, '2024-12-16', '2024-12-16 23:09:49'),
(4, 'Sucursales', 'sucursales', 'bi bi-shop', 'modules', 3, '2024-12-17', '2024-12-16 23:09:49');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id_admin`);

--
-- Indices de la tabla `columns`
--
ALTER TABLE `columns`
  ADD PRIMARY KEY (`id_column`);

--
-- Indices de la tabla `files`
--
ALTER TABLE `files`
  ADD PRIMARY KEY (`id_file`);

--
-- Indices de la tabla `folders`
--
ALTER TABLE `folders`
  ADD PRIMARY KEY (`id_folder`);

--
-- Indices de la tabla `modules`
--
ALTER TABLE `modules`
  ADD PRIMARY KEY (`id_module`);

--
-- Indices de la tabla `offices`
--
ALTER TABLE `offices`
  ADD PRIMARY KEY (`id_office`);

--
-- Indices de la tabla `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`id_page`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `admins`
--
ALTER TABLE `admins`
  MODIFY `id_admin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `columns`
--
ALTER TABLE `columns`
  MODIFY `id_column` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `files`
--
ALTER TABLE `files`
  MODIFY `id_file` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `folders`
--
ALTER TABLE `folders`
  MODIFY `id_folder` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `modules`
--
ALTER TABLE `modules`
  MODIFY `id_module` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `offices`
--
ALTER TABLE `offices`
  MODIFY `id_office` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `pages`
--
ALTER TABLE `pages`
  MODIFY `id_page` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
