-- phpMyAdmin SQL Dump
-- version 4.4.14.1
-- http://www.phpmyadmin.net

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


--
-- Database: `client_not`
--

-- --------------------------------------------------------

--
-- table `notifications`
--

CREATE TABLE IF NOT EXISTS `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- table `notification_type`
--

CREATE TABLE IF NOT EXISTS `notification_type` (
  `notification_id` int(11) NOT NULL,
  `type_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- table `types`
--

CREATE TABLE IF NOT EXISTS `types` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

ALTER TABLE `notifications`
ADD PRIMARY KEY (`id`);

ALTER TABLE `notification_type`
ADD UNIQUE KEY `notification_id` (`notification_id`,`type_id`),
ADD KEY `type_id` (`type_id`),
ADD KEY `notification_id_2` (`notification_id`);

ALTER TABLE `types`
ADD PRIMARY KEY (`id`);

ALTER TABLE `notifications`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `types`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `notification_type`
ADD CONSTRAINT `notification_type_ibfk_1` FOREIGN KEY (`notification_id`) REFERENCES `notifications` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `notification_type_ibfk_2` FOREIGN KEY (`type_id`) REFERENCES `types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;