-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 25, 2026 at 03:15 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `find_me`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `communes`
--

CREATE TABLE `communes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `province_code` varchar(20) NOT NULL,
  `district_code` varchar(20) NOT NULL,
  `commune_name_en` varchar(255) NOT NULL,
  `commune_name_kh` varchar(255) NOT NULL,
  `commune_code` varchar(20) NOT NULL,
  `latitude` decimal(10,7) DEFAULT NULL,
  `longitude` decimal(10,7) DEFAULT NULL,
  `visit` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `communes`
--

INSERT INTO `communes` (`id`, `province_code`, `district_code`, `commune_name_en`, `commune_name_kh`, `commune_code`, `latitude`, `longitude`, `visit`, `created_at`, `updated_at`) VALUES
(1, '01', '0101', 'Phnom Dek', 'ភ្នំដែក', '010101', 11.5564000, 104.9282000, 0, '2026-03-15 03:00:24', '2026-03-16 02:28:21');

-- --------------------------------------------------------

--
-- Table structure for table `districts`
--

CREATE TABLE `districts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `province_code` varchar(20) NOT NULL,
  `district_name_en` varchar(255) NOT NULL,
  `district_name_kh` varchar(255) NOT NULL,
  `district_code` varchar(20) NOT NULL,
  `latitude` decimal(10,7) DEFAULT NULL,
  `longitude` decimal(10,7) DEFAULT NULL,
  `visit` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `districts`
--

INSERT INTO `districts` (`id`, `province_code`, `district_name_en`, `district_name_kh`, `district_code`, `latitude`, `longitude`, `visit`, `created_at`, `updated_at`) VALUES
(1, '01', 'Sampov Loun', 'សំពៅលូន', '0101', 11.5564000, 104.9282000, 0, '2026-03-15 03:00:14', '2026-03-15 03:59:30');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hcs`
--

CREATE TABLE `hcs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `province_code` varchar(20) NOT NULL,
  `district_code` varchar(20) NOT NULL,
  `commune_code` varchar(20) NOT NULL,
  `od_code` varchar(20) NOT NULL,
  `hc_name_en` varchar(255) NOT NULL,
  `hc_name_kh` varchar(255) NOT NULL,
  `hc_code` varchar(20) NOT NULL,
  `name_director` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `latitude` decimal(10,7) DEFAULT NULL,
  `longitude` decimal(10,7) DEFAULT NULL,
  `visit` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hcs`
--

INSERT INTO `hcs` (`id`, `province_code`, `district_code`, `commune_code`, `od_code`, `hc_name_en`, `hc_name_kh`, `hc_code`, `name_director`, `phone`, `image`, `latitude`, `longitude`, `visit`, `created_at`, `updated_at`) VALUES
(1, '01', '0101', '010101', '01010101', 'Takrey', 'តាក្រី', '0101010101', 'test', '093945893', 'hcs/AcelqDs8TkL6Obuc4gIt4aZGguYqpLhAjMQaMU46.jpg', 11.5564000, 104.9282000, 0, '2026-03-15 08:36:16', '2026-03-15 08:38:18');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_02_27_130452_create_personal_access_tokens_table', 1),
(5, '2026_03_02_085254_add_username_to_users_table', 1),
(6, '2026_03_03_044626_create_roles_table', 1),
(7, '2026_03_03_113907_create_registers_table', 1),
(8, '2026_03_08_040219_create_provinces_table', 1),
(9, '2026_03_08_040220_create_communes_table', 1),
(10, '2026_03_08_040220_create_districts_table', 1),
(11, '2026_03_08_040221_create_ods_table', 1),
(12, '2026_03_08_040222_create_hcs_table', 1),
(13, '2026_03_08_040224_create_villages_table', 1),
(14, '2026_03_12_093622_add_remember_token_to_registers_table', 1),
(15, '2026_03_15_093203_add_director_phone_image_to_ods_table', 1),
(16, '2026_03_15_153053_add_name_director_phone_image_to_hcs_table', 2);

-- --------------------------------------------------------

--
-- Table structure for table `ods`
--

CREATE TABLE `ods` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `province_code` varchar(20) NOT NULL,
  `district_code` varchar(20) NOT NULL,
  `commune_code` varchar(20) NOT NULL,
  `od_name_en` varchar(255) NOT NULL,
  `od_name_kh` varchar(255) NOT NULL,
  `od_code` varchar(20) NOT NULL,
  `name_director` varchar(255) DEFAULT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `latitude` decimal(10,7) DEFAULT NULL,
  `longitude` decimal(10,7) DEFAULT NULL,
  `visit` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ods`
