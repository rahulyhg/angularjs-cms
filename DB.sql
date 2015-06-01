-- phpMyAdmin SQL Dump
-- version 4.1.12
-- http://www.phpmyadmin.net
--
-- Host: localhost:8889
-- Generation Time: Jan 25, 2015 at 09:32 PM
-- Server version: 5.5.34
-- PHP Version: 5.5.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `aecreateit`
--

-- --------------------------------------------------------

--
-- Table structure for table `files`
--

CREATE TABLE `files` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `file` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `alt` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `remove` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=43 ;

--
-- Dumping data for table `files`
--

INSERT INTO `files` (`id`, `file`, `title`, `alt`, `remove`) VALUES
(7, '54954a6dd8349_n7F5qX6A_1920x1080.jpg.jpg', 'Flowers', 'wallpaper', 0),
(26, '54989ceeacb65_My-Nexus4-wallpaper-Patterns.jpeg.jpeg', 'triangle wallpaper', '', 0),
(28, '549b13872001a_2394-10.jpg.jpg', 'flower', 'alternate text', 0),
(29, '549b138db454f_7250.jpg.jpg', '', '', 0),
(30, '549b13946e25a_Clown_Fish_1920x1080HDTV1080p.jpg.jpg', '', '', 0),
(31, '549b139ee8f82_sea_shore.jpg.jpg', '', '', 0),
(32, '549b13a9d2ff0_wallpaper-2789936.jpeg.jpeg', 'Sky wallpaper', 'wallpaper', 0),
(36, '54a9babfdb7a6_elefanto_wallpaper.png.png', '', '', 0),
(42, '54c54f1e16298_IMG_0414.JPG.jpg', '', '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE `pages` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `title` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `content` text COLLATE utf8_unicode_ci NOT NULL,
  `user_id` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lead_image_id` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `template` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `remove` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=26 ;

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` (`id`, `title`, `content`, `user_id`, `date`, `lead_image_id`, `template`, `remove`) VALUES
(18, 'My page', 'My content<br><br><br><img src="files/images/549b13946e25a_Clown_Fish_1920x1080HDTV1080p.jpg.jpg" class="textEreaImage"><br>test<br><img src="files/images/549b13872001a_2394-10.jpg.jpg" class="textEreaImage">', '1', '0000-00-00 00:00:00', '26', 'welcome', 0),
(21, 'Third page', 'Some content<br><img src="files/images/549b13872001a_2394-10.jpg.jpg" class="textEreaImage">', '1', '2015-04-01 17:38:23', '7', 'aeadmin', 0),
(22, 'New page', 'Some content here...<br>', '1', '0000-00-00 00:00:00', '28', 'error', 0);

-- --------------------------------------------------------

--
-- Table structure for table `portfolio`
--

CREATE TABLE `portfolio` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `title` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `lead_image_id` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(1000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `link` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `category` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `remove` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

--
-- Dumping data for table `portfolio`
--

INSERT INTO `portfolio` (`id`, `title`, `lead_image_id`, `description`, `link`, `category`, `remove`) VALUES
(1, 'my first portfolio', '29', 'This is about nothing', 'aecreate.it', 'cms', 0),
(2, 'new one', '30', 'keepass.com', 'keepass.com', 'web app', 0),
(4, 'Third portfolio', '28', 'Not much...', 'Nowhere', 'None', 0);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `site_title` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `site_description` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `site_keywords` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `wallpaper_id` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `site_title`, `site_description`, `site_keywords`, `wallpaper_id`) VALUES
(1, 'Site title', 'Site descriptionsss', 'Site keywords', '42');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `username` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `profile_image` varchar(4) COLLATE utf8_unicode_ci DEFAULT NULL,
  `token` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `remove` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;
