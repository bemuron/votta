-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Oct 11, 2022 at 01:00 PM
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
-- Database: `votta`
--

-- --------------------------------------------------------

--
-- Table structure for table `candidates`
--

DROP TABLE IF EXISTS `candidates`;
CREATE TABLE IF NOT EXISTS `candidates` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `candidate_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `election_id` bigint(20) UNSIGNED NOT NULL,
  `post_id` bigint(20) UNSIGNED NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `candidates`
--

INSERT INTO `candidates` (`id`, `candidate_name`, `election_id`, `post_id`, `description`, `image`, `created_at`, `updated_at`) VALUES
(1, 'Sam Maxwell', 6, 3, 'Willing to offer my best as the voice for the students', 'sam.jpg', '2022-03-12 15:38:37', NULL),
(2, 'John Smith', 6, 3, 'the man himself, right for the job alone.\r\nTried and tested. Your vote secured', 'john.jpg', '2022-07-18 12:44:19', '2022-07-22 14:07:16'),
(4, 'Team Mark', 9, 5, 'Lets do this', 'mark.jpg', '2022-07-22 22:06:45', NULL),
(5, 'Cafe Javas', 11, 9, 'Dessert is our thing', 'javas.jpg', '2022-07-22 22:07:27', NULL),
(6, 'Mercedes', 5, 4, 'Majestic in style', 'mercedes.jpg', '2022-07-22 22:08:08', NULL),
(7, 'Audi', 5, 4, 'Simpy elegant', 'audi.jpg', '2022-07-22 22:08:08', NULL),
(8, 'Team Samy', 9, 5, 'We get things done', 'sammy.jpg', '2022-07-22 22:08:08', '2022-08-16 09:40:51'),
(9, 'KFC', 11, 9, 'Ask us about dessert.', 'kfc.jpg', '2022-07-22 22:08:08', NULL);

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
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `elections`
--

INSERT INTO `elections` (`id`, `name`, `description`, `image`, `image_big`, `created_by`, `start_date`, `end_date`, `status`, `created_at`, `updated_at`) VALUES
(6, 'The Class President', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.', 'pres_thumb.jpg', 'pres_big.JPG', 2, '2022-07-21 00:00:00', '2022-12-31 00:00:00', 1, '2022-07-21 12:10:10', '2022-09-19 09:29:15'),
(5, 'The best car', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. ', 'car_thumb.jpg', 'car_big.JPG', 2, '2022-07-16 00:00:00', '2022-12-31 00:00:00', 1, '2022-07-16 16:20:31', '2022-07-16 16:58:44'),
(11, 'The best dessert', 'Choosing who has the best dessert.', 'dessert_thumb.jpg', 'dessert_big.jpg', 2, '2022-08-01 00:00:00', '2022-12-31 00:00:00', 1, '2022-09-23 20:30:03', NULL),
(9, 'Best team', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. ', 'team_thumb.jpg', 'team_big.JPG', 2, '2022-07-16 00:00:00', '2022-12-31 00:00:00', 1, '2022-07-16 16:20:31', '2022-07-16 16:58:44');

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
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(12, '2022_03_12_142134_create_candidates_table', 2),
(13, '2022_09_20_063001_create_user_divisions_table', 3),
(14, '2022_09_20_063339_create_user_sub_divisions_table', 3),
(15, '2022_09_24_114004_create_voter_bases_table', 4);

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
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `election_id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 1, 'Class Leader', 'The leader of the class body', '2022-03-12 15:41:03', '2022-04-08 05:09:20'),
(2, 1, 'Chairman', 'the next board chair overseeing issues', '2022-04-08 04:54:36', '2022-04-08 05:12:11'),
(3, 6, 'Class President', 'Post held by the winner of the class president poll', '2022-07-16 16:25:35', '2022-07-21 12:54:37'),
(4, 5, 'The Best Car', 'Title for the overall best car', '2022-07-18 11:00:54', NULL),
(5, 9, 'Best Team', 'The best of the competing teams', '2022-07-18 11:00:54', NULL),
(6, 7, 'Dessert Crown', 'Winner of the the best dessert', '2022-07-18 11:00:54', NULL),
(7, 7, 'de best sample post', 'talking about the best. Simply the best', '2022-09-19 09:04:50', '2022-09-19 09:35:33'),
(9, 11, 'Dessert King', 'Who will hold the bragging rights for the best made dessert', '2022-09-23 20:40:37', NULL);

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
  `sub_division` int(11) DEFAULT '0',
  `status` smallint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `salt` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `user_role`, `sub_division`, `status`, `created_at`, `updated_at`, `salt`) VALUES
