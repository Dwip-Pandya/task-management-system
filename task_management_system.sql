-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 16, 2025 at 01:49 PM
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
(1, 3, 6, 'This will be done on weekend.', NULL, '2025-10-10 00:25:03', '2025-10-10 00:25:03'),
(2, 3, 3, 'Can\'t wait till the weekend ! complete it before the weekend.', 1, '2025-10-10 01:15:45', '2025-10-10 01:15:45'),
(3, 22, 6, 'This will be done on weekend.', NULL, '2025-10-13 06:04:45', '2025-10-13 06:04:45'),
(4, 30, 2, 'Results : \r\n1. Basic Crud implementation\r\n2. Code structure is not appropriate\r\n3. DB Schema Design is good.', NULL, '2025-10-14 00:38:41', '2025-10-14 00:38:41'),
(5, 3, 6, 'Task Completed.', NULL, '2025-10-14 00:55:39', '2025-10-14 00:55:39');

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
(14, '2025_10_07_055840_create_sessions_table', 2),
(15, '2025_10_07_081603_create_notifications_table', 3),
(16, '2025_10_01_000800_create_notifications_table', 4);

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
(1, 1, 'TaskFlow', 'A smart task management system for teams.', '2025-10-16 11:21:18', '2025-10-16 11:21:18'),
(2, 4, 'MediTrack', 'A hospital management and record tracking system.', '2025-10-16 11:21:18', '2025-10-16 11:21:18'),
(3, 1, 'FocusFlow', 'A productivity tool for micro-goal tracking and time focus.', '2025-10-16 11:21:18', '2025-10-16 11:21:18'),
(4, 4, 'WriterVault', 'A content management system for writers and creators.', '2025-10-16 11:21:18', '2025-10-16 11:21:18'),
(5, 1, 'EventHub', 'An event booking and seat management platform.', '2025-10-16 11:21:18', '2025-10-16 11:21:18'),
(6, 4, 'EduTrack', 'An online learning progress tracker for students and mentors.', '2025-10-16 11:34:46', '2025-10-16 11:34:46'),
(7, 1, 'ShopEase', 'A lightweight e-commerce management system for small businesses.', '2025-10-16 11:34:46', '2025-10-16 11:34:46'),
(8, 4, 'CodeSync', 'A collaborative code versioning and feedback platform.', '2025-10-16 11:34:46', '2025-10-16 11:34:46'),
(9, 1, 'FitPulse', 'A fitness activity tracker with daily logs and goals.', '2025-10-16 11:34:46', '2025-10-16 11:34:46'),
(10, 4, 'ServeMate', 'A customer support and ticket management system.', '2025-10-16 11:34:46', '2025-10-16 11:34:46');

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
('aGLkV3DeadF8S8RXDlzoX4m3zJxvbB8Gf9k9syue', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoiM3QwZ0xTVUZaT1J6R1Rpa1dPR21ZdElIUDhRME9ZdVVobHJ4dHRVViI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi91c2VycyI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7czo3OiJ1c2VyX2lkIjtpOjE7czoxNDoiaXNfZGVhY3RpdmF0ZWQiO2I6MDt9', 1760615356),
('ZjnBeseVSYlTFEDuah0HroSamhQBvxHpLleuKreE', 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoiYlRmeUY1MnhaeGhKZVlGd25md1pZcHN0bGZxdDJzWERzYlE1UWdqUiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDI6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9jaGFydHMvbW9udGhseSI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjM7czo3OiJ1c2VyX2lkIjtpOjM7czoxNDoiaXNfZGVhY3RpdmF0ZWQiO2I6MDt9', 1760612839);

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
(15, 'other', NULL, NULL),
(16, 'meeting', NULL, NULL),
(17, 'bug', NULL, NULL),
(18, 'feature', NULL, NULL),
(19, 'maintenance', NULL, NULL),
(20, 'design', NULL, NULL),
(21, 'testing', NULL, NULL),
(22, 'documentation', NULL, NULL),
(23, 'research', NULL, NULL),
(24, 'deployment', NULL, NULL),
(25, 'support', NULL, NULL),
(26, 'review', NULL, NULL),
(27, 'optimization', NULL, NULL),
(28, 'training', NULL, NULL),
(29, 'planning', NULL, NULL),
(30, 'development', NULL, NULL),
(31, 'support', NULL, NULL),
(32, 'other', NULL, NULL);

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
(1, 'Setup Project Structure', 'Initialize Laravel structure and configure base modules for TaskFlow.', 3, 1, 1, 2, 15, 1, '2025-10-25 00:00:00', NULL, '2025-10-16 11:30:09', '2025-10-16 11:30:09'),
(2, 'Implement Role System', 'Develop role-based access for Admin, Manager, and User.', 6, 1, 2, 3, 15, 1, '2025-10-28 00:00:00', NULL, '2025-10-16 11:30:09', '2025-10-16 11:30:09'),
(3, 'Fix Task Deletion Bug', 'Resolve issue causing incorrect cascade delete in task module.', 8, 1, 1, 4, 2, 1, '2025-10-30 00:00:00', NULL, '2025-10-16 11:30:09', '2025-10-16 11:30:09'),
(4, 'Patient Record Module', 'Create CRUD for patient profiles with medical history.', 7, 4, 2, 3, 15, 2, '2025-10-24 00:00:00', NULL, '2025-10-16 11:30:09', '2025-10-16 11:30:09'),
(5, 'Database Optimization', 'Optimize MySQL queries for faster patient search results.', 4, 4, 1, 4, 12, 2, '2025-10-27 00:00:00', NULL, '2025-10-16 11:30:09', '2025-10-16 11:30:09'),
(6, 'Doctor Meeting Setup', 'Plan a meeting with doctors to gather requirements for appointment module.', 3, 4, 1, 2, 1, 2, '2025-10-22 00:00:00', NULL, '2025-10-16 11:30:09', '2025-10-16 11:30:09'),
(7, 'Pomodoro Timer Integration', 'Integrate frontend timer with session tracking backend.', 6, 1, 2, 3, 15, 3, '2025-10-26 00:00:00', NULL, '2025-10-16 11:30:09', '2025-10-16 11:30:09'),
(8, 'Add Goal Notes', 'Allow users to add personal notes to their micro-goals.', 7, 1, 1, 2, 3, 3, '2025-10-23 00:00:00', NULL, '2025-10-16 11:30:09', '2025-10-16 11:30:09'),
(9, 'Performance Testing', 'Run tests to ensure timers sync correctly with goal completion data.', 8, 1, 1, 3, 6, 3, '2025-10-30 00:00:00', NULL, '2025-10-16 11:30:09', '2025-10-16 11:30:09'),
(10, 'Design Dashboard UI', 'Create Bootstrap-based dashboard layout for project overview.', 3, 4, 2, 2, 5, 4, '2025-10-21 00:00:00', NULL, '2025-10-16 11:30:09', '2025-10-16 11:30:09'),
(11, 'Add Tagging Feature', 'Implement tag-based categorization for notes and projects.', 6, 4, 1, 3, 15, 4, '2025-10-25 00:00:00', NULL, '2025-10-16 11:30:09', '2025-10-16 11:30:09'),
(12, 'Fix Image Upload Error', 'Resolve file size validation bug during image uploads.', 7, 4, 1, 4, 2, 4, '2025-10-28 00:00:00', NULL, '2025-10-16 11:30:09', '2025-10-16 11:30:09'),
(13, 'Seat Mapping System', 'Develop dynamic seat layout for events using JavaScript.', 8, 1, 2, 3, 15, 5, '2025-10-29 00:00:00', NULL, '2025-10-16 11:30:09', '2025-10-16 11:30:09'),
(14, 'Payment Gateway Integration', 'Integrate Razorpay for secure ticket payments.', 4, 1, 1, 4, 9, 5, '2025-10-30 00:00:00', NULL, '2025-10-16 11:30:09', '2025-10-16 11:30:09'),
(15, 'Client Review Meeting', 'Schedule review meeting with client to finalize event modules.', 3, 1, 1, 2, 1, 5, '2025-10-23 00:00:00', NULL, '2025-10-16 11:30:09', '2025-10-16 11:30:09'),
(16, 'Setup Course Module', 'Create course listing and progress tracking structure.', 3, 4, 2, 3, 15, 6, '2025-10-24 00:00:00', NULL, '2025-10-16 11:36:34', '2025-10-16 11:36:34'),
(17, 'Add Mentor Dashboard', 'Develop mentor view for student reports and analytics.', 6, 4, 1, 3, 5, 6, '2025-10-27 00:00:00', NULL, '2025-10-16 11:36:34', '2025-10-16 11:36:34'),
(18, 'Fix Progress Update Bug', 'Resolve delayed sync issue in student progress API.', 8, 4, 2, 2, 2, 6, '2025-10-26 00:00:00', NULL, '2025-10-16 11:36:34', '2025-10-16 11:36:34'),
(19, 'Prepare Documentation', 'Write initial API documentation for course endpoints.', 7, 4, 1, 1, 7, 6, '2025-10-29 00:00:00', NULL, '2025-10-16 11:36:34', '2025-10-16 11:36:34'),
(20, 'Add Product Filters', 'Implement category and price filters in product listing page.', 4, 1, 2, 2, 15, 7, '2025-10-23 00:00:00', NULL, '2025-10-16 11:36:34', '2025-10-16 11:36:34'),
(21, 'Bug Fix - Checkout', 'Fix issue with cart total mismatch in checkout summary.', 6, 1, 1, 4, 2, 7, '2025-10-24 00:00:00', NULL, '2025-10-16 11:36:34', '2025-10-16 11:36:34'),
(22, 'Repository Module Setup', 'Build base module for project and branch creation.', 3, 4, 2, 3, 15, 8, '2025-10-25 00:00:00', NULL, '2025-10-16 11:36:34', '2025-10-16 11:36:34'),
(23, 'Add Code Review Feature', 'Implement inline commenting and approval workflow.', 8, 4, 1, 4, 11, 8, '2025-10-28 00:00:00', NULL, '2025-10-16 11:36:34', '2025-10-16 11:36:34'),
(24, 'UI Update', 'Redesign project list page for better user navigation.', 7, 4, 1, 2, 5, 8, '2025-10-30 00:00:00', NULL, '2025-10-16 11:36:34', '2025-10-16 11:36:34'),
(25, 'Deploy Staging Server', 'Deploy initial version of CodeSync to staging environment.', 6, 4, 2, 3, 9, 8, '2025-10-31 00:00:00', NULL, '2025-10-16 11:36:34', '2025-10-16 11:36:34'),
(26, 'Optimize Diff Loading', 'Improve performance of code diff rendering on large files.', 4, 4, 1, 4, 12, 8, '2025-10-27 00:00:00', NULL, '2025-10-16 11:36:34', '2025-10-16 11:36:34'),
(27, 'Activity Tracker Module', 'Create backend logic for tracking daily steps and calories.', 8, 1, 2, 3, 15, 9, '2025-10-25 00:00:00', NULL, '2025-10-16 11:36:34', '2025-10-16 11:36:34'),
(28, 'UI Design for Dashboard', 'Design summary screen with activity stats and goals.', 7, 1, 1, 2, 5, 9, '2025-10-26 00:00:00', NULL, '2025-10-16 11:36:34', '2025-10-16 11:36:34'),
(29, 'Add Notification Alerts', 'Implement daily reminder notifications for users.', 3, 1, 1, 3, 3, 9, '2025-10-29 00:00:00', NULL, '2025-10-16 11:36:34', '2025-10-16 11:36:34'),
(30, 'Setup Ticket Module', 'Develop base structure for customer issue tracking and responses.', 6, 4, 2, 3, 15, 10, '2025-10-24 00:00:00', NULL, '2025-10-16 11:36:34', '2025-10-16 11:36:34'),
(31, 'Code Cleanup', 'Refactor controllers and remove unused imports.', 3, 1, 3, 1, 12, 1, '2025-10-10 00:00:00', '2025-10-09 00:00:00', '2025-10-16 11:39:05', '2025-10-16 11:39:05'),
(32, 'Add Help Page', 'Create a help page explaining basic navigation and features.', 7, 4, 3, 1, 7, 4, '2025-10-08 00:00:00', '2025-10-07 00:00:00', '2025-10-16 11:39:05', '2025-10-16 11:39:05'),
(33, 'Grammar Correction', 'Proofread text content in dashboard and reports.', 8, 4, 3, 1, 7, 6, '2025-10-11 00:00:00', '2025-10-11 00:00:00', '2025-10-16 11:39:05', '2025-10-16 11:39:05'),
(34, 'Design Update', 'Tweak product card layout and spacing for better readability.', 4, 1, 3, 1, 5, 7, '2025-10-12 00:00:00', '2025-10-12 00:00:00', '2025-10-16 11:39:05', '2025-10-16 11:39:05'),
(35, 'Update Logo', 'Replace placeholder logo with final approved design.', 6, 1, 3, 1, 5, 9, '2025-10-09 00:00:00', '2025-10-09 00:00:00', '2025-10-16 11:39:05', '2025-10-16 11:39:05'),
(36, 'Update User Permissions', 'Adjust role access for new user type in TaskFlow.', 3, 1, 3, 2, 15, 1, '2025-10-10 00:00:00', '2025-10-09 00:00:00', '2025-10-16 11:47:10', '2025-10-16 11:47:10'),
(37, 'Fix Appointment Overlap', 'Prevent overlapping appointments in MediTrack scheduling.', 4, 4, 3, 3, 2, 2, '2025-10-11 00:00:00', '2025-10-10 00:00:00', '2025-10-16 11:47:10', '2025-10-16 11:47:10'),
(38, 'Goal History Feature', 'Implement logging of user goal updates in FocusFlow.', 9, 1, 3, 2, 3, 3, '2025-10-12 00:00:00', '2025-10-12 00:00:00', '2025-10-16 11:47:10', '2025-10-16 11:47:10'),
(39, 'Search Optimization', 'Improve keyword search performance in WriterVault.', 3, 4, 3, 3, 12, 4, '2025-10-13 00:00:00', '2025-10-12 00:00:00', '2025-10-16 11:47:10', '2025-10-16 11:47:10'),
(40, 'Seat Auto Allocation', 'Implement automatic seat allocation during booking.', 9, 1, 3, 3, 15, 5, '2025-10-14 00:00:00', '2025-10-14 00:00:00', '2025-10-16 11:47:10', '2025-10-16 11:47:10'),
(41, 'Student Analytics', 'Add performance chart for mentors in EduTrack dashboard.', 4, 4, 3, 2, 8, 6, '2025-10-13 00:00:00', '2025-10-13 00:00:00', '2025-10-16 11:47:10', '2025-10-16 11:47:10'),
(42, 'Cart Coupon Logic', 'Add discount coupon logic in ShopEase checkout.', 3, 1, 3, 2, 15, 7, '2025-10-14 00:00:00', '2025-10-13 00:00:00', '2025-10-16 11:47:10', '2025-10-16 11:47:10'),
(43, 'Branch Comparison Tool', 'Add diff comparison tool for branches in CodeSync.', 9, 4, 3, 3, 15, 8, '2025-10-11 00:00:00', '2025-10-11 00:00:00', '2025-10-16 11:47:10', '2025-10-16 11:47:10'),
(44, 'Steps Data Sync', 'Fix delayed syncing issue with Fitbit API in FitPulse.', 4, 1, 3, 3, 2, 9, '2025-10-10 00:00:00', '2025-10-09 00:00:00', '2025-10-16 11:47:10', '2025-10-16 11:47:10'),
(45, 'Ticket Priority Sorting', 'Allow users to sort tickets by urgency in ServeMate.', 3, 4, 3, 3, 15, 10, '2025-10-15 00:00:00', '2025-10-15 00:00:00', '2025-10-16 11:47:10', '2025-10-16 11:47:10'),
(46, 'Course Reminder Emails', 'Add automated email reminders for course deadlines.', 9, 4, 3, 2, 9, 6, '2025-10-14 00:00:00', '2025-10-14 00:00:00', '2025-10-16 11:47:10', '2025-10-16 11:47:10'),
(47, 'UI Bug Fixes', 'Fix minor layout issues across responsive breakpoints.', 4, 1, 3, 2, 5, 7, '2025-10-12 00:00:00', '2025-10-11 00:00:00', '2025-10-16 11:47:10', '2025-10-16 11:47:10'),
(48, 'Team Analytics Dashboard', 'Planned analytics screen pending design approval.', 3, 1, 4, 2, 5, 1, '2025-10-20 00:00:00', NULL, '2025-10-16 11:47:10', '2025-10-16 11:47:10'),
(49, 'Multi-Language Support', 'Localization and translation setup paused for next sprint.', 9, 4, 4, 3, 15, 8, '2025-10-22 00:00:00', NULL, '2025-10-16 11:47:10', '2025-10-16 11:47:10'),
(50, 'Push Notification System', 'Awaiting backend updates for push message integration.', 4, 1, 4, 3, 3, 9, '2025-10-25 00:00:00', NULL, '2025-10-16 11:47:10', '2025-10-16 11:47:10');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role_id`, `google_id`, `remember_token`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Trupti Pandya', 'trupti007@gmail.com', NULL, '$2y$12$UqfDX5L0m.jGKJfGW5b0.OulK2srvjQCddcJYC/efjIQNRyN2QFcS', 1, NULL, NULL, '2025-10-16 05:42:37', '2025-10-16 05:42:37', NULL),
(2, 'vrushabh Gajjar', 'vg@gmail.com', NULL, '$2y$12$AsYeJmeFTV4v3Fx3TLeDH.rf6rPR9tjPUfyA3WFwPr1xmO.7jYfvq', 4, NULL, NULL, '2025-10-16 05:44:03', '2025-10-16 05:44:03', NULL),
(3, 'Sahil Sharma', 'sahilsharma@gmail.com', NULL, '$2y$12$29vB72qhvW7FVrXwJ4HL/OZjxfma2QL1dFz54hjU0hh6k9oj6k/0u', 3, NULL, NULL, '2025-10-16 05:44:21', '2025-10-16 05:44:21', NULL),
(4, 'Pandya Dwip', 'aydnapdwip@gmail.com', NULL, '$2y$12$coE1U1on3Yvi3kql9fWdwueG/qpPzK5i/OBLqL6aga.xwL3yvczzq', 2, '116898354848865798613', NULL, '2025-10-16 05:44:38', '2025-10-16 05:44:38', NULL),
(5, 'Namrata Gohel', 'namrata.gohel@gmail.com', NULL, '$2y$12$5REUcR5KfB1brHPnFxr6B.3b0Fd4O/05DLmdE37J0u.ikUvp2Tdai', 4, NULL, NULL, '2025-10-16 05:45:18', '2025-10-16 05:45:18', NULL),
(6, 'Ananya Shah', 'ananyashah@gmail.com', NULL, '$2y$12$GGYuaB8ctsPv4NUIFSXl6OjqJkUlheVsAkEq6xNfp/i1NeFQCLdXO', 3, NULL, NULL, '2025-10-16 05:45:36', '2025-10-16 05:45:36', NULL),
(7, 'Vaishali Vala', 'vaishali@gmail.com', NULL, '$2y$12$3TK53zvAQ86W7/A//Wyreuyqo8Z14KzzLhg5WMSsqHkMBwewYJSpu', 2, NULL, NULL, '2025-10-16 05:46:43', '2025-10-16 05:46:43', NULL),
(8, 'Prachi Anarkat', 'prachiank@gmail.com', NULL, '$2y$12$5T4GaCrBtUokD2zvYO/EF..dy29KuXc60/8UHw3JawJJst.dE/c8S', 3, NULL, NULL, '2025-10-16 05:47:10', '2025-10-16 05:47:10', NULL),
(9, 'Tishika Vachhani', 'tishika@gmail.com', NULL, '$2y$12$XnUsXKNp2hO0mFVOWaqbWut6qPHCm8JJPHE5QEOQk/M4ELjFRuilC', 2, NULL, NULL, '2025-10-16 06:09:57', '2025-10-16 06:19:15', NULL);

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
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `google_id` (`google_id`),
  ADD KEY `users_role_id_foreign` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `comment_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `priorities`
--
ALTER TABLE `priorities`
  MODIFY `priority_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `project_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `statuses`
--
ALTER TABLE `statuses`
  MODIFY `status_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tags`
--
ALTER TABLE `tags`
  MODIFY `tag_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `task_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

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
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