--

INSERT INTO `ods` (`id`, `province_code`, `district_code`, `commune_code`, `od_name_en`, `od_name_kh`, `od_code`, `name_director`, `phone`, `image`, `latitude`, `longitude`, `visit`, `created_at`, `updated_at`) VALUES
(1, '01', '0101', '010101', 'Sampov Loun', 'សំពៅលូន', '01010101', 'លោកបណ្ឌិតសាស្ត្រាចារ្យ ឆិល រីម', '012 999 222', 'ods/xny0FlAHnmkrU7rqvLaVBGnLWoqTVOVxtXZhRXBR.jpg', 11.5951240, 104.8703790, 0, '2026-03-15 03:01:47', '2026-03-15 03:57:37');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` text NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `provinces`
--

CREATE TABLE `provinces` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `province_name_en` varchar(255) NOT NULL,
  `province_name_kh` varchar(255) NOT NULL,
  `province_code` varchar(20) NOT NULL,
  `latitude` decimal(10,7) DEFAULT NULL,
  `longitude` decimal(10,7) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `provinces`
--

INSERT INTO `provinces` (`id`, `province_name_en`, `province_name_kh`, `province_code`, `latitude`, `longitude`, `created_at`, `updated_at`) VALUES
(1, 'Battambang', 'បាត់ដំបង', '01', 11.5564000, 104.9282000, '2026-03-15 03:00:05', '2026-03-15 03:59:43'),
(4, 'Banteay Meanchey', 'បន្ទាយមានជ័យ', '02', 11.5564000, 104.9282000, '2026-03-18 05:26:29', '2026-03-18 05:26:29');

-- --------------------------------------------------------

--
-- Table structure for table `registers`
--

CREATE TABLE `registers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `username` varchar(255) NOT NULL,
  `gender` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `status` varchar(255) NOT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `registers`
--

