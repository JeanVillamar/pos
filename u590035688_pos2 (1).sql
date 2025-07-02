-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 02-07-2025 a las 19:36:49
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `u590035688_pos2`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `admins`
--

CREATE TABLE `admins` (
  `id_admin` int(11) NOT NULL,
  `email_admin` text DEFAULT NULL,
  `password_admin` text DEFAULT NULL,
  `rol_admin` text DEFAULT NULL,
  `permissions_admin` text DEFAULT '{}',
  `token_admin` text DEFAULT NULL,
  `token_exp_admin` text DEFAULT NULL,
  `status_admin` int(11) DEFAULT 1,
  `title_admin` text DEFAULT NULL,
  `symbol_admin` text DEFAULT NULL,
  `font_admin` text DEFAULT NULL,
  `color_admin` text DEFAULT NULL,
  `back_admin` text DEFAULT NULL,
  `scode_admin` text DEFAULT NULL,
  `name_admin` text DEFAULT NULL,
  `id_office_admin` int(11) DEFAULT 0,
  `cash_admin` int(11) DEFAULT 1,
  `chatgpt_admin` text DEFAULT NULL,
  `date_created_admin` date DEFAULT NULL,
  `date_updated_admin` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `admins`
--

INSERT INTO `admins` (`id_admin`, `email_admin`, `password_admin`, `rol_admin`, `permissions_admin`, `token_admin`, `token_exp_admin`, `status_admin`, `title_admin`, `symbol_admin`, `font_admin`, `color_admin`, `back_admin`, `scode_admin`, `name_admin`, `id_office_admin`, `cash_admin`, `chatgpt_admin`, `date_created_admin`, `date_updated_admin`) VALUES
(1, 'superadmin@pos.com', '$2a$07$azybxcags23425sdg23sde6uGXI561aGFmyTbcWmXv.UsRXAwvf9e', 'superadmin', '{\"todo\":\"on\"}', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE3NDg2MjQ1MzEsImV4cCI6MTc0ODcxMDkzMSwiZGF0YSI6eyJpZCI6MSwiZW1haWwiOiJzdXBlcmFkbWluQHBvcy5jb20ifX0.mLCEBfD8TRrNUhhehXVw74N-ysIThHwgMC8EUPg3h-g', '1748710931', 1, 'SMART POS LINE', '<i class=\"bi bi-cart-check-fill\"></i>', '<link rel=\"preconnect\" href=\"https://fonts.googleapis.com\"><link rel=\"preconnect\" href=\"https://fonts.gstatic.com\" crossorigin><link href=\"https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&display=swap\" rel=\"stylesheet\">', '#611be4', 'http://cms.pos.com/views/assets/files/6760a08e6d34e6.png', 'w958zu', 'El Programador', 0, 1, NULL, '2024-12-16', '2025-07-01 21:18:00'),
(2, 'admin@pos.com', '$2a$07$azybxcags23425sdg23sdeanQZqjaf6Birm2NvcYTNtJw24CsO5uq', 'admin', '{\"todo\":\"on\"}', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE3NDg3MTEzMjcsImV4cCI6MTc0ODc5NzcyNywiZGF0YSI6eyJpZCI6MiwiZW1haWwiOiJhZG1pbkBwb3MuY29tIn19.r9NLOawpusHRfg4Ujc-tqcV7FZnFi94PtG2Sf0M_sjA', '1748797727', 1, '', '', '', '', '', '', 'Sara Perez', 0, 1, NULL, '2024-12-19', '2025-05-31 17:08:47'),
(3, 'supervisor@pos.com', '$2a$07$azybxcags23425sdg23sdeanQZqjaf6Birm2NvcYTNtJw24CsO5uq', 'editor', '{\"pos\":\"on\",\"clientes\":\"on\",\"productos\":\"on\",\"compras\":\"on\"}', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE3MzQ2Mzg5OTIsImV4cCI6MTczNDcyNTM5MiwiZGF0YSI6eyJpZCI6IjMiLCJlbWFpbCI6InN1cGVydmlzb3JAcG9zLmNvbSJ9fQ.As7FXVdD2qpivzfahbXWcU14TsB2J9k1KYFbkFQvxCk', '1734725392', 1, '', '', '', '', '', '', 'Jorge Riquelme', 0, 1, NULL, '2024-12-19', '2025-04-14 22:01:04'),
(4, 'frioexpressnorte05@gmail.com', '', 'admin', '{\"todo\":\"on\"}', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE3NTE0Nzc1MTgsImV4cCI6MTc1MTU2MzkxOCwiZGF0YSI6eyJpZCI6IjQiLCJlbWFpbCI6ImZyaW9leHByZXNzbm9ydGUwNUBnbWFpbC5jb20ifX0.oK75TOPH5Mbr662HXy3jiO8S53xI6-ho9HS9IU2Efnk', '1751563918', 1, '', '', '', '', '', '', 'Jaime Suarez', 1, 1, NULL, '2024-12-19', '2025-07-02 17:31:58'),
(5, 'admin@colinas.com', '$2a$07$azybxcags23425sdg23sdeanQZqjaf6Birm2NvcYTNtJw24CsO5uq', 'admin', '%7B%22todo%22%3A%22on%22%7D', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE3MzQ2NDE4MzEsImV4cCI6MTczNDcyODIzMSwiZGF0YSI6eyJpZCI6IjUiLCJlbWFpbCI6ImFkbWluQGNvbGluYXMuY29tIn19.q6HOV81yruqi5BEluh0e6ta_1cAM-tQuWuWQCb7HC28', '1734728231', 1, '', '', '', '', '', '', 'Marta+Galindo', 2, 1, NULL, '2024-12-19', '2025-04-14 22:01:09'),
(6, 'admin@valles.com', '$2a$07$azybxcags23425sdg23sdeanQZqjaf6Birm2NvcYTNtJw24CsO5uq', 'admin', '%7B%22todo%22%3A%22on%22%7D', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE3NDQ4MzI5NTksImV4cCI6MTc0NDkxOTM1OSwiZGF0YSI6eyJpZCI6NiwiZW1haWwiOiJhZG1pbkB2YWxsZXMuY29tIn19.dqpDFgWadh6J82AAm9UHLvH1hs83CpDk1MXnNY9cPMc', '1744919359', 1, '', '', '', '', '', '', 'Mary+Mendez', 3, 1, NULL, '2024-12-19', '2025-04-16 19:49:19'),
(7, 'supervisor@pueblolindo.com', '$2a$07$azybxcags23425sdg23sdeanQZqjaf6Birm2NvcYTNtJw24CsO5uq', 'editor', '%7B%22pos%22%3A%22on%22%2C%22clientes%22%3A%22on%22%2C%22productos%22%3A%22on%22%2C%22compras%22%3A%22on%22%2C%22caja%22%3A%22on%22%2C%22gastos%22%3A%22on%22%7D', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE3NTE0Nzc0NjEsImV4cCI6MTc1MTU2Mzg2MSwiZGF0YSI6eyJpZCI6IjciLCJlbWFpbCI6InN1cGVydmlzb3JAcHVlYmxvbGluZG8uY29tIn19.7Ls5Z5e9Tqilx8slar653Q9j7rJFrfbal6KZ24obFRk', '1751563861', 1, '', '', '', '', '', '', 'Pepe+Lucio', 1, 1, NULL, '2024-12-19', '2025-07-02 17:31:01'),
(8, 'supervisor@colinas.com', '$2a$07$azybxcags23425sdg23sdeanQZqjaf6Birm2NvcYTNtJw24CsO5uq', 'editor', '%7B%22pos%22%3A%22on%22%2C%22clientes%22%3A%22on%22%2C%22productos%22%3A%22on%22%2C%22compras%22%3A%22on%22%2C%22caja%22%3A%22on%22%2C%22gastos%22%3A%22on%22%7D', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE3MzQ2NDAxNjcsImV4cCI6MTczNDcyNjU2NywiZGF0YSI6eyJpZCI6IjQiLCJlbWFpbCI6ImFkbWluQHB1ZWJsb2xpbmRvLmNvbSJ9fQ.3FWKr8N8HDe7j5zLcbLJx6mTu3BGB88yXhDHvX7e8dE', '1734726567', 1, '', '', '', '', '', '', 'Mario+Lopez', 2, 1, NULL, '2024-12-19', '2025-04-14 22:01:16'),
(9, 'supervisor@valles.com', '$2a$07$azybxcags23425sdg23sdeanQZqjaf6Birm2NvcYTNtJw24CsO5uq', 'editor', '%7B%22pos%22%3A%22on%22%2C%22clientes%22%3A%22on%22%2C%22productos%22%3A%22on%22%2C%22compras%22%3A%22on%22%2C%22caja%22%3A%22on%22%2C%22gastos%22%3A%22on%22%7D', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE3MzQ2NDAxNjcsImV4cCI6MTczNDcyNjU2NywiZGF0YSI6eyJpZCI6IjQiLCJlbWFpbCI6ImFkbWluQHB1ZWJsb2xpbmRvLmNvbSJ9fQ.3FWKr8N8HDe7j5zLcbLJx6mTu3BGB88yXhDHvX7e8dE', '1734726567', 1, '', '', '', '', '', '', 'Julia+Martinez', 3, 1, NULL, '2024-12-19', '2025-04-14 22:01:15'),
(10, 'seller@pueblolindo.com', '$2a$07$azybxcags23425sdg23sdeanQZqjaf6Birm2NvcYTNtJw24CsO5uq', 'editor', '%7B%22pos%22%3A%22on%22%2C%22clientes%22%3A%22on%22%7D', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE3MzQ2NDAxNjcsImV4cCI6MTczNDcyNjU2NywiZGF0YSI6eyJpZCI6IjQiLCJlbWFpbCI6ImFkbWluQHB1ZWJsb2xpbmRvLmNvbSJ9fQ.3FWKr8N8HDe7j5zLcbLJx6mTu3BGB88yXhDHvX7e8dE', '1734726567', 1, '', '', '', '', '', '', 'Marcos+Londo%C3%B1o', 1, 1, NULL, '2024-12-19', '2025-04-14 22:01:18'),
(11, 'seller@colinas.com', '$2a$07$azybxcags23425sdg23sdeanQZqjaf6Birm2NvcYTNtJw24CsO5uq', 'editor', '%7B%22pos%22%3A%22on%22%2C%22clientes%22%3A%22on%22%7D', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE3MzQ2NDAxNjcsImV4cCI6MTczNDcyNjU2NywiZGF0YSI6eyJpZCI6IjQiLCJlbWFpbCI6ImFkbWluQHB1ZWJsb2xpbmRvLmNvbSJ9fQ.3FWKr8N8HDe7j5zLcbLJx6mTu3BGB88yXhDHvX7e8dE', '1734726567', 1, '', '', '', '', '', '', 'Jaco+Cifuentes', 2, 1, NULL, '2024-12-19', '2025-04-14 22:01:19'),
(12, 'seller@valles.com', '$2a$07$azybxcags23425sdg23sdeanQZqjaf6Birm2NvcYTNtJw24CsO5uq', 'editor', '%7B%22pos%22%3A%22on%22%2C%22clientes%22%3A%22on%22%7D', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE3MzQ2NDE4MDcsImV4cCI6MTczNDcyODIwNywiZGF0YSI6eyJpZCI6IjEyIiwiZW1haWwiOiJzZWxsZXJAdmFsbGVzLmNvbSJ9fQ.G27uSrgFBaEP5wzVNPRk-HvzWIr7Pk9lcAlCyXW70-c', '1734728207', 1, '', '', '', '', '', '', 'Mona+Lisa', 3, 1, NULL, '2024-12-19', '2025-04-14 22:01:21');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bills`
--

CREATE TABLE `bills` (
  `id_bill` int(11) NOT NULL,
  `concept_bill` text DEFAULT NULL,
  `cost_bill` double DEFAULT 0,
  `date_bill` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `id_admin_bill` int(11) DEFAULT 0,
  `id_office_bill` int(11) DEFAULT 0,
  `date_created_bill` date DEFAULT NULL,
  `date_updated_bill` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `bills`
--

INSERT INTO `bills` (`id_bill`, `concept_bill`, `cost_bill`, `date_bill`, `id_admin_bill`, `id_office_bill`, `date_created_bill`, `date_updated_bill`) VALUES
(3, 'Almuerzo', 50, '2024-12-28 01:03:00', 1, 1, '2024-12-27', '2024-12-28 01:10:00'),
(4, 'Fotocopias', 10, '2024-12-28 01:03:00', 1, 1, '2024-12-27', '2024-12-28 01:10:12'),
(5, 'Almuerzos', 9, '2025-04-17 10:03:00', 1, 1, '2025-04-17', '2025-04-17 15:28:05'),
(6, 'Limpieza', 11, '2025-04-17 10:03:00', 1, 1, '2025-04-17', '2025-04-17 15:29:04');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cashs`
--

CREATE TABLE `cashs` (
  `id_cash` int(11) NOT NULL,
  `start_cash` double DEFAULT 0,
  `bills_cash` double DEFAULT 0,
  `money_cash` double DEFAULT 0,
  `diff_cash` double DEFAULT 0,
  `end_cash` double DEFAULT 0,
  `gap_cash` double DEFAULT 0,
  `status_cash` int(11) DEFAULT 1,
  `date_start_cash` datetime DEFAULT NULL,
  `date_end_cash` datetime DEFAULT NULL,
  `id_admin_cash` int(11) DEFAULT 0,
  `id_office_cash` int(11) DEFAULT 0,
  `date_created_cash` date DEFAULT NULL,
  `date_updated_cash` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `cashs`
--

INSERT INTO `cashs` (`id_cash`, `start_cash`, `bills_cash`, `money_cash`, `diff_cash`, `end_cash`, `gap_cash`, `status_cash`, `date_start_cash`, `date_end_cash`, `id_admin_cash`, `id_office_cash`, `date_created_cash`, `date_updated_cash`) VALUES
(1, 1000, 0, 0, 0, 0, 0, 0, '2024-12-25 12:03:00', '0000-00-00 00:00:00', 7, 1, '2024-12-25', '2024-12-26 17:42:13'),
(2, 1000, 0, 0, 0, 0, 0, 0, '2024-12-26 12:03:00', '0000-00-00 00:00:00', 7, 1, '2024-12-26', '2024-12-27 19:20:32'),
(3, 1000, -60, 8222.7, 9162.7, 9100, -62.7, 0, '2024-12-27 14:03:00', '2024-12-27 20:25:00', 1, 1, '2024-12-27', '2024-12-28 01:26:01'),
(4, 1300, 0, 0, 1300, 1300, 0, 0, '2025-03-09 20:03:00', '2025-04-10 11:09:00', 1, 1, '2025-03-09', '2025-04-10 16:19:03'),
(5, 2000, 0, 0, 2000, 2000, 0, 0, '2025-04-10 11:03:00', '2025-04-11 05:52:00', 1, 1, '2025-04-10', '2025-04-11 10:53:14'),
(6, 1500, 0, 0, 1500, 1500, 0, 0, '2025-04-11 05:03:00', '2025-04-12 11:04:00', 1, 1, '2025-04-11', '2025-04-12 16:04:13'),
(7, 1300, 0, 0, 1300, 1300, 0, 0, '2025-04-12 11:03:00', '2025-04-15 00:07:00', 4, 1, '2025-04-12', '2025-04-15 05:07:40'),
(10, 1700, 0, 0, 1700, 1700, 0, 0, '2025-04-15 00:03:00', '2025-04-16 13:28:00', 4, 1, '2025-04-15', '2025-04-16 18:29:13'),
(12, 2300, 0, 0, 2300, 2300, 0, 0, '2025-04-16 15:03:00', '2025-04-17 07:45:00', 6, 3, '2025-04-16', '2025-04-17 12:45:39'),
(13, 2800, 0, 0, 0, 0, 0, 1, '2025-04-17 07:03:00', '0000-00-00 00:00:00', 6, 3, '2025-04-17', '2025-04-17 12:45:57'),
(14, 2700, 0, 0, 2700, 2700, 0, 0, '2025-04-17 10:03:00', '2025-05-03 12:03:00', 4, 1, '2025-04-17', '2025-05-03 17:03:46'),
(15, 1222, 0, 0, 1222, 1222, 0, 1, '2025-05-03 12:03:00', '2025-05-31 12:10:00', 4, 1, '2025-05-03', '2025-05-31 17:10:12'),
(16, 1200, 0, 0, 0, 0, 0, 1, '2025-05-31 12:03:00', '0000-00-00 00:00:00', 1, 1, '2025-05-31', '2025-05-31 17:10:29');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categories`
--

CREATE TABLE `categories` (
  `id_category` int(11) NOT NULL,
  `title_category` text DEFAULT NULL,
  `img_category` text DEFAULT NULL,
  `order_category` int(11) DEFAULT 0,
  `status_category` int(11) DEFAULT 1,
  `id_office_category` int(11) DEFAULT 0,
  `date_created_category` date DEFAULT NULL,
  `date_updated_category` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `categories`
--

INSERT INTO `categories` (`id_category`, `title_category`, `img_category`, `order_category`, `status_category`, `id_office_category`, `date_created_category`, `date_updated_category`) VALUES
(1, 'Headphones', 'http://cms.pos.com/views/assets%2Ffiles/67632dad8945845.png', 0, 1, 0, '2024-12-18', '2025-07-01 21:41:14'),
(2, 'Shoes', 'http://cms.pos.com/views/assets/files/67632df45101e56.png', 0, 1, 0, '2024-12-18', '2025-07-01 21:41:49'),
(3, 'Mobiles', 'http://cms.pos.com/views/assets/files/67632e0cbf0a320.png', 0, 1, 1, '2024-12-18', '2025-07-01 21:42:01'),
(4, 'Watches', 'http://cms.pos.com/views/assets/files/67632e2558a3145.png', 0, 1, 1, '2024-12-18', '2025-07-01 21:42:10'),
(5, 'Laptops', 'http://cms.pos.com/views/assets/files/67632e3962b825.png', 0, 1, 1, '2024-12-18', '2025-07-01 21:42:21'),
(6, 'Ventilacion', 'http://cms.pos.com/views%2Fassets%2Ffiles%2F68011f581077844.webp', 0, 1, 1, '2025-04-17', '2025-07-01 21:42:46');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clients`
--

CREATE TABLE `clients` (
  `id_client` int(11) NOT NULL,
  `dni_client` text DEFAULT NULL,
  `name_client` text DEFAULT NULL,
  `surname_client` text DEFAULT NULL,
  `email_client` text DEFAULT NULL,
  `address_client` text DEFAULT NULL,
  `phone_client` text DEFAULT NULL,
  `id_office_client` int(11) DEFAULT 0,
  `dni_type_client` text DEFAULT NULL,
  `date_created_client` date DEFAULT NULL,
  `date_updated_client` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `clients`
--

INSERT INTO `clients` (`id_client`, `dni_client`, `name_client`, `surname_client`, `email_client`, `address_client`, `phone_client`, `id_office_client`, `dni_type_client`, `date_created_client`, `date_updated_client`) VALUES
(1, '0951656735', 'Jean Frank', 'Villamar Lindao', 'jeanfrank_2020@hotmail.com', 'Mucholote 1', '0992519289', 1, 'CEDULA', '2024-12-18', '2025-04-11 18:18:51'),
(2, '568457573547', 'Lina', 'Gomez', 'lina@correo.com', 'Calle+14+%23+45+64', '6013567898', 1, '', '2024-12-18', '2025-04-11 17:39:16'),
(3, '3463453412', 'Luis', 'Perez', 'luis@correo.com', 'Calle+24+%23+45+64', '6011567898', 1, '', '2024-12-18', '2025-04-11 17:39:16'),
(4, '57456845689', 'Mar%C3%ADa', 'Zuleta', 'maria@correo.com', 'Calle+44+%23+45+64', '6012567898', 2, NULL, '2024-12-18', '2024-12-18 19:40:52'),
(5, '6846234124243', 'Clara', 'Gutierrez', 'clara@correo.com', 'Calle+2+%23+45+64', '6042567898', 2, NULL, '2024-12-18', '2024-12-18 19:41:28'),
(6, '123446355785', 'Jose', 'Martinez', 'jose@correo.com', 'Calle+1+%23+45+64', '6041567898', 2, NULL, '2024-12-18', '2024-12-18 19:42:10'),
(7, '123446355785', 'Miguel', 'Montes', 'miguel@correo.com', 'Calle+1+%23+333+65', '6021567898', 3, NULL, '2024-12-18', '2024-12-18 19:43:50'),
(8, '3427468457', 'Julio', 'Sanchez', 'julio@correo.com', 'Calle+55+%23+333+65', '6091567898', 3, NULL, '2024-12-18', '2024-12-18 19:44:30'),
(9, '3427468457', 'Karla', 'Tellez', 'karla@correo.com', 'Calle+11+%23+333+65', '6081567898', 3, NULL, '2024-12-18', '2024-12-18 19:45:05'),
(10, '98765123', 'Marcos', 'Jimenez', 'marcos@correo.com', 'calle 34', '6012345678', 1, '', '2024-12-26', '2025-04-11 17:39:16'),
(11, '9999999999999', 'Consumidor', 'Final', 'consumidorfinal@email.com', '-', '-', 1, 'VENTA A CONSUMIDOR FINAL', '2025-04-10', '2025-04-11 18:19:07');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `columns`
--

CREATE TABLE `columns` (
  `id_column` int(11) NOT NULL,
  `id_module_column` int(11) DEFAULT 0,
  `title_column` text DEFAULT NULL,
  `alias_column` text DEFAULT NULL,
  `type_column` text DEFAULT NULL,
  `matrix_column` text DEFAULT NULL,
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
(14, 4, 'title_office', 'Sucursal', 'text', NULL, 1, '2024-12-17', '2025-04-11 15:47:53'),
(15, 4, 'address_office', 'Direccion establecimiento', 'text', NULL, 1, '2024-12-17', '2025-04-11 15:47:53'),
(16, 4, 'phone_office', 'Teléfono', 'text', NULL, 1, '2024-12-17', '2024-12-16 23:17:24'),
(17, 6, 'dni_client', 'Identificación', 'text', NULL, 1, '2024-12-18', '2025-04-11 17:39:05'),
(18, 6, 'name_client', 'Nombre', 'text', NULL, 1, '2024-12-18', '2024-12-18 19:37:40'),
(19, 6, 'surname_client', 'Apellido', 'text', NULL, 1, '2024-12-18', '2024-12-18 19:37:40'),
(20, 6, 'email_client', 'Email', 'email', NULL, 1, '2024-12-18', '2024-12-18 19:37:40'),
(21, 6, 'address_client', 'Dirección', 'text', NULL, 1, '2024-12-18', '2024-12-18 19:37:41'),
(22, 6, 'phone_client', 'Teléfono', 'text', NULL, 1, '2024-12-18', '2024-12-18 19:37:41'),
(23, 6, 'id_office_client', 'Sucursal', 'relations', 'offices', 1, '2024-12-18', '2024-12-18 19:38:33'),
(24, 8, 'title_category', 'Categoría', 'text', NULL, 1, '2024-12-18', '2024-12-18 20:14:59'),
(25, 8, 'img_category', 'Imagen', 'image', NULL, 1, '2024-12-18', '2024-12-18 20:15:00'),
(26, 8, 'order_category', 'Orden', 'order', NULL, 1, '2024-12-18', '2024-12-18 20:15:00'),
(27, 8, 'status_category', 'Estado', 'boolean', NULL, 1, '2024-12-18', '2024-12-18 20:15:00'),
(28, 10, 'title_product', 'Producto', 'text', NULL, 1, '2024-12-18', '2024-12-18 20:38:31'),
(29, 10, 'img_product', 'Imagen', 'image', NULL, 1, '2024-12-18', '2024-12-18 20:38:31'),
(30, 10, 'id_category_product', 'Categoría', 'relations', 'categories', 1, '2024-12-18', '2024-12-18 20:42:20'),
(31, 10, 'sku_product', 'SKU', 'text', NULL, 1, '2024-12-18', '2024-12-18 20:38:32'),
(32, 10, 'unit_product', 'Medida', 'select', 'unidad,centímetros cúbicos,decibel,pie cúbico,libra,tonelada', 1, '2024-12-18', '2025-04-12 15:48:36'),
(33, 10, 'tax_product', 'Impuesto', 'select', 'IVA_15,No Objeto de Impuesto,IVA_0,Exento de IVA', 1, '2024-12-18', '2025-04-12 15:48:21'),
(34, 10, 'rte_product', 'Retención', 'select', 'NULL,RETF_11', 1, '2024-12-18', '2024-12-18 20:48:00'),
(35, 10, 'stock_product', 'Stock', 'stock', NULL, 1, '2024-12-18', '2024-12-28 01:28:29'),
(36, 10, 'discount_product', 'Descuento', 'double', NULL, 1, '2024-12-18', '2024-12-18 20:38:33'),
(37, 10, 'status_product', 'Estado', 'boolean', NULL, 1, '2024-12-18', '2024-12-18 20:38:33'),
(38, 10, 'id_office_product', 'Sucursal', 'relations', 'offices', 1, '2024-12-18', '2024-12-18 20:48:54'),
(39, 12, 'supplier_purchase', 'Proveedor', 'text', NULL, 1, '2024-12-18', '2024-12-18 21:43:57'),
(40, 12, 'id_product_purchase', 'Producto', 'relations', 'products', 1, '2024-12-18', '2024-12-18 21:44:36'),
(41, 12, 'cost_purchase', 'Costo', 'money', NULL, 1, '2024-12-18', '2024-12-18 21:43:58'),
(42, 12, 'utility_purchase', 'Utilidad', 'select', '10%,20%,30%,40%,50%', 1, '2024-12-18', '2024-12-18 22:04:48'),
(43, 12, 'price_purchase', 'Precio', 'money', NULL, 1, '2024-12-18', '2024-12-18 21:43:58'),
(44, 12, 'qty_purchase', 'Cantidad', 'int', NULL, 1, '2024-12-18', '2024-12-18 21:43:58'),
(45, 12, 'invest_purchase', 'Inversión', 'money', NULL, 1, '2024-12-18', '2024-12-18 21:43:58'),
(46, 12, 'contact_purchase', 'Teléfono', 'text', NULL, 1, '2024-12-18', '2024-12-18 21:43:59'),
(47, 12, 'id_office_purchase', 'Sucursal', 'relations', 'offices', 1, '2024-12-18', '2024-12-18 21:55:18'),
(48, 14, 'transaction_order', 'Transacción', 'pos', NULL, 1, '2024-12-18', '2024-12-28 00:49:38'),
(49, 14, 'id_admin_order', 'Vendedor', 'relations', 'admins', 1, '2024-12-18', '2024-12-18 22:41:54'),
(50, 14, 'id_client_order', 'Cliente', 'relations', 'clients', 1, '2024-12-18', '2024-12-18 22:42:03'),
(51, 14, 'subtotal_order', 'Subtotal', 'money', NULL, 1, '2024-12-18', '2024-12-18 22:41:11'),
(52, 14, 'discount_order', 'Descuento', 'money', NULL, 1, '2024-12-18', '2024-12-18 22:41:11'),
(53, 14, 'tax_order', 'Impuesto', 'money', NULL, 1, '2024-12-18', '2024-12-18 22:41:12'),
(54, 14, 'total_order', 'Total', 'money', NULL, 1, '2024-12-18', '2024-12-18 22:41:12'),
(55, 14, 'method_order', 'Método', 'select', 'efectivo,transferencia,tarjeta', 1, '2024-12-18', '2024-12-18 22:46:09'),
(56, 14, 'transfer_order', 'Transferencia', 'text', NULL, 1, '2024-12-18', '2024-12-18 22:41:12'),
(57, 14, 'status_order', 'Estado', 'select', 'Completada,Pendiente', 1, '2024-12-18', '2024-12-18 22:46:26'),
(58, 14, 'date_order', 'Fecha', 'timestamp', NULL, 1, '2024-12-18', '2024-12-18 22:41:13'),
(59, 14, 'id_office_order', 'Sucursal', 'relations', 'offices', 1, '2024-12-18', '2024-12-18 22:42:12'),
(60, 16, 'id_order_sale', 'Orden', 'relations', 'orders', 1, '2024-12-18', '2024-12-18 22:55:22'),
(61, 16, 'id_product_sale', 'Producto', 'relations', 'products', 1, '2024-12-18', '2024-12-18 22:55:18'),
(62, 16, 'tax_type_sale', 'Tipo Impuesto', 'text', NULL, 1, '2024-12-18', '2024-12-18 22:54:25'),
(63, 16, 'tax_sale', 'Impuesto', 'double', NULL, 1, '2024-12-18', '2025-04-11 16:54:36'),
(64, 16, 'discount_sale', 'Descuento', 'double', NULL, 1, '2024-12-18', '2025-04-11 16:54:36'),
(65, 16, 'qty_sale', 'Cantidad', 'int', NULL, 1, '2024-12-18', '2024-12-18 22:54:25'),
(66, 16, 'subtotal_sale', 'Subtotal', 'money', NULL, 1, '2024-12-18', '2024-12-18 22:54:26'),
(67, 16, 'status_sale', 'Estado', 'select', 'Completada,Pendiente', 1, '2024-12-18', '2024-12-18 22:55:10'),
(68, 16, 'id_admin_sale', 'Vendedor', 'relations', 'admins', 1, '2024-12-18', '2024-12-18 22:55:01'),
(69, 16, 'id_client_sale', 'Cliente', 'relations', 'clients', 1, '2024-12-18', '2024-12-18 22:54:56'),
(70, 16, 'id_office_sale', 'Sucursal', 'relations', 'offices', 1, '2024-12-18', '2024-12-18 22:54:49'),
(71, 18, 'start_cash', 'Dinero Inicial', 'money', NULL, 1, '2024-12-19', '2024-12-18 23:09:25'),
(72, 18, 'bills_cash', 'Gastos', 'money', NULL, 1, '2024-12-19', '2024-12-18 23:09:26'),
(73, 18, 'money_cash', 'Ingresos', 'money', NULL, 1, '2024-12-19', '2024-12-18 23:09:26'),
(74, 18, 'diff_cash', 'Diferencia', 'money', NULL, 1, '2024-12-19', '2024-12-18 23:09:26'),
(75, 18, 'end_cash', 'Dinero Final', 'money', NULL, 1, '2024-12-19', '2024-12-18 23:09:26'),
(76, 18, 'gap_cash', 'Brecha', 'money', NULL, 1, '2024-12-19', '2024-12-18 23:09:27'),
(77, 18, 'status_cash', 'Estado', 'boolean', NULL, 1, '2024-12-19', '2024-12-18 23:09:27'),
(78, 18, 'date_start_cash', 'Fecha Inicial', 'datetime', NULL, 1, '2024-12-19', '2024-12-18 23:09:27'),
(79, 18, 'date_end_cash', 'Fecha Final', 'datetime', NULL, 1, '2024-12-19', '2024-12-18 23:09:27'),
(80, 18, 'id_admin_cash', 'Administrador', 'relations', 'admins', 1, '2024-12-19', '2024-12-18 23:09:43'),
(81, 18, 'id_office_cash', 'Sucursal', 'relations', 'offices', 1, '2024-12-19', '2024-12-18 23:09:39'),
(82, 20, 'concept_bill', 'Concepto', 'text', NULL, 1, '2024-12-19', '2024-12-18 23:14:38'),
(83, 20, 'cost_bill', 'Costo', 'money', NULL, 1, '2024-12-19', '2024-12-18 23:14:38'),
(84, 20, 'date_bill', 'Fecha', 'timestamp', NULL, 1, '2024-12-19', '2024-12-18 23:14:39'),
(85, 20, 'id_admin_bill', 'Administrador', 'relations', 'admins', 1, '2024-12-19', '2024-12-19 15:48:06'),
(86, 20, 'id_office_bill', 'Sucursal', 'relations', 'offices', 1, '2024-12-19', '2024-12-19 15:55:46'),
(87, 2, 'name_admin', 'Nombre', 'text', NULL, 1, '2024-12-19', '2024-12-19 20:12:24'),
(88, 2, 'id_office_admin', 'Sucursal', 'relations', 'offices', 1, '2024-12-19', '2024-12-19 20:20:36'),
(89, 4, 'dni_office', 'RUC', 'text', NULL, 1, '2025-03-09', '2025-03-10 01:38:55'),
(90, 8, 'id_office_category', 'Sucursal', 'relations', 'offices', 1, '2025-03-11', '2025-03-11 05:26:52'),
(91, 38, 'name_information', 'Nombre o Razon Social', 'text', NULL, 1, '2025-04-10', '2025-04-10 17:04:53'),
(92, 38, 'ruc_information', 'RUC', 'text', NULL, 1, '2025-04-10', '2025-04-10 17:04:54'),
(93, 38, 'address_matriz_information', 'Direccion matriz', 'text', NULL, 1, '2025-04-10', '2025-04-10 17:04:54'),
(94, 38, 'address_establishment_information', 'Direccion establecimiento', 'text', NULL, 1, '2025-04-10', '2025-04-10 17:04:54'),
(95, 38, 'name_comercial_information', 'Nombre comercial', 'text', NULL, 1, '2025-04-10', '2025-04-10 17:04:54'),
(96, 38, 'email_information', 'Correo', 'text', NULL, 1, '2025-04-10', '2025-04-10 17:09:08'),
(97, 38, 'phone_information', 'Numero de Telefono', 'text', NULL, 1, '2025-04-10', '2025-04-10 17:09:08'),
(98, 38, 'id_office_information', 'Sucursal', 'relations', 'offices', 1, '2025-04-10', '2025-04-10 17:19:48'),
(99, 4, 'id_local_office', 'Id Local', 'int', NULL, 1, '2025-04-11', '2025-04-11 13:59:36'),
(100, 4, 'address_matriz_office', 'Direccion matriz', 'text', NULL, 1, '2025-04-11', '2025-04-11 15:47:54'),
(101, 4, 'company_name_office', 'Razon Social', 'text', NULL, 1, '2025-04-11', '2025-04-11 16:32:51'),
(102, 4, 'email_office', 'Correo', 'text', NULL, 1, '2025-04-11', '2025-04-11 15:47:54'),
(103, 6, 'dni_type_client', 'Tipo Identificación', 'select', 'RUC,CEDULA,PASAPORTE,IDENTIFICACION EXTERIOR,VENTA A CONSUMIDOR FINAL', 1, '2025-04-11', '2025-04-11 18:18:40'),
(104, 39, 'id_order_invoce', 'id Orden', 'relations', NULL, 1, '2025-04-13', '2025-04-13 07:43:48'),
(105, 39, 'access_key_invoice', 'Clave Acceso', 'text', NULL, 1, '2025-04-13', '2025-04-13 07:43:49'),
(106, 39, 'status_invoice', 'Estado', 'text', NULL, 1, '2025-04-13', '2025-04-13 07:43:49'),
(107, 39, 'authorization_date_invoice', 'Fecha Autorización', 'timestamp', NULL, 1, '2025-04-13', '2025-04-13 07:43:49'),
(108, 39, 'pdf_invoice', 'PDF', 'file', NULL, 1, '2025-04-13', '2025-04-13 07:43:49'),
(109, 40, 'id_secuencial', 'Id', 'int', NULL, 1, '2025-04-14', '2025-04-14 15:35:36'),
(110, 40, 'ultimo_numero_secuencial', 'Ultimo secuencial', 'int', NULL, 1, '2025-04-14', '2025-04-14 16:10:58'),
(111, 40, 'oficina_secuencial', 'Oficina', 'int', NULL, 1, '2025-04-14', '2025-04-14 17:13:36'),
(112, 40, 'caja_secuencial', 'Caja', 'int', NULL, 1, '2025-04-14', '2025-04-14 17:13:36'),
(113, 40, 'office_secuencial', 'Sucursal', 'relations', 'offices', 1, '2025-04-14', '2025-04-14 20:49:56'),
(114, 2, 'cash_admin', 'Numero Caja', 'text', NULL, 1, '2025-04-14', '2025-04-14 21:58:24');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `files`
--

CREATE TABLE `files` (
  `id_file` int(11) NOT NULL,
  `id_folder_file` int(11) DEFAULT 0,
  `name_file` text DEFAULT NULL,
  `extension_file` text DEFAULT NULL,
  `type_file` text DEFAULT NULL,
  `size_file` double DEFAULT 0,
  `link_file` text DEFAULT NULL,
  `thumbnail_vimeo_file` text DEFAULT NULL,
  `id_mailchimp_file` text DEFAULT NULL,
  `date_created_file` date DEFAULT NULL,
  `date_updated_file` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `files`
--

INSERT INTO `files` (`id_file`, `id_folder_file`, `name_file`, `extension_file`, `type_file`, `size_file`, `link_file`, `thumbnail_vimeo_file`, `id_mailchimp_file`, `date_created_file`, `date_updated_file`) VALUES
(1, 1, '674dfdf7195d735', 'png', 'image/png', 918215, 'http://cms.pos.com/views/assets/files/6760a08e6d34e6.png', NULL, NULL, '2024-12-16', '2025-07-01 21:45:14'),
(2, 1, '674e11c5ce4055', 'png', 'image/png', 2945, 'http://cms.pos.com/views/assets/files/67632dad8945845.png', NULL, NULL, '2024-12-18', '2025-07-01 21:45:14'),
(3, 1, '674e120172bb75', 'png', 'image/png', 3709, 'http://cms.pos.com/views/assets/files/67632df45101e56.png', NULL, NULL, '2024-12-18', '2025-07-01 21:45:14'),
(4, 1, '674e121bf13c531', 'png', 'image/png', 4095, 'http://cms.pos.com/views/assets/files/67632e0cbf0a320.png', NULL, NULL, '2024-12-18', '2025-07-01 21:45:14'),
(5, 1, '674e127c5d2498', 'png', 'image/png', 2209, 'http://cms.pos.com/views/assets/files/67632e2558a3145.png', NULL, NULL, '2024-12-18', '2025-07-01 21:45:14'),
(6, 1, '674e12a07b01a44', 'png', 'image/png', 4586, 'http://cms.pos.com/views/assets/files/67632e3962b825.png', NULL, NULL, '2024-12-18', '2025-07-01 21:45:14'),
(7, 1, '674e153f3200655', 'png', 'image/png', 6759, 'http://cms.pos.com/views/assets/files/676333a5a17a913.png', NULL, NULL, '2024-12-18', '2025-07-01 21:45:14'),
(8, 1, '674e165bedcfd39', 'png', 'image/png', 8880, 'http://cms.pos.com/views/assets/files/6763359f5e7e639.png', NULL, NULL, '2024-12-18', '2025-07-01 21:45:14'),
(9, 1, '674e169291f1134', 'png', 'image/png', 12674, 'http://cms.pos.com/views/assets/files/676335bf88cd611.png', NULL, NULL, '2024-12-18', '2025-07-01 21:45:14'),
(10, 1, '674e16e1b80a153', 'png', 'image/png', 10279, 'http://cms.pos.com/views/assets/files/676335e7b765751.png', NULL, NULL, '2024-12-18', '2025-07-01 21:45:14'),
(11, 1, '674e17362b0eb18', 'png', 'image/png', 8830, 'http://cms.pos.com/views/assets/files/676336050329e21.png', NULL, NULL, '2024-12-18', '2025-07-01 21:45:14'),
(12, 1, '674e17f3d32aa27', 'png', 'image/png', 8335, 'http://cms.pos.com/views/assets/files/6763362601cc654.png', NULL, NULL, '2024-12-18', '2025-07-01 21:45:14'),
(13, 1, '674e1818b981f4', 'png', 'image/png', 10435, 'http://cms.pos.com/views/assets/files/6763364983cdc29.png', NULL, NULL, '2024-12-18', '2025-07-01 21:45:14'),
(14, 1, '674e1c3aed10842', 'png', 'image/png', 7847, 'http://cms.pos.com/views/assets/files/6763368780d2c31.png', NULL, NULL, '2024-12-18', '2025-07-01 21:45:14'),
(15, 1, '674e1c695cead29', 'png', 'image/png', 11802, 'http://cms.pos.com/views/assets/files/676336be037ba26.png', NULL, NULL, '2024-12-18', '2025-07-01 21:45:14'),
(16, 1, '674e1c9596eb513', 'png', 'image/png', 14185, 'http://cms.pos.com/views/assets/files/676336d8ae17952.png', NULL, NULL, '2024-12-18', '2025-07-01 21:45:14'),
(17, 1, '674e1e2a37cea58', 'png', 'image/png', 11585, 'http://cms.pos.com/views/assets/files/676336fb70b6d27.png', NULL, NULL, '2024-12-18', '2025-07-01 21:45:14'),
(18, 1, '674e1e0aa0fb026', 'png', 'image/png', 10007, 'http://cms.pos.com/views/assets/files/6763372162e555.png', NULL, NULL, '2024-12-18', '2025-07-01 21:45:14'),
(19, 1, '674e1d54eb75924', 'png', 'image/png', 10979, 'http://cms.pos.com/views/assets/files/6763375d7ae0e5.png', NULL, NULL, '2024-12-18', '2025-07-01 21:45:14'),
(20, 1, '674e1d83c2b1011', 'png', 'image/png', 10505, 'http://cms.pos.com/views/assets/files/676337786b5b132.png', NULL, NULL, '2024-12-18', '2025-07-01 21:45:14'),
(21, 1, '6751c1a6b299418', 'png', 'image/png', 1072, 'http://cms.pos.com/views/assets/files/67659e224786f6.png', NULL, NULL, '2024-12-20', '2025-07-01 21:45:14'),
(22, 1, 'ventilador_ml', 'webp', 'image/webp', 5542, 'http://cms.pos.com/views/assets/files/68011f581077844.webp', NULL, NULL, '2025-04-17', '2025-07-01 21:45:14');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `folders`
--

CREATE TABLE `folders` (
  `id_folder` int(11) NOT NULL,
  `name_folder` text DEFAULT NULL,
  `size_folder` text DEFAULT NULL,
  `total_folder` double DEFAULT 0,
  `max_upload_folder` text DEFAULT NULL,
  `url_folder` text DEFAULT NULL,
  `keys_folder` text DEFAULT NULL,
  `date_created_folder` date DEFAULT NULL,
  `date_updated_folder` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `folders`
--

INSERT INTO `folders` (`id_folder`, `name_folder`, `size_folder`, `total_folder`, `max_upload_folder`, `url_folder`, `keys_folder`, `date_created_folder`, `date_updated_folder`) VALUES
(1, 'Server', '200000000000', 1085475, '500000000', 'http://cms.pos.com', NULL, '2024-12-16', '2025-07-01 21:46:47');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `informations`
--

CREATE TABLE `informations` (
  `id_information` int(11) NOT NULL,
  `name_information` text DEFAULT NULL,
  `ruc_information` text DEFAULT NULL,
  `address_matriz_information` text DEFAULT NULL,
  `address_establishment_information` text DEFAULT NULL,
  `name_comercial_information` text DEFAULT NULL,
  `email_information` text DEFAULT NULL,
  `phone_information` text DEFAULT NULL,
  `id_office_information` int(11) DEFAULT 0,
  `date_created_information` date DEFAULT NULL,
  `date_updated_information` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `informations`
--

INSERT INTO `informations` (`id_information`, `name_information`, `ruc_information`, `address_matriz_information`, `address_establishment_information`, `name_comercial_information`, `email_information`, `phone_information`, `id_office_information`, `date_created_information`, `date_updated_information`) VALUES
(1, 'FRIO POLO NORTE', '0106316441001', 'Barrio: CDLA LAS ORQUIDEAS Calle: MZ 63 Número: 520 Intersección: SL 14 Referencia: JUNTO A DISENSA', 'Barrio: CDLA LAS ORQUIDEAS Calle: MZ 63 Número: 520 Intersección: SL 14 Referencia: JUNTO A DISENSA', 'FRIO POLO NORTE', 'frioexpressnorte05@gmail.com', '0983381344', 1, '2025-04-10', '2025-04-17 20:55:06'),
(2, 'MARIBEL+MARIA+LINDAO+SORIANO', '0915387815001', 'MUCHOLOTE+1', 'MUCHOLOTE+1', 'EMPRESA+TEST+S.A', 'mlindao72%40hotmail.com', '0984084043', 3, '2025-04-14', '2025-04-14 20:47:01');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `invoices`
--

CREATE TABLE `invoices` (
  `id_invoice` int(11) NOT NULL,
  `id_order_invoce` int(11) DEFAULT 0,
  `access_key_invoice` text DEFAULT NULL,
  `status_invoice` text DEFAULT NULL,
  `authorization_date_invoice` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `pdf_invoice` text DEFAULT NULL,
  `date_created_invoice` date DEFAULT NULL,
  `date_updated_invoice` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `modules`
--

CREATE TABLE `modules` (
  `id_module` int(11) NOT NULL,
  `id_page_module` int(11) DEFAULT 0,
  `type_module` text DEFAULT NULL,
  `title_module` text DEFAULT NULL,
  `suffix_module` text DEFAULT NULL,
  `content_module` text DEFAULT NULL,
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
(2, 2, 'tables', 'admins', 'admin', '', 100, 0, '2024-12-16', '2024-12-19 20:12:22'),
(3, 4, 'breadcrumbs', 'sucursales', '', '', 100, 1, '2024-12-17', '2024-12-16 23:10:34'),
(4, 4, 'tables', 'offices', 'office', '', 100, 1, '2024-12-17', '2024-12-16 23:17:23'),
(5, 5, 'breadcrumbs', 'clientes', '', '', 100, 1, '2024-12-18', '2024-12-18 19:33:43'),
(6, 5, 'tables', 'clients', 'client', '', 100, 1, '2024-12-18', '2024-12-18 19:37:39'),
(7, 6, 'breadcrumbs', 'categorías', '', '', 100, 1, '2024-12-18', '2024-12-18 20:12:25'),
(8, 6, 'tables', 'categories', 'category', '', 100, 1, '2024-12-18', '2024-12-18 20:14:59'),
(9, 7, 'breadcrumbs', 'productos', '', '', 100, 1, '2024-12-18', '2024-12-18 20:33:10'),
(10, 7, 'tables', 'products', 'product', '', 100, 1, '2024-12-18', '2024-12-18 20:38:30'),
(11, 8, 'breadcrumbs', 'compras', '', '', 100, 1, '2024-12-18', '2024-12-18 21:37:39'),
(12, 8, 'tables', 'purchases', 'purchase', '', 100, 1, '2024-12-18', '2024-12-18 21:43:57'),
(13, 9, 'breadcrumbs', 'Órdenes', '', '', 100, 1, '2024-12-18', '2024-12-18 22:35:32'),
(14, 9, 'tables', 'orders', 'order', '', 100, 0, '2024-12-18', '2024-12-18 22:45:34'),
(15, 10, 'breadcrumbs', 'ventas', '', '', 100, 1, '2024-12-18', '2024-12-18 22:50:59'),
(16, 10, 'tables', 'sales', 'sale', '', 100, 0, '2024-12-18', '2024-12-18 22:54:24'),
(17, 11, 'breadcrumbs', 'caja', '', '', 100, 1, '2024-12-19', '2024-12-18 23:02:12'),
(18, 11, 'tables', 'cashs', 'cash', '', 100, 1, '2024-12-19', '2024-12-18 23:09:25'),
(19, 12, 'breadcrumbs', 'gastos', '', '', 100, 1, '2024-12-19', '2024-12-18 23:12:39'),
(20, 12, 'tables', 'bills', 'bill', '', 100, 1, '2024-12-19', '2024-12-18 23:14:38'),
(21, 1, 'custom', 'orders', '', '', 100, 1, '2024-12-20', '2024-12-20 16:00:40'),
(22, 1, 'custom', 'products', '', '', 50, 1, '2024-12-20', '2024-12-20 16:02:03'),
(23, 1, 'custom', 'panel', '', '', 50, 1, '2024-12-20', '2024-12-20 16:02:18'),
(24, 13, 'metrics', 'ventas', '', '{\"type\":\"add\",\"table\":\"orders\", \"column\":\"total_order\",\"config\":\"price\",\"icon\":\"fas fa-cart-arrow-down\",\"color\":\"28, 175, 159\"  }', 25, 1, '2025-01-02', '2025-01-02 21:09:09'),
(25, 13, 'metrics', 'compras', '', '{\"type\":\"add\",\"table\":\"purchases\", \"column\":\"invest_purchase\",\"config\":\"price\",\"icon\":\"fas fa-shopping-basket\",\"color\":\"128, 0, 0\"  }', 25, 1, '2025-01-02', '2025-01-02 21:11:06'),
(26, 13, 'metrics', 'productos', '', '{\"type\":\"add\",\"table\":\"products\", \"column\":\"stock_product\",\"config\":\"unit\",\"icon\":\"fas fa-box\",\"color\":\"77, 93, 219\"  }', 25, 1, '2025-01-02', '2025-01-02 21:12:50'),
(27, 13, 'metrics', 'clientes', '', '{\"type\":\"total\",\"table\":\"clients\", \"column\":\"id_client\",\"config\":\"unit\",\"icon\":\"fas fa-users\",\"color\":\"43, 62, 101\"  }', 25, 1, '2025-01-02', '2025-01-02 21:13:46'),
(28, 13, 'graphics', 'gráfico de ventas diarias', '', '{\"type\":\"bar\",\"table\":\"orders\",\"xAxis\":\"date_created_order\",\"yAxis\":\"total_order\",\"color\":\"134, 153, 163\"}', 100, 1, '2025-01-02', '2025-01-02 21:28:51'),
(29, 13, 'graphics', 'gráfico de ventas mensuales', '', '{\"type\":\"line\",\"table\":\"orders\",\"xAxis\":\"date_created_order\",\"yAxis\":\"total_order\",\"color\":\"252, 115, 3\"}', 100, 1, '2025-01-02', '2025-01-02 22:03:38'),
(30, 13, 'graphics', 'ventas por sucursal', '', '{\"type\":\"bar\",\"table\":\"orders\",\"xAxis\":\"id_office_order\",\"yAxis\":\"total_order\",\"color\":\"28, 175, 159\"}', 50, 1, '2025-01-02', '2025-01-02 22:44:48'),
(31, 13, 'graphics', 'compras por sucursal', '', '{\"type\":\"bar\",\"table\":\"purchases\",\"xAxis\":\"id_office_purcahse\",\"yAxis\":\"invest_purchase\",\"color\":\"137, 39, 236\"}', 50, 1, '2025-01-02', '2025-01-02 23:12:33'),
(33, 13, 'custom', 'clientes mas activos', '', '', 50, 1, '2025-01-03', '2025-01-03 14:05:22'),
(37, 13, 'custom', 'productos mas vendidos', '', '', 50, 1, '2025-03-11', '2025-03-11 05:34:46'),
(38, 14, 'tables', 'informations', 'information', '', 100, 1, '2025-04-10', '2025-04-10 16:56:47'),
(39, 15, 'tables', 'invoices', 'invoice', '', 100, 1, '2025-04-13', '2025-04-13 07:43:48'),
(40, 16, 'tables', 'secuencials', 'secuencial', '', 100, 1, '2025-04-14', '2025-04-14 15:35:36');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `offices`
--

CREATE TABLE `offices` (
  `id_office` int(11) NOT NULL,
  `title_office` text DEFAULT NULL,
  `address_office` text DEFAULT NULL,
  `phone_office` text DEFAULT NULL,
  `dni_office` text DEFAULT NULL,
  `id_local_office` int(11) DEFAULT 0,
  `address_matriz_office` text DEFAULT NULL,
  `company_name_office` text DEFAULT NULL,
  `email_office` text DEFAULT NULL,
  `date_created_office` date DEFAULT NULL,
  `date_updated_office` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `offices`
--

INSERT INTO `offices` (`id_office`, `title_office`, `address_office`, `phone_office`, `dni_office`, `id_local_office`, `address_matriz_office`, `company_name_office`, `email_office`, `date_created_office`, `date_updated_office`) VALUES
(1, 'Frio Polo Norte', 'PEDRO PABLO GOMEZ Número: 520 Intersección: AV QUITO Y AV MACHALA', '0983381344', '0106316441001', 1, 'PEDRO PABLO GOMEZ Número: 520 Intersección: AV QUITO Y AV MACHALA', 'PULLA IÑIGUEZ MARCELO RAFAEL', 'frioexpressnorte05@gmail.com', '2024-12-17', '2025-04-11 16:33:27'),
(2, 'Sucursal Colinas del Monte', 'Calle 67 # 45 67', '6043218798', '099999999', 2, NULL, NULL, NULL, '2024-12-17', '2025-04-11 14:00:12'),
(3, 'Negocio TEST', 'Mucholote 1 6ta etapa', '0992519289', '0915387815001', 1, 'Mucholote 1 6ta etapa', 'MARIBEL MARIA LINDAO SORIANO', 'mlindao72@hotmail.com', '2024-12-17', '2025-04-13 07:48:21');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `orders`
--

CREATE TABLE `orders` (
  `id_order` int(11) NOT NULL,
  `transaction_order` text DEFAULT NULL,
  `id_admin_order` int(11) DEFAULT 0,
  `id_client_order` int(11) DEFAULT 0,
  `subtotal_order` double DEFAULT 0,
  `discount_order` double DEFAULT 0,
  `tax_order` double DEFAULT 0,
  `total_order` double DEFAULT 0,
  `method_order` text DEFAULT NULL,
  `transfer_order` text DEFAULT NULL,
  `status_order` text DEFAULT NULL,
  `date_order` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `id_office_order` int(11) DEFAULT 0,
  `date_created_order` date DEFAULT NULL,
  `date_updated_order` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `orders`
--

INSERT INTO `orders` (`id_order`, `transaction_order`, `id_admin_order`, `id_client_order`, `subtotal_order`, `discount_order`, `tax_order`, `total_order`, `method_order`, `transfer_order`, `status_order`, `date_order`, `id_office_order`, `date_created_order`, `date_updated_order`) VALUES
(1, '192896735117', 1, 10, 0, 0, 0, 0, NULL, NULL, 'Pendiente', '2025-01-02 21:38:51', 1, '2024-10-10', '2025-01-02 21:38:51'),
(4, '853516679348', 1, 9, 6391.7, 839.16, 1054.98, 6607.52, 'efectivo', '', 'Completada', '2025-01-02 22:45:50', 1, '2024-11-20', '2025-01-02 22:45:50'),
(5, '988567392254', 1, 1, 838.6, 0, 159.33, 997.93, 'efectivo', '', 'Completada', '2025-01-02 22:45:07', 2, '2024-12-25', '2025-01-02 22:45:07'),
(6, '626919863779', 1, 3, 518.7, 0, 98.55, 617.25, 'efectivo', '', 'Completada', '2025-01-02 22:45:53', 3, '2024-12-27', '2025-01-02 22:45:53'),
(7, '723935942625', 1, 1, 1957.2, 0, 371.87, 2329.07, 'efectivo', '', 'Completada', '2025-03-10 01:53:31', 1, '2025-03-09', '2025-03-10 01:53:31'),
(8, '137915447282', 1, 1, 1118.6, 0, 212.53, 1331.13, 'efectivo', '', 'Completada', '2025-04-10 17:58:47', 1, '2025-04-10', '2025-04-10 17:58:47'),
(11, '347949215165', 1, 1, 1957.2, 0, 371.87, 2329.07, 'efectivo', '', 'Completada', '2025-04-10 21:56:28', 1, '2025-04-10', '2025-04-10 21:56:28'),
(13, '873577851293', 1, 1, 1118.6, 0, 212.53, 1331.13, 'efectivo', '', 'Completada', '2025-04-10 22:28:28', 1, '2025-04-10', '2025-04-10 22:28:28'),
(14, '941125561643', 1, 1, 1957.2, 0, 371.87, 2329.07, 'efectivo', '', 'Completada', '2025-04-11 10:54:30', 1, '2025-04-11', '2025-04-11 10:54:30'),
(15, '541619224765', 1, 1, 1957.2, 0, 371.87, 2329.07, 'efectivo', '', 'Completada', '2025-04-11 11:08:05', 1, '2025-04-11', '2025-04-11 11:08:05'),
(16, '562716418352', 1, 1, 1957.2, 0, 371.87, 2329.07, 'efectivo', '', 'Completada', '2025-04-11 11:16:31', 1, '2025-04-11', '2025-04-11 11:16:31'),
(17, '192462157651', 1, 2, 5733.42, 293.71, 815.96, 6255.67, 'efectivo', '', 'Completada', '2025-04-11 11:58:53', 1, '2025-04-11', '2025-04-11 11:58:53'),
(18, '629311355874', 1, 2, 1957.2, 0, 293.58, 2250.78, 'efectivo', '', 'Completada', '2025-04-11 14:05:58', 1, '2025-04-11', '2025-04-11 14:05:58'),
(19, '371496381718', 1, 3, 2237.2, 0, 335.58, 2572.78, 'efectivo', '', 'Completada', '2025-04-11 14:08:46', 1, '2025-04-11', '2025-04-11 14:08:46'),
(20, '228694345611', 1, 5, 1677.2, 0, 251.58, 1928.78, 'efectivo', '', 'Completada', '2025-04-11 14:09:44', 1, '2025-04-11', '2025-04-11 14:09:44'),
(21, '715496631571', 1, 4, 1957.2, 0, 293.58, 2250.78, 'efectivo', '', 'Completada', '2025-04-11 14:18:26', 1, '2025-04-11', '2025-04-11 14:18:26'),
(22, '415517235973', 1, 1, 4054.82, 293.71, 564.17, 4325.28, 'efectivo', '', 'Completada', '2025-04-11 15:35:30', 1, '2025-04-11', '2025-04-11 15:35:30'),
(23, '289183946762', 1, 5, 3915.24, 587.41, 499.17, 3827, 'efectivo', '', 'Completada', '2025-04-11 15:40:49', 1, '2025-04-11', '2025-04-11 15:40:49'),
(24, '934627281135', 1, 6, 1957.2, 0, 293.58, 2250.78, 'efectivo', '', 'Completada', '2025-04-11 17:00:56', 1, '2025-04-11', '2025-04-11 17:00:56'),
(25, '875948272567', 1, 1, 2073.5, 0, 393.96, 2467.46, 'efectivo', '', 'Completada', '2025-04-11 18:20:08', 1, '2025-04-11', '2025-04-11 18:20:08'),
(26, '656774481235', 1, 1, 1957.2, 0, 293.58, 2250.78, 'efectivo', '', 'Completada', '2025-04-11 18:22:44', 1, '2025-04-11', '2025-04-11 18:22:44'),
(27, '315748529766', 1, 11, 1118.6, 0, 167.79, 1286.39, 'efectivo', '', 'Completada', '2025-04-11 18:29:32', 1, '2025-04-11', '2025-04-11 18:29:32'),
(28, '688354319354', 1, 1, 3076.64, 587.41, 373.38, 2862.61, 'efectivo', '', 'Completada', '2025-04-11 18:39:59', 1, '2025-04-11', '2025-04-11 18:39:59'),
(29, '753814852341', 1, 1, 1118.6, 0, 167.79, 1286.39, 'efectivo', '', 'Completada', '2025-04-11 20:22:33', 1, '2025-04-11', '2025-04-11 20:22:33'),
(30, '826241559737', 1, 1, 1118.6, 0, 167.79, 1286.39, 'efectivo', '', 'Completada', '2025-04-11 20:25:17', 1, '2025-04-11', '2025-04-11 20:25:17'),
(31, '218551217747', 1, 1, 360, 0, 68.4, 428.4, 'efectivo', '', 'Completada', '2025-04-11 20:23:49', 1, '2025-04-11', '2025-04-11 20:23:49'),
(32, '461723557457', 1, 1, 1957.2, 0, 293.58, 2250.78, 'efectivo', '', 'Completada', '2025-04-11 21:27:45', 1, '2025-04-11', '2025-04-11 21:27:45'),
(33, '421157243196', 1, 1, 1037.4, 0, 197.11, 1234.51, 'efectivo', '', 'Completada', '2025-04-11 21:30:03', 1, '2025-04-11', '2025-04-11 21:30:03'),
(34, '227466148945', 1, 1, 979.02, 293.71, 102.8, 788.11, 'efectivo', '', 'Completada', '2025-04-11 21:37:27', 1, '2025-04-11', '2025-04-11 21:37:27'),
(35, '636845941723', 1, 1, 2237.2, 0, 335.58, 2572.78, 'efectivo', '', 'Completada', '2025-04-11 21:41:12', 1, '2025-04-11', '2025-04-11 21:41:12'),
(36, '474815738663', 1, 1, 1118.6, 0, 167.79, 1286.39, 'efectivo', '', 'Completada', '2025-04-11 21:44:57', 1, '2025-04-11', '2025-04-11 21:44:57'),
(37, '996121185737', 1, 1, 1957.2, 0, 293.58, 2250.78, 'efectivo', '', 'Completada', '2025-04-11 21:57:45', 1, '2025-04-11', '2025-04-11 21:57:45'),
(38, '779134654178', 1, 1, 1957.2, 0, 293.58, 2250.78, 'efectivo', '', 'Completada', '2025-04-11 22:05:01', 1, '2025-04-11', '2025-04-11 22:05:01'),
(39, '951492725683', 1, 1, 838.6, 0, 125.79, 964.39, 'efectivo', '', 'Completada', '2025-04-11 22:06:36', 1, '2025-04-11', '2025-04-11 22:06:36'),
(40, '777652261513', 1, 1, 2332.2, 0, 443.12, 2775.32, 'efectivo', '', 'Completada', '2025-04-11 22:08:06', 1, '2025-04-11', '2025-04-11 22:08:06'),
(41, '746191194535', 1, 1, 2936.22, 293.71, 396.38, 3038.89, 'efectivo', '', 'Completada', '2025-04-11 22:17:38', 1, '2025-04-11', '2025-04-11 22:17:38'),
(42, '257831665944', 1, 1, 2097.62, 293.71, 270.59, 2074.5, 'efectivo', '', 'Completada', '2025-04-11 22:22:39', 1, '2025-04-11', '2025-04-11 22:22:39'),
(43, '363274466192', 1, 1, 838.6, 0, 125.79, 964.39, 'efectivo', '', 'Completada', '2025-04-11 22:27:11', 1, '2025-04-11', '2025-04-11 22:27:11'),
(44, '486174395771', 1, 1, 1957.2, 0, 293.58, 2250.78, 'efectivo', '', 'Completada', '2025-04-11 22:36:38', 1, '2025-04-11', '2025-04-11 22:36:38'),
(45, '785449631369', 1, 1, 1118.6, 0, 167.79, 1286.39, 'efectivo', '', 'Completada', '2025-04-11 22:39:58', 1, '2025-04-11', '2025-04-11 22:39:58'),
(46, '124463257359', 1, 1, 1957.2, 0, 293.58, 2250.78, 'efectivo', '', 'Completada', '2025-04-12 04:20:20', 1, '2025-04-11', '2025-04-12 04:20:20'),
(47, '782147417893', 1, 1, 1118.6, 0, 167.79, 1286.39, 'efectivo', '', 'Completada', '2025-04-12 04:23:34', 1, '2025-04-11', '2025-04-12 04:23:34'),
(48, '468522279651', 1, 1, 1957.2, 0, 293.58, 2250.78, 'efectivo', '', 'Completada', '2025-04-12 04:25:08', 1, '2025-04-11', '2025-04-12 04:25:08'),
(49, '223982317695', 1, 1, 838.6, 0, 125.79, 964.39, 'efectivo', '', 'Completada', '2025-04-12 04:28:10', 1, '2025-04-11', '2025-04-12 04:28:10'),
(50, '316518235589', 1, 1, 1957.2, 0, 293.58, 2250.78, 'efectivo', '', 'Completada', '2025-04-12 04:37:12', 1, '2025-04-11', '2025-04-12 04:37:12'),
(51, '851192524357', 1, 1, 838.6, 0, 125.79, 964.39, 'efectivo', '', 'Completada', '2025-04-12 04:39:06', 1, '2025-04-11', '2025-04-12 04:39:06'),
(52, '256633699841', 1, 1, 1957.2, 0, 293.58, 2250.78, 'efectivo', '', 'Completada', '2025-04-12 16:05:51', 1, '2025-04-12', '2025-04-12 16:05:51'),
(53, '465549683126', 1, 1, 3076.64, 587.41, 373.38, 2862.61, 'efectivo', '', 'Completada', '2025-04-12 16:07:38', 1, '2025-04-12', '2025-04-12 16:07:38'),
(54, '573851695416', 1, 1, 2097.62, 293.71, 270.59, 2074.5, 'efectivo', '', 'Completada', '2025-04-12 16:37:11', 1, '2025-04-12', '2025-04-12 16:37:11'),
(55, '154138246897', 1, 1, 1118.6, 0, 167.79, 1286.39, 'efectivo', '', 'Completada', '2025-04-12 16:47:54', 1, '2025-04-12', '2025-04-12 16:47:54'),
(56, '815671433965', 1, 1, 838.6, 0, 125.79, 964.39, 'efectivo', '', 'Completada', '2025-04-12 16:53:24', 1, '2025-04-12', '2025-04-12 16:53:24'),
(57, '412325383817', 1, 1, 1118.6, 0, 167.79, 1286.39, 'efectivo', '', 'Completada', '2025-04-12 16:54:37', 1, '2025-04-12', '2025-04-12 16:54:37'),
(58, '543993812775', 1, 1, 1787.3, 0, 339.59, 2126.89, 'efectivo', '', 'Completada', '2025-04-13 07:51:18', 3, '2025-04-13', '2025-04-13 07:51:18'),
(59, '125253243171', 1, 1, 3465.9, 0, 658.52, 4124.42, 'efectivo', '', 'Completada', '2025-04-13 07:53:29', 3, '2025-04-13', '2025-04-13 07:53:29'),
(60, '785552461343', 1, 1, 1218.6, 0, 231.53, 1450.13, 'efectivo', '', 'Completada', '2025-04-13 07:55:13', 3, '2025-04-13', '2025-04-13 07:55:13'),
(61, '157316626137', 1, 1, 498.7, 0, 94.75, 593.45, 'efectivo', '', 'Completada', '2025-04-13 07:57:16', 3, '2025-04-13', '2025-04-13 07:57:16'),
(62, '972935145688', 1, 1, 498.7, 0, 94.75, 593.45, 'efectivo', '', 'Completada', '2025-04-13 08:02:20', 3, '2025-04-13', '2025-04-13 08:02:20'),
(63, '293171687258', 1, 1, 498.7, 0, 74.81, 573.51, 'efectivo', '', 'Completada', '2025-04-13 08:30:09', 3, '2025-04-13', '2025-04-13 08:30:09'),
(64, '688926533749', 1, 1, 498.7, 0, 74.81, 573.51, 'efectivo', '', 'Completada', '2025-04-13 08:39:22', 3, '2025-04-13', '2025-04-13 08:39:22'),
(65, '739132145273', 1, 1, 2795.8, 0, 531.2, 3327, 'efectivo', '', 'Completada', '2025-04-13 09:15:23', 3, '2025-04-13', '2025-04-13 09:15:23'),
(66, '277669534924', 1, 1, 3075.8, 0, 584.4, 3660.2, 'efectivo', '', 'Completada', '2025-04-13 09:18:20', 3, '2025-04-13', '2025-04-13 09:18:20'),
(67, '716846359381', 1, 1, 2795.8, 0, 531.2, 3327, 'efectivo', '', 'Completada', '2025-04-13 09:23:28', 3, '2025-04-13', '2025-04-13 09:23:28'),
(68, '213673154847', 1, 1, 5801.8, 0, 1102.34, 6904.14, 'efectivo', '', 'Completada', '2025-04-13 09:25:15', 3, '2025-04-13', '2025-04-13 09:25:15'),
(69, '119764416534', 1, 1, 6649.3, 0, 1263.37, 7912.67, 'efectivo', '', 'Completada', '2025-04-13 09:26:22', 3, '2025-04-13', '2025-04-13 09:26:22'),
(70, '597318432324', 1, 1, 3635.8, 0, 690.8, 4326.6, 'efectivo', '', 'Completada', '2025-04-13 09:29:29', 3, '2025-04-13', '2025-04-13 09:29:29'),
(71, '347251921633', 1, 1, 3075.8, 0, 461.37, 3537.17, 'efectivo', '', 'Completada', '2025-04-13 09:35:47', 3, '2025-04-13', '2025-04-13 09:35:47'),
(72, '633798155366', 1, 1, 1398.6, 0, 209.79, 1608.39, 'efectivo', '', 'Completada', '2025-04-14 15:48:48', 3, '2025-04-14', '2025-04-14 15:48:48'),
(73, '682916534572', 1, 1, 388.7, 0, 58.3, 447, 'efectivo', '', 'Completada', '2025-04-14 15:50:58', 3, '2025-04-14', '2025-04-14 15:50:58'),
(74, '366231856342', 1, 1, 1398.6, 0, 209.79, 1608.39, 'efectivo', '', 'Completada', '2025-04-14 16:02:25', 3, '2025-04-14', '2025-04-14 16:02:25'),
(75, '451992772414', 1, 1, 2237.2, 0, 335.58, 2572.78, 'efectivo', '', 'Completada', '2025-04-14 16:23:14', 3, '2025-04-14', '2025-04-14 16:23:14'),
(76, '945874516398', 1, 1, 1118.6, 0, 167.79, 1286.39, 'efectivo', '', 'Completada', '2025-04-14 20:53:56', 3, '2025-04-14', '2025-04-14 20:53:56'),
(77, '362644429576', 1, 1, 838.6, 0, 125.79, 964.39, 'efectivo', '', 'Completada', '2025-04-14 20:55:22', 3, '2025-04-14', '2025-04-14 20:55:22'),
(78, '634217648129', 1, 1, 838.6, 0, 125.79, 964.39, 'efectivo', '', 'Completada', '2025-04-14 21:00:27', 3, '2025-04-14', '2025-04-14 21:00:27'),
(79, '167954321754', 1, 1, 2515.8, 0, 377.37, 2893.17, 'efectivo', '', 'Completada', '2025-04-14 21:04:23', 3, '2025-04-14', '2025-04-14 21:04:23'),
(80, '896133644597', 1, 1, 2237.2, 0, 335.58, 2572.78, 'efectivo', '', 'Completada', '2025-04-14 21:07:28', 3, '2025-04-14', '2025-04-14 21:07:28'),
(81, '337321425981', 1, 1, 777.4, 0, 116.61, 894.01, 'efectivo', '', 'Completada', '2025-04-14 21:08:47', 3, '2025-04-14', '2025-04-14 21:08:47'),
(82, '561912619637', 6, 1, 838.6, 0, 125.79, 964.39, 'efectivo', '', 'Completada', '2025-04-14 22:36:47', 3, '2025-04-14', '2025-04-14 22:36:47'),
(83, '582795738141', 6, 1, 838.6, 0, 125.79, 964.39, 'efectivo', '', 'Completada', '2025-04-14 22:39:45', 3, '2025-04-14', '2025-04-14 22:39:45'),
(84, '722217335637', 6, 1, 838.6, 0, 125.79, 964.39, 'efectivo', '', 'Completada', '2025-04-15 04:06:01', 3, '2025-04-14', '2025-04-15 04:06:01'),
(85, '412179568726', 6, 1, 1118.6, 0, 167.79, 1286.39, 'efectivo', '', 'Completada', '2025-04-15 04:07:57', 3, '2025-04-14', '2025-04-15 04:07:57'),
(86, '952681453649', 6, 1, 1398.6, 0, 209.79, 1608.39, 'efectivo', '', 'Completada', '2025-04-15 04:10:10', 3, '2025-04-14', '2025-04-15 04:10:10'),
(87, '341867852623', 6, 1, 1118.6, 0, 167.79, 1286.39, 'efectivo', '', 'Completada', '2025-04-15 04:17:37', 3, '2025-04-14', '2025-04-15 04:17:37'),
(88, '773315665367', 6, 1, 388.7, 0, 58.3, 447, 'efectivo', '', 'Completada', '2025-04-15 04:19:27', 3, '2025-04-14', '2025-04-15 04:19:27'),
(89, '359437162185', 6, 1, 388.7, 0, 58.3, 447, 'efectivo', '', 'Completada', '2025-04-15 04:21:03', 3, '2025-04-14', '2025-04-15 04:21:03'),
(90, '968182442771', 1, 1, 838.6, 0, 125.79, 964.39, 'efectivo', '', 'Completada', '2025-04-15 04:28:41', 3, '2025-04-14', '2025-04-15 04:28:41'),
(91, '142358174673', 1, 2, 1398.6, 0, 209.79, 1608.39, 'efectivo', '', 'Completada', '2025-04-15 04:29:40', 3, '2025-04-14', '2025-04-15 04:29:40'),
(92, '762591372583', 1, 3, 1398.6, 0, 209.79, 1608.39, 'efectivo', '', 'Completada', '2025-04-15 04:30:45', 3, '2025-04-14', '2025-04-15 04:30:45'),
(93, '437315655894', 1, 2, 1118.6, 0, 167.79, 1286.39, 'efectivo', '', 'Completada', '2025-04-15 04:34:01', 3, '2025-04-14', '2025-04-15 04:34:01'),
(94, '852249812343', 1, 1, 388.7, 0, 58.3, 447, 'efectivo', '', 'Completada', '2025-04-15 04:41:36', 3, '2025-04-14', '2025-04-15 04:41:36'),
(95, '461956347251', 1, 1, 1398.6, 0, 209.79, 1608.39, 'efectivo', '', 'Completada', '2025-04-15 04:43:32', 3, '2025-04-14', '2025-04-15 04:43:32'),
(96, '426965815425', 6, 1, 838.6, 0, 125.79, 964.39, 'efectivo', '', 'Completada', '2025-04-15 04:45:09', 3, '2025-04-14', '2025-04-15 04:45:09'),
(97, '547214573796', 6, 1, 388.7, 0, 58.3, 447, 'efectivo', '', 'Completada', '2025-04-15 04:51:56', 3, '2025-04-14', '2025-04-15 04:51:56'),
(98, '612872297537', 6, 2, 838.6, 0, 125.79, 964.39, 'efectivo', '', 'Completada', '2025-04-15 04:59:05', 3, '2025-04-14', '2025-04-15 04:59:05'),
(99, '454282291615', 4, 1, 5034.4, 0, 755.16, 5789.56, 'efectivo', '', 'Completada', '2025-04-15 05:08:42', 1, '2025-04-15', '2025-04-15 05:08:42'),
(100, '863324977166', 4, 1, 3495.8, 0, 524.37, 4020.17, 'efectivo', '', 'Completada', '2025-04-15 05:13:56', 1, '2025-04-15', '2025-04-15 05:13:56'),
(101, '645265171693', 6, 1, 3355.8, 0, 503.37, 3859.17, 'efectivo', '', 'Completada', '2025-04-16 20:06:01', 3, '2025-04-16', '2025-04-16 20:06:01'),
(102, '287632463159', 6, 1, 3355.8, 0, 503.37, 3859.17, 'efectivo', '', 'Completada', '2025-04-16 20:13:35', 3, '2025-04-16', '2025-04-16 20:13:35'),
(103, '512146319645', 6, 1, 2446, 0, 366.9, 2812.9, 'efectivo', '', 'Completada', '2025-04-16 20:29:30', 3, '2025-04-16', '2025-04-16 20:29:30'),
(104, '543378526961', 6, 1, 2446, 0, 366.9, 2812.9, 'efectivo', '', 'Completada', '2025-04-16 21:24:45', 3, '2025-04-16', '2025-04-16 21:24:45'),
(105, '745487431515', 6, 1, 907.4, 0, 136.11, 1043.51, 'efectivo', '', 'Completada', '2025-04-16 21:26:08', 3, '2025-04-16', '2025-04-16 21:26:08'),
(106, '463472389547', 6, 1, 10254.9, 0, 1538.23, 11793.13, 'efectivo', '', 'Completada', '2025-04-16 21:34:30', 3, '2025-04-16', '2025-04-16 21:34:30'),
(107, '497617475832', 6, 1, 3354.4, 0, 503.16, 3857.56, 'efectivo', '', 'Completada', '2025-04-16 21:52:58', 3, '2025-04-16', '2025-04-16 21:52:58'),
(108, '729985433624', 6, 1, 2446, 0, 366.9, 2812.9, 'efectivo', '', 'Completada', '2025-04-16 22:15:52', 3, '2025-04-16', '2025-04-16 22:15:52'),
(109, '548912723665', 6, 1, 1538.6, 0, 230.79, 1769.39, 'efectivo', '', 'Completada', '2025-04-16 22:26:33', 3, '2025-04-16', '2025-04-16 22:26:33'),
(110, '226415998673', 6, 1, 2446, 0, 366.9, 2812.9, 'efectivo', '', 'Completada', '2025-04-16 22:27:37', 3, '2025-04-16', '2025-04-16 22:27:37'),
(111, '114546823592', 6, 1, 2446, 0, 366.9, 2812.9, 'efectivo', '', 'Completada', '2025-04-16 22:28:46', 3, '2025-04-16', '2025-04-16 22:28:46'),
(112, '739212188544', 6, 1, 2797.2, 0, 419.58, 3216.78, 'efectivo', '', 'Completada', '2025-04-16 22:33:47', 3, '2025-04-16', '2025-04-16 22:33:47'),
(113, '618747952393', 6, 1, 6993, 0, 1048.95, 8041.95, 'efectivo', '', 'Completada', '2025-04-16 22:37:21', 3, '2025-04-16', '2025-04-16 22:37:21'),
(114, '674814536521', 6, 1, 4195.8, 0, 629.37, 4825.17, 'efectivo', '', 'Completada', '2025-04-16 22:41:35', 3, '2025-04-16', '2025-04-16 22:41:35'),
(115, '779531866658', 6, 1, 2515.8, 0, 377.37, 2893.17, 'efectivo', '', 'Completada', '2025-04-16 22:42:59', 3, '2025-04-16', '2025-04-16 22:42:59'),
(116, '953138474326', 6, 1, 3354.4, 0, 503.16, 3857.56, 'efectivo', '', 'Completada', '2025-04-16 22:49:07', 3, '2025-04-16', '2025-04-16 22:49:07'),
(117, '699561538324', 6, 1, 4195.8, 0, 629.37, 4825.17, 'efectivo', '', 'Completada', '2025-04-16 22:50:28', 3, '2025-04-16', '2025-04-16 22:50:28'),
(118, '442857896573', 6, 1, 1398.6, 0, 209.79, 1608.39, 'efectivo', '', 'Completada', '2025-04-16 22:58:11', 3, '2025-04-16', '2025-04-16 22:58:11'),
(119, '479163439865', 6, 1, 1398.6, 0, 209.79, 1608.39, 'efectivo', '', 'Completada', '2025-04-17 12:47:03', 3, '2025-04-17', '2025-04-17 12:47:03'),
(120, '964273378937', 1, 1, 21, 0, 3.15, 24.15, 'efectivo', '', 'Completada', '2025-04-17 15:47:54', 1, '2025-04-17', '2025-04-17 15:47:54'),
(121, '346724156387', 4, 1, 21, 0, 3.15, 24.15, 'efectivo', '', 'Completada', '2025-04-17 15:51:35', 1, '2025-04-17', '2025-04-17 15:51:35'),
(122, '164368568342', 4, 1, 21, 0, 3.15, 24.15, 'efectivo', '', 'Completada', '2025-04-17 16:00:35', 1, '2025-04-17', '2025-04-17 16:00:35'),
(123, '387752932745', 4, 11, 21, 0, 3.15, 24.15, 'efectivo', '', 'Completada', '2025-04-17 16:04:15', 1, '2025-04-17', '2025-04-17 16:04:15'),
(124, '236715951239', 4, 1, 979.02, 293.71, 102.8, 788.11, 'efectivo', '', 'Completada', '2025-04-17 16:42:08', 1, '2025-04-17', '2025-04-17 16:42:08'),
(125, '176966821344', 4, 1, 1118.6, 0, 167.79, 1286.39, 'efectivo', '', 'Completada', '2025-04-17 16:48:15', 1, '2025-04-17', '2025-04-17 16:48:15'),
(126, '432925659741', 4, 1, 979.02, 293.71, 102.8, 788.11, 'efectivo', '', 'Completada', '2025-04-17 17:08:36', 1, '2025-04-17', '2025-04-17 17:08:36'),
(127, '472695884451', 4, 1, 1118.6, 0, 167.79, 1286.39, 'efectivo', '', 'Completada', '2025-04-17 17:09:48', 1, '2025-04-17', '2025-04-17 17:09:48'),
(128, '475261293695', 4, 1, 0, 0, 0, 0, NULL, NULL, 'Pendiente', '2025-04-17 21:38:57', 1, '2025-04-17', '2025-04-17 21:38:57'),
(129, '216532945279', 4, 1, 21, 0, 3.15, 24.15, 'efectivo', '', 'Completada', '2025-05-03 17:04:37', 1, '2025-05-03', '2025-05-03 17:04:37'),
(130, '638579511222', 2, 11, 1979.04, 587.41, 208.74, 1600.37, 'efectivo', '', 'Completada', '2025-05-31 17:11:36', 1, '2025-05-31', '2025-05-31 17:11:36');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pages`
--

CREATE TABLE `pages` (
  `id_page` int(11) NOT NULL,
  `title_page` text DEFAULT NULL,
  `url_page` text DEFAULT NULL,
  `icon_page` text DEFAULT NULL,
  `type_page` text DEFAULT NULL,
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
(3, 'Archivos', 'archivos', 'bi bi-file-earmark-image', 'custom', 3, '2024-12-16', '2024-12-18 23:16:16'),
(4, 'Sucursales', 'sucursales', 'bi bi-shop', 'modules', 4, '2024-12-17', '2024-12-18 23:16:16'),
(5, 'Clientes', 'clientes', 'bi bi-people', 'modules', 5, '2024-12-18', '2024-12-18 23:16:16'),
(6, 'Categorías', 'categorias', 'bi bi-card-list', 'modules', 6, '2024-12-18', '2024-12-18 23:16:16'),
(7, 'Productos', 'productos', 'bi bi-box', 'modules', 7, '2024-12-18', '2024-12-18 23:16:16'),
(8, 'Compras', 'compras', 'bi bi-basket-fill', 'modules', 8, '2024-12-18', '2024-12-18 23:16:16'),
(9, 'Órdenes', 'ordenes', 'bi bi-ticket-detailed', 'modules', 9, '2024-12-18', '2024-12-18 23:16:16'),
(10, 'Ventas', 'ventas', 'bi bi-cash-coin', 'modules', 10, '2024-12-18', '2025-04-14 15:31:00'),
(11, 'Caja', 'caja', 'fas fa-cash-register', 'modules', 11, '2024-12-19', '2025-04-10 16:46:49'),
(12, 'Gastos', 'gastos', 'fas fa-money-bill-wave', 'modules', 12, '2024-12-19', '2024-12-18 23:16:16'),
(13, 'Informes', 'informes', 'bi bi-file-earmark-bar-graph', 'modules', 13, '2025-01-02', '2025-04-10 16:44:10'),
(14, 'Informacion', 'informacion', 'bi bi-briefcase', 'modules', 14, '2025-04-10', '2025-04-13 15:07:58'),
(15, 'Facturación', 'facturacion', 'bi bi-receipt-cutoff', 'modules', 15, '2025-04-13', '2025-04-13 15:07:58'),
(16, 'Secuencial', 'secuencial', 'bi bi-123', 'modules', 1000, '2025-04-14', '2025-04-14 15:31:42');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `products`
--

CREATE TABLE `products` (
  `id_product` int(11) NOT NULL,
  `title_product` text DEFAULT NULL,
  `img_product` text DEFAULT NULL,
  `id_category_product` int(11) DEFAULT 0,
  `sku_product` text DEFAULT NULL,
  `unit_product` text DEFAULT NULL,
  `tax_product` text DEFAULT NULL,
  `rte_product` text DEFAULT NULL,
  `stock_product` int(11) DEFAULT 0,
  `discount_product` double DEFAULT 0,
  `status_product` int(11) DEFAULT 1,
  `id_office_product` int(11) DEFAULT 0,
  `date_created_product` date DEFAULT NULL,
  `date_updated_product` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `products`
--

INSERT INTO `products` (`id_product`, `title_product`, `img_product`, `id_category_product`, `sku_product`, `unit_product`, `tax_product`, `rte_product`, `stock_product`, `discount_product`, `status_product`, `id_office_product`, `date_created_product`, `date_updated_product`) VALUES
(1, 'Airpod+2', 'https://pos.smartposline.com/views%2Fassets%2Ffiles%2F676333a5a17a913.png', 1, 'PT001', 'unidad', 'IVA_15', 'NULL', 100, 0, 1, 1, '2024-12-18', '2025-04-17 15:19:40'),
(2, 'Swagme', 'https://pos.smartposline.com/views%2Fassets%2Ffiles%2F6763359f5e7e639.png', 1, 'PT002', 'unidad', 'IVA_15', 'NULL', 100, 0, 1, 1, '2024-12-18', '2025-04-17 15:21:23'),
(3, 'Red+Nike+Angelo', 'https://pos.smartposline.com/views%2Fassets%2Ffiles%2F676335bf88cd611.png', 2, 'PT003', 'unidad', 'IVA_15', 'NULL', 100, 0, 1, 1, '2024-12-18', '2025-04-17 15:21:23'),
(4, 'Blue+White+OGR', 'https://pos.smartposline.com/views%2Fassets%2Ffiles%2F676335e7b765751.png', 2, 'PT004', 'unidad', 'IVA_15', 'NULL', 100, 0, 1, 1, '2024-12-18', '2025-04-17 15:21:23'),
(5, 'Green+Nike+Fe', 'https://pos.smartposline.com/views%2Fassets%2Ffiles%2F676336050329e21.png', 2, 'PT005', 'unidad', 'IVA_15', 'NULL', 97, 0, 1, 1, '2024-12-18', '2025-04-17 15:21:23'),
(6, 'Iphone+11', 'https://pos.smartposline.com/views%2Fassets%2Ffiles%2F6763362601cc654.png', 3, 'PT006', 'unidad', 'IVA_15', 'NULL', 100, 0, 1, 1, '2024-12-18', '2025-04-17 15:21:23'),
(7, 'IPhone+14+64GB', 'https://pos.smartposline.com/views%2Fassets%2Ffiles%2F6763364983cdc29.png', 3, 'PT007', 'unidad', 'IVA_15', 'NULL', 100, 0, 1, 1, '2024-12-18', '2025-04-17 15:21:23'),
(8, 'Rolex Tribute V3', 'https://pos.smartposline.com/views/assets/files/6763368780d2c31.png', 4, 'PT008', 'unidad', 'IVA_15', 'NULL', 100, 0, 1, 1, '2024-12-18', '2025-04-17 15:23:02'),
(9, 'Timex Black SIlver', 'https://pos.smartposline.com/views/assets/files/676336be037ba26.png', 4, 'PT009', 'unidad', 'Exento de IVA', 'NULL', 90, 0, 1, 1, '2024-12-18', '2025-04-17 15:23:02'),
(10, 'Fossil Pair Of 3 in 1', 'https://pos.smartposline.com/views/assets/files/676336d8ae17952.png', 4, 'PT010', 'unidad', 'No Objeto de Impuesto', 'NULL', 92, 0, 1, 1, '2024-12-18', '2025-04-17 15:23:02'),
(11, 'MacBook Pro', 'https://pos.smartposline.com/views/assets/files/676336fb70b6d27.png', 5, 'PT011', 'unidad', 'IVA_15', 'NULL', 95, 0, 1, 1, '2024-12-18', '2025-04-17 15:23:02'),
(12, 'IdeaPad Slim 5 Gen 7', 'https://pos.smartposline.com/views/assets/files/6763372162e555.png', 5, 'PT012', 'unidad', 'IVA_15', 'NULL', 61, 0, 1, 1, '2024-12-18', '2025-04-17 15:23:02'),
(13, 'Tablet 1.02 inch', 'https://pos.smartposline.com/views/assets/files/6763375d7ae0e5.png', 5, 'PT013', 'unidad', 'IVA_15', 'NULL', 51, 0, 1, 1, '2024-12-18', '2025-04-17 17:09:47'),
(14, 'Yoga Book 9i', 'https://pos.smartposline.com/views/assets/files/676337786b5b132.png', 5, 'PT014', 'unidad', 'IVA_15', 'NULL', 82, 30, 1, 1, '2024-12-18', '2025-05-31 17:11:36'),
(15, 'Airpod+2', 'https://pos.smartposline.com/views%2Fassets%2Ffiles%2F676333a5a17a913.png', 1, 'PT001', 'unidad', 'IVA_15', 'NULL', 100, 0, 1, 2, '2024-12-18', '2025-04-17 15:21:23'),
(16, 'Swagme', 'https://pos.smartposline.com/views%2Fassets%2Ffiles%2F6763359f5e7e639.png', 1, 'PT002', 'unidad', 'IVA_15', 'NULL', 100, 0, 1, 2, '2024-12-18', '2025-04-17 15:21:23'),
(17, 'Red+Nike+Angelo', 'https://pos.smartposline.com/views%2Fassets%2Ffiles%2F676335bf88cd611.png', 2, 'PT003', 'unidad', 'IVA_15', 'NULL', 100, 0, 1, 2, '2024-12-18', '2025-04-17 15:21:23'),
(18, 'Blue+White+OGR', 'https://pos.smartposline.com/views%2Fassets%2Ffiles%2F676335e7b765751.png', 2, 'PT004', 'unidad', 'IVA_15', 'NULL', 100, 0, 1, 2, '2024-12-18', '2025-04-17 15:21:23'),
(19, 'Green+Nike+Fe', 'https://pos.smartposline.com/views%2Fassets%2Ffiles%2F676336050329e21.png', 2, 'PT005', 'unidad', 'IVA_15', 'NULL', 100, 0, 1, 2, '2024-12-18', '2025-04-17 15:21:23'),
(20, 'Iphone+11', 'https://pos.smartposline.com/views%2Fassets%2Ffiles%2F6763362601cc654.png', 3, 'PT006', 'unidad', 'IVA_15', 'NULL', 100, 0, 1, 2, '2024-12-18', '2025-04-17 15:21:23'),
(21, 'IPhone+14+64GB', 'https://pos.smartposline.com/views%2Fassets%2Ffiles%2F6763364983cdc29.png', 3, 'PT007', 'unidad', 'IVA_15', 'NULL', 100, 0, 1, 2, '2024-12-18', '2025-04-17 15:21:23'),
(22, 'Rolex+Tribute+V3', 'https://pos.smartposline.com/views%2Fassets%2Ffiles%2F6763368780d2c31.png', 4, 'PT008', 'unidad', 'IVA_15', 'NULL', 100, 0, 1, 2, '2024-12-18', '2025-04-17 15:21:23'),
(23, 'Timex+Black+SIlver', 'https://pos.smartposline.com/views%2Fassets%2Ffiles%2F676336be037ba26.png', 4, 'PT009', 'unidad', 'IVA_15', 'NULL', 100, 0, 1, 2, '2024-12-18', '2025-04-17 15:21:23'),
(24, 'Fossil+Pair+Of+3+in+1', 'https://pos.smartposline.com/views%2Fassets%2Ffiles%2F676336d8ae17952.png', 4, 'PT010', 'unidad', 'IVA_15', 'NULL', 100, 0, 1, 2, '2024-12-18', '2025-04-17 15:21:23'),
(25, 'MacBook+Pro', 'https://pos.smartposline.com/views%2Fassets%2Ffiles%2F676336fb70b6d27.png', 5, 'PT011', 'unidad', 'IVA_15', 'NULL', 100, 0, 1, 2, '2024-12-18', '2025-04-17 15:21:23'),
(26, 'IdeaPad+Slim+5+Gen+7', 'https://pos.smartposline.com/views%2Fassets%2Ffiles%2F6763372162e555.png', 5, 'PT012', 'unidad', 'IVA_15', 'NULL', 100, 0, 1, 2, '2024-12-18', '2025-04-17 15:21:23'),
(27, 'Tablet+1.02+inch', 'https://pos.smartposline.com/views%2Fassets%2Ffiles%2F6763375d7ae0e5.png', 5, 'PT013', 'unidad', 'IVA_15', 'NULL', 100, 0, 1, 2, '2024-12-18', '2025-04-17 15:21:23'),
(28, 'Yoga+Book+9i', 'https://pos.smartposline.com/views%2Fassets%2Ffiles%2F676337786b5b132.png', 5, 'PT014', 'unidad', 'IVA_15', 'NULL', 100, 0, 1, 2, '2024-12-18', '2025-04-17 15:21:23'),
(29, 'Airpod+2', 'https://pos.smartposline.com/views%2Fassets%2Ffiles%2F676333a5a17a913.png', 1, 'PT001', 'unidad', 'IVA_15', 'NULL', 100, 0, 1, 3, '2024-12-18', '2025-04-17 15:21:23'),
(30, 'Swagme', 'https://pos.smartposline.com/views%2Fassets%2Ffiles%2F6763359f5e7e639.png', 1, 'PT002', 'unidad', 'IVA_15', 'NULL', 100, 0, 1, 3, '2024-12-18', '2025-04-17 15:21:23'),
(31, 'Red Nike Angelo', 'https://pos.smartposline.com/views/assets/files/676335bf88cd611.png', 2, 'PT003', 'unidad', 'IVA_15', 'NULL', 90, 0, 1, 3, '2024-12-18', '2025-04-17 15:23:02'),
(32, 'Blue+White+OGR', 'https://pos.smartposline.com/views%2Fassets%2Ffiles%2F676335e7b765751.png', 2, 'PT004', 'unidad', 'IVA_15', 'NULL', 100, 0, 1, 3, '2024-12-18', '2025-04-17 15:21:23'),
(33, 'Green+Nike+Fe', 'https://pos.smartposline.com/views%2Fassets%2Ffiles%2F676336050329e21.png', 2, 'PT005', 'unidad', 'IVA_15', 'NULL', 100, 0, 1, 3, '2024-12-18', '2025-04-17 15:21:23'),
(34, 'Iphone+11', 'https://pos.smartposline.com/views%2Fassets%2Ffiles%2F6763362601cc654.png', 3, 'PT006', 'unidad', 'IVA_15', 'NULL', 96, 0, 1, 3, '2024-12-18', '2025-04-17 15:21:23'),
(35, 'IPhone+14+64GB', 'https://pos.smartposline.com/views%2Fassets%2Ffiles%2F6763364983cdc29.png', 3, 'PT007', 'unidad', 'IVA_15', 'NULL', 99, 0, 1, 3, '2024-12-18', '2025-04-17 15:21:23'),
(36, 'Rolex Tribute V3', 'https://pos.smartposline.com/views/assets/files/6763368780d2c31.png', 4, 'PT008', 'unidad', 'IVA_15', 'NULL', 95, 0, 1, 3, '2024-12-18', '2025-04-17 15:23:02'),
(37, 'Timex+Black+SIlver', 'https://pos.smartposline.com/views%2Fassets%2Ffiles%2F676336be037ba26.png', 4, 'PT009', 'unidad', 'IVA_15', 'NULL', 83, 0, 1, 3, '2024-12-18', '2025-04-17 15:21:23'),
(38, 'Fossil+Pair+Of+3+in+1', 'https://pos.smartposline.com/views%2Fassets%2Ffiles%2F676336d8ae17952.png', 4, 'PT010', 'unidad', 'IVA_15', 'NULL', 87, 0, 1, 3, '2024-12-18', '2025-04-17 15:21:23'),
(39, 'MacBook+Pro', 'https://pos.smartposline.com/views%2Fassets%2Ffiles%2F676336fb70b6d27.png', 5, 'PT011', 'unidad', 'IVA_15', 'NULL', 89, 0, 1, 3, '2024-12-18', '2025-04-17 15:21:23'),
(40, 'IdeaPad+Slim+5+Gen+7', 'https://pos.smartposline.com/views%2Fassets%2Ffiles%2F6763372162e555.png', 5, 'PT012', 'unidad', 'IVA_15', 'NULL', 68, 0, 1, 3, '2024-12-18', '2025-04-17 15:21:23'),
(41, 'Tablet+1.02+inch', 'https://pos.smartposline.com/views%2Fassets%2Ffiles%2F6763375d7ae0e5.png', 5, 'PT013', 'unidad', 'IVA_15', 'NULL', 79, 0, 1, 3, '2024-12-18', '2025-04-17 15:21:23'),
(42, 'Yoga+Book+9i', 'https://pos.smartposline.com/views%2Fassets%2Ffiles%2F676337786b5b132.png', 5, 'PT014', 'unidad', 'IVA_15', 'NULL', 72, 0, 1, 3, '2024-12-18', '2025-04-17 15:21:23'),
(43, 'Ventilador AMAZON', 'https://http2.mlstatic.com/D_NQ_NP_2X_615146-MEC82554910059_022025-N.webp', 6, 'PT015', 'unidad', 'IVA_15', 'NULL', 44, 0, 1, 1, '2025-04-17', '2025-05-31 17:11:36');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `purchases`
--

CREATE TABLE `purchases` (
  `id_purchase` int(11) NOT NULL,
  `supplier_purchase` text DEFAULT NULL,
  `id_product_purchase` int(11) DEFAULT 0,
  `cost_purchase` double DEFAULT 0,
  `utility_purchase` text DEFAULT NULL,
  `price_purchase` double DEFAULT 0,
  `qty_purchase` int(11) DEFAULT 0,
  `invest_purchase` double DEFAULT 0,
  `contact_purchase` text DEFAULT NULL,
  `id_office_purchase` int(11) DEFAULT 0,
  `date_created_purchase` date DEFAULT NULL,
  `date_updated_purchase` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `purchases`
--

INSERT INTO `purchases` (`id_purchase`, `supplier_purchase`, `id_product_purchase`, `cost_purchase`, `utility_purchase`, `price_purchase`, `qty_purchase`, `invest_purchase`, `contact_purchase`, `id_office_purchase`, `date_created_purchase`, `date_updated_purchase`) VALUES
(1, 'Apple', 1, 300, '30%', 390, 100, 30000, '6054321234', 1, '2024-12-18', '2024-12-18 22:05:08'),
(2, 'JBL', 2, 100, '40%', 140, 100, 10000, '6054321256', 1, '2024-12-18', '2024-12-18 22:05:12'),
(3, 'Nike', 3, 80, '50%', 120, 100, 8000, '6054321277', 1, '2024-12-18', '2024-12-18 22:05:16'),
(4, 'Adidas', 4, 80, '50%', 120, 100, 8000, '6054321266', 1, '2024-12-18', '2024-12-18 22:05:19'),
(5, 'Nike', 5, 80, '50%', 120, 100, 8000, '6054321277', 1, '2024-12-18', '2024-12-18 22:05:21'),
(6, 'Apple', 6, 699, '40%', 978.6, 100, 69900, '6054321234', 1, '2024-12-18', '2024-12-18 22:05:23'),
(7, 'Apple', 7, 899, '40%', 1258.6, 100, 89900, '6054321234', 1, '2024-12-18', '2024-12-18 22:05:26'),
(8, 'Rolex', 8, 199, '30%', 258.7, 100, 19900, '6054320000', 1, '2024-12-18', '2024-12-18 22:05:28'),
(9, 'Rolex', 9, 299, '30%', 388.7, 100, 29900, '6054320000', 1, '2024-12-18', '2024-12-18 22:05:30'),
(10, 'Fossil', 10, 399, '30%', 518.7, 100, 39900, '6054320022', 1, '2024-12-18', '2024-12-18 22:05:32'),
(11, 'Apple', 11, 1099, '40%', 1538.6, 100, 109900, '6054321234', 1, '2024-12-18', '2024-12-18 22:05:34'),
(12, 'Lenovo', 12, 599, '40%', 838.6, 100, 59900, '6054321222', 1, '2024-12-18', '2024-12-18 22:05:37'),
(13, 'Lenovo', 13, 799, '40%', 1118.6, 100, 79900, '6054321222', 1, '2024-12-18', '2024-12-18 22:05:39'),
(14, 'Lenovo', 14, 999, '40%', 1398.6, 100, 99900, '6054321222', 1, '2024-12-18', '2024-12-18 22:05:43'),
(15, 'Apple', 15, 300, '30%25', 390, 100, 30000, '6054321234', 2, '2024-12-18', '2024-12-18 22:09:06'),
(16, 'JBL', 16, 100, '40%25', 140, 100, 10000, '6054321256', 2, '2024-12-18', '2024-12-18 22:10:03'),
(17, 'Nike', 17, 80, '50%25', 120, 100, 8000, '6054321277', 2, '2024-12-18', '2024-12-18 22:10:11'),
(18, 'Adidas', 18, 80, '50%25', 120, 100, 8000, '6054321266', 2, '2024-12-18', '2024-12-18 22:10:19'),
(19, 'Nike', 19, 80, '50%25', 120, 100, 8000, '6054321277', 2, '2024-12-18', '2024-12-18 22:10:26'),
(20, 'Apple', 20, 699, '40%25', 978.6, 100, 69900, '6054321234', 2, '2024-12-18', '2024-12-18 22:10:35'),
(21, 'Apple', 21, 899, '40%25', 1258.6, 100, 89900, '6054321234', 2, '2024-12-18', '2024-12-18 22:10:46'),
(22, 'Rolex', 22, 199, '30%25', 258.7, 100, 19900, '6054320000', 2, '2024-12-18', '2024-12-18 22:11:06'),
(23, 'Rolex', 23, 299, '30%25', 388.7, 100, 29900, '6054320000', 2, '2024-12-18', '2024-12-18 22:11:21'),
(24, 'Fossil', 24, 399, '30%25', 518.7, 100, 39900, '6054320022', 2, '2024-12-18', '2024-12-18 22:11:32'),
(25, 'Apple', 25, 1099, '40%25', 1538.6, 100, 109900, '6054321234', 2, '2024-12-18', '2024-12-18 22:11:39'),
(26, 'Lenovo', 26, 599, '40%25', 838.6, 100, 59900, '6054321222', 2, '2024-12-18', '2024-12-18 22:11:46'),
(27, 'Lenovo', 27, 799, '40%25', 1118.6, 100, 79900, '6054321222', 2, '2024-12-18', '2024-12-18 22:11:55'),
(28, 'Lenovo', 28, 999, '40%25', 1398.6, 100, 99900, '6054321222', 2, '2024-12-18', '2024-12-18 22:12:00'),
(29, 'Apple', 29, 300, '30%25', 390, 100, 30000, '6054321234', 3, '2024-12-18', '2024-12-18 22:13:09'),
(30, 'JBL', 30, 100, '40%25', 140, 100, 10000, '6054321256', 3, '2024-12-18', '2024-12-18 22:13:18'),
(31, 'Nike', 31, 80, '50%25', 120, 100, 8000, '6054321277', 3, '2024-12-18', '2024-12-18 22:13:29'),
(32, 'Adidas', 32, 80, '50%25', 120, 100, 8000, '6054321266', 3, '2024-12-18', '2024-12-18 22:13:35'),
(33, 'Nike', 33, 80, '50%25', 120, 100, 8000, '6054321277', 3, '2024-12-18', '2024-12-18 22:13:43'),
(34, 'Apple', 34, 699, '40%25', 978.6, 100, 69900, '6054321234', 3, '2024-12-18', '2024-12-18 22:13:52'),
(35, 'Apple', 35, 899, '40%25', 1258.6, 100, 89900, '6054321234', 3, '2024-12-18', '2024-12-18 22:13:59'),
(36, 'Rolex', 36, 199, '30%25', 258.7, 100, 19900, '6054320000', 3, '2024-12-18', '2024-12-18 22:14:07'),
(37, 'Rolex', 37, 299, '30%25', 388.7, 100, 29900, '6054320000', 3, '2024-12-18', '2024-12-18 22:14:15'),
(38, 'Fossil', 38, 399, '30%25', 518.7, 100, 39900, '6054320022', 3, '2024-12-18', '2024-12-18 22:14:22'),
(39, 'Apple', 39, 1099, '40%25', 1538.6, 100, 109900, '6054321234', 3, '2024-12-18', '2024-12-18 22:14:30'),
(40, 'Lenovo', 40, 599, '40%25', 838.6, 100, 59900, '6054321222', 3, '2024-12-18', '2024-12-18 22:14:37'),
(41, 'Lenovo', 41, 799, '40%25', 1118.6, 100, 79900, '6054321222', 3, '2024-12-18', '2024-12-18 22:14:44'),
(42, 'Lenovo', 42, 999, '40%25', 1398.6, 100, 99900, '6054321222', 3, '2024-12-18', '2024-12-18 22:14:51'),
(45, 'Amazon', 43, 15, '40%25', 21, 50, 750, '0999999999', 1, '2025-04-17', '2025-04-17 15:42:22');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sales`
--

CREATE TABLE `sales` (
  `id_sale` int(11) NOT NULL,
  `id_order_sale` int(11) DEFAULT 0,
  `id_product_sale` int(11) DEFAULT 0,
  `tax_type_sale` text DEFAULT NULL,
  `tax_sale` double DEFAULT 0,
  `discount_sale` double DEFAULT 0,
  `qty_sale` int(11) DEFAULT 0,
  `subtotal_sale` double DEFAULT 0,
  `status_sale` text DEFAULT NULL,
  `id_admin_sale` int(11) DEFAULT 0,
  `id_client_sale` int(11) DEFAULT 0,
  `id_office_sale` int(11) DEFAULT 0,
  `date_created_sale` date DEFAULT NULL,
  `date_updated_sale` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `sales`
--

INSERT INTO `sales` (`id_sale`, `id_order_sale`, `id_product_sale`, `tax_type_sale`, `tax_sale`, `discount_sale`, `qty_sale`, `subtotal_sale`, `status_sale`, `id_admin_sale`, `id_client_sale`, `id_office_sale`, `date_created_sale`, `date_updated_sale`) VALUES
(31, 4, 13, 'IVA', 19, 0, 4, 2237.2, 'Completada', 1, 9, 1, '2024-12-27', '2025-01-03 13:48:38'),
(32, 4, 12, 'IVA', 19, 0, 5, 838.6, 'Completada', 1, 9, 1, '2024-12-27', '2025-01-03 13:48:52'),
(33, 4, 10, 'IVA', 19, 0, 3, 518.7, 'Completada', 1, 9, 1, '2024-12-27', '2025-01-03 13:49:05'),
(34, 4, 14, 'IVA', 19, 30, 2, 2797.2, 'Completada', 1, 9, 1, '2024-12-27', '2025-01-02 18:29:19'),
(35, 5, 12, 'IVA', 19, 0, 1, 838.6, 'Completada', 1, 1, 2, '2024-12-27', '2025-01-02 22:45:58'),
(36, 6, 10, 'IVA', 19, 0, 2, 518.7, 'Completada', 1, 3, 3, '2024-12-27', '2025-01-03 13:49:08'),
(37, 7, 13, 'IVA', 19, 0, 1, 1118.6, 'Completada', 1, 1, 1, '2025-03-09', '2025-03-10 01:53:32'),
(38, 7, 12, 'IVA', 19, 0, 1, 838.6, 'Completada', 1, 1, 1, '2025-03-09', '2025-03-10 01:53:32'),
(39, 8, 13, 'IVA', 19, 0, 2, 2237.2, 'Completada', 1, 1, 1, '2025-04-10', '2025-04-10 17:58:47'),
(43, 11, 13, 'IVA', 19, 0, 1, 1118.6, 'Completada', 1, 1, 1, '2025-04-10', '2025-04-10 21:56:29'),
(44, 11, 12, 'IVA', 19, 0, 1, 838.6, 'Completada', 1, 1, 1, '2025-04-10', '2025-04-10 21:56:29'),
(46, 13, 13, 'IVA', 19, 0, 2, 2237.2, 'Completada', 1, 1, 1, '2025-04-10', '2025-04-10 22:28:28'),
(47, 14, 13, 'IVA', 19, 0, 1, 1118.6, 'Completada', 1, 1, 1, '2025-04-11', '2025-04-11 10:54:30'),
(48, 14, 12, 'IVA', 19, 0, 1, 838.6, 'Completada', 1, 1, 1, '2025-04-11', '2025-04-11 10:54:30'),
(49, 15, 13, 'IVA', 19, 0, 1, 1118.6, 'Completada', 1, 1, 1, '2025-04-11', '2025-04-11 11:08:05'),
(50, 15, 12, 'IVA', 19, 0, 2, 1677.2, 'Completada', 1, 1, 1, '2025-04-11', '2025-04-11 11:08:06'),
(51, 16, 13, 'IVA', 19, 0, 1, 1118.6, 'Completada', 1, 1, 1, '2025-04-11', '2025-04-11 11:16:31'),
(52, 16, 12, 'IVA', 19, 0, 2, 1677.2, 'Completada', 1, 1, 1, '2025-04-11', '2025-04-11 11:16:31'),
(58, 17, 11, 'IVA', 15, 0, 2, 3077.2, 'Completada', 1, 2, 1, '2025-04-11', '2025-04-11 11:58:53'),
(59, 17, 12, 'IVA', 15, 0, 2, 1677.2, 'Completada', 1, 2, 1, '2025-04-11', '2025-04-11 11:58:53'),
(60, 17, 14, 'IVA', 15, 30, 1, 1398.6, 'Completada', 1, 2, 1, '2025-04-11', '2025-04-11 11:58:54'),
(61, 18, 13, 'IVA', 15, 0, 1, 1118.6, 'Completada', 1, 2, 1, '2025-04-11', '2025-04-11 14:05:58'),
(62, 18, 12, 'IVA', 15, 0, 1, 838.6, 'Completada', 1, 2, 1, '2025-04-11', '2025-04-11 14:05:59'),
(63, 19, 13, 'IVA', 15, 0, 2, 2237.2, 'Completada', 1, 3, 1, '2025-04-11', '2025-04-11 14:08:46'),
(64, 20, 12, 'IVA', 15, 0, 2, 1677.2, 'Completada', 1, 5, 1, '2025-04-11', '2025-04-11 14:09:44'),
(65, 21, 13, 'IVA', 15, 0, 1, 1118.6, 'Completada', 1, 4, 1, '2025-04-11', '2025-04-11 14:18:26'),
(66, 21, 12, 'IVA', 15, 0, 1, 838.6, 'Completada', 1, 4, 1, '2025-04-11', '2025-04-11 14:18:27'),
(67, 22, 14, 'IVA', 15, 30, 1, 1398.6, 'Completada', 1, 1, 1, '2025-04-11', '2025-04-11 15:35:30'),
(68, 22, 13, 'IVA', 15, 0, 2, 2237.2, 'Completada', 1, 1, 1, '2025-04-11', '2025-04-11 15:35:30'),
(69, 22, 12, 'IVA', 15, 0, 1, 838.6, 'Completada', 1, 1, 1, '2025-04-11', '2025-04-11 15:35:30'),
(70, 23, 13, 'IVA', 15, 0, 1, 1118.6, 'Completada', 1, 5, 1, '2025-04-11', '2025-04-11 15:40:50'),
(71, 23, 12, 'IVA', 15, 0, 1, 838.6, 'Completada', 1, 5, 1, '2025-04-11', '2025-04-11 15:40:50'),
(72, 23, 14, 'IVA', 15, 30, 2, 1958.04, 'Completada', 1, 5, 1, '2025-04-11', '2025-04-11 15:40:50'),
(73, 24, 13, 'IVA', 15, 0, 1, 1118.6, 'Completada', 1, 6, 1, '2025-04-11', '2025-04-11 17:00:56'),
(74, 24, 12, 'IVA', 15, 0, 1, 838.6, 'Completada', 1, 6, 1, '2025-04-11', '2025-04-11 17:00:56'),
(75, 25, 9, 'IVA', 19, 0, 4, 1554.8, 'Completada', 1, 10, 1, '2025-04-11', '2025-04-11 18:20:08'),
(76, 25, 10, 'IVA', 19, 0, 1, 518.7, 'Completada', 1, 10, 1, '2025-04-11', '2025-04-11 18:20:08'),
(77, 26, 13, 'IVA', 15, 0, 1, 1118.6, 'Completada', 1, 1, 1, '2025-04-11', '2025-04-11 18:22:45'),
(78, 26, 12, 'IVA', 15, 0, 1, 838.6, 'Completada', 1, 1, 1, '2025-04-11', '2025-04-11 18:22:45'),
(79, 27, 13, 'IVA', 15, 0, 1, 1118.6, 'Completada', 1, 11, 1, '2025-04-11', '2025-04-11 18:29:32'),
(80, 28, 13, 'IVA', 15, 0, 1, 1118.6, 'Completada', 1, 1, 1, '2025-04-11', '2025-04-11 18:39:59'),
(81, 28, 14, 'IVA', 15, 30, 2, 1958.04, 'Completada', 1, 1, 1, '2025-04-11', '2025-04-11 18:39:59'),
(82, 29, 13, 'IVA', 15, 0, 1, 1118.6, 'Completada', 1, 1, 1, '2025-04-11', '2025-04-11 20:22:33'),
(83, 31, 5, 'IVA', 19, 0, 3, 360, 'Completada', 1, 1, 1, '2025-04-11', '2025-04-11 20:23:50'),
(84, 30, 13, 'IVA', 15, 0, 1, 1118.6, 'Completada', 1, 1, 1, '2025-04-11', '2025-04-11 20:25:18'),
(85, 32, 13, 'IVA', 15, 0, 1, 1118.6, 'Completada', 1, 1, 1, '2025-04-11', '2025-04-11 21:27:45'),
(86, 32, 12, 'IVA', 15, 0, 1, 838.6, 'Completada', 1, 1, 1, '2025-04-11', '2025-04-11 21:27:45'),
(87, 33, 10, 'IVA', 19, 0, 2, 1037.4, 'Completada', 1, 1, 1, '2025-04-11', '2025-04-11 21:30:03'),
(88, 34, 14, 'IVA', 15, 30, 1, 1398.6, 'Completada', 1, 1, 1, '2025-04-11', '2025-04-11 21:37:28'),
(89, 35, 13, 'IVA', 15, 0, 2, 2237.2, 'Completada', 1, 1, 1, '2025-04-11', '2025-04-11 21:41:12'),
(90, 36, 13, 'IVA', 15, 0, 1, 1118.6, 'Completada', 1, 1, 1, '2025-04-11', '2025-04-11 21:44:58'),
(91, 37, 13, 'IVA', 15, 0, 1, 1118.6, 'Completada', 1, 1, 1, '2025-04-11', '2025-04-11 21:57:45'),
(92, 37, 12, 'IVA', 15, 0, 1, 838.6, 'Completada', 1, 1, 1, '2025-04-11', '2025-04-11 21:57:45'),
(93, 38, 13, 'IVA', 15, 0, 1, 1118.6, 'Completada', 1, 1, 1, '2025-04-11', '2025-04-11 22:05:01'),
(94, 38, 12, 'IVA', 15, 0, 1, 838.6, 'Completada', 1, 1, 1, '2025-04-11', '2025-04-11 22:05:01'),
(95, 39, 12, 'IVA', 15, 0, 1, 838.6, 'Completada', 1, 1, 1, '2025-04-11', '2025-04-11 22:06:37'),
(96, 40, 9, 'IVA', 19, 0, 6, 2332.2, 'Completada', 1, 1, 1, '2025-04-11', '2025-04-11 22:08:06'),
(97, 41, 13, 'IVA', 15, 0, 1, 1118.6, 'Completada', 1, 1, 1, '2025-04-11', '2025-04-11 22:17:38'),
(98, 41, 12, 'IVA', 15, 0, 1, 838.6, 'Completada', 1, 1, 1, '2025-04-11', '2025-04-11 22:17:38'),
(99, 41, 14, 'IVA', 15, 30, 1, 1398.6, 'Completada', 1, 1, 1, '2025-04-11', '2025-04-11 22:17:39'),
(100, 42, 13, 'IVA', 15, 0, 1, 1118.6, 'Completada', 1, 1, 1, '2025-04-11', '2025-04-11 22:22:39'),
(101, 42, 14, 'IVA', 15, 30, 1, 1398.6, 'Completada', 1, 1, 1, '2025-04-11', '2025-04-11 22:22:39'),
(102, 43, 12, 'IVA', 15, 0, 1, 838.6, 'Completada', 1, 1, 1, '2025-04-11', '2025-04-11 22:27:11'),
(103, 44, 13, 'IVA', 15, 0, 1, 1118.6, 'Completada', 1, 1, 1, '2025-04-11', '2025-04-11 22:36:38'),
(104, 44, 12, 'IVA', 15, 0, 1, 838.6, 'Completada', 1, 1, 1, '2025-04-11', '2025-04-11 22:36:39'),
(105, 45, 13, 'IVA', 15, 0, 1, 1118.6, 'Completada', 1, 1, 1, '2025-04-11', '2025-04-11 22:39:58'),
(106, 46, 13, 'IVA', 15, 0, 1, 1118.6, 'Completada', 1, 1, 1, '2025-04-11', '2025-04-12 04:20:21'),
(107, 46, 12, 'IVA', 15, 0, 1, 838.6, 'Completada', 1, 1, 1, '2025-04-11', '2025-04-12 04:20:21'),
(108, 47, 13, 'IVA', 15, 0, 1, 1118.6, 'Completada', 1, 1, 1, '2025-04-11', '2025-04-12 04:23:34'),
(109, 48, 13, 'IVA', 15, 0, 1, 1118.6, 'Completada', 1, 1, 1, '2025-04-11', '2025-04-12 04:25:08'),
(110, 48, 12, 'IVA', 15, 0, 1, 838.6, 'Completada', 1, 1, 1, '2025-04-11', '2025-04-12 04:25:08'),
(111, 49, 12, 'IVA', 15, 0, 1, 838.6, 'Completada', 1, 1, 1, '2025-04-11', '2025-04-12 04:28:10'),
(112, 50, 12, 'IVA', 15, 0, 1, 838.6, 'Completada', 1, 1, 1, '2025-04-11', '2025-04-12 04:37:12'),
(113, 50, 13, 'IVA', 15, 0, 1, 1118.6, 'Completada', 1, 1, 1, '2025-04-11', '2025-04-12 04:37:12'),
(114, 51, 12, 'IVA', 15, 0, 1, 838.6, 'Completada', 1, 1, 1, '2025-04-11', '2025-04-12 04:39:06'),
(115, 52, 13, 'IVA', 15, 0, 1, 1118.6, 'Completada', 1, 1, 1, '2025-04-12', '2025-04-12 16:05:52'),
(116, 52, 12, 'IVA', 15, 0, 1, 838.6, 'Completada', 1, 1, 1, '2025-04-12', '2025-04-12 16:05:52'),
(117, 53, 14, 'IVA', 15, 30, 2, 1958.04, 'Completada', 1, 1, 1, '2025-04-12', '2025-04-12 16:07:39'),
(118, 53, 13, 'IVA', 15, 0, 1, 1118.6, 'Completada', 1, 1, 1, '2025-04-12', '2025-04-12 16:07:39'),
(119, 54, 14, 'IVA', 15, 30, 1, 1398.6, 'Completada', 1, 1, 1, '2025-04-12', '2025-04-12 16:37:11'),
(120, 54, 13, 'IVA', 15, 0, 1, 1118.6, 'Completada', 1, 1, 1, '2025-04-12', '2025-04-12 16:37:11'),
(121, 55, 13, 'IVA', 15, 0, 1, 1118.6, 'Completada', 1, 1, 1, '2025-04-12', '2025-04-12 16:47:54'),
(122, 56, 12, 'IVA', 15, 0, 1, 838.6, 'Completada', 1, 1, 1, '2025-04-12', '2025-04-12 16:53:24'),
(123, 57, 13, 'IVA', 15, 0, 1, 1118.6, 'Completada', 1, 1, 1, '2025-04-12', '2025-04-12 16:54:37'),
(124, 58, 42, 'IVA', 19, 0, 1, 1398.6, 'Completada', 1, 1, 3, '2025-04-13', '2025-04-13 07:51:18'),
(125, 58, 37, 'IVA', 19, 0, 1, 388.7, 'Completada', 1, 1, 3, '2025-04-13', '2025-04-13 07:51:18'),
(126, 59, 37, 'IVA', 19, 0, 1, 388.7, 'Completada', 1, 1, 3, '2025-04-13', '2025-04-13 07:53:30'),
(127, 59, 39, 'IVA', 19, 0, 2, 3077.2, 'Completada', 1, 1, 3, '2025-04-13', '2025-04-13 07:53:30'),
(128, 60, 34, 'IVA', 19, 0, 1, 978.6, 'Completada', 1, 1, 3, '2025-04-13', '2025-04-13 07:55:13'),
(129, 60, 31, 'IVA', 19, 0, 2, 240, 'Completada', 1, 1, 3, '2025-04-13', '2025-04-13 07:55:13'),
(130, 61, 36, 'IVA', 19, 0, 1, 258.7, 'Completada', 1, 1, 3, '2025-04-13', '2025-04-13 07:57:17'),
(131, 61, 31, 'IVA', 19, 0, 2, 240, 'Completada', 1, 1, 3, '2025-04-13', '2025-04-13 07:57:17'),
(132, 62, 36, 'IVA', 19, 0, 1, 258.7, 'Completada', 1, 1, 3, '2025-04-13', '2025-04-13 08:02:20'),
(133, 62, 31, 'IVA', 19, 0, 2, 240, 'Completada', 1, 1, 3, '2025-04-13', '2025-04-13 08:02:21'),
(136, 63, 36, 'IVA', 15, 0, 1, 258.7, 'Completada', 1, 1, 3, '2025-04-13', '2025-04-13 08:30:10'),
(137, 63, 31, 'IVA', 15, 0, 2, 240, 'Completada', 1, 1, 3, '2025-04-13', '2025-04-13 08:30:10'),
(138, 64, 36, 'IVA', 15, 0, 1, 258.7, 'Completada', 1, 1, 3, '2025-04-13', '2025-04-13 08:39:22'),
(139, 64, 31, 'IVA', 15, 0, 2, 240, 'Completada', 1, 1, 3, '2025-04-13', '2025-04-13 08:39:22'),
(141, 65, 41, 'IVA', 19, 0, 1, 1118.6, 'Completada', 1, 1, 3, '2025-04-13', '2025-04-13 09:15:23'),
(142, 65, 40, 'IVA', 19, 0, 2, 1677.2, 'Completada', 1, 1, 3, '2025-04-13', '2025-04-13 09:15:23'),
(143, 66, 40, 'IVA', 19, 0, 1, 838.6, 'Completada', 1, 1, 3, '2025-04-13', '2025-04-13 09:18:21'),
(144, 66, 41, 'IVA', 19, 0, 2, 2237.2, 'Completada', 1, 1, 3, '2025-04-13', '2025-04-13 09:18:21'),
(145, 67, 41, 'IVA', 19, 0, 1, 1118.6, 'Completada', 1, 1, 3, '2025-04-13', '2025-04-13 09:23:28'),
(146, 67, 40, 'IVA', 19, 0, 2, 1677.2, 'Completada', 1, 1, 3, '2025-04-13', '2025-04-13 09:23:28'),
(147, 68, 41, 'IVA', 19, 0, 1, 1118.6, 'Completada', 1, 1, 3, '2025-04-13', '2025-04-13 09:25:15'),
(148, 68, 40, 'IVA', 19, 0, 1, 838.6, 'Completada', 1, 1, 3, '2025-04-13', '2025-04-13 09:25:16'),
(149, 68, 42, 'IVA', 19, 0, 1, 1398.6, 'Completada', 1, 1, 3, '2025-04-13', '2025-04-13 09:25:16'),
(150, 68, 37, 'IVA', 19, 0, 1, 388.7, 'Completada', 1, 1, 3, '2025-04-13', '2025-04-13 09:25:16'),
(151, 68, 38, 'IVA', 19, 0, 1, 518.7, 'Completada', 1, 1, 3, '2025-04-13', '2025-04-13 09:25:16'),
(152, 68, 39, 'IVA', 19, 0, 1, 1538.6, 'Completada', 1, 1, 3, '2025-04-13', '2025-04-13 09:25:17'),
(153, 69, 42, 'IVA', 19, 0, 1, 1398.6, 'Completada', 1, 1, 3, '2025-04-13', '2025-04-13 09:26:22'),
(154, 69, 39, 'IVA', 19, 0, 1, 1538.6, 'Completada', 1, 1, 3, '2025-04-13', '2025-04-13 09:26:23'),
(155, 69, 38, 'IVA', 19, 0, 5, 2593.5, 'Completada', 1, 1, 3, '2025-04-13', '2025-04-13 09:26:23'),
(156, 69, 41, 'IVA', 19, 0, 1, 1118.6, 'Completada', 1, 1, 3, '2025-04-13', '2025-04-13 09:26:23'),
(157, 70, 42, 'IVA', 19, 0, 1, 1398.6, 'Completada', 1, 1, 3, '2025-04-13', '2025-04-13 09:29:30'),
(158, 70, 41, 'IVA', 19, 0, 2, 2237.2, 'Completada', 1, 1, 3, '2025-04-13', '2025-04-13 09:29:30'),
(160, 71, 40, 'IVA', 15, 0, 1, 838.6, 'Completada', 1, 1, 3, '2025-04-13', '2025-04-13 09:35:47'),
(161, 71, 41, 'IVA', 15, 0, 2, 2237.2, 'Completada', 1, 1, 3, '2025-04-13', '2025-04-13 09:35:47'),
(162, 72, 42, 'IVA', 15, 0, 1, 1398.6, 'Completada', 1, 1, 3, '2025-04-14', '2025-04-14 15:48:48'),
(163, 73, 37, 'IVA', 15, 0, 1, 388.7, 'Completada', 1, 1, 3, '2025-04-14', '2025-04-14 15:50:58'),
(164, 74, 42, 'IVA', 15, 0, 1, 1398.6, 'Completada', 1, 1, 3, '2025-04-14', '2025-04-14 16:02:26'),
(165, 75, 41, 'IVA', 15, 0, 2, 2237.2, 'Completada', 1, 1, 3, '2025-04-14', '2025-04-14 16:23:14'),
(166, 76, 41, 'IVA', 15, 0, 1, 1118.6, 'Completada', 1, 1, 3, '2025-04-14', '2025-04-14 20:53:56'),
(167, 77, 40, 'IVA', 15, 0, 1, 838.6, 'Completada', 1, 1, 3, '2025-04-14', '2025-04-14 20:55:23'),
(168, 78, 40, 'IVA', 15, 0, 1, 838.6, 'Completada', 1, 1, 3, '2025-04-14', '2025-04-14 21:00:27'),
(169, 79, 40, 'IVA', 15, 0, 3, 2515.8, 'Completada', 1, 1, 3, '2025-04-14', '2025-04-14 21:04:24'),
(170, 80, 41, 'IVA', 15, 0, 2, 2237.2, 'Completada', 1, 1, 3, '2025-04-14', '2025-04-14 21:07:29'),
(171, 81, 37, 'IVA', 15, 0, 2, 777.4, 'Completada', 1, 1, 3, '2025-04-14', '2025-04-14 21:08:47'),
(172, 82, 40, 'IVA', 15, 0, 1, 838.6, 'Completada', 6, 1, 3, '2025-04-14', '2025-04-14 22:36:47'),
(173, 83, 40, 'IVA', 15, 0, 1, 838.6, 'Completada', 6, 1, 3, '2025-04-14', '2025-04-14 22:39:45'),
(174, 84, 40, 'IVA', 15, 0, 1, 838.6, 'Completada', 6, 1, 3, '2025-04-14', '2025-04-15 04:06:01'),
(175, 85, 41, 'IVA', 15, 0, 1, 1118.6, 'Completada', 6, 1, 3, '2025-04-14', '2025-04-15 04:07:58'),
(176, 86, 42, 'IVA', 15, 0, 1, 1398.6, 'Completada', 6, 1, 3, '2025-04-14', '2025-04-15 04:10:10'),
(177, 87, 41, 'IVA', 15, 0, 1, 1118.6, 'Completada', 6, 1, 3, '2025-04-14', '2025-04-15 04:17:37'),
(178, 88, 37, 'IVA', 15, 0, 1, 388.7, 'Completada', 6, 1, 3, '2025-04-14', '2025-04-15 04:19:28'),
(179, 89, 37, 'IVA', 15, 0, 1, 388.7, 'Completada', 6, 1, 3, '2025-04-14', '2025-04-15 04:21:03'),
(180, 90, 40, 'IVA', 15, 0, 1, 838.6, 'Completada', 1, 1, 3, '2025-04-14', '2025-04-15 04:28:41'),
(181, 91, 42, 'IVA', 15, 0, 1, 1398.6, 'Completada', 1, 2, 3, '2025-04-14', '2025-04-15 04:29:41'),
(182, 92, 42, 'IVA', 15, 0, 1, 1398.6, 'Completada', 1, 3, 3, '2025-04-14', '2025-04-15 04:30:45'),
(183, 93, 41, 'IVA', 15, 0, 1, 1118.6, 'Completada', 1, 2, 3, '2025-04-14', '2025-04-15 04:34:01'),
(184, 94, 37, 'IVA', 15, 0, 1, 388.7, 'Completada', 1, 1, 3, '2025-04-14', '2025-04-15 04:41:36'),
(185, 95, 42, 'IVA', 15, 0, 1, 1398.6, 'Completada', 1, 1, 3, '2025-04-14', '2025-04-15 04:43:33'),
(186, 96, 40, 'IVA', 15, 0, 1, 838.6, 'Completada', 6, 1, 3, '2025-04-14', '2025-04-15 04:45:09'),
(187, 97, 37, 'IVA', 15, 0, 1, 388.7, 'Completada', 6, 1, 3, '2025-04-14', '2025-04-15 04:51:56'),
(188, 98, 40, 'IVA', 15, 0, 1, 838.6, 'Completada', 6, 2, 3, '2025-04-14', '2025-04-15 04:59:05'),
(189, 99, 13, 'IVA', 15, 0, 1, 1118.6, 'Completada', 4, 1, 1, '2025-04-15', '2025-04-15 05:08:42'),
(190, 99, 12, 'IVA', 15, 0, 1, 838.6, 'Completada', 4, 1, 1, '2025-04-15', '2025-04-15 05:08:43'),
(191, 99, 11, 'IVA', 15, 0, 2, 3077.2, 'Completada', 4, 1, 1, '2025-04-15', '2025-04-15 05:08:43'),
(192, 100, 13, 'IVA', 15, 0, 1, 1118.6, 'Completada', 4, 1, 1, '2025-04-15', '2025-04-15 05:13:56'),
(194, 100, 11, 'IVA', 15, 0, 1, 1538.6, 'Completada', 4, 1, 1, '2025-04-15', '2025-04-15 05:13:56'),
(195, 100, 12, 'IVA', 15, 0, 1, 838.6, 'Completada', 4, 1, 1, '2025-04-15', '2025-04-15 05:13:56'),
(196, 101, 42, 'IVA', 15, 0, 1, 1398.6, 'Completada', 6, 1, 3, '2025-04-16', '2025-04-16 20:06:01'),
(197, 101, 41, 'IVA', 15, 0, 1, 1118.6, 'Completada', 6, 1, 3, '2025-04-16', '2025-04-16 20:06:01'),
(198, 101, 40, 'IVA', 15, 0, 1, 838.6, 'Completada', 6, 1, 3, '2025-04-16', '2025-04-16 20:06:02'),
(199, 102, 42, 'IVA', 15, 0, 1, 1398.6, 'Completada', 6, 1, 3, '2025-04-16', '2025-04-16 20:13:35'),
(200, 102, 41, 'IVA', 15, 0, 1, 1118.6, 'Completada', 6, 1, 3, '2025-04-16', '2025-04-16 20:13:35'),
(201, 102, 40, 'IVA', 15, 0, 1, 838.6, 'Completada', 6, 1, 3, '2025-04-16', '2025-04-16 20:13:36'),
(202, 103, 39, 'IVA', 15, 0, 1, 1538.6, 'Completada', 6, 1, 3, '2025-04-16', '2025-04-16 20:29:30'),
(203, 103, 38, 'IVA', 15, 0, 1, 518.7, 'Completada', 6, 1, 3, '2025-04-16', '2025-04-16 20:29:30'),
(204, 103, 37, 'IVA', 15, 0, 1, 388.7, 'Completada', 6, 1, 3, '2025-04-16', '2025-04-16 20:29:30'),
(205, 104, 39, 'IVA', 15, 0, 1, 1538.6, 'Completada', 6, 1, 3, '2025-04-16', '2025-04-16 21:24:45'),
(206, 104, 38, 'IVA', 15, 0, 1, 518.7, 'Completada', 6, 1, 3, '2025-04-16', '2025-04-16 21:24:45'),
(207, 104, 37, 'IVA', 15, 0, 1, 388.7, 'Completada', 6, 1, 3, '2025-04-16', '2025-04-16 21:24:45'),
(208, 105, 37, 'IVA', 15, 0, 1, 388.7, 'Completada', 6, 1, 3, '2025-04-16', '2025-04-16 21:26:08'),
(209, 105, 38, 'IVA', 15, 0, 1, 518.7, 'Completada', 6, 1, 3, '2025-04-16', '2025-04-16 21:26:09'),
(210, 106, 42, 'IVA', 15, 0, 1, 1398.6, 'Completada', 6, 1, 3, '2025-04-16', '2025-04-16 21:34:31'),
(211, 106, 41, 'IVA', 15, 0, 1, 1118.6, 'Completada', 6, 1, 3, '2025-04-16', '2025-04-16 21:34:31'),
(212, 106, 40, 'IVA', 15, 0, 1, 838.6, 'Completada', 6, 1, 3, '2025-04-16', '2025-04-16 21:34:31'),
(213, 106, 39, 'IVA', 15, 0, 1, 1538.6, 'Completada', 6, 1, 3, '2025-04-16', '2025-04-16 21:34:31'),
(214, 106, 38, 'IVA', 15, 0, 1, 518.7, 'Completada', 6, 1, 3, '2025-04-16', '2025-04-16 21:34:32'),
(215, 106, 37, 'IVA', 15, 0, 1, 388.7, 'Completada', 6, 1, 3, '2025-04-16', '2025-04-16 21:34:32'),
(216, 106, 36, 'IVA', 15, 0, 1, 258.7, 'Completada', 6, 1, 3, '2025-04-16', '2025-04-16 21:34:32'),
(217, 106, 35, 'IVA', 15, 0, 1, 1258.6, 'Completada', 6, 1, 3, '2025-04-16', '2025-04-16 21:34:32'),
(218, 106, 34, 'IVA', 15, 0, 3, 2935.8, 'Completada', 6, 1, 3, '2025-04-16', '2025-04-16 21:34:32'),
(219, 107, 40, 'IVA', 15, 0, 4, 3354.4, 'Completada', 6, 1, 3, '2025-04-16', '2025-04-16 21:52:58'),
(220, 108, 39, 'IVA', 15, 0, 1, 1538.6, 'Completada', 6, 1, 3, '2025-04-16', '2025-04-16 22:15:52'),
(221, 108, 38, 'IVA', 15, 0, 1, 518.7, 'Completada', 6, 1, 3, '2025-04-16', '2025-04-16 22:15:52'),
(222, 108, 37, 'IVA', 15, 0, 1, 388.7, 'Completada', 6, 1, 3, '2025-04-16', '2025-04-16 22:15:52'),
(223, 109, 39, 'IVA', 15, 0, 1, 1538.6, 'Completada', 6, 1, 3, '2025-04-16', '2025-04-16 22:26:33'),
(224, 110, 39, 'IVA', 15, 0, 1, 1538.6, 'Completada', 6, 1, 3, '2025-04-16', '2025-04-16 22:27:37'),
(225, 110, 38, 'IVA', 15, 0, 1, 518.7, 'Completada', 6, 1, 3, '2025-04-16', '2025-04-16 22:27:37'),
(226, 110, 37, 'IVA', 15, 0, 1, 388.7, 'Completada', 6, 1, 3, '2025-04-16', '2025-04-16 22:27:38'),
(227, 111, 39, 'IVA', 15, 0, 1, 1538.6, 'Completada', 6, 1, 3, '2025-04-16', '2025-04-16 22:28:46'),
(228, 111, 38, 'IVA', 15, 0, 1, 518.7, 'Completada', 6, 1, 3, '2025-04-16', '2025-04-16 22:28:46'),
(229, 111, 37, 'IVA', 15, 0, 1, 388.7, 'Completada', 6, 1, 3, '2025-04-16', '2025-04-16 22:28:46'),
(230, 112, 42, 'IVA', 15, 0, 2, 2797.2, 'Completada', 6, 1, 3, '2025-04-16', '2025-04-16 22:33:47'),
(231, 113, 42, 'IVA', 15, 0, 5, 6993, 'Completada', 6, 1, 3, '2025-04-16', '2025-04-16 22:37:22'),
(232, 114, 42, 'IVA', 15, 0, 3, 4195.8, 'Completada', 6, 1, 3, '2025-04-16', '2025-04-16 22:41:36'),
(233, 115, 40, 'IVA', 15, 0, 3, 2515.8, 'Completada', 6, 1, 3, '2025-04-16', '2025-04-16 22:42:59'),
(234, 116, 40, 'IVA', 15, 0, 4, 3354.4, 'Completada', 6, 1, 3, '2025-04-16', '2025-04-16 22:49:07'),
(235, 117, 42, 'IVA', 15, 0, 3, 4195.8, 'Completada', 6, 1, 3, '2025-04-16', '2025-04-16 22:50:28'),
(236, 118, 42, 'IVA', 15, 0, 1, 1398.6, 'Completada', 6, 1, 3, '2025-04-16', '2025-04-16 22:58:12'),
(237, 119, 42, 'IVA', 15, 0, 1, 1398.6, 'Completada', 6, 1, 3, '2025-04-17', '2025-04-17 12:47:04'),
(238, 120, 43, 'IVA', 15, 0, 1, 21, 'Completada', 1, 1, 1, '2025-04-17', '2025-04-17 15:47:54'),
(239, 121, 43, 'IVA', 15, 0, 1, 21, 'Completada', 4, 1, 1, '2025-04-17', '2025-04-17 15:51:35'),
(240, 122, 43, 'IVA', 15, 0, 1, 21, 'Completada', 4, 1, 1, '2025-04-17', '2025-04-17 16:00:35'),
(241, 123, 43, 'IVA', 15, 0, 1, 21, 'Completada', 4, 11, 1, '2025-04-17', '2025-04-17 16:04:15'),
(242, 124, 14, 'IVA', 15, 30, 1, 1398.6, 'Completada', 4, 1, 1, '2025-04-17', '2025-04-17 16:42:08'),
(243, 125, 13, 'IVA', 15, 0, 1, 1118.6, 'Completada', 4, 1, 1, '2025-04-17', '2025-04-17 16:48:15'),
(244, 126, 14, 'IVA', 15, 30, 1, 1398.6, 'Completada', 4, 1, 1, '2025-04-17', '2025-04-17 17:08:36'),
(245, 127, 13, 'IVA', 15, 0, 1, 1118.6, 'Completada', 4, 1, 1, '2025-04-17', '2025-04-17 17:09:48'),
(246, 129, 43, 'IVA', 15, 0, 1, 21, 'Completada', 4, 1, 1, '2025-05-03', '2025-05-03 17:04:37'),
(247, 130, 43, 'IVA', 15, 0, 1, 21, 'Completada', 2, 11, 1, '2025-05-31', '2025-05-31 17:11:36'),
(248, 130, 14, 'IVA', 15, 30, 2, 1958.04, 'Completada', 2, 11, 1, '2025-05-31', '2025-05-31 17:11:36');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `secuencials`
--

CREATE TABLE `secuencials` (
  `id_secuencial` int(11) NOT NULL DEFAULT 0,
  `ultimo_numero_secuencial` int(11) DEFAULT 0,
  `oficina_secuencial` int(11) DEFAULT 0,
  `caja_secuencial` int(11) DEFAULT 0,
  `office_secuencial` int(11) DEFAULT 0,
  `date_created_secuencial` date DEFAULT NULL,
  `date_updated_secuencial` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `secuencials`
--

INSERT INTO `secuencials` (`id_secuencial`, `ultimo_numero_secuencial`, `oficina_secuencial`, `caja_secuencial`, `office_secuencial`, `date_created_secuencial`, `date_updated_secuencial`) VALUES
(0, 1, 1, 1, NULL, NULL, '2025-04-14 21:54:16'),
(1, 211, 1, 1, 3, '2025-04-14', '2025-07-01 22:39:28'),
(2, 2, 1, 2, 3, '2025-04-14', '2025-04-14 20:51:13'),
(3, 1, 1, 3, 3, '2025-04-14', '2025-04-14 20:51:20'),
(4, 15, 1, 1, 1, '2025-04-14', '2025-05-03 17:04:37'),
(5, 10, 1, 2, 1, '2025-04-14', '2025-04-15 04:41:36');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id_admin`);

--
-- Indices de la tabla `bills`
--
ALTER TABLE `bills`
  ADD PRIMARY KEY (`id_bill`);

--
-- Indices de la tabla `cashs`
--
ALTER TABLE `cashs`
  ADD PRIMARY KEY (`id_cash`);

--
-- Indices de la tabla `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id_category`);

--
-- Indices de la tabla `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id_client`);

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
-- Indices de la tabla `informations`
--
ALTER TABLE `informations`
  ADD PRIMARY KEY (`id_information`);

--
-- Indices de la tabla `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id_invoice`);

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
-- Indices de la tabla `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id_order`);

--
-- Indices de la tabla `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`id_page`);

--
-- Indices de la tabla `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id_product`);

--
-- Indices de la tabla `purchases`
--
ALTER TABLE `purchases`
  ADD PRIMARY KEY (`id_purchase`);

--
-- Indices de la tabla `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id_sale`);

--
-- Indices de la tabla `secuencials`
--
ALTER TABLE `secuencials`
  ADD PRIMARY KEY (`id_secuencial`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `admins`
--
ALTER TABLE `admins`
  MODIFY `id_admin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `bills`
--
ALTER TABLE `bills`
  MODIFY `id_bill` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `cashs`
--
ALTER TABLE `cashs`
  MODIFY `id_cash` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `categories`
--
ALTER TABLE `categories`
  MODIFY `id_category` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `clients`
--
ALTER TABLE `clients`
  MODIFY `id_client` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `columns`
--
ALTER TABLE `columns`
  MODIFY `id_column` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=115;

--
-- AUTO_INCREMENT de la tabla `files`
--
ALTER TABLE `files`
  MODIFY `id_file` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de la tabla `folders`
--
ALTER TABLE `folders`
  MODIFY `id_folder` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `informations`
--
ALTER TABLE `informations`
  MODIFY `id_information` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id_invoice` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `modules`
--
ALTER TABLE `modules`
  MODIFY `id_module` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT de la tabla `offices`
--
ALTER TABLE `offices`
  MODIFY `id_office` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `orders`
--
ALTER TABLE `orders`
  MODIFY `id_order` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=131;

--
-- AUTO_INCREMENT de la tabla `pages`
--
ALTER TABLE `pages`
  MODIFY `id_page` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `products`
--
ALTER TABLE `products`
  MODIFY `id_product` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT de la tabla `purchases`
--
ALTER TABLE `purchases`
  MODIFY `id_purchase` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT de la tabla `sales`
--
ALTER TABLE `sales`
  MODIFY `id_sale` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=249;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
