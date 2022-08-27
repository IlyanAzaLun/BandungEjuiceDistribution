#
# TABLE STRUCTURE FOR: activity_logs
#

DROP TABLE IF EXISTS `activity_logs`;

CREATE TABLE `activity_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` mediumtext NOT NULL,
  `user` mediumtext NOT NULL,
  `details` mediumtext NOT NULL,
  `ip_address` mediumtext NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

#
# TABLE STRUCTURE FOR: address_information
#

DROP TABLE IF EXISTS `address_information`;

CREATE TABLE `address_information` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
  `is_active` tinyint(1) DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

#
# TABLE STRUCTURE FOR: bank_information
#

DROP TABLE IF EXISTS `bank_information`;

CREATE TABLE `bank_information` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(48) NOT NULL,
  `no_account` varchar(48) NOT NULL,
  `own_by` varchar(48) NOT NULL,
  `balance` varchar(48) DEFAULT '0',
  `description` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` varchar(48) NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` varchar(48) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=146 DEFAULT CHARSET=utf8mb4;

INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (1, 'BCA', '4375977789', 'ADI PRAYOGO', '0', '', '2022-04-11 15:28:55', '1', '2022-06-04 12:19:59', '8');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (2, 'BCA', '7405018888', 'GHEA SATYA P / OPIK', '0', '', '2022-04-11 16:00:30', '1', '2022-06-04 12:25:51', '8');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (3, 'BCA', '7405788888', 'PT DUA SAHABAT BERKARYA', '0', '', '2022-06-04 12:20:47', '8', '2022-06-04 12:25:35', '8');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (4, 'BCA', '4378050888', 'GHEA SATYA PAMUDA', '0', 'REK PAJAK', '2022-06-04 12:21:22', '8', '2022-06-04 12:25:10', '8');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (5, 'BCA', '4375000688', 'OPIK', '0', 'REK PAJAK', '2022-06-04 12:21:56', '8', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (6, 'MANDIRI', '1300016964911', 'OPIK', '0', '', '2022-06-04 12:24:05', '8', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (7, 'TOKOPEDIA BED', '0', '', '0', '', '2022-06-04 13:25:04', '7', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (8, 'SHOPEE MUDAHIGAT', '0', '', '0', '', '2022-06-04 14:27:05', '1', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (9, 'KAS KECIL', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (10, 'BANK BCA ADIS', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (11, 'BANK MANDIRI', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (12, 'DEBIT BCA', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (13, 'TOKOPEDIA BANTING', '0', '', '0', '', '2022-08-23 16:35:45', '', '2022-08-24 15:13:46', '8');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (14, 'TOKOPEDIA SEASON', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (15, 'TOKOPEDIA KASIMURA', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (16, 'TOKOPEDIA MUDAHINGAT', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (17, 'BUKALAPAK BED', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (18, 'BUKALAPAK KASIMURA', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (19, 'SHOPEE GLOW DISTRIBUTION', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (20, 'SHOPEE KASIMURA', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (21, 'SHOPEE GALIH', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (22, 'TOKOPEDIA HAPPENING', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (23, 'BUKALAPAK MUDAHINGAT', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (24, 'REK. GOPAY', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (25, 'TOKOPEDIA FAF13', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (26, 'BUKALAPAK FAF13', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (27, 'SHOPEE VAP. SKILLZ', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (28, 'SHOPEE LUCKYLAKU18', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (29, 'BUKALAPAK LUCKY LAKU', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (30, 'BUKALAPAK BTS_STORE', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (31, 'BUKALAPAK NINJA CLOUD', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (32, 'BUKALAPAK BLENJONK STORE', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (33, 'BUKALAPAK TOLELOT_STORE', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (34, 'BUKALAPAK OSCORP COMPANY', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (35, 'BUKALAPAK SENGGOLBACOK', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (36, 'TOKOPEDIA SENGGOLBACOK', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (37, 'TOKOPEDIA BED 2', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (38, 'TOKOPEDIA GROWUPCLOUD', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (39, 'MAYBANK 1757001363 - MOBIL', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (40, 'MAYBANK 1757234725 - TANAH', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (41, 'MAYBANK 8757902830 - ADIS', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (42, 'BCA OPIK BUDIYANTO (BED X JVS)', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (43, 'PIUTANG USAHA', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (44, 'PIUTANG KONSINYASI', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (45, 'PIUTANG PPN', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (46, 'PIUTANG KARYAWAN', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (47, 'BIAYA DIBAYAR DIMUKA', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (48, 'PIUTANG ONGKOS KIRIM', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (49, 'PIUTANG E-MONEY', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (50, 'PPN MASUKAN', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (51, 'PAJAK DIBAYAR DIMUKA', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (52, 'PERSEDIAAN BARANG', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (53, 'ONGKIR PERSEDIAAN', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (54, 'CABANG A', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (55, 'CABANG B', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (56, 'CABANG C', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (57, 'POTONGAN BELI DAN BIAYA LAIN', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (58, 'PERSEDIAAN BARANG DALAM PROSES', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (59, 'BARANG-BARANG KONSINYASI', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (60, 'TANAH', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (61, 'BANGUNAN', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (62, 'AKUMULASI PENYUSUTAN BANGUNAN', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (63, 'KENDARAAN', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (64, 'AKUMULASI PENYUSUTAN KENDARAAN', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (65, 'PERALATAN', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (66, 'AKUMULASI PENYUSUTAN PERALATAN', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (67, 'UANG MUKA PESANAN PEMBELIAN', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (68, 'DANA DEPOSIT DI SUPLIER', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (69, 'HUTANG USAHA', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (70, 'HUTANG BANK', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (71, 'HUTANG KONSINYASI', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (72, 'HUTANG GAJI', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (73, 'HUTANG SALES', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (74, 'HUTANG GAJI PROSES PERAKITAN', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (75, 'HUTANG BIAYA OVERHEAD PROSES PERAKITAN', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (76, 'TITIPAN BIAYA MAKAN', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (77, 'TITIPAN BIAYA HIBURAN & REKREASI', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (78, 'TITIPAN DONASI, ZAKAT, INFAQ & SADAQAH', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (79, 'TITIPAN BIAYA THR', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (80, 'TITIPAN ASURANSI KESEHATAN', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (81, 'UANG MUKA PESANAN PENJUALAN', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (82, 'DEPOSIT PELANGGAN', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (83, 'PPN KELUARAN', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (84, 'HUTANG PAJAK', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (85, 'BARANG-BARANG KONSINYASI', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (86, 'MODAL', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (87, 'LABA DITAHAN', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (88, 'LABA TAHUN BERJALAN', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (89, 'DEVIDEN', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (90, 'PRIVE', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (91, 'HISTORICAL BALANCING', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (92, 'PENDAPATAN JUAL', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (93, 'PENDAPATAN KONSINYASI', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (94, 'POTONGAN JUAL', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (95, 'RETUR JUAL', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (96, 'BIAYA PENGIRIMAN', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (97, 'PENDAPATAN JASA', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (98, 'HARGA POKOK PENJUALAN', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (99, 'POTONGAN PEMBELIAN', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (100, 'KERUGIAN PIUTANG', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (101, 'PENGATURAN STOK', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (102, 'ITEM MASUK', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (103, 'ITEM KELUAR', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (104, 'BIAYA LISTRIK', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (105, 'BIAYA SEWA BANGUNAN', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (106, 'BIAYA ATK', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (107, 'BIAYA BUNGA PINJAMAN', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (108, 'BIAYA INTERNET', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (109, 'BIAYA PENGIRIMAN', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (110, 'BIAYA DONASI, ZAKAT, INFAQ & SADAQAH', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (111, 'BIAYA HIBURAN & REKREASI', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (112, 'BIAYA AIR', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (113, 'BIAYA BENSIN, TOL & PARKIR - UMUM', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (114, 'BIAYA PERBAIKAN & PEMELIHARAAN', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (115, 'BIAYA PERJALANAN DINAS UMUM', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (116, 'BIAYA JAMUAN & MAKAN', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (117, 'BIAYA KOMUNIKASI', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (118, 'BIAYA IURAN RT / RW', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (119, 'BIAYA RUMAH TANGGA KANTOR', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (120, 'BIAYA ALAT TULIS KANTOR & PRINTING', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (121, 'BIAYA KEAMANAN & KEBERSIHAN', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (122, 'BIAYA ADM BANK', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (123, 'BIAYA BUBBLE WARP', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (124, 'BIAYA TELEPON', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (125, 'BIAYA INVENTARIS KANTOR', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (126, 'BIAYA KERTAS & FOTOCOPY', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (127, 'BIAYA LAKBAN', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (128, 'BIAYA PLASTIK', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (129, 'KERUGIAN PIUTANG', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (130, 'BIAYA IKLAN & PROMOSI', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (131, 'BIAYA ENTERTAIN', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (132, 'KOMISI & FEE PENJUALAN', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (133, 'PERJALANAN DINAS - PENJUALAN', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (134, 'BENSIN, TOL & PARKIR - PENJUALAN', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (135, 'BIAYA GAJI PEGAWAI', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (136, 'BIAYA UPAH KERJA', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (137, 'BIAYA BONUS KOMISI', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (138, 'BIAYA LEMBUR', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (139, 'BIAYA ASURANSI PENGOBATAN', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (140, 'THR ', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (141, 'MANFAAT & TUNJANGAN LAINNYA', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (142, 'BIAYA BELI BAHAN BAKU', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (143, 'BIAYA OVERHEAD', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (144, 'BIAYA PENYUSUTAN', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');
INSERT INTO `bank_information` (`id`, `name`, `no_account`, `own_by`, `balance`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES (145, 'BIAYA BELANJA NON INVENTORY', '', '', '0', '', '2022-08-23 16:35:45', '', NULL, '');


#
# TABLE STRUCTURE FOR: customer_information
#

DROP TABLE IF EXISTS `customer_information`;

CREATE TABLE `customer_information` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
  `updated_by` varchar(48) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `customer_code` (`customer_code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

#
# TABLE STRUCTURE FOR: delivery_head
#

DROP TABLE IF EXISTS `delivery_head`;

CREATE TABLE `delivery_head` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
  `note` text DEFAULT NULL,
  `is_controlled_by` varchar(48) DEFAULT NULL,
  `is_delivered` tinyint(1) DEFAULT NULL,
  `is_cancelled` tinyint(1) DEFAULT NULL,
  `is_shipping_cost` tinyint(1) DEFAULT NULL,
  `cancel_note` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` varchar(48) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` varchar(48) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

#
# TABLE STRUCTURE FOR: delivery_list_item
#

DROP TABLE IF EXISTS `delivery_list_item`;

CREATE TABLE `delivery_list_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

#
# TABLE STRUCTURE FOR: email_templates
#

DROP TABLE IF EXISTS `email_templates`;

CREATE TABLE `email_templates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` mediumtext NOT NULL,
  `code` mediumtext NOT NULL,
  `data` mediumtext NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

INSERT INTO `email_templates` (`id`, `name`, `code`, `data`, `created_at`) VALUES (1, 'Reset Password Template', 'reset_password', '<h1><strong>{company_name}</strong></h1>\r\n\r\n<h3>Click on Reset Link to Proceed : <a href=\"{reset_link}\">Reset Now</a></h3>\r\n', '2020-01-03 16:40:14');


#
# TABLE STRUCTURE FOR: expedition
#

DROP TABLE IF EXISTS `expedition`;

CREATE TABLE `expedition` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `expedition_name` text NOT NULL,
  `services_expedition` text NOT NULL,
  `note` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` varchar(48) NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` varchar(48) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

#
# TABLE STRUCTURE FOR: fifo_items
#

DROP TABLE IF EXISTS `fifo_items`;

CREATE TABLE `fifo_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
  `updated_by` varchar(48) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

#
# TABLE STRUCTURE FOR: invoice_payment
#

DROP TABLE IF EXISTS `invoice_payment`;

CREATE TABLE `invoice_payment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
  `description` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

#
# TABLE STRUCTURE FOR: invoice_purchasing
#

DROP TABLE IF EXISTS `invoice_purchasing`;

CREATE TABLE `invoice_purchasing` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
  `pack_by` varchar(48) DEFAULT NULL COMMENT 'who is packing, get information from users',
  `pack` varchar(48) DEFAULT NULL,
  `weight` varchar(48) DEFAULT '0',
  `transaction_destination` varchar(48) DEFAULT NULL,
  `type_payment_shipping` varchar(48) DEFAULT NULL,
  `note_destination` varchar(48) DEFAULT NULL,
  `transaction_source` varchar(48) DEFAULT NULL,
  `is_cancelled` tinyint(1) NOT NULL DEFAULT 0,
  `cancel_note` text DEFAULT NULL,
  `is_consignment` tinyint(1) NOT NULL DEFAULT 0,
  `is_child` tinyint(1) NOT NULL DEFAULT 0,
  `is_transaction` tinyint(1) NOT NULL DEFAULT 1,
  `is_shipping_cost` tinyint(1) DEFAULT 1 COMMENT 'status shipping cost is added to invoice	',
  `is_controlled_by` varchar(48) DEFAULT NULL,
  `is_delivered` tinyint(1) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` varchar(48) DEFAULT NULL,
  `updated_by` varchar(48) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `invoice_code` (`invoice_code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

#
# TABLE STRUCTURE FOR: invoice_selling
#

DROP TABLE IF EXISTS `invoice_selling`;

CREATE TABLE `invoice_selling` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
  `updated_by` varchar(48) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `invoice_code` (`invoice_code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

#
# TABLE STRUCTURE FOR: invoice_transaction_list_item
#

DROP TABLE IF EXISTS `invoice_transaction_list_item`;

CREATE TABLE `invoice_transaction_list_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
  `updated_by` varchar(48) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

#
# TABLE STRUCTURE FOR: items
#

DROP TABLE IF EXISTS `items`;

CREATE TABLE `items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

#
# TABLE STRUCTURE FOR: items_history
#

DROP TABLE IF EXISTS `items_history`;

CREATE TABLE `items_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
  `updated_by` varchar(48) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

#
# TABLE STRUCTURE FOR: order_purchase
#

DROP TABLE IF EXISTS `order_purchase`;

CREATE TABLE `order_purchase` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
  `updated_by` varchar(48) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

#
# TABLE STRUCTURE FOR: order_sale
#

DROP TABLE IF EXISTS `order_sale`;

CREATE TABLE `order_sale` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
  `updated_by` varchar(48) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

#
# TABLE STRUCTURE FOR: order_sale_list_item
#

DROP TABLE IF EXISTS `order_sale_list_item`;

CREATE TABLE `order_sale_list_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
  `updated_by` varchar(48) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

#
# TABLE STRUCTURE FOR: permissions
#

DROP TABLE IF EXISTS `permissions`;

CREATE TABLE `permissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` mediumtext NOT NULL,
  `code` mediumtext DEFAULT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=89 DEFAULT CHARSET=utf8mb4;

INSERT INTO `permissions` (`id`, `title`, `code`, `description`) VALUES (1, 'Users List', 'users_list', 'test\r\n');
INSERT INTO `permissions` (`id`, `title`, `code`, `description`) VALUES (2, 'Add Users', 'users_add', '');
INSERT INTO `permissions` (`id`, `title`, `code`, `description`) VALUES (3, 'Edit Users', 'users_edit', '');
INSERT INTO `permissions` (`id`, `title`, `code`, `description`) VALUES (4, 'Delete Users', 'users_delete', '');
INSERT INTO `permissions` (`id`, `title`, `code`, `description`) VALUES (5, 'Users View', 'users_view', '');
INSERT INTO `permissions` (`id`, `title`, `code`, `description`) VALUES (6, 'Activity Logs List', 'activity_log_list', '');
INSERT INTO `permissions` (`id`, `title`, `code`, `description`) VALUES (7, 'Acivity Log View', 'activity_log_view', '');
INSERT INTO `permissions` (`id`, `title`, `code`, `description`) VALUES (8, 'Roles List', 'roles_list', '');
INSERT INTO `permissions` (`id`, `title`, `code`, `description`) VALUES (9, 'Add Roles', 'roles_add', '');
INSERT INTO `permissions` (`id`, `title`, `code`, `description`) VALUES (10, 'Edit Roles', 'roles_edit', '');
INSERT INTO `permissions` (`id`, `title`, `code`, `description`) VALUES (11, 'Permissions List', 'permissions_list', '');
INSERT INTO `permissions` (`id`, `title`, `code`, `description`) VALUES (12, 'Add Permissions', 'permissions_add', '');
INSERT INTO `permissions` (`id`, `title`, `code`, `description`) VALUES (13, 'Permissions Edit', 'permissions_edit', '');
INSERT INTO `permissions` (`id`, `title`, `code`, `description`) VALUES (14, 'Delete Permissions', 'permissions_delete', '');
INSERT INTO `permissions` (`id`, `title`, `code`, `description`) VALUES (15, 'Company Settings', 'company_settings', '');
INSERT INTO `permissions` (`id`, `title`, `code`, `description`) VALUES (16, 'Backup', 'backup_db', '');
INSERT INTO `permissions` (`id`, `title`, `code`, `description`) VALUES (17, 'Manage Email Templates', 'email_templates', '');
INSERT INTO `permissions` (`id`, `title`, `code`, `description`) VALUES (18, 'General Settings', 'general_settings', '');
INSERT INTO `permissions` (`id`, `title`, `code`, `description`) VALUES (19, 'Invoice Purchase List', 'purchase_list', '');
INSERT INTO `permissions` (`id`, `title`, `code`, `description`) VALUES (20, 'Invoice Purchasing Create', 'purchase_create', '');
INSERT INTO `permissions` (`id`, `title`, `code`, `description`) VALUES (21, 'Items List', 'items_list', '');
INSERT INTO `permissions` (`id`, `title`, `code`, `description`) VALUES (22, 'Add Items', 'items_add', '');
INSERT INTO `permissions` (`id`, `title`, `code`, `description`) VALUES (23, 'Edit Items', 'items_edit', '');
INSERT INTO `permissions` (`id`, `title`, `code`, `description`) VALUES (24, 'Delete Items', 'items_delete', '');
INSERT INTO `permissions` (`id`, `title`, `code`, `description`) VALUES (25, 'Info Item', 'items_info', '');
INSERT INTO `permissions` (`id`, `title`, `code`, `description`) VALUES (28, 'Upload file', 'upload_file', '');
INSERT INTO `permissions` (`id`, `title`, `code`, `description`) VALUES (29, 'Item Empty', 'items_truncate', '');
INSERT INTO `permissions` (`id`, `title`, `code`, `description`) VALUES (30, 'Data Customer', 'customer_list', '');
INSERT INTO `permissions` (`id`, `title`, `code`, `description`) VALUES (31, 'Add Customer', 'customer_add', '');
INSERT INTO `permissions` (`id`, `title`, `code`, `description`) VALUES (32, 'Add Address', 'address_add', '');
INSERT INTO `permissions` (`id`, `title`, `code`, `description`) VALUES (33, 'List Address', 'address_list', '');
INSERT INTO `permissions` (`id`, `title`, `code`, `description`) VALUES (34, 'Edit Customer', 'customer_edit', '');
INSERT INTO `permissions` (`id`, `title`, `code`, `description`) VALUES (35, 'Edit Address', 'address_edit', '');
INSERT INTO `permissions` (`id`, `title`, `code`, `description`) VALUES (36, 'Data Supplier', 'supplier_list', '');
INSERT INTO `permissions` (`id`, `title`, `code`, `description`) VALUES (37, 'Add Supplier', 'supplier_add', '');
INSERT INTO `permissions` (`id`, `title`, `code`, `description`) VALUES (38, 'Edit Supplier', 'supplier_edit', '');
INSERT INTO `permissions` (`id`, `title`, `code`, `description`) VALUES (39, 'Invoice Purchasing Edit', 'purchase_edit', '');
INSERT INTO `permissions` (`id`, `title`, `code`, `description`) VALUES (40, 'Invoice Purchasing Info', 'purchase_info', '');
INSERT INTO `permissions` (`id`, `title`, `code`, `description`) VALUES (41, 'Invoice Purchase Returns', 'purchase_returns', '');
INSERT INTO `permissions` (`id`, `title`, `code`, `description`) VALUES (42, 'Invoice Purchase Returns Edit', 'purchase_returns_edit', '');
INSERT INTO `permissions` (`id`, `title`, `code`, `description`) VALUES (43, 'Invoice Purchase Returns Information', 'purchase_returns_info', '');
INSERT INTO `permissions` (`id`, `title`, `code`, `description`) VALUES (44, 'Invoice Purchase Returns Cancel', 'purchase_returns_cancel', '');
INSERT INTO `permissions` (`id`, `title`, `code`, `description`) VALUES (45, 'Invoice Purchase Payment List', 'purchase_payment_list', '');
INSERT INTO `permissions` (`id`, `title`, `code`, `description`) VALUES (46, 'Invoice Purchase Payment', 'purchase_payment', '');
INSERT INTO `permissions` (`id`, `title`, `code`, `description`) VALUES (47, 'Selling list', 'sale_list', '');
INSERT INTO `permissions` (`id`, `title`, `code`, `description`) VALUES (48, 'Order List', 'order_list', '');
INSERT INTO `permissions` (`id`, `title`, `code`, `description`) VALUES (49, 'Expedition List', 'expedition_list', '');
INSERT INTO `permissions` (`id`, `title`, `code`, `description`) VALUES (50, 'Expedition Create', 'expedition_create', '');
INSERT INTO `permissions` (`id`, `title`, `code`, `description`) VALUES (51, 'Expedition Update', 'expedition_update', '');
INSERT INTO `permissions` (`id`, `title`, `code`, `description`) VALUES (52, 'Expedition Delete', 'expedition_delete', '');
INSERT INTO `permissions` (`id`, `title`, `code`, `description`) VALUES (53, 'Create Order', 'order_create', '');
INSERT INTO `permissions` (`id`, `title`, `code`, `description`) VALUES (54, 'Edit Order', 'order_edit', '');
INSERT INTO `permissions` (`id`, `title`, `code`, `description`) VALUES (55, 'Data Information Invoice Sale', 'warehouse_order_list', 'Admin, Quality Control, Shipping: to show all Invoice SALE');
INSERT INTO `permissions` (`id`, `title`, `code`, `description`) VALUES (56, 'Warehouse Order Validation Available', 'warehouse_order_validation_available', '');
INSERT INTO `permissions` (`id`, `title`, `code`, `description`) VALUES (57, 'Sale Create', 'sale_create', '');
INSERT INTO `permissions` (`id`, `title`, `code`, `description`) VALUES (58, 'Shipper List Transaction', 'shipper_transaction_list', '');
INSERT INTO `permissions` (`id`, `title`, `code`, `description`) VALUES (59, 'Delivered Report', 'report_delivered', 'List information Delevered Pack');
INSERT INTO `permissions` (`id`, `title`, `code`, `description`) VALUES (60, 'Invoice Sales Returns', 'sale_returns', '');
INSERT INTO `permissions` (`id`, `title`, `code`, `description`) VALUES (61, 'Invoice Sales Returns Edit', 'sale_returns_edit', '');
INSERT INTO `permissions` (`id`, `title`, `code`, `description`) VALUES (62, 'Account Bank List', 'account_bank_list', '');
INSERT INTO `permissions` (`id`, `title`, `code`, `description`) VALUES (63, 'Add New Account Bank', 'account_bank_add', '');
INSERT INTO `permissions` (`id`, `title`, `code`, `description`) VALUES (64, 'Update Account Bank', 'account_bank_edit', '');
INSERT INTO `permissions` (`id`, `title`, `code`, `description`) VALUES (65, 'Sale Return List', 'sale_return_list', '');
INSERT INTO `permissions` (`id`, `title`, `code`, `description`) VALUES (66, 'Purchase Return List', 'purchase_return_list', '');
INSERT INTO `permissions` (`id`, `title`, `code`, `description`) VALUES (67, 'Order Info', 'order_info', 'Get Information from Orders item');
INSERT INTO `permissions` (`id`, `title`, `code`, `description`) VALUES (68, 'List Item of FIFO', 'list_fifo', 'This Model is like masturbate');
INSERT INTO `permissions` (`id`, `title`, `code`, `description`) VALUES (69, 'Download File', 'download_file', 'This Permission for download report or file.');
INSERT INTO `permissions` (`id`, `title`, `code`, `description`) VALUES (70, 'Warehouse Report Confirmation', 'warehouse_order_is_confirmation', 'Report Order for Confirmation by ');
INSERT INTO `permissions` (`id`, `title`, `code`, `description`) VALUES (71, 'Quality Control', 'quality_control', 'permission for Quality Control');
INSERT INTO `permissions` (`id`, `title`, `code`, `description`) VALUES (72, 'Report Packing', 'report_packing', 'list information for Packing report');
INSERT INTO `permissions` (`id`, `title`, `code`, `description`) VALUES (73, 'Payment', 'payment', 'Information about payment');
INSERT INTO `permissions` (`id`, `title`, `code`, `description`) VALUES (74, 'Update Balance Bank Account', 'update_balance', 'Update information balance accounts bank. ');
INSERT INTO `permissions` (`id`, `title`, `code`, `description`) VALUES (75, 'Cashflow', 'cashflow', 'List history information transaction account');
INSERT INTO `permissions` (`id`, `title`, `code`, `description`) VALUES (76, 'Drop Items', 'drop_items', 'Drops Item, without information payment and transaction.');
INSERT INTO `permissions` (`id`, `title`, `code`, `description`) VALUES (77, 'Example', 'example', 'Example Page');
INSERT INTO `permissions` (`id`, `title`, `code`, `description`) VALUES (78, 'Dashboard Staff', 'dashboard_staff', 'Permission dshboard for staff');
INSERT INTO `permissions` (`id`, `title`, `code`, `description`) VALUES (79, 'Fetch All Data Invoice Sale', 'fetch_all_invoice_sales', 'Admin, Warehouse, Quality Control, Shipper :Fetch All Invoice Sales');
INSERT INTO `permissions` (`id`, `title`, `code`, `description`) VALUES (80, 'Request Customer', 'requst_data_customer', 'Fetch all data customer');
INSERT INTO `permissions` (`id`, `title`, `code`, `description`) VALUES (81, 'Sale Edit', 'sale_edit', 'Sale Edit or Update Data Information Invoice Sale');
INSERT INTO `permissions` (`id`, `title`, `code`, `description`) VALUES (82, 'Sale Cancel', 'sale_cancel', 'Cancel Invoice Sale, not remove the information, just hide from list');
INSERT INTO `permissions` (`id`, `title`, `code`, `description`) VALUES (83, 'Travel Permits', 'travel_permits', 'Travel Permits Data');
INSERT INTO `permissions` (`id`, `title`, `code`, `description`) VALUES (85, 'Delivery Document', 'delivery_document', 'Information for delivery packet');
INSERT INTO `permissions` (`id`, `title`, `code`, `description`) VALUES (86, 'Create Delivery Document', 'delivery_document_create', 'Create Information Delivery Packet');
INSERT INTO `permissions` (`id`, `title`, `code`, `description`) VALUES (87, 'Request Data Bank', 'requst_data_bank', 'Fetch All Data Bank');
INSERT INTO `permissions` (`id`, `title`, `code`, `description`) VALUES (88, 'Total Debt To ...', 'total_debt_to', 'Information Payment Debt To Suppliers');


#
# TABLE STRUCTURE FOR: role_permissions
#

DROP TABLE IF EXISTS `role_permissions`;

CREATE TABLE `role_permissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role` int(11) NOT NULL,
  `permission` mediumtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=345 DEFAULT CHARSET=utf8mb4;

INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (1, 1, 'users_list');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (2, 1, 'users_add');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (3, 1, 'users_edit');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (4, 1, 'users_delete');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (5, 1, 'users_view');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (6, 1, 'activity_log_view');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (7, 1, 'roles_list');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (8, 1, 'roles_add');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (9, 1, 'roles_edit');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (10, 1, 'permissions_list');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (11, 1, 'permissions_add');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (12, 1, 'permissions_edit');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (13, 1, 'permissions_delete');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (14, 1, 'company_settings');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (15, 1, 'activity_log_list');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (16, 1, 'email_templates');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (17, 1, 'general_settings');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (18, 1, 'backup_db');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (308, 8, 'roles_edit');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (115, 2, 'purchase_returns_info');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (114, 2, 'address_list');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (23, 2, 'users_view');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (309, 3, 'upload_file');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (113, 2, 'roles_list');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (112, 2, 'activity_log_view');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (111, 2, 'activity_log_list');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (37, 1, 'purchase_create');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (38, 1, 'purchase_list');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (39, 1, 'items_list');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (40, 1, 'items_add');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (42, 1, 'items_delete');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (43, 1, 'items_edit');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (45, 1, 'items_info');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (46, 1, 'upload_file');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (47, 1, 'items_truncate');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (48, 1, 'customer_list');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (49, 1, 'customer_add');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (50, 1, 'address_add');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (51, 1, 'address_list');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (52, 1, 'customer_edit');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (53, 1, 'address_edit');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (55, 1, 'supplier_list');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (56, 1, 'supplier_add');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (57, 1, 'supplier_edit');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (58, 1, 'purchase_edit');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (59, 1, 'purchase_info');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (60, 1, 'purchase_returns');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (61, 1, 'purchase_returns_edit');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (62, 1, 'purchase_returns_info');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (63, 1, 'purchase_returns_cancel');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (64, 1, 'purchase_payment_list');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (65, 1, 'purchase_payment');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (66, 1, 'sale_list');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (67, 1, 'order_list');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (68, 1, 'expedition_list');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (69, 1, 'expedition_create');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (70, 1, 'expedition_update');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (71, 1, 'expedition_delete');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (72, 1, 'order_create');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (73, 1, 'order_edit');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (74, 3, 'users_view');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (75, 3, 'items_list');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (76, 3, 'items_info');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (77, 3, 'customer_list');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (108, 2, 'items_info');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (80, 3, 'address_list');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (107, 2, 'items_list');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (83, 3, 'sale_list');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (84, 3, 'order_list');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (85, 3, 'expedition_list');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (86, 3, 'order_create');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (87, 3, 'order_edit');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (88, 1, 'warehouse_order_list');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (89, 1, 'warehouse_order_validation_available');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (90, 1, 'sale_create');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (200, 1, 'payment');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (92, 1, 'shipper_transaction_list');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (94, 3, 'sale_create');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (95, 4, 'warehouse_order_list');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (96, 4, 'warehouse_order_validation_available');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (224, 7, 'report_delivered');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (101, 1, 'sale_returns_edit');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (100, 1, 'sale_returns');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (102, 1, 'account_bank_list');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (103, 1, 'account_bank_add');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (104, 1, 'account_bank_edit');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (105, 1, 'sale_return_list');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (106, 1, 'purchase_return_list');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (109, 2, 'supplier_list');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (110, 2, 'purchase_info');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (116, 2, 'purchase_payment_list');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (117, 2, 'sale_list');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (118, 2, 'order_list');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (119, 2, 'expedition_list');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (120, 2, 'order_create');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (121, 2, 'order_edit');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (122, 2, 'warehouse_order_list');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (123, 2, 'sale_create');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (124, 2, 'shipper_transaction_list');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (125, 2, 'account_bank_list');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (126, 2, 'sale_return_list');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (127, 2, 'purchase_return_list');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (128, 1, 'order_info');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (130, 1, 'list_fifo');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (135, 5, 'users_view');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (150, 5, 'items_list');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (344, 8, 'total_debt_to');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (343, 1, 'total_debt_to');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (154, 5, 'items_info');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (155, 5, 'upload_file');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (157, 5, 'customer_list');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (158, 5, 'customer_add');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (159, 5, 'address_add');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (160, 5, 'address_list');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (342, 8, 'requst_data_bank');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (341, 1, 'requst_data_bank');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (340, 1, 'delivery_document_create');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (339, 1, 'delivery_document');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (338, 1, 'travel_permits');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (337, 9, 'warehouse_order_list');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (336, 9, 'requst_data_customer');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (335, 9, 'sale_create');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (334, 9, 'order_edit');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (333, 9, 'order_create');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (174, 5, 'sale_list');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (175, 5, 'order_list');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (176, 5, 'expedition_list');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (332, 9, 'expedition_list');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (331, 9, 'order_list');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (330, 9, 'sale_list');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (180, 5, 'order_create');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (329, 9, 'address_list');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (328, 9, 'address_add');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (327, 9, 'customer_add');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (184, 5, 'sale_create');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (326, 9, 'customer_list');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (325, 9, 'upload_file');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (324, 9, 'items_info');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (323, 9, 'items_list');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (322, 9, 'users_view');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (321, 7, 'address_edit');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (320, 8, 'sale_cancel');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (319, 1, 'sale_cancel');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (318, 8, 'sale_edit');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (317, 1, 'sale_edit');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (195, 1, 'download_file');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (196, 1, 'warehouse_order_is_confirmation');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (197, 1, 'quality_control');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (198, 1, 'report_delivered');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (199, 1, 'report_packing');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (201, 1, 'update_balance');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (202, 1, 'cashflow');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (232, 3, 'requst_data_customer');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (210, 6, 'quality_control');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (227, 7, 'quality_control');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (213, 7, 'shipper_transaction_list');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (214, 7, 'report_packing');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (230, 6, 'shipper_transaction_list');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (216, 1, 'drop_items');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (217, 1, 'example');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (218, 1, 'dashboard_staff');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (219, 1, 'fetch_all_invoice_sales');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (220, 1, 'requst_data_customer');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (221, 4, 'warehouse_order_is_confirmation');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (222, 4, 'fetch_all_invoice_sales');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (223, 4, 'requst_data_customer');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (225, 7, 'fetch_all_invoice_sales');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (226, 7, 'requst_data_customer');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (228, 6, 'fetch_all_invoice_sales');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (229, 6, 'requst_data_customer');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (306, 8, 'users_list');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (234, 8, 'users_add');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (235, 8, 'users_edit');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (236, 8, 'users_delete');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (237, 8, 'users_view');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (238, 8, 'activity_log_list');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (239, 8, 'activity_log_view');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (240, 8, 'roles_list');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (241, 8, 'roles_add');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (242, 8, 'backup_db');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (243, 8, 'email_templates');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (244, 8, 'general_settings');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (245, 8, 'purchase_list');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (246, 8, 'purchase_create');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (247, 8, 'items_list');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (248, 8, 'items_add');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (249, 8, 'items_edit');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (250, 8, 'items_delete');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (251, 8, 'items_info');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (252, 8, 'upload_file');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (253, 8, 'customer_list');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (254, 8, 'customer_add');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (255, 8, 'address_add');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (256, 8, 'address_list');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (257, 8, 'customer_edit');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (258, 8, 'address_edit');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (259, 8, 'supplier_list');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (260, 8, 'supplier_add');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (261, 8, 'supplier_edit');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (262, 8, 'purchase_edit');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (263, 8, 'purchase_info');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (264, 8, 'purchase_returns');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (265, 8, 'purchase_returns_edit');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (266, 8, 'purchase_returns_info');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (267, 8, 'purchase_returns_cancel');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (268, 8, 'purchase_payment_list');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (269, 8, 'purchase_payment');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (270, 8, 'sale_list');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (271, 8, 'order_list');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (272, 8, 'expedition_list');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (273, 8, 'expedition_create');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (274, 8, 'expedition_update');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (275, 8, 'expedition_delete');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (276, 8, 'order_create');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (277, 8, 'order_edit');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (311, 2, 'purchase_list');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (279, 8, 'warehouse_order_validation_available');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (280, 8, 'sale_create');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (310, 8, 'warehouse_order_list');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (282, 8, 'report_delivered');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (283, 8, 'sale_returns');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (284, 8, 'sale_returns_edit');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (285, 8, 'account_bank_list');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (286, 8, 'account_bank_add');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (287, 8, 'account_bank_edit');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (288, 8, 'sale_return_list');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (289, 8, 'purchase_return_list');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (290, 8, 'order_info');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (291, 8, 'list_fifo');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (292, 8, 'download_file');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (293, 8, 'warehouse_order_is_confirmation');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (294, 8, 'quality_control');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (295, 8, 'report_packing');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (296, 8, 'payment');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (297, 8, 'update_balance');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (298, 8, 'cashflow');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (299, 8, 'drop_items');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (300, 8, 'dashboard_staff');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (301, 8, 'fetch_all_invoice_sales');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (302, 8, 'requst_data_customer');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (303, 4, 'items_list');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (304, 7, 'customer_list');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (305, 7, 'customer_edit');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (312, 2, 'upload_file');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (313, 2, 'customer_list');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (314, 2, 'report_delivered');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (315, 2, 'report_packing');
INSERT INTO `role_permissions` (`id`, `role`, `permission`) VALUES (316, 5, 'requst_data_customer');


#
# TABLE STRUCTURE FOR: roles
#

DROP TABLE IF EXISTS `roles`;

CREATE TABLE `roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` mediumtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4;

INSERT INTO `roles` (`id`, `title`) VALUES (1, 'Administrator');
INSERT INTO `roles` (`id`, `title`) VALUES (2, 'Manager');
INSERT INTO `roles` (`id`, `title`) VALUES (3, 'Marketing');
INSERT INTO `roles` (`id`, `title`) VALUES (4, 'Warehouse');
INSERT INTO `roles` (`id`, `title`) VALUES (5, 'Head Marketing');
INSERT INTO `roles` (`id`, `title`) VALUES (6, 'Quality Control');
INSERT INTO `roles` (`id`, `title`) VALUES (7, 'Shipper');
INSERT INTO `roles` (`id`, `title`) VALUES (8, 'Admin System');
INSERT INTO `roles` (`id`, `title`) VALUES (9, 'Admin Marketing');


#
# TABLE STRUCTURE FOR: settings
#

DROP TABLE IF EXISTS `settings`;

CREATE TABLE `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `key` mediumtext NOT NULL,
  `value` mediumtext NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4;

INSERT INTO `settings` (`id`, `key`, `value`, `created_at`) VALUES (1, 'company_name', 'Bandung Ejuice Distribution', '2018-06-21 10:37:59');
INSERT INTO `settings` (`id`, `key`, `value`, `created_at`) VALUES (2, 'company_email', 'iyang_agung_s@protonmail.com', '2018-07-11 04:09:58');
INSERT INTO `settings` (`id`, `key`, `value`, `created_at`) VALUES (3, 'timezone', 'Asia/Jakarta', '2018-07-15 12:54:17');
INSERT INTO `settings` (`id`, `key`, `value`, `created_at`) VALUES (4, 'login_theme', '1', '2019-06-06 07:04:28');
INSERT INTO `settings` (`id`, `key`, `value`, `created_at`) VALUES (5, 'date_format', 'd F, Y', '2020-01-03 18:31:45');
INSERT INTO `settings` (`id`, `key`, `value`, `created_at`) VALUES (6, 'datetime_format', 'h:i a - d M, Y ', '2020-01-03 18:32:24');
INSERT INTO `settings` (`id`, `key`, `value`, `created_at`) VALUES (7, 'google_recaptcha_enabled', '0', '2020-01-04 17:44:03');
INSERT INTO `settings` (`id`, `key`, `value`, `created_at`) VALUES (8, 'google_recaptcha_sitekey', '6LdIWswUAAAAAMRp6xt2wBu7V59jUvZvKWf_rbJc', '2020-01-04 17:44:17');
INSERT INTO `settings` (`id`, `key`, `value`, `created_at`) VALUES (9, 'google_recaptcha_secretkey', '6LdIWswUAAAAAIsdboq_76c63PHFsOPJHNR-z-75', '2020-01-04 17:44:40');
INSERT INTO `settings` (`id`, `key`, `value`, `created_at`) VALUES (10, 'bg_img_type', 'jpeg', '2020-01-06 16:53:33');
INSERT INTO `settings` (`id`, `key`, `value`, `created_at`) VALUES (11, 'default_lang', 'id', '2021-04-12 08:53:06');
INSERT INTO `settings` (`id`, `key`, `value`, `created_at`) VALUES (12, 'company_icon', '1654922869.png', '2022-01-12 07:00:58');


#
# TABLE STRUCTURE FOR: supplier_information
#

DROP TABLE IF EXISTS `supplier_information`;

CREATE TABLE `supplier_information` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
  `updated_by` varchar(48) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `customer_code` (`customer_code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

#
# TABLE STRUCTURE FOR: users
#

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4;

INSERT INTO `users` (`id`, `name`, `username`, `email`, `password`, `phone`, `address`, `last_login`, `role`, `reset_token`, `status`, `is_logged`, `img_type`, `created_at`, `updated_at`) VALUES (1, 'Iyang', 'admin', 'admin@administrator.com', '749b52356b9140cce3ca933b058f8d0b5d29001dff418df2aea27e81d2e5e347', '6289862327', 'Cipanteneun, Licin, Cimalaka, Sumedang, Jawabarat', '2022-08-26 13:08:44', 1, '$2y$10$XuKmLc8HfE/4U53ggD50eOiasBQD10TdVPITbSmN5GOQVX5n4d94.', 1, 1, 'jpg', '2018-06-27 11:30:16', '0000-00-00 00:00:00');
INSERT INTO `users` (`id`, `name`, `username`, `email`, `password`, `phone`, `address`, `last_login`, `role`, `reset_token`, `status`, `is_logged`, `img_type`, `created_at`, `updated_at`) VALUES (7, 'Yuni', 'bedyuni', 'yuni_admin@admin', '8096781b587655ee8f951ce868dfda2755b59f4cae09ee7712af43bcae5db89f', '0', '0', '2022-08-26 13:08:39', 8, '', 1, 1, 'png', '2022-04-20 12:37:56', NULL);
INSERT INTO `users` (`id`, `name`, `username`, `email`, `password`, `phone`, `address`, `last_login`, `role`, `reset_token`, `status`, `is_logged`, `img_type`, `created_at`, `updated_at`) VALUES (3, 'Fauzi', 'bedfauzi', 'fauzi@marketing', '50658374df8ab3c3c41965a7b01f08309010c693423f5877c56bfb4510326033', '0', '0', '2022-08-25 19:32:26', 3, '', 1, 0, 'png', '2022-01-10 02:04:27', '0000-00-00 00:00:00');
INSERT INTO `users` (`id`, `name`, `username`, `email`, `password`, `phone`, `address`, `last_login`, `role`, `reset_token`, `status`, `is_logged`, `img_type`, `created_at`, `updated_at`) VALUES (8, 'Arnie', 'bedarnie', 'arnie_admin@admin', '00db12cd13d8badd6a81e549097cb9f43d81e13191f41ad401c1d0658e19128e', '0', '0', '2022-08-12 11:08:02', 8, '', 1, 1, 'png', '2022-04-20 12:40:14', NULL);
INSERT INTO `users` (`id`, `name`, `username`, `email`, `password`, `phone`, `address`, `last_login`, `role`, `reset_token`, `status`, `is_logged`, `img_type`, `created_at`, `updated_at`) VALUES (5, 'Gentur', 'bedgentur', 'gentur@admin.com', 'cbebf8c8da4c995497ac7a51311f3a4cd5f385dc61afcc8e4598b3ff0a5da127', '0', '0', '2022-06-14 11:50:32', 8, '', 1, 0, 'png', '2022-04-08 13:15:56', NULL);
INSERT INTO `users` (`id`, `name`, `username`, `email`, `password`, `phone`, `address`, `last_login`, `role`, `reset_token`, `status`, `is_logged`, `img_type`, `created_at`, `updated_at`) VALUES (6, 'Apif', 'bedapif', 'apif@admin.com', '1b68eb8e3463748b1cb1ec05895deba637e88ded428cf9a5e90720767af4b7a8', '0', '0', '2022-07-07 14:33:09', 8, '', 1, 0, 'png', '2022-04-08 13:16:53', NULL);
INSERT INTO `users` (`id`, `name`, `username`, `email`, `password`, `phone`, `address`, `last_login`, `role`, `reset_token`, `status`, `is_logged`, `img_type`, `created_at`, `updated_at`) VALUES (9, 'Wahyu', 'wahyu_warehouse', 'wahyu@warehouse', 'ae1fd358c7612a02fdc6d923fd40308ebefb0e954c7ddb6f9a8bcdd1f3b00c3b', '0', '0', '2022-08-09 14:08:19', 4, '$2y$10$NPIf12KIMwi4sFmI8879HOrxmvO0V9rX4roC2ZAVx6Sl9nCEYbV06', 1, 1, 'png', '2022-04-20 16:35:32', NULL);
INSERT INTO `users` (`id`, `name`, `username`, `email`, `password`, `phone`, `address`, `last_login`, `role`, `reset_token`, `status`, `is_logged`, `img_type`, `created_at`, `updated_at`) VALUES (10, 'Kiki', 'bedkiki', 'kiki@qualitycontrol.com', 'b9aba08fd6dbbf23259a9b0ebfdab5b1cfe279527d15febdcd0690c80098d771', '0', '0', '2022-08-12 13:19:09', 6, '', 1, 0, 'png', '2022-06-02 14:12:41', NULL);
INSERT INTO `users` (`id`, `name`, `username`, `email`, `password`, `phone`, `address`, `last_login`, `role`, `reset_token`, `status`, `is_logged`, `img_type`, `created_at`, `updated_at`) VALUES (11, 'Toeti', 'cantik', 'pujiastuti@shipping', 'c8824394c8bc50faec3065c142fc90721998d3c531cd864cd4bd2b56885c3323', '0', '0', '2022-08-05 13:08:09', 7, '', 1, 1, 'png', '2022-06-03 13:14:10', NULL);
INSERT INTO `users` (`id`, `name`, `username`, `email`, `password`, `phone`, `address`, `last_login`, `role`, `reset_token`, `status`, `is_logged`, `img_type`, `created_at`, `updated_at`) VALUES (12, 'Aulia', 'bedaulia', 'bedaul@admin.com', 'd70385ef6ec2322534fedf0de5365a17a90c83c228289eecfd277085125ee198', '0', '0', '2022-06-04 15:06:30', 8, '', 1, 1, 'png', '2022-06-04 15:30:24', NULL);
INSERT INTO `users` (`id`, `name`, `username`, `email`, `password`, `phone`, `address`, `last_login`, `role`, `reset_token`, `status`, `is_logged`, `img_type`, `created_at`, `updated_at`) VALUES (13, 'Rifky', 'bedrifky', 'rifky@marketingmanager.com', 'd19b40c690446e885766b9d6552b2ec00f36c211f79f5f3a01d34e310058699f', '0', '0', '2022-08-12 13:20:57', 3, '', 1, 0, 'png', '2022-06-06 12:34:12', NULL);
INSERT INTO `users` (`id`, `name`, `username`, `email`, `password`, `phone`, `address`, `last_login`, `role`, `reset_token`, `status`, `is_logged`, `img_type`, `created_at`, `updated_at`) VALUES (14, 'Bagus', 'bedbagus', 'bagus@warehouse', '97af6e5a490581f60dc9df5a872a962da45dab3278878259e258245347b1be2c', '0', '0', '2022-06-15 10:05:40', 4, '', 1, 0, 'png', '2022-06-09 15:33:46', NULL);
INSERT INTO `users` (`id`, `name`, `username`, `email`, `password`, `phone`, `address`, `last_login`, `role`, `reset_token`, `status`, `is_logged`, `img_type`, `created_at`, `updated_at`) VALUES (15, 'Dedi Mahendra ', 'beddedi', 'dedi@warehouse', 'de7494be9efd718e0461a0a68992e0fc8236f99219749a38f84898709ff04e4c', '0', '0', '2022-08-23 10:08:56', 4, '', 1, 1, 'jpg', '2022-06-11 09:45:58', NULL);
INSERT INTO `users` (`id`, `name`, `username`, `email`, `password`, `phone`, `address`, `last_login`, `role`, `reset_token`, `status`, `is_logged`, `img_type`, `created_at`, `updated_at`) VALUES (16, 'Fadhil', 'bedfadhil', 'fadhil@marketing.com', '79fe266a634c632aea17a2acaf7662161eb57334da00a166342a49887d4c5ef0', '0', '0', '2022-08-25 19:31:35', 3, '', 1, 0, 'png', '2022-06-13 11:38:48', NULL);
INSERT INTO `users` (`id`, `name`, `username`, `email`, `password`, `phone`, `address`, `last_login`, `role`, `reset_token`, `status`, `is_logged`, `img_type`, `created_at`, `updated_at`) VALUES (17, 'Heru', 'bedheru', 'heru@marketing', 'ff87c01afde10b72b0e9f3c23f8e444e8d319ae01098bd409951b914464496be', 'HERU - 0812-1417-9226', '0', '2022-08-26 10:59:16', 3, '', 1, 0, 'png', '2022-06-14 15:10:20', NULL);
INSERT INTO `users` (`id`, `name`, `username`, `email`, `password`, `phone`, `address`, `last_login`, `role`, `reset_token`, `status`, `is_logged`, `img_type`, `created_at`, `updated_at`) VALUES (18, 'Agung', 'bedagung', 'agung@marketing', 'd150a9226f147c18930a3eb099de38f9262462d4f032e82b3ebd43db05792e65', 'AGUNG - 0813-1250-9011', '0', '2022-08-25 19:32:09', 3, '', 1, 0, 'png', '2022-06-17 13:03:47', NULL);
INSERT INTO `users` (`id`, `name`, `username`, `email`, `password`, `phone`, `address`, `last_login`, `role`, `reset_token`, `status`, `is_logged`, `img_type`, `created_at`, `updated_at`) VALUES (19, 'Aep', 'bedaep', 'aep@marketing', '6bfee26cb25cf1d39c636f1b5a8346cb72f7f359729efa760557e6ab92613fb9', '0', '0', '2022-06-24 14:55:07', 3, '', 1, 0, 'png', '2022-06-21 12:42:51', NULL);
INSERT INTO `users` (`id`, `name`, `username`, `email`, `password`, `phone`, `address`, `last_login`, `role`, `reset_token`, `status`, `is_logged`, `img_type`, `created_at`, `updated_at`) VALUES (20, 'Fahmi', 'bedfahmi', 'fahmi@marketingonline', '897053fff8842ed613eac5ade787b0f24b56f3eca43cc4dc4999b7db7d6c7da2', '0', '0', '2022-08-13 17:22:41', 3, '', 1, 0, 'png', '2022-06-21 12:43:52', NULL);
INSERT INTO `users` (`id`, `name`, `username`, `email`, `password`, `phone`, `address`, `last_login`, `role`, `reset_token`, `status`, `is_logged`, `img_type`, `created_at`, `updated_at`) VALUES (21, 'Nita', 'bednita', 'nita@marketingonline', '50ea7847ffbad5dc455682c74cd92ece7ba6dbb2d59a0a27c4343ada7f19b2c2', '0', '0', '2022-06-21 12:44:23', 3, '', 1, 0, 'png', '2022-06-21 12:44:23', NULL);
INSERT INTO `users` (`id`, `name`, `username`, `email`, `password`, `phone`, `address`, `last_login`, `role`, `reset_token`, `status`, `is_logged`, `img_type`, `created_at`, `updated_at`) VALUES (22, 'Reseller', 'bedreseller', 'reseller@reseller', 'c2e41ec1d2dc1f4167014bc0b0513741cb048e8c5ce36543454fe1f177e10d44', '0', '0', '2022-06-21 12:45:55', 3, '', 1, 0, 'png', '2022-06-21 12:45:55', NULL);
INSERT INTO `users` (`id`, `name`, `username`, `email`, `password`, `phone`, `address`, `last_login`, `role`, `reset_token`, `status`, `is_logged`, `img_type`, `created_at`, `updated_at`) VALUES (23, 'Distribution', 'beddistribution', 'distribution@distribution', '0813842bba199fe80f049d5361ada811565f04d560fcc140a3d704a53d06af09', '0', '0', '2022-06-21 12:46:34', 3, '', 1, 0, 'png', '2022-06-21 12:46:34', NULL);
INSERT INTO `users` (`id`, `name`, `username`, `email`, `password`, `phone`, `address`, `last_login`, `role`, `reset_token`, `status`, `is_logged`, `img_type`, `created_at`, `updated_at`) VALUES (24, 'Sita', 'bedsita', 'sita@marketing', 'a334fe8e032d04402b7420f560357e291cf0a38e3ec0549f76509c99a87690d3', 'SITA - 0821-2351-1151', '0', '2022-08-25 19:31:21', 3, '', 1, 0, 'png', '2022-06-21 13:58:40', NULL);
INSERT INTO `users` (`id`, `name`, `username`, `email`, `password`, `phone`, `address`, `last_login`, `role`, `reset_token`, `status`, `is_logged`, `img_type`, `created_at`, `updated_at`) VALUES (25, 'Anita', 'bedanita', 'anita@adminmarketing', 'a2f15df5f666faafa476103ec57cca601f0c7d722147f9b52b8386ab6e0b6f38', '0', '0', '2022-06-24 13:33:43', 9, '', 1, 1, 'png', '2022-06-22 13:46:29', NULL);
INSERT INTO `users` (`id`, `name`, `username`, `email`, `password`, `phone`, `address`, `last_login`, `role`, `reset_token`, `status`, `is_logged`, `img_type`, `created_at`, `updated_at`) VALUES (26, 'Promo', 'bedpromo', 'promo@formarketing', '8679a3a34e36c5b5e00e611e3fe7f2615108839ab3c246d0ef3d0df93dcc0721', '0', '0', '2022-06-22 18:55:52', 3, '', 0, 0, 'png', '2022-06-22 18:55:52', NULL);
INSERT INTO `users` (`id`, `name`, `username`, `email`, `password`, `phone`, `address`, `last_login`, `role`, `reset_token`, `status`, `is_logged`, `img_type`, `created_at`, `updated_at`) VALUES (27, 'Cepi', 'bedcepi', 'cepi@warehouse', '2993f71686c773fc3db35d4d776ec1d7df471f07b11140174ba9a2d8488f436d', '0', '0', '2022-06-25 17:06:14', 4, '', 1, 1, 'png', '2022-06-25 14:10:34', NULL);
INSERT INTO `users` (`id`, `name`, `username`, `email`, `password`, `phone`, `address`, `last_login`, `role`, `reset_token`, `status`, `is_logged`, `img_type`, `created_at`, `updated_at`) VALUES (28, 'Staff', 'bedstaff', 'staff@marketing', '79983e573c818c62c61a479e7a560f64286ee4ac53ee537559a09acb546d6ee6', '0', '0', '2022-06-25 17:33:14', 3, '', 0, 0, 'png', '2022-06-25 17:32:44', NULL);
INSERT INTO `users` (`id`, `name`, `username`, `email`, `password`, `phone`, `address`, `last_login`, `role`, `reset_token`, `status`, `is_logged`, `img_type`, `created_at`, `updated_at`) VALUES (29, 'No Cuan', 'bednocuan', 'nocuan@marketing', 'bc0461ce783da756c35520fc9dcd0b4cb798c5bc6781767f5bed38febfac180f', '0', '0', '2022-06-27 18:34:40', 3, '', 0, 0, 'png', '2022-06-27 18:34:40', NULL);