INSERT INTO `registers` (`id`, `username`, `gender`, `email`, `phone`, `password`, `role_id`, `status`, `profile_image`, `created_at`, `updated_at`, `remember_token`) VALUES
(1, 'saky', 'male', 'ransaky@gmail.com', '012 999 555', '$2y$12$TJIk4n.mr3vwLg5v578w6.ZmcVmcCLOrmt2sDCJzvwkFn1fEDLBA.', 1, 'active', 'profiles/But0QSSrrqVsGlYk65hGgskn9sL0P4ZKGqXSTHI1.png', '2026-03-15 02:44:52', '2026-03-15 02:44:52', NULL),
(2, 'admin', 'male', 'devillsoprozzz169@gmail.com', '012 222 555', '$2y$12$FnF1Kq1x9UNdcka2Kh0Jnuro7Av6F/4k7eWeH/5xPu96fWVvPGi1C', 1, 'active', 'profiles/zIjcYxaRmTioQaYPST9i2naZExJFRqpBVAjGJ7Dg.jpg', '2026-03-15 08:10:45', '2026-03-20 09:17:09', 'qelEr1ew2gIYCqectrR6tYwjw8vEtV1e6JQ74p6iuid7Wfgyp1OJ2CGGyLr0'),
(3, 'test', 'male', 'test@gmail.com', '012 333 555', '$2y$12$uTkrH1BJTuJcaFCgVcpeGuYSpkwbhOl0BZN2fHW66VBsZVSRYVdLC', 2, 'active', 'profiles/gIO1JW48tjgzEetZ07zYJEYU3pwsdykfrLiTfhPq.jpg', '2026-03-16 01:32:11', '2026-03-18 00:13:49', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'Admintrative', '2026-03-15 02:43:58', '2026-03-15 02:44:10'),
(2, 'User', 'Guest Only', '2026-03-15 02:44:20', '2026-03-15 02:44:20'),
(3, 'Staff', 'Staff Only', '2026-03-16 01:29:53', '2026-03-16 01:30:17');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('1ZP1I0xCuYm9ryCigkoO0ylb4E45c0UE9JTcZBoe', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoibmJRbTdvMElYZ3I2WG9mMldGa1dZcjBveElwQklicGxDTXNIQnROUyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7Tjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1774107078),
('4RI36BungGEJSvdNeWSKybFIkD0PPPe0PTBdrb3y', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiakR5N09Ed0xCck9yVGF2U0JiMnRsR1drRkk0eThIeEFibXNOV05vRCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzA6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9yZWdpc3RlciI7czo1OiJyb3V0ZSI7Tjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1774023326),
('AALCBzUwa35y8QSCsODKQXc7Do0lc9ZNq0rh00Y8', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoic0ZNWjJZZ21SZlhyMzgyM2g1VzFQUUdFQTVhOE1SNXVPOEZ6a0dlMyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7Tjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1774404040),
('GqLUMkLYX5ow5tmirOJF1x9x33wfQhotFsAuCwJD', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiVEdWdVVvb0xQUlBqQ085VjdsNnpSODhndHgzanM0RnJhV2Z0MTRKcyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7Tjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1774023514);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `villages`
--

CREATE TABLE `villages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `province_code` varchar(20) NOT NULL,
  `district_code` varchar(20) NOT NULL,
  `commune_code` varchar(20) NOT NULL,
  `od_code` varchar(20) NOT NULL,
  `hc_code` varchar(20) NOT NULL,
  `village_name_en` varchar(255) NOT NULL,
  `village_name_kh` varchar(255) NOT NULL,
  `village_code` varchar(20) NOT NULL,
  `latitude` decimal(10,7) DEFAULT NULL,
  `longitude` decimal(10,7) DEFAULT NULL,
  `visit` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `villages`
--

INSERT INTO `villages` (`id`, `province_code`, `district_code`, `commune_code`, `od_code`, `hc_code`, `village_name_en`, `village_name_kh`, `village_code`, `latitude`, `longitude`, `visit`, `created_at`, `updated_at`) VALUES
(1, '01', '0101', '010101', '01010101', '0101010101', 'Kamrieng', 'កំរៀង', '010101010101', 13.0885200, 102.4711460, 0, '2026-03-18 00:02:37', '2026-03-18 00:02:37');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `communes`
--
ALTER TABLE `communes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `communes_commune_code_unique` (`commune_code`),
  ADD KEY `communes_province_code_district_code_commune_code_index` (`province_code`,`district_code`,`commune_code`);

