-- phpMyAdmin SQL Dump
-- version 3.3.7deb7
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Сен 01 2015 г., 17:34
-- Версия сервера: 5.5.40
-- Версия PHP: 5.4.36-0+deb7u1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `skidos`
--

-- --------------------------------------------------------

--
-- Структура таблицы `auth_assignment`
--

CREATE TABLE IF NOT EXISTS `auth_assignment` (
  `item_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`item_name`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `auth_assignment`
--

INSERT INTO `auth_assignment` (`item_name`, `user_id`, `created_at`) VALUES
('admin', '1', 1439910900),
('shop', '4', 1440709284),
('user', '2', 1440144373),
('user', '3', 1440709231),
('user', '5', 1440883300);

-- --------------------------------------------------------

--
-- Структура таблицы `auth_item`
--

CREATE TABLE IF NOT EXISTS `auth_item` (
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `type` int(11) NOT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `rule_name` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `data` text COLLATE utf8_unicode_ci,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`name`),
  KEY `rule_name` (`rule_name`),
  KEY `idx-auth_item-type` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `auth_item`
--

INSERT INTO `auth_item` (`name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) VALUES
('accessComment', 2, 'удаление\\редактировние комментариев', NULL, NULL, 1439910899, 1439910899),
('accessOwnComment', 2, 'удаление\\редактирование комментариев, относящихся к конкретному магазину', 'isOwner', NULL, 1439910899, 1439910899),
('accessOwnUrl', 2, 'удаление\\редактирование своих записей урл', 'isOwner', NULL, 1439910899, 1439910899),
('accessUrl', 2, 'удаление\\редактировние записей урл', NULL, NULL, 1439910899, 1439910899),
('admin', 1, NULL, NULL, NULL, 1439910899, 1439910899),
('shop', 1, NULL, NULL, NULL, 1439910899, 1439910899),
('user', 1, NULL, NULL, NULL, 1439910899, 1439910899);

-- --------------------------------------------------------

--
-- Структура таблицы `auth_item_child`
--

