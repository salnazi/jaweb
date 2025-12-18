-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 18, 2025 at 05:17 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `jasquare_app`
--

-- --------------------------------------------------------

--
-- Table structure for table `codes`
--

CREATE TABLE `codes` (
  `id` int(11) NOT NULL,
  `code_name` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `price` float DEFAULT 0,
  `uploaded_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `devices`
--

CREATE TABLE `devices` (
  `id` int(11) NOT NULL,
  `device_name` varchar(50) NOT NULL,
  `device_key` varchar(50) NOT NULL,
  `state` enum('ON','OFF') DEFAULT 'OFF',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `devices`
--

INSERT INTO `devices` (`id`, `device_name`, `device_key`, `state`, `updated_at`) VALUES
(1, 'Light', 'light1', 'OFF', '2025-12-07 08:34:15'),
(2, 'Fan', 'fan1', 'OFF', '2025-12-07 08:34:14');

-- --------------------------------------------------------

--
-- Table structure for table `device_logs`
--

CREATE TABLE `device_logs` (
  `id` int(11) NOT NULL,
  `device` varchar(50) NOT NULL,
  `on_time` datetime DEFAULT NULL,
  `off_time` datetime DEFAULT NULL,
  `duration` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `device_status`
--

CREATE TABLE `device_status` (
  `id` int(11) NOT NULL,
  `device` varchar(50) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `family_expenses`
--

CREATE TABLE `family_expenses` (
  `id` int(11) NOT NULL,
  `date` date NOT NULL,
  `category` varchar(50) NOT NULL,
  `description` varchar(255) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `added_on` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `family_expenses`
--

INSERT INTO `family_expenses` (`id`, `date`, `category`, `description`, `amount`, `added_on`) VALUES
(34, '2025-12-07', 'Food', 'Apple', 200.00, '2025-12-07 14:32:25'),
(35, '2025-12-07', 'Utilities', 'EB Bill', 3920.00, '2025-12-07 14:33:31'),
(45, '2025-12-07', 'Food', 'Apple one', 400.00, '2025-12-07 14:53:51'),
(46, '2025-12-07', 'Transport', 'Podakkudi', 50.00, '2025-12-07 14:58:25'),
(47, '2025-12-07', 'Entertainment', 'Movie', 300.00, '2025-12-07 14:58:51'),
(49, '2025-12-07', 'Food', 'Apple', 300.00, '2025-12-07 15:00:47');

-- --------------------------------------------------------

--
-- Table structure for table `fan_light_devices`
--

CREATE TABLE `fan_light_devices` (
  `id` int(11) NOT NULL,
  `device_name` varchar(100) DEFAULT NULL,
  `device_type` varchar(20) DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `fan_light_devices`
--

INSERT INTO `fan_light_devices` (`id`, `device_name`, `device_type`, `status`) VALUES
(1, 'Living Room Light', 'light', 1),
(2, 'Bedroom Light', 'light', 0),
(3, 'Main Ceiling Fan', 'fan', 1),
(4, 'Kids Room Fan', 'fan', 0),
(5, 'Kitchen Light', 'light', 1),
(6, 'Hall Fan', 'fan', 1),
(7, 'Bathroom Light', 'light', 0),
(8, 'Balcony Light', 'light', 1),
(9, 'Study Room Fan', 'fan', 0),
(10, 'Garden Light', 'light', 1);

-- --------------------------------------------------------

--
-- Table structure for table `fan_light_schedule`
--

CREATE TABLE `fan_light_schedule` (
  `id` int(11) NOT NULL,
  `device` varchar(50) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `on_time` datetime DEFAULT NULL,
  `off_time` datetime DEFAULT NULL,
  `hours_used` varchar(8) DEFAULT '00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `fan_light_schedule`
--

INSERT INTO `fan_light_schedule` (`id`, `device`, `status`, `on_time`, `off_time`, `hours_used`) VALUES
(1, 'Fan', 1, '2025-12-07 19:10:37', NULL, '00:00:00'),
(2, 'Light', 1, '2025-12-08 14:44:34', '2025-12-07 19:11:06', '00:00:12'),
(3, 'AC', 0, '2025-12-08 14:44:41', '2025-12-08 14:44:48', '00:00:27'),
(4, 'Heater', 0, NULL, NULL, '00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `inventory_items`
--

CREATE TABLE `inventory_items` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `category` varchar(50) DEFAULT NULL,
  `quantity` int(11) DEFAULT 0,
  `stock_in_date` date DEFAULT NULL,
  `stock_out_date` date DEFAULT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inventory_items`
--

INSERT INTO `inventory_items` (`id`, `name`, `category`, `quantity`, `stock_in_date`, `stock_out_date`, `description`) VALUES
(1, 'Keyboard', 'iTech', 2, '2025-12-08', '2025-12-08', 'Desktop Keyboard');

-- --------------------------------------------------------

--
-- Table structure for table `inventory_management_system`
--

CREATE TABLE `inventory_management_system` (
  `id` int(11) NOT NULL,
  `item_name` varchar(200) NOT NULL,
  `sku` varchar(100) DEFAULT '',
  `category` varchar(100) DEFAULT '',
  `quantity` int(11) DEFAULT 0,
  `unit_price` decimal(12,2) DEFAULT 0.00,
  `low_stock_level` int(11) DEFAULT 0,
  `last_stock_in` datetime DEFAULT NULL,
  `last_stock_out` datetime DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `added_on` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `inventory_management_system`
--

INSERT INTO `inventory_management_system` (`id`, `item_name`, `sku`, `category`, `quantity`, `unit_price`, `low_stock_level`, `last_stock_in`, `last_stock_out`, `notes`, `added_on`) VALUES
(1, 'AA Batteries (Pack 4)', 'BAT-4', 'Electronics', 120, 130.00, 10, NULL, NULL, '', '2025-12-08 04:07:03'),
(2, 'USB-C Cable 1m', 'USB-C-1M', 'Accessories', 80, 199.00, 15, NULL, NULL, NULL, '2025-12-08 04:07:03'),
(3, 'Wireless Mouse', 'WM-100', 'Peripherals', 25, 799.00, 5, NULL, NULL, NULL, '2025-12-08 04:07:03'),
(4, 'Office Chair', 'CHAIR-01', 'Furniture', 8, 4500.00, 2, NULL, NULL, NULL, '2025-12-08 04:07:03'),
(5, 'Notebook A4 (100 pages)', 'NB-A4', 'Stationery', 200, 45.00, 20, NULL, NULL, NULL, '2025-12-08 04:07:03');

-- --------------------------------------------------------

--
-- Table structure for table `inventory_management_system_movements`
--

CREATE TABLE `inventory_management_system_movements` (
  `id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `type` enum('IN','OUT') NOT NULL,
  `quantity` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `reference` varchar(255) DEFAULT NULL,
  `note` text DEFAULT NULL,
  `created_on` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jacms_categories`
--

CREATE TABLE `jacms_categories` (
  `category_id` int(11) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jacms_categories`
--

INSERT INTO `jacms_categories` (`category_id`, `name`, `slug`) VALUES
(1, 'PHP/MySQL', 'php-mysql'),
(2, 'JavaScript (AJAX/UI)', 'javascript'),
(3, 'FinTech Industry', 'fintech'),
(4, 'E-Commerce Platform', 'ecommerce'),
(5, 'API Integration', 'api-integration'),
(6, 'Mobile App', 'mobile-app');

-- --------------------------------------------------------

--
-- Table structure for table `jacms_media`
--

CREATE TABLE `jacms_media` (
  `media_id` int(10) UNSIGNED NOT NULL,
  `project_id` int(10) UNSIGNED NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `file_type` varchar(50) NOT NULL,
  `is_hero_image` tinyint(1) NOT NULL DEFAULT 0,
  `sort_order` int(5) NOT NULL DEFAULT 0,
  `date_uploaded` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jacms_media`
--

INSERT INTO `jacms_media` (`media_id`, `project_id`, `file_path`, `file_type`, `is_hero_image`, `sort_order`, `date_uploaded`) VALUES
(1, 6, 'assets/uploads/proj_693fac85d3974.jpg', 'image/jpeg', 1, 0, '2025-12-15 01:06:53'),
(2, 5, 'uploads/project_images/5_hero.jpg', 'image', 1, 0, '2025-12-15 10:44:20');

-- --------------------------------------------------------

--
-- Table structure for table `jacms_menus`
--

CREATE TABLE `jacms_menus` (
  `link_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `link_type` enum('internal','external') NOT NULL DEFAULT 'internal',
  `target` enum('_self','_blank') NOT NULL DEFAULT '_self',
  `url` varchar(512) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `menu_position` int(11) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jacms_menus`
--

INSERT INTO `jacms_menus` (`link_id`, `title`, `link_type`, `target`, `url`, `slug`, `menu_position`, `is_active`) VALUES
(1, 'Home', 'internal', '_self', '/index.php', 'index', 1, 1),
(2, 'About Us', 'internal', '_self', '/about-us.php', 'about-us', 2, 1),
(6, 'Google', 'external', '_self', 'http://google.com', 'google', 3, 1),
(7, 'Services', 'internal', '_self', '/services.php', 'services', 4, 1),
(8, 'Web Design', 'internal', '_self', 'web-design.php', 'web-design', 5, 1),
(10, 'Rocket', 'internal', '_self', '/rocket.php', 'rocket', 7, 1);

-- --------------------------------------------------------

--
-- Table structure for table `jacms_menu_items_delete`
--

CREATE TABLE `jacms_menu_items_delete` (
  `item_id` int(11) UNSIGNED NOT NULL,
  `menu_id` int(11) DEFAULT 1,
  `title` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `link_type` varchar(10) NOT NULL DEFAULT 'internal',
  `target` varchar(10) NOT NULL DEFAULT '_self',
  `order_index` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jacms_menu_items_delete`
--

INSERT INTO `jacms_menu_items_delete` (`item_id`, `menu_id`, `title`, `url`, `link_type`, `target`, `order_index`) VALUES
(1, 1, 'Home', 'index.php', 'internal', '_self', 1),
(2, 1, 'About Us', 'http://localhost/jacms/about-us,php', 'external', '_self', 2),
(3, 1, 'Services', 'services.php', 'internal', '_self', 3),
(5, 1, 'Contact Us', 'contact-us.php', 'internal', '_self', 5),
(8, 1, 'Test', 'http://localhost/jacms/test.php', 'external', '_self', 8);

-- --------------------------------------------------------

--
-- Table structure for table `jacms_menu_links_delete`
--

CREATE TABLE `jacms_menu_links_delete` (
  `link_id` int(11) UNSIGNED NOT NULL,
  `link_text` varchar(100) NOT NULL,
  `link_target` varchar(255) NOT NULL COMMENT 'The slug (for internal) or URL (for external)',
  `link_type` enum('slug','url') NOT NULL DEFAULT 'slug',
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jacms_menu_links_delete`
--

INSERT INTO `jacms_menu_links_delete` (`link_id`, `link_text`, `link_target`, `link_type`, `sort_order`, `is_active`) VALUES
(1, 'Home', 'index', 'slug', 10, 1),
(2, 'About Us', 'about-us', 'slug', 20, 1),
(3, 'All Projects', 'projects', 'slug', 30, 1),
(4, 'Contact', 'contact', 'slug', 40, 1);

-- --------------------------------------------------------

--
-- Table structure for table `jacms_pages`
--

CREATE TABLE `jacms_pages` (
  `page_id` int(11) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `url` varchar(255) DEFAULT NULL,
  `slug` varchar(255) NOT NULL,
  `menu_link_id` int(11) DEFAULT NULL,
  `content_markdown` longtext NOT NULL,
  `meta_description` varchar(500) DEFAULT NULL,
  `status` enum('draft','live') NOT NULL DEFAULT 'draft',
  `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
  `date_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jacms_pages`
--

INSERT INTO `jacms_pages` (`page_id`, `title`, `url`, `slug`, `menu_link_id`, `content_markdown`, `meta_description`, `status`, `date_created`, `date_updated`) VALUES
(1, 'JACMS Homepage', '/index.php', 'index', 1, '## Our Mission\r\n\r\nWelcome to the official homepage of the JACMS. This content is dynamically fetched from the database using the `page.php` controller file. \r\n\r\n### Key Features\r\n\r\n* **Prepared Statements:** Database queries are secure against SQL Injection.\r\n* **Markdown Support:** Easily format your content using Markdown.\r\n* **Dynamic Slugs:** Single file (`page.php`) handles all static pages.\r\n\r\n***\r\n\r\nFeel free to modify this content in the admin area once it is built!', 'The official homepage of the JACMS project, a simple yet robust PHP and MySQL-based Content Management System.', 'live', '2025-12-15 11:06:27', '2025-12-16 05:40:31'),
(2, 'About Our Development Journey', '/about-us', 'about-us', 2, '## The Story Behind JACMS\r\n\r\nJACMS was built as a demonstration of modern, secure, and maintainable PHP development practices.\r\n\r\n### Core Technologies Used\r\n\r\n1.  **PHP 8+**\r\n2.  **MySQL/MariaDB** (using Prepared Statements)\r\n3.  **Bootstrap 5** (for responsive design)\r\n4.  **PHP Markdown Parser** (Required for rendering this content)\r\n\r\nWe focus on clear separation of concerns, strong error handling (using transactions in the admin), and secure data access.\r\n\r\n### Team\r\n\r\n* John Doe (Developer Lead)\r\n* Jane Smith (Frontend Designer)', 'Learn more about the core technologies and secure development practices used in the JACMS project.', 'live', '2025-12-15 11:06:27', '2025-12-15 23:00:46'),
(4, 'Contact Us Form', 'https://google.com', 'google', 6, '## The Story Behind JACMS\r\n\r\nJACMS was built as a demonstration of modern, secure, and maintainable PHP development practices.\r\n\r\n### Core Technologies Used\r\n\r\n1.  **PHP 8+**\r\n2.  **MySQL/MariaDB** (using Prepared Statements)\r\n3.  **Bootstrap 5** (for responsive design)\r\n4.  **PHP Markdown Parser** (Required for rendering this content)\r\n\r\nWe focus on clear separation of concerns, strong error handling (using transactions in the admin), and secure data access.\r\n\r\n### Team\r\n\r\n* John Doe (Developer Lead)\r\n* Jane Smith (Frontend Designer)', '', 'live', '2025-12-15 13:19:31', '2025-12-15 23:52:52'),
(7, 'Services', '/services.php', 'services', 7, 'Services', 'services', 'live', '2025-12-16 00:00:08', '2025-12-16 05:40:58'),
(8, 'Web Design Company', '/web-design.php', 'web-design', 8, 'Web Design', 'web design, web hosting', 'live', '2025-12-16 02:17:59', '2025-12-16 02:38:59'),
(9, 'Tester Page', '/tester.php', 'tester', NULL, 'testeer', 'tester', 'live', '2025-12-16 05:52:36', '2025-12-16 05:52:36'),
(10, 'Rocket', '/rocket.php', 'rocket', NULL, 'Rocket', '', 'live', '2025-12-16 06:13:25', '2025-12-16 06:13:25');

-- --------------------------------------------------------

--
-- Table structure for table `jacms_pages_builder`
--

CREATE TABLE `jacms_pages_builder` (
  `id` int(11) NOT NULL,
  `page_name` varchar(255) NOT NULL,
  `content_json` longtext NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jacms_pages_builder`
--

INSERT INTO `jacms_pages_builder` (`id`, `page_name`, `content_json`, `created_at`, `updated_at`) VALUES
(2, 'Homr', '[{\"columns\":[{\"col\":\"col-md-6\",\"blocks\":[{\"type\":\"text\",\"html\":\"Edit text\",\"style\":\"\"}]},{\"col\":\"col-md-6\",\"blocks\":[{\"type\":\"text\",\"html\":\"\\n                <img src=\\\"https://picsum.photos/600/300\\\" class=\\\"img-fluid\\\" draggable=\\\"false\\\">\\n            \",\"style\":\"\"}]}]},{\"columns\":[{\"col\":\"col-md-6\",\"blocks\":[{\"type\":\"text\",\"html\":\"Edit text\",\"style\":\"\"}]},{\"col\":\"col-md-6\",\"blocks\":[{\"type\":\"text\",\"html\":\"\\n                <img src=\\\"https://picsum.photos/600/300\\\" class=\\\"img-fluid\\\" draggable=\\\"false\\\">\\n            \",\"style\":\"\"}]}]},{\"columns\":[{\"col\":\"col-md-6\",\"blocks\":[{\"type\":\"text\",\"html\":\"\\n                <img src=\\\"https://picsum.photos/600/300\\\" class=\\\"img-fluid\\\">\\n            \",\"style\":\"\"}]},{\"col\":\"col-md-6\",\"blocks\":[{\"type\":\"text\",\"html\":\"\\n                <button class=\\\"btn btn-primary\\\">Button</button>\\n            \",\"style\":\"\"}]}]},{\"columns\":[{\"col\":\"col-md-6\",\"blocks\":[]},{\"col\":\"col-md-6\",\"blocks\":[]}]}]', '2025-12-16 20:35:55', '2025-12-16 20:37:00');

-- --------------------------------------------------------

--
-- Table structure for table `jacms_projects`
--

CREATE TABLE `jacms_projects` (
  `project_id` int(11) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `client_name` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `results_markdown` text DEFAULT NULL,
  `tech_stack` varchar(255) DEFAULT NULL,
  `status` enum('live','draft','archived') NOT NULL DEFAULT 'draft',
  `date_added` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jacms_projects`
--

INSERT INTO `jacms_projects` (`project_id`, `title`, `client_name`, `description`, `results_markdown`, `tech_stack`, `status`, `date_added`, `last_updated`) VALUES
(1, 'Financial Dashboard', 'Apex Credit', 'Internal financial risk tool.', '45% efficiency gain.', 'PHP, AJAX', 'live', '2025-12-14 23:56:19', '2025-12-14 23:56:19'),
(3, 'Subscription Portal', 'Digital Media', 'Client billing management.', '10% retention improvement.', 'JavaScript, Stripe', 'live', '2025-12-14 23:56:21', '2025-12-14 23:56:21'),
(4, 'Draft Landing Page1', 'Startup Idea', 'Simple page concept.', 'Awaiting client approval.', 'HTML, CSS', 'draft', '2025-12-14 23:56:22', '2025-12-15 10:20:15'),
(5, 'Mobile Order Apps22', 'Local Cafe', 'Order app prototype.', 'Great initial feedback.', 'PHP, Mobile CSS', 'live', '2025-12-14 23:56:23', '2025-12-15 10:44:19'),
(6, 'Hotelbooking API', 'Agoda', 'Hotelbooking API Agoda', 'Hotelbooking API Agoda', 'Hotelbooking API Agoda', 'live', '2025-12-15 00:58:04', '2025-12-15 00:58:04');

-- --------------------------------------------------------

--
-- Table structure for table `jacms_project_categories`
--

CREATE TABLE `jacms_project_categories` (
  `project_id` int(11) UNSIGNED NOT NULL,
  `category_id` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jacms_project_categories`
--

INSERT INTO `jacms_project_categories` (`project_id`, `category_id`) VALUES
(1, 1),
(1, 2),
(1, 3),
(3, 1),
(3, 2),
(3, 3),
(4, 2),
(5, 1),
(5, 2),
(5, 3),
(5, 4),
(5, 5),
(5, 6),
(6, 5);

-- --------------------------------------------------------

--
-- Table structure for table `jacms_settings`
--

CREATE TABLE `jacms_settings` (
  `setting_key` varchar(50) NOT NULL,
  `setting_value` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jacms_settings`
--

INSERT INTO `jacms_settings` (`setting_key`, `setting_value`) VALUES
('about_text', 'Welcome to JA Square Web Solutions'),
('admin_email', 'salnazi@gmail.com'),
('footer_text', 'A Square Web Solutions. 2025 All Rights Reserved.'),
('header_text', 'Welcome to JA Square Web Solutions'),
('meta_description', 'Project for Sale, Web Design, Web Development, Mobile native app'),
('meta_keywords', 'Project for Sale, Web Design, Web Development, Mobile native app'),
('site_tagline', 'Crafting bespoke digital experiences.'),
('url_routing_method', 'query_string'),
('website_title', 'JA Square Web');

-- --------------------------------------------------------

--
-- Table structure for table `jacms_users`
--

CREATE TABLE `jacms_users` (
  `user_id` int(11) UNSIGNED NOT NULL,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `role` enum('admin','editor') NOT NULL DEFAULT 'editor',
  `date_created` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jacms_users`
--

INSERT INTO `jacms_users` (`user_id`, `username`, `password_hash`, `email`, `role`, `date_created`) VALUES
(1, 'admin', '$2y$10$Kb4v1YVl3Pf4Z4ID5w9yheeFUBnfJgeaOBwgrnNIPp6TLElkY/EHC', 'admin@example.com', 'admin', '2025-12-14 23:56:16'),
(2, 'salnazi', '$2y$10$6r0iYw0YZ23AeopyEBhvsOB9GLsZztS4ldBPBL3O8dhWscw41uwi.', 'salnazi84@gmail.com', 'editor', '2025-12-15 03:35:32');

-- --------------------------------------------------------

--
-- Table structure for table `jasquare_devices`
--

CREATE TABLE `jasquare_devices` (
  `id` int(11) NOT NULL,
  `device_name` varchar(50) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jasquare_devices`
--

INSERT INTO `jasquare_devices` (`id`, `device_name`, `status`, `updated_at`) VALUES
(1, 'fan', 0, '2025-12-07 14:02:29'),
(2, 'light', 0, '2025-12-07 14:02:21');

-- --------------------------------------------------------

--
-- Table structure for table `jaweb_activity_log`
--

CREATE TABLE `jaweb_activity_log` (
  `id` int(11) NOT NULL,
  `user` varchar(100) NOT NULL DEFAULT 'Admin',
  `action_type` enum('ADD','UPDATE','DELETE','SYSTEM') NOT NULL,
  `module` varchar(100) DEFAULT NULL COMMENT 'The file name: e.g. categories.php',
  `action` text NOT NULL COMMENT 'The description of the change',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'Current Timestamp',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'Last Updated Timestamp'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jaweb_activity_log`
--

INSERT INTO `jaweb_activity_log` (`id`, `user`, `action_type`, `module`, `action`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'UPDATE', 'settings.php', 'System logs cleared by admin', '2025-12-17 19:55:47', '2025-12-17 19:55:47'),
(2, 'admin', 'UPDATE', 'settings.php', 'Database optimization executed', '2025-12-17 19:56:19', '2025-12-17 19:56:19');

-- --------------------------------------------------------

--
-- Table structure for table `jaweb_categories`
--

CREATE TABLE `jaweb_categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jaweb_categories`
--

INSERT INTO `jaweb_categories` (`id`, `name`, `slug`) VALUES
(1, 'Web Development', 'web-development'),
(2, 'Graphic Design', 'graphic-design'),
(3, 'Digital Marketing', 'digital-marketing'),
(4, 'SEO Optimization', 'seo-optimization'),
(5, 'Mobile App Dev', 'mobile-app-dev'),
(6, 'UI/UX Design', 'ui-ux-design'),
(7, 'Content Writing', 'content-writing'),
(8, 'Branding & Identity', 'branding-identity'),
(9, 'Social Media Management', 'social-media-management'),
(10, 'E-commerce Solutionss', 'e-commerce-solutionsss');

-- --------------------------------------------------------

--
-- Table structure for table `jaweb_contact_leads`
--

CREATE TABLE `jaweb_contact_leads` (
  `lead_id` int(11) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `message` text NOT NULL,
  `lead_source` varchar(50) DEFAULT NULL,
  `sent_to_admin` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jaweb_contact_messages`
--

CREATE TABLE `jaweb_contact_messages` (
  `message_id` int(11) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `status` enum('new','read','archived') NOT NULL DEFAULT 'new',
  `received_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jaweb_leads`
--

CREATE TABLE `jaweb_leads` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `service` varchar(50) DEFAULT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jaweb_menus`
--

CREATE TABLE `jaweb_menus` (
  `link_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `link_type` varchar(50) DEFAULT 'custom',
  `target` varchar(20) DEFAULT '_self',
  `url` text NOT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `menu_position` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jaweb_menus`
--

INSERT INTO `jaweb_menus` (`link_id`, `title`, `link_type`, `target`, `url`, `slug`, `menu_position`, `is_active`) VALUES
(1, 'Home', 'custom', '_self', 'index.php', '', 1, 0),
(2, 'Portfolio', 'custom', '_self', 'portfolio.php', NULL, 2, 1),
(3, 'test', 'custom', '_self', 'tee', '', 3, 1);

-- --------------------------------------------------------

--
-- Table structure for table `jaweb_pages`
--

CREATE TABLE `jaweb_pages` (
  `page_id` int(11) UNSIGNED NOT NULL,
  `page_key` varchar(50) NOT NULL COMMENT 'e.g., index, about-us, contact-us',
  `content_key` varchar(50) NOT NULL COMMENT 'e.g., hero_title, mission_statement',
  `content_value` text DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jaweb_pages`
--

INSERT INTO `jaweb_pages` (`page_id`, `page_key`, `content_key`, `content_value`, `updated_at`) VALUES
(1, 'index', 'hero_title', 'We build lightning-fast, scalable web solutions tailored for your business success.', '2025-12-12 08:20:52'),
(3, 'about-us', 'mission_statement', 'We aim to empower businesses of all sizes with robust, scalable, and beautifully designed online presences, focusing on performance and security.', '2025-12-12 04:35:29'),
(4, 'web-design', 'main_title', 'Cutting-Edge, Data-Driven Web Design', '2025-12-12 04:35:29'),
(5, 'web-hosting', 'main_title', 'Secure, Blazing-Fast, and Affordable Web Hosting', '2025-12-12 04:35:29'),
(6, 'contact-us', 'hero_title', 'Ready to Start Your Project? Get in Touch.', '2025-12-12 04:35:29'),
(0, 'index', 'hero_title', 'Launch Your Digital Future Today', '2025-12-17 04:17:10'),
(0, 'index', 'hero_subtitle', 'We build lightning-fast, scalable web solutions tailored for your business success.', '2025-12-17 04:17:10'),
(0, 'about-us', 'mission_statement', 'We aim to empower businesses of all sizes with robust, scalable, and beautifully designed online presences, focusing on performance and security.', '2025-12-17 04:17:10'),
(0, 'web-design', 'main_title', 'Cutting-Edge, Data-Driven Web Design', '2025-12-17 04:17:10'),
(0, 'web-hosting', 'main_title', 'Secure, Blazing-Fast, and Affordable Web Hosting', '2025-12-17 04:17:10'),
(0, 'contact-us', 'hero_title', 'Ready to Start Your Project? Get in Touch.', '2025-12-17 04:17:10');

-- --------------------------------------------------------

--
-- Table structure for table `jaweb_page_content`
--

CREATE TABLE `jaweb_page_content` (
  `content_id` int(11) UNSIGNED NOT NULL,
  `page_key` varchar(50) NOT NULL,
  `content_key` varchar(100) NOT NULL,
  `content_value` text DEFAULT NULL,
  `last_updated_by` varchar(50) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jaweb_portfolio`
--

CREATE TABLE `jaweb_portfolio` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `category` varchar(100) DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `target` varchar(20) DEFAULT '_blank',
  `price` varchar(50) DEFAULT NULL,
  `seo_tag` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jaweb_portfolio`
--

INSERT INTO `jaweb_portfolio` (`id`, `title`, `category`, `image_url`, `description`, `url`, `target`, `price`, `seo_tag`, `created_at`) VALUES
(1, 'POS Systems', 'Graphic Design', 'img/portfolio/1765981634_6942bdc2c3552.jpg', 'Restaurant POS System at low cost.', 'google.com', '_self', '20', 'pos, web application', '2025-12-17 19:57:14'),
(2, 'Login Systems', '', 'img/portfolio/1765993786_6942ed3a7540f.jpg', 'logins', 'logins.com', '_blank', '100', 'login, registrations', '2025-12-17 20:01:39'),
(13, 'Biometric Systems2', 'Branding & Identity', 'img/portfolio/1766000584_694307c854f34.jpg', 'biometric, web, applications2', 'https://google.coms2', '_blank', '32', 'biometric, web, application2', '2025-12-17 23:20:31');

-- --------------------------------------------------------

--
-- Table structure for table `jaweb_site_settings`
--

CREATE TABLE `jaweb_site_settings` (
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jaweb_site_settings`
--

INSERT INTO `jaweb_site_settings` (`setting_key`, `setting_value`) VALUES
('company_address', 'Chennai, India.'),
('company_email', 'salnazi@gmail.com'),
('company_name', 'JA Square '),
('company_phone', '+91 84283 57459');

-- --------------------------------------------------------

--
-- Table structure for table `jaweb_system_logs`
--

CREATE TABLE `jaweb_system_logs` (
  `id` int(11) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `rules_no` varchar(100) NOT NULL,
  `rules_details` text NOT NULL,
  `change_log` text NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jaweb_system_logs`
--

INSERT INTO `jaweb_system_logs` (`id`, `filename`, `rules_no`, `rules_details`, `change_log`, `updated_at`) VALUES
(1, 'categories.php', '1-17', 'Category CRUD', 'Deleted ID: 4', '2025-12-17 08:43:26'),
(2, 'categories.php', '1-17', 'Category CRUD', 'Deleted ID: 5', '2025-12-17 08:43:32');

-- --------------------------------------------------------

--
-- Table structure for table `jaweb_users`
--

CREATE TABLE `jaweb_users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','editor') NOT NULL DEFAULT 'editor',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jaweb_users`
--

INSERT INTO `jaweb_users` (`id`, `username`, `password`, `role`, `created_at`) VALUES
(2, 'admin', '$2y$10$gOtCJqd7wVVcKBFEnrgVauoPcIrQpMccA5i.EK6PdzXp2OHEhl3aa', 'admin', '2025-12-17 05:36:28'),
(3, 'salnazi', '$2y$10$Pv9nRfmLSKW75Lr.N7e1Sup.ojNz9/fUOaMSqqTO53ua8WNBemcwK', 'admin', '2025-12-17 16:13:08');

-- --------------------------------------------------------

--
-- Table structure for table `module_downloads`
--

CREATE TABLE `module_downloads` (
  `id` int(11) NOT NULL,
  `module_name` varchar(255) NOT NULL,
  `user_ip` varchar(50) NOT NULL,
  `user_agent` varchar(255) NOT NULL,
  `download_time` datetime NOT NULL DEFAULT current_timestamp(),
  `payment_status` enum('pending','completed') NOT NULL DEFAULT 'pending',
  `transaction_id` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `module_downloads`
--

INSERT INTO `module_downloads` (`id`, `module_name`, `user_ip`, `user_agent`, `download_time`, `payment_status`, `transaction_id`) VALUES
(1, 'family_expense.php', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36 OPR/124.0.0.0', '2025-12-08 15:19:04', 'completed', 'txn_69369f1023e67'),
(2, 'fan_light_control.php', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36 OPR/124.0.0.0', '2025-12-08 15:44:35', 'completed', 'txn_6936a50bb745a'),
(3, 'fan_light_control.php', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36 OPR/124.0.0.0', '2025-12-08 15:45:20', 'completed', 'txn_6936a53815a00'),
(4, 'fan_light_control.php', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36 OPR/124.0.0.0', '2025-12-08 15:49:45', 'completed', 'txn_6936a641a3653'),
(5, 'gpay_qr_payment.php', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36 OPR/124.0.0.0', '2025-12-08 15:56:39', 'completed', 'txn_6936a7df23930'),
(6, 'fan_light_control.php', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36 OPR/124.0.0.0', '2025-12-08 15:57:22', 'completed', 'txn_6936a80a5ae94'),
(7, 'family_expense.php', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36 OPR/124.0.0.0', '2025-12-08 15:58:08', 'completed', 'txn_6936a838ab901'),
(8, 'family_expense.php', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36 OPR/124.0.0.0', '2025-12-08 15:59:26', 'completed', 'txn_6936a886dad32'),
(9, 'fan_light_control.php', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36 OPR/124.0.0.0', '2025-12-09 11:33:01', 'completed', 'txn_6937bb9521fe6'),
(10, 'fan_light_control.php', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36 OPR/124.0.0.0', '2025-12-09 11:36:09', 'pending', 'txn_6937bc51445a6'),
(11, 'fan_light_control.php', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36 OPR/124.0.0.0', '2025-12-09 11:36:34', 'pending', 'txn_6937bc6a9f31e'),
(12, 'fan_light_control.php', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36 OPR/124.0.0.0', '2025-12-09 11:36:51', 'pending', 'txn_6937bc7bdeb76'),
(13, 'fan_light_control.php', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36 OPR/124.0.0.0', '2025-12-09 11:42:34', 'pending', 'txn_6937bdd20d9f6'),
(14, 'inventory_management_system.php', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36 OPR/124.0.0.0', '2025-12-09 11:45:05', 'pending', 'txn_6937be69d3b9d'),
(15, 'ja_cash.php', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36 OPR/124.0.0.0', '2025-12-09 11:47:13', 'pending', 'txn_6937bee91cf3c'),
(16, 'login_registration.php', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36 OPR/124.0.0.0', '2025-12-09 11:48:04', 'pending', 'txn_6937bf1cd6f72'),
(17, 'gpay_qr_payment.php', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36 OPR/124.0.0.0', '2025-12-09 11:53:23', 'completed', 'txn_6937c05be3aea'),
(18, 'mobile_number_tracking.php', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36 OPR/124.0.0.0', '2025-12-09 11:55:23', 'completed', 'txn_6937c0d3ca8bf'),
(19, 'timetable.php', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36 OPR/124.0.0.0', '2025-12-09 12:08:01', 'completed', 'txn_6937c3c98d91b'),
(20, 'timetable.php', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36 OPR/124.0.0.0', '2025-12-09 12:08:43', 'completed', 'txn_6937c3f387080'),
(21, 'school_id_card_print.php', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36 OPR/124.0.0.0', '2025-12-09 12:09:51', 'pending', 'txn_6937c437ab477'),
(22, 'school_id_card_print.php', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36 OPR/124.0.0.0', '2025-12-09 12:12:21', 'pending', 'txn_6937c4cd273ef'),
(23, 'school_id_card_print.php', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36 OPR/124.0.0.0', '2025-12-09 12:12:58', 'pending', 'txn_6937c4f288baa'),
(24, 'school_id_card_print.php', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36 OPR/124.0.0.0', '2025-12-09 12:13:08', 'pending', 'txn_6937c4fcb3bbb'),
(25, 'school_id_card_print.php', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36 OPR/124.0.0.0', '2025-12-09 12:14:01', 'pending', 'txn_6937c53198179'),
(26, 'fan_light_control.php', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36 OPR/124.0.0.0', '2025-12-09 12:49:49', 'pending', 'txn_6937cd95f3376'),
(27, 'family_expense.php', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36 OPR/124.0.0.0', '2025-12-17 09:04:08', 'completed', 'txn_694224b0441de'),
(28, 'gpay_qr_payment.php', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36 OPR/124.0.0.0', '2025-12-18 09:44:04', 'pending', 'txn_69437f8cedebc');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `transaction_id` varchar(50) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `status` enum('Pending','Paid') NOT NULL DEFAULT 'Pending',
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `transaction_id`, `amount`, `status`, `created_at`) VALUES
(1, 'TXN1765092422866', 10.00, 'Paid', '2025-12-07 12:57:02'),
(2, 'TXN1765092458689', 10.00, 'Pending', '2025-12-07 12:57:38'),
(3, 'TXN1765092462851', 10.00, 'Paid', '2025-12-07 12:57:42'),
(4, 'TXN1765092466853', 10.00, 'Pending', '2025-12-07 12:57:46'),
(5, 'TXN1765092467831', 10.00, 'Paid', '2025-12-07 12:57:47'),
(6, 'TXN1765092468966', 10.00, 'Paid', '2025-12-07 12:57:48'),
(7, 'TXN1765092939640', 10.00, 'Paid', '2025-12-07 13:05:39'),
(8, 'TXN1765092948134', 10.00, 'Paid', '2025-12-07 13:05:48'),
(9, 'TXN1765092948493', 10.00, 'Paid', '2025-12-07 13:05:48'),
(10, 'TXN1765092949878', 10.00, 'Paid', '2025-12-07 13:05:49'),
(11, 'TXN1765092949843', 10.00, 'Paid', '2025-12-07 13:05:49');

-- --------------------------------------------------------

--
-- Table structure for table `pos_products`
--

CREATE TABLE `pos_products` (
  `id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock_quantity` int(11) NOT NULL DEFAULT 0,
  `image_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pos_products`
--

INSERT INTO `pos_products` (`id`, `product_name`, `price`, `stock_quantity`, `image_path`, `created_at`) VALUES
(3, 'Juice', 40.00, 95, 'uploads/products/prod_69391b7a487212.05574260.jpg', '2025-12-10 01:34:26'),
(4, 'Veg Rice', 80.00, 200, 'uploads/products/prod_69391b8e184a59.65994430.jpg', '2025-12-10 01:34:46'),
(5, 'Chicken Briyani', 150.00, 999, 'uploads/products/prod_69391ba1acb2f8.84050151.jpg', '2025-12-10 01:35:05'),
(6, 'Fried Rice', 60.00, 1000, 'uploads/products/prod_69391bb2b730a1.42117392.jpg', '2025-12-10 01:35:22'),
(7, 'Egg Omlette', 20.00, 1000, 'uploads/products/prod_69391bc5dc1cc6.48843309.jpg', '2025-12-10 01:35:41'),
(8, 'Mutton Gravy', 280.00, 996, 'uploads/products/prod_69391bd84de695.23031481.jpg', '2025-12-10 01:36:00'),
(9, 'Chicken Gravy', 220.00, 1000, 'uploads/products/prod_69391bedd3a5a5.36730503.jpg', '2025-12-10 01:36:21'),
(10, 'Rice (White)', 20.00, 1000, 'uploads/products/prod_69391bfea74882.03797796.jpg', '2025-12-10 01:36:38'),
(11, 'Water Bottle', 20.00, 999, 'uploads/products/prod_69391c0c47c3b0.45473236.jpg', '2025-12-10 01:36:52'),
(12, 'Veg Noodles', 150.00, 1000, 'uploads/products/prod_69391c1af22605.24674549.jpg', '2025-12-10 01:37:06'),
(13, 'Curd Rice', 50.00, 979, 'uploads/products/prod_69391c4e953e76.14639426.jpg', '2025-12-10 01:37:58'),
(14, 'Chicken Lollipop (6 Pcs)', 140.00, 996, 'uploads/products/prod_69391c85efecd8.74743557.jpg', '2025-12-10 01:38:53'),
(22, 'Veg Rice', 10.00, 110, NULL, '2025-12-11 05:06:43'),
(23, 'Juice', 21.00, 6, NULL, '2025-12-11 05:08:04'),
(28, 'Veg Rice', 11.00, 11, NULL, '2025-12-11 05:43:04');

-- --------------------------------------------------------

--
-- Table structure for table `pos_sales`
--

CREATE TABLE `pos_sales` (
  `id` int(11) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `payment_method` varchar(50) NOT NULL,
  `sale_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pos_sales`
--

INSERT INTO `pos_sales` (`id`, `total_amount`, `payment_method`, `sale_date`) VALUES
(1, 40.00, 'Cash', '2025-12-09 12:29:10'),
(2, 510.00, 'Card', '2025-12-07 12:47:33'),
(3, 50.00, 'Cash', '2025-12-09 13:35:43'),
(4, 330.00, 'Cash', '2025-12-10 13:36:37'),
(5, 340.00, 'Cash', '2025-12-09 13:43:29'),
(6, 330.00, 'Cash', '2025-12-10 14:14:27'),
(7, 100.00, 'Cash', '2025-12-10 14:14:45'),
(8, 50.00, 'Cash', '2025-12-08 14:22:58'),
(9, 430.00, 'Cash', '2025-12-09 14:28:27'),
(10, 40.00, 'Card', '2025-12-11 10:17:06'),
(11, 50.00, 'Cash', '2025-12-11 10:17:25'),
(12, 190.00, 'Cash', '2025-12-11 10:18:45'),
(13, 61.00, 'Cash', '2025-12-11 10:40:58'),
(14, 50.00, 'Cash', '2025-12-11 10:47:09'),
(15, 50.00, 'Cash', '2025-12-11 10:48:46'),
(16, 50.00, 'Cash', '2025-12-11 10:50:08'),
(17, 150.00, 'Cash', '2025-12-11 10:50:43'),
(18, 50.00, 'Cash', '2025-12-11 10:52:32'),
(19, 50.00, 'Cash', '2025-12-11 10:52:58'),
(20, 50.00, 'Cash', '2025-12-11 11:03:24'),
(21, 50.00, 'Cash', '2025-12-11 11:03:30'),
(22, 140.00, 'Cash', '2025-12-11 11:06:18'),
(23, 40.00, 'Cash', '2025-12-11 11:07:34'),
(24, 40.00, 'Cash', '2025-12-11 11:10:15'),
(25, 84.00, 'Cash', '2025-12-11 11:11:45');

-- --------------------------------------------------------

--
-- Table structure for table `pos_sale_items`
--

CREATE TABLE `pos_sale_items` (
  `id` int(11) NOT NULL,
  `sale_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `item_price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pos_sale_items`
--

INSERT INTO `pos_sale_items` (`id`, `sale_id`, `product_id`, `quantity`, `item_price`) VALUES
(3, 2, 14, 1, 140.00),
(4, 2, 3, 1, 40.00),
(5, 2, 8, 1, 280.00),
(6, 2, 13, 1, 50.00),
(7, 3, 13, 1, 50.00),
(8, 4, 8, 1, 280.00),
(9, 4, 13, 1, 50.00),
(10, 5, 8, 1, 280.00),
(11, 5, 3, 1, 40.00),
(12, 5, 11, 1, 20.00),
(13, 6, 13, 1, 50.00),
(14, 6, 14, 2, 140.00),
(15, 7, 13, 2, 50.00),
(16, 8, 13, 1, 50.00),
(17, 9, 8, 1, 280.00),
(18, 9, 13, 3, 50.00),
(19, 10, 3, 1, 40.00),
(20, 11, 13, 1, 50.00),
(21, 12, 3, 1, 40.00),
(22, 12, 13, 3, 50.00),
(23, 13, 3, 1, 40.00),
(24, 13, 23, 1, 21.00),
(25, 14, 13, 1, 50.00),
(26, 15, 13, 1, 50.00),
(27, 16, 13, 1, 50.00),
(28, 17, 5, 1, 150.00),
(29, 18, 13, 1, 50.00),
(30, 19, 13, 1, 50.00),
(31, 20, 13, 1, 50.00),
(32, 21, 13, 1, 50.00),
(33, 22, 14, 1, 140.00),
(34, 23, 3, 1, 40.00),
(35, 24, 3, 1, 40.00),
(36, 25, 23, 4, 21.00);

-- --------------------------------------------------------

--
-- Table structure for table `pos_users`
--

CREATE TABLE `pos_users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email_id` varchar(100) DEFAULT NULL,
  `photo_path` varchar(255) DEFAULT NULL,
  `fingerprint_hash` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pos_users`
--

INSERT INTO `pos_users` (`id`, `username`, `email_id`, `photo_path`, `fingerprint_hash`, `created_at`) VALUES
(1, 'salnazi', 'salnazi@gmail.com', '', 'FP-l28mrravaap', '2025-12-11 06:47:50');

-- --------------------------------------------------------

--
-- Table structure for table `rental_car_booking_system`
--

CREATE TABLE `rental_car_booking_system` (
  `id` int(11) NOT NULL,
  `car_name` varchar(100) NOT NULL,
  `car_type` varchar(50) NOT NULL,
  `available` int(11) DEFAULT 1,
  `travel_date` date DEFAULT NULL,
  `return_date` datetime DEFAULT NULL,
  `pickup_location` varchar(255) DEFAULT NULL,
  `drop_location` varchar(255) DEFAULT NULL,
  `renter_name` varchar(100) DEFAULT NULL,
  `renter_mobile` varchar(15) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `added_on` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `rental_car_booking_system`
--

INSERT INTO `rental_car_booking_system` (`id`, `car_name`, `car_type`, `available`, `travel_date`, `return_date`, `pickup_location`, `drop_location`, `renter_name`, `renter_mobile`, `amount`, `added_on`) VALUES
(1, 'Honda City', 'Sedan', 1, '2025-12-08', '2025-12-08 09:23:12', 'Mannargudi', 'Chennai', 'Salim Nazir', '8428357459', 6888.00, '2025-12-07 15:58:33'),
(2, 'Toyota Innova', 'SUV', 1, '2025-12-08', '2025-12-08 09:21:37', 'Mannargudi', 'Trichy', 'Salim Nazir', '8428357459', 1000.00, '2025-12-07 15:58:33'),
(3, 'Maruti Swift', 'Hatchback', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-07 15:58:33'),
(4, 'Ford EcoSport', 'SUV', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-07 15:58:33'),
(5, 'Hyundai Verna', 'Sedan', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-07 15:58:33');

-- --------------------------------------------------------

--
-- Table structure for table `school`
--

CREATE TABLE `school` (
  `id` int(11) NOT NULL,
  `school_name` varchar(255) NOT NULL,
  `address` text DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `schools`
--

CREATE TABLE `schools` (
  `id` int(11) NOT NULL,
  `school_name` varchar(255) NOT NULL,
  `address` text DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `schools`
--

INSERT INTO `schools` (`id`, `school_name`, `address`, `logo`) VALUES
(1, 'JA Square School', 'Mannargudi\r\nTamilnadu', 'uploads/1765202109_7174.png');

-- --------------------------------------------------------

--
-- Table structure for table `school_info`
--

CREATE TABLE `school_info` (
  `id` int(11) NOT NULL,
  `school_name` varchar(150) NOT NULL,
  `address` text DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `generated_id` varchar(50) NOT NULL,
  `student_name` varchar(150) NOT NULL,
  `class` varchar(30) NOT NULL,
  `section` varchar(30) NOT NULL,
  `photo` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `generated_id`, `student_name`, `class`, `section`, `photo`, `created_at`) VALUES
(1, 'SCH2025125072', 'Jaseena', '4', 'A', 'uploads/1765202129_4789.jpg', '2025-12-08 13:55:29'),
(2, 'SCH2025127250', 'Jaseem', '11', 'D', 'uploads/1765202194_5229.jpg', '2025-12-08 13:56:34'),
(4, 'SCH2025128287', 'Salim Nazir', '7', 'E', 'uploads/student_1765208414_8114.jpg', '2025-12-08 15:40:14'),
(6, 'SCH2025125132', 'salim', '1', 'B', 'uploads/student_1766031291_5982.jpg', '2025-12-18 04:14:51');

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `id` int(11) NOT NULL,
  `subject_name` varchar(120) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `teachers`
--

CREATE TABLE `teachers` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `timetable`
--

CREATE TABLE `timetable` (
  `id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `day` enum('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday') NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tracked_numbers`
--

CREATE TABLE `tracked_numbers` (
  `id` int(11) NOT NULL,
  `mobile_number` varchar(20) DEFAULT NULL,
  `registration_info` varchar(255) DEFAULT '',
  `latitude` float DEFAULT 0,
  `longitude` float DEFAULT 0,
  `tracked_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tracked_numbers`
--

INSERT INTO `tracked_numbers` (`id`, `mobile_number`, `registration_info`, `latitude`, `longitude`, `tracked_at`) VALUES
(1, '8428357459', '', 63.57, 88.01, '2025-12-08 11:49:20');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `qr_code` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password_hash`, `qr_code`, `created_at`) VALUES
(1, 'Salim Nazir', 'salnazi@gmail.com', '$2y$10$21MwYzH./TGR0V7saFbeY.OOU30.XJdNpA6gbiR5Ja159dj/9ZR/q', 'qr_1765090488_1201.png', '2025-12-07 06:54:48'),
(2, 'Faizal Nisha', 'faisal90@gmail.com', '$2y$10$CTqG6bplq.knhjtoe/NcW.Dg3npKhKGIPauGot1oVyd5elQuaT9vu', 'qr_1765090885_5923.png', '2025-12-07 07:01:25');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `codes`
--
ALTER TABLE `codes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `devices`
--
ALTER TABLE `devices`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `device_logs`
--
ALTER TABLE `device_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `device_status`
--
ALTER TABLE `device_status`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `family_expenses`
--
ALTER TABLE `family_expenses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fan_light_devices`
--
ALTER TABLE `fan_light_devices`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fan_light_schedule`
--
ALTER TABLE `fan_light_schedule`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inventory_items`
--
ALTER TABLE `inventory_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inventory_management_system`
--
ALTER TABLE `inventory_management_system`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inventory_management_system_movements`
--
ALTER TABLE `inventory_management_system_movements`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jacms_pages_builder`
--
ALTER TABLE `jacms_pages_builder`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jasquare_devices`
--
ALTER TABLE `jasquare_devices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `device_name` (`device_name`);

--
-- Indexes for table `jaweb_activity_log`
--
ALTER TABLE `jaweb_activity_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jaweb_categories`
--
ALTER TABLE `jaweb_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jaweb_leads`
--
ALTER TABLE `jaweb_leads`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jaweb_menus`
--
ALTER TABLE `jaweb_menus`
  ADD PRIMARY KEY (`link_id`);

--
-- Indexes for table `jaweb_portfolio`
--
ALTER TABLE `jaweb_portfolio`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jaweb_site_settings`
--
ALTER TABLE `jaweb_site_settings`
  ADD PRIMARY KEY (`setting_key`);

--
-- Indexes for table `jaweb_system_logs`
--
ALTER TABLE `jaweb_system_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jaweb_users`
--
ALTER TABLE `jaweb_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `module_downloads`
--
ALTER TABLE `module_downloads`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pos_products`
--
ALTER TABLE `pos_products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pos_sales`
--
ALTER TABLE `pos_sales`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pos_sale_items`
--
ALTER TABLE `pos_sale_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sale_id` (`sale_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `pos_users`
--
ALTER TABLE `pos_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email_id` (`email_id`);

--
-- Indexes for table `rental_car_booking_system`
--
ALTER TABLE `rental_car_booking_system`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `school`
--
ALTER TABLE `school`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `schools`
--
ALTER TABLE `schools`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `school_info`
--
ALTER TABLE `school_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `teachers`
--
ALTER TABLE `teachers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `timetable`
--
ALTER TABLE `timetable`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_tt_teacher` (`teacher_id`),
  ADD KEY `fk_tt_subject` (`subject_id`);

--
-- Indexes for table `tracked_numbers`
--
ALTER TABLE `tracked_numbers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `codes`
--
ALTER TABLE `codes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `devices`
--
ALTER TABLE `devices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `device_logs`
--
ALTER TABLE `device_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `device_status`
--
ALTER TABLE `device_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `family_expenses`
--
ALTER TABLE `family_expenses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `fan_light_schedule`
--
ALTER TABLE `fan_light_schedule`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `inventory_items`
--
ALTER TABLE `inventory_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `inventory_management_system`
--
ALTER TABLE `inventory_management_system`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `inventory_management_system_movements`
--
ALTER TABLE `inventory_management_system_movements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jacms_pages_builder`
--
ALTER TABLE `jacms_pages_builder`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `jasquare_devices`
--
ALTER TABLE `jasquare_devices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `jaweb_activity_log`
--
ALTER TABLE `jaweb_activity_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `jaweb_categories`
--
ALTER TABLE `jaweb_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `jaweb_leads`
--
ALTER TABLE `jaweb_leads`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jaweb_menus`
--
ALTER TABLE `jaweb_menus`
  MODIFY `link_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `jaweb_portfolio`
--
ALTER TABLE `jaweb_portfolio`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `jaweb_system_logs`
--
ALTER TABLE `jaweb_system_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `jaweb_users`
--
ALTER TABLE `jaweb_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `module_downloads`
--
ALTER TABLE `module_downloads`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `pos_products`
--
ALTER TABLE `pos_products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `pos_sales`
--
ALTER TABLE `pos_sales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `pos_sale_items`
--
ALTER TABLE `pos_sale_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `pos_users`
--
ALTER TABLE `pos_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `rental_car_booking_system`
--
ALTER TABLE `rental_car_booking_system`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `school`
--
ALTER TABLE `school`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `schools`
--
ALTER TABLE `schools`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `school_info`
--
ALTER TABLE `school_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `teachers`
--
ALTER TABLE `teachers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `timetable`
--
ALTER TABLE `timetable`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tracked_numbers`
--
ALTER TABLE `tracked_numbers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `pos_sale_items`
--
ALTER TABLE `pos_sale_items`
  ADD CONSTRAINT `pos_sale_items_ibfk_1` FOREIGN KEY (`sale_id`) REFERENCES `pos_sales` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pos_sale_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `pos_products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `timetable`
--
ALTER TABLE `timetable`
  ADD CONSTRAINT `fk_tt_subject` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_tt_teacher` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
