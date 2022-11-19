-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 19, 2022 at 03:24 PM
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
(1, 'Concept Presentation', '2022-07-18 01:34:56', '2022-11-19 22:22:17'),
(2, 'Thesis Defense 20%', '2022-07-18 01:35:13', '2022-11-19 22:22:35'),
(3, 'Thesis Defense 50%', '2022-11-19 22:23:05', '2022-11-19 22:23:05'),
(4, 'Thesis Final Defense', '2022-11-19 22:23:05', '2022-11-19 22:23:05');

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
(1, 9, 'Sample title', 1, '2021', '<p>Sample description</p>', '/media/documents/banner/11192022-091507_Screenshot 2022-11-19 211410.jpg', '/media/documents/files/11192022-091507_pdfjs-express-demo.pdf', NULL, NULL, NULL, 'PENDING', '2022-11-19 13:15:07', '2022-11-19 13:15:07');

-- --------------------------------------------------------

--
-- Table structure for table `instructor_sections`
--

CREATE TABLE `instructor_sections` (
  `id` int(11) NOT NULL,
  `instructor_id` int(11) NOT NULL,
  `sections` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `instructor_sections`
--

INSERT INTO `instructor_sections` (`id`, `instructor_id`, `sections`) VALUES
(1, 2, '[\"4-C\",\"4-B\",\"4-A\"]');

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
(1, 1, 9, 4, 'concept', '<p>test</p>', 'Approved', '[{\"title\":\"Complexity and Innovativeness of the proposal\",\"name\":\"complexity\",\"max\":20,\"grade\":\"18\"},{\"title\":\"Content and appropriateness of the Document\",\"name\":\"content\",\"max\":50,\"grade\":\"35\"},{\"title\":\"Group Delivery and presentation\",\"name\":\"delivery\",\"max\":30,\"grade\":\"25\"}]', '[{\"id\":\"9\",\"name\":\" Leader L. Leader\",\"grade\":\"80\"},{\"id\":\"10\",\"name\":\" Student  One\",\"grade\":\"80\"},{\"id\":\"11\",\"name\":\" Student  Two\",\"grade\":\"80\"},{\"id\":\"12\",\"name\":\" Student  Three\",\"grade\":\"80\"},{\"id\":\"13\",\"name\":\" Student  Four\",\"grade\":\"80\"}]');

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
  `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
  `date_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `thesis_groups`
--

INSERT INTO `thesis_groups` (`id`, `group_leader_id`, `group_number`, `group_member_ids`, `instructor_id`, `panel_ids`, `adviser_id`, `date_created`, `date_updated`) VALUES
(1, 9, 1, '[\"10\",\"11\",\"12\",\"13\"]', 2, '[\"4\",\"5\",\"6\",\"7\",\"8\"]', NULL, '2022-11-19 13:12:04', '2022-11-19 13:12:32');

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
(1, 'Data mining', '2022-10-21 13:17:20', '2022-10-25 01:50:39'),
(2, 'Robotics', '2022-10-21 13:17:20', '2022-10-25 01:50:39');

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
  `school_year` text DEFAULT NULL,
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

INSERT INTO `users` (`id`, `roll`, `first_name`, `middle_name`, `last_name`, `school_year`, `group_number`, `year_and_section`, `avatar`, `username`, `email`, `password`, `role`, `isLeader`, `leader_id`, `is_new`, `date_added`, `date_updated`) VALUES
(1, NULL, 'coordinator', 'coordinator', 'coordinator', NULL, NULL, NULL, '/media/avatar/10162022-031509_10152022-111537_10072022-033907_avatar4.png', 'coordinator-coordinator-YZNlsAI7LOqw', 'coordinator@gmail.com', '$argon2i$v=19$m=65536,t=4,p=1$OXRvbjgxWUpnMW5mZU00cA$+aapaOG+CDk1+hgObV+ODcnlmTazsF7MpKS823s6+qY', 'coordinator', NULL, NULL, 0, '2022-09-28 03:58:39', '2022-11-19 12:23:27'),
(2, NULL, 'instructor', 'instructor', 'instructor', NULL, NULL, NULL, '/media/avatar/11192022-084503_avatar.png', 'instructor-instructor-Zrd0P4NzmtH', 'instructor@gmail.com', '$argon2i$v=19$m=65536,t=4,p=1$NU90am5OS0lCVWhQeG9XMQ$jBGoY/ZNnuX6xL9Hq6VRk8djacZfagKtkzQQYCeVMV4', 'instructor', NULL, NULL, 0, '2022-11-19 20:45:03', '2022-11-19 13:07:58'),
(3, NULL, 'adviser', 'adviser', 'adviser', NULL, NULL, NULL, '/media/avatar/11192022-084828_avatar5.png', 'adviser-adviser-rBlKUpDC6GN', 'adviser@gmail.com', '$argon2i$v=19$m=65536,t=4,p=1$YjdqN1pCM3B2ZFJrVzhVSg$ms1+AHX0l6ZZgndinZPnLMpFIChwV4K0xxBbUpT9Bz4', 'adviser', NULL, NULL, 1, '2022-11-19 20:48:27', '2022-11-19 12:48:28'),
(4, NULL, 'panel', NULL, 'one', NULL, NULL, NULL, '/media/avatar/11192022-085150_user1-128x128.jpg', 'panel-one-iuhpigLFyXAi', 'panel_one@gmail.com', '$argon2i$v=19$m=65536,t=4,p=1$Z29lME5weGtzRndwVFIvUw$qXh99wqFL6XiczKcJG6wGP/LVwUsA1pfKRJnags/fv4', 'panel', NULL, NULL, 0, '2022-11-19 20:51:50', '2022-11-19 14:19:11'),
(5, NULL, 'panel', NULL, 'two', NULL, NULL, NULL, '/media/avatar/11192022-085253_user2-160x160.jpg', 'panel-two-z2P1xZQ8HUC', 'panel_two@gmail.com', '$argon2i$v=19$m=65536,t=4,p=1$ZE04NHd0WTBuVG0veDU3Uw$e3vZ9/sxDYgeOVxQeP6Andsq7dbAMcRk7bYy5XXzHXk', 'panel', NULL, NULL, 1, '2022-11-19 20:52:53', '2022-11-19 12:52:53'),
(6, NULL, 'panel', NULL, 'three', NULL, NULL, NULL, '/media/avatar/11192022-085421_user3-128x128.jpg', 'panel-three-UMRcDuxaaFdK', 'panel_three@gmail.com', '$argon2i$v=19$m=65536,t=4,p=1$QWVyL05SVjhGU2NTLjhBTw$Vz/EIBmnN02nJ8AnsVNiZHCSkCl8yk+DmrY/wepIIAY', 'panel', NULL, NULL, 1, '2022-11-19 20:54:21', '2022-11-19 12:54:21'),
(7, NULL, 'panel', NULL, 'four', NULL, NULL, NULL, '/media/avatar/11192022-085520_user4-128x128.jpg', 'panel-four-zhOwMXLTVM7i', 'panel_four@gmail.com', '$argon2i$v=19$m=65536,t=4,p=1$S3VtSUtVSVBSaWtlN3NoVA$28a1VF24JPzNqE4YmTQbT0SsnyzYcQGKRjhzdfBLjRY', 'panel', NULL, NULL, 1, '2022-11-19 20:55:19', '2022-11-19 12:55:20'),
(8, NULL, 'panel', NULL, 'five', NULL, NULL, NULL, '/media/avatar/11192022-085812_user6-128x128.jpg', 'panel-five-TDnkgEeaidWs', 'panel_five@gmail.com', '$argon2i$v=19$m=65536,t=4,p=1$MlQvZm0wTWhYaEhDN0VyWg$loOCKTkTlu1oF4MPmRoRbhDukhyVWth1qPNgU9asSEs', 'panel', NULL, NULL, 1, '2022-11-19 20:57:29', '2022-11-19 12:58:12'),
(9, '2468', 'leader', 'leader', 'leader', 'SY: 2021-22', 1, '4-A', NULL, 'leader-leader-F0Qi4eZ1x7lG', 'leader@gmail.com', '$argon2i$v=19$m=65536,t=4,p=1$U1plbnVHUzFabWZaYkZzVQ$aGENFN5EPdwTipO8FOvqApI/GVkoIyWBC8Biaww/wHo', 'student', 1, NULL, 0, '2022-11-19 20:59:36', '2022-11-19 13:12:04'),
(10, '1357', 'student', NULL, 'one', 'SY: 2021-22', 1, '4-A', NULL, 'student-one-9Z5ldidSW02B', 'student_one@gmail.com', '$argon2i$v=19$m=65536,t=4,p=1$aFh2ak42OWsxdzk3U09mNg$KzCFzg8okp0DbK+7tK6yF3IBB2OdWjDw74X2p8e2M/s', 'student', NULL, 9, 0, '2022-11-19 21:05:34', '2022-11-19 13:12:04'),
(11, '12345', 'student', NULL, 'two', 'SY: 2021-22', 1, '4-A', NULL, 'student-two-VtiFj9Rr1aAk', 'student_two@gmail.com', '$argon2i$v=19$m=65536,t=4,p=1$YTVJUUVDNkREZTZLcURUeg$9xB67KpblUoVOgZ4r0fBgFkuYDHoaAnQdV22CG8ZxdY', 'student', NULL, 9, 0, '2022-11-19 21:06:16', '2022-11-19 13:12:04'),
(12, '54321', 'student', NULL, 'three', 'SY: 2021-22', 1, '4-A', NULL, 'student-three-6UDvBHYuvp0f', 'student_three@gmail.com', '$argon2i$v=19$m=65536,t=4,p=1$d2ptNjlnQ0U5UHZWNHNleg$BbRp+NB/DAwpObC3Zyu2gLCLntFTKQyknWz0CuAWaRc', 'student', NULL, 9, 0, '2022-11-19 21:07:00', '2022-11-19 13:12:04'),
(13, '98765', 'student', NULL, 'four', 'SY: 2021-22', 1, '4-A', NULL, 'student-four-u5X9QiWhR4eC', 'student_four@gmail.com', '$argon2i$v=19$m=65536,t=4,p=1$Zk1VR3Y0dzFwandIUTFvaw$vYXUDcoKoYvVjxN8jSaBPpyDyuH65u4CMHMv+WI0zC4', 'student', NULL, 9, 0, '2022-11-19 21:07:31', '2022-11-19 13:12:04');

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
-- Indexes for table `instructor_sections`
--
ALTER TABLE `instructor_sections`
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
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `chat`
--
ALTER TABLE `chat`
  MODIFY `chat_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `documents`
--
ALTER TABLE `documents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `instructor_sections`
--
ALTER TABLE `instructor_sections`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `invite`
--
ALTER TABLE `invite`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `panel_ratings`
--
ALTER TABLE `panel_ratings`
  MODIFY `rating_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `schedule_list`
--
ALTER TABLE `schedule_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `system_config`
--
ALTER TABLE `system_config`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `thesis_groups`
--
ALTER TABLE `thesis_groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `types`
--
ALTER TABLE `types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
