-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 05, 2022 at 06:55 AM
-- Server version: 10.4.22-MariaDB
-- PHP Version: 7.3.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_bandung-ejuice-distribution`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` int(11) NOT NULL,
  `title` mediumtext NOT NULL,
  `user` mediumtext NOT NULL,
  `details` mediumtext NOT NULL,
  `ip_address` mediumtext NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `address_information`
--

CREATE TABLE `address_information` (
  `id` int(11) NOT NULL,
  `customer_code` varchar(48) NOT NULL,
  `address` text DEFAULT NULL,
  `village` varchar(48) DEFAULT NULL,
  `sub_district` varchar(48) DEFAULT NULL,
  `city` varchar(48) DEFAULT NULL,
  `province` varchar(48) DEFAULT NULL,
  `zip` varchar(10) DEFAULT NULL,
  `contact_phone` varchar(48) DEFAULT NULL,
  `contact_mail` varchar(48) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` varchar(48) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` varchar(48) DEFAULT NULL,
  `note` text DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `bank_information`
--

CREATE TABLE `bank_information` (
  `id` int(11) NOT NULL,
  `name` varchar(48) NOT NULL,
  `no_account` varchar(48) NOT NULL,
  `own_by` varchar(48) NOT NULL,
  `balance` varchar(48) DEFAULT '0',
  `description` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` varchar(48) NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` varchar(48) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `customer_information`
--

CREATE TABLE `customer_information` (
  `id` int(11) NOT NULL,
  `customer_code` varchar(48) NOT NULL,
  `store_name` varchar(48) DEFAULT NULL,
  `owner_name` varchar(48) DEFAULT NULL,
  `customer_type` varchar(48) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL,
  `contact_us` varchar(48) DEFAULT NULL,
  `note` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` varchar(48) NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` varchar(48) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `delivery_head`
--

CREATE TABLE `delivery_head` (
  `id` int(11) NOT NULL,
  `delivery_code` varchar(48) DEFAULT NULL,
  `from_address` text DEFAULT NULL,
  `destination_address` text DEFAULT NULL,
  `pack_by` varchar(48) DEFAULT NULL,
  `pack` varchar(48) DEFAULT NULL,
  `weight` varchar(48) DEFAULT NULL,
  `shipping_cost` varchar(48) DEFAULT NULL,
  `customer_code` varchar(48) DEFAULT NULL,
  `store_name` varchar(48) DEFAULT NULL,
  `expedition` varchar(48) DEFAULT NULL,
  `services_expedition` varchar(48) DEFAULT NULL,
  `invoice_reference` varchar(48) DEFAULT NULL,
  `type_invoice` varchar(48) DEFAULT NULL,
  `note` text DEFAULT NULL,
  `is_controlled_by` varchar(48) DEFAULT NULL,
  `is_delivered` tinyint(1) DEFAULT NULL,
  `is_cancelled` tinyint(1) DEFAULT NULL,
  `is_shipping_cost` tinyint(1) DEFAULT NULL,
  `cancel_note` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` varchar(48) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` varchar(48) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `delivery_list_item`
--

CREATE TABLE `delivery_list_item` (
  `id` int(11) NOT NULL,
  `delivery_code` varchar(48) NOT NULL,
  `item_id` int(11) NOT NULL,
  `item_code` varchar(48) NOT NULL,
  `item_name` text NOT NULL,
  `item_quantity` varchar(48) NOT NULL,
  `item_unit` varchar(11) NOT NULL,
  `item_total_weight` int(11) NOT NULL,
  `item_description` text NOT NULL,
  `control_by` int(11) NOT NULL,
  `is_canceled` tinyint(1) NOT NULL,
  `created_by` varchar(48) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_by` varchar(48) NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `email_templates`
--

CREATE TABLE `email_templates` (
  `id` int(11) NOT NULL,
  `name` mediumtext NOT NULL,
  `code` mediumtext NOT NULL,
  `data` mediumtext NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `email_templates`
--

INSERT INTO `email_templates` (`id`, `name`, `code`, `data`, `created_at`) VALUES
(1, 'Reset Password Template', 'reset_password', '<h1><strong>{company_name}</strong></h1>\r\n\r\n<h3>Click on Reset Link to Proceed : <a href=\"{reset_link}\">Reset Now</a></h3>\r\n', '2020-01-03 09:40:14');

-- --------------------------------------------------------

--
-- Table structure for table `expedition`
--

CREATE TABLE `expedition` (
  `id` int(11) NOT NULL,
  `expedition_name` text NOT NULL,
  `services_expedition` text NOT NULL,
  `note` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` varchar(48) NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` varchar(48) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `fifo_items`
--

CREATE TABLE `fifo_items` (
  `id` int(11) NOT NULL,
  `invoice_code` varchar(48) DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  `item_code` varchar(48) DEFAULT NULL,
  `item_name` text DEFAULT NULL,
  `item_capital_price` varchar(48) NOT NULL DEFAULT '0',
  `item_quantity` varchar(48) NOT NULL DEFAULT '0',
  `item_unit` varchar(11) DEFAULT NULL,
  `item_discount` varchar(48) NOT NULL DEFAULT '0',
  `total_price` varchar(48) NOT NULL DEFAULT '0',
  `customer_code` varchar(48) DEFAULT NULL,
  `is_readable` tinyint(1) DEFAULT 1,
  `is_cancelled` tinyint(1) DEFAULT 0,
  `parent` varchar(48) DEFAULT NULL,
  `reference_purchase` varchar(48) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` varchar(48) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` varchar(48) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `invoice_payment`
--

CREATE TABLE `invoice_payment` (
  `id` int(11) NOT NULL,
  `invoice_code` varchar(48) DEFAULT NULL,
  `date_start` timestamp NULL DEFAULT NULL,
  `date_due` timestamp NULL DEFAULT NULL,
  `customer_code` varchar(48) DEFAULT NULL,
  `grand_total` varchar(48) DEFAULT '0',
  `payup` varchar(48) DEFAULT '0' COMMENT 'want to be paid',
  `leftovers` varchar(48) DEFAULT '0' COMMENT 'remnant of debt or owed',
  `status_payment` int(1) NOT NULL DEFAULT 0 COMMENT '0: "deposit", 1: "withdraw"',
  `payment_type` varchar(48) DEFAULT NULL COMMENT 'cash, transfer, ...',
  `bank_id` int(11) DEFAULT NULL COMMENT 'destination bank to withdrawl or deposit, a balance.',
  `is_cancelled` int(1) DEFAULT NULL,
  `cancel_note` text DEFAULT NULL,
  `created_by` varchar(48) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_by` varchar(48) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `description` text DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `invoice_purchasing`
--

CREATE TABLE `invoice_purchasing` (
  `id` int(11) NOT NULL,
  `invoice_code` varchar(48) DEFAULT NULL,
  `have_a_child` varchar(48) DEFAULT NULL,
  `total_price` varchar(48) NOT NULL DEFAULT '0',
  `discounts` varchar(48) NOT NULL DEFAULT '0',
  `shipping_cost` varchar(48) NOT NULL DEFAULT '0',
  `other_cost` varchar(48) NOT NULL DEFAULT '0',
  `grand_total` varchar(48) NOT NULL DEFAULT '0',
  `supplier` varchar(48) DEFAULT NULL,
  `payment_type` varchar(48) DEFAULT NULL COMMENT 'cash, credit',
  `status_payment` varchar(48) DEFAULT NULL COMMENT '0: "debt", 1: "paid off"',
  `date_start` timestamp NULL DEFAULT NULL,
  `date_due` timestamp NULL DEFAULT NULL,
  `note` text DEFAULT NULL,
  `expedition` varchar(48) DEFAULT NULL,
  `services_expedition` varchar(48) DEFAULT NULL,
  `pack_by` varchar(48) DEFAULT NULL,
  `pack` varchar(48) DEFAULT NULL,
  `weight` varchar(48) DEFAULT '0',
  `transaction_destination` varchar(48) DEFAULT NULL,
  `type_payment_shipping` varchar(48) DEFAULT NULL,
  `note_destination` text DEFAULT NULL,
  `transaction_source` varchar(48) DEFAULT NULL,
  `is_cancelled` tinyint(1) NOT NULL DEFAULT 0,
  `cancel_note` text DEFAULT NULL,
  `is_consignment` tinyint(1) NOT NULL DEFAULT 0,
  `is_child` tinyint(1) NOT NULL DEFAULT 0,
  `is_transaction` tinyint(1) NOT NULL DEFAULT 1,
  `is_shipping_cost` tinyint(1) DEFAULT 1 COMMENT 'status shipping cost is added to invoice	',
  `is_controlled_by` varchar(48) DEFAULT NULL,
  `is_delivered` tinyint(1) DEFAULT NULL,
  `receipt_code` varchar(48) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` varchar(48) DEFAULT NULL,
  `updated_by` varchar(48) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `invoice_selling`
--

CREATE TABLE `invoice_selling` (
  `id` int(11) NOT NULL,
  `invoice_code` varchar(48) DEFAULT NULL,
  `have_a_child` varchar(48) DEFAULT NULL,
  `total_price` varchar(48) NOT NULL DEFAULT '0',
  `discounts` varchar(48) NOT NULL DEFAULT '0',
  `shipping_cost` varchar(48) NOT NULL DEFAULT '0',
  `other_cost` varchar(48) NOT NULL DEFAULT '0',
  `grand_total` varchar(48) NOT NULL DEFAULT '0',
  `customer` varchar(48) DEFAULT NULL,
  `payment_type` varchar(48) DEFAULT NULL COMMENT 'cash, credit',
  `status_payment` varchar(48) DEFAULT NULL COMMENT '0: "owed", 1: "paid off"',
  `date_start` timestamp NULL DEFAULT NULL,
  `date_due` timestamp NULL DEFAULT NULL,
  `note` text DEFAULT NULL,
  `expedition` varchar(48) DEFAULT NULL,
  `services_expedition` varchar(48) DEFAULT NULL,
  `pack_by` varchar(48) DEFAULT NULL COMMENT 'who is packing, get information from users',
  `pack` varchar(48) DEFAULT NULL,
  `weight` varchar(48) DEFAULT '0',
  `transaction_destination` varchar(48) DEFAULT NULL,
  `type_payment_shipping` varchar(48) DEFAULT NULL,
  `note_destination` text DEFAULT NULL,
  `is_controlled_by` varchar(48) DEFAULT NULL,
  `is_delivered` tinyint(1) DEFAULT NULL,
  `is_cancelled` tinyint(1) NOT NULL DEFAULT 0,
  `is_child` tinyint(1) NOT NULL DEFAULT 0,
  `is_transaction` tinyint(1) NOT NULL DEFAULT 1,
  `is_have` int(11) DEFAULT NULL,
  `is_shipping_cost` tinyint(1) DEFAULT 1 COMMENT 'status shipping cost is added to invoice	',
  `cancel_note` text DEFAULT NULL,
  `reference_order` varchar(48) DEFAULT NULL,
  `receipt_code` varchar(48) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` varchar(48) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` varchar(48) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `invoice_transaction_list_item`
--

CREATE TABLE `invoice_transaction_list_item` (
  `id` int(11) NOT NULL,
  `index_list` int(11) DEFAULT NULL,
  `invoice_code` varchar(48) DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  `item_code` varchar(48) DEFAULT NULL,
  `item_name` text DEFAULT NULL,
  `item_capital_price` varchar(48) NOT NULL DEFAULT '0',
  `item_selling_price` varchar(48) NOT NULL DEFAULT '0',
  `item_current_quantity` varchar(48) NOT NULL DEFAULT '0',
  `item_quantity` varchar(48) NOT NULL DEFAULT '0',
  `item_unit` varchar(11) DEFAULT NULL,
  `item_total_weight` varchar(48) DEFAULT '0',
  `item_discount` varchar(48) NOT NULL DEFAULT '0',
  `total_price` varchar(48) NOT NULL DEFAULT '0',
  `item_status` varchar(48) DEFAULT NULL COMMENT 'status in or out',
  `item_description` text DEFAULT NULL,
  `customer_code` varchar(48) DEFAULT NULL,
  `control_by` int(11) DEFAULT NULL COMMENT 'Quality Controll By',
  `is_cancelled` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` varchar(48) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` varchar(48) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `id` int(11) NOT NULL,
  `item_code` varchar(48) NOT NULL,
  `item_name` text NOT NULL,
  `category` varchar(48) NOT NULL,
  `brand` varchar(48) NOT NULL,
  `brands` varchar(48) DEFAULT NULL,
  `mg` tinyint(3) DEFAULT NULL,
  `ml` tinyint(3) DEFAULT NULL,
  `vg` tinyint(3) DEFAULT NULL,
  `pg` tinyint(3) DEFAULT NULL,
  `flavour` varchar(48) DEFAULT NULL,
  `unit` char(3) DEFAULT NULL,
  `weight` varchar(48) DEFAULT NULL,
  `quantity` varchar(48) NOT NULL,
  `broken` varchar(48) NOT NULL DEFAULT '0' COMMENT 'total item damaged',
  `capital_price` varchar(48) DEFAULT NULL,
  `selling_price` varchar(48) DEFAULT NULL,
  `shadow_selling_price` varchar(48) DEFAULT '0',
  `customs` int(11) DEFAULT NULL,
  `note` text DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` int(11) NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `items_history`
--

CREATE TABLE `items_history` (
  `id` int(11) NOT NULL,
  `item_id` int(11) DEFAULT NULL,
  `item_code` varchar(48) DEFAULT NULL,
  `item_name` text DEFAULT NULL,
  `item_quantity` varchar(48) NOT NULL DEFAULT '0',
  `item_order_quantity` varchar(48) NOT NULL DEFAULT '0',
  `item_unit` varchar(3) DEFAULT NULL,
  `item_capital_price` varchar(48) NOT NULL DEFAULT '0',
  `item_selling_price` varchar(48) NOT NULL DEFAULT '0',
  `item_discount` varchar(48) DEFAULT '0',
  `total_price` varchar(48) DEFAULT '0',
  `status_type` varchar(48) DEFAULT NULL,
  `status_transaction` varchar(48) DEFAULT NULL,
  `invoice_reference` varchar(48) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` varchar(48) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` varchar(48) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `order_purchase`
--

CREATE TABLE `order_purchase` (
  `id` int(11) NOT NULL,
  `invoice` varchar(48) DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  `item_code` varchar(48) DEFAULT NULL,
  `item_name` text DEFAULT NULL,
  `item_order_quantity` varchar(48) NOT NULL DEFAULT '0',
  `item_unit` char(3) DEFAULT NULL,
  `capital_price` varchar(48) NOT NULL DEFAULT '0',
  `selling_price` varchar(48) NOT NULL DEFAULT '0',
  `item_discount` varchar(48) NOT NULL DEFAULT '0',
  `total_price` varchar(48) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` varchar(48) DEFAULT NULL,
  `updated_by` varchar(48) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `order_sale`
--

CREATE TABLE `order_sale` (
  `id` int(11) NOT NULL,
  `order_code` varchar(48) DEFAULT NULL,
  `total_price` varchar(48) NOT NULL DEFAULT '0',
  `discounts` varchar(48) NOT NULL DEFAULT '0',
  `shipping_cost` varchar(48) NOT NULL DEFAULT '0',
  `other_cost` varchar(48) NOT NULL DEFAULT '0',
  `grand_total` varchar(48) NOT NULL DEFAULT '0',
  `customer` varchar(48) DEFAULT NULL,
  `payment_type` varchar(48) DEFAULT NULL,
  `note` text DEFAULT NULL,
  `is_cancelled` tinyint(1) NOT NULL DEFAULT 0,
  `is_confirmed` tinyint(1) DEFAULT NULL,
  `is_created` tinyint(1) NOT NULL DEFAULT 0,
  `is_have` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` varchar(48) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` varchar(48) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `order_sale_list_item`
--

CREATE TABLE `order_sale_list_item` (
  `id` int(11) NOT NULL,
  `index_list` int(11) DEFAULT NULL,
  `order_code` varchar(48) DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  `item_code` varchar(48) DEFAULT NULL,
  `item_name` text DEFAULT NULL,
  `item_capital_price` varchar(48) NOT NULL DEFAULT '0',
  `item_selling_price` varchar(48) NOT NULL DEFAULT '0',
  `item_quantity` varchar(48) NOT NULL DEFAULT '0',
  `item_order_quantity` varchar(48) NOT NULL DEFAULT '0',
  `item_unit` varchar(48) NOT NULL DEFAULT current_timestamp(),
  `item_discount` varchar(48) NOT NULL DEFAULT '0',
  `item_total_price` varchar(48) NOT NULL DEFAULT '0',
  `status_available` tinyint(1) DEFAULT NULL,
  `item_description` text NOT NULL,
  `customer_code` varchar(48) NOT NULL,
  `is_cancelled` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` varchar(48) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` varchar(48) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` int(11) NOT NULL,
  `title` mediumtext NOT NULL,
  `code` mediumtext DEFAULT NULL,
  `description` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `title`, `code`, `description`) VALUES
(1, 'Users List', 'users_list', 'test\r\n'),
(2, 'Add Users', 'users_add', ''),
(3, 'Edit Users', 'users_edit', ''),
(4, 'Delete Users', 'users_delete', ''),
(5, 'Users View', 'users_view', ''),
(6, 'Activity Logs List', 'activity_log_list', ''),
(7, 'Acivity Log View', 'activity_log_view', ''),
(8, 'Roles List', 'roles_list', ''),
(9, 'Add Roles', 'roles_add', ''),
(10, 'Edit Roles', 'roles_edit', ''),
(11, 'Permissions List', 'permissions_list', ''),
(12, 'Add Permissions', 'permissions_add', ''),
(13, 'Permissions Edit', 'permissions_edit', ''),
(14, 'Delete Permissions', 'permissions_delete', ''),
(15, 'Company Settings', 'company_settings', ''),
(16, 'Backup', 'backup_db', ''),
(17, 'Manage Email Templates', 'email_templates', ''),
(18, 'General Settings', 'general_settings', ''),
(19, 'Invoice Purchase List', 'purchase_list', ''),
(20, 'Invoice Purchasing Create', 'purchase_create', ''),
(21, 'Items List', 'items_list', ''),
(22, 'Add Items', 'items_add', ''),
(23, 'Edit Items', 'items_edit', ''),
(24, 'Delete Items', 'items_delete', ''),
(25, 'Info Item', 'items_info', ''),
(28, 'Upload file', 'upload_file', ''),
(29, 'Item Empty', 'items_truncate', ''),
(30, 'Data Customer', 'customer_list', ''),
(31, 'Add Customer', 'customer_add', ''),
(32, 'Add Address', 'address_add', ''),
(33, 'List Address', 'address_list', ''),
(34, 'Edit Customer', 'customer_edit', ''),
(35, 'Edit Address', 'address_edit', ''),
(36, 'Data Supplier', 'supplier_list', ''),
(37, 'Add Supplier', 'supplier_add', ''),
(38, 'Edit Supplier', 'supplier_edit', ''),
(39, 'Invoice Purchasing Edit', 'purchase_edit', ''),
(40, 'Invoice Purchasing Info', 'purchase_info', ''),
(41, 'Invoice Purchase Returns', 'purchase_returns', ''),
(42, 'Invoice Purchase Returns Edit', 'purchase_returns_edit', ''),
(43, 'Invoice Purchase Returns Information', 'purchase_returns_info', ''),
(44, 'Invoice Purchase Returns Cancel', 'purchase_returns_cancel', ''),
(45, 'Invoice Purchase Payment List', 'purchase_payment_list', ''),
(46, 'Invoice Purchase Payment', 'purchase_payment', ''),
(47, 'Selling list', 'sale_list', ''),
(48, 'Order List', 'order_list', ''),
(49, 'Expedition List', 'expedition_list', ''),
(50, 'Expedition Create', 'expedition_create', ''),
(51, 'Expedition Update', 'expedition_update', ''),
(52, 'Expedition Delete', 'expedition_delete', ''),
(53, 'Create Order', 'order_create', ''),
(54, 'Edit Order', 'order_edit', ''),
(55, 'Data Information Invoice Sale', 'warehouse_order_list', 'Admin, Quality Control, Shipping: to show all Invoice SALE'),
(56, 'Warehouse Order Validation Available', 'warehouse_order_validation_available', ''),
(57, 'Sale Create', 'sale_create', ''),
(58, 'Shipper List Transaction', 'shipper_transaction_list', ''),
(59, 'Delivered Report', 'report_delivered', 'List information Delevered Pack'),
(60, 'Invoice Sales Returns', 'sale_returns', ''),
(61, 'Invoice Sales Returns Edit', 'sale_returns_edit', ''),
(62, 'Account Bank List', 'account_bank_list', ''),
(63, 'Add New Account Bank', 'account_bank_add', ''),
(64, 'Update Account Bank', 'account_bank_edit', ''),
(65, 'Sale Return List', 'sale_return_list', ''),
(66, 'Purchase Return List', 'purchase_return_list', ''),
(67, 'Order Info', 'order_info', 'Get Information from Orders item'),
(68, 'List Item of FIFO', 'list_fifo', 'This Model is like masturbate'),
(69, 'Download File', 'download_file', 'This Permission for download report or file.'),
(70, 'Warehouse Report Confirmation', 'warehouse_order_is_confirmation', 'Report Order for Confirmation by '),
(71, 'Quality Control', 'quality_control', 'permission for Quality Control'),
(72, 'Report Packing', 'report_packing', 'list information for Packing report'),
(73, 'Payment', 'payment', 'Information about payment'),
(74, 'Update Balance Bank Account', 'update_balance', 'Update information balance accounts bank. '),
(75, 'Cashflow', 'cashflow', 'List history information transaction account'),
(76, 'Drop Items', 'drop_items', 'Drops Item, without information payment and transaction.'),
(77, 'Example', 'example', 'Example Page'),
(78, 'Dashboard Staff', 'dashboard_staff', 'Permission dshboard for staff'),
(79, 'Fetch All Data Invoice Sale', 'fetch_all_invoice_sales', 'Admin, Warehouse, Quality Control, Shipper :Fetch All Invoice Sales'),
(80, 'Request Customer', 'requst_data_customer', 'Fetch all data customer'),
(81, 'Sale Edit', 'sale_edit', 'Sale Edit or Update Data Information Invoice Sale'),
(82, 'Sale Cancel', 'sale_cancel', 'Cancel Invoice Sale, not remove the information, just hide from list'),
(83, 'Travel Permits', 'travel_permits', 'Travel Permits Data'),
(85, 'Delivery Document', 'delivery_document', 'Information for delivery packet'),
(86, 'Create Delivery Document', 'delivery_document_create', 'Create Information Delivery Packet'),
(87, 'Request Data Bank', 'requst_data_bank', 'Fetch All Data Bank'),
(88, 'Total Debt To ...', 'total_debt_to', 'Information Payment Debt To Suppliers');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `title` mediumtext NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `title`) VALUES
(1, 'Administrator'),
(2, 'Manager'),
(3, 'Marketing'),
(4, 'Warehouse'),
(5, 'Head Marketing'),
(6, 'Quality Control'),
(7, 'Shipper'),
(8, 'Admin System'),
(9, 'Admin Marketing');

-- --------------------------------------------------------

--
-- Table structure for table `role_permissions`
--

CREATE TABLE `role_permissions` (
  `id` int(11) NOT NULL,
  `role` int(11) NOT NULL,
  `permission` mediumtext NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `role_permissions`
--

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
(308, 8, 'roles_edit'),
(115, 2, 'purchase_returns_info'),
(114, 2, 'address_list'),
(23, 2, 'users_view'),
(309, 3, 'upload_file'),
(113, 2, 'roles_list'),
(112, 2, 'activity_log_view'),
(111, 2, 'activity_log_list'),
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
(55, 1, 'supplier_list'),
(56, 1, 'supplier_add'),
(57, 1, 'supplier_edit'),
(58, 1, 'purchase_edit'),
(59, 1, 'purchase_info'),
(60, 1, 'purchase_returns'),
(61, 1, 'purchase_returns_edit'),
(62, 1, 'purchase_returns_info'),
(63, 1, 'purchase_returns_cancel'),
(64, 1, 'purchase_payment_list'),
(65, 1, 'purchase_payment'),
(66, 1, 'sale_list'),
(67, 1, 'order_list'),
(68, 1, 'expedition_list'),
(69, 1, 'expedition_create'),
(70, 1, 'expedition_update'),
(71, 1, 'expedition_delete'),
(72, 1, 'order_create'),
(73, 1, 'order_edit'),
(74, 3, 'users_view'),
(75, 3, 'items_list'),
(76, 3, 'items_info'),
(77, 3, 'customer_list'),
(108, 2, 'items_info'),
(80, 3, 'address_list'),
(107, 2, 'items_list'),
(83, 3, 'sale_list'),
(84, 3, 'order_list'),
(85, 3, 'expedition_list'),
(86, 3, 'order_create'),
(87, 3, 'order_edit'),
(88, 1, 'warehouse_order_list'),
(89, 1, 'warehouse_order_validation_available'),
(90, 1, 'sale_create'),
(200, 1, 'payment'),
(92, 1, 'shipper_transaction_list'),
(94, 3, 'sale_create'),
(95, 4, 'warehouse_order_list'),
(96, 4, 'warehouse_order_validation_available'),
(224, 7, 'report_delivered'),
(101, 1, 'sale_returns_edit'),
(100, 1, 'sale_returns'),
(102, 1, 'account_bank_list'),
(103, 1, 'account_bank_add'),
(104, 1, 'account_bank_edit'),
(105, 1, 'sale_return_list'),
(106, 1, 'purchase_return_list'),
(109, 2, 'supplier_list'),
(110, 2, 'purchase_info'),
(116, 2, 'purchase_payment_list'),
(117, 2, 'sale_list'),
(118, 2, 'order_list'),
(119, 2, 'expedition_list'),
(120, 2, 'order_create'),
(121, 2, 'order_edit'),
(122, 2, 'warehouse_order_list'),
(123, 2, 'sale_create'),
(124, 2, 'shipper_transaction_list'),
(125, 2, 'account_bank_list'),
(126, 2, 'sale_return_list'),
(127, 2, 'purchase_return_list'),
(128, 1, 'order_info'),
(130, 1, 'list_fifo'),
(135, 5, 'users_view'),
(348, 7, 'delivery_document_create'),
(347, 7, 'delivery_document'),
(150, 5, 'items_list'),
(346, 8, 'delivery_document_create'),
(344, 8, 'total_debt_to'),
(343, 1, 'total_debt_to'),
(154, 5, 'items_info'),
(155, 5, 'upload_file'),
(157, 5, 'customer_list'),
(158, 5, 'customer_add'),
(159, 5, 'address_add'),
(160, 5, 'address_list'),
(342, 8, 'requst_data_bank'),
(341, 1, 'requst_data_bank'),
(340, 1, 'delivery_document_create'),
(339, 1, 'delivery_document'),
(338, 1, 'travel_permits'),
(337, 9, 'warehouse_order_list'),
(336, 9, 'requst_data_customer'),
(335, 9, 'sale_create'),
(334, 9, 'order_edit'),
(333, 9, 'order_create'),
(174, 5, 'sale_list'),
(175, 5, 'order_list'),
(176, 5, 'expedition_list'),
(332, 9, 'expedition_list'),
(331, 9, 'order_list'),
(330, 9, 'sale_list'),
(180, 5, 'order_create'),
(329, 9, 'address_list'),
(328, 9, 'address_add'),
(327, 9, 'customer_add'),
(184, 5, 'sale_create'),
(326, 9, 'customer_list'),
(325, 9, 'upload_file'),
(324, 9, 'items_info'),
(323, 9, 'items_list'),
(322, 9, 'users_view'),
(321, 7, 'address_edit'),
(320, 8, 'sale_cancel'),
(319, 1, 'sale_cancel'),
(318, 8, 'sale_edit'),
(317, 1, 'sale_edit'),
(195, 1, 'download_file'),
(196, 1, 'warehouse_order_is_confirmation'),
(197, 1, 'quality_control'),
(198, 1, 'report_delivered'),
(199, 1, 'report_packing'),
(201, 1, 'update_balance'),
(202, 1, 'cashflow'),
(232, 3, 'requst_data_customer'),
(345, 8, 'delivery_document'),
(210, 6, 'quality_control'),
(227, 7, 'quality_control'),
(213, 7, 'shipper_transaction_list'),
(214, 7, 'report_packing'),
(230, 6, 'shipper_transaction_list'),
(216, 1, 'drop_items'),
(217, 1, 'example'),
(218, 1, 'dashboard_staff'),
(219, 1, 'fetch_all_invoice_sales'),
(220, 1, 'requst_data_customer'),
(221, 4, 'warehouse_order_is_confirmation'),
(222, 4, 'fetch_all_invoice_sales'),
(223, 4, 'requst_data_customer'),
(225, 7, 'fetch_all_invoice_sales'),
(226, 7, 'requst_data_customer'),
(228, 6, 'fetch_all_invoice_sales'),
(229, 6, 'requst_data_customer'),
(306, 8, 'users_list'),
(234, 8, 'users_add'),
(235, 8, 'users_edit'),
(236, 8, 'users_delete'),
(237, 8, 'users_view'),
(238, 8, 'activity_log_list'),
(239, 8, 'activity_log_view'),
(240, 8, 'roles_list'),
(241, 8, 'roles_add'),
(242, 8, 'backup_db'),
(243, 8, 'email_templates'),
(244, 8, 'general_settings'),
(245, 8, 'purchase_list'),
(246, 8, 'purchase_create'),
(247, 8, 'items_list'),
(248, 8, 'items_add'),
(249, 8, 'items_edit'),
(250, 8, 'items_delete'),
(251, 8, 'items_info'),
(252, 8, 'upload_file'),
(253, 8, 'customer_list'),
(254, 8, 'customer_add'),
(255, 8, 'address_add'),
(256, 8, 'address_list'),
(257, 8, 'customer_edit'),
(258, 8, 'address_edit'),
(259, 8, 'supplier_list'),
(260, 8, 'supplier_add'),
(261, 8, 'supplier_edit'),
(262, 8, 'purchase_edit'),
(263, 8, 'purchase_info'),
(264, 8, 'purchase_returns'),
(265, 8, 'purchase_returns_edit'),
(266, 8, 'purchase_returns_info'),
(267, 8, 'purchase_returns_cancel'),
(268, 8, 'purchase_payment_list'),
(269, 8, 'purchase_payment'),
(270, 8, 'sale_list'),
(271, 8, 'order_list'),
(272, 8, 'expedition_list'),
(273, 8, 'expedition_create'),
(274, 8, 'expedition_update'),
(275, 8, 'expedition_delete'),
(276, 8, 'order_create'),
(277, 8, 'order_edit'),
(311, 2, 'purchase_list'),
(279, 8, 'warehouse_order_validation_available'),
(280, 8, 'sale_create'),
(310, 8, 'warehouse_order_list'),
(282, 8, 'report_delivered'),
(283, 8, 'sale_returns'),
(284, 8, 'sale_returns_edit'),
(285, 8, 'account_bank_list'),
(286, 8, 'account_bank_add'),
(287, 8, 'account_bank_edit'),
(288, 8, 'sale_return_list'),
(289, 8, 'purchase_return_list'),
(290, 8, 'order_info'),
(291, 8, 'list_fifo'),
(292, 8, 'download_file'),
(293, 8, 'warehouse_order_is_confirmation'),
(294, 8, 'quality_control'),
(295, 8, 'report_packing'),
(296, 8, 'payment'),
(297, 8, 'update_balance'),
(298, 8, 'cashflow'),
(299, 8, 'drop_items'),
(300, 8, 'dashboard_staff'),
(301, 8, 'fetch_all_invoice_sales'),
(302, 8, 'requst_data_customer'),
(303, 4, 'items_list'),
(304, 7, 'customer_list'),
(305, 7, 'customer_edit'),
(312, 2, 'upload_file'),
(313, 2, 'customer_list'),
(314, 2, 'report_delivered'),
(315, 2, 'report_packing'),
(316, 5, 'requst_data_customer');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `key` mediumtext NOT NULL,
  `value` mediumtext NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `key`, `value`, `created_at`) VALUES
(1, 'company_name', 'Bandung Ejuice Distribution', '2018-06-21 03:37:59'),
(2, 'company_email', 'iyang_agung_s@protonmail.com', '2018-07-10 21:09:58'),
(3, 'timezone', 'Asia/Jakarta', '2018-07-15 05:54:17'),
(4, 'login_theme', '1', '2019-06-06 00:04:28'),
(5, 'date_format', 'd F, Y', '2020-01-03 11:31:45'),
(6, 'datetime_format', 'h:i a - d M, Y ', '2020-01-03 11:32:24'),
(7, 'google_recaptcha_enabled', '0', '2020-01-04 10:44:03'),
(8, 'google_recaptcha_sitekey', '6LdIWswUAAAAAMRp6xt2wBu7V59jUvZvKWf_rbJc', '2020-01-04 10:44:17'),
(9, 'google_recaptcha_secretkey', '6LdIWswUAAAAAIsdboq_76c63PHFsOPJHNR-z-75', '2020-01-04 10:44:40'),
(10, 'bg_img_type', 'jpeg', '2020-01-06 09:53:33'),
(11, 'default_lang', 'id', '2021-04-12 01:53:06'),
(12, 'company_icon', '1654922869.png', '2022-01-12 00:00:58');

-- --------------------------------------------------------

--
-- Table structure for table `supplier_information`
--

CREATE TABLE `supplier_information` (
  `id` int(11) NOT NULL,
  `customer_code` varchar(48) NOT NULL,
  `store_name` varchar(48) DEFAULT NULL,
  `owner_name` varchar(48) DEFAULT NULL,
  `supplier_type` varchar(48) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL,
  `contact_us` varchar(48) DEFAULT NULL,
  `note` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `created_by` varchar(48) NOT NULL,
  `updated_at` datetime NOT NULL,
  `updated_by` varchar(48) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` mediumtext NOT NULL,
  `username` mediumtext NOT NULL,
  `email` mediumtext NOT NULL,
  `password` mediumtext NOT NULL,
  `phone` mediumtext NOT NULL,
  `address` longtext NOT NULL,
  `last_login` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `role` int(11) NOT NULL,
  `reset_token` mediumtext NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `is_logged` tinyint(1) NOT NULL DEFAULT 0,
  `img_type` varchar(3000) NOT NULL DEFAULT 'png',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `email`, `password`, `phone`, `address`, `last_login`, `role`, `reset_token`, `status`, `is_logged`, `img_type`, `created_at`, `updated_at`) VALUES
(1, 'Iyang', 'admin', 'admin@administrator.com', '749b52356b9140cce3ca933b058f8d0b5d29001dff418df2aea27e81d2e5e347', '6289862327', 'Cipanteneun, Licin, Cimalaka, Sumedang, Jawabarat', '2022-09-04 09:09:41', 1, '$2y$10$XuKmLc8HfE/4U53ggD50eOiasBQD10TdVPITbSmN5GOQVX5n4d94.', 1, 1, 'jpg', '2018-06-27 04:30:16', '0000-00-00 00:00:00'),
(7, 'Yuni', 'bedyuni', 'yuni_admin@admin', '8096781b587655ee8f951ce868dfda2755b59f4cae09ee7712af43bcae5db89f', '0', '0', '2022-09-02 11:09:53', 8, '', 1, 1, 'png', '2022-04-20 05:37:56', NULL),
(3, 'Fauzi', 'bedfauzi', 'fauzi@marketing', '50658374df8ab3c3c41965a7b01f08309010c693423f5877c56bfb4510326033', '0', '0', '2022-08-25 12:32:26', 3, '', 1, 0, 'png', '2022-01-09 19:04:27', '0000-00-00 00:00:00'),
(8, 'Arnie', 'bedarnie', 'arnie_admin@admin', '00db12cd13d8badd6a81e549097cb9f43d81e13191f41ad401c1d0658e19128e', '0', '0', '2022-09-02 08:09:32', 8, '', 1, 1, 'png', '2022-04-20 05:40:14', NULL),
(5, 'Gentur', 'bedgentur', 'gentur@admin.com', 'cbebf8c8da4c995497ac7a51311f3a4cd5f385dc61afcc8e4598b3ff0a5da127', '0', '0', '2022-06-14 04:50:32', 8, '', 1, 0, 'png', '2022-04-08 06:15:56', NULL),
(6, 'Apif', 'bedapif', 'apif@admin.com', '1b68eb8e3463748b1cb1ec05895deba637e88ded428cf9a5e90720767af4b7a8', '0', '0', '2022-09-03 08:09:27', 8, '', 1, 1, 'png', '2022-04-08 06:16:53', NULL),
(9, 'Wahyu', 'wahyu_warehouse', 'wahyu@warehouse', 'ae1fd358c7612a02fdc6d923fd40308ebefb0e954c7ddb6f9a8bcdd1f3b00c3b', '0', '0', '2022-08-09 07:08:19', 4, '$2y$10$NPIf12KIMwi4sFmI8879HOrxmvO0V9rX4roC2ZAVx6Sl9nCEYbV06', 1, 1, 'png', '2022-04-20 09:35:32', NULL),
(10, 'Kiki', 'bedkiki', 'kiki@qualitycontrol.com', 'b9aba08fd6dbbf23259a9b0ebfdab5b1cfe279527d15febdcd0690c80098d771', '0', '0', '2022-08-12 06:19:09', 6, '', 1, 0, 'png', '2022-06-02 07:12:41', NULL),
(11, 'Toeti', 'cantik', 'pujiastuti@shipping', 'c8824394c8bc50faec3065c142fc90721998d3c531cd864cd4bd2b56885c3323', '0', '0', '2022-08-31 05:48:47', 7, '', 1, 0, 'png', '2022-06-03 06:14:10', NULL),
(12, 'Aulia', 'bedaulia', 'bedaul@admin.com', 'd70385ef6ec2322534fedf0de5365a17a90c83c228289eecfd277085125ee198', '0', '0', '2022-06-04 08:06:30', 8, '', 1, 1, 'png', '2022-06-04 08:30:24', NULL),
(13, 'Rifky', 'bedrifky', 'rifky@marketingmanager.com', 'd19b40c690446e885766b9d6552b2ec00f36c211f79f5f3a01d34e310058699f', '0', '0', '2022-08-12 06:20:57', 3, '', 1, 0, 'png', '2022-06-06 05:34:12', NULL),
(14, 'Bagus', 'bedbagus', 'bagus@warehouse', '97af6e5a490581f60dc9df5a872a962da45dab3278878259e258245347b1be2c', '0', '0', '2022-06-15 03:05:40', 4, '', 1, 0, 'png', '2022-06-09 08:33:46', NULL),
(15, 'Dedi Mahendra ', 'beddedi', 'dedi@warehouse', 'de7494be9efd718e0461a0a68992e0fc8236f99219749a38f84898709ff04e4c', '0', '0', '2022-08-29 11:08:05', 4, '', 1, 1, 'jpg', '2022-06-11 02:45:58', NULL),
(16, 'Fadhil', 'bedfadhil', 'fadhil@marketing.com', '79fe266a634c632aea17a2acaf7662161eb57334da00a166342a49887d4c5ef0', '0', '0', '2022-08-25 12:31:35', 3, '', 1, 0, 'png', '2022-06-13 04:38:48', NULL),
(17, 'Heru', 'bedheru', 'heru@marketing', 'ff87c01afde10b72b0e9f3c23f8e444e8d319ae01098bd409951b914464496be', 'HERU - 0812-1417-9226', '0', '2022-08-26 03:59:16', 3, '', 1, 0, 'png', '2022-06-14 08:10:20', NULL),
(18, 'Agung', 'bedagung', 'agung@marketing', 'd150a9226f147c18930a3eb099de38f9262462d4f032e82b3ebd43db05792e65', 'AGUNG - 0813-1250-9011', '0', '2022-08-25 12:32:09', 3, '', 1, 0, 'png', '2022-06-17 06:03:47', NULL),
(19, 'Aep', 'bedaep', 'aep@marketing', '6bfee26cb25cf1d39c636f1b5a8346cb72f7f359729efa760557e6ab92613fb9', '0', '0', '2022-06-24 07:55:07', 3, '', 1, 0, 'png', '2022-06-21 05:42:51', NULL),
(20, 'Fahmi', 'bedfahmi', 'fahmi@marketingonline', '897053fff8842ed613eac5ade787b0f24b56f3eca43cc4dc4999b7db7d6c7da2', '0', '0', '2022-08-13 10:22:41', 3, '', 1, 0, 'png', '2022-06-21 05:43:52', NULL),
(21, 'Nita', 'bednita', 'nita@marketingonline', '50ea7847ffbad5dc455682c74cd92ece7ba6dbb2d59a0a27c4343ada7f19b2c2', '0', '0', '2022-06-21 05:44:23', 3, '', 1, 0, 'png', '2022-06-21 05:44:23', NULL),
(22, 'Reseller', 'bedreseller', 'reseller@reseller', 'c2e41ec1d2dc1f4167014bc0b0513741cb048e8c5ce36543454fe1f177e10d44', '0', '0', '2022-06-21 05:45:55', 3, '', 1, 0, 'png', '2022-06-21 05:45:55', NULL),
(23, 'Distribution', 'beddistribution', 'distribution@distribution', '0813842bba199fe80f049d5361ada811565f04d560fcc140a3d704a53d06af09', '0', '0', '2022-06-21 05:46:34', 3, '', 1, 0, 'png', '2022-06-21 05:46:34', NULL),
(24, 'Sita', 'bedsita', 'sita@marketing', 'a334fe8e032d04402b7420f560357e291cf0a38e3ec0549f76509c99a87690d3', 'SITA - 0821-2351-1151', '0', '2022-08-25 12:31:21', 3, '', 1, 0, 'png', '2022-06-21 06:58:40', NULL),
(25, 'Anita', 'bedanita', 'anita@adminmarketing', 'a2f15df5f666faafa476103ec57cca601f0c7d722147f9b52b8386ab6e0b6f38', '0', '0', '2022-06-24 06:33:43', 9, '', 1, 1, 'png', '2022-06-22 06:46:29', NULL),
(26, 'Promo', 'bedpromo', 'promo@formarketing', '8679a3a34e36c5b5e00e611e3fe7f2615108839ab3c246d0ef3d0df93dcc0721', '0', '0', '2022-06-22 11:55:52', 3, '', 0, 0, 'png', '2022-06-22 11:55:52', NULL),
(27, 'Cepi', 'bedcepi', 'cepi@warehouse', '2993f71686c773fc3db35d4d776ec1d7df471f07b11140174ba9a2d8488f436d', '0', '0', '2022-06-25 10:06:14', 4, '', 1, 1, 'png', '2022-06-25 07:10:34', NULL),
(28, 'Staff', 'bedstaff', 'staff@marketing', '79983e573c818c62c61a479e7a560f64286ee4ac53ee537559a09acb546d6ee6', '0', '0', '2022-06-25 10:33:14', 3, '', 0, 0, 'png', '2022-06-25 10:32:44', NULL),
(29, 'No Cuan', 'bednocuan', 'nocuan@marketing', 'bc0461ce783da756c35520fc9dcd0b4cb798c5bc6781767f5bed38febfac180f', '0', '0', '2022-06-27 11:34:40', 3, '', 0, 0, 'png', '2022-06-27 11:34:40', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `address_information`
--
ALTER TABLE `address_information`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bank_information`
--
ALTER TABLE `bank_information`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer_information`
--
ALTER TABLE `customer_information`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `customer_code` (`customer_code`);

--
-- Indexes for table `delivery_head`
--
ALTER TABLE `delivery_head`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `delivery_list_item`
--
ALTER TABLE `delivery_list_item`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `email_templates`
--
ALTER TABLE `email_templates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `expedition`
--
ALTER TABLE `expedition`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fifo_items`
--
ALTER TABLE `fifo_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoice_payment`
--
ALTER TABLE `invoice_payment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoice_purchasing`
--
ALTER TABLE `invoice_purchasing`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `invoice_code` (`invoice_code`);

--
-- Indexes for table `invoice_selling`
--
ALTER TABLE `invoice_selling`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `invoice_code` (`invoice_code`);

--
-- Indexes for table `invoice_transaction_list_item`
--
ALTER TABLE `invoice_transaction_list_item`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `items_history`
--
ALTER TABLE `items_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_purchase`
--
ALTER TABLE `order_purchase`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_sale`
--
ALTER TABLE `order_sale`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_sale_list_item`
--
ALTER TABLE `order_sale_list_item`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `supplier_information`
--
ALTER TABLE `supplier_information`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `customer_code` (`customer_code`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `address_information`
--
ALTER TABLE `address_information`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bank_information`
--
ALTER TABLE `bank_information`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customer_information`
--
ALTER TABLE `customer_information`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `delivery_head`
--
ALTER TABLE `delivery_head`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `delivery_list_item`
--
ALTER TABLE `delivery_list_item`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `email_templates`
--
ALTER TABLE `email_templates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `expedition`
--
ALTER TABLE `expedition`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fifo_items`
--
ALTER TABLE `fifo_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoice_payment`
--
ALTER TABLE `invoice_payment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoice_purchasing`
--
ALTER TABLE `invoice_purchasing`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoice_selling`
--
ALTER TABLE `invoice_selling`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoice_transaction_list_item`
--
ALTER TABLE `invoice_transaction_list_item`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `items_history`
--
ALTER TABLE `items_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_purchase`
--
ALTER TABLE `order_purchase`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_sale`
--
ALTER TABLE `order_sale`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_sale_list_item`
--
ALTER TABLE `order_sale_list_item`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=89;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `role_permissions`
--
ALTER TABLE `role_permissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=349;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `supplier_information`
--
ALTER TABLE `supplier_information`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
