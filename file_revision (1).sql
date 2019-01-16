-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 16, 2019 at 12:07 PM
-- Server version: 10.1.35-MariaDB
-- PHP Version: 7.2.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `file_revision`
--

-- --------------------------------------------------------

--
-- Table structure for table `filestorage`
--

CREATE TABLE `filestorage` (
  `id` int(11) NOT NULL,
  `real_name` varchar(128) NOT NULL,
  `storage_name` varchar(25) NOT NULL,
  `created` int(12) NOT NULL,
  `uploader_ip` varchar(45) NOT NULL,
  `storage_engine` int(10) NOT NULL,
  `mime` varchar(64) NOT NULL,
  `filesize` int(10) NOT NULL,
  `parent` varchar(15) NOT NULL,
  `user_id` int(10) NOT NULL,
  `thumbnail` int(1) DEFAULT '0',
  `marked` int(1) NOT NULL DEFAULT '0',
  `trash` int(1) NOT NULL DEFAULT '0',
  `shared` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `filestorage`
--

INSERT INTO `filestorage` (`id`, `real_name`, `storage_name`, `created`, `uploader_ip`, `storage_engine`, `mime`, `filesize`, `parent`, `user_id`, `thumbnail`, `marked`, `trash`, `shared`) VALUES
(1, 'cover letter.docx', '2mJAvZTzNzr4kongvNIO9nSkp', 1547635537, '::1', 1, 'application/vnd.openxmlformats-officedocument.wordprocessingml.d', 13603, '0', 1, 0, 0, 0, 0),
(2, 'angular-6-tutorial.pdf', '99omdBU1QEbUDjQHFzbLs2til', 1547635564, '::1', 1, 'application/pdf', 213781, '1', 1, 0, 0, 0, 0),
(3, 'Capturedddd.PNG', 'CZ0htCFei95ckymmRn5ojphtn', 1547635721, '::1', 1, 'image/png', 15477, '2', 2, 1, 0, 0, 0),
(4, 'Capturedddd.PNG', 'HU39LR4RaZzolmElHUGLflbtA', 1547635729, '::1', 1, 'image/png', 15477, '2', 2, 1, 0, 0, 0),
(5, 'summit_archive_1493863833.pdf', 'lFBWkAhTixCg2AuIzZREFGYcK', 1547635741, '::1', 1, 'application/pdf', 1593056, '2', 2, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `folders`
--

CREATE TABLE `folders` (
  `id` int(11) NOT NULL,
  `parent` int(10) NOT NULL,
  `folder_name` varchar(256) NOT NULL,
  `user_id` int(11) NOT NULL,
  `public_key` varchar(20) NOT NULL,
  `marked` int(1) NOT NULL DEFAULT '0',
  `trash` int(11) NOT NULL DEFAULT '0',
  `shared` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `folders`
--

INSERT INTO `folders` (`id`, `parent`, `folder_name`, `user_id`, `public_key`, `marked`, `trash`, `shared`) VALUES
(1, 0, 'knlk', 1, 'dqRPYNxXxun6aqDheOil', 0, 0, 0),
(2, 0, 'adad', 2, 'q1rsgFYvBx67Bm4hI4K5', 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `payment_durations`
--

CREATE TABLE `payment_durations` (
  `id` int(11) NOT NULL,
  `months` int(3) NOT NULL,
  `discount` decimal(5,2) NOT NULL,
  `enabled` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `payment_services`
--

CREATE TABLE `payment_services` (
  `id` int(11) NOT NULL,
  `display_name` varchar(64) NOT NULL,
  `library_name` varchar(64) NOT NULL,
  `active` int(1) NOT NULL,
  `public_display_name` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `payment_services`
--

INSERT INTO `payment_services` (`id`, `display_name`, `library_name`, `active`, `public_display_name`) VALUES
(1, 'Stripe (Credit Card Payments)', 'stripe', 0, 'Credit Card'),
(2, 'PayPal', 'paypal', 0, 'Pay with PayPal');

-- --------------------------------------------------------

--
-- Table structure for table `payment_transactions`
--

CREATE TABLE `payment_transactions` (
  `id` int(11) NOT NULL,
  `user_id` int(10) NOT NULL,
  `transaction_id` varchar(30) NOT NULL,
  `data` varchar(128) DEFAULT NULL,
  `amount` float(5,2) NOT NULL,
  `status` int(1) NOT NULL,
  `time` int(12) NOT NULL,
  `payment_service` int(10) NOT NULL,
  `duration` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `setting_items`
--

CREATE TABLE `setting_items` (
  `id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `content` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `setting_items`
--

INSERT INTO `setting_items` (`id`, `name`, `content`) VALUES
(1, 'file_maxSize', '137438953472'),
(2, 'emailmsg_registration_subject', 'Thank you for joining FileBEAR'),
(3, 'emailmsg_registration_message', 'Dear {firstname},<br/>\r\nthank you for registering on FileBear.<br/>\r\nPlease click the link bellow to activate your account.<br/>\r\n<a href=\"{link}\">Complete Registration</a>\r\n<br/><br/>\r\nThanks,<br/>\r\nYour FileBEAR Team<br/>'),
(4, 'emailmsg_account_activated_subject', 'Account activated successfuly'),
(5, 'emailmsg_account_activated_message', 'Dear {firstname},<br/>\r\nthank you for registering on FileBear.<br/>\r\nYour account is now activated and ready to use.\r\n<br/><br/>\r\nThanks,<br/>\r\nYour FileBEAR Team<br/>'),
(6, 'email_notifications', '0'),
(7, 'emailmsg_user_forgotpw_subject', 'Forgot Password on FileBear'),
(8, 'emailmsg_user_forgotpw_message', 'Dear {firstname},<br/>\r\nYou can reset your password by clicking the link below<br/>\r\n<a href=\"{link}\">Reset Password</a>\r\n<br/><br/>\r\nThanks,<br/>\r\nYour FileBEAR Team<br/>'),
(9, 'emailmsg_file_shared_subject', 'New File Shared with You'),
(10, 'emailmsg_file_shared_message', 'Dear {firstname},<br/>\r\nA new file was shared with your account.<br/>\r\nPlease check your <a href=\"{shared_link}\">shared directory</a> to get more information.\r\n<br/><br/>\r\nThanks,<br/>\r\nYour FileBEAR Team<br/>'),
(11, 'emailmsg_folder_shared_subject', 'New Folder Shared with You'),
(12, 'emailmsg_folder_shared_message', 'Dear {firstname},<br/>\r\nA new folder was shared with your account.<br/>\r\nPlease check your <a href=\"{shared_link}\">shared directory</a> to get more information.\r\n<br/><br/>\r\nThanks,<br/>\r\nYour FileBEAR Team<br/>'),
(13, 'emailmsg_subscription_purchased_subject', 'Thank you for purchasing a premium subscription'),
(14, 'emailmsg_subscription_purchased_message', 'Dear {firstname},<br/>\r\nthank you for purchasing a premium subscription on FileBear.<br/>\r\nPlease let us know if you have any further questions.<br/>\r\n<br/>\r\nBest regards,<br/>\r\nYour FileBear Team'),
(15, 'emailmsg_subscription_renewalnotice_subject', 'Your subscription is about to end'),
(16, 'emailmsg_subscription_renewalnotice_message', 'Dear {firstname},<br/>\r\nyour subscription on FileBear ends in less then two weeks.<br/>\r\nPlease go to your account settings to renew your subscription!<br/>\r\n\r\nBest regards,<br/>\r\nYour FileBear Team'),
(17, 'emailmsg_subscription_nvalid_subject', 'Your subscription has ended'),
(18, 'emailmsg_subscription_nvalid_message', 'Dear {firstname},<br/>\r\nyour subscription on FileBear has ended.<br/>\r\n<br/>\r\nBest regards,<br/>\r\nYour FileBear Team<br/>'),
(19, 'page_title', 'exam revision'),
(20, 'storage_capacity', '1.25'),
(21, 'storage_max_files', '200'),
(22, 'storage_max_folders', '100');

-- --------------------------------------------------------

--
-- Table structure for table `shared_files`
--

CREATE TABLE `shared_files` (
  `id` int(11) NOT NULL,
  `file_id` int(10) NOT NULL,
  `user_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `shared_files`
--

INSERT INTO `shared_files` (`id`, `file_id`, `user_id`) VALUES
(1, 1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `shared_folders`
--

CREATE TABLE `shared_folders` (
  `id` int(11) NOT NULL,
  `folder_id` int(10) NOT NULL,
  `user_id` int(10) NOT NULL,
  `permission` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `storage_types`
--

CREATE TABLE `storage_types` (
  `id` int(11) NOT NULL,
  `display_name` varchar(64) NOT NULL,
  `library_name` varchar(64) NOT NULL,
  `active` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `storage_types`
--

INSERT INTO `storage_types` (`id`, `display_name`, `library_name`, `active`) VALUES
(1, 'Local Storage', 'local', 1),
(2, 'Amazon AWS S3', 'AwsS3', 0),
(3, 'External FTP Server', 'ftp', 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `firstname` varchar(64) NOT NULL,
  `lastname` varchar(64) NOT NULL,
  `email` varchar(128) NOT NULL,
  `password` varchar(256) NOT NULL,
  `hash` varchar(64) DEFAULT NULL,
  `last_ip` varchar(45) DEFAULT NULL,
  `pw_reset_code` varchar(64) DEFAULT NULL,
  `group_id` int(11) NOT NULL,
  `premium` int(1) DEFAULT NULL,
  `premium_until` int(11) DEFAULT NULL,
  `active` int(1) NOT NULL DEFAULT '0',
  `activation_code` varchar(16) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `firstname`, `lastname`, `email`, `password`, `hash`, `last_ip`, `pw_reset_code`, `group_id`, `premium`, `premium_until`, `active`, `activation_code`) VALUES
(1, 'Kenbrian', 'Muchiri', 'ken.vilgax@gmail.com', '8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92', NULL, NULL, NULL, 1, NULL, NULL, 1, NULL),
(2, 'ken', 'brian', 'admin@gmail.com', '8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92', NULL, NULL, NULL, 3, NULL, NULL, 1, 'IvxagA');

-- --------------------------------------------------------

--
-- Table structure for table `user_groups`
--

CREATE TABLE `user_groups` (
  `id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `default` int(1) NOT NULL DEFAULT '0',
  `admincp` int(1) NOT NULL DEFAULT '0',
  `admin` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_groups`
--

INSERT INTO `user_groups` (`id`, `name`, `default`, `admincp`, `admin`) VALUES
(1, 'Administrator', 0, 1, 1),
(2, 'Staff', 0, 1, 0),
(3, 'Customer', 1, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `user_sessions`
--

CREATE TABLE `user_sessions` (
  `id` int(11) NOT NULL,
  `uid` int(10) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `time` int(12) NOT NULL,
  `current_page` varchar(64) NOT NULL,
  `sid` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_sessions`
--

INSERT INTO `user_sessions` (`id`, `uid`, `ip_address`, `time`, `current_page`, `sid`) VALUES
(4, 1, '::1', 1547635778, '', '2oQIPF1mm1vF11lcfZAj9mDO8BA3adiW4ywQQMf1DkbiXCBzG5ogPtMUrYaZn6db');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `filestorage`
--
ALTER TABLE `filestorage`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `storage_name` (`storage_name`);

--
-- Indexes for table `folders`
--
ALTER TABLE `folders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`);

--
-- Indexes for table `payment_durations`
--
ALTER TABLE `payment_durations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment_services`
--
ALTER TABLE `payment_services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment_transactions`
--
ALTER TABLE `payment_transactions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `setting_items`
--
ALTER TABLE `setting_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`);

--
-- Indexes for table `shared_files`
--
ALTER TABLE `shared_files`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `shared_folders`
--
ALTER TABLE `shared_folders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `storage_types`
--
ALTER TABLE `storage_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_groups`
--
ALTER TABLE `user_groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_sessions`
--
ALTER TABLE `user_sessions`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `filestorage`
--
ALTER TABLE `filestorage`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `folders`
--
ALTER TABLE `folders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `payment_durations`
--
ALTER TABLE `payment_durations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment_services`
--
ALTER TABLE `payment_services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `payment_transactions`
--
ALTER TABLE `payment_transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `setting_items`
--
ALTER TABLE `setting_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `shared_files`
--
ALTER TABLE `shared_files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `shared_folders`
--
ALTER TABLE `shared_folders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `storage_types`
--
ALTER TABLE `storage_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `user_groups`
--
ALTER TABLE `user_groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `user_sessions`
--
ALTER TABLE `user_sessions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
