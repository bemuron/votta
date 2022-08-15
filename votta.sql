-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jul 24, 2022 at 12:57 AM
-- Server version: 5.7.31
-- PHP Version: 7.4.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `emtechint_votta`
--

-- --------------------------------------------------------

--
-- Table structure for table `candidates`
--

DROP TABLE IF EXISTS `candidates`;
CREATE TABLE IF NOT EXISTS `candidates` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `election_id` bigint(20) UNSIGNED NOT NULL,
  `post_id` bigint(20) UNSIGNED NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `candidates`
--

INSERT INTO `candidates` (`id`, `user_id`, `election_id`, `post_id`, `description`, `image`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 'Willing to offer my best to this prosper', 'candidate.jpg', '2022-03-12 15:38:37', NULL),
(2, 1, 6, 3, 'the man himself, right for the job alone.\r\nTried and tested. Your vote secured', 'candidate.jpg', '2022-07-18 12:44:19', '2022-07-22 14:07:16'),
(3, 4, 8, 3, 'The best just', 'candidate.jpg', '2022-07-22 22:06:08', NULL),
(4, 11, 9, 3, 'Lets do this', 'candidate.jpg', '2022-07-22 22:06:45', NULL),
(5, 3, 7, 3, 'I am the big boss already', 'candidate.jpg', '2022-07-22 22:07:27', NULL),
(6, 5, 5, 3, 'The main man himself', 'candidate.jpg', '2022-07-22 22:08:08', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `elections`
--

DROP TABLE IF EXISTS `elections`;
CREATE TABLE IF NOT EXISTS `elections` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `image` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image_big` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `elections`
--

INSERT INTO `elections` (`id`, `name`, `description`, `image`, `image_big`, `created_by`, `start_date`, `end_date`, `status`, `created_at`, `updated_at`) VALUES
(7, 'Who has the best desert', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. ', 'election_thumb.jpg', 'students.JPG', 1, '2022-07-16 00:00:00', '2022-08-31 00:00:00', 1, '2022-07-16 16:20:31', '2022-07-16 16:58:44'),
(6, 'The Class President', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. ', 'election_thumb.jpg', 'students.JPG', 1, '2022-07-21 00:00:00', '2022-08-31 00:00:00', 1, '2022-07-21 12:10:10', '2022-07-22 20:17:56'),
(5, 'The best car', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. ', 'election_thumb.jpg', 'students.JPG', 1, '2022-07-16 00:00:00', '2022-08-31 00:00:00', 1, '2022-07-16 16:20:31', '2022-07-16 16:58:44'),
(8, 'Blue or Red?', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. ', 'election_thumb.jpg', 'students.JPG', 1, '2022-07-16 00:00:00', '2022-08-31 00:00:00', 1, '2022-07-16 16:20:31', '2022-07-16 16:58:44'),
(9, 'Best team', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. ', 'election_thumb.jpg', 'students.JPG', 1, '2022-07-16 00:00:00', '2022-08-31 00:00:00', 1, '2022-07-16 16:20:31', '2022-07-16 16:58:44');

-- --------------------------------------------------------

--
-- Table structure for table `election_post`
--

DROP TABLE IF EXISTS `election_post`;
CREATE TABLE IF NOT EXISTS `election_post` (
  `election_id` bigint(20) UNSIGNED NOT NULL,
  `post_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `election_user`
--

DROP TABLE IF EXISTS `election_user`;
CREATE TABLE IF NOT EXISTS `election_user` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `election_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `is_candidate` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2022_03_12_094146_create_elections_table', 1),
(6, '2022_03_12_094227_create_posts_table', 1),
(7, '2022_03_12_094315_create_votes_table', 1),
(8, '2022_03_12_094444_create_election_user_table', 1),
(9, '2022_03_12_094458_create_post_user_table', 1),
(10, '2022_03_12_094522_create_election_post_table', 1),
(12, '2022_03_12_142134_create_candidates_table', 2);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE IF NOT EXISTS `password_resets` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

DROP TABLE IF EXISTS `posts`;
CREATE TABLE IF NOT EXISTS `posts` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `election_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `election_id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 1, 'Class Leader', 'The leader of the class body', '2022-03-12 15:41:03', '2022-04-08 05:09:20'),
(2, 1, 'Chairman', 'the next board chair overseeing issues', '2022-04-08 04:54:36', '2022-04-08 05:12:11'),
(3, 6, 'Big Man', 'big man post saved', '2022-07-16 16:25:35', '2022-07-21 12:54:37'),
(4, 5, 'Another post', 'another post', '2022-07-18 11:00:54', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `post_user`
--

DROP TABLE IF EXISTS `post_user`;
CREATE TABLE IF NOT EXISTS `post_user` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `post_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_role` smallint(6) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `user_role`, `created_at`, `updated_at`) VALUES
(1, 'Test User', 'tester@email.com', '2022-03-12 15:37:14', '$2y$10$FA.vKszxyxHD48QUNGerUe4uNcOSS0rfToYOwRHx.lySIJP3ETLF.', NULL, 1, '2022-03-12 15:37:14', NULL),
(2, 'Admin User', 'admin@email.com', NULL, '$2y$10$FA.vKszxyxHD48QUNGerUe4uNcOSS0rfToYOwRHx.lySIJP3ETLF.', NULL, 1, '2022-03-15 03:44:03', '2022-03-15 03:44:03'),
(3, 'Tester2', 'tester2@email.com', NULL, '$2y$10$74WX.q2CGRyxUATc16z1Gu/F7sY8eK9/IyJ9HO3kSyINACsUr1C.C', NULL, 0, '2022-07-22 21:31:47', '2022-07-22 21:31:47'),
(4, 'Tester 3', 'tester3@email.com', NULL, '$2y$10$fG7Vwqf8dzsWQt1no3bpQOsBKEQ0wt51WmLYAY3xVq./LiNeMY0uG', NULL, 0, '2022-07-22 21:59:32', '2022-07-22 21:59:32'),
(5, 'Tester4', 'tester4@email.com', NULL, '$2y$10$dw2gpY9hYjQZ8RfKZ/GnceG9bWAmrsYTSUDXOWUBdl0AYZwwVSbeO', NULL, 0, '2022-07-22 22:00:12', '2022-07-22 22:00:12'),
(6, 'Tester5', 'tester5@email.com', NULL, '$2y$10$10qHKUYiLVgovl/KhxiNiudXVIodYm69mTD/gORm5/u68EnOOPune', NULL, 0, '2022-07-22 22:00:57', '2022-07-22 22:00:57'),
(7, 'Tester6', 'tester6@email.com', NULL, '$2y$10$HvvpkBmSmX4TWePII/F/AOWs30FQd0mawTIPpMZacMdgjoBojfLVa', NULL, 0, '2022-07-22 22:01:37', '2022-07-22 22:01:37'),
(8, 'Tester7', 'tester7@email.com', NULL, '$2y$10$1bJ3zKgYvZ5OrREJ8HIW7eE0ePpjkMpOb6G1ec1IH/az4izDwSTH.', NULL, 0, '2022-07-22 22:02:17', '2022-07-22 22:02:17'),
(9, 'Tester8', 'tester8@email.com', NULL, '$2y$10$lBqAXolPLHCHppt7SUAQTuHYThjK0UDQef7w0DzHVSIhS.BS6TuIK', NULL, 0, '2022-07-22 22:03:05', '2022-07-22 22:03:05'),
(10, 'Tester9', 'tester9@email.com', NULL, '$2y$10$5GfTGI/50mkoKH9iFgesXeMmr8kUnfDe46W5W6HGOV250F3Hlf.kS', NULL, 0, '2022-07-22 22:03:46', '2022-07-22 22:03:46'),
(11, 'Tester10', 'tester10@email.com', NULL, '$2y$10$A1sJ.vjkGI2qbafqFWIlfe6FcPQHcVUwt7Sykmq8DDNfIuW1P60BS', NULL, 0, '2022-07-22 22:04:27', '2022-07-22 22:04:27');

-- --------------------------------------------------------

--
-- Table structure for table `votes`
--

DROP TABLE IF EXISTS `votes`;
CREATE TABLE IF NOT EXISTS `votes` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `candidate_id` int(11) NOT NULL,
  `election_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `voter_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=60 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `votes`
--

INSERT INTO `votes` (`id`, `candidate_id`, `election_id`, `post_id`, `voter_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 1, '2022-03-14 10:46:06', NULL),
(2, 1, 1, 1, 1, '2022-03-14 10:46:45', NULL),
(3, 1, 1, 1, 1, '2022-03-14 10:51:03', NULL),
(4, 1, 1, 1, 1, '2022-03-14 10:51:45', NULL),
(5, 1, 1, 1, 1, '2022-03-14 10:52:41', NULL),
(6, 1, 1, 1, 1, '2022-03-14 10:55:33', NULL),
(7, 1, 1, 1, 1, '2022-03-14 10:59:08', NULL),
(8, 1, 1, 1, 1, '2022-03-14 11:01:28', NULL),
(9, 1, 1, 1, 1, '2022-03-14 11:03:08', NULL),
(10, 1, 1, 1, 1, '2022-03-14 11:15:50', NULL),
(11, 1, 1, 1, 1, '2022-03-14 11:16:31', NULL),
(12, 1, 1, 1, 1, '2022-03-14 11:17:47', NULL),
(13, 1, 1, 1, 1, '2022-03-14 11:18:34', NULL),
(14, 1, 1, 1, 1, '2022-03-14 11:20:25', NULL),
(15, 1, 1, 1, 1, '2022-03-14 11:22:28', NULL),
(16, 1, 1, 1, 1, '2022-03-14 11:29:02', NULL),
(17, 1, 1, 1, 1, '2022-03-14 11:33:57', NULL),
(18, 1, 1, 1, 1, '2022-03-14 11:35:37', NULL),
(19, 1, 1, 1, 1, '2022-03-14 11:39:01', NULL),
(20, 1, 1, 1, 1, '2022-03-14 11:53:44', NULL),
(21, 1, 1, 1, 1, '2022-03-14 11:54:21', NULL),
(22, 1, 1, 1, 1, '2022-03-14 11:54:33', NULL),
(23, 1, 1, 1, 1, '2022-03-14 11:57:03', NULL),
(24, 1, 1, 1, 1, '2022-03-14 11:58:49', NULL),
(25, 1, 1, 1, 1, '2022-03-14 11:59:51', NULL),
(26, 1, 1, 1, 1, '2022-03-14 12:00:14', NULL),
(27, 1, 1, 1, 1, '2022-03-14 12:01:31', NULL),
(28, 1, 1, 1, 1, '2022-03-14 12:03:01', NULL),
(29, 1, 1, 1, 1, '2022-03-14 12:03:15', NULL),
(30, 1, 1, 1, 1, '2022-03-14 12:03:46', NULL),
(31, 1, 1, 1, 1, '2022-03-14 12:03:58', NULL),
(32, 1, 1, 1, 1, '2022-03-14 12:04:13', NULL),
(33, 1, 1, 1, 1, '2022-03-14 12:04:23', NULL),
(34, 1, 1, 1, 1, '2022-03-14 12:05:18', NULL),
(35, 1, 1, 1, 1, '2022-03-14 12:05:27', NULL),
(36, 1, 1, 1, 1, '2022-03-14 12:06:39', NULL),
(37, 1, 1, 1, 1, '2022-03-14 12:06:48', NULL),
(38, 1, 1, 1, 1, '2022-03-14 12:07:25', NULL),
(39, 1, 1, 1, 1, '2022-03-14 12:07:35', NULL),
(40, 1, 1, 1, 1, '2022-03-14 12:08:30', NULL),
(41, 1, 1, 1, 1, '2022-03-14 12:08:59', NULL),
(42, 1, 1, 1, 1, '2022-03-14 12:10:33', NULL),
(43, 1, 1, 1, 1, '2022-03-14 12:10:59', NULL),
(44, 1, 1, 1, 1, '2022-03-14 12:12:13', NULL),
(45, 1, 1, 1, 1, '2022-03-14 12:12:31', NULL),
(46, 1, 1, 1, 1, '2022-03-14 12:13:12', NULL),
(47, 1, 1, 1, 1, '2022-03-14 12:13:22', NULL),
(48, 1, 1, 1, 1, '2022-03-14 12:15:14', NULL),
(49, 1, 1, 1, 1, '2022-03-14 12:15:58', NULL),
(50, 1, 1, 1, 1, '2022-03-14 17:00:33', NULL),
(51, 1, 1, 1, 1, '2022-03-15 01:35:40', NULL),
(52, 2, 6, 3, 1, '2022-07-22 20:18:41', NULL),
(53, 2, 6, 3, 3, '2022-07-22 21:32:01', NULL),
(54, 6, 6, 3, 5, '2022-07-23 15:53:43', NULL),
(55, 6, 6, 3, 4, '2022-07-23 15:56:44', NULL),
(56, 6, 6, 3, 8, '2022-07-23 16:03:07', NULL),
(57, 6, 6, 3, 7, '2022-07-23 16:20:11', NULL),
(58, 4, 6, 3, 9, '2022-07-23 16:35:58', NULL),
(59, 5, 6, 3, 10, '2022-07-23 17:11:32', NULL);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;