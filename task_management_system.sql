-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 07, 2025 at 10:44 AM
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
(1, 1, 3, 'want this to be done be E.O.D', NULL, '2025-10-07 01:33:23', '2025-10-07 01:33:23'),
(2, 1, 3, 'ok', 1, '2025-10-07 01:33:30', '2025-10-07 01:33:30');

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
(1, '0001_01_01_000001_create_cache_table', 1),
(2, '0001_01_01_000002_create_jobs_table', 1),
(3, '2025_01_01_000001_create_roles_table', 1),
(4, '2025_01_01_000002_create_users_table', 1),
(5, '2025_01_01_000100_create_tbl_user_table', 1),
(6, '2025_01_01_000200_create_statuses_table', 1),
(7, '2025_01_01_000300_create_priorities_table', 1),
(8, '2025_01_01_000400_create_tags_table', 1),
(9, '2025_01_01_000500_create_projects_table', 1),
(10, '2025_01_01_000600_create_tasks_table', 1),
(11, '2025_01_01_000700_create_comments_table', 1),
(13, '2025_10_07_055840_create_sessions_table', 2);

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
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`project_id`, `created_by`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 1, 'Default Project', 'This project contains all existing tasks.', '2025-10-07 01:19:16', '2025-10-07 01:19:16'),
(2, 1, 'Website Redesign', 'Revamp the company website for modern UI/UX and improved performance.\r\ncolor pallette has been shared over mail.', '2025-10-07 06:51:09', '2025-10-07 01:36:38'),
(3, 1, 'Mobile App Development', 'Develop a cross-platform mobile app for customer engagement.', '2025-10-07 06:51:09', '2025-10-07 06:51:09'),
(4, 1, 'Inventory Management System', 'Create a system to track stock, suppliers, and orders efficiently.', '2025-10-07 06:51:09', '2025-10-07 06:51:09'),
(5, 1, 'Marketing Campaign Analysis', 'Analyze past marketing campaigns to improve ROI.', '2025-10-07 06:51:09', '2025-10-07 06:51:09'),
(6, 1, 'Customer Feedback Portal', 'Build a portal for collecting and managing customer feedback.', '2025-10-07 06:51:09', '2025-10-07 06:51:09'),
(7, 3, 'PHP-Fresher interview', '- 6 months of experience\r\n- 2 projects (E-com & HMS)\r\n- 2 internships', '2025-10-07 03:08:35', '2025-10-07 03:08:35');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'admin', NULL, NULL),
(2, 'user', NULL, NULL),
(3, 'project member', NULL, NULL),
(4, 'project manager', NULL, NULL);

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
('8YcqmjxIBe2to4siAVdrrkRH6Pq8t2pylY3VFkIJ', 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiQUhkTzV6YjdFbE5hZkFNQmVxTFo2aGxWdUNwSnJ5UFBxdmZhdGxhNiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NTA6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9wcm9qZWN0cz9jcmVhdG9yX3JvbGU9Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6Mzt9', 1759826425);

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
  `project_id` bigint(20) UNSIGNED DEFAULT NULL,
  `due_date` datetime DEFAULT NULL,
  `completed_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`task_id`, `title`, `description`, `assigned_to`, `created_by`, `status_id`, `priority_id`, `tag_id`, `project_id`, `due_date`, `completed_at`, `created_at`, `updated_at`) VALUES
(1, 'Website Wireframe', 'Create wireframes for the new website layout.\r\nand add remarks about the changes.', 2, 4, 3, 4, 5, 1, '2025-10-08 00:00:00', NULL, '2025-10-07 06:51:29', '2025-10-07 02:20:18'),
(2, 'Bug Fix: Login Issue', 'Resolve login failure for certain users.', 5, 2, 2, 3, 2, 2, '2025-10-10 12:00:00', NULL, '2025-10-07 06:51:29', '2025-10-07 06:51:29'),
(3, 'Mobile App MVP', 'Develop minimum viable product for mobile app.', 6, 4, 1, 4, 3, 2, '2025-11-01 18:00:00', NULL, '2025-10-07 06:51:29', '2025-10-07 06:51:29'),
(4, 'Database Optimization', 'Optimize queries and indexes for faster reports.', 7, 2, 1, 3, 12, 3, '2025-10-20 16:00:00', NULL, '2025-10-07 06:51:29', '2025-10-07 06:51:29'),
(5, 'Customer Feedback Form', 'Design and implement feedback collection form.', 8, 4, 1, 2, 7, 5, '2025-10-18 14:00:00', NULL, '2025-10-07 06:51:29', '2025-10-07 06:51:29'),
(6, 'Marketing Analysis', 'Analyze recent marketing campaigns.', 9, 4, 1, 2, 14, 4, '2025-10-25 15:00:00', NULL, '2025-10-07 06:51:57', '2025-10-07 06:51:57'),
(7, 'Server Maintenance', 'Perform routine server maintenance and updates.', 2, 4, 1, 3, 4, 3, '2025-10-12 10:00:00', NULL, '2025-10-07 06:51:57', '2025-10-07 06:51:57'),
(8, 'New Feature: Chat', 'Implement chat feature in mobile app.', 6, 2, 1, 4, 3, 2, '2025-11-05 12:00:00', NULL, '2025-10-07 06:51:57', '2025-10-07 06:51:57'),
(9, 'Bug Fix: Payment Gateway', 'Fix payment gateway timeout errors.', 5, 4, 2, 4, 2, 1, '2025-10-14 18:00:00', NULL, '2025-10-07 06:51:57', '2025-10-07 06:51:57'),
(10, 'UI Update', 'Update dashboard UI for better usability.', 7, 2, 1, 2, 5, 1, '2025-10-20 14:00:00', NULL, '2025-10-07 06:51:57', '2025-10-07 06:51:57'),
(11, 'User Documentation', 'Prepare user documentation for new software release.', 8, 4, 1, 2, 7, 5, '2025-10-28 16:00:00', NULL, '2025-10-07 06:51:57', '2025-10-07 06:51:57'),
(12, 'Testing Payment Module', 'Test all functionalities of the payment module.', 6, 2, 2, 3, 6, 2, '2025-10-19 11:00:00', NULL, '2025-10-07 06:51:57', '2025-10-07 06:51:57'),
(13, 'Deployment to Staging', 'Deploy latest build to staging environment.', 9, 4, 1, 4, 9, 3, '2025-10-17 09:00:00', NULL, '2025-10-07 06:51:57', '2025-10-07 06:51:57'),
(14, 'Team Training', 'Conduct training session for new team members.', 2, 2, 1, 2, 13, 4, '2025-10-22 10:30:00', NULL, '2025-10-07 06:51:57', '2025-10-07 06:51:57'),
(15, 'Bug Fix: Profile Update', 'Fix issue with user profile update.', 5, 4, 2, 3, 2, 1, '2025-10-16 14:00:00', NULL, '2025-10-07 06:51:57', '2025-10-07 06:51:57'),
(16, 'SEO Optimization', 'Improve SEO for company website.', 7, 2, 1, 3, 12, 1, '2025-10-21 17:00:00', NULL, '2025-10-07 06:51:57', '2025-10-07 06:51:57'),
(17, 'Feature: Export Reports', 'Add export functionality to reports section.', 6, 4, 1, 3, 3, 3, '2025-10-29 15:00:00', NULL, '2025-10-07 06:51:57', '2025-10-07 06:51:57'),
(18, 'Client Meeting Preparation', 'Prepare slides and documents for client meeting.', 2, 4, 1, 2, 1, 4, '2025-10-15 09:00:00', NULL, '2025-10-07 06:51:57', '2025-10-07 06:51:57'),
(19, 'Code Review', 'Review latest pull requests and provide feedback.', 8, 2, 1, 2, 11, 2, '2025-10-18 13:00:00', NULL, '2025-10-07 06:51:57', '2025-10-07 06:51:57'),
(20, 'Database Backup', 'Take full database backup before release.', 9, 4, 1, 3, 4, 3, '2025-10-12 20:00:00', NULL, '2025-10-07 06:51:57', '2025-10-07 06:51:57'),
(21, 'Bug Fix: Notifications', 'Resolve notification delivery errors.', 5, 2, 2, 3, 2, 1, '2025-10-14 16:00:00', NULL, '2025-10-07 06:51:57', '2025-10-07 06:51:57'),
(22, 'Research: New Technologies', 'Research emerging technologies for product enhancement.', 6, 4, 1, 2, 8, 3, '2025-10-30 11:00:00', NULL, '2025-10-07 06:51:57', '2025-10-07 06:51:57'),
(23, 'Feature: Dark Mode', 'Implement dark mode in web and mobile app.', 7, 2, 1, 3, 3, 2, '2025-11-03 18:00:00', NULL, '2025-10-07 06:51:57', '2025-10-07 06:51:57'),
(24, 'Support Ticket Handling', 'Handle high-priority support tickets.', 2, 4, 2, 4, 10, 5, '2025-10-17 12:00:00', NULL, '2025-10-07 06:51:57', '2025-10-07 06:51:57'),
(25, 'Maintenance: Server Cleanup', 'Remove old logs and optimize server storage.', 9, 2, 1, 3, 4, 3, '2025-10-23 09:30:00', NULL, '2025-10-07 06:51:57', '2025-10-07 06:51:57'),
(26, 'Review of the Dashboard wire-frame', 'review the wire-frame', 2, 3, 3, 1, 11, 2, '2025-10-07 00:00:00', NULL, '2025-10-07 01:35:05', '2025-10-07 01:35:05');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user`
--

CREATE TABLE `tbl_user` (
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL DEFAULT 2,
  `google_id` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `role_id` bigint(20) UNSIGNED NOT NULL DEFAULT 2,
  `google_id` varchar(255) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role_id`, `google_id`, `remember_token`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'admin', 'admin@gmail.com', NULL, '$2y$12$4GJPcpxuMotwr70cz7T8OuBJsNDcTMjNBjV1yaHsOyiG2b2R0S95.', 1, NULL, NULL, '2025-10-07 01:19:16', '2025-10-07 01:19:16', NULL),
(2, 'Pandya Dwip', 'aydnapdwip@gmail.com', NULL, '$2y$12$lK2HKAnE2sHFb/T2WL4S7uAwqCGAyyou9W235q211ZKOzgpr/7EfG', 2, '116898354848865798613', NULL, '2025-10-06 19:04:51', '2025-10-06 19:21:33', NULL),
(3, 'Trupti Pandya', 'trupti007@gmail.com', NULL, '$2y$12$z4V2h1QDnfl5StkJiQAma.9xvfQlL03mz3NxCLwLBtnZjln143cou', 1, NULL, NULL, '2025-10-06 19:09:19', '2025-10-06 19:09:19', NULL),
(4, 'vrushabh Gajjar', 'vg@gmail.com', NULL, '$2y$12$cSJ4pLnGfxhD2nJ9z14Xx.g1ojSe5ALo67tfHoNkKfR1Pml/.ay/W', 4, NULL, NULL, '2025-10-06 19:22:23', '2025-10-06 19:25:54', NULL),
(5, 'Vaishali Vala', 'vaishali@gmail.com', NULL, '$2y$12$lnB39lemTQ7pQTuOU8dSs.Oq7Awpo/c73c7mxfY47mwSc0/JhupXW', 2, NULL, NULL, '2025-10-06 19:39:04', '2025-10-06 19:39:04', NULL),
(6, 'Sahil Sharma', 'sahilsharma@gmail.com', NULL, '$2y$12$gPja0GYQhPIgaCPAUWRGE.KfuXKyROs7bMIRdrxLONOjmdikjUdkW', 3, NULL, NULL, '2025-10-06 19:39:40', '2025-10-06 19:39:40', NULL),
(7, 'Aruhi Patel', 'aruhi@gmail.com', NULL, '$2y$12$d8h17XaOkHrzyoegE8RAPOB4rat7cg8DB/8VwgghfgqresrMiKXWm', 2, NULL, NULL, '2025-10-06 19:40:04', '2025-10-06 19:40:04', NULL),
(8, 'Vishwa pandya', 'vish@gmail.com', NULL, '$2y$12$GML.0bpSl7qO/URkfwD0xORqyPzdtSC9i23x01yukIoMySgiujBfC', 2, NULL, NULL, '2025-10-06 19:40:33', '2025-10-06 19:40:33', NULL),
(9, 'Namrata Gohel', 'namrata.gohel@gmail.com', NULL, '$2y$12$G.w0u.BwNt8/12Ez2osqleHwq2PpkNngdxdXZK78bSTDQJ9RnOtO.', 4, NULL, NULL, '2025-10-06 19:40:47', '2025-10-06 19:40:47', NULL),
(10, 'Prachi Anarkat', 'prachiank@gmail.com', NULL, '$2y$12$zxt/t4BNGJepubu6SsKlneN7EXrANgXfKtQDPj87nVKCX3ZO9TXgS', 4, NULL, NULL, '2025-10-06 19:41:05', '2025-10-06 19:41:05', NULL),
(11, 'Prathmesh Pandya', 'prathmesh.pandya@gmail.com', NULL, '$2y$12$HP6Ss6z7TDitdP.5MeVwKuauKFIDz9VXuXdi2sE4eHR9TwRXMlow.', 2, NULL, NULL, '2025-10-06 19:41:20', '2025-10-06 19:41:20', NULL);

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
  ADD PRIMARY KEY (`comment_id`),
  ADD KEY `comments_task_id_foreign` (`task_id`),
  ADD KEY `comments_user_id_foreign` (`user_id`);

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
  ADD PRIMARY KEY (`task_id`),
  ADD KEY `tasks_assigned_to_foreign` (`assigned_to`),
  ADD KEY `tasks_created_by_foreign` (`created_by`),
  ADD KEY `tasks_status_id_foreign` (`status_id`),
  ADD KEY `tasks_priority_id_foreign` (`priority_id`),
  ADD KEY `tasks_tag_id_foreign` (`tag_id`),
  ADD KEY `tasks_project_id_foreign` (`project_id`);

--
-- Indexes for table `tbl_user`
--
ALTER TABLE `tbl_user`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `tbl_user_email_unique` (`email`),
  ADD UNIQUE KEY `tbl_user_google_id_unique` (`google_id`),
  ADD KEY `tbl_user_role_id_foreign` (`role_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_google_id_unique` (`google_id`),
  ADD KEY `users_role_id_foreign` (`role_id`);

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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `priorities`
--
ALTER TABLE `priorities`
  MODIFY `priority_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `project_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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
  MODIFY `task_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `tbl_user`
--
ALTER TABLE `tbl_user`
  MODIFY `user_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tasks`
--
ALTER TABLE `tasks`
  ADD CONSTRAINT `tasks_assigned_to_foreign` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `tasks_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tasks_priority_id_foreign` FOREIGN KEY (`priority_id`) REFERENCES `priorities` (`priority_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tasks_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`project_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `tasks_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `statuses` (`status_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tasks_tag_id_foreign` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`tag_id`) ON DELETE SET NULL;

--
-- Constraints for table `tbl_user`
--
ALTER TABLE `tbl_user`
  ADD CONSTRAINT `tbl_user_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
