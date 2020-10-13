-- phpMyAdmin SQL Dump
-- version 4.9.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Apr 20, 2020 at 08:55 PM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `jquinn63`
--

-- --------------------------------------------------------

--
-- Table structure for table `PT_address`
--

CREATE TABLE `PT_address` (
  `address_id` int(11) NOT NULL,
  `users_id` int(11) NOT NULL,
  `address_first_line` varchar(50) NOT NULL,
  `address_second_line` varchar(50) NOT NULL,
  `address_postcode` varchar(10) NOT NULL,
  `address_country` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `PT_address`
--

INSERT INTO `PT_address` (`address_id`, `users_id`, `address_first_line`, `address_second_line`, `address_postcode`, `address_country`) VALUES
(1, 1, '35 Ballynakilly Road        ', 'Creenagh        ', 'BT71 6JJ  ', 'UK        '),
(2, 3, '23 Belfast Way    ', 'Belfast    ', 'BT7 4FR   ', 'UK    '),
(11, 2, '43 Dungannon Road', 'Dungannon', 'BT6 54E', 'UK'),
(36, 43, '56 Creenagh Lane', 'Edendork', 'BT71 5JJ', 'UK'),
(37, 44, '109 Irish Street', 'Dungannon', 'BT7 8DR', 'UK'),
(40, 58, '109 Irish Street', 'Dungannon', 'BT71 5JJ', 'UK'),
(42, 60, '14 Bennett Drive', 'Belfast', 'Bt146db', 'UK'),
(43, 61, '12 Grange Road', 'Belfast', 'BT5 6RE', 'UK'),
(53, 73, '109 Irish Street', 'Dungannon', 'BT7 8DR', 'UK');

-- --------------------------------------------------------

--
-- Table structure for table `PT_exercise_types`
--

CREATE TABLE `PT_exercise_types` (
  `id` int(11) NOT NULL,
  `exercise_type` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `PT_exercise_types`
--

INSERT INTO `PT_exercise_types` (`id`, `exercise_type`) VALUES
(1, 'Chest press'),
(2, 'Squat'),
(3, 'Deadlift');

-- --------------------------------------------------------

--
-- Table structure for table `PT_external_messages`
--

CREATE TABLE `PT_external_messages` (
  `id` int(11) NOT NULL,
  `email_from` varchar(20) NOT NULL,
  `name_from` varchar(20) NOT NULL,
  `message_content` varchar(4000) NOT NULL,
  `has_message_been_replied_to` tinyint(1) NOT NULL DEFAULT 0,
  `date` varchar(30) NOT NULL,
  `time` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `PT_external_messages`
--

INSERT INTO `PT_external_messages` (`id`, `email_from`, `name_from`, `message_content`, `has_message_been_replied_to`, `date`, `time`) VALUES
(3, 'jack@email.com', 'Jack', 'Hi, can you take a complete beginner?', 0, 'Monday 13th Apr 2020', '18:49:18'),
(4, 'john@email.com', 'John', 'Hi, what is your contract lengths?', 0, 'Friday 17th Apr 2020', '15:29:56'),
(8, 'john@gmail.com', 'John', 'Hi, can I get a years subscription?', 0, 'Friday 17th Apr 2020', '16:09:59'),
(9, 'john@gmail.com', 'John', 'Hi, can I get a years subscription?', 0, 'Friday 17th Apr 2020', '16:25:07'),
(23, 'john@email.com', 'John', 'hi, what is your weekly rate?', 0, 'Monday 20th Apr 2020', '12:38:26');

-- --------------------------------------------------------

--
-- Table structure for table `PT_group`
--

CREATE TABLE `PT_group` (
  `group_id` int(11) NOT NULL,
  `users_id` int(11) NOT NULL,
  `group_number` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `PT_group`
--

INSERT INTO `PT_group` (`group_id`, `users_id`, `group_number`) VALUES
(15, 2, 2),
(16, 3, 2),
(85, 43, 3),
(86, 44, 3),
(118, 60, 4);

-- --------------------------------------------------------

--
-- Table structure for table `PT_group_bookings`
--

CREATE TABLE `PT_group_bookings` (
  `booking_id` int(11) NOT NULL,
  `booking_title` varchar(30) NOT NULL,
  `booking_start_time` varchar(20) NOT NULL,
  `booking_end_time` varchar(20) NOT NULL,
  `booking_date` varchar(20) NOT NULL,
  `booking_for` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `PT_group_bookings`
--

INSERT INTO `PT_group_bookings` (`booking_id`, `booking_title`, `booking_start_time`, `booking_end_time`, `booking_date`, `booking_for`) VALUES
(3, 'Cardio1', '09:00:00', '10:00:00', '2020-04-20', 2);

-- --------------------------------------------------------

--
-- Table structure for table `PT_group_messages`
--

CREATE TABLE `PT_group_messages` (
  `id` int(11) NOT NULL,
  `group_number` int(11) NOT NULL,
  `group_message_from` int(11) NOT NULL,
  `message_content` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `PT_group_messages`
--

INSERT INTO `PT_group_messages` (`id`, `group_number`, `group_message_from`, `message_content`) VALUES
(12, 2, 1, 26),
(13, 2, 1, 29),
(15, 2, 2, 3),
(16, 2, 1, 31),
(17, 2, 3, 32),
(18, 2, 3, 33),
(21, 2, 3, 63),
(22, 2, 3, 65),
(31, 3, 1, 83),
(32, 2, 3, 84),
(33, 2, 1, 86),
(34, 2, 1, 87);

-- --------------------------------------------------------

--
-- Table structure for table `PT_internal_messages`
--

CREATE TABLE `PT_internal_messages` (
  `id` int(11) NOT NULL,
  `message_to` int(11) NOT NULL,
  `message_from` int(11) NOT NULL,
  `message_content` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `PT_internal_messages`
--

INSERT INTO `PT_internal_messages` (`id`, `message_to`, `message_from`, `message_content`) VALUES
(6, 1, 3, 1),
(7, 3, 1, 2),
(8, 1, 3, 3),
(9, 3, 1, 4),
(10, 1, 3, 5),
(11, 1, 3, 6),
(22, 2, 1, 17),
(23, 3, 1, 17),
(24, 43, 1, 17),
(34, 1, 3, 55),
(35, 1, 3, 56),
(37, 1, 3, 66),
(38, 3, 1, 67),
(42, 2, 1, 71),
(43, 3, 1, 71),
(48, 2, 1, 85),
(49, 3, 1, 85),
(50, 1, 3, 88),
(70, 2, 1, 106),
(71, 3, 1, 106),
(72, 43, 1, 106),
(73, 44, 1, 106),
(74, 58, 1, 106),
(75, 60, 1, 106),
(76, 61, 1, 106),
(82, 2, 1, 114),
(83, 3, 1, 114),
(84, 43, 1, 114),
(85, 44, 1, 114),
(86, 58, 1, 114),
(87, 60, 1, 114),
(88, 61, 1, 114),
(92, 2, 1, 120),
(93, 3, 1, 120),
(94, 43, 1, 120),
(95, 44, 1, 120),
(96, 58, 1, 120),
(97, 60, 1, 120),
(98, 61, 1, 120),
(102, 2, 1, 126),
(103, 3, 1, 126),
(104, 43, 1, 126),
(105, 44, 1, 126),
(106, 58, 1, 126),
(107, 60, 1, 126),
(108, 61, 1, 126),
(112, 2, 1, 133),
(113, 3, 1, 133),
(114, 43, 1, 133),
(115, 44, 1, 133),
(116, 58, 1, 133),
(117, 60, 1, 133),
(118, 61, 1, 133),
(121, 2, 1, 139),
(122, 3, 1, 139),
(123, 43, 1, 139),
(124, 44, 1, 139),
(125, 58, 1, 139),
(126, 60, 1, 139),
(127, 61, 1, 139),
(130, 2, 1, 144),
(131, 3, 1, 144),
(132, 43, 1, 144),
(133, 44, 1, 144),
(134, 58, 1, 144),
(135, 60, 1, 144),
(136, 61, 1, 144);

-- --------------------------------------------------------

--
-- Table structure for table `PT_message_content`
--

CREATE TABLE `PT_message_content` (
  `content_id` int(11) NOT NULL,
  `content` varchar(4000) NOT NULL,
  `date` varchar(30) NOT NULL,
  `time` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `PT_message_content`
--

INSERT INTO `PT_message_content` (`content_id`, `content`, `date`, `time`) VALUES
(1, 'Hi Coach, Can\'t make next Tuesday. Hope that is OK.', 'Tuesday 07th Apr 2020', '12:30:56'),
(2, 'Hi Niamh, Yes that is fine!', 'Tuesday 07th Apr 2020', '18:00:32'),
(3, 'Thank you very much!', 'Tuesday 07th Apr 2020', '21:00:00'),
(4, 'You\'re welcome!', 'Wednesday 08th Apr 2020', '16:00:00'),
(5, 'See you some other time then!', 'Wednesday 08th Apr 2020', '17:00:00'),
(6, 'Actually I can make Tuesday now, sorry for mix up', 'Wednesday 08th Apr 2020', '18:00:00'),
(17, 'Hi John, Niamh and Jane!', 'Wednesday 08th Apr 2020', '18:00:00'),
(26, 'Hi group, hope you are all well', 'Wednesday 08th Apr 2020', '21:12:12'),
(27, 'Next week is cancelled guys, sorry for short notice', 'Wednesday 08th Apr 2020', '21:00:34'),
(28, 'Next week is cancelled', 'Thursday 09th Apr 2020', '06:29:59'),
(29, 'Hi guys, next week is cancelled. Sorry for the short notice.', 'Thursday 09th Apr 2020', '06:30:12'),
(31, 'Hi group...again', 'Thursday 09th Apr 2020', '08:31:23'),
(32, 'Hi Coach, hope you are well!', 'Thursday 09th Apr 2020', '09:00:00'),
(33, 'Hi Coach, hope you well again', 'Monday 13th Apr 2020', '12:31:00'),
(52, 'Group 1 has been created', 'Monday 10th Apr 2020', '22:00:00'),
(55, 'Cardio<br>Thu Apr 09 2020 06:00:00 GMT+0100 (British Summer Time)<br>Can I cancel this please?', 'Monday 13th Apr 2020', '12:00:00'),
(56, 'Cancellation Request: <br> Booking title: Cardio<br> Date of event: Thu Apr 09 2020 06:00:00 GMT+0100 (British Summer Time)<br><br>Can I cancel this please?', 'Saturday 11th Apr 2020', '15:00:00'),
(58, 'Cancellation Request: <br> Booking title: Cardio for Group2<br> Date of event: Mon Apr 20 2020 08:00:00 GMT+0100 (British Summer Time)<br><br>cancel please', 'Monday 13th Apr 2020', '18:00:00'),
(60, 'Cancellation Request: <br> Booking title: Cardio for Group2<br> Date of event: Mon Apr 20 2020 08:00:00 GMT+0100 (British Summer Time)<br><br>cancel please\r\n', 'Friday 10th Apr 2020', '21:00:00'),
(61, 'Cancellation Request: <br> Booking title: Cardio for Group2<br> Date of event: Mon Apr 20 2020 08:00:00 GMT+0100 (British Summer Time)<br><br>cancel please\r\n', 'Saturday 11th Apr 2020', '09:30:00'),
(62, 'Cancellation Request: <br> Booking title: Cardio for Group2<br> Date of event: Mon Apr 20 2020 08:00:00 GMT+0100 (British Summer Time)<br><br>cancel', 'Saturday 11th Apr 2020', '23:00:12'),
(63, 'Cancellation Request: <br> Booking title: Cardio for Group2<br> Date of event: Mon Apr 20 2020 08:00:00 GMT+0100 (British Summer Time)<br><br>cancel', 'Sunday 12th Apr 2020', '12:00:00'),
(64, 'Cancellation Request: <br> Booking title: Cardio for Group2<br> Date of event: Mon Apr 20 2020 08:00:00 GMT+0100 (British Summer Time)<br><br>Can we cancel this please', 'Sunday 12th Apr 2020', '06:39:12'),
(65, 'Cancellation Request: <br> Booking title: Cardio for Group2<br> Date of event: Mon Apr 20 2020 08:00:00 GMT+0100 (British Summer Time)<br><br>Can we cancel this please', 'Sunday 12th Apr 2020', '12:23:24'),
(66, 'Cancellation Request: <br> Booking title: Cardio3<br> Date of event: Tue Apr 28 2020 10:00:00 GMT+0100 (British Summer Time)<br><br>I would like to cancel, busy that day', 'Monday 13th Apr 2020', '12:23:23'),
(67, 'No problem cancelled', 'Monday 13th Apr 2020', '17:21:23'),
(71, 'Hi', 'Monday 13th Apr 2020', '17:43:59'),
(83, 'Group 3 has been created', 'Tuesday 14th Apr 2020', '13:52:57'),
(84, 'test123', 'Thursday 16th Apr 2020', '12:03:08'),
(85, 'test1234', 'Thursday 16th Apr 2020', '12:06:37'),
(86, 'test1234', 'Thursday 16th Apr 2020', '12:33:33'),
(87, 'test1234', 'Thursday 16th Apr 2020', '12:32:47'),
(88, 'Hi Joseph, test123', 'Thursday 16th Apr 2020', '12:43:44'),
(90, 'Hi Jason', 'Friday 17th Apr 2020', '17:16:41'),
(92, 'Hi Coach, loved the last session!', 'Friday 17th Apr 2020', '17:24:36'),
(106, 'Hi, closed this weekend for cleaning.', 'Sunday 19th Apr 2020', '22:19:00'),
(114, 'Hi, closed this weekend for cleaning', 'Sunday 19th Apr 2020', '23:03:40'),
(120, 'Hi closed this weekend for cleaning', 'Sunday 19th Apr 2020', '23:28:03'),
(126, 'Hi, closed this weekend for cleaning. Sorry!', 'Monday 20th Apr 2020', '10:26:57'),
(133, 'Hi gym closed this weekend for cleaning. Sorry', 'Monday 20th Apr 2020', '12:00:00'),
(139, 'Hi guys, gym this weekend for cleaning!', 'Monday 20th Apr 2020', '12:16:28'),
(144, 'Hi, gym closed this weekend', 'Monday 20th Apr 2020', '12:44:36');

-- --------------------------------------------------------

--
-- Table structure for table `PT_program_uploads`
--

CREATE TABLE `PT_program_uploads` (
  `program_id` int(11) NOT NULL,
  `program_name` varchar(30) NOT NULL,
  `program_address` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `PT_program_uploads`
--

INSERT INTO `PT_program_uploads` (`program_id`, `program_name`, `program_address`) VALUES
(1, 'Cardio1', '../trainingprograms/Cardio1.docx'),
(2, 'Cardio2', '../trainingprograms/Cardio2.docx'),
(4, 'Weights1', '../trainingprograms/Weights1.docx'),
(5, 'Weights2', '../trainingprograms/Weights2.docx'),
(6, 'Weights3', '../trainingprograms/Weights3.docx'),
(17, 'Cardio3', '../trainingprograms/Cardio3.docx');

-- --------------------------------------------------------

--
-- Table structure for table `PT_single_bookings`
--

CREATE TABLE `PT_single_bookings` (
  `booking_id` int(11) NOT NULL,
  `booking_title` varchar(30) NOT NULL,
  `booking_date` varchar(20) NOT NULL,
  `booking_start_time` varchar(20) NOT NULL,
  `booking_end_time` varchar(20) NOT NULL,
  `booking_for` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `PT_single_bookings`
--

INSERT INTO `PT_single_bookings` (`booking_id`, `booking_title`, `booking_date`, `booking_start_time`, `booking_end_time`, `booking_for`) VALUES
(4, 'Cardio3', '2020-04-15', '09:00:00', '10:00:00', 58),
(8, 'Weights1', '2020-04-17', '06:00:00', '07:00:00', 2),
(9, 'Weights1', '2020-04-23', '08:00:00', '09:00:00', 2),
(10, 'Cardio2', '2020-04-08', '06:00:00', '07:00:00', 2),
(12, 'Weights3', '2020-04-29', '06:00:00', '07:00:00', 3);

-- --------------------------------------------------------

--
-- Table structure for table `PT_users`
--

CREATE TABLE `PT_users` (
  `users_id` int(11) NOT NULL,
  `first_name` varchar(20) NOT NULL,
  `last_name` varchar(20) NOT NULL,
  `email` varchar(20) NOT NULL,
  `pass` varchar(20) NOT NULL,
  `dob` date NOT NULL,
  `gender` varchar(6) NOT NULL,
  `phone_number` varchar(20) NOT NULL,
  `profile_pic` varchar(50) NOT NULL DEFAULT '../imgs/default_profile_pic.png',
  `coach_boolean` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `PT_users`
--

INSERT INTO `PT_users` (`users_id`, `first_name`, `last_name`, `email`, `pass`, `dob`, `gender`, `phone_number`, `profile_pic`, `coach_boolean`) VALUES
(1, 'Joseph ', 'Quinn ', 'jquinn64@qub.ac.uk', '’6Ëuø±îê0É]‹6ª', '1994-06-27', 'Male', '123456789', '../imgs/default_profile_pic.png', 1),
(2, 'John', 'Smith', 'js@hotmail.com', 'ÔzKÎ#\\T±Ðtíç?n', '1970-01-01', 'Male', '987654321', '../imgs/male_profile_pic_2.jpg', 0),
(3, 'Niamh         ', 'Scullion', 'niamhs@live.com    ', 'ÔzKÎ#\\T±Ðtíç?n', '1992-10-29', 'Female', '12345654321', '../imgs/ns_profile_pic.jpg', 0),
(43, 'Jane', 'Smith', 'rs@hotmail.co.uk', 'm`è©h†Ó\\¦;Óâ>%$L', '1987-03-16', 'Female', '12345654321', '../imgs/female_profile_pic_1.jpg', 0),
(44, 'Barry', 'McDonald', 'bm@hotmail.com', '–à¾2=Õ”°GÐQHg', '1978-09-23', 'Male', '12345654321', '../imgs/male_profile_pic_4.jpg', 0),
(58, 'John', 'Johnson', 'jj@live.com', 'ÎTäU*Ã5æ=ÌàŸ', '1992-10-18', 'Male', '12345654321', '../imgs/male_profile_pic_1.jpg', 0),
(60, 'Ronan', 'Rush', 'rush@email.com', 'B¡]Í\'ï50Óö|y†û¾', '1990-04-02', 'Male', '07707152647', '../imgs/Milk_glass.jpg', 0),
(61, 'Adam', 'Hammil', 'ahammil@email.com', 'ÎTäU*Ã5æ=ÌàŸ', '1993-06-21', 'Male', '987654321', '../imgs/de049bf71947406f51000e82bbd85879.jpg', 0),
(73, 'Erin', 'McHugh', 'emchugh@email.com', '’6Ëuø±îê0É]‹6ª', '1990-05-19', 'Female', '12345678910', '../imgs/default_profile_pic.png', 1);

-- --------------------------------------------------------

--
-- Table structure for table `PT_users_weeklyupdate`
--

CREATE TABLE `PT_users_weeklyupdate` (
  `id` int(11) NOT NULL,
  `users_id` int(11) NOT NULL,
  `exercise_type` int(11) NOT NULL,
  `result` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `PT_users_weeklyupdate`
--

INSERT INTO `PT_users_weeklyupdate` (`id`, `users_id`, `exercise_type`, `result`) VALUES
(1, 3, 2, 80),
(2, 3, 3, 100),
(3, 3, 1, 40),
(4, 3, 1, 50),
(5, 3, 2, 90),
(6, 3, 3, 110),
(7, 3, 1, 50),
(8, 3, 2, 90),
(9, 3, 3, 110),
(19, 2, 1, 70),
(20, 2, 2, 150),
(21, 2, 3, 180),
(43, 61, 1, 20),
(44, 61, 2, 258),
(45, 61, 3, 410);

-- --------------------------------------------------------

--
-- Table structure for table `PT_websitecontent`
--

CREATE TABLE `PT_websitecontent` (
  `id` int(11) NOT NULL,
  `content_name` varchar(20) NOT NULL,
  `content_description` varchar(3000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `PT_websitecontent`
--

INSERT INTO `PT_websitecontent` (`id`, `content_name`, `content_description`) VALUES
(1, 'homepagemainline', 'STRENGTH TRAINING SIMPLIFIED'),
(2, 'homepagetagline', 'IRELAND\'S PREMIUM PERSONAL TRAINING SERVICE'),
(3, 'aboutustitle', 'About Us'),
(4, 'aboutusdescription', 'We are Irelands premium personal training service.\r\nWe work with you tirelessly to achieve your goals, by providing both besoke training plans and meal plans.\r\nYour goals could be putting on muscle mass, weight loss or simply to become fitter.\r\nWe currently have locations in both Belfast and Dublin.');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `PT_address`
--
ALTER TABLE `PT_address`
  ADD PRIMARY KEY (`address_id`),
  ADD KEY `users_id` (`users_id`);

--
-- Indexes for table `PT_exercise_types`
--
ALTER TABLE `PT_exercise_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `PT_external_messages`
--
ALTER TABLE `PT_external_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `PT_group`
--
ALTER TABLE `PT_group`
  ADD PRIMARY KEY (`group_id`),
  ADD UNIQUE KEY `users_id` (`users_id`);

--
-- Indexes for table `PT_group_bookings`
--
ALTER TABLE `PT_group_bookings`
  ADD PRIMARY KEY (`booking_id`);

--
-- Indexes for table `PT_group_messages`
--
ALTER TABLE `PT_group_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `message_content` (`message_content`),
  ADD KEY `group_message_from` (`group_message_from`);

--
-- Indexes for table `PT_internal_messages`
--
ALTER TABLE `PT_internal_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `message_to` (`message_to`),
  ADD KEY `message_from` (`message_from`),
  ADD KEY `message_content` (`message_content`);

--
-- Indexes for table `PT_message_content`
--
ALTER TABLE `PT_message_content`
  ADD PRIMARY KEY (`content_id`);

--
-- Indexes for table `PT_program_uploads`
--
ALTER TABLE `PT_program_uploads`
  ADD PRIMARY KEY (`program_id`);

--
-- Indexes for table `PT_single_bookings`
--
ALTER TABLE `PT_single_bookings`
  ADD PRIMARY KEY (`booking_id`),
  ADD KEY `booking_for` (`booking_for`);

--
-- Indexes for table `PT_users`
--
ALTER TABLE `PT_users`
  ADD PRIMARY KEY (`users_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `PT_users_weeklyupdate`
--
ALTER TABLE `PT_users_weeklyupdate`
  ADD PRIMARY KEY (`id`),
  ADD KEY `users_id` (`users_id`),
  ADD KEY `exercise_type` (`exercise_type`);

--
-- Indexes for table `PT_websitecontent`
--
ALTER TABLE `PT_websitecontent`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `PT_address`
--
ALTER TABLE `PT_address`
  MODIFY `address_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT for table `PT_exercise_types`
--
ALTER TABLE `PT_exercise_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `PT_external_messages`
--
ALTER TABLE `PT_external_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `PT_group`
--
ALTER TABLE `PT_group`
  MODIFY `group_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=128;

--
-- AUTO_INCREMENT for table `PT_group_bookings`
--
ALTER TABLE `PT_group_bookings`
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `PT_group_messages`
--
ALTER TABLE `PT_group_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT for table `PT_internal_messages`
--
ALTER TABLE `PT_internal_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=138;

--
-- AUTO_INCREMENT for table `PT_message_content`
--
ALTER TABLE `PT_message_content`
  MODIFY `content_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=148;

--
-- AUTO_INCREMENT for table `PT_program_uploads`
--
ALTER TABLE `PT_program_uploads`
  MODIFY `program_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `PT_single_bookings`
--
ALTER TABLE `PT_single_bookings`
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `PT_users`
--
ALTER TABLE `PT_users`
  MODIFY `users_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=92;

--
-- AUTO_INCREMENT for table `PT_users_weeklyupdate`
--
ALTER TABLE `PT_users_weeklyupdate`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=124;

--
-- AUTO_INCREMENT for table `PT_websitecontent`
--
ALTER TABLE `PT_websitecontent`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `PT_address`
--
ALTER TABLE `PT_address`
  ADD CONSTRAINT `PT_address_ibfk_1` FOREIGN KEY (`users_id`) REFERENCES `PT_users` (`users_id`);

--
-- Constraints for table `PT_group`
--
ALTER TABLE `PT_group`
  ADD CONSTRAINT `PT_group_ibfk_1` FOREIGN KEY (`users_id`) REFERENCES `PT_users` (`users_id`);

--
-- Constraints for table `PT_group_messages`
--
ALTER TABLE `PT_group_messages`
  ADD CONSTRAINT `PT_group_messages_ibfk_2` FOREIGN KEY (`message_content`) REFERENCES `PT_message_content` (`content_id`),
  ADD CONSTRAINT `PT_group_messages_ibfk_3` FOREIGN KEY (`group_message_from`) REFERENCES `PT_users` (`users_id`);

--
-- Constraints for table `PT_internal_messages`
--
ALTER TABLE `PT_internal_messages`
  ADD CONSTRAINT `PT_internal_messages_ibfk_1` FOREIGN KEY (`message_to`) REFERENCES `PT_users` (`users_id`),
  ADD CONSTRAINT `PT_internal_messages_ibfk_2` FOREIGN KEY (`message_from`) REFERENCES `PT_users` (`users_id`),
  ADD CONSTRAINT `PT_internal_messages_ibfk_3` FOREIGN KEY (`message_content`) REFERENCES `PT_message_content` (`content_id`);

--
-- Constraints for table `PT_single_bookings`
--
ALTER TABLE `PT_single_bookings`
  ADD CONSTRAINT `PT_single_bookings_ibfk_1` FOREIGN KEY (`booking_for`) REFERENCES `PT_users` (`users_id`);

--
-- Constraints for table `PT_users_weeklyupdate`
--
ALTER TABLE `PT_users_weeklyupdate`
  ADD CONSTRAINT `PT_users_weeklyupdate_ibfk_1` FOREIGN KEY (`users_id`) REFERENCES `PT_users` (`users_id`),
  ADD CONSTRAINT `PT_users_weeklyupdate_ibfk_2` FOREIGN KEY (`exercise_type`) REFERENCES `PT_exercise_types` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
