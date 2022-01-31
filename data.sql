-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 29, 2022 at 12:36 PM
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
  `title` text NOT NULL,
  `details` text NOT NULL,
  `user` text NOT NULL,
  `ip_address` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `activity_logs`
--

INSERT INTO `activity_logs` (`id`, `title`, `details`, `user`, `ip_address`, `created_at`, `updated_at`) VALUES
(1, 'New Item Upload, #ACC-000001, Created by User: #1', 'item_code=\'ACC-000001\', item_name=\'Accecory MOD\', category=\'ACC\', brand=\'BED\', brands=\'\', mg=\'\', ml=\'\', vg=\'\', pg=\'\', flavour=\'\', quantity=\'10\', unit=\'PCS\', capital_price=\'100000\', selling_price=\'150000\', customs=\'\', note=\'Import Templates\', created_by=\'1\'', '1', '127.0.0.1', '2022-01-27 09:30:14', NULL),
(2, 'New Item Upload, #ACC-000002, Created by User: #1', 'item_code=\'ACC-000002\', item_name=\'Accecory POD\', category=\'ACC\', brand=\'BED\', brands=\'\', mg=\'\', ml=\'\', vg=\'\', pg=\'\', flavour=\'\', quantity=\'20\', unit=\'PCS\', capital_price=\'200000\', selling_price=\'250000\', customs=\'\', note=\'Import Templates\', created_by=\'1\'', '1', '127.0.0.1', '2022-01-27 09:30:14', NULL),
(3, 'New Item Upload, #LIQ-000001, Created by User: #1', 'item_code=\'LIQ-000001\', item_name=\'LIQUID\', category=\'LIQ\', brand=\'BED\', brands=\'\', mg=\'10\', ml=\'20\', vg=\'30\', pg=\'40\', flavour=\'APPLE\', quantity=\'10\', unit=\'PAX\', capital_price=\'100000\', selling_price=\'200000\', customs=\'2022\', note=\'Import Templates\', created_by=\'1\'', '1', '127.0.0.1', '2022-01-27 09:30:14', NULL),
(4, 'User: Administrator Logged Out', 'id=\'1\', name=\'Administrator\', username=\'admin\', email=\'admin@administrator.com\', password=\'e57d520851be5ab85f56880afa8cb65717bbe238430a00de39600d77e855ef82\', phone=\'6289862327\', address=\'Cipanteneun, Licin, Cimalaka, Sumedang, Jawabarat\', last_login=\'2022-01-27 17:01:04\', role=\'1\', reset_token=\'\', status=\'1\', img_type=\'png\', created_at=\'2018-06-27 18:30:16\', updated_at=\'0000-00-00 00:00:00\'', '1', '127.0.0.1', '2022-01-27 10:05:38', NULL),
(5, 'Company Settings Updated by User: #1', 'company_name=\'B.E.DD\', company_email=\'iyang_agung_s@protonmail.com\'', '1', '127.0.0.1', '2022-01-27 10:39:07', NULL),
(6, 'Company Settings Updated by User: #1', 'company_name=\'B.E.D\', company_email=\'iyang_agung_s@protonmail.com\'', '1', '127.0.0.1', '2022-01-27 10:39:11', NULL),
(7, 'User #1 Updated by User:Iyang Agung S', 'id=\'1\', name=\'Administrator\', username=\'admin\', email=\'admin@administrator.com\', password=\'e57d520851be5ab85f56880afa8cb65717bbe238430a00de39600d77e855ef82\', phone=\'6289862327\', address=\'Cipanteneun, Licin, Cimalaka, Sumedang, Jawabarat\', last_login=\'2022-01-27 17:01:05\', role=\'1\', reset_token=\'\', status=\'1\', img_type=\'png\', created_at=\'2018-06-27 18:30:16\', updated_at=\'0000-00-00 00:00:00\'', '1', '127.0.0.1', '2022-01-27 10:43:34', NULL),
(8, 'User #1 Updated by User:Iyang Agung Supriatna', 'id=\'1\', name=\'Iyang Agung S\', username=\'admin\', email=\'admin@administrator.com\', password=\'e57d520851be5ab85f56880afa8cb65717bbe238430a00de39600d77e855ef82\', phone=\'6289862327\', address=\'Cipanteneun, Licin, Cimalaka, Sumedang, Jawabarat\', last_login=\'2022-01-27 17:43:34\', role=\'1\', reset_token=\'\', status=\'1\', img_type=\'png\', created_at=\'2018-06-27 18:30:16\', updated_at=\'0000-00-00 00:00:00\'', '1', '127.0.0.1', '2022-01-27 10:44:04', NULL),
(9, 'Role #1 Updated by User: #1', 'id=\'1\', title=\'Admin\'', '1', '127.0.0.1', '2022-01-27 10:44:29', NULL),
(10, 'Role #1 Updated by User: #1', 'id=\'1\', title=\'Admin\'', '1', '127.0.0.1', '2022-01-27 10:44:45', NULL),
(11, 'New Permission #27 Created by User: #1', '0=\'27\'', '1', '127.0.0.1', '2022-01-27 10:47:01', NULL),
(12, 'Permission # Deleted by User: #1', 'id=\'26\', title=\'Upoad Item\', code=\'upload_item\'', '1', '127.0.0.1', '2022-01-27 10:47:10', NULL),
(13, 'New Permission #28 Created by User: #1', 'name=\'Upload file\', code=\'upload_file\'', '1', '127.0.0.1', '2022-01-27 10:49:08', NULL),
(14, 'User: Iyang Agung Supriatna Logged Out', 'id=\'1\', name=\'Iyang Agung Supriatna\', username=\'admin\', email=\'admin@administrator.com\', password=\'e57d520851be5ab85f56880afa8cb65717bbe238430a00de39600d77e855ef82\', phone=\'6289862327\', address=\'Cipanteneun, Licin, Cimalaka, Sumedang, Jawabarat\', last_login=\'2022-01-27 17:44:04\', role=\'1\', reset_token=\'\', status=\'1\', img_type=\'png\', created_at=\'2018-06-27 18:30:16\', updated_at=\'0000-00-00 00:00:00\'', '1', '127.0.0.1', '2022-01-27 10:53:37', NULL),
(15, 'Iyang Agung Supriatna (admin) Logged in', 'username=\'admin\', password=\'mak65yousmil65\'', '1', '127.0.0.1', '2022-01-27 10:53:44', NULL),
(16, 'Updated Item #1, #ACC-000001, Updated by User: #1', 'id=\'1\', item_code=\'ACC-000001\', item_name=\'Accecory MOD\', category=\'ACC\', brand=\'BED\', brands=\'\', mg=\'0\', ml=\'0\', vg=\'0\', pg=\'0\', flavour=\'\', quantity=\'10\', unit=\'PCS\', capital_price=\'100000\', selling_price=\'150000\', customs=\'0\', note=\'0\', created_at=\'2022-01-27 16:30:14\', created_by=\'1\', updated_at=\'\', updated_by=\'\'', '1', '127.0.0.1', '2022-01-27 11:04:27', NULL),
(17, 'Updated Item #1, #ACC-000001, Updated by User: #1', 'id=\'1\', item_code=\'ACC-000001\', item_name=\'Accecory MOD\', category=\'ACC\', brand=\'0\', brands=\'0\', mg=\'0\', ml=\'0\', vg=\'0\', pg=\'0\', flavour=\'0\', quantity=\'0\', unit=\'PCS\', capital_price=\'100,000\', selling_price=\'150,000\', customs=\'0\', note=\'0\', created_at=\'2022-01-27 16:30:14\', created_by=\'1\', updated_at=\'2022-01-27 18:04:27\', updated_by=\'1\'', '1', '127.0.0.1', '2022-01-27 11:04:58', NULL),
(18, 'Updated Item #3, #LIQ-000001, Updated by User: #1', 'id=\'3\', item_code=\'LIQ-000001\', item_name=\'LIQUID\', category=\'LIQ\', brand=\'BED\', brands=\'\', mg=\'10\', ml=\'20\', vg=\'30\', pg=\'40\', flavour=\'APPLE\', quantity=\'10\', unit=\'PAX\', capital_price=\'100000\', selling_price=\'200000\', customs=\'2022\', note=\'0\', created_at=\'2022-01-27 16:30:14\', created_by=\'1\', updated_at=\'\', updated_by=\'\'', '1', '127.0.0.1', '2022-01-27 11:05:18', NULL),
(19, 'Updated Item #3, #LIQ-000001, Updated by User: #1', 'id=\'3\', item_code=\'LIQ-000001\', item_name=\'LIQUID\', category=\'LIQ\', brand=\'0\', brands=\'0\', mg=\'0\', ml=\'0\', vg=\'0\', pg=\'0\', flavour=\'0\', quantity=\'0\', unit=\'PCS\', capital_price=\'100,000\', selling_price=\'200,000\', customs=\'0\', note=\'0\', created_at=\'2022-01-27 16:30:14\', created_by=\'1\', updated_at=\'2022-01-27 18:05:18\', updated_by=\'1\'', '1', '127.0.0.1', '2022-01-27 11:11:25', NULL),
(20, 'New Item #4, #LIQ-FC-000001, Created by User: #1', 'item_code=\'LIQ-FC-000001\', item_name=\'FREBASE CREAMY\', category=\'LIQ\', brand=\'\', brands=\'\', mg=\'\', ml=\'10\', vg=\'10\', pg=\'10\', flavour=\'Lechy\', quantity=\'\', unit=\'PAC\', capital_price=\'1,000\', selling_price=\'10,000\', customs=\'2022\', note=\'\', created_by=\'1\'', '1', '127.0.0.1', '2022-01-27 11:15:35', NULL),
(21, 'Updated Item #4, #LIQ-FC-000001, Updated by User: #1', 'id=\'4\', item_code=\'LIQ-FC-000001\', item_name=\'FREBASE CREAMY\', category=\'LIQ\', brand=\'0\', brands=\'0\', mg=\'0\', ml=\'10\', vg=\'10\', pg=\'10\', flavour=\'Lechy\', quantity=\'0\', unit=\'PAC\', capital_price=\'1,000\', selling_price=\'10,000\', customs=\'2022\', note=\'0\', created_at=\'2022-01-27 18:15:35\', created_by=\'1\', updated_at=\'\', updated_by=\'\'', '1', '127.0.0.1', '2022-01-27 11:18:24', NULL),
(22, 'Updated Item #1, #ACC-000001, Updated by User: #1', 'id=\'1\', item_code=\'ACC-000001\', item_name=\'Accecory MOD\', category=\'ACC\', brand=\'0\', brands=\'0\', mg=\'0\', ml=\'0\', vg=\'0\', pg=\'0\', flavour=\'0\', quantity=\'0\', unit=\'PCS\', capital_price=\'100,000\', selling_price=\'150,000\', customs=\'0\', note=\'0\', created_at=\'2022-01-27 16:30:14\', created_by=\'1\', updated_at=\'2022-01-27 18:04:58\', updated_by=\'1\'', '1', '127.0.0.1', '2022-01-27 11:29:24', NULL),
(23, 'Updated Item #4, #LIQ-FC-000001, Updated by User: #1', 'id=\'4\', item_code=\'LIQ-FC-000001\', item_name=\'FREBASE CREAMY\', category=\'LIQ\', brand=\'BED\', brands=\'0\', mg=\'10\', ml=\'20\', vg=\'30\', pg=\'40\', flavour=\'Lechy\', quantity=\'0\', unit=\'PAC\', capital_price=\'1,000\', selling_price=\'10,000\', customs=\'2022\', note=\'0\', created_at=\'2022-01-27 18:15:35\', created_by=\'1\', updated_at=\'2022-01-27 18:18:24\', updated_by=\'1\'', '1', '127.0.0.1', '2022-01-27 11:29:36', NULL),
(24, 'New Item #5, #CAT-000001, Created by User: #1', 'item_code=\'CAT-000001\', item_name=\'TEST\', category=\'CAT & COIL\', brand=\'\', brands=\'\', mg=\'\', ml=\'\', vg=\'\', pg=\'\', flavour=\'\', quantity=\'10\', unit=\'PCS\', capital_price=\'\', selling_price=\'\', customs=\'\', note=\'\', created_by=\'1\'', '1', '127.0.0.1', '2022-01-27 11:37:27', NULL),
(25, 'New Permission #29 Created by User: #1', 'name=\'Item Empty\', code=\'items_truncate\'', '1', '127.0.0.1', '2022-01-27 12:25:18', NULL),
(26, 'Permission # Deleted by User: #1', 'id=\'27\', title=\'Upoad Item\', code=\'upload_items\'', '1', '127.0.0.1', '2022-01-27 12:25:46', NULL),
(27, 'Role #1 Updated by User: #1', 'id=\'1\', title=\'Admin\'', '1', '127.0.0.1', '2022-01-27 12:27:19', NULL),
(28, 'All item, Deleted by User: #1', '0=\'1\'', '1', '127.0.0.1', '2022-01-27 12:27:30', NULL),
(29, 'Iyang Agung Supriatna (admin) Logged in', 'username=\'admin\', password=\'mak65yousmil65\'', '1', '127.0.0.1', '2022-01-28 04:22:18', NULL),
(30, 'New Item Upload, #ACC-000001, Created by User: #1', 'item_code=\'ACC-000001\', item_name=\'Accecory MOD\', category=\'ACC\', brand=\'BED\', brands=\'\', mg=\'\', ml=\'\', vg=\'\', pg=\'\', flavour=\'\', quantity=\'10\', unit=\'PCS\', capital_price=\'100000\', selling_price=\'150000\', customs=\'\', note=\'Import Templates\', created_by=\'1\'', '1', '127.0.0.1', '2022-01-28 04:22:45', NULL),
(31, 'New Item Upload, #ACC-000002, Created by User: #1', 'item_code=\'ACC-000002\', item_name=\'Accecory POD\', category=\'ACC\', brand=\'BED\', brands=\'\', mg=\'\', ml=\'\', vg=\'\', pg=\'\', flavour=\'\', quantity=\'20\', unit=\'PCS\', capital_price=\'200000\', selling_price=\'250000\', customs=\'\', note=\'Import Templates\', created_by=\'1\'', '1', '127.0.0.1', '2022-01-28 04:22:45', NULL),
(32, 'New Item Upload, #LIQ-000001, Created by User: #1', 'item_code=\'LIQ-000001\', item_name=\'LIQUID\', category=\'LIQ\', brand=\'BED\', brands=\'\', mg=\'10\', ml=\'20\', vg=\'30\', pg=\'40\', flavour=\'APPLE\', quantity=\'10\', unit=\'PAX\', capital_price=\'100000\', selling_price=\'200000\', customs=\'2022\', note=\'Import Templates\', created_by=\'1\'', '1', '127.0.0.1', '2022-01-28 04:22:45', NULL),
(33, 'New Permission #30 Created by User: #1', 'name=\'Data Customer\', code=\'customer_list\'', '1', '127.0.0.1', '2022-01-28 06:05:31', NULL),
(34, 'Role #1 Updated by User: #1', 'id=\'1\', title=\'Admin\'', '1', '127.0.0.1', '2022-01-28 06:05:44', NULL),
(35, 'New Permission #31 Created by User: #1', 'name=\'Add Customer\', code=\'customer_add\'', '1', '127.0.0.1', '2022-01-28 06:51:00', NULL),
(36, 'Role #1 Updated by User: #1', 'id=\'1\', title=\'Admin\'', '1', '127.0.0.1', '2022-01-28 06:51:25', NULL),
(37, 'New Customer #3, Created by User: #1', 'customer_code=\'PL-000003\', store_name=\'Iyang\', owner_name=\'admin\', customer_type=\'AGENT\', note=\'\'', '1', '127.0.0.1', '2022-01-28 08:43:02', NULL),
(38, 'New Customer #4, Created by User: #1', 'customer_code=\'PL-000004\', store_name=\'asd\', owner_name=\'asd\', customer_type=\'AGENT\', note=\'\'', '1', '127.0.0.1', '2022-01-28 08:45:01', NULL),
(39, 'New Customer #5, Created by User: #1', 'customer_code=\'PL-000005\', store_name=\'asd\', owner_name=\'asd\', customer_type=\'WS\', note=\'\'', '1', '127.0.0.1', '2022-01-28 08:46:24', NULL),
(40, 'New Customer #1, Created by User: #1', 'customer_code=\'PL-000005\', store_name=\'ASD\', owner_name=\'ASD\', customer_type=\'WS\', is_active=\'1\', created_by=\'1\', note=\'\'', '1', '127.0.0.1', '2022-01-28 08:48:31', NULL),
(41, 'New Permission #32 Created by User: #1', 'name=\'Add Address\', code=\'address_add\'', '1', '127.0.0.1', '2022-01-28 08:53:19', NULL),
(42, 'New Permission #33 Created by User: #1', 'name=\'List Address\', code=\'address_list\'', '1', '127.0.0.1', '2022-01-28 08:53:34', NULL),
(43, 'Role #1 Updated by User: #1', 'id=\'1\', title=\'Admin\'', '1', '127.0.0.1', '2022-01-28 08:53:46', NULL),
(44, 'New Address #1, Created by User: #1', 'customer_id=\'1\', province=\'\', city=\'Kabupaten Badung\', sub_district=\'A\', village=\'B\', zip=\'C\', contact_phone=\'D\', contact_mail=\'E\', created_by=\'1\', note=\'F\'', '1', '127.0.0.1', '2022-01-28 11:48:00', NULL),
(45, 'New Address #2, Created by User: #1', 'customer_id=\'1\', province=\'Jawa Timur\', city=\'Kabupaten Bangkalan\', sub_district=\'B\', village=\'C\', zip=\'D\', contact_phone=\'E\', contact_mail=\'F\', created_by=\'1\', note=\'G\r\n\'', '1', '127.0.0.1', '2022-01-28 11:50:10', NULL),
(46, 'Iyang Agung Supriatna (admin) Logged in', 'username=\'admin\', password=\'mak65yousmil65\'', '1', '127.0.0.1', '2022-01-29 04:23:54', NULL),
(47, 'New Customer #2, Created by User: #1', 'customer_code=\'PL-000002\', store_name=\'TEST\', owner_name=\'SECOND\', customer_type=\'AGENT\', is_active=\'1\', created_by=\'1\', note=\'\'', '1', '127.0.0.1', '2022-01-29 04:38:50', NULL),
(48, 'New Address #3, Created by User: #1', 'customer_id=\'2\', province=\'DI Yogyakarta\', city=\'Kabupaten Bantul\', sub_district=\'AAA\', village=\'BBB\', zip=\'CC123\', contact_phone=\'098123\', contact_mail=\'admin\', created_by=\'1\', note=\'\'', '1', '127.0.0.1', '2022-01-29 04:39:17', NULL),
(49, 'New Permission #34 Created by User: #1', 'name=\'Edit Customer\', code=\'customer_edit\'', '1', '127.0.0.1', '2022-01-29 06:54:10', NULL),
(50, 'New Permission #35 Created by User: #1', 'name=\'Edit Address\', code=\'address_edit\'', '1', '127.0.0.1', '2022-01-29 06:54:28', NULL),
(51, 'Role #1 Updated by User: #1', 'id=\'1\', title=\'Admin\'', '1', '127.0.0.1', '2022-01-29 06:54:43', NULL),
(52, 'Update Customer #, Update by User: #1', '', '1', '127.0.0.1', '2022-01-29 07:09:43', NULL),
(53, 'Update Customer #, Update by User: #1', '', '1', '127.0.0.1', '2022-01-29 07:11:21', NULL),
(54, 'Update Customer #2, Update by User: #1', '', '1', '127.0.0.1', '2022-01-29 07:12:40', NULL),
(55, 'Update Customer #2, Update by User: #1', '', '1', '127.0.0.1', '2022-01-29 07:12:47', NULL),
(56, 'Update Customer #2, Update by User: #1', '', '1', '127.0.0.1', '2022-01-29 07:13:47', NULL),
(57, 'Update Address #2, Update by User: #1', 'customer_id=\'1\', province=\'Jawa Timur\', city=\'Kabupaten Tulungagung\', sub_district=\'B\', village=\'C\', zip=\'D\', contact_phone=\'E\', contact_mail=\'F\', updated_at=\'2022-01-29 15:23:30\', updated_by=\'1\', is_active=\'1\', note=\'updated\'', '1', '127.0.0.1', '2022-01-29 08:23:30', NULL),
(58, 'Update Address #1, Update by User: #1', 'customer_id=\'1\', province=\'Jawa Barat\', city=\'Kota Bandung\', sub_district=\'A\', village=\'B\', zip=\'C\', contact_phone=\'D\', contact_mail=\'E\', updated_at=\'2022-01-29 15:23:50\', updated_by=\'1\', is_active=\'0\', note=\'updated\'', '1', '127.0.0.1', '2022-01-29 08:23:51', NULL),
(59, 'Update Customer #2, Update by User: #1', '', '1', '127.0.0.1', '2022-01-29 09:13:21', NULL),
(60, 'Update Customer #2, Update by User: #1', '', '1', '127.0.0.1', '2022-01-29 09:13:25', NULL),
(61, 'Update Address #3, Update by User: #1', '', '1', '127.0.0.1', '2022-01-29 09:14:19', NULL),
(62, 'Update Address #3, Update by User: #1', '', '1', '127.0.0.1', '2022-01-29 09:14:25', NULL),
(63, 'Update Address #1, Update by User: #1', '', '1', '127.0.0.1', '2022-01-29 09:14:34', NULL),
(64, 'Update Address #2, Update by User: #1', '', '1', '127.0.0.1', '2022-01-29 09:14:37', NULL),
(65, 'Update Address #1, Update by User: #1', '', '1', '127.0.0.1', '2022-01-29 09:14:51', NULL),
(66, 'Update Customer #1, Update by User: #1', '', '1', '127.0.0.1', '2022-01-29 09:15:41', NULL),
(67, 'Update Customer #2, Update by User: #1', '', '1', '127.0.0.1', '2022-01-29 09:15:47', NULL),
(68, 'Update Customer #1, Update by User: #1', '', '1', '127.0.0.1', '2022-01-29 09:15:52', NULL),
(69, 'Update Customer #1, Update by User: #1', '', '1', '127.0.0.1', '2022-01-29 09:15:58', NULL),
(70, 'Update Customer #1, Update by User: #1', '', '1', '127.0.0.1', '2022-01-29 09:17:18', NULL),
(71, 'Update Address #3, Update by User: #1', '', '1', '127.0.0.1', '2022-01-29 09:17:29', NULL),
(72, 'Update Customer #2, Update by User: #1', '', '1', '127.0.0.1', '2022-01-29 09:19:11', NULL),
(73, 'New Permission #36 Created by User: #1', 'name=\'Add Purchase\', code=\'purchase_add\'', '1', '127.0.0.1', '2022-01-29 09:40:37', NULL),
(74, 'Role #1 Updated by User: #1', 'id=\'1\', title=\'Admin\'', '1', '127.0.0.1', '2022-01-29 09:40:53', NULL),
(75, 'Permission # Deleted by User: #1', 'id=\'36\', title=\'Add Purchase\', code=\'purchase_add\'', '1', '127.0.0.1', '2022-01-29 09:46:47', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `address_information`
--

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
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `created_by` varchar(48) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` varchar(48) NOT NULL,
  `note` text NOT NULL,
  `is_active` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `address_information`
--

INSERT INTO `address_information` (`id`, `customer_id`, `village`, `sub_district`, `city`, `province`, `zip`, `contact_phone`, `contact_mail`, `created_at`, `created_by`, `updated_at`, `updated_by`, `note`, `is_active`) VALUES
(1, 1, 'B', 'A', 'Kota Tasikmalaya', 'Jawa Barat', 'C', 'D', 'E', '2022-01-28 11:48:00', '1', '2022-01-29 09:14:51', '1', 'updated', 0),
(2, 1, 'C', 'B', 'Kabupaten Tulungagung', 'Jawa Timur', 'D', 'E', 'F', '2022-01-28 11:50:10', '1', '2022-01-29 09:14:37', '1', 'updated', 1),
(3, 2, 'BBB', 'AAA', 'Kota Yogyakarta', 'DI Yogyakarta', 'CC123', '098123', 'admin', '2022-01-29 04:39:17', '1', '2022-01-29 09:17:29', '1', '0', 1);

-- --------------------------------------------------------

--
-- Table structure for table `customer_information`
--

CREATE TABLE `customer_information` (
  `id` int(11) NOT NULL,
  `customer_code` varchar(48) NOT NULL,
  `store_name` varchar(48) NOT NULL,
  `owner_name` varchar(48) NOT NULL,
  `customer_type` varchar(48) NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `note` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` varchar(48) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` varchar(48) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `customer_information`
--

INSERT INTO `customer_information` (`id`, `customer_code`, `store_name`, `owner_name`, `customer_type`, `is_active`, `note`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES
(1, 'PL-000005', 'ASD', 'DASD', 'WS', 1, '0', '2022-01-28 08:48:31', '1', '2022-01-29 09:17:18', '1'),
(2, 'PL-000002', 'ILYANAZALUN', 'IYANG AGUNG SUPARIATNA', 'AGENT', 1, 'pelanggan', '2022-01-29 04:38:50', '1', '2022-01-29 09:19:11', '1');

-- --------------------------------------------------------

--
-- Table structure for table `email_templates`
--

CREATE TABLE `email_templates` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `code` text NOT NULL,
  `data` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `email_templates`
--

INSERT INTO `email_templates` (`id`, `name`, `code`, `data`, `created_at`) VALUES
(1, 'Reset Password Template', 'reset_password', '<h1><strong>{company_name}</strong></h1>\r\n\r\n<h3>Click on Reset Link to Proceed : <a href=\"{reset_link}\">Reset Now</a></h3>\r\n', '2020-01-03 02:40:14');

-- --------------------------------------------------------

--
-- Table structure for table `invoice`
--

CREATE TABLE `invoice` (
  `id` int(11) NOT NULL,
  `invoice_code` varchar(48) NOT NULL,
  `total_price` varchar(48) NOT NULL,
  `discounts` varchar(48) NOT NULL,
  `grant_total` varchar(48) NOT NULL,
  `customer` int(11) NOT NULL,
  `supplier` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

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
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `created_by` varchar(48) CHARACTER SET utf8mb4 NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` varchar(48) CHARACTER SET utf8mb4 DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`id`, `item_code`, `item_name`, `category`, `brand`, `brands`, `mg`, `ml`, `vg`, `pg`, `flavour`, `quantity`, `unit`, `capital_price`, `selling_price`, `customs`, `note`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES
(1, 'ACC-000001', 'Accecory MOD', 'ACC', 'BED', NULL, 0, 0, 0, 0, '', 10, 'PCS', '100000', '150000', 0, 0, '2022-01-28 11:22:45', '1', NULL, NULL),
(2, 'ACC-000002', 'Accecory POD', 'ACC', 'BED', NULL, 0, 0, 0, 0, '', 20, 'PCS', '200000', '250000', 0, 0, '2022-01-28 11:22:45', '1', NULL, NULL),
(3, 'LIQ-000001', 'LIQUID', 'LIQ', 'BED', NULL, 10, 20, 30, 40, 'APPLE', 10, 'PAX', '100000', '200000', 2022, 0, '2022-01-28 11:22:45', '1', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `order`
--

CREATE TABLE `order` (
  `id` int(11) NOT NULL,
  `invoice` int(11) NOT NULL,
  `item` int(11) NOT NULL,
  `capital_price` varchar(48) NOT NULL,
  `selling_price` varchar(48) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` int(11) NOT NULL,
  `title` text NOT NULL,
  `code` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `permissions`
--

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

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `title` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `title`) VALUES
(1, 'Admin'),
(2, 'Manager'),
(3, 'Marketing'),
(4, 'Warehouse'),
(5, 'Shipping'),
(6, 'Marketing');

-- --------------------------------------------------------

--
-- Table structure for table `role_permissions`
--

CREATE TABLE `role_permissions` (
  `id` int(11) NOT NULL,
  `role` int(11) NOT NULL,
  `permission` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `key` text NOT NULL,
  `value` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `settings`
--

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

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `username` text NOT NULL,
  `email` text NOT NULL,
  `password` text NOT NULL,
  `phone` text NOT NULL,
  `address` longtext NOT NULL,
  `last_login` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `role` int(11) NOT NULL,
  `reset_token` text NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `is_logged` TINYINT(1) NOT NULL DEFAULT '0',
  `img_type` varchar(3000) NOT NULL DEFAULT 'png',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `email`, `password`, `phone`, `address`, `last_login`, `role`, `reset_token`, `status`, `img_type`, `created_at`, `updated_at`) VALUES
(1, 'Iyang Agung Supriatna', 'admin', 'admin@administrator.com', 'e57d520851be5ab85f56880afa8cb65717bbe238430a00de39600d77e855ef82', '6289862327', 'Cipanteneun, Licin, Cimalaka, Sumedang, Jawabarat', '2022-01-29 04:01:23', 1, '', 1, 'png', '2018-06-27 11:30:16', '0000-00-00 00:00:00'),
(2, 'Esa', 'esa_shipping', 'Esa@shipping.com', '740062676a3134f36f0f0fc90152e91a417d0d363bf2441ebd6a5103f562dacf', '0', '0', '2022-01-27 10:42:29', 5, '', 1, 'png', '2022-01-08 17:58:13', '0000-00-00 00:00:00'),
(3, 'Fauzi', 'fauzi_marketing', 'fauzi@marketing', 'e2a530e251d3675034d23f5c5f87f54ec3182a088ba7d13350824794f8e6b76e', '0', '0', '2022-01-10 00:01:35', 3, '', 1, 'png', '2022-01-10 02:04:27', '0000-00-00 00:00:00');

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
-- Indexes for table `customer_information`
--
ALTER TABLE `customer_information`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `email_templates`
--
ALTER TABLE `email_templates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoice`
--
ALTER TABLE `invoice`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order`
--
ALTER TABLE `order`
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;

--
-- AUTO_INCREMENT for table `address_information`
--
ALTER TABLE `address_information`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `customer_information`
--
ALTER TABLE `customer_information`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `email_templates`
--
ALTER TABLE `email_templates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `invoice`
--
ALTER TABLE `invoice`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `order`
--
ALTER TABLE `order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `role_permissions`
--
ALTER TABLE `role_permissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
