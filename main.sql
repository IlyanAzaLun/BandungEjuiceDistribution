#
# TABLE STRUCTURE FOR: activity_logs
#

DROP TABLE IF EXISTS `activity_logs`;

CREATE TABLE `activity_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` text NOT NULL,
  `user` text NOT NULL,
  `details` text NOT NULL,
  `ip_address` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=67 DEFAULT CHARSET=latin1;

#
# TABLE STRUCTURE FOR: email_templates
#

DROP TABLE IF EXISTS `email_templates`;

CREATE TABLE `email_templates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `code` text NOT NULL,
  `data` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

INSERT INTO `email_templates` (`id`, `name`, `code`, `data`, `created_at`) VALUES (1, 'Reset Password Template', 'reset_password', '<h1><strong>{company_name}</strong></h1>\r\n\r\n<h3>Click on Reset Link to Proceed : <a href=\"{reset_link}\">Reset Now</a></h3>\r\n', '2020-01-03 16:40:14');

#
# TABLE STRUCTURE FOR: address_information
#

CREATE TABLE `address_information` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `village` varchar(48) NOT NULL,
  `sub_district` varchar(48) NOT NULL,
  `city` varchar(48) NOT NULL,
  `province` varchar(48) NOT NULL,
  `zip` varchar(10) NOT NULL,
  `contact_phone` varchar(48) NOT NULL,
  `contact_mail` varchar(48) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` varchar(48) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` varchar(48) NOT NULL,
  `note` text NOT NULL,
  `is_active` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


#
# TABLE STRUCTURE FOR: customer_information
#

CREATE TABLE `customer_information` (
  `id` int(11) NOT NULL,
  `customer_code` varchar(48) NOT NULL,
  `store_name` varchar(48) NOT NULL,
  `owner_name` varchar(48) NOT NULL,
  `customer_type` varchar(48) NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `note` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` varchar(48) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` varchar(48) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

#
# TABLE STRUCTURE FOR: invoice
#

CREATE TABLE `invoice` (
  `id` int(11) NOT NULL,
  `invoice_code` varchar(48) NOT NULL,
  `total_price` varchar(48) NOT NULL,
  `discounts` varchar(48) NOT NULL,
  `grant_total` varchar(48) NOT NULL,
  `customer` int(11) NOT NULL,
  `supplier` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

#
# TABLE STRUCTURE FOR: items
#

CREATE TABLE `items` (
  `id` int(11) NOT NULL,
  `item_code` varchar(48) CHARACTER SET utf8mb4 NOT NULL,
  `item_name` varchar(48) CHARACTER SET utf8mb4 NOT NULL,
  `category` varchar(48) CHARACTER SET utf8mb4 NOT NULL,
  `brand` varchar(48) CHARACTER SET utf8mb4 NOT NULL,
  `brands` varchar(48) CHARACTER SET utf8mb4 DEFAULT NULL,
  `mg` tinyint(3) NOT NULL,
  `ml` tinyint(3) NOT NULL,
  `vg` tinyint(3) NOT NULL,
  `pg` tinyint(3) NOT NULL,
  `flavour` varchar(48) CHARACTER SET utf8mb4 NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit` char(3) CHARACTER SET utf8mb4 NOT NULL,
  `capital_price` varchar(48) CHARACTER SET utf8mb4 NOT NULL,
  `selling_price` varchar(48) CHARACTER SET utf8mb4 NOT NULL,
  `customs` int(11) NOT NULL,
  `note` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` varchar(48) CHARACTER SET utf8mb4 NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` varchar(48) CHARACTER SET utf8mb4 DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


#
# TABLE STRUCTURE FOR: order
#


