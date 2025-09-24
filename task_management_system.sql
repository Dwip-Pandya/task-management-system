-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 24, 2025 at 09:10 AM
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
-- Database: `task_management_system`
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
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `comment_id` bigint(20) UNSIGNED NOT NULL,
  `task_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `message` text NOT NULL,
  `parent_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`comment_id`, `task_id`, `user_id`, `message`, `parent_id`, `created_at`, `updated_at`) VALUES
(1, 1, 3, 'This should be done by the weekend.', NULL, '2025-09-19 13:04:11', '2025-09-19 13:04:11'),
(2, 1, 5, 'Ma\'am, i am currently Occupied by another task with high priority, please try to hand-over this task to another developer.', 1, '2025-09-19 13:06:19', '2025-09-19 13:06:19'),
(3, 7, 1, 'Work Done.', NULL, '2025-09-19 23:41:30', '2025-09-19 23:41:30'),
(4, 6, 3, 'I want this task done by weekend.', NULL, '2025-09-19 23:58:50', '2025-09-19 23:58:50'),
(5, 2, 1, 'Complete.', NULL, '2025-09-20 00:01:37', '2025-09-20 00:01:37'),
(6, 2, 3, 'Well done.', 5, '2025-09-20 00:02:52', '2025-09-20 00:02:52'),
(7, 13, 3, 'Should be done on weekend.', NULL, '2025-09-20 00:04:18', '2025-09-20 00:04:18'),
(8, 14, 3, 'This should be done on weekend.', NULL, '2025-09-20 00:14:31', '2025-09-20 00:14:31'),
(9, 14, 1, 'Sure ma\'am', 8, '2025-09-20 00:17:44', '2025-09-20 00:17:44');

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
(4, '2025_09_19_094454_create_tbl_user_table', 2),
(5, '2025_09_19_105335_create_tasks_table', 3),
(6, '2025_09_19_105359_create_statuses_table', 3),
(7, '2025_09_19_105407_create_priorities_table', 3),
(8, '2025_09_19_105419_create_tags_table', 3),
(9, '2025_09_19_113927_create_comments_table', 4),
(10, '2025_09_19_115223_create_task_records_table', 5),
(11, '2025_09_19_124854_add_replied_to_comments_table', 6),
(12, '2025_09_23_101028_create_projects_table', 7);

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
-- Table structure for table `priorities`
--