--
-- Indexes for table `districts`
--
ALTER TABLE `districts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `districts_district_code_unique` (`district_code`),
  ADD KEY `districts_province_code_district_code_index` (`province_code`,`district_code`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `hcs`
--
ALTER TABLE `hcs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `hcs_hc_code_unique` (`hc_code`),
  ADD KEY `hcs_province_code_foreign` (`province_code`),
  ADD KEY `hcs_district_code_foreign` (`district_code`),
  ADD KEY `hcs_commune_code_foreign` (`commune_code`),
  ADD KEY `hcs_od_code_foreign` (`od_code`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ods`
--
ALTER TABLE `ods`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ods_od_code_unique` (`od_code`),
  ADD KEY `ods_district_code_foreign` (`district_code`),
  ADD KEY `ods_commune_code_foreign` (`commune_code`),
  ADD KEY `ods_province_code_district_code_commune_code_od_code_index` (`province_code`,`district_code`,`commune_code`,`od_code`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  ADD KEY `personal_access_tokens_expires_at_index` (`expires_at`);

--
-- Indexes for table `provinces`
--
ALTER TABLE `provinces`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `provinces_province_code_unique` (`province_code`),
  ADD KEY `provinces_province_code_index` (`province_code`);

--
-- Indexes for table `registers`
--
ALTER TABLE `registers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `registers_email_unique` (`email`),
  ADD KEY `registers_role_id_foreign` (`role_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_unique` (`name`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_username_unique` (`username`);

--
-- Indexes for table `villages`
--
ALTER TABLE `villages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `villages_village_code_unique` (`village_code`),
  ADD KEY `villages_province_code_foreign` (`province_code`),
  ADD KEY `villages_district_code_foreign` (`district_code`),
  ADD KEY `villages_commune_code_foreign` (`commune_code`),
  ADD KEY `villages_od_code_foreign` (`od_code`),
  ADD KEY `villages_hc_code_foreign` (`hc_code`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `communes`
--
ALTER TABLE `communes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `districts`
--
ALTER TABLE `districts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hcs`
--
ALTER TABLE `hcs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `ods`
--
ALTER TABLE `ods`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `provinces`
--
ALTER TABLE `provinces`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `registers`
--
ALTER TABLE `registers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `villages`
--
ALTER TABLE `villages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `districts`
--
ALTER TABLE `districts`
  ADD CONSTRAINT `districts_province_code_foreign` FOREIGN KEY (`province_code`) REFERENCES `provinces` (`province_code`) ON DELETE CASCADE;

--
-- Constraints for table `hcs`
--
ALTER TABLE `hcs`
  ADD CONSTRAINT `hcs_commune_code_foreign` FOREIGN KEY (`commune_code`) REFERENCES `communes` (`commune_code`) ON DELETE CASCADE,
  ADD CONSTRAINT `hcs_district_code_foreign` FOREIGN KEY (`district_code`) REFERENCES `districts` (`district_code`) ON DELETE CASCADE,
  ADD CONSTRAINT `hcs_od_code_foreign` FOREIGN KEY (`od_code`) REFERENCES `ods` (`od_code`) ON DELETE CASCADE,
  ADD CONSTRAINT `hcs_province_code_foreign` FOREIGN KEY (`province_code`) REFERENCES `provinces` (`province_code`) ON DELETE CASCADE;

--
-- Constraints for table `ods`
--
ALTER TABLE `ods`
  ADD CONSTRAINT `ods_commune_code_foreign` FOREIGN KEY (`commune_code`) REFERENCES `communes` (`commune_code`) ON DELETE CASCADE,
  ADD CONSTRAINT `ods_district_code_foreign` FOREIGN KEY (`district_code`) REFERENCES `districts` (`district_code`) ON DELETE CASCADE,
  ADD CONSTRAINT `ods_province_code_foreign` FOREIGN KEY (`province_code`) REFERENCES `provinces` (`province_code`) ON DELETE CASCADE;

--
-- Constraints for table `registers`
--
ALTER TABLE `registers`
  ADD CONSTRAINT `registers_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `villages`
--
ALTER TABLE `villages`
  ADD CONSTRAINT `villages_commune_code_foreign` FOREIGN KEY (`commune_code`) REFERENCES `communes` (`commune_code`) ON DELETE CASCADE,
  ADD CONSTRAINT `villages_district_code_foreign` FOREIGN KEY (`district_code`) REFERENCES `districts` (`district_code`) ON DELETE CASCADE,
  ADD CONSTRAINT `villages_hc_code_foreign` FOREIGN KEY (`hc_code`) REFERENCES `hcs` (`hc_code`) ON DELETE CASCADE,
  ADD CONSTRAINT `villages_od_code_foreign` FOREIGN KEY (`od_code`) REFERENCES `ods` (`od_code`) ON DELETE CASCADE,
  ADD CONSTRAINT `villages_province_code_foreign` FOREIGN KEY (`province_code`) REFERENCES `provinces` (`province_code`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
