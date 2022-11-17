-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 17, 2022 at 12:17 AM
-- Server version: 10.4.22-MariaDB
-- PHP Version: 7.4.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `west`
--

-- --------------------------------------------------------

--
-- Table structure for table `category_list`
--

CREATE TABLE `category_list` (
  `id` int(30) NOT NULL,
  `name` text NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `category_list`
--

INSERT INTO `category_list` (`id`, `name`, `date_created`, `date_updated`) VALUES
(1, 'Thesis Defense 50%', '2022-07-18 01:34:56', '2022-10-18 11:09:08'),
(2, 'Thesis Final Defense', '2022-07-18 01:35:13', '2022-07-18 01:42:37'),
(3, 'Concept Presentation', '2022-07-18 01:35:42', '2022-07-18 01:35:42'),
(4, 'Thesis Defense 20%', '2022-07-18 01:41:33', '2022-07-18 01:41:33');

-- --------------------------------------------------------

--
-- Table structure for table `chat`
--

CREATE TABLE `chat` (
  `chat_id` int(11) NOT NULL,
  `incoming_id` int(11) NOT NULL,
  `outgoing_id` int(11) NOT NULL,
  `sender_type` enum('student','instructor','adviser') NOT NULL,
  `message` text DEFAULT NULL,
  `message_type` enum('text','file','image') NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `chat`
--

INSERT INTO `chat` (`chat_id`, `incoming_id`, `outgoing_id`, `sender_type`, `message`, `message_type`, `date_created`) VALUES
(51, 17, 4, 'student', 'test', 'text', '2022-11-10 10:47:45'),
(52, 34, 4, 'student', 'test', 'text', '2022-11-11 01:41:21'),
(53, 4, 34, 'adviser', 'test', 'text', '2022-11-11 01:55:34'),
(54, 17, 40, 'student', 'test', 'text', '2022-11-11 01:52:31'),
(55, 4, 17, 'instructor', 'test', 'text', '2022-11-11 06:53:28'),
(56, 4, 17, 'instructor', 'test', 'text', '2022-11-11 04:22:25'),
(57, 40, 17, 'instructor', 'test 2', 'text', '2022-11-11 04:22:33'),
(58, 4, 17, 'instructor', 'test', 'text', '2022-11-11 04:23:50'),
(59, 17, 40, 'student', 'test', 'text', '2022-11-11 04:24:16'),
(60, 17, 40, 'student', 'test', 'text', '2022-11-11 04:25:57'),
(61, 17, 4, 'student', 'test', 'text', '2022-11-11 04:26:24');

-- --------------------------------------------------------

--
-- Table structure for table `documents`
--

CREATE TABLE `documents` (
  `id` int(11) NOT NULL,
  `leader_id` int(11) DEFAULT NULL,
  `title` text NOT NULL,
  `type_id` int(11) NOT NULL,
  `year` varchar(32) NOT NULL,
  `description` text NOT NULL,
  `img_banner` varchar(100) NOT NULL,
  `project_document` varchar(100) NOT NULL,
  `adviser_feedback` text DEFAULT NULL,
  `instructor_feedback` text DEFAULT NULL,
  `panel_rate_status` enum('APPROVED','DISAPPROVED') DEFAULT NULL,
  `publish_status` enum('PENDING','TO PUBLISH','PUBLISHED') NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
  `date_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `documents`
--

INSERT INTO `documents` (`id`, `leader_id`, `title`, `type_id`, `year`, `description`, `img_banner`, `project_document`, `adviser_feedback`, `instructor_feedback`, `panel_rate_status`, `publish_status`, `date_created`, `date_updated`) VALUES
(11, 4, 'test', 1, '2022', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec ut aliquam ligula. Cras consequat id orci eget imperdiet. Nulla eu libero purus. Donec dolor ipsum, dictum sit amet convallis quis, blandit ut nibh. Sed gravida molestie augue, et rutrum ipsum gravida at. Sed pulvinar ante ut justo molestie ullamcorper. Etiam lectus mi, maximus a suscipit vitae, sagittis vitae enim. Donec ullamcorper laoreet purus at mattis.<br></p><p>In eu nulla neque. Integer et posuere lorem. Ut cursus lorem sit amet magna consequat auctor. Morbi justo ipsum, semper rhoncus leo non, facilisis mollis lorem. Aliquam erat volutpat. Sed convallis, metus eu auctor porta, metus felis tincidunt neque, nec molestie sapien ante ac purus. Ut bibendum odio in scelerisque molestie.<br></p><p>Etiam convallis vitae nisi scelerisque gravida. Morbi commodo aliquam tellus, ut iaculis velit volutpat eget. Vestibulum bibendum diam nec sapien accumsan, quis convallis tellus sodales. Praesent ex diam, gravida pellentesque dolor id, sagittis rutrum sapien. Mauris pretium enim quis est bibendum auctor. Aliquam bibendum aliquet nisi, nec iaculis tortor commodo et. Nulla facilisi. Proin ultrices, nisi ac lacinia pellentesque, lectus magna sodales ante, vitae porttitor est nisl bibendum neque. Integer at quam sed augue dictum accumsan id et turpis. Donec dignissim erat vitae purus tincidunt, viverra euismod leo luctus. Duis vulputate, nunc a iaculis hendrerit, libero nibh dignissim elit, a pharetra orci ex vehicula arcu.</p>', '/media/documents/banner/11052022-043131_ERD.png', '/media/documents/files/11052022-043131_pdfjs-express-demo.pdf', '{\"feedback\":[{\"message\":\"<p>test</p>\",\"token\":\"6367bb9ef054c\",\"isResolved\":\"true\",\"date\":\"2022-11-06 21:50:22\"},{\"message\":\"<p>test</p>\",\"token\":\"6367bba42fa19\",\"isResolved\":\"true\",\"date\":\"2022-11-06 21:50:28\"},{\"message\":\"<p>test</p>\",\"token\":\"6367c0df9aa76\",\"isResolved\":\"false\",\"date\":\"2022-11-06 22:12:47\"}],\"isApproved\":\"false\"}', '{\"feedback\":[{\"message\":\"<p>test</p>\",\"token\":\"6367c0c88308a\",\"isResolved\":\"false\",\"date\":\"2022-11-06 22:12:24\"},{\"message\":\"<p>test</p>\",\"token\":\"6367c0d12d33f\",\"isResolved\":\"false\",\"date\":\"2022-11-06 22:12:33\"}],\"isApproved\":\"false\"}', 'APPROVED', 'PENDING', '2022-11-05 08:31:31', '2022-11-16 09:15:40'),
(28, NULL, 'test', 1, '2004', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec ut aliquam ligula. Cras consequat id orci eget imperdiet. Nulla eu libero purus. Donec dolor ipsum, dictum sit amet convallis quis, blandit ut nibh. Sed gravida molestie augue, et rutrum ipsum gravida at. Sed pulvinar ante ut justo molestie ullamcorper. Etiam lectus mi, maximus a suscipit vitae, sagittis vitae enim. Donec ullamcorper laoreet purus at mattis.<br></p><p>In eu nulla neque. Integer et posuere lorem. Ut cursus lorem sit amet magna consequat auctor. Morbi justo ipsum, semper rhoncus leo non, facilisis mollis lorem. Aliquam erat volutpat. Sed convallis, metus eu auctor porta, metus felis tincidunt neque, nec molestie sapien ante ac purus. Ut bibendum odio in scelerisque molestie.<br></p><p>Etiam convallis vitae nisi scelerisque gravida. Morbi commodo aliquam tellus, ut iaculis velit volutpat eget. Vestibulum bibendum diam nec sapien accumsan, quis convallis tellus sodales. Praesent ex diam, gravida pellentesque dolor id, sagittis rutrum sapien. Mauris pretium enim quis est bibendum auctor. Aliquam bibendum aliquet nisi, nec iaculis tortor commodo et. Nulla facilisi. Proin ultrices, nisi ac lacinia pellentesque, lectus magna sodales ante, vitae porttitor est nisl bibendum neque. Integer at quam sed augue dictum accumsan id et turpis. Donec dignissim erat vitae purus tincidunt, viverra euismod leo luctus. Duis vulputate, nunc a iaculis hendrerit, libero nibh dignissim elit, a pharetra orci ex vehicula arcu.</p>', '/media/documents/banner/11082022-115146_ERD.png', '/media/documents/files/11082022-115146_pdfjs-express-demo.pdf', NULL, NULL, NULL, 'PUBLISHED', '2022-11-08 15:51:46', '2022-11-11 10:02:24');

-- --------------------------------------------------------

--
-- Table structure for table `invite`
--

CREATE TABLE `invite` (
  `id` int(11) NOT NULL,
  `adviser_id` int(11) NOT NULL,
  `leader_id` int(11) NOT NULL,
  `status` enum('PENDING','APPROVED','DECLINED') NOT NULL,
  `proposed_title` text NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `invite`
--

INSERT INTO `invite` (`id`, `adviser_id`, `leader_id`, `status`, `proposed_title`, `date_created`) VALUES
(12, 34, 4, 'APPROVED', 'test title', '2022-10-29 05:35:10');

-- --------------------------------------------------------

--
-- Table structure for table `panel_ratings`
--

CREATE TABLE `panel_ratings` (
  `rating_id` int(11) NOT NULL,
  `document_id` int(11) NOT NULL,
  `leader_id` int(11) NOT NULL,
  `panel_id` int(11) NOT NULL,
  `rating_type` enum('concept','20percent','50percent','final') NOT NULL,
  `comment` text NOT NULL,
  `action` enum('Approved','Disapproved') NOT NULL,
  `group_grade` text DEFAULT NULL,
  `individual_grade` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `panel_ratings`
--

INSERT INTO `panel_ratings` (`rating_id`, `document_id`, `leader_id`, `panel_id`, `rating_type`, `comment`, `action`, `group_grade`, `individual_grade`) VALUES
(6, 11, 4, 37, 'concept', '<p>test</p>', 'Disapproved', '[{\"title\":\"Complexity and Innovativeness of the proposal\",\"name\":\"complexity\",\"max\":20,\"grade\":\"2\"},{\"title\":\"Content and appropriateness of the Document\",\"name\":\"content\",\"max\":50,\"grade\":\"22\"},{\"title\":\"Group Delivery and presentation\",\"name\":\"delivery\",\"max\":30,\"grade\":\"22\"}]', '[{\"id\":\"4\",\"name\":\" Stu D. Ent\",\"grade\":\"22\"},{\"id\":\"23\",\"name\":\" Test T. Test22\",\"grade\":\"22\"},{\"id\":\"24\",\"name\":\" Test1 T. Lastname\",\"grade\":\"22\"}]'),
(7, 11, 4, 36, 'concept', '<p>test</p>', 'Approved', '[{\"title\":\"Complexity and Innovativeness of the proposal\",\"name\":\"complexity\",\"max\":20,\"grade\":\"2\"},{\"title\":\"Content and appropriateness of the Document\",\"name\":\"content\",\"max\":50,\"grade\":\"22\"},{\"title\":\"Group Delivery and presentation\",\"name\":\"delivery\",\"max\":30,\"grade\":\"22\"}]', '[{\"id\":\"4\",\"name\":\" Stu D. Ent\",\"grade\":\"22\"},{\"id\":\"23\",\"name\":\" Test T. Test22\",\"grade\":\"22\"},{\"id\":\"24\",\"name\":\" Test1 T. Lastname\",\"grade\":\"22\"}]'),
(9, 11, 4, 33, 'concept', '<p>test</p>', 'Approved', '[{\"title\":\"Complexity and Innovativeness of the proposal\",\"name\":\"complexity\",\"max\":20,\"grade\":\"2\"},{\"title\":\"Content and appropriateness of the Document\",\"name\":\"content\",\"max\":50,\"grade\":\"22\"},{\"title\":\"Group Delivery and presentation\",\"name\":\"delivery\",\"max\":30,\"grade\":\"22\"}]', '[{\"id\":\"4\",\"name\":\" Stu D. Ent\",\"grade\":\"55\"},{\"id\":\"23\",\"name\":\" Test T. Test22\",\"grade\":\"55\"},{\"id\":\"24\",\"name\":\" Test1 T. Lastname\",\"grade\":\"55\"}]');

-- --------------------------------------------------------

--
-- Table structure for table `schedule_list`
--

CREATE TABLE `schedule_list` (
  `id` int(30) NOT NULL,
  `user_id` int(30) NOT NULL,
  `category_id` int(30) NOT NULL,
  `title` text NOT NULL,
  `description` text NOT NULL,
  `schedule_from` datetime NOT NULL,
  `schedule_to` datetime DEFAULT NULL,
  `is_whole` tinyint(4) NOT NULL DEFAULT 0,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `schedule_list`
--

INSERT INTO `schedule_list` (`id`, `user_id`, `category_id`, `title`, `description`, `schedule_from`, `schedule_to`, `is_whole`, `date_created`, `date_updated`) VALUES
(1, 1, 3, 'Sample Task 101', 'This is a sample task.', '2022-05-12 09:00:00', '2022-05-12 14:00:00', 0, '2022-05-12 10:51:43', '2022-05-12 11:24:50'),
(2, 1, 3, 'Task 102', 'Test 123', '2022-05-16 13:00:00', '2022-05-17 17:00:00', 0, '2022-05-12 11:05:59', '2022-05-12 11:05:59'),
(3, 1, 3, 'Task 101', 'Test 123', '2022-05-12 15:00:00', '2022-05-12 17:00:00', 0, '2022-05-12 12:04:11', '2022-05-12 12:04:11'),
(4, 1, 3, 'TEst 123', 'Test only', '2022-05-14 10:00:00', '2022-05-14 14:00:00', 0, '2022-05-12 13:15:30', '2022-05-12 13:15:30'),
(5, 1, 3, 'Thesis', 'Group 5', '2022-07-15 10:00:00', '2022-07-15 11:00:00', 0, '2022-07-18 11:07:36', '2022-07-18 11:07:36'),
(12, 1, 2, 'test', 'test', '2022-10-18 19:54:00', NULL, 1, '2022-10-18 19:54:10', '2022-10-18 19:54:10'),
(13, 34, 3, 'test 2', 'desc', '2022-10-30 11:22:00', NULL, 1, '2022-10-29 13:23:17', '2022-10-29 13:23:17'),
(14, 1, 1, 'test', 'test', '2022-11-05 10:15:00', '0000-00-00 00:00:00', 1, '2022-11-05 10:15:30', '2022-11-05 10:23:32');

-- --------------------------------------------------------

--
-- Table structure for table `system_config`
--

CREATE TABLE `system_config` (
  `id` int(11) NOT NULL,
  `system_name` text NOT NULL,
  `home_content` text NOT NULL,
  `cover` varchar(250) NOT NULL,
  `logo` varchar(250) NOT NULL,
  `contact` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `system_config`
--

INSERT INTO `system_config` (`id`, `system_name`, `home_content`, `cover`, `logo`, `contact`) VALUES
(1, 'Thesis Progress Monitoring and Archive Management System', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec ut aliquam ligula. Cras consequat id orci eget imperdiet. Nulla eu libero purus. Donec dolor ipsum, dictum sit amet convallis quis, blandit ut nibh. Sed gravida molestie augue, et rutrum ipsum gravida at. Sed pulvinar ante ut justo molestie ullamcorper. Etiam lectus mi, maximus a suscipit vitae, sagittis vitae enim. Donec ullamcorper laoreet purus at mattis.<br></p><p>In eu nulla neque. Integer et posuere lorem. Ut cursus lorem sit amet magna consequat auctor. Morbi justo ipsum, semper rhoncus leo non, facilisis mollis lorem. Aliquam erat volutpat. Sed convallis, metus eu auctor porta, metus felis tincidunt neque, nec molestie sapien ante ac purus. Ut bibendum odio in scelerisque molestie.<br></p><p>Etiam convallis vitae nisi scelerisque gravida. Morbi commodo aliquam tellus, ut iaculis velit volutpat eget. Vestibulum bibendum diam nec sapien accumsan, quis convallis tellus sodales. Praesent ex diam, gravida pellentesque dolor id, sagittis rutrum sapien. Mauris pretium enim quis est bibendum auctor. Aliquam bibendum aliquet nisi, nec iaculis tortor commodo et. Nulla facilisi. Proin ultrices, nisi ac lacinia pellentesque, lectus magna sodales ante, vitae porttitor est nisl bibendum neque. Integer at quam sed augue dictum accumsan id et turpis. Donec dignissim erat vitae purus tincidunt, viverra euismod leo luctus. Duis vulputate, nunc a iaculis hendrerit, libero nibh dignissim elit, a pharetra orci ex vehicula arcu.</p>', '/public/cover-1638840281.jpg', '/public/10172022-112443_logo-1657357283.png', '09854698789 / 78945632');

-- --------------------------------------------------------

--
-- Table structure for table `thesis_groups`
--

CREATE TABLE `thesis_groups` (
  `id` int(11) NOT NULL,
  `group_leader_id` int(11) NOT NULL,
  `group_number` int(11) NOT NULL,
  `group_member_ids` varchar(50) NOT NULL,
  `instructor_id` int(11) DEFAULT NULL,
  `panel_ids` varchar(100) DEFAULT NULL,
  `adviser_id` int(11) DEFAULT NULL,
  `status` tinyint(1) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
  `date_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `thesis_groups`
--

INSERT INTO `thesis_groups` (`id`, `group_leader_id`, `group_number`, `group_member_ids`, `instructor_id`, `panel_ids`, `adviser_id`, `status`, `date_created`, `date_updated`) VALUES
(10, 4, 1, '[\"23\",\"24\"]', 17, '[\"33\",\"36\",\"37\"]', 34, 1, '2022-10-29 05:33:22', '2022-11-16 08:19:28'),
(11, 40, 2, '[]', 17, '[\"35\",\"36\",\"37\"]', NULL, 1, '2022-11-10 09:23:38', '2022-11-11 06:20:40');

-- --------------------------------------------------------

--
-- Table structure for table `types`
--

CREATE TABLE `types` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
  `date_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `types`
--

INSERT INTO `types` (`id`, `name`, `date_created`, `date_updated`) VALUES
(1, 'Data mining', '2022-10-21 13:17:20', '2022-10-25 01:50:39');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `roll` varchar(250) DEFAULT NULL,
  `first_name` varchar(250) NOT NULL,
  `middle_name` varchar(250) DEFAULT NULL,
  `last_name` varchar(250) NOT NULL,
  `group_number` int(11) DEFAULT NULL,
  `year_and_section` varchar(32) DEFAULT NULL,
  `avatar` varchar(250) DEFAULT NULL,
  `username` varchar(500) NOT NULL,
  `email` varchar(250) NOT NULL,
  `password` varchar(250) DEFAULT NULL,
  `role` enum('student','instructor','coordinator','panel','adviser') NOT NULL,
  `isLeader` tinyint(1) DEFAULT NULL,
  `leader_id` int(11) DEFAULT NULL,
  `is_new` tinyint(1) NOT NULL,
  `date_added` datetime NOT NULL,
  `date_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `roll`, `first_name`, `middle_name`, `last_name`, `group_number`, `year_and_section`, `avatar`, `username`, `email`, `password`, `role`, `isLeader`, `leader_id`, `is_new`, `date_added`, `date_updated`) VALUES
(1, NULL, 'fname', 'mname', 'lname', NULL, NULL, '/media/avatar/10162022-031509_10152022-111537_10072022-033907_avatar4.png', 'fname-lname-YZNlsAI7LOqw', 'coordinator@gmail.com', '$argon2i$v=19$m=65536,t=4,p=1$Ny9ZNWhMQkxPcjRCanNMUA$1pMWiZQC4APzf2mxgU6RvlaLz7KakK6pngGWFWZaL5k', 'coordinator', NULL, NULL, 0, '2022-09-28 03:58:39', '2022-10-21 13:02:34'),
(4, '0987654321', 'stu', 'd', 'ent', 1, '3-b', '/media/avatar/10052022-113300_avatar.png', 'john-montemar-EusXG8wLM6Cz', 'test@gmail.com', '$argon2i$v=19$m=65536,t=4,p=1$UU1vUWVVZExLSk8ySVpWdg$bakr6kcD2wntPgYevkNOi6NRLDMq1ywf8jA1MeseJg4', 'student', 1, NULL, 0, '2022-09-28 03:58:39', '2022-10-30 02:41:50'),
(17, NULL, 'instructor', 'p', 'montemar', NULL, NULL, '/media/avatar/10052022-113300_avatar.png', 'instructor-montemar-WL2M1i9AQewU', 'instructor@gmail.com', '$argon2i$v=19$m=65536,t=4,p=1$Ny9ZNWhMQkxPcjRCanNMUA$1pMWiZQC4APzf2mxgU6RvlaLz7KakK6pngGWFWZaL5k', 'instructor', NULL, NULL, 0, '2022-10-06 01:04:41', '2022-10-21 13:02:30'),
(23, '12345678', 'test', 'test122', 'test22', 1, '4-b', '/media/avatar/10072022-033731_avatar5.png', 'test1222awdawdawd-test22-uSPjPC2KBWiJ', 'test1@gmail.com', NULL, 'student', NULL, 4, 0, '2022-10-07 03:37:31', '2022-10-21 13:02:26'),
(24, '1234561214124', 'test1', 'test', 'lastname', 1, '4-a', '/media/avatar/10072022-033907_avatar4.png', 'test1-montemar-ZmJugFKPUNeX', 'test2@gmail.com', NULL, 'student', NULL, 4, 0, '2022-10-07 03:39:07', '2022-10-30 02:41:56'),
(32, NULL, 'awd12awd', 'awd12', 'awd12', NULL, NULL, '/media/avatar/10152022-111537_10072022-033907_avatar4.png', 'awd-awd-DQmvqDXkzhr/', 'admin@admin.com', '$argon2i$v=19$m=65536,t=4,p=1$QWNvSG5LTTl2d3A0QVdKMw$UY31n1y7snAyX6XHseUFYoqf8qUTJby8t5kasXIx6Cg', 'coordinator', NULL, NULL, 1, '2022-10-15 10:34:44', '2022-10-20 09:14:38'),
(33, NULL, 'firstname', 'awd', 'lastname', NULL, NULL, '/media/avatar/10162022-013033_10072022-033731_avatar5.png', 'john-montemar-mWcie4wMaSKL', 'testpanel@gmail.com', '$argon2i$v=19$m=65536,t=4,p=1$Mjh5TTRjQ3FKUnZkQnlxag$kZCUG5FyXSLTn1vcEewgsUzrSqHNOcHaTMYJVbbQ99I', 'panel', NULL, NULL, 0, '2022-10-16 01:30:33', '2022-11-06 13:47:46'),
(34, NULL, 'ad', 'v', 'iser', NULL, NULL, '/media/avatar/10192022-111103_10162022-031509_10152022-111537_10072022-033907_avatar4.png', 'ad-iser-GuOJfN2uAKx8', 'adviser@gmail.com', '$argon2i$v=19$m=65536,t=4,p=1$YVMxbzBhZGVYODhxeWRNNA$I/9UQcNR1lmViVg1qAQg7jCttBbsanzd6SIu+TCH7jg', 'adviser', NULL, NULL, 0, '2022-10-19 11:11:03', '2022-10-24 15:45:05'),
(35, NULL, 'firstname', 'awd', 'lastname', NULL, NULL, '/media/avatar/10162022-013033_10072022-033731_avatar5.png', 'john-montemar-mWcie4wMaSKL', 'testpanel@gmail.com', '$argon2i$v=19$m=65536,t=4,p=1$QWNvSG5LTTl2d3A0QVdKMw$UY31n1y7snAyX6XHseUFYoqf8qUTJby8t5kasXIx6Cg', 'panel', NULL, NULL, 1, '2022-10-16 01:30:33', '2022-11-05 09:28:13'),
(36, NULL, 'firstname', 'awd', 'lastname', NULL, NULL, '/media/avatar/10162022-013033_10072022-033731_avatar5.png', 'john-montemar-mWcie4wMaSKL', 'testpanel@gmail.com', '$argon2i$v=19$m=65536,t=4,p=1$QWNvSG5LTTl2d3A0QVdKMw$UY31n1y7snAyX6XHseUFYoqf8qUTJby8t5kasXIx6Cg', 'panel', NULL, NULL, 1, '2022-10-16 01:30:33', '2022-11-05 09:28:13'),
(37, NULL, 'firstname', 'awd', 'lastname', NULL, NULL, '/media/avatar/10162022-013033_10072022-033731_avatar5.png', 'john-montemar-mWcie4wMaSKL', 'testpanel@gmail.com', '$argon2i$v=19$m=65536,t=4,p=1$QWNvSG5LTTl2d3A0QVdKMw$UY31n1y7snAyX6XHseUFYoqf8qUTJby8t5kasXIx6Cg', 'panel', NULL, NULL, 1, '2022-10-16 01:30:33', '2022-11-05 09:28:13'),
(38, NULL, 'firstname', 'awd', 'lastname', NULL, NULL, '/media/avatar/10162022-013033_10072022-033731_avatar5.png', 'john-montemar-mWcie4wMaSKL', 'testpanel@gmail.com', '$argon2i$v=19$m=65536,t=4,p=1$QWNvSG5LTTl2d3A0QVdKMw$UY31n1y7snAyX6XHseUFYoqf8qUTJby8t5kasXIx6Cg', 'panel', NULL, NULL, 1, '2022-10-16 01:30:33', '2022-11-05 09:28:13'),
(39, NULL, 'firstname', 'awd', 'lastname', NULL, NULL, '/media/avatar/10162022-013033_10072022-033731_avatar5.png', 'john-montemar-mWcie4wMaSKL', 'testpanel@gmail.com', '$argon2i$v=19$m=65536,t=4,p=1$QWNvSG5LTTl2d3A0QVdKMw$UY31n1y7snAyX6XHseUFYoqf8qUTJby8t5kasXIx6Cg', 'panel', NULL, NULL, 1, '2022-10-16 01:30:33', '2022-11-05 09:28:13'),
(40, '018392874', 'stuawd', 'd', 'entawd', 2, '3-b', '/media/avatar/10052022-113300_avatar.png', 'stu-ent-EusXG8wLM6Cz', 'sample@gmail.com', '$argon2i$v=19$m=65536,t=4,p=1$UU1vUWVVZExLSk8ySVpWdg$bakr6kcD2wntPgYevkNOi6NRLDMq1ywf8jA1MeseJg4', 'student', 1, NULL, 0, '2022-09-28 03:58:39', '2022-11-10 09:25:12');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `category_list`
--
ALTER TABLE `category_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `chat`
--
ALTER TABLE `chat`
  ADD PRIMARY KEY (`chat_id`);

--
-- Indexes for table `documents`
--
ALTER TABLE `documents`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invite`
--
ALTER TABLE `invite`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `panel_ratings`
--
ALTER TABLE `panel_ratings`
  ADD PRIMARY KEY (`rating_id`);

--
-- Indexes for table `schedule_list`
--
ALTER TABLE `schedule_list`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `system_config`
--
ALTER TABLE `system_config`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `thesis_groups`
--
ALTER TABLE `thesis_groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `types`
--
ALTER TABLE `types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `category_list`
--
ALTER TABLE `category_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `chat`
--
ALTER TABLE `chat`
  MODIFY `chat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT for table `documents`
--
ALTER TABLE `documents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `invite`
--
ALTER TABLE `invite`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `panel_ratings`
--
ALTER TABLE `panel_ratings`
  MODIFY `rating_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `schedule_list`
--
ALTER TABLE `schedule_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `system_config`
--
ALTER TABLE `system_config`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `thesis_groups`
--
ALTER TABLE `thesis_groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `types`
--
ALTER TABLE `types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