CREATE TABLE `priorities` (
  `priority_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `priorities`
--

INSERT INTO `priorities` (`priority_id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'low', NULL, NULL),
(2, 'medium', NULL, NULL),
(3, 'high', NULL, NULL),
(4, 'urgent', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`project_id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Default Project', 'This project contains all existing tasks & for new tasks you can assign the tasks as you want.', '2025-09-23 04:46:38', '2025-09-23 07:18:29'),
(2, 'Viaansh', 'Viaansh Project', '2025-09-23 07:11:15', '2025-09-23 07:11:15'),
(3, 'Ajay Steel', 'end to end Website Development.', '2025-09-23 07:52:05', '2025-09-23 07:52:05');

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
('CPsqo9N25c4cLWsw30ipWGqMMMtc9Go2Tjj6Cb83', 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoibjJtTTNWMkRqUnc4SVVlUjRhU21wQk9HdXVzRWh6RnBod1JNVkM0eCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9kYXNoYm9hcmQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTozO30=', 1758696893);

-- --------------------------------------------------------

--
-- Table structure for table `statuses`
--

CREATE TABLE `statuses` (
  `status_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `statuses`
--

INSERT INTO `statuses` (`status_id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'pending', NULL, NULL),
(2, 'in_progress', NULL, NULL),
(3, 'completed', NULL, NULL),
(4, 'on_hold', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE `tags` (
  `tag_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tags`
--

INSERT INTO `tags` (`tag_id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'meeting', NULL, NULL),
(2, 'bug', NULL, NULL),
(3, 'feature', NULL, NULL),
(4, 'maintenance', NULL, NULL),
(5, 'design', NULL, NULL),
(6, 'testing', NULL, NULL),
(7, 'documentation', NULL, NULL),
(8, 'research', NULL, NULL),
(9, 'deployment', NULL, NULL),
(10, 'support', NULL, NULL),
(11, 'review', NULL, NULL),
(12, 'optimization', NULL, NULL),
(13, 'training', NULL, NULL),
(14, 'planning', NULL, NULL),
(15, 'other', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `task_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `assigned_to` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `status_id` bigint(20) UNSIGNED NOT NULL,
  `priority_id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED DEFAULT NULL,
  `tag_id` bigint(20) UNSIGNED DEFAULT NULL,
  `due_date` datetime DEFAULT NULL,
  `completed_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`task_id`, `title`, `description`, `assigned_to`, `created_by`, `status_id`, `priority_id`, `project_id`, `tag_id`, `due_date`, `completed_at`, `created_at`, `updated_at`) VALUES
(1, 'Symphony Infra.com', 'Require Gathering for the real estate website features.', 5, 3, 4, 1, 1, 14, '2025-09-26 00:00:00', NULL, '2025-09-19 12:20:01', '2025-09-19 14:26:35'),
(2, 'Project Kickoff Meeting', 'Conduct an initial meeting with stakeholders to align on goals, deliverables, and timelines.', 1, 3, 2, 2, 1, 1, '2025-09-24 00:00:00', NULL, '2025-09-19 14:47:03', '2025-09-19 14:47:03'),
(3, 'Fix Login Page Error', 'Resolve the issue where users cannot log in due to invalid session handling.', 6, 3, 2, 3, 1, 2, '2025-09-19 00:00:00', NULL, '2025-09-19 14:47:46', '2025-09-19 14:47:46'),
(4, 'Add Dark Mode Feature', 'Implement a dark mode toggle for better user experience in low-light environments.', 4, 3, 2, 3, 1, 3, '2025-09-21 00:00:00', NULL, '2025-09-19 14:48:31', '2025-09-19 14:48:31'),
(5, 'Server Patch Update', 'Apply the latest security patches to the production servers.', 2, 3, 2, 4, 1, 4, '2025-09-20 00:00:00', NULL, '2025-09-19 14:49:05', '2025-09-19 14:49:05'),
(6, 'Dashboard Redesign', 'Create new wireframes and UI components to improve the dashboard’s usability.', 4, 3, 1, 2, 1, 5, '2025-09-23 00:00:00', NULL, '2025-09-19 14:49:40', '2025-09-19 14:49:40'),
(7, 'Regression Testing Cycle', 'Execute regression test cases to ensure no new bugs were introduced after the last release.', 1, 3, 3, 2, 1, 6, '2025-09-20 00:00:00', NULL, '2025-09-19 14:50:19', '2025-09-19 14:50:19'),
(8, 'Write API Documentation', 'Document all available API endpoints with request/response examples for developers.', 1, 3, 4, 1, 1, 7, '2025-09-28 00:00:00', NULL, '2025-09-19 14:50:50', '2025-09-19 14:50:50'),
(9, 'Competitor Analysis', 'Research competitor products and prepare a comparison report highlighting gaps and opportunities.', 1, 3, 2, 2, 1, 8, '2025-09-24 00:00:00', NULL, '2025-09-19 14:51:33', '2025-09-19 14:51:33'),
(10, 'Deploy Extension Version 2.3', 'Deploy the new build to the staging environment and verify configurations.', 5, 3, 2, 2, 1, 9, '2025-09-27 00:00:00', NULL, '2025-09-19 14:52:35', '2025-09-19 14:52:35'),
(11, 'Customer Support Ticket #4521', 'Assist the customer facing an issue with data export functionality.', 1, 3, 3, 4, 1, 10, '2025-09-20 00:00:00', NULL, '2025-09-19 14:54:13', '2025-09-19 14:54:13'),
(12, 'Performance Analysis', 'Team Analysis', 1, 3, 1, 4, 1, 1, '2025-09-21 00:00:00', NULL, '2025-09-19 23:34:28', '2025-09-19 23:34:28'),
(15, 'Home Page Design', '5 section home page for an IT company', 1, 3, 2, 2, 2, 5, '2025-09-27 00:00:00', NULL, '2025-09-23 07:16:23', '2025-09-23 07:16:23'),
(16, 'Website UI/UX', 'Ajay Steel Website UI/UX.', 4, 3, 2, 2, 3, 5, '2025-09-30 00:00:00', NULL, '2025-09-23 07:53:16', '2025-09-23 07:53:16'),
(17, 'Frontend Development.', '6 Section Website of Ajay Steel.', 6, 3, 2, 2, 3, 14, NULL, NULL, '2025-09-23 07:54:15', '2025-09-23 07:57:05'),
(18, 'Resource Gathering', 'Getting all the info of Ajay Steel.', 1, 3, 2, 3, 3, 1, '2025-09-28 00:00:00', NULL, '2025-09-23 07:55:00', '2025-09-23 07:55:00');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user`
--

CREATE TABLE `tbl_user` (
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') NOT NULL DEFAULT 'user',
  `google_id` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tbl_user`
--

INSERT INTO `tbl_user` (`user_id`, `name`, `email`, `password`, `role`, `google_id`, `created_at`, `updated_at`) VALUES
(1, 'Pandya Dwip', 'aydnapdwip@gmail.com', 'eyJpdiI6InpMQmNidy9IVVE5MGx1K0pZOFAyMVE9PSIsInZhbHVlIjoiRlFBTHJ0aGdhSm85TEJ4VTVnaE1nYWYzcFBnN3J1TDVqc3ZZTXBWMURxTT0iLCJtYWMiOiJlMzhmZDkyYzBiNWQyNzI3YTMyMTVhNWE4YWY1MjZlNzU1ZGE3NTBiNzhjYjJiOGU0MDg5M2MyYWM5MmJiNzk3IiwidGFnIjoiIn0=', 'user', '116898354848865798613', '2025-09-19 12:09:17', '2025-09-19 12:09:17'),
(2, 'Namrata Gohel', 'namrata.gohel@gmail.com', '$2y$12$NBBSEy3h1O4h1TNIlubUmOKEP5EblSQa9AUt.5LftSXEwPAaJQ5uu', 'admin', NULL, '2025-09-19 12:10:04', '2025-09-19 12:10:04'),
(3, 'Trupti Pandya', 'trupti007@gmail.com', '$2y$12$phkqIXjGB/d01Wx0rc7W0ecxfn/1kX.pmKmm3fLhDRoWbBFryP/8e', 'admin', NULL, '2025-09-19 12:12:25', '2025-09-19 12:12:25'),
(4, 'Vaishali Vala', 'vaishali@gmail.com', '$2y$12$dHLEKweSpo9KRZPjR8BQa.RSpzTCiZD6OyjrkaS1JYxCddL/uDHhG', 'user', NULL, '2025-09-19 12:14:44', '2025-09-19 12:15:30'),
(5, 'Hardik Pandya', 'hardik@gmail.com', '$2y$12$NqXo7Bd5O.1Npj7Sod6BAea3Sm.B09fnf1G7cEK4rKdFcQ6pqnd4W', 'user', NULL, '2025-09-19 12:17:52', '2025-09-19 12:17:52'),
(6, 'Sahil Sharma', 'sahilsharma@gmail.com', '$2y$12$I9PIcweivuezxp0qcMID.ePoRw2mgEPfVBGTh9d4zY9/g0Jh44f6a', 'user', NULL, '2025-09-19 14:46:01', '2025-09-19 14:46:01'),
(9, 'Atul', 'atul007@gmail.com', '$2y$12$VGswUr5zrzF2VZN.nXIVg.5h2DDcJ6e.5ORHkF8D3pVNH7bduwheO', 'admin', NULL, '2025-09-20 00:12:27', '2025-09-20 00:12:49');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`comment_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

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
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `priorities`
--
ALTER TABLE `priorities`
  ADD PRIMARY KEY (`priority_id`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`project_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `statuses`
--
ALTER TABLE `statuses`
  ADD PRIMARY KEY (`status_id`);

--
-- Indexes for table `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`tag_id`);

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`task_id`);

--
-- Indexes for table `tbl_user`
--
ALTER TABLE `tbl_user`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `tbl_user_email_unique` (`email`),
  ADD UNIQUE KEY `tbl_user_google_id_unique` (`google_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `comment_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `priorities`
--
ALTER TABLE `priorities`
  MODIFY `priority_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `project_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `statuses`
--
ALTER TABLE `statuses`
  MODIFY `status_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tags`
--
ALTER TABLE `tags`
  MODIFY `tag_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `task_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `tbl_user`
--
ALTER TABLE `tbl_user`
  MODIFY `user_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
