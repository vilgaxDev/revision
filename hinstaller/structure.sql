SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;


CREATE TABLE IF NOT EXISTS `filestorage` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
  `shared` int(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `storage_name` (`storage_name`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `folders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent` int(10) NOT NULL,
  `folder_name` varchar(256) NOT NULL,
  `user_id` int(11) NOT NULL,
  `public_key` varchar(20) NOT NULL,
  `marked` int(1) NOT NULL DEFAULT '0',
  `trash` int(11) NOT NULL DEFAULT '0',
  `shared` int(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `payment_durations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `months` int(3) NOT NULL,
  `discount` decimal(5,2) NOT NULL,
  `enabled` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `payment_services` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `display_name` varchar(64) NOT NULL,
  `library_name` varchar(64) NOT NULL,
  `active` int(1) NOT NULL,
  `public_display_name` varchar(64) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

INSERT INTO `payment_services` (`id`, `display_name`, `library_name`, `active`, `public_display_name`) VALUES
(1, 'Stripe (Credit Card Payments)', 'stripe', 0, 'Credit Card'),
(2, 'PayPal', 'paypal', 0, 'Pay with PayPal');

CREATE TABLE IF NOT EXISTS `payment_transactions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL,
  `transaction_id` varchar(30) NOT NULL,
  `data` varchar(128) DEFAULT NULL,
  `amount` float(5,2) NOT NULL,
  `status` int(1) NOT NULL,
  `time` int(12) NOT NULL,
  `payment_service` int(10) NOT NULL,
  `duration` int(2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `setting_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `content` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;
INSERT INTO `setting_items` (`id`, `name`, `content`) VALUES (NULL, 'file_maxSize', '137438953472');
INSERT INTO `setting_items` (`name`, `content`) VALUES
('emailmsg_registration_subject', 'Thank you for joining FileBEAR'),
('emailmsg_registration_message', 'Dear {firstname},<br/>\r\nthank you for registering on FileBear.<br/>\r\nPlease click the link bellow to activate your account.<br/>\r\n<a href=\"{link}\">Complete Registration</a>\r\n<br/><br/>\r\nThanks,<br/>\r\nYour FileBEAR Team<br/>'),
('emailmsg_account_activated_subject', 'Account activated successfuly'),
('emailmsg_account_activated_message', 'Dear {firstname},<br/>\r\nthank you for registering on FileBear.<br/>\r\nYour account is now activated and ready to use.\r\n<br/><br/>\r\nThanks,<br/>\r\nYour FileBEAR Team<br/>'),
('email_notifications', '0'),
('emailmsg_user_forgotpw_subject', 'Forgot Password on FileBear'),
('emailmsg_user_forgotpw_message', 'Dear {firstname},<br/>\r\nYou can reset your password by clicking the link below<br/>\r\n<a href=\"{link}\">Reset Password</a>\r\n<br/><br/>\r\nThanks,<br/>\r\nYour FileBEAR Team<br/>'),
('emailmsg_file_shared_subject', 'New File Shared with You'),
('emailmsg_file_shared_message', 'Dear {firstname},<br/>\r\nA new file was shared with your account.<br/>\r\nPlease check your <a href=\"{shared_link}\">shared directory</a> to get more information.\r\n<br/><br/>\r\nThanks,<br/>\r\nYour FileBEAR Team<br/>'),
('emailmsg_folder_shared_subject', 'New Folder Shared with You'),
('emailmsg_folder_shared_message', 'Dear {firstname},<br/>\r\nA new folder was shared with your account.<br/>\r\nPlease check your <a href=\"{shared_link}\">shared directory</a> to get more information.\r\n<br/><br/>\r\nThanks,<br/>\r\nYour FileBEAR Team<br/>'),
('emailmsg_subscription_purchased_subject', 'Thank you for purchasing a premium subscription'),
('emailmsg_subscription_purchased_message', 'Dear {firstname},<br/>\r\nthank you for purchasing a premium subscription on FileBear.<br/>\r\nPlease let us know if you have any further questions.<br/>\r\n<br/>\r\nBest regards,<br/>\r\nYour FileBear Team'),
('emailmsg_subscription_renewalnotice_subject', 'Your subscription is about to end'),
('emailmsg_subscription_renewalnotice_message', 'Dear {firstname},<br/>\r\nyour subscription on FileBear ends in less then two weeks.<br/>\r\nPlease go to your account settings to renew your subscription!<br/>\r\n\r\nBest regards,<br/>\r\nYour FileBear Team'),
('emailmsg_subscription_nvalid_subject', 'Your subscription has ended'),
('emailmsg_subscription_nvalid_message', 'Dear {firstname},<br/>\r\nyour subscription on FileBear has ended.<br/>\r\n<br/>\r\nBest regards,<br/>\r\nYour FileBear Team<br/>');

CREATE TABLE IF NOT EXISTS `shared_files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `file_id` int(10) NOT NULL,
  `user_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `shared_folders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `folder_id` int(10) NOT NULL,
  `user_id` int(10) NOT NULL,
  `permission` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `storage_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `display_name` varchar(64) NOT NULL,
  `library_name` varchar(64) NOT NULL,
  `active` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

INSERT INTO `storage_types` (`id`, `display_name`, `library_name`, `active`) VALUES
(1, 'Local Storage', 'local', 1),
(2, 'Amazon AWS S3', 'AwsS3', 0),
(3, 'External FTP Server', 'ftp', 0);

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
  `activation_code` varchar(16) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `user_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `default` int(1) NOT NULL DEFAULT '0',
  `admincp` int(1) NOT NULL DEFAULT '0',
  `admin` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

INSERT INTO `user_groups` (`id`, `name`, `default`, `admincp`, `admin`) VALUES
(1, 'Administrator', 0, 1, 1),
(2, 'Staff', 0, 1, 0),
(3, 'Customer', 1, 0, 0);

CREATE TABLE IF NOT EXISTS `user_sessions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(10) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `time` int(12) NOT NULL,
  `current_page` varchar(64) NOT NULL,
  `sid` varchar(64) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