CREATE TABLE IF NOT EXISTS `auth_item_child` (
  `parent` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `child` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`parent`,`child`),
  KEY `child` (`child`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `auth_item_child`
--

INSERT INTO `auth_item_child` (`parent`, `child`) VALUES
('accessOwnComment', 'accessComment'),
('admin', 'accessComment'),
('shop', 'accessOwnComment'),
('shop', 'accessOwnUrl'),
('accessOwnUrl', 'accessUrl'),
('admin', 'accessUrl'),
('admin', 'shop'),
('admin', 'user');

-- --------------------------------------------------------

--
-- Структура таблицы `auth_rule`
--

CREATE TABLE IF NOT EXISTS `auth_rule` (
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `data` text COLLATE utf8_unicode_ci,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `auth_rule`
--

INSERT INTO `auth_rule` (`name`, `data`, `created_at`, `updated_at`) VALUES
('isOwner', 'O:18:"app\\rbac\\OwnerRule":3:{s:4:"name";s:7:"isOwner";s:9:"createdAt";i:1439910899;s:9:"updatedAt";i:1439910899;}', 1439910899, 1439910899);

-- --------------------------------------------------------

--
-- Структура таблицы `comment`
--

CREATE TABLE IF NOT EXISTS `comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT 'ид магазина',
  `author_id` int(11) NOT NULL COMMENT 'ид автора комментария',
  `message` text NOT NULL,
  `answer` text,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_comment_author` (`author_id`),
  KEY `fk_comment_shop` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Дамп данных таблицы `comment`
--


-- --------------------------------------------------------

--
-- Структура таблицы `migration`
--

CREATE TABLE IF NOT EXISTS `migration` (
  `version` varchar(180) NOT NULL,
  `apply_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Дамп данных таблицы `migration`
--

INSERT INTO `migration` (`version`, `apply_time`) VALUES
('m000000_000000_base', 1439910888),
('m140506_102106_rbac_init', 1439910899),
('m150517_100812_create_user', 1439910900),
('m150517_111753_create_url', 1439910900),
('m150517_112925_create_profile', 1439910901),
('m150520_153926_create_pay_log', 1439910901),
('m150530_084956_create_purchase', 1439910902),
('m150612_083226_create_comment', 1439910902);

-- --------------------------------------------------------

--
-- Структура таблицы `pay_log`
--

CREATE TABLE IF NOT EXISTS `pay_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `sum` decimal(10,2) NOT NULL,
  `type` smallint(6) NOT NULL,
  `status` smallint(6) NOT NULL DEFAULT '0',
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_paylog_user` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Дамп данных таблицы `pay_log`
--


-- --------------------------------------------------------

--
-- Структура таблицы `profile`
--

CREATE TABLE IF NOT EXISTS `profile` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `url` varchar(255) NOT NULL,
  `recommender_bonus` smallint(6) NOT NULL COMMENT '% бонус для рекомендателя',
  `buyer_bonus` smallint(6) NOT NULL COMMENT '% бонус для покупателя',
  PRIMARY KEY (`id`),
  KEY `fk_profile_user` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

--
-- Дамп данных таблицы `profile`
--

INSERT INTO `profile` (`id`, `user_id`, `url`, `recommender_bonus`, `buyer_bonus`) VALUES
(1, 4, 'http://obshya.com', 5, 5);

-- --------------------------------------------------------

--
-- Структура таблицы `purchase`
--

CREATE TABLE IF NOT EXISTS `purchase` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `affiliate_id` int(11) DEFAULT NULL,
  `shop_id` int(11) DEFAULT NULL,
  `url_id` int(11) DEFAULT NULL,
  `sum` decimal(10,2) NOT NULL,
  `status` smallint(6) NOT NULL DEFAULT '0',
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_purchase_user` (`user_id`),
  KEY `fk_purchase_affiliate` (`affiliate_id`),
  KEY `fk_purchase_shop` (`shop_id`),
  KEY `fk_purchase_url` (`url_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

--
-- Дамп данных таблицы `purchase`
--

INSERT INTO `purchase` (`id`, `user_id`, `affiliate_id`, `shop_id`, `url_id`, `sum`, `status`, `created_at`, `updated_at`) VALUES
(1, 5, NULL, 4, 1, 0.00, 1, 1440955569, 1440955569),
(2, 5, NULL, 4, 1, 0.00, 1, 1440955671, 1440955671);

-- --------------------------------------------------------

--
-- Структура таблицы `url`
--

CREATE TABLE IF NOT EXISTS `url` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `link` varchar(255) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_url_user` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

--
-- Дамп данных таблицы `url`
--

INSERT INTO `url` (`id`, `user_id`, `link`, `name`, `created_at`, `updated_at`) VALUES
(1, 4, 'http://obshya.com', '??????? ????????', 1440709284, 1440709284);

-- --------------------------------------------------------

--
-- Структура таблицы `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `balance` decimal(10,2) NOT NULL DEFAULT '0.00',
  `auth_key` varchar(32) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `password_reset_token` varchar(255) DEFAULT NULL,
  `rating` int(11) NOT NULL DEFAULT '0',
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `phone` (`phone`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

--
-- Дамп данных таблицы `user`
--

INSERT INTO `user` (`id`, `email`, `phone`, `balance`, `auth_key`, `password_hash`, `password_reset_token`, `rating`, `created_at`, `updated_at`) VALUES
(1, 'admin', NULL, 0.00, 'g7xOmQpJH4qccNTl0Z4nzZjPkTRQEkMY', '$2y$13$Zo3vL6.GZ2G7JtTq8kkpv.MfiKGxWBGcE/424vwb6v0fxDULFhJsu', NULL, 0, 1439910900, 1439910900),
(2, 'optobeats@gmail.com', '380950673193', 0.00, 'E64eMvk_GJpbZtf_B0K3-KC6O2OpSQYf', '$2y$13$DkyckG2/b3iYXGL.qe8Pke2lzd2pQirbbCVNsFuRoPKPlJpMgrYlS', NULL, 0, 1440144373, 1440144373),
(3, 'rembrant122@gmail.com', '', 0.00, 'ZxbxliaZSBE1olKilWOP1N1RPVu4D3yh', '$2y$13$umBF1xFsZn/DJ8FzPTBySeEu6v/I8W1U9XslG9g8etmM1lOs099Oq', 'OqjqcdrC85b_G9Hga60fP1xJpqIPrYT8_1440943333', 0, 1440709231, 1440943333),
(4, 'ako40ff@gmail.com', NULL, 0.00, 'Y30VN8WrsvGLu9aYKO6AI3YWPxSRIs_X', '$2y$13$tbSOfhr57X6YUv3G5gaUaOV.ELLk57VoHLQ818J0ooMQneNFzUsBS', NULL, 0, 1440709284, 1440709284),
(5, 'coolfire@inbox.ru', '79780116403', 0.00, '2KmPtvxaRDee-gxIxdIh7-iRdBwHEy6r', '$2y$13$f5N9Ew2RWXDL0gkUgHal6eLj8vUcFqYHjOfwFv7LE.6X9Tf5bf5Zi', NULL, 0, 1440883300, 1440943643);

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `auth_assignment`
--
ALTER TABLE `auth_assignment`
  ADD CONSTRAINT `auth_assignment_ibfk_1` FOREIGN KEY (`item_name`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `auth_item`
--
ALTER TABLE `auth_item`
  ADD CONSTRAINT `auth_item_ibfk_1` FOREIGN KEY (`rule_name`) REFERENCES `auth_rule` (`name`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `auth_item_child`
--
ALTER TABLE `auth_item_child`
  ADD CONSTRAINT `auth_item_child_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `auth_item_child_ibfk_2` FOREIGN KEY (`child`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `fk_comment_author` FOREIGN KEY (`author_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `fk_comment_shop` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Ограничения внешнего ключа таблицы `pay_log`
--
ALTER TABLE `pay_log`
  ADD CONSTRAINT `fk_paylog_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Ограничения внешнего ключа таблицы `profile`
--
ALTER TABLE `profile`
  ADD CONSTRAINT `fk_profile_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Ограничения внешнего ключа таблицы `purchase`
--
ALTER TABLE `purchase`
  ADD CONSTRAINT `fk_purchase_affiliate` FOREIGN KEY (`affiliate_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `fk_purchase_shop` FOREIGN KEY (`shop_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `fk_purchase_url` FOREIGN KEY (`url_id`) REFERENCES `url` (`id`),
  ADD CONSTRAINT `fk_purchase_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Ограничения внешнего ключа таблицы `url`
--
ALTER TABLE `url`
  ADD CONSTRAINT `fk_url_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);