(2, 'Admin User', 'admin@email.com', NULL, '$2y$10$FA.vKszxyxHD48QUNGerUe4uNcOSS0rfToYOwRHx.lySIJP3ETLF.', NULL, 1, 3, 1, '2022-03-15 03:44:03', '2022-03-15 03:44:03', 'c5g73sq9v1'),
(23, 'Vickey', 'vickey@email.com', NULL, '$2y$10$1v2K89BfNKxBxLroWMXJUOAjn1IQeYsCSuTcem.sI8pFK6zL1mjM.', NULL, 0, 3, 1, '2022-10-11 06:10:04', NULL, '2b07eff46a'),
(24, 'Max', 'max@email.com', NULL, '$2y$10$/Vqfu3U4dHW/h5a3W64bhebbJvjZPBPA59r83Bb59ZI.ihi.1r6IG', NULL, 0, 7, 1, '2022-10-11 06:10:37', NULL, '8e9899f89a'),
(25, 'Peter', 'peter@email.com', NULL, '$2y$10$k9.dgefMZ4ydIRihrOXBAO6h5SuZIAsr1gL6.NpEu3Pyv1XkDGWbS', NULL, 0, 8, 1, '2022-10-11 06:11:26', NULL, '5e27bc4de7'),
(22, 'Suzan', 'suzan@email.com', NULL, '$2y$10$DGVJBMsN/AsmyYc5ToD8Qei8FJlSvlkavPcl9FLMkALnExFY30gP2', NULL, 0, 6, 1, '2022-10-11 06:09:03', NULL, 'ff735fb728'),
(21, 'Mark', 'mark@email.com', NULL, '$2y$10$t5wY0boOoPWGFogi6anEuu8EmpeusSKSQHVTBkTrW3Hg3jUiJuoey', NULL, 0, 5, 1, '2022-10-11 05:56:10', NULL, 'a7b20cc1b3');

-- --------------------------------------------------------

--
-- Table structure for table `user_divisions`
--

DROP TABLE IF EXISTS `user_divisions`;
CREATE TABLE IF NOT EXISTS `user_divisions` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `division_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_divisions`
--

INSERT INTO `user_divisions` (`id`, `division_name`, `created_at`, `updated_at`) VALUES
(1, 'Human Resources', '2022-09-20 17:50:42', '2022-09-20 17:52:32'),
(3, 'IT', '2022-09-23 18:57:46', '2022-09-23 18:57:46'),
(4, 'Finance', '2022-09-23 18:57:46', '2022-09-23 18:57:46'),
(5, 'Legal', '2022-09-23 18:57:46', '2022-09-23 18:57:46'),
(6, 'Procurement', '2022-09-23 18:57:46', '2022-09-23 18:57:46');

-- --------------------------------------------------------

--
-- Table structure for table `user_sub_divisions`
--

DROP TABLE IF EXISTS `user_sub_divisions`;
CREATE TABLE IF NOT EXISTS `user_sub_divisions` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `division_id` bigint(20) UNSIGNED DEFAULT NULL,
  `sub_division_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_sub_divisions`
--

INSERT INTO `user_sub_divisions` (`id`, `division_id`, `sub_division_name`, `created_at`, `updated_at`) VALUES
(1, 1, 'Wellbeing', '2022-09-20 18:37:41', '2022-09-20 18:41:31'),
(3, 1, 'Talent Sourcing', '2022-09-23 20:00:38', '2022-09-23 20:00:38'),
(4, 3, 'App Development', '2022-09-23 20:00:38', '2022-09-23 20:00:38'),
(5, 3, 'System Admin', '2022-09-23 20:00:38', '2022-09-23 20:00:38'),
(6, 4, 'Payments', '2022-09-23 20:00:38', '2022-09-23 20:00:38'),
(7, 5, 'Legal', '2022-10-11 05:11:57', NULL),
(8, 6, 'Procurement', '2022-10-11 05:50:37', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `voter_bases`
--

DROP TABLE IF EXISTS `voter_bases`;
CREATE TABLE IF NOT EXISTS `voter_bases` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `election_id` bigint(20) UNSIGNED NOT NULL,
  `division_id` bigint(20) UNSIGNED NOT NULL,
  `sub_division_id` bigint(20) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `voter_bases`
--

INSERT INTO `voter_bases` (`id`, `election_id`, `division_id`, `sub_division_id`) VALUES
(5, 5, 1, 0),
(7, 11, 5, 0),
(8, 9, 6, 0),
(10, 11, 4, 0),
(12, 5, 5, 0),
(16, 6, 5, 0),
(17, 6, 6, 0),
(18, 6, 4, 6);

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
  `voter_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `votes`
--

INSERT INTO `votes` (`id`, `candidate_id`, `election_id`, `post_id`, `voter_id`, `created_at`, `updated_at`) VALUES
(1, 4, 9, 5, 'zWmm/q7T8rIPf/pCkagoWdoMPoQ1ZTI3YmM0ZGU3', '2022-10-11 06:23:37', NULL),
(2, 1, 6, 3, 'zWmm/q7T8rIPf/pCkagoWdoMPoQ1ZTI3YmM0ZGU3', '2022-10-11 06:24:23', NULL),
(3, 1, 6, 3, 'SnYlChAolsoRZob/JWwAMDLngbM4ZTk4OTlmODlh', '2022-10-11 06:26:15', NULL),
(4, 7, 5, 4, 'SnYlChAolsoRZob/JWwAMDLngbM4ZTk4OTlmODlh', '2022-10-11 06:27:00', NULL),
(5, 5, 11, 9, 'SnYlChAolsoRZob/JWwAMDLngbM4ZTk4OTlmODlh', '2022-10-11 06:27:27', NULL),
(6, 7, 5, 4, 'WM3DM1FCWi7MmIKQSAb7x3i/eK4yYjA3ZWZmNDZh', '2022-10-11 06:30:10', NULL),
(7, 5, 11, 9, 'rtG44IietYbyVq//PovUTTeilmVmZjczNWZiNzI4', '2022-10-11 06:31:44', NULL),
(8, 2, 6, 3, 'rtG44IietYbyVq//PovUTTeilmVmZjczNWZiNzI4', '2022-10-11 06:32:06', NULL);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
