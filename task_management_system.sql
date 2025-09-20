-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 19, 2025 at 10:30 PM
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
(2, 1, 5, 'Ma\'am, i am currently Occupied by another task with high priority, please try to hand-over this task to another developer.', 1, '2025-09-19 13:06:19', '2025-09-19 13:06:19');

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
(11, '2025_09_19_124854_add_replied_to_comments_table', 6);

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
('vzwqZNLMH0RAAkvUt4d36sVH8SOwPzb09efoblSD', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoib3RMdllzbGRoaTV2TGZaNGoxMk1HbnM2ZGNodzliSjVZOGVKeU80SSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzY6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC91c2VyL2Rhc2hib2FyZCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NDoidXNlciI7TzoxNToiQXBwXE1vZGVsc1xVc2VyIjozNTp7czoxMzoiACoAY29ubmVjdGlvbiI7czo1OiJteXNxbCI7czo4OiIAKgB0YWJsZSI7czo4OiJ0YmxfdXNlciI7czoxMzoiACoAcHJpbWFyeUtleSI7czo3OiJ1c2VyX2lkIjtzOjEwOiIAKgBrZXlUeXBlIjtzOjM6ImludCI7czoxMjoiaW5jcmVtZW50aW5nIjtiOjE7czo3OiIAKgB3aXRoIjthOjA6e31zOjEyOiIAKgB3aXRoQ291bnQiO2E6MDp7fXM6MTk6InByZXZlbnRzTGF6eUxvYWRpbmciO2I6MDtzOjEwOiIAKgBwZXJQYWdlIjtpOjE1O3M6NjoiZXhpc3RzIjtiOjE7czoxODoid2FzUmVjZW50bHlDcmVhdGVkIjtiOjA7czoyODoiACoAZXNjYXBlV2hlbkNhc3RpbmdUb1N0cmluZyI7YjowO3M6MTM6IgAqAGF0dHJpYnV0ZXMiO2E6ODp7czo3OiJ1c2VyX2lkIjtpOjE7czo0OiJuYW1lIjtzOjExOiJQYW5keWEgRHdpcCI7czo1OiJlbWFpbCI7czoyMDoiYXlkbmFwZHdpcEBnbWFpbC5jb20iO3M6ODoicGFzc3dvcmQiO3M6MjI4OiJleUpwZGlJNklucE1RbU5pZHk5SVZWRTVNR3gxSzBwWk9GQXlNVkU5UFNJc0luWmhiSFZsSWpvaVJsRkJUSEowYUdkaFNtODVURUo0VlRWbmFFMW5ZV1l6Y0ZCbk4zSjFURFZxYzNaWlRYQldNVVJ4VFQwaUxDSnRZV01pT2lKbE16aG1aRGt5WXpCaU5XUXlOekkzWVRNeU1UVmhOV0U0WVdZMU1qWmxOelUxWkdFM05UQmlOemhqWWpKaU9HVTBNRGc1TTJNeVlXTTVNbUppTnprM0lpd2lkR0ZuSWpvaUluMD0iO3M6NDoicm9sZSI7czo0OiJ1c2VyIjtzOjk6Imdvb2dsZV9pZCI7czoyMToiMTE2ODk4MzU0ODQ4ODY1Nzk4NjEzIjtzOjEwOiJjcmVhdGVkX2F0IjtzOjE5OiIyMDI1LTA5LTE5IDE3OjM5OjE3IjtzOjEwOiJ1cGRhdGVkX2F0IjtzOjE5OiIyMDI1LTA5LTE5IDE3OjM5OjE3Ijt9czoxMToiACoAb3JpZ2luYWwiO2E6ODp7czo3OiJ1c2VyX2lkIjtpOjE7czo0OiJuYW1lIjtzOjExOiJQYW5keWEgRHdpcCI7czo1OiJlbWFpbCI7czoyMDoiYXlkbmFwZHdpcEBnbWFpbC5jb20iO3M6ODoicGFzc3dvcmQiO3M6MjI4OiJleUpwZGlJNklucE1RbU5pZHk5SVZWRTVNR3gxSzBwWk9GQXlNVkU5UFNJc0luWmhiSFZsSWpvaVJsRkJUSEowYUdkaFNtODVURUo0VlRWbmFFMW5ZV1l6Y0ZCbk4zSjFURFZxYzNaWlRYQldNVVJ4VFQwaUxDSnRZV01pT2lKbE16aG1aRGt5WXpCaU5XUXlOekkzWVRNeU1UVmhOV0U0WVdZMU1qWmxOelUxWkdFM05UQmlOemhqWWpKaU9HVTBNRGc1TTJNeVlXTTVNbUppTnprM0lpd2lkR0ZuSWpvaUluMD0iO3M6NDoicm9sZSI7czo0OiJ1c2VyIjtzOjk6Imdvb2dsZV9pZCI7czoyMToiMTE2ODk4MzU0ODQ4ODY1Nzk4NjEzIjtzOjEwOiJjcmVhdGVkX2F0IjtzOjE5OiIyMDI1LTA5LTE5IDE3OjM5OjE3IjtzOjEwOiJ1cGRhdGVkX2F0IjtzOjE5OiIyMDI1LTA5LTE5IDE3OjM5OjE3Ijt9czoxMDoiACoAY2hhbmdlcyI7YTowOnt9czoxMToiACoAcHJldmlvdXMiO2E6MDp7fXM6ODoiACoAY2FzdHMiO2E6MDp7fXM6MTc6IgAqAGNsYXNzQ2FzdENhY2hlIjthOjA6e31zOjIxOiIAKgBhdHRyaWJ1dGVDYXN0Q2FjaGUiO2E6MDp7fXM6MTM6IgAqAGRhdGVGb3JtYXQiO047czoxMDoiACoAYXBwZW5kcyI7YTowOnt9czoxOToiACoAZGlzcGF0Y2hlc0V2ZW50cyI7YTowOnt9czoxNDoiACoAb2JzZXJ2YWJsZXMiO2E6MDp7fXM6MTI6IgAqAHJlbGF0aW9ucyI7YTowOnt9czoxMDoiACoAdG91Y2hlcyI7YTowOnt9czoyNzoiACoAcmVsYXRpb25BdXRvbG9hZENhbGxiYWNrIjtOO3M6MjY6IgAqAHJlbGF0aW9uQXV0b2xvYWRDb250ZXh0IjtOO3M6MTA6InRpbWVzdGFtcHMiO2I6MTtzOjEzOiJ1c2VzVW5pcXVlSWRzIjtiOjA7czo5OiIAKgBoaWRkZW4iO2E6MTp7aTowO3M6ODoicGFzc3dvcmQiO31zOjEwOiIAKgB2aXNpYmxlIjthOjA6e31zOjExOiIAKgBmaWxsYWJsZSI7YTo1OntpOjA7czo0OiJuYW1lIjtpOjE7czo1OiJlbWFpbCI7aToyO3M6ODoicGFzc3dvcmQiO2k6MztzOjQ6InJvbGUiO2k6NDtzOjk6Imdvb2dsZV9pZCI7fXM6MTA6IgAqAGd1YXJkZWQiO2E6MTp7aTowO3M6MToiKiI7fXM6MTk6IgAqAGF1dGhQYXNzd29yZE5hbWUiO3M6ODoicGFzc3dvcmQiO3M6MjA6IgAqAHJlbWVtYmVyVG9rZW5OYW1lIjtzOjE0OiJyZW1lbWJlcl90b2tlbiI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7fQ==', 1758313783);

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
  `tag_id` bigint(20) UNSIGNED DEFAULT NULL,
  `due_date` datetime DEFAULT NULL,
  `completed_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`task_id`, `title`, `description`, `assigned_to`, `created_by`, `status_id`, `priority_id`, `tag_id`, `due_date`, `completed_at`, `created_at`, `updated_at`) VALUES
(1, 'Symphony Infra.com', 'Require Gathering for the real estate website features.', 5, 3, 4, 1, 14, '2025-09-26 00:00:00', NULL, '2025-09-19 12:20:01', '2025-09-19 14:26:35'),
(2, 'Project Kickoff Meeting', 'Conduct an initial meeting with stakeholders to align on goals, deliverables, and timelines.', 1, 3, 2, 2, 1, '2025-09-25 00:00:00', NULL, '2025-09-19 14:47:03', '2025-09-19 14:47:03'),
(3, 'Fix Login Page Error', 'Resolve the issue where users cannot log in due to invalid session handling.', 6, 3, 2, 3, 2, '2025-09-19 00:00:00', NULL, '2025-09-19 14:47:46', '2025-09-19 14:47:46'),
(4, 'Add Dark Mode Feature', 'Implement a dark mode toggle for better user experience in low-light environments.', 4, 3, 2, 3, 3, '2025-09-21 00:00:00', NULL, '2025-09-19 14:48:31', '2025-09-19 14:48:31'),
(5, 'Server Patch Update', 'Apply the latest security patches to the production servers.', 2, 3, 2, 4, 4, '2025-09-20 00:00:00', NULL, '2025-09-19 14:49:05', '2025-09-19 14:49:05'),
(6, 'Dashboard Redesign', 'Create new wireframes and UI components to improve the dashboard’s usability.', 4, 3, 1, 2, 5, '2025-09-23 00:00:00', NULL, '2025-09-19 14:49:40', '2025-09-19 14:49:40'),
(7, 'Regression Testing Cycle', 'Execute regression test cases to ensure no new bugs were introduced after the last release.', 1, 3, 3, 2, 6, '2025-09-20 00:00:00', NULL, '2025-09-19 14:50:19', '2025-09-19 14:50:19'),
(8, 'Write API Documentation', 'Document all available API endpoints with request/response examples for developers.', 1, 3, 4, 1, 7, '2025-09-28 00:00:00', NULL, '2025-09-19 14:50:50', '2025-09-19 14:50:50'),
(9, 'Competitor Analysis', 'Research competitor products and prepare a comparison report highlighting gaps and opportunities.', 1, 3, 2, 2, 8, '2025-09-24 00:00:00', NULL, '2025-09-19 14:51:33', '2025-09-19 14:51:33'),
(10, 'Deploy Extension Version 2.3', 'Deploy the new build to the staging environment and verify configurations.', 5, 3, 2, 2, 9, '2025-09-27 00:00:00', NULL, '2025-09-19 14:52:35', '2025-09-19 14:52:35'),
(11, 'Customer Support Ticket #4521', 'Assist the customer facing an issue with data export functionality.', 1, 3, 3, 4, 10, '2025-09-20 00:00:00', NULL, '2025-09-19 14:54:13', '2025-09-19 14:54:13');

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
(6, 'Sahil Sharma', 'sahilsharma@gmail.com', '$2y$12$I9PIcweivuezxp0qcMID.ePoRw2mgEPfVBGTh9d4zY9/g0Jh44f6a', 'user', NULL, '2025-09-19 14:46:01', '2025-09-19 14:46:01');

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
  MODIFY `comment_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `priorities`
--
ALTER TABLE `priorities`
  MODIFY `priority_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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
  MODIFY `task_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `tbl_user`
--
ALTER TABLE `tbl_user`
  MODIFY `user_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
