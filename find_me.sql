-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 11, 2026 at 05:26 AM
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
(1, '01', '0101', 'Chrouy Sdao', 'ជ្រោយស្តៅ', '010101', 11.5564000, 104.9282000, 0, '2026-03-08 01:18:43', '2026-03-08 01:18:43'),
(3, '02', '0201', 'Khnarch Meas', 'ខ្នាចមាស', '020101', 11.5564000, 104.9282000, 0, '2026-03-08 02:32:22', '2026-03-08 02:32:22');

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
(1, '01', 'Ma Lai', 'ម៉ាឡៃ', '0101', 12.6689880, 104.2977400, 0, '2026-03-08 00:28:23', '2026-03-09 08:21:57'),
(2, '02', 'Sampov Loun', 'សំពៅលូន', '0201', 12.6193210, 102.8725590, 0, '2026-03-08 00:32:33', '2026-03-08 00:33:11');

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
  `latitude` decimal(10,7) DEFAULT NULL,
  `longitude` decimal(10,7) DEFAULT NULL,
  `visit` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hcs`
--

INSERT INTO `hcs` (`id`, `province_code`, `district_code`, `commune_code`, `od_code`, `hc_name_en`, `hc_name_kh`, `hc_code`, `latitude`, `longitude`, `visit`, `created_at`, `updated_at`) VALUES
(1, '01', '0101', '010101', '01010101', 'Boeng Pring', 'បឹងព្រីង', '0101010101', 11.5564000, 104.9282000, 0, '2026-03-08 02:34:11', '2026-03-08 02:34:11'),
(2, '02', '0201', '020101', '02010101', 'Trav Chou', 'ត្រាវជូរ', '0201010101', 12.7768320, 103.7307310, 0, '2026-03-08 02:36:15', '2026-03-08 02:37:18');

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
(13, '2026_03_08_040224_create_villages_table', 1);

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
  `latitude` decimal(10,7) DEFAULT NULL,
  `longitude` decimal(10,7) DEFAULT NULL,
  `visit` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ods`
--

INSERT INTO `ods` (`id`, `province_code`, `district_code`, `commune_code`, `od_name_en`, `od_name_kh`, `od_code`, `latitude`, `longitude`, `visit`, `created_at`, `updated_at`) VALUES
(1, '01', '0101', '010101', 'Ma Lai', 'ម៉ាឡៃ', '01010101', 12.9869300, 103.9284850, 0, '2026-03-08 01:50:08', '2026-03-08 02:35:43'),
(3, '02', '0201', '020101', 'Sampov Loun', 'សំពៅលូន', '02010101', 12.8905630, 103.7197450, 0, '2026-03-08 02:33:22', '2026-03-08 02:33:22');

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
(3, 'Banteay Meanchey', 'បន្ទាយមានជ័យ', '01', 13.5750390, 102.9687860, '2026-03-07 22:48:29', '2026-03-07 23:51:07'),
(4, 'Battambang', 'បាត់ដំបង', '02', 12.9154020, 102.9024760, '2026-03-08 00:29:27', '2026-03-08 00:29:27');

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
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `registers`
--

INSERT INTO `registers` (`id`, `username`, `gender`, `email`, `phone`, `password`, `role_id`, `status`, `profile_image`, `created_at`, `updated_at`) VALUES
(1, 'saky', 'male', 'ransaky@gmail.com', '012 555 999', '$2y$12$xkqJ85PVLBqWWYrOsx1dUeHrMqWsCxcWP3x0IKQMEVxlml86yZhe2', 1, 'active', 'profiles/mZhnSnO59AY13A8V97TKGZej167mXfZlnfdib0nc.png', '2026-03-07 22:01:18', '2026-03-07 22:01:18'),
(2, 'admin', 'male', 'admin@gmail.com', '012 999 555', '$2y$12$3s75TDR/QDTjHf8NOXbdQOz4VAu/jz9gmTGm2a.JYYniuFQDLX/9u', 1, 'active', 'profiles/0z00H12M7DIMa2ZlwKbazG6VRYGDnIVCqQ87yRyb.jpg', '2026-03-10 19:03:46', '2026-03-10 19:03:46'),
(3, 'ylimsros', 'male', 'ylimsros.pwd@gmail.com', '0716088000', '$2y$12$Yb8ATcQGrAi.vW0mMb2JUeXutyeXxSdawTfGYgucN1tbh.9ORp95S', 2, 'active', NULL, '2026-03-10 19:10:45', '2026-03-10 19:10:45'),
(4, 'chhilrim', 'male', 'chhilrimcms@gmail.com', NULL, '$2y$12$p5bcdQHR2gGsbU2nn5VYTu5RZ8r7naf0gsL2r2TUsXqEW96Zptk6u', 1, 'active', NULL, '2026-03-10 19:15:46', '2026-03-10 19:15:46');

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
(1, 'admin', 'admintrative', '2026-03-07 22:00:50', '2026-03-07 22:00:50'),
(2, 'User', NULL, '2026-03-10 19:09:47', '2026-03-10 19:09:47');

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
('9YzfNDf8UaWTRxJo5LPbvKOBvYfHOwDiuk1kUIU6', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiZUtSUTdneE45ZXFSc3lNY25WdjBvd1V5dXVLYkZhNHBib0dydEoxSyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7Tjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1773202026),
('l2AvBZy5PMS8JR4Mwgmgjglg2Aqky8jsPYxWywPa', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoic0FmYXVoZ0x5NkFtbDRnbDdqalJBbncyTHRjUmh1dzJjMGJuMWhuSCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NzA6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC8ud2VsbC1rbm93bi9hcHBzcGVjaWZpYy9jb20uY2hyb21lLmRldnRvb2xzLmpzb24iO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1773072091),
('Wy5i7tPunES7pRXxHrYYHYZS48j1nACxG1WNdXBH', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoicG1VRW5LRjZQdExFNThyYm1mbkZPcnYwMmFFTUp6VmVMRXNVZHZyOCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzA6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9yZWdpc3RlciI7czo1OiJyb3V0ZSI7Tjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1773201737);

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
(1, '02', '0201', '020101', '02010101', '0201010101', 'Kroam', 'ក្រោម', '020101010101', 13.1019360, 102.7056400, 0, '2026-03-08 03:13:43', '2026-03-08 03:13:43');

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `districts`
--
ALTER TABLE `districts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hcs`
--
ALTER TABLE `hcs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `ods`
--
ALTER TABLE `ods`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `provinces`
--
ALTER TABLE `provinces`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `registers`
--
ALTER TABLE `registers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

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