CREATE TABLE `order` (
  `id` int(11) NOT NULL,
  `invoice` int(11) NOT NULL,
  `item` int(11) NOT NULL,
  `capital_price` varchar(48) NOT NULL,
  `selling_price` varchar(48) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


#
# TABLE STRUCTURE FOR: permissions
#

DROP TABLE IF EXISTS `permissions`;

CREATE TABLE `permissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` text NOT NULL,
  `code` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=latin1;

INSERT INTO `permissions` (`id`, `title`, `code`) VALUES
(1, 'Users List', 'users_list'),
(2, 'Add Users', 'users_add'),
(3, 'Edit Users', 'users_edit'),
(4, 'Delete Users', 'users_delete'),
(5, 'Users View', 'users_view'),
(6, 'Activity Logs List', 'activity_log_list'),
(7, 'Acivity Log View', 'activity_log_view'),
(8, 'Roles List', 'roles_list'),
(9, 'Add Roles', 'roles_add'),
(10, 'Edit Roles', 'roles_edit'),
(11, 'Permissions List', 'permissions_list'),
(12, 'Add Permissions', 'permissions_add'),
(13, 'Permissions Edit', 'permissions_edit'),
(14, 'Delete Permissions', 'permissions_delete'),
(15, 'Company Settings', 'company_settings'),
(16, 'Backup', 'backup_db'),
(17, 'Manage Email Templates', 'email_templates'),
(18, 'General Settings', 'general_settings'),
(19, 'Invoice Purchase List', 'purchase_list'),
(20, 'Invoice purchasing create', 'purchase_create'),
(21, 'Items List', 'items_list'),
(22, 'Add Items', 'items_add'),
(23, 'Edit Items', 'items_edit'),
(24, 'Delete Items', 'items_delete'),
(25, 'Info Item', 'items_info'),
(28, 'Upload file', 'upload_file'),
(29, 'Item Empty', 'items_truncate'),
(30, 'Data Customer', 'customer_list'),
(31, 'Add Customer', 'customer_add'),
(32, 'Add Address', 'address_add'),
(33, 'List Address', 'address_list'),
(34, 'Edit Customer', 'customer_edit'),
(35, 'Edit Address', 'address_edit');


#
# TABLE STRUCTURE FOR: role_permissions
#

DROP TABLE IF EXISTS `role_permissions`;

CREATE TABLE `role_permissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role` int(11) NOT NULL,
  `permission` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=latin1;

INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES
(1, 1, 'users_list'),
(2, 1, 'users_add'),
(3, 1, 'users_edit'),
(4, 1, 'users_delete'),
(5, 1, 'users_view'),
(6, 1, 'activity_log_view'),
(7, 1, 'roles_list'),
(8, 1, 'roles_add'),
(9, 1, 'roles_edit'),
(10, 1, 'permissions_list'),
(11, 1, 'permissions_add'),
(12, 1, 'permissions_edit'),
(13, 1, 'permissions_delete'),
(14, 1, 'company_settings'),
(15, 1, 'activity_log_list'),
(16, 1, 'email_templates'),
(17, 1, 'general_settings'),
(18, 1, 'backup_db'),
(19, 2, 'users_list'),
(20, 2, 'users_add'),
(21, 2, 'users_edit'),
(22, 2, 'users_delete'),
(23, 2, 'users_view'),
(24, 2, 'roles_list'),
(25, 2, 'roles_add'),
(26, 2, 'roles_edit'),
(27, 2, 'permissions_list'),
(28, 2, 'permissions_add'),
(29, 2, 'permissions_edit'),
(30, 2, 'permissions_delete'),
(37, 1, 'purchase_create'),
(38, 1, 'purchase_list'),
(39, 1, 'items_list'),
(40, 1, 'items_add'),
(42, 1, 'items_delete'),
(43, 1, 'items_edit'),
(45, 1, 'items_info'),
(46, 1, 'upload_file'),
(47, 1, 'items_truncate'),
(48, 1, 'customer_list'),
(49, 1, 'customer_add'),
(50, 1, 'address_add'),
(51, 1, 'address_list'),
(52, 1, 'customer_edit'),
(53, 1, 'address_edit'),
(54, 1, 'purchase_add');


#
# TABLE STRUCTURE FOR: roles
#

DROP TABLE IF EXISTS `roles`;

CREATE TABLE `roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

INSERT INTO `roles` (`id`, `title`) VALUES (1, 'Admin');
INSERT INTO `roles` (`id`, `title`) VALUES (2, 'Manager');


#
# TABLE STRUCTURE FOR: settings
#

DROP TABLE IF EXISTS `settings`;

CREATE TABLE `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `key` text NOT NULL,
  `value` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

INSERT INTO `settings` (`id`, `key`, `value`, `created_at`) VALUES
(1, 'company_name', 'B.E.D', '2018-06-21 10:37:59'),
(2, 'company_email', 'iyang_agung_s@protonmail.com', '2018-07-11 04:09:58'),
(3, 'timezone', 'Asia/Jakarta', '2018-07-15 12:54:17'),
(4, 'login_theme', '1', '2019-06-06 07:04:28'),
(5, 'date_format', 'd F, Y', '2020-01-03 18:31:45'),
(6, 'datetime_format', 'h:i a - d M, Y ', '2020-01-03 18:32:24'),
(7, 'google_recaptcha_enabled', '0', '2020-01-04 17:44:03'),
(8, 'google_recaptcha_sitekey', '6LdIWswUAAAAAMRp6xt2wBu7V59jUvZvKWf_rbJc', '2020-01-04 17:44:17'),
(9, 'google_recaptcha_secretkey', '6LdIWswUAAAAAIsdboq_76c63PHFsOPJHNR-z-75', '2020-01-04 17:44:40'),
(10, 'bg_img_type', 'jpeg', '2020-01-06 16:53:33'),
(11, 'default_lang', 'en', '2021-04-12 08:53:06'),
(12, 'company_icon', '1642562442.png', '2022-01-12 07:00:58');

#
# TABLE STRUCTURE FOR: users
#

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `username` text NOT NULL,
  `email` text NOT NULL,
  `password` text NOT NULL,
  `phone` text NOT NULL,
  `address` longtext NOT NULL,
  `last_login` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `role` int(11) NOT NULL,
  `reset_token` text NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `is_logged` TINYINT(1) NOT NULL DEFAULT '0',
  `img_type` varchar(3000) NOT NULL DEFAULT 'png',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

INSERT INTO `users` (`id`, `name`, `username`, `email`, `password`, `phone`, `address`, `last_login`, `role`, `reset_token`, `status`, `img_type`, `created_at`, `updated_at`) VALUES
(1, 'Iyang Agung Supriatna', 'admin', 'admin@administrator.com', 'e57d520851be5ab85f56880afa8cb65717bbe238430a00de39600d77e855ef82', '6289862327', 'Cipanteneun, Licin, Cimalaka, Sumedang, Jawabarat', '2022-01-29 04:01:23', 1, '', 1, 'png', '2018-06-27 11:30:16', '0000-00-00 00:00:00'),
(2, 'Esa', 'esa_shipping', 'Esa@shipping.com', '740062676a3134f36f0f0fc90152e91a417d0d363bf2441ebd6a5103f562dacf', '0', '0', '2022-01-27 10:42:29', 5, '', 1, 'png', '2022-01-08 17:58:13', '0000-00-00 00:00:00'),
(3, 'Fauzi', 'fauzi_marketing', 'fauzi@marketing', 'e2a530e251d3675034d23f5c5f87f54ec3182a088ba7d13350824794f8e6b76e', '0', '0', '2022-01-10 00:01:35', 3, '', 1, 'png', '2022-01-10 02:04:27', '0000-00-00 00:00:00');


