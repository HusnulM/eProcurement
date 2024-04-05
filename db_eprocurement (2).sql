-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 05 Apr 2024 pada 10.47
-- Versi server: 8.0.29
-- Versi PHP: 7.4.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_eprocurement`
--

DELIMITER $$
--
-- Fungsi
--
CREATE DEFINER=`root`@`localhost` FUNCTION `fGetDatabaseLocalDatetime` () RETURNS DATETIME READS SQL DATA
BEGIN
DECLARE _return datetime;
SET _return = (SELECT now());
return (_return);
END$$

CREATE DEFINER=`root`@`localhost` FUNCTION `fGetEmail` (`pUserid` INT) RETURNS VARCHAR(100) CHARSET utf8mb4 COLLATE utf8mb4_unicode_ci BEGIN
    DECLARE hasil VARCHAR(80);
	
    SET hasil = (SELECT email from users where id = pUserid);
    	-- return the customer level
	RETURN (hasil);
END$$

CREATE DEFINER=`root`@`localhost` FUNCTION `fGetQuantityCreatedPRItem` (`pPrnum` VARCHAR(80), `pPritem` INT) RETURNS DECIMAL(18,3) BEGIN
DECLARE poCreatedQty decimal(18,3) DEFAULT 0;
    
    SET poCreatedQty = (SELECT COALESCE(sum(quantity),0) from t_po02 where prnum = pPrnum and pritem = pPritem);
    
    RETURN (poCreatedQty);
END$$

CREATE DEFINER=`root`@`localhost` FUNCTION `fGetUserName` (`pUserid` INT) RETURNS VARCHAR(100) CHARSET utf8mb4 COLLATE utf8mb4_unicode_ci BEGIN
    DECLARE hasil VARCHAR(50);
	
    SET hasil = (SELECT name from users where id = pUserid);
    	-- return the customer level
	RETURN (hasil);
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `dcn_nriv`
--

CREATE TABLE `dcn_nriv` (
  `year` int NOT NULL,
  `month` varchar(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `object` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `current_number` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `createdon` date NOT NULL,
  `createdby` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `dcn_nriv`
--

INSERT INTO `dcn_nriv` (`year`, `month`, `object`, `current_number`, `createdon`, `createdby`) VALUES
(2024, '03', 'BATCH', '10', '2024-03-11', 'husnulmub@gmail.com'),
(2024, '03', 'PR', '1', '2024-03-04', 'sys-admin');

-- --------------------------------------------------------

--
-- Struktur dari tabel `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `file_types`
--

CREATE TABLE `file_types` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_of_files` int NOT NULL,
  `labels` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_validations` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_maxsize` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `createdby` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `updatedby` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `general_setting`
--

CREATE TABLE `general_setting` (
  `id` int NOT NULL,
  `setting_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `setting_value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `createdby` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `createdon` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `general_setting`
--

INSERT INTO `general_setting` (`id`, `setting_name`, `setting_value`, `createdby`, `createdon`) VALUES
(1, 'COMPANY_LOGO', 'storage/files/companylogo/KRS-Logo.jpg', 'sys-admin', '2022-08-17 22:19:52'),
(2, 'APP_THEME', 'SBAR', 'sys-admin', '2022-08-17 22:19:52'),
(3, 'APP_BGIMAGE', 'assets/img/20210123135013.jpg', 'sys-admin', '2022-10-14 10:52:57'),
(4, 'COMPANY_ADDRESS', 'Wisma 77, Letjen S. Parman St No.Kav 77 Lantai 9, RT.6/RW.3, Slipi, Palmerah, West Jakarta City, Jakarta 11410', 'sys-admin', '2023-04-01 13:19:50'),
(5, 'WORKFLOW_PR_ACTIVE', 'Y', 'sys-admin', '2024-03-05 15:58:09'),
(6, 'WORKFLOW_PO_ACTIVE', 'Y', 'sys-admin', '2024-03-05 15:58:09');

-- --------------------------------------------------------

--
-- Struktur dari tabel `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `jobs`
--

INSERT INTO `jobs` (`id`, `queue`, `payload`, `attempts`, `reserved_at`, `available_at`, `created_at`) VALUES
(110, 'default', '{\"uuid\":\"c15b53de-d7cf-414a-b948-e359ee68abb7\",\"displayName\":\"App\\\\Mail\\\\NotifApprovePbjMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":13:{s:8:\\\"mailable\\\";O:28:\\\"App\\\\Mail\\\\NotifApprovePbjMail\\\":31:{s:4:\\\"data\\\";O:29:\\\"Illuminate\\\\Support\\\\Collection\\\":2:{s:8:\\\"\\u0000*\\u0000items\\\";a:2:{i:0;O:8:\\\"stdClass\\\":34:{s:2:\\\"id\\\";i:660;s:9:\\\"pbjnumber\\\";s:17:\\\"PBJ-IT\\/2023000007\\\";s:6:\\\"deptid\\\";i:1;s:7:\\\"tgl_pbj\\\";s:10:\\\"2023-11-07\\\";s:17:\\\"tujuan_permintaan\\\";s:8:\\\"Gudang A\\\";s:6:\\\"kepada\\\";s:4:\\\"HSSE\\\";s:9:\\\"unit_desc\\\";s:7:\\\"EXS-501\\\";s:12:\\\"engine_model\\\";s:3:\\\"tes\\\";s:10:\\\"chassis_sn\\\";s:13:\\\"SY048DCB16128\\\";s:9:\\\"reference\\\";s:4:\\\"test\\\";s:9:\\\"requestor\\\";s:13:\\\"Administrator\\\";s:10:\\\"type_model\\\";s:11:\\\"SANY SY500H\\\";s:4:\\\"user\\\";s:5:\\\"UJANG\\\";s:13:\\\"kode_brg_jasa\\\";s:6:\\\"jasa01\\\";s:9:\\\"engine_sn\\\";s:3:\\\"tes\\\";s:5:\\\"hm_km\\\";s:1:\\\"0\\\";s:2:\\\"km\\\";s:1:\\\"0\\\";s:16:\\\"budget_cost_code\\\";s:1:\\\"1\\\";s:14:\\\"cheklistnumber\\\";N;s:10:\\\"pbj_status\\\";s:1:\\\"O\\\";s:9:\\\"createdby\\\";s:28:\\\"fransiskusaditya88@gmail.com\\\";s:9:\\\"createdon\\\";s:19:\\\"2023-11-07 14:44:54\\\";s:7:\\\"pbjitem\\\";i:1;s:10:\\\"partnumber\\\";s:11:\\\"07063-01054\\\";s:11:\\\"description\\\";s:24:\\\"Element Hydraulic Filter\\\";s:8:\\\"quantity\\\";s:5:\\\"2.000\\\";s:4:\\\"unit\\\";s:2:\\\"PC\\\";s:6:\\\"figure\\\";s:11:\\\"test update\\\";s:6:\\\"remark\\\";s:4:\\\"reaa\\\";s:11:\\\"approvestat\\\";s:1:\\\"O\\\";s:9:\\\"prcreated\\\";s:1:\\\"N\\\";s:9:\\\"wocreated\\\";s:1:\\\"N\\\";s:7:\\\"duedate\\\";i:0;s:8:\\\"deptname\\\";s:2:\\\"IT\\\";}i:1;O:8:\\\"stdClass\\\":34:{s:2:\\\"id\\\";i:660;s:9:\\\"pbjnumber\\\";s:17:\\\"PBJ-IT\\/2023000007\\\";s:6:\\\"deptid\\\";i:1;s:7:\\\"tgl_pbj\\\";s:10:\\\"2023-11-07\\\";s:17:\\\"tujuan_permintaan\\\";s:8:\\\"Gudang A\\\";s:6:\\\"kepada\\\";s:4:\\\"HSSE\\\";s:9:\\\"unit_desc\\\";s:7:\\\"EXS-501\\\";s:12:\\\"engine_model\\\";s:3:\\\"tes\\\";s:10:\\\"chassis_sn\\\";s:13:\\\"SY048DCB16128\\\";s:9:\\\"reference\\\";s:4:\\\"test\\\";s:9:\\\"requestor\\\";s:13:\\\"Administrator\\\";s:10:\\\"type_model\\\";s:11:\\\"SANY SY500H\\\";s:4:\\\"user\\\";s:5:\\\"UJANG\\\";s:13:\\\"kode_brg_jasa\\\";s:6:\\\"jasa01\\\";s:9:\\\"engine_sn\\\";s:3:\\\"tes\\\";s:5:\\\"hm_km\\\";s:1:\\\"0\\\";s:2:\\\"km\\\";s:1:\\\"0\\\";s:16:\\\"budget_cost_code\\\";s:1:\\\"1\\\";s:14:\\\"cheklistnumber\\\";N;s:10:\\\"pbj_status\\\";s:1:\\\"O\\\";s:9:\\\"createdby\\\";s:28:\\\"fransiskusaditya88@gmail.com\\\";s:9:\\\"createdon\\\";s:19:\\\"2023-11-07 14:44:54\\\";s:7:\\\"pbjitem\\\";i:2;s:10:\\\"partnumber\\\";s:11:\\\"07063-01100\\\";s:11:\\\"description\\\";s:24:\\\"Element Hydraulic Filter\\\";s:8:\\\"quantity\\\";s:5:\\\"2.000\\\";s:4:\\\"unit\\\";s:2:\\\"PC\\\";s:6:\\\"figure\\\";s:4:\\\"coba\\\";s:6:\\\"remark\\\";s:3:\\\"ada\\\";s:11:\\\"approvestat\\\";s:1:\\\"O\\\";s:9:\\\"prcreated\\\";s:1:\\\"N\\\";s:9:\\\"wocreated\\\";s:1:\\\"N\\\";s:7:\\\"duedate\\\";i:0;s:8:\\\"deptname\\\";s:2:\\\"IT\\\";}}s:28:\\\"\\u0000*\\u0000escapeWhenCastingToString\\\";b:0;}s:5:\\\"pbjid\\\";i:660;s:6:\\\"pbjnum\\\";s:17:\\\"PBJ-IT\\/2023000007\\\";s:6:\\\"locale\\\";N;s:4:\\\"from\\\";a:0:{}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:28:\\\"fransiskusaditya88@gmail.com\\\";}}s:2:\\\"cc\\\";a:0:{}s:3:\\\"bcc\\\";a:0:{}s:7:\\\"replyTo\\\";a:0:{}s:7:\\\"subject\\\";N;s:8:\\\"markdown\\\";N;s:7:\\\"\\u0000*\\u0000html\\\";N;s:4:\\\"view\\\";N;s:8:\\\"textView\\\";N;s:8:\\\"viewData\\\";a:0:{}s:11:\\\"attachments\\\";a:0:{}s:14:\\\"rawAttachments\\\";a:0:{}s:15:\\\"diskAttachments\\\";a:0:{}s:9:\\\"callbacks\\\";a:0:{}s:5:\\\"theme\\\";N;s:6:\\\"mailer\\\";s:4:\\\"smtp\\\";s:29:\\\"\\u0000*\\u0000assertionableRenderStrings\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\"}}', 0, NULL, 1699343095, 1699343095),
(111, 'default', '{\"uuid\":\"0d39582f-7b9d-48b3-8423-c240445646f1\",\"displayName\":\"App\\\\Mail\\\\NotifApprovePrMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":13:{s:8:\\\"mailable\\\";O:27:\\\"App\\\\Mail\\\\NotifApprovePrMail\\\":31:{s:4:\\\"data\\\";O:29:\\\"Illuminate\\\\Support\\\\Collection\\\":2:{s:8:\\\"\\u0000*\\u0000items\\\";a:2:{i:0;O:8:\\\"stdClass\\\":26:{s:2:\\\"id\\\";i:595;s:5:\\\"prnum\\\";s:16:\\\"PR-IT\\/2023000004\\\";s:6:\\\"typepr\\\";N;s:4:\\\"note\\\";N;s:6:\\\"prdate\\\";s:10:\\\"2023-11-07\\\";s:8:\\\"relgroup\\\";N;s:11:\\\"approvestat\\\";s:1:\\\"N\\\";s:9:\\\"requestby\\\";s:13:\\\"Administrator\\\";s:9:\\\"warehouse\\\";N;s:9:\\\"idproject\\\";N;s:6:\\\"remark\\\";s:7:\\\"Testing\\\";s:5:\\\"appby\\\";N;s:6:\\\"deptid\\\";i:1;s:9:\\\"createdon\\\";s:19:\\\"2023-11-07 14:51:28\\\";s:9:\\\"createdby\\\";s:28:\\\"fransiskusaditya88@gmail.com\\\";s:6:\\\"pritem\\\";i:1;s:8:\\\"material\\\";s:11:\\\"07063-01054\\\";s:7:\\\"matdesc\\\";s:24:\\\"Element Hydraulic Filter\\\";s:8:\\\"quantity\\\";s:5:\\\"1.000\\\";s:4:\\\"unit\\\";s:2:\\\"PC\\\";s:9:\\\"pocreated\\\";s:1:\\\"N\\\";s:9:\\\"pbjnumber\\\";s:17:\\\"PBJ-IT\\/2023000007\\\";s:7:\\\"pbjitem\\\";i:1;s:7:\\\"no_plat\\\";s:7:\\\"EXS-501\\\";s:7:\\\"duedate\\\";i:0;s:8:\\\"deptname\\\";s:2:\\\"IT\\\";}i:1;O:8:\\\"stdClass\\\":26:{s:2:\\\"id\\\";i:595;s:5:\\\"prnum\\\";s:16:\\\"PR-IT\\/2023000004\\\";s:6:\\\"typepr\\\";N;s:4:\\\"note\\\";N;s:6:\\\"prdate\\\";s:10:\\\"2023-11-07\\\";s:8:\\\"relgroup\\\";N;s:11:\\\"approvestat\\\";s:1:\\\"N\\\";s:9:\\\"requestby\\\";s:13:\\\"Administrator\\\";s:9:\\\"warehouse\\\";N;s:9:\\\"idproject\\\";N;s:6:\\\"remark\\\";s:7:\\\"Testing\\\";s:5:\\\"appby\\\";N;s:6:\\\"deptid\\\";i:1;s:9:\\\"createdon\\\";s:19:\\\"2023-11-07 14:51:28\\\";s:9:\\\"createdby\\\";s:28:\\\"fransiskusaditya88@gmail.com\\\";s:6:\\\"pritem\\\";i:2;s:8:\\\"material\\\";s:11:\\\"07063-01100\\\";s:7:\\\"matdesc\\\";s:24:\\\"Element Hydraulic Filter\\\";s:8:\\\"quantity\\\";s:5:\\\"1.000\\\";s:4:\\\"unit\\\";s:2:\\\"PC\\\";s:9:\\\"pocreated\\\";s:1:\\\"N\\\";s:9:\\\"pbjnumber\\\";s:17:\\\"PBJ-IT\\/2023000007\\\";s:7:\\\"pbjitem\\\";i:2;s:7:\\\"no_plat\\\";s:7:\\\"EXS-501\\\";s:7:\\\"duedate\\\";i:0;s:8:\\\"deptname\\\";s:2:\\\"IT\\\";}}s:28:\\\"\\u0000*\\u0000escapeWhenCastingToString\\\";b:0;}s:5:\\\"pprid\\\";i:595;s:5:\\\"prnum\\\";s:16:\\\"PR-IT\\/2023000004\\\";s:6:\\\"locale\\\";N;s:4:\\\"from\\\";a:0:{}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:28:\\\"fransiskusaditya88@gmail.com\\\";}}s:2:\\\"cc\\\";a:0:{}s:3:\\\"bcc\\\";a:0:{}s:7:\\\"replyTo\\\";a:0:{}s:7:\\\"subject\\\";N;s:8:\\\"markdown\\\";N;s:7:\\\"\\u0000*\\u0000html\\\";N;s:4:\\\"view\\\";N;s:8:\\\"textView\\\";N;s:8:\\\"viewData\\\";a:0:{}s:11:\\\"attachments\\\";a:0:{}s:14:\\\"rawAttachments\\\";a:0:{}s:15:\\\"diskAttachments\\\";a:0:{}s:9:\\\"callbacks\\\";a:0:{}s:5:\\\"theme\\\";N;s:6:\\\"mailer\\\";s:4:\\\"smtp\\\";s:29:\\\"\\u0000*\\u0000assertionableRenderStrings\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\"}}', 0, NULL, 1699343488, 1699343488),
(112, 'default', '{\"uuid\":\"bc0c8946-49a4-4776-b4d9-497ad5aa36b2\",\"displayName\":\"App\\\\Mail\\\\NotifApprovePoMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":13:{s:8:\\\"mailable\\\";O:27:\\\"App\\\\Mail\\\\NotifApprovePoMail\\\":31:{s:4:\\\"data\\\";O:29:\\\"Illuminate\\\\Support\\\\Collection\\\":2:{s:8:\\\"\\u0000*\\u0000items\\\";a:2:{i:0;O:8:\\\"stdClass\\\":34:{s:2:\\\"id\\\";i:487;s:5:\\\"ponum\\\";s:17:\\\"PO\\/20231109000001\\\";s:9:\\\"ext_ponum\\\";s:17:\\\"PO\\/20231109000001\\\";s:6:\\\"deptid\\\";i:1;s:6:\\\"potype\\\";N;s:5:\\\"podat\\\";s:10:\\\"2023-11-09\\\";s:13:\\\"delivery_date\\\";s:10:\\\"2023-11-09\\\";s:6:\\\"vendor\\\";s:6:\\\"300002\\\";s:4:\\\"note\\\";s:3:\\\"tes\\\";s:8:\\\"currency\\\";s:3:\\\"IDR\\\";s:11:\\\"approvestat\\\";s:1:\\\"O\\\";s:5:\\\"appby\\\";N;s:9:\\\"completed\\\";N;s:3:\\\"ppn\\\";s:5:\\\"11.00\\\";s:8:\\\"tf_price\\\";N;s:7:\\\"tf_dest\\\";N;s:11:\\\"tf_shipment\\\";N;s:6:\\\"tf_top\\\";s:31:\\\"45 Hari Setelah Barang diterima\\\";s:10:\\\"tf_packing\\\";N;s:11:\\\"tf_shipdate\\\";N;s:9:\\\"createdon\\\";s:19:\\\"2023-11-09 03:11:37\\\";s:9:\\\"createdby\\\";s:28:\\\"fransiskusaditya88@gmail.com\\\";s:6:\\\"poitem\\\";i:1;s:8:\\\"material\\\";s:11:\\\"07063-01054\\\";s:7:\\\"matdesc\\\";s:24:\\\"Element Hydraulic Filter\\\";s:8:\\\"quantity\\\";s:5:\\\"1.000\\\";s:5:\\\"grqty\\\";s:5:\\\"0.000\\\";s:7:\\\"openqty\\\";s:5:\\\"1.000\\\";s:4:\\\"unit\\\";s:2:\\\"PC\\\";s:5:\\\"price\\\";s:10:\\\"107800.000\\\";s:8:\\\"grstatus\\\";s:1:\\\"O\\\";s:10:\\\"pocomplete\\\";N;s:7:\\\"duedate\\\";i:0;s:8:\\\"deptname\\\";s:2:\\\"IT\\\";}i:1;O:8:\\\"stdClass\\\":34:{s:2:\\\"id\\\";i:487;s:5:\\\"ponum\\\";s:17:\\\"PO\\/20231109000001\\\";s:9:\\\"ext_ponum\\\";s:17:\\\"PO\\/20231109000001\\\";s:6:\\\"deptid\\\";i:1;s:6:\\\"potype\\\";N;s:5:\\\"podat\\\";s:10:\\\"2023-11-09\\\";s:13:\\\"delivery_date\\\";s:10:\\\"2023-11-09\\\";s:6:\\\"vendor\\\";s:6:\\\"300002\\\";s:4:\\\"note\\\";s:3:\\\"tes\\\";s:8:\\\"currency\\\";s:3:\\\"IDR\\\";s:11:\\\"approvestat\\\";s:1:\\\"O\\\";s:5:\\\"appby\\\";N;s:9:\\\"completed\\\";N;s:3:\\\"ppn\\\";s:5:\\\"11.00\\\";s:8:\\\"tf_price\\\";N;s:7:\\\"tf_dest\\\";N;s:11:\\\"tf_shipment\\\";N;s:6:\\\"tf_top\\\";s:31:\\\"45 Hari Setelah Barang diterima\\\";s:10:\\\"tf_packing\\\";N;s:11:\\\"tf_shipdate\\\";N;s:9:\\\"createdon\\\";s:19:\\\"2023-11-09 03:11:37\\\";s:9:\\\"createdby\\\";s:28:\\\"fransiskusaditya88@gmail.com\\\";s:6:\\\"poitem\\\";i:2;s:8:\\\"material\\\";s:11:\\\"07063-01100\\\";s:7:\\\"matdesc\\\";s:24:\\\"Element Hydraulic Filter\\\";s:8:\\\"quantity\\\";s:5:\\\"1.000\\\";s:5:\\\"grqty\\\";s:5:\\\"0.000\\\";s:7:\\\"openqty\\\";s:5:\\\"1.000\\\";s:4:\\\"unit\\\";s:2:\\\"PC\\\";s:5:\\\"price\\\";s:10:\\\"130620.000\\\";s:8:\\\"grstatus\\\";s:1:\\\"O\\\";s:10:\\\"pocomplete\\\";N;s:7:\\\"duedate\\\";i:0;s:8:\\\"deptname\\\";s:2:\\\"IT\\\";}}s:28:\\\"\\u0000*\\u0000escapeWhenCastingToString\\\";b:0;}s:4:\\\"poid\\\";i:487;s:5:\\\"ponum\\\";s:17:\\\"PO\\/20231109000001\\\";s:6:\\\"locale\\\";N;s:4:\\\"from\\\";a:0:{}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:28:\\\"fransiskusaditya88@gmail.com\\\";}}s:2:\\\"cc\\\";a:0:{}s:3:\\\"bcc\\\";a:0:{}s:7:\\\"replyTo\\\";a:0:{}s:7:\\\"subject\\\";N;s:8:\\\"markdown\\\";N;s:7:\\\"\\u0000*\\u0000html\\\";N;s:4:\\\"view\\\";N;s:8:\\\"textView\\\";N;s:8:\\\"viewData\\\";a:0:{}s:11:\\\"attachments\\\";a:0:{}s:14:\\\"rawAttachments\\\";a:0:{}s:15:\\\"diskAttachments\\\";a:0:{}s:9:\\\"callbacks\\\";a:0:{}s:5:\\\"theme\\\";N;s:6:\\\"mailer\\\";s:4:\\\"smtp\\\";s:29:\\\"\\u0000*\\u0000assertionableRenderStrings\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\"}}', 0, NULL, 1699498838, 1699498838),
(113, 'default', '{\"uuid\":\"9750e61c-0c83-4451-ae4b-69ce6d8e0fc9\",\"displayName\":\"App\\\\Mail\\\\NotifApprovePbjMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":13:{s:8:\\\"mailable\\\";O:28:\\\"App\\\\Mail\\\\NotifApprovePbjMail\\\":31:{s:4:\\\"data\\\";O:29:\\\"Illuminate\\\\Support\\\\Collection\\\":2:{s:8:\\\"\\u0000*\\u0000items\\\";a:2:{i:0;O:8:\\\"stdClass\\\":34:{s:2:\\\"id\\\";i:661;s:9:\\\"pbjnumber\\\";s:17:\\\"PBJ-IT\\/2023000008\\\";s:6:\\\"deptid\\\";i:1;s:7:\\\"tgl_pbj\\\";s:10:\\\"2023-11-13\\\";s:17:\\\"tujuan_permintaan\\\";s:4:\\\"Test\\\";s:6:\\\"kepada\\\";s:2:\\\"IT\\\";s:9:\\\"unit_desc\\\";s:7:\\\"EXS-501\\\";s:12:\\\"engine_model\\\";s:3:\\\"tes\\\";s:10:\\\"chassis_sn\\\";s:13:\\\"SY048DCB16128\\\";s:9:\\\"reference\\\";s:4:\\\"test\\\";s:9:\\\"requestor\\\";s:13:\\\"Administrator\\\";s:10:\\\"type_model\\\";s:11:\\\"SANY SY500H\\\";s:4:\\\"user\\\";s:6:\\\"MAHMUD\\\";s:13:\\\"kode_brg_jasa\\\";s:6:\\\"jasa01\\\";s:9:\\\"engine_sn\\\";s:11:\\\"engine SN 1\\\";s:5:\\\"hm_km\\\";s:1:\\\"0\\\";s:2:\\\"km\\\";s:1:\\\"0\\\";s:16:\\\"budget_cost_code\\\";s:1:\\\"1\\\";s:14:\\\"cheklistnumber\\\";N;s:10:\\\"pbj_status\\\";s:1:\\\"O\\\";s:9:\\\"createdby\\\";s:28:\\\"fransiskusaditya88@gmail.com\\\";s:9:\\\"createdon\\\";s:19:\\\"2023-11-13 17:29:16\\\";s:7:\\\"pbjitem\\\";i:1;s:10:\\\"partnumber\\\";s:10:\\\"SPANDEK 3M\\\";s:11:\\\"description\\\";s:10:\\\"SPANDEK 3M\\\";s:8:\\\"quantity\\\";s:5:\\\"8.000\\\";s:4:\\\"unit\\\";s:3:\\\"Lbr\\\";s:6:\\\"figure\\\";s:0:\\\"\\\";s:6:\\\"remark\\\";s:0:\\\"\\\";s:11:\\\"approvestat\\\";s:1:\\\"O\\\";s:9:\\\"prcreated\\\";s:1:\\\"N\\\";s:9:\\\"wocreated\\\";s:1:\\\"N\\\";s:7:\\\"duedate\\\";i:0;s:8:\\\"deptname\\\";s:2:\\\"IT\\\";}i:1;O:8:\\\"stdClass\\\":34:{s:2:\\\"id\\\";i:661;s:9:\\\"pbjnumber\\\";s:17:\\\"PBJ-IT\\/2023000008\\\";s:6:\\\"deptid\\\";i:1;s:7:\\\"tgl_pbj\\\";s:10:\\\"2023-11-13\\\";s:17:\\\"tujuan_permintaan\\\";s:4:\\\"Test\\\";s:6:\\\"kepada\\\";s:2:\\\"IT\\\";s:9:\\\"unit_desc\\\";s:7:\\\"EXS-501\\\";s:12:\\\"engine_model\\\";s:3:\\\"tes\\\";s:10:\\\"chassis_sn\\\";s:13:\\\"SY048DCB16128\\\";s:9:\\\"reference\\\";s:4:\\\"test\\\";s:9:\\\"requestor\\\";s:13:\\\"Administrator\\\";s:10:\\\"type_model\\\";s:11:\\\"SANY SY500H\\\";s:4:\\\"user\\\";s:6:\\\"MAHMUD\\\";s:13:\\\"kode_brg_jasa\\\";s:6:\\\"jasa01\\\";s:9:\\\"engine_sn\\\";s:11:\\\"engine SN 1\\\";s:5:\\\"hm_km\\\";s:1:\\\"0\\\";s:2:\\\"km\\\";s:1:\\\"0\\\";s:16:\\\"budget_cost_code\\\";s:1:\\\"1\\\";s:14:\\\"cheklistnumber\\\";N;s:10:\\\"pbj_status\\\";s:1:\\\"O\\\";s:9:\\\"createdby\\\";s:28:\\\"fransiskusaditya88@gmail.com\\\";s:9:\\\"createdon\\\";s:19:\\\"2023-11-13 17:29:16\\\";s:7:\\\"pbjitem\\\";i:2;s:10:\\\"partnumber\\\";s:10:\\\"Baut Skrup\\\";s:11:\\\"description\\\";s:10:\\\"Baut Skrup\\\";s:8:\\\"quantity\\\";s:5:\\\"2.000\\\";s:4:\\\"unit\\\";s:3:\\\"Ktk\\\";s:6:\\\"figure\\\";s:0:\\\"\\\";s:6:\\\"remark\\\";s:0:\\\"\\\";s:11:\\\"approvestat\\\";s:1:\\\"O\\\";s:9:\\\"prcreated\\\";s:1:\\\"N\\\";s:9:\\\"wocreated\\\";s:1:\\\"N\\\";s:7:\\\"duedate\\\";i:0;s:8:\\\"deptname\\\";s:2:\\\"IT\\\";}}s:28:\\\"\\u0000*\\u0000escapeWhenCastingToString\\\";b:0;}s:5:\\\"pbjid\\\";i:661;s:6:\\\"pbjnum\\\";s:17:\\\"PBJ-IT\\/2023000008\\\";s:6:\\\"locale\\\";N;s:4:\\\"from\\\";a:0:{}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:28:\\\"fransiskusaditya88@gmail.com\\\";}}s:2:\\\"cc\\\";a:0:{}s:3:\\\"bcc\\\";a:0:{}s:7:\\\"replyTo\\\";a:0:{}s:7:\\\"subject\\\";N;s:8:\\\"markdown\\\";N;s:7:\\\"\\u0000*\\u0000html\\\";N;s:4:\\\"view\\\";N;s:8:\\\"textView\\\";N;s:8:\\\"viewData\\\";a:0:{}s:11:\\\"attachments\\\";a:0:{}s:14:\\\"rawAttachments\\\";a:0:{}s:15:\\\"diskAttachments\\\";a:0:{}s:9:\\\"callbacks\\\";a:0:{}s:5:\\\"theme\\\";N;s:6:\\\"mailer\\\";s:4:\\\"smtp\\\";s:29:\\\"\\u0000*\\u0000assertionableRenderStrings\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\"}}', 0, NULL, 1699871358, 1699871358),
(114, 'default', '{\"uuid\":\"96d0870e-7a8c-4cb5-ae35-63d64b4b66ac\",\"displayName\":\"App\\\\Mail\\\\NotifApprovePrMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":13:{s:8:\\\"mailable\\\";O:27:\\\"App\\\\Mail\\\\NotifApprovePrMail\\\":31:{s:4:\\\"data\\\";O:29:\\\"Illuminate\\\\Support\\\\Collection\\\":2:{s:8:\\\"\\u0000*\\u0000items\\\";a:1:{i:0;O:8:\\\"stdClass\\\":26:{s:2:\\\"id\\\";i:453;s:5:\\\"prnum\\\";s:16:\\\"PR-IT\\/2023000003\\\";s:6:\\\"typepr\\\";N;s:4:\\\"note\\\";N;s:6:\\\"prdate\\\";s:10:\\\"2023-10-09\\\";s:8:\\\"relgroup\\\";N;s:11:\\\"approvestat\\\";s:1:\\\"N\\\";s:9:\\\"requestby\\\";s:13:\\\"Administrator\\\";s:9:\\\"warehouse\\\";N;s:9:\\\"idproject\\\";N;s:6:\\\"remark\\\";s:3:\\\"rer\\\";s:5:\\\"appby\\\";N;s:6:\\\"deptid\\\";i:1;s:9:\\\"createdon\\\";s:19:\\\"2023-10-09 16:33:50\\\";s:9:\\\"createdby\\\";s:28:\\\"fransiskusaditya88@gmail.com\\\";s:6:\\\"pritem\\\";i:1;s:8:\\\"material\\\";s:7:\\\"01B0643\\\";s:7:\\\"matdesc\\\";s:11:\\\"BOLT U-RELL\\\";s:8:\\\"quantity\\\";s:5:\\\"1.000\\\";s:4:\\\"unit\\\";s:3:\\\"PCS\\\";s:9:\\\"pocreated\\\";s:1:\\\"N\\\";s:9:\\\"pbjnumber\\\";s:1:\\\"0\\\";s:7:\\\"pbjitem\\\";i:0;s:7:\\\"no_plat\\\";s:0:\\\"\\\";s:7:\\\"duedate\\\";i:37;s:8:\\\"deptname\\\";s:2:\\\"IT\\\";}}s:28:\\\"\\u0000*\\u0000escapeWhenCastingToString\\\";b:0;}s:5:\\\"pprid\\\";i:453;s:5:\\\"prnum\\\";s:16:\\\"PR-IT\\/2023000003\\\";s:6:\\\"locale\\\";N;s:4:\\\"from\\\";a:0:{}s:2:\\\"to\\\";a:0:{}s:2:\\\"cc\\\";a:0:{}s:3:\\\"bcc\\\";a:0:{}s:7:\\\"replyTo\\\";a:0:{}s:7:\\\"subject\\\";N;s:8:\\\"markdown\\\";N;s:7:\\\"\\u0000*\\u0000html\\\";N;s:4:\\\"view\\\";N;s:8:\\\"textView\\\";N;s:8:\\\"viewData\\\";a:0:{}s:11:\\\"attachments\\\";a:0:{}s:14:\\\"rawAttachments\\\";a:0:{}s:15:\\\"diskAttachments\\\";a:0:{}s:9:\\\"callbacks\\\";a:0:{}s:5:\\\"theme\\\";N;s:6:\\\"mailer\\\";s:4:\\\"smtp\\\";s:29:\\\"\\u0000*\\u0000assertionableRenderStrings\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\"}}', 0, NULL, 1700041764, 1700041764),
(115, 'default', '{\"uuid\":\"7794908d-b00a-4da6-80fb-416cebe1324e\",\"displayName\":\"App\\\\Mail\\\\NotifApprovePoMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":13:{s:8:\\\"mailable\\\";O:27:\\\"App\\\\Mail\\\\NotifApprovePoMail\\\":31:{s:4:\\\"data\\\";O:29:\\\"Illuminate\\\\Support\\\\Collection\\\":2:{s:8:\\\"\\u0000*\\u0000items\\\";a:2:{i:0;O:8:\\\"stdClass\\\":34:{s:2:\\\"id\\\";i:488;s:5:\\\"ponum\\\";s:17:\\\"PO\\/20231119000001\\\";s:9:\\\"ext_ponum\\\";s:17:\\\"PO\\/20231119000001\\\";s:6:\\\"deptid\\\";i:1;s:6:\\\"potype\\\";N;s:5:\\\"podat\\\";s:10:\\\"2023-11-19\\\";s:13:\\\"delivery_date\\\";s:10:\\\"2023-11-19\\\";s:6:\\\"vendor\\\";s:6:\\\"300000\\\";s:4:\\\"note\\\";N;s:8:\\\"currency\\\";s:3:\\\"IDR\\\";s:11:\\\"approvestat\\\";s:1:\\\"O\\\";s:5:\\\"appby\\\";N;s:9:\\\"completed\\\";N;s:3:\\\"ppn\\\";s:5:\\\"11.00\\\";s:8:\\\"tf_price\\\";N;s:7:\\\"tf_dest\\\";N;s:11:\\\"tf_shipment\\\";N;s:6:\\\"tf_top\\\";s:31:\\\"45 Hari Setelah Barang diterima\\\";s:10:\\\"tf_packing\\\";N;s:11:\\\"tf_shipdate\\\";N;s:9:\\\"createdon\\\";s:19:\\\"2023-11-19 15:11:40\\\";s:9:\\\"createdby\\\";s:28:\\\"fransiskusaditya88@gmail.com\\\";s:6:\\\"poitem\\\";i:1;s:8:\\\"material\\\";s:7:\\\"01B0643\\\";s:7:\\\"matdesc\\\";s:11:\\\"BOLT U-RELL\\\";s:8:\\\"quantity\\\";s:5:\\\"1.000\\\";s:5:\\\"grqty\\\";s:5:\\\"0.000\\\";s:7:\\\"openqty\\\";s:5:\\\"1.000\\\";s:4:\\\"unit\\\";s:3:\\\"PCS\\\";s:5:\\\"price\\\";s:10:\\\"500000.000\\\";s:8:\\\"grstatus\\\";s:1:\\\"O\\\";s:10:\\\"pocomplete\\\";N;s:7:\\\"duedate\\\";i:0;s:8:\\\"deptname\\\";s:2:\\\"IT\\\";}i:1;O:8:\\\"stdClass\\\":34:{s:2:\\\"id\\\";i:488;s:5:\\\"ponum\\\";s:17:\\\"PO\\/20231119000001\\\";s:9:\\\"ext_ponum\\\";s:17:\\\"PO\\/20231119000001\\\";s:6:\\\"deptid\\\";i:1;s:6:\\\"potype\\\";N;s:5:\\\"podat\\\";s:10:\\\"2023-11-19\\\";s:13:\\\"delivery_date\\\";s:10:\\\"2023-11-19\\\";s:6:\\\"vendor\\\";s:6:\\\"300000\\\";s:4:\\\"note\\\";N;s:8:\\\"currency\\\";s:3:\\\"IDR\\\";s:11:\\\"approvestat\\\";s:1:\\\"O\\\";s:5:\\\"appby\\\";N;s:9:\\\"completed\\\";N;s:3:\\\"ppn\\\";s:5:\\\"11.00\\\";s:8:\\\"tf_price\\\";N;s:7:\\\"tf_dest\\\";N;s:11:\\\"tf_shipment\\\";N;s:6:\\\"tf_top\\\";s:31:\\\"45 Hari Setelah Barang diterima\\\";s:10:\\\"tf_packing\\\";N;s:11:\\\"tf_shipdate\\\";N;s:9:\\\"createdon\\\";s:19:\\\"2023-11-19 15:11:40\\\";s:9:\\\"createdby\\\";s:28:\\\"fransiskusaditya88@gmail.com\\\";s:6:\\\"poitem\\\";i:2;s:8:\\\"material\\\";s:7:\\\"01B1674\\\";s:7:\\\"matdesc\\\";s:28:\\\"BOLT CUTTING EDGE + END BEAT\\\";s:8:\\\"quantity\\\";s:5:\\\"1.000\\\";s:5:\\\"grqty\\\";s:5:\\\"0.000\\\";s:7:\\\"openqty\\\";s:5:\\\"1.000\\\";s:4:\\\"unit\\\";s:3:\\\"PCS\\\";s:5:\\\"price\\\";s:10:\\\"200000.000\\\";s:8:\\\"grstatus\\\";s:1:\\\"O\\\";s:10:\\\"pocomplete\\\";N;s:7:\\\"duedate\\\";i:0;s:8:\\\"deptname\\\";s:2:\\\"IT\\\";}}s:28:\\\"\\u0000*\\u0000escapeWhenCastingToString\\\";b:0;}s:4:\\\"poid\\\";i:488;s:5:\\\"ponum\\\";s:17:\\\"PO\\/20231119000001\\\";s:6:\\\"locale\\\";N;s:4:\\\"from\\\";a:0:{}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:28:\\\"fransiskusaditya88@gmail.com\\\";}}s:2:\\\"cc\\\";a:0:{}s:3:\\\"bcc\\\";a:0:{}s:7:\\\"replyTo\\\";a:0:{}s:7:\\\"subject\\\";N;s:8:\\\"markdown\\\";N;s:7:\\\"\\u0000*\\u0000html\\\";N;s:4:\\\"view\\\";N;s:8:\\\"textView\\\";N;s:8:\\\"viewData\\\";a:0:{}s:11:\\\"attachments\\\";a:0:{}s:14:\\\"rawAttachments\\\";a:0:{}s:15:\\\"diskAttachments\\\";a:0:{}s:9:\\\"callbacks\\\";a:0:{}s:5:\\\"theme\\\";N;s:6:\\\"mailer\\\";s:4:\\\"smtp\\\";s:29:\\\"\\u0000*\\u0000assertionableRenderStrings\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\"}}', 0, NULL, 1700406764, 1700406764),
(116, 'default', '{\"uuid\":\"f104a28a-0c06-47a4-8ab8-21cb3d7d652e\",\"displayName\":\"App\\\\Mail\\\\NotifApprovePrMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":13:{s:8:\\\"mailable\\\";O:27:\\\"App\\\\Mail\\\\NotifApprovePrMail\\\":31:{s:4:\\\"data\\\";O:29:\\\"Illuminate\\\\Support\\\\Collection\\\":2:{s:8:\\\"\\u0000*\\u0000items\\\";a:2:{i:0;O:8:\\\"stdClass\\\":26:{s:2:\\\"id\\\";i:596;s:5:\\\"prnum\\\";s:16:\\\"PR-IT\\/2023000005\\\";s:6:\\\"typepr\\\";N;s:4:\\\"note\\\";N;s:6:\\\"prdate\\\";s:10:\\\"2023-11-21\\\";s:8:\\\"relgroup\\\";N;s:11:\\\"approvestat\\\";s:1:\\\"N\\\";s:9:\\\"requestby\\\";s:13:\\\"Administrator\\\";s:9:\\\"warehouse\\\";N;s:9:\\\"idproject\\\";N;s:6:\\\"remark\\\";s:3:\\\"tes\\\";s:5:\\\"appby\\\";N;s:6:\\\"deptid\\\";i:1;s:9:\\\"createdon\\\";s:19:\\\"2023-11-21 15:37:18\\\";s:9:\\\"createdby\\\";s:28:\\\"fransiskusaditya88@gmail.com\\\";s:6:\\\"pritem\\\";i:1;s:8:\\\"material\\\";s:7:\\\"01B0643\\\";s:7:\\\"matdesc\\\";s:11:\\\"BOLT U-RELL\\\";s:8:\\\"quantity\\\";s:5:\\\"1.000\\\";s:4:\\\"unit\\\";s:3:\\\"PCS\\\";s:9:\\\"pocreated\\\";s:1:\\\"N\\\";s:9:\\\"pbjnumber\\\";s:1:\\\"0\\\";s:7:\\\"pbjitem\\\";i:0;s:7:\\\"no_plat\\\";s:0:\\\"\\\";s:7:\\\"duedate\\\";i:0;s:8:\\\"deptname\\\";s:2:\\\"IT\\\";}i:1;O:8:\\\"stdClass\\\":26:{s:2:\\\"id\\\";i:596;s:5:\\\"prnum\\\";s:16:\\\"PR-IT\\/2023000005\\\";s:6:\\\"typepr\\\";N;s:4:\\\"note\\\";N;s:6:\\\"prdate\\\";s:10:\\\"2023-11-21\\\";s:8:\\\"relgroup\\\";N;s:11:\\\"approvestat\\\";s:1:\\\"N\\\";s:9:\\\"requestby\\\";s:13:\\\"Administrator\\\";s:9:\\\"warehouse\\\";N;s:9:\\\"idproject\\\";N;s:6:\\\"remark\\\";s:3:\\\"tes\\\";s:5:\\\"appby\\\";N;s:6:\\\"deptid\\\";i:1;s:9:\\\"createdon\\\";s:19:\\\"2023-11-21 15:37:18\\\";s:9:\\\"createdby\\\";s:28:\\\"fransiskusaditya88@gmail.com\\\";s:6:\\\"pritem\\\";i:2;s:8:\\\"material\\\";s:7:\\\"01B1674\\\";s:7:\\\"matdesc\\\";s:28:\\\"BOLT CUTTING EDGE + END BEAT\\\";s:8:\\\"quantity\\\";s:5:\\\"1.000\\\";s:4:\\\"unit\\\";s:3:\\\"PCS\\\";s:9:\\\"pocreated\\\";s:1:\\\"N\\\";s:9:\\\"pbjnumber\\\";s:1:\\\"0\\\";s:7:\\\"pbjitem\\\";i:0;s:7:\\\"no_plat\\\";s:0:\\\"\\\";s:7:\\\"duedate\\\";i:0;s:8:\\\"deptname\\\";s:2:\\\"IT\\\";}}s:28:\\\"\\u0000*\\u0000escapeWhenCastingToString\\\";b:0;}s:5:\\\"pprid\\\";i:596;s:5:\\\"prnum\\\";s:16:\\\"PR-IT\\/2023000005\\\";s:6:\\\"locale\\\";N;s:4:\\\"from\\\";a:0:{}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:28:\\\"fransiskusaditya88@gmail.com\\\";}}s:2:\\\"cc\\\";a:0:{}s:3:\\\"bcc\\\";a:0:{}s:7:\\\"replyTo\\\";a:0:{}s:7:\\\"subject\\\";N;s:8:\\\"markdown\\\";N;s:7:\\\"\\u0000*\\u0000html\\\";N;s:4:\\\"view\\\";N;s:8:\\\"textView\\\";N;s:8:\\\"viewData\\\";a:0:{}s:11:\\\"attachments\\\";a:0:{}s:14:\\\"rawAttachments\\\";a:0:{}s:15:\\\"diskAttachments\\\";a:0:{}s:9:\\\"callbacks\\\";a:0:{}s:5:\\\"theme\\\";N;s:6:\\\"mailer\\\";s:4:\\\"smtp\\\";s:29:\\\"\\u0000*\\u0000assertionableRenderStrings\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\"}}', 0, NULL, 1700555839, 1700555839),
(117, 'default', '{\"uuid\":\"ed24d8b0-6222-4390-be69-1c9b2c284bf6\",\"displayName\":\"App\\\\Mail\\\\NotifApproveWoMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":13:{s:8:\\\"mailable\\\";O:27:\\\"App\\\\Mail\\\\NotifApproveWoMail\\\":31:{s:4:\\\"data\\\";O:29:\\\"Illuminate\\\\Support\\\\Collection\\\":2:{s:8:\\\"\\u0000*\\u0000items\\\";a:2:{i:0;O:8:\\\"stdClass\\\":22:{s:2:\\\"id\\\";i:2;s:5:\\\"wonum\\\";s:15:\\\"WO\\/202312000001\\\";s:6:\\\"wodate\\\";s:10:\\\"2023-12-25\\\";s:11:\\\"description\\\";s:3:\\\"tes\\\";s:7:\\\"mekanik\\\";N;s:7:\\\"whscode\\\";s:1:\\\"1\\\";s:7:\\\"whsname\\\";s:10:\\\"Site Lahat\\\";s:14:\\\"license_number\\\";s:7:\\\"EXS-501\\\";s:14:\\\"last_odo_meter\\\";N;s:13:\\\"schedule_type\\\";s:11:\\\"Un-Schedule\\\";s:10:\\\"wo_process\\\";s:4:\\\"Open\\\";s:6:\\\"woitem\\\";i:1;s:8:\\\"material\\\";s:7:\\\"01B0643\\\";s:7:\\\"matdesc\\\";s:11:\\\"BOLT U-RELL\\\";s:8:\\\"quantity\\\";s:5:\\\"1.000\\\";s:4:\\\"unit\\\";s:3:\\\"PCS\\\";s:6:\\\"refdoc\\\";s:15:\\\"CKL-2312-000001\\\";s:10:\\\"refdocitem\\\";N;s:11:\\\"approvestat\\\";s:1:\\\"N\\\";s:11:\\\"pbj_created\\\";s:1:\\\"N\\\";s:9:\\\"createdby\\\";s:28:\\\"fransiskusaditya88@gmail.com\\\";s:9:\\\"createdon\\\";s:19:\\\"2023-12-25 07:12:37\\\";}i:1;O:8:\\\"stdClass\\\":22:{s:2:\\\"id\\\";i:2;s:5:\\\"wonum\\\";s:15:\\\"WO\\/202312000001\\\";s:6:\\\"wodate\\\";s:10:\\\"2023-12-25\\\";s:11:\\\"description\\\";s:3:\\\"tes\\\";s:7:\\\"mekanik\\\";N;s:7:\\\"whscode\\\";s:1:\\\"1\\\";s:7:\\\"whsname\\\";s:10:\\\"Site Lahat\\\";s:14:\\\"license_number\\\";s:7:\\\"EXS-501\\\";s:14:\\\"last_odo_meter\\\";N;s:13:\\\"schedule_type\\\";s:11:\\\"Un-Schedule\\\";s:10:\\\"wo_process\\\";s:4:\\\"Open\\\";s:6:\\\"woitem\\\";i:2;s:8:\\\"material\\\";s:7:\\\"01B1674\\\";s:7:\\\"matdesc\\\";s:28:\\\"BOLT CUTTING EDGE + END BEAT\\\";s:8:\\\"quantity\\\";s:5:\\\"2.000\\\";s:4:\\\"unit\\\";s:3:\\\"PCS\\\";s:6:\\\"refdoc\\\";s:15:\\\"CKL-2312-000001\\\";s:10:\\\"refdocitem\\\";N;s:11:\\\"approvestat\\\";s:1:\\\"N\\\";s:11:\\\"pbj_created\\\";s:1:\\\"N\\\";s:9:\\\"createdby\\\";s:28:\\\"fransiskusaditya88@gmail.com\\\";s:9:\\\"createdon\\\";s:19:\\\"2023-12-25 07:12:37\\\";}}s:28:\\\"\\u0000*\\u0000escapeWhenCastingToString\\\";b:0;}s:4:\\\"woid\\\";i:2;s:5:\\\"wonum\\\";s:15:\\\"WO\\/202312000001\\\";s:6:\\\"locale\\\";N;s:4:\\\"from\\\";a:0:{}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:28:\\\"fransiskusaditya88@gmail.com\\\";}}s:2:\\\"cc\\\";a:0:{}s:3:\\\"bcc\\\";a:0:{}s:7:\\\"replyTo\\\";a:0:{}s:7:\\\"subject\\\";N;s:8:\\\"markdown\\\";N;s:7:\\\"\\u0000*\\u0000html\\\";N;s:4:\\\"view\\\";N;s:8:\\\"textView\\\";N;s:8:\\\"viewData\\\";a:0:{}s:11:\\\"attachments\\\";a:0:{}s:14:\\\"rawAttachments\\\";a:0:{}s:15:\\\"diskAttachments\\\";a:0:{}s:9:\\\"callbacks\\\";a:0:{}s:5:\\\"theme\\\";N;s:6:\\\"mailer\\\";s:4:\\\"smtp\\\";s:29:\\\"\\u0000*\\u0000assertionableRenderStrings\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\"}}', 0, NULL, 1703488180, 1703488180),
(118, 'default', '{\"uuid\":\"12d36465-8a60-429b-96e6-86dc74ba86bf\",\"displayName\":\"App\\\\Mail\\\\NotifApprovePbjMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":13:{s:8:\\\"mailable\\\";O:28:\\\"App\\\\Mail\\\\NotifApprovePbjMail\\\":31:{s:4:\\\"data\\\";O:29:\\\"Illuminate\\\\Support\\\\Collection\\\":2:{s:8:\\\"\\u0000*\\u0000items\\\";a:1:{i:0;O:8:\\\"stdClass\\\":34:{s:2:\\\"id\\\";i:662;s:9:\\\"pbjnumber\\\";s:17:\\\"PBJ-IT\\/2023000009\\\";s:6:\\\"deptid\\\";i:1;s:7:\\\"tgl_pbj\\\";s:10:\\\"2023-12-25\\\";s:17:\\\"tujuan_permintaan\\\";s:4:\\\"Test\\\";s:6:\\\"kepada\\\";s:2:\\\"IT\\\";s:9:\\\"unit_desc\\\";s:7:\\\"EXS-501\\\";s:12:\\\"engine_model\\\";s:3:\\\"tes\\\";s:10:\\\"chassis_sn\\\";s:13:\\\"SY048DCB16128\\\";s:9:\\\"reference\\\";s:4:\\\"test\\\";s:9:\\\"requestor\\\";s:13:\\\"Administrator\\\";s:10:\\\"type_model\\\";s:11:\\\"SANY SY500H\\\";s:4:\\\"user\\\";s:5:\\\"UJANG\\\";s:13:\\\"kode_brg_jasa\\\";s:6:\\\"jasa01\\\";s:9:\\\"engine_sn\\\";N;s:5:\\\"hm_km\\\";s:1:\\\"0\\\";s:2:\\\"km\\\";s:1:\\\"0\\\";s:16:\\\"budget_cost_code\\\";s:1:\\\"1\\\";s:14:\\\"cheklistnumber\\\";N;s:10:\\\"pbj_status\\\";s:1:\\\"O\\\";s:9:\\\"createdby\\\";s:28:\\\"fransiskusaditya88@gmail.com\\\";s:9:\\\"createdon\\\";s:19:\\\"2023-12-25 14:14:34\\\";s:7:\\\"pbjitem\\\";i:1;s:10:\\\"partnumber\\\";s:7:\\\"01B0643\\\";s:11:\\\"description\\\";s:11:\\\"BOLT U-RELL\\\";s:8:\\\"quantity\\\";s:5:\\\"1.000\\\";s:4:\\\"unit\\\";s:3:\\\"PCS\\\";s:6:\\\"figure\\\";s:0:\\\"\\\";s:6:\\\"remark\\\";s:0:\\\"\\\";s:11:\\\"approvestat\\\";s:1:\\\"O\\\";s:9:\\\"prcreated\\\";s:1:\\\"N\\\";s:9:\\\"wocreated\\\";s:1:\\\"N\\\";s:7:\\\"duedate\\\";i:0;s:8:\\\"deptname\\\";s:2:\\\"IT\\\";}}s:28:\\\"\\u0000*\\u0000escapeWhenCastingToString\\\";b:0;}s:5:\\\"pbjid\\\";i:662;s:6:\\\"pbjnum\\\";s:17:\\\"PBJ-IT\\/2023000009\\\";s:6:\\\"locale\\\";N;s:4:\\\"from\\\";a:0:{}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:28:\\\"fransiskusaditya88@gmail.com\\\";}}s:2:\\\"cc\\\";a:0:{}s:3:\\\"bcc\\\";a:0:{}s:7:\\\"replyTo\\\";a:0:{}s:7:\\\"subject\\\";N;s:8:\\\"markdown\\\";N;s:7:\\\"\\u0000*\\u0000html\\\";N;s:4:\\\"view\\\";N;s:8:\\\"textView\\\";N;s:8:\\\"viewData\\\";a:0:{}s:11:\\\"attachments\\\";a:0:{}s:14:\\\"rawAttachments\\\";a:0:{}s:15:\\\"diskAttachments\\\";a:0:{}s:9:\\\"callbacks\\\";a:0:{}s:5:\\\"theme\\\";N;s:6:\\\"mailer\\\";s:4:\\\"smtp\\\";s:29:\\\"\\u0000*\\u0000assertionableRenderStrings\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\"}}', 0, NULL, 1703488474, 1703488474),
(119, 'default', '{\"uuid\":\"91935d0c-be1c-4710-842c-aea749afc01e\",\"displayName\":\"App\\\\Mail\\\\NotifApprovePrMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":13:{s:8:\\\"mailable\\\";O:27:\\\"App\\\\Mail\\\\NotifApprovePrMail\\\":31:{s:4:\\\"data\\\";O:29:\\\"Illuminate\\\\Support\\\\Collection\\\":2:{s:8:\\\"\\u0000*\\u0000items\\\";a:2:{i:0;O:8:\\\"stdClass\\\":26:{s:2:\\\"id\\\";i:597;s:5:\\\"prnum\\\";s:16:\\\"PR-IT\\/2023000006\\\";s:6:\\\"typepr\\\";N;s:4:\\\"note\\\";N;s:6:\\\"prdate\\\";s:10:\\\"2023-12-29\\\";s:8:\\\"relgroup\\\";N;s:11:\\\"approvestat\\\";s:1:\\\"N\\\";s:9:\\\"requestby\\\";s:13:\\\"Administrator\\\";s:9:\\\"warehouse\\\";N;s:9:\\\"idproject\\\";N;s:6:\\\"remark\\\";s:4:\\\"test\\\";s:5:\\\"appby\\\";N;s:6:\\\"deptid\\\";i:1;s:9:\\\"createdon\\\";s:19:\\\"2023-12-29 16:53:35\\\";s:9:\\\"createdby\\\";s:28:\\\"fransiskusaditya88@gmail.com\\\";s:6:\\\"pritem\\\";i:1;s:8:\\\"material\\\";s:7:\\\"01B0643\\\";s:7:\\\"matdesc\\\";s:11:\\\"BOLT U-RELL\\\";s:8:\\\"quantity\\\";s:5:\\\"1.000\\\";s:4:\\\"unit\\\";s:3:\\\"PCS\\\";s:9:\\\"pocreated\\\";s:1:\\\"N\\\";s:9:\\\"pbjnumber\\\";s:1:\\\"0\\\";s:7:\\\"pbjitem\\\";i:0;s:7:\\\"no_plat\\\";s:0:\\\"\\\";s:7:\\\"duedate\\\";i:0;s:8:\\\"deptname\\\";s:2:\\\"IT\\\";}i:1;O:8:\\\"stdClass\\\":26:{s:2:\\\"id\\\";i:597;s:5:\\\"prnum\\\";s:16:\\\"PR-IT\\/2023000006\\\";s:6:\\\"typepr\\\";N;s:4:\\\"note\\\";N;s:6:\\\"prdate\\\";s:10:\\\"2023-12-29\\\";s:8:\\\"relgroup\\\";N;s:11:\\\"approvestat\\\";s:1:\\\"N\\\";s:9:\\\"requestby\\\";s:13:\\\"Administrator\\\";s:9:\\\"warehouse\\\";N;s:9:\\\"idproject\\\";N;s:6:\\\"remark\\\";s:4:\\\"test\\\";s:5:\\\"appby\\\";N;s:6:\\\"deptid\\\";i:1;s:9:\\\"createdon\\\";s:19:\\\"2023-12-29 16:53:35\\\";s:9:\\\"createdby\\\";s:28:\\\"fransiskusaditya88@gmail.com\\\";s:6:\\\"pritem\\\";i:2;s:8:\\\"material\\\";s:7:\\\"01B1674\\\";s:7:\\\"matdesc\\\";s:28:\\\"BOLT CUTTING EDGE + END BEAT\\\";s:8:\\\"quantity\\\";s:5:\\\"2.000\\\";s:4:\\\"unit\\\";s:3:\\\"PCS\\\";s:9:\\\"pocreated\\\";s:1:\\\"N\\\";s:9:\\\"pbjnumber\\\";s:1:\\\"0\\\";s:7:\\\"pbjitem\\\";i:0;s:7:\\\"no_plat\\\";s:0:\\\"\\\";s:7:\\\"duedate\\\";i:0;s:8:\\\"deptname\\\";s:2:\\\"IT\\\";}}s:28:\\\"\\u0000*\\u0000escapeWhenCastingToString\\\";b:0;}s:5:\\\"pprid\\\";i:597;s:5:\\\"prnum\\\";s:16:\\\"PR-IT\\/2023000006\\\";s:6:\\\"locale\\\";N;s:4:\\\"from\\\";a:0:{}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:28:\\\"fransiskusaditya88@gmail.com\\\";}}s:2:\\\"cc\\\";a:0:{}s:3:\\\"bcc\\\";a:0:{}s:7:\\\"replyTo\\\";a:0:{}s:7:\\\"subject\\\";N;s:8:\\\"markdown\\\";N;s:7:\\\"\\u0000*\\u0000html\\\";N;s:4:\\\"view\\\";N;s:8:\\\"textView\\\";N;s:8:\\\"viewData\\\";a:0:{}s:11:\\\"attachments\\\";a:0:{}s:14:\\\"rawAttachments\\\";a:0:{}s:15:\\\"diskAttachments\\\";a:0:{}s:9:\\\"callbacks\\\";a:0:{}s:5:\\\"theme\\\";N;s:6:\\\"mailer\\\";s:4:\\\"smtp\\\";s:29:\\\"\\u0000*\\u0000assertionableRenderStrings\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\"}}', 0, NULL, 1703843617, 1703843617),
(120, 'default', '{\"uuid\":\"bb767d2a-0f2e-4d1b-8289-61573c8de5de\",\"displayName\":\"App\\\\Mail\\\\NotifApprovePrMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":13:{s:8:\\\"mailable\\\";O:27:\\\"App\\\\Mail\\\\NotifApprovePrMail\\\":31:{s:4:\\\"data\\\";O:29:\\\"Illuminate\\\\Support\\\\Collection\\\":2:{s:8:\\\"\\u0000*\\u0000items\\\";a:1:{i:0;O:8:\\\"stdClass\\\":26:{s:2:\\\"id\\\";i:597;s:5:\\\"prnum\\\";s:16:\\\"PR-IT\\/2023000006\\\";s:6:\\\"typepr\\\";N;s:4:\\\"note\\\";N;s:6:\\\"prdate\\\";s:10:\\\"2023-12-29\\\";s:8:\\\"relgroup\\\";N;s:11:\\\"approvestat\\\";s:1:\\\"R\\\";s:9:\\\"requestby\\\";s:13:\\\"Administrator\\\";s:9:\\\"warehouse\\\";N;s:9:\\\"idproject\\\";N;s:6:\\\"remark\\\";s:4:\\\"test\\\";s:5:\\\"appby\\\";N;s:6:\\\"deptid\\\";i:1;s:9:\\\"createdon\\\";s:19:\\\"2023-12-29 16:53:35\\\";s:9:\\\"createdby\\\";s:28:\\\"fransiskusaditya88@gmail.com\\\";s:6:\\\"pritem\\\";i:2;s:8:\\\"material\\\";s:7:\\\"01B1674\\\";s:7:\\\"matdesc\\\";s:28:\\\"BOLT CUTTING EDGE + END BEAT\\\";s:8:\\\"quantity\\\";s:5:\\\"2.000\\\";s:4:\\\"unit\\\";s:3:\\\"PCS\\\";s:9:\\\"pocreated\\\";s:1:\\\"N\\\";s:9:\\\"pbjnumber\\\";s:1:\\\"0\\\";s:7:\\\"pbjitem\\\";i:0;s:7:\\\"no_plat\\\";s:0:\\\"\\\";s:7:\\\"duedate\\\";i:0;s:8:\\\"deptname\\\";s:2:\\\"IT\\\";}}s:28:\\\"\\u0000*\\u0000escapeWhenCastingToString\\\";b:0;}s:5:\\\"pprid\\\";i:597;s:5:\\\"prnum\\\";s:16:\\\"PR-IT\\/2023000006\\\";s:6:\\\"locale\\\";N;s:4:\\\"from\\\";a:0:{}s:2:\\\"to\\\";a:0:{}s:2:\\\"cc\\\";a:0:{}s:3:\\\"bcc\\\";a:0:{}s:7:\\\"replyTo\\\";a:0:{}s:7:\\\"subject\\\";N;s:8:\\\"markdown\\\";N;s:7:\\\"\\u0000*\\u0000html\\\";N;s:4:\\\"view\\\";N;s:8:\\\"textView\\\";N;s:8:\\\"viewData\\\";a:0:{}s:11:\\\"attachments\\\";a:0:{}s:14:\\\"rawAttachments\\\";a:0:{}s:15:\\\"diskAttachments\\\";a:0:{}s:9:\\\"callbacks\\\";a:0:{}s:5:\\\"theme\\\";N;s:6:\\\"mailer\\\";s:4:\\\"smtp\\\";s:29:\\\"\\u0000*\\u0000assertionableRenderStrings\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\"}}', 0, NULL, 1703844094, 1703844094),
(121, 'default', '{\"uuid\":\"6e33f54c-3756-40f7-ab60-d8a0c7a743bb\",\"displayName\":\"App\\\\Mail\\\\NotifApprovePoMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":13:{s:8:\\\"mailable\\\";O:27:\\\"App\\\\Mail\\\\NotifApprovePoMail\\\":31:{s:4:\\\"data\\\";O:29:\\\"Illuminate\\\\Support\\\\Collection\\\":2:{s:8:\\\"\\u0000*\\u0000items\\\";a:3:{i:0;O:8:\\\"stdClass\\\":34:{s:2:\\\"id\\\";i:489;s:5:\\\"ponum\\\";s:17:\\\"PO\\/20231229000001\\\";s:9:\\\"ext_ponum\\\";s:17:\\\"PO\\/20231229000001\\\";s:6:\\\"deptid\\\";i:5;s:6:\\\"potype\\\";N;s:5:\\\"podat\\\";s:10:\\\"2023-12-29\\\";s:13:\\\"delivery_date\\\";s:10:\\\"2023-12-29\\\";s:6:\\\"vendor\\\";s:6:\\\"300001\\\";s:4:\\\"note\\\";s:4:\\\"Test\\\";s:8:\\\"currency\\\";s:3:\\\"IDR\\\";s:11:\\\"approvestat\\\";s:1:\\\"O\\\";s:5:\\\"appby\\\";N;s:9:\\\"completed\\\";N;s:3:\\\"ppn\\\";s:4:\\\"0.00\\\";s:8:\\\"tf_price\\\";N;s:7:\\\"tf_dest\\\";N;s:11:\\\"tf_shipment\\\";N;s:6:\\\"tf_top\\\";N;s:10:\\\"tf_packing\\\";N;s:11:\\\"tf_shipdate\\\";N;s:9:\\\"createdon\\\";s:19:\\\"2023-12-29 10:12:55\\\";s:9:\\\"createdby\\\";s:28:\\\"fransiskusaditya88@gmail.com\\\";s:6:\\\"poitem\\\";i:1;s:8:\\\"material\\\";s:7:\\\"01B0643\\\";s:7:\\\"matdesc\\\";s:11:\\\"BOLT U-RELL\\\";s:8:\\\"quantity\\\";s:5:\\\"1.000\\\";s:5:\\\"grqty\\\";s:5:\\\"0.000\\\";s:7:\\\"openqty\\\";s:5:\\\"1.000\\\";s:4:\\\"unit\\\";s:3:\\\"PCS\\\";s:5:\\\"price\\\";s:10:\\\"500000.000\\\";s:8:\\\"grstatus\\\";s:1:\\\"O\\\";s:10:\\\"pocomplete\\\";N;s:7:\\\"duedate\\\";i:0;s:8:\\\"deptname\\\";s:4:\\\"HSSE\\\";}i:1;O:8:\\\"stdClass\\\":34:{s:2:\\\"id\\\";i:489;s:5:\\\"ponum\\\";s:17:\\\"PO\\/20231229000001\\\";s:9:\\\"ext_ponum\\\";s:17:\\\"PO\\/20231229000001\\\";s:6:\\\"deptid\\\";i:5;s:6:\\\"potype\\\";N;s:5:\\\"podat\\\";s:10:\\\"2023-12-29\\\";s:13:\\\"delivery_date\\\";s:10:\\\"2023-12-29\\\";s:6:\\\"vendor\\\";s:6:\\\"300001\\\";s:4:\\\"note\\\";s:4:\\\"Test\\\";s:8:\\\"currency\\\";s:3:\\\"IDR\\\";s:11:\\\"approvestat\\\";s:1:\\\"O\\\";s:5:\\\"appby\\\";N;s:9:\\\"completed\\\";N;s:3:\\\"ppn\\\";s:4:\\\"0.00\\\";s:8:\\\"tf_price\\\";N;s:7:\\\"tf_dest\\\";N;s:11:\\\"tf_shipment\\\";N;s:6:\\\"tf_top\\\";N;s:10:\\\"tf_packing\\\";N;s:11:\\\"tf_shipdate\\\";N;s:9:\\\"createdon\\\";s:19:\\\"2023-12-29 10:12:55\\\";s:9:\\\"createdby\\\";s:28:\\\"fransiskusaditya88@gmail.com\\\";s:6:\\\"poitem\\\";i:2;s:8:\\\"material\\\";s:7:\\\"01B1674\\\";s:7:\\\"matdesc\\\";s:28:\\\"BOLT CUTTING EDGE + END BEAT\\\";s:8:\\\"quantity\\\";s:5:\\\"2.000\\\";s:5:\\\"grqty\\\";s:5:\\\"0.000\\\";s:7:\\\"openqty\\\";s:5:\\\"2.000\\\";s:4:\\\"unit\\\";s:3:\\\"PCS\\\";s:5:\\\"price\\\";s:10:\\\"200000.000\\\";s:8:\\\"grstatus\\\";s:1:\\\"O\\\";s:10:\\\"pocomplete\\\";N;s:7:\\\"duedate\\\";i:0;s:8:\\\"deptname\\\";s:4:\\\"HSSE\\\";}i:2;O:8:\\\"stdClass\\\":34:{s:2:\\\"id\\\";i:489;s:5:\\\"ponum\\\";s:17:\\\"PO\\/20231229000001\\\";s:9:\\\"ext_ponum\\\";s:17:\\\"PO\\/20231229000001\\\";s:6:\\\"deptid\\\";i:5;s:6:\\\"potype\\\";N;s:5:\\\"podat\\\";s:10:\\\"2023-12-29\\\";s:13:\\\"delivery_date\\\";s:10:\\\"2023-12-29\\\";s:6:\\\"vendor\\\";s:6:\\\"300001\\\";s:4:\\\"note\\\";s:4:\\\"Test\\\";s:8:\\\"currency\\\";s:3:\\\"IDR\\\";s:11:\\\"approvestat\\\";s:1:\\\"O\\\";s:5:\\\"appby\\\";N;s:9:\\\"completed\\\";N;s:3:\\\"ppn\\\";s:4:\\\"0.00\\\";s:8:\\\"tf_price\\\";N;s:7:\\\"tf_dest\\\";N;s:11:\\\"tf_shipment\\\";N;s:6:\\\"tf_top\\\";N;s:10:\\\"tf_packing\\\";N;s:11:\\\"tf_shipdate\\\";N;s:9:\\\"createdon\\\";s:19:\\\"2023-12-29 10:12:55\\\";s:9:\\\"createdby\\\";s:28:\\\"fransiskusaditya88@gmail.com\\\";s:6:\\\"poitem\\\";i:3;s:8:\\\"material\\\";s:7:\\\"02B0298\\\";s:7:\\\"matdesc\\\";s:21:\\\"BOLT+NUT CUTTING EDGE\\\";s:8:\\\"quantity\\\";s:5:\\\"3.000\\\";s:5:\\\"grqty\\\";s:5:\\\"0.000\\\";s:7:\\\"openqty\\\";s:5:\\\"3.000\\\";s:4:\\\"unit\\\";s:3:\\\"PCS\\\";s:5:\\\"price\\\";s:9:\\\"30000.000\\\";s:8:\\\"grstatus\\\";s:1:\\\"O\\\";s:10:\\\"pocomplete\\\";N;s:7:\\\"duedate\\\";i:0;s:8:\\\"deptname\\\";s:4:\\\"HSSE\\\";}}s:28:\\\"\\u0000*\\u0000escapeWhenCastingToString\\\";b:0;}s:4:\\\"poid\\\";i:489;s:5:\\\"ponum\\\";s:17:\\\"PO\\/20231229000001\\\";s:6:\\\"locale\\\";N;s:4:\\\"from\\\";a:0:{}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:28:\\\"fransiskusaditya88@gmail.com\\\";}}s:2:\\\"cc\\\";a:0:{}s:3:\\\"bcc\\\";a:0:{}s:7:\\\"replyTo\\\";a:0:{}s:7:\\\"subject\\\";N;s:8:\\\"markdown\\\";N;s:7:\\\"\\u0000*\\u0000html\\\";N;s:4:\\\"view\\\";N;s:8:\\\"textView\\\";N;s:8:\\\"viewData\\\";a:0:{}s:11:\\\"attachments\\\";a:0:{}s:14:\\\"rawAttachments\\\";a:0:{}s:15:\\\"diskAttachments\\\";a:0:{}s:9:\\\"callbacks\\\";a:0:{}s:5:\\\"theme\\\";N;s:6:\\\"mailer\\\";s:4:\\\"smtp\\\";s:29:\\\"\\u0000*\\u0000assertionableRenderStrings\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\"}}', 0, NULL, 1703844475, 1703844475);
INSERT INTO `jobs` (`id`, `queue`, `payload`, `attempts`, `reserved_at`, `available_at`, `created_at`) VALUES
(122, 'default', '{\"uuid\":\"48c3e7b1-8c88-437a-b449-2bac99bbdcc6\",\"displayName\":\"App\\\\Mail\\\\NotifApprovePoMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":13:{s:8:\\\"mailable\\\";O:27:\\\"App\\\\Mail\\\\NotifApprovePoMail\\\":31:{s:4:\\\"data\\\";O:29:\\\"Illuminate\\\\Support\\\\Collection\\\":2:{s:8:\\\"\\u0000*\\u0000items\\\";a:1:{i:0;O:8:\\\"stdClass\\\":34:{s:2:\\\"id\\\";i:489;s:5:\\\"ponum\\\";s:17:\\\"PO\\/20231229000001\\\";s:9:\\\"ext_ponum\\\";s:17:\\\"PO\\/20231229000001\\\";s:6:\\\"deptid\\\";i:5;s:6:\\\"potype\\\";N;s:5:\\\"podat\\\";s:10:\\\"2023-12-29\\\";s:13:\\\"delivery_date\\\";s:10:\\\"2023-12-29\\\";s:6:\\\"vendor\\\";s:6:\\\"300001\\\";s:4:\\\"note\\\";s:4:\\\"Test\\\";s:8:\\\"currency\\\";s:3:\\\"IDR\\\";s:11:\\\"approvestat\\\";s:1:\\\"R\\\";s:5:\\\"appby\\\";N;s:9:\\\"completed\\\";N;s:3:\\\"ppn\\\";s:4:\\\"0.00\\\";s:8:\\\"tf_price\\\";N;s:7:\\\"tf_dest\\\";N;s:11:\\\"tf_shipment\\\";N;s:6:\\\"tf_top\\\";N;s:10:\\\"tf_packing\\\";N;s:11:\\\"tf_shipdate\\\";N;s:9:\\\"createdon\\\";s:19:\\\"2023-12-29 10:12:55\\\";s:9:\\\"createdby\\\";s:28:\\\"fransiskusaditya88@gmail.com\\\";s:6:\\\"poitem\\\";i:3;s:8:\\\"material\\\";s:7:\\\"02B0298\\\";s:7:\\\"matdesc\\\";s:21:\\\"BOLT+NUT CUTTING EDGE\\\";s:8:\\\"quantity\\\";s:5:\\\"3.000\\\";s:5:\\\"grqty\\\";s:5:\\\"0.000\\\";s:7:\\\"openqty\\\";s:5:\\\"3.000\\\";s:4:\\\"unit\\\";s:3:\\\"PCS\\\";s:5:\\\"price\\\";s:9:\\\"30000.000\\\";s:8:\\\"grstatus\\\";s:1:\\\"O\\\";s:10:\\\"pocomplete\\\";N;s:7:\\\"duedate\\\";i:0;s:8:\\\"deptname\\\";s:4:\\\"HSSE\\\";}}s:28:\\\"\\u0000*\\u0000escapeWhenCastingToString\\\";b:0;}s:4:\\\"poid\\\";i:489;s:5:\\\"ponum\\\";s:17:\\\"PO\\/20231229000001\\\";s:6:\\\"locale\\\";N;s:4:\\\"from\\\";a:0:{}s:2:\\\"to\\\";a:0:{}s:2:\\\"cc\\\";a:0:{}s:3:\\\"bcc\\\";a:0:{}s:7:\\\"replyTo\\\";a:0:{}s:7:\\\"subject\\\";N;s:8:\\\"markdown\\\";N;s:7:\\\"\\u0000*\\u0000html\\\";N;s:4:\\\"view\\\";N;s:8:\\\"textView\\\";N;s:8:\\\"viewData\\\";a:0:{}s:11:\\\"attachments\\\";a:0:{}s:14:\\\"rawAttachments\\\";a:0:{}s:15:\\\"diskAttachments\\\";a:0:{}s:9:\\\"callbacks\\\";a:0:{}s:5:\\\"theme\\\";N;s:6:\\\"mailer\\\";s:4:\\\"smtp\\\";s:29:\\\"\\u0000*\\u0000assertionableRenderStrings\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\"}}', 0, NULL, 1703844672, 1703844672),
(123, 'default', '{\"uuid\":\"8dbb0e77-c2ed-4e33-a344-dfaf60e29016\",\"displayName\":\"App\\\\Mail\\\\NotifApprovePbjMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":13:{s:8:\\\"mailable\\\";O:28:\\\"App\\\\Mail\\\\NotifApprovePbjMail\\\":31:{s:4:\\\"data\\\";O:29:\\\"Illuminate\\\\Support\\\\Collection\\\":2:{s:8:\\\"\\u0000*\\u0000items\\\";a:3:{i:0;O:8:\\\"stdClass\\\":34:{s:2:\\\"id\\\";i:665;s:9:\\\"pbjnumber\\\";s:17:\\\"PBJ-IT\\/2024000001\\\";s:6:\\\"deptid\\\";i:1;s:7:\\\"tgl_pbj\\\";s:10:\\\"2024-02-01\\\";s:17:\\\"tujuan_permintaan\\\";s:4:\\\"Test\\\";s:6:\\\"kepada\\\";s:5:\\\"Plant\\\";s:9:\\\"unit_desc\\\";N;s:12:\\\"engine_model\\\";s:3:\\\"tes\\\";s:10:\\\"chassis_sn\\\";s:3:\\\"tes\\\";s:9:\\\"reference\\\";s:4:\\\"test\\\";s:9:\\\"requestor\\\";s:13:\\\"Administrator\\\";s:10:\\\"type_model\\\";N;s:4:\\\"user\\\";N;s:13:\\\"kode_brg_jasa\\\";N;s:9:\\\"engine_sn\\\";N;s:5:\\\"hm_km\\\";s:1:\\\"0\\\";s:2:\\\"km\\\";s:1:\\\"0\\\";s:16:\\\"budget_cost_code\\\";s:1:\\\"1\\\";s:14:\\\"cheklistnumber\\\";N;s:10:\\\"pbj_status\\\";s:1:\\\"O\\\";s:9:\\\"createdby\\\";s:28:\\\"fransiskusaditya88@gmail.com\\\";s:9:\\\"createdon\\\";s:19:\\\"2024-02-01 21:50:02\\\";s:7:\\\"pbjitem\\\";i:1;s:10:\\\"partnumber\\\";s:6:\\\"100017\\\";s:11:\\\"description\\\";s:6:\\\"CUTTER\\\";s:8:\\\"quantity\\\";s:6:\\\"10.000\\\";s:4:\\\"unit\\\";s:3:\\\"PCS\\\";s:6:\\\"figure\\\";s:0:\\\"\\\";s:6:\\\"remark\\\";s:0:\\\"\\\";s:11:\\\"approvestat\\\";s:1:\\\"O\\\";s:9:\\\"prcreated\\\";s:1:\\\"N\\\";s:9:\\\"wocreated\\\";s:1:\\\"N\\\";s:7:\\\"duedate\\\";i:0;s:8:\\\"deptname\\\";s:2:\\\"IT\\\";}i:1;O:8:\\\"stdClass\\\":34:{s:2:\\\"id\\\";i:665;s:9:\\\"pbjnumber\\\";s:17:\\\"PBJ-IT\\/2024000001\\\";s:6:\\\"deptid\\\";i:1;s:7:\\\"tgl_pbj\\\";s:10:\\\"2024-02-01\\\";s:17:\\\"tujuan_permintaan\\\";s:4:\\\"Test\\\";s:6:\\\"kepada\\\";s:5:\\\"Plant\\\";s:9:\\\"unit_desc\\\";N;s:12:\\\"engine_model\\\";s:3:\\\"tes\\\";s:10:\\\"chassis_sn\\\";s:3:\\\"tes\\\";s:9:\\\"reference\\\";s:4:\\\"test\\\";s:9:\\\"requestor\\\";s:13:\\\"Administrator\\\";s:10:\\\"type_model\\\";N;s:4:\\\"user\\\";N;s:13:\\\"kode_brg_jasa\\\";N;s:9:\\\"engine_sn\\\";N;s:5:\\\"hm_km\\\";s:1:\\\"0\\\";s:2:\\\"km\\\";s:1:\\\"0\\\";s:16:\\\"budget_cost_code\\\";s:1:\\\"1\\\";s:14:\\\"cheklistnumber\\\";N;s:10:\\\"pbj_status\\\";s:1:\\\"O\\\";s:9:\\\"createdby\\\";s:28:\\\"fransiskusaditya88@gmail.com\\\";s:9:\\\"createdon\\\";s:19:\\\"2024-02-01 21:50:02\\\";s:7:\\\"pbjitem\\\";i:2;s:10:\\\"partnumber\\\";s:9:\\\"15W40-DH1\\\";s:11:\\\"description\\\";s:14:\\\"Oil Engine Pan\\\";s:8:\\\"quantity\\\";s:6:\\\"11.000\\\";s:4:\\\"unit\\\";s:5:\\\"Liter\\\";s:6:\\\"figure\\\";s:0:\\\"\\\";s:6:\\\"remark\\\";s:0:\\\"\\\";s:11:\\\"approvestat\\\";s:1:\\\"O\\\";s:9:\\\"prcreated\\\";s:1:\\\"N\\\";s:9:\\\"wocreated\\\";s:1:\\\"N\\\";s:7:\\\"duedate\\\";i:0;s:8:\\\"deptname\\\";s:2:\\\"IT\\\";}i:2;O:8:\\\"stdClass\\\":34:{s:2:\\\"id\\\";i:665;s:9:\\\"pbjnumber\\\";s:17:\\\"PBJ-IT\\/2024000001\\\";s:6:\\\"deptid\\\";i:1;s:7:\\\"tgl_pbj\\\";s:10:\\\"2024-02-01\\\";s:17:\\\"tujuan_permintaan\\\";s:4:\\\"Test\\\";s:6:\\\"kepada\\\";s:5:\\\"Plant\\\";s:9:\\\"unit_desc\\\";N;s:12:\\\"engine_model\\\";s:3:\\\"tes\\\";s:10:\\\"chassis_sn\\\";s:3:\\\"tes\\\";s:9:\\\"reference\\\";s:4:\\\"test\\\";s:9:\\\"requestor\\\";s:13:\\\"Administrator\\\";s:10:\\\"type_model\\\";N;s:4:\\\"user\\\";N;s:13:\\\"kode_brg_jasa\\\";N;s:9:\\\"engine_sn\\\";N;s:5:\\\"hm_km\\\";s:1:\\\"0\\\";s:2:\\\"km\\\";s:1:\\\"0\\\";s:16:\\\"budget_cost_code\\\";s:1:\\\"1\\\";s:14:\\\"cheklistnumber\\\";N;s:10:\\\"pbj_status\\\";s:1:\\\"O\\\";s:9:\\\"createdby\\\";s:28:\\\"fransiskusaditya88@gmail.com\\\";s:9:\\\"createdon\\\";s:19:\\\"2024-02-01 21:50:02\\\";s:7:\\\"pbjitem\\\";i:3;s:10:\\\"partnumber\\\";s:10:\\\"165289Z00A\\\";s:11:\\\"description\\\";s:16:\\\"AIR FILTER INNER\\\";s:8:\\\"quantity\\\";s:6:\\\"12.000\\\";s:4:\\\"unit\\\";s:3:\\\"PCS\\\";s:6:\\\"figure\\\";s:0:\\\"\\\";s:6:\\\"remark\\\";s:0:\\\"\\\";s:11:\\\"approvestat\\\";s:1:\\\"O\\\";s:9:\\\"prcreated\\\";s:1:\\\"N\\\";s:9:\\\"wocreated\\\";s:1:\\\"N\\\";s:7:\\\"duedate\\\";i:0;s:8:\\\"deptname\\\";s:2:\\\"IT\\\";}}s:28:\\\"\\u0000*\\u0000escapeWhenCastingToString\\\";b:0;}s:5:\\\"pbjid\\\";i:665;s:6:\\\"pbjnum\\\";s:17:\\\"PBJ-IT\\/2024000001\\\";s:6:\\\"locale\\\";N;s:4:\\\"from\\\";a:0:{}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:28:\\\"fransiskusaditya88@gmail.com\\\";}}s:2:\\\"cc\\\";a:0:{}s:3:\\\"bcc\\\";a:0:{}s:7:\\\"replyTo\\\";a:0:{}s:7:\\\"subject\\\";N;s:8:\\\"markdown\\\";N;s:7:\\\"\\u0000*\\u0000html\\\";N;s:4:\\\"view\\\";N;s:8:\\\"textView\\\";N;s:8:\\\"viewData\\\";a:0:{}s:11:\\\"attachments\\\";a:0:{}s:14:\\\"rawAttachments\\\";a:0:{}s:15:\\\"diskAttachments\\\";a:0:{}s:9:\\\"callbacks\\\";a:0:{}s:5:\\\"theme\\\";N;s:6:\\\"mailer\\\";s:4:\\\"smtp\\\";s:29:\\\"\\u0000*\\u0000assertionableRenderStrings\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\"}}', 0, NULL, 1706799006, 1706799006),
(124, 'default', '{\"uuid\":\"a79394e4-747b-4b08-8cd5-ffda4a06ddd3\",\"displayName\":\"App\\\\Mail\\\\NotifApprovePrMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":13:{s:8:\\\"mailable\\\";O:27:\\\"App\\\\Mail\\\\NotifApprovePrMail\\\":31:{s:4:\\\"data\\\";O:29:\\\"Illuminate\\\\Support\\\\Collection\\\":2:{s:8:\\\"\\u0000*\\u0000items\\\";a:3:{i:0;O:8:\\\"stdClass\\\":26:{s:2:\\\"id\\\";i:598;s:5:\\\"prnum\\\";s:16:\\\"PR-IT\\/2024000001\\\";s:6:\\\"typepr\\\";N;s:4:\\\"note\\\";N;s:6:\\\"prdate\\\";s:10:\\\"2024-02-28\\\";s:8:\\\"relgroup\\\";N;s:11:\\\"approvestat\\\";s:1:\\\"N\\\";s:9:\\\"requestby\\\";s:13:\\\"Administrator\\\";s:9:\\\"warehouse\\\";N;s:9:\\\"idproject\\\";N;s:6:\\\"remark\\\";s:3:\\\"tes\\\";s:5:\\\"appby\\\";N;s:6:\\\"deptid\\\";i:1;s:9:\\\"createdon\\\";s:19:\\\"2024-02-28 18:16:40\\\";s:9:\\\"createdby\\\";s:28:\\\"fransiskusaditya88@gmail.com\\\";s:6:\\\"pritem\\\";i:1;s:8:\\\"material\\\";s:7:\\\"01B0643\\\";s:7:\\\"matdesc\\\";s:11:\\\"BOLT U-RELL\\\";s:8:\\\"quantity\\\";s:5:\\\"3.000\\\";s:4:\\\"unit\\\";s:3:\\\"PCS\\\";s:9:\\\"pocreated\\\";s:1:\\\"N\\\";s:9:\\\"pbjnumber\\\";s:1:\\\"0\\\";s:7:\\\"pbjitem\\\";i:0;s:7:\\\"no_plat\\\";s:0:\\\"\\\";s:7:\\\"duedate\\\";i:0;s:8:\\\"deptname\\\";s:2:\\\"IT\\\";}i:1;O:8:\\\"stdClass\\\":26:{s:2:\\\"id\\\";i:598;s:5:\\\"prnum\\\";s:16:\\\"PR-IT\\/2024000001\\\";s:6:\\\"typepr\\\";N;s:4:\\\"note\\\";N;s:6:\\\"prdate\\\";s:10:\\\"2024-02-28\\\";s:8:\\\"relgroup\\\";N;s:11:\\\"approvestat\\\";s:1:\\\"N\\\";s:9:\\\"requestby\\\";s:13:\\\"Administrator\\\";s:9:\\\"warehouse\\\";N;s:9:\\\"idproject\\\";N;s:6:\\\"remark\\\";s:3:\\\"tes\\\";s:5:\\\"appby\\\";N;s:6:\\\"deptid\\\";i:1;s:9:\\\"createdon\\\";s:19:\\\"2024-02-28 18:16:40\\\";s:9:\\\"createdby\\\";s:28:\\\"fransiskusaditya88@gmail.com\\\";s:6:\\\"pritem\\\";i:2;s:8:\\\"material\\\";s:7:\\\"01B1674\\\";s:7:\\\"matdesc\\\";s:28:\\\"BOLT CUTTING EDGE + END BEAT\\\";s:8:\\\"quantity\\\";s:5:\\\"4.000\\\";s:4:\\\"unit\\\";s:3:\\\"PCS\\\";s:9:\\\"pocreated\\\";s:1:\\\"N\\\";s:9:\\\"pbjnumber\\\";s:1:\\\"0\\\";s:7:\\\"pbjitem\\\";i:0;s:7:\\\"no_plat\\\";s:0:\\\"\\\";s:7:\\\"duedate\\\";i:0;s:8:\\\"deptname\\\";s:2:\\\"IT\\\";}i:2;O:8:\\\"stdClass\\\":26:{s:2:\\\"id\\\";i:598;s:5:\\\"prnum\\\";s:16:\\\"PR-IT\\/2024000001\\\";s:6:\\\"typepr\\\";N;s:4:\\\"note\\\";N;s:6:\\\"prdate\\\";s:10:\\\"2024-02-28\\\";s:8:\\\"relgroup\\\";N;s:11:\\\"approvestat\\\";s:1:\\\"N\\\";s:9:\\\"requestby\\\";s:13:\\\"Administrator\\\";s:9:\\\"warehouse\\\";N;s:9:\\\"idproject\\\";N;s:6:\\\"remark\\\";s:3:\\\"tes\\\";s:5:\\\"appby\\\";N;s:6:\\\"deptid\\\";i:1;s:9:\\\"createdon\\\";s:19:\\\"2024-02-28 18:16:40\\\";s:9:\\\"createdby\\\";s:28:\\\"fransiskusaditya88@gmail.com\\\";s:6:\\\"pritem\\\";i:3;s:8:\\\"material\\\";s:7:\\\"02B0298\\\";s:7:\\\"matdesc\\\";s:21:\\\"BOLT+NUT CUTTING EDGE\\\";s:8:\\\"quantity\\\";s:5:\\\"5.000\\\";s:4:\\\"unit\\\";s:3:\\\"PCS\\\";s:9:\\\"pocreated\\\";s:1:\\\"N\\\";s:9:\\\"pbjnumber\\\";s:1:\\\"0\\\";s:7:\\\"pbjitem\\\";i:0;s:7:\\\"no_plat\\\";s:0:\\\"\\\";s:7:\\\"duedate\\\";i:0;s:8:\\\"deptname\\\";s:2:\\\"IT\\\";}}s:28:\\\"\\u0000*\\u0000escapeWhenCastingToString\\\";b:0;}s:5:\\\"pprid\\\";i:598;s:5:\\\"prnum\\\";s:16:\\\"PR-IT\\/2024000001\\\";s:6:\\\"locale\\\";N;s:4:\\\"from\\\";a:0:{}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:28:\\\"fransiskusaditya88@gmail.com\\\";}}s:2:\\\"cc\\\";a:0:{}s:3:\\\"bcc\\\";a:0:{}s:7:\\\"replyTo\\\";a:0:{}s:7:\\\"subject\\\";N;s:8:\\\"markdown\\\";N;s:7:\\\"\\u0000*\\u0000html\\\";N;s:4:\\\"view\\\";N;s:8:\\\"textView\\\";N;s:8:\\\"viewData\\\";a:0:{}s:11:\\\"attachments\\\";a:0:{}s:14:\\\"rawAttachments\\\";a:0:{}s:15:\\\"diskAttachments\\\";a:0:{}s:9:\\\"callbacks\\\";a:0:{}s:5:\\\"theme\\\";N;s:6:\\\"mailer\\\";s:4:\\\"smtp\\\";s:29:\\\"\\u0000*\\u0000assertionableRenderStrings\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\"}}', 0, NULL, 1709119003, 1709119003),
(125, 'default', '{\"uuid\":\"91fc7bc3-b20e-43c7-99f8-79d3ac4e90db\",\"displayName\":\"App\\\\Mail\\\\NotifApprovePrMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":13:{s:8:\\\"mailable\\\";O:27:\\\"App\\\\Mail\\\\NotifApprovePrMail\\\":31:{s:4:\\\"data\\\";O:29:\\\"Illuminate\\\\Support\\\\Collection\\\":2:{s:8:\\\"\\u0000*\\u0000items\\\";a:1:{i:0;O:8:\\\"stdClass\\\":26:{s:2:\\\"id\\\";i:598;s:5:\\\"prnum\\\";s:16:\\\"PR-IT\\/2024000001\\\";s:6:\\\"typepr\\\";N;s:4:\\\"note\\\";N;s:6:\\\"prdate\\\";s:10:\\\"2024-02-28\\\";s:8:\\\"relgroup\\\";N;s:11:\\\"approvestat\\\";s:1:\\\"N\\\";s:9:\\\"requestby\\\";s:13:\\\"Administrator\\\";s:9:\\\"warehouse\\\";N;s:9:\\\"idproject\\\";N;s:6:\\\"remark\\\";s:3:\\\"tes\\\";s:5:\\\"appby\\\";N;s:6:\\\"deptid\\\";i:1;s:9:\\\"createdon\\\";s:19:\\\"2024-02-28 18:16:40\\\";s:9:\\\"createdby\\\";s:28:\\\"fransiskusaditya88@gmail.com\\\";s:6:\\\"pritem\\\";i:1;s:8:\\\"material\\\";s:7:\\\"01B0643\\\";s:7:\\\"matdesc\\\";s:11:\\\"BOLT U-RELL\\\";s:8:\\\"quantity\\\";s:5:\\\"3.000\\\";s:4:\\\"unit\\\";s:3:\\\"PCS\\\";s:9:\\\"pocreated\\\";s:1:\\\"N\\\";s:9:\\\"pbjnumber\\\";s:1:\\\"0\\\";s:7:\\\"pbjitem\\\";i:0;s:7:\\\"no_plat\\\";s:0:\\\"\\\";s:7:\\\"duedate\\\";i:0;s:8:\\\"deptname\\\";s:2:\\\"IT\\\";}}s:28:\\\"\\u0000*\\u0000escapeWhenCastingToString\\\";b:0;}s:5:\\\"pprid\\\";i:598;s:5:\\\"prnum\\\";s:16:\\\"PR-IT\\/2024000001\\\";s:6:\\\"locale\\\";N;s:4:\\\"from\\\";a:0:{}s:2:\\\"to\\\";a:0:{}s:2:\\\"cc\\\";a:0:{}s:3:\\\"bcc\\\";a:0:{}s:7:\\\"replyTo\\\";a:0:{}s:7:\\\"subject\\\";N;s:8:\\\"markdown\\\";N;s:7:\\\"\\u0000*\\u0000html\\\";N;s:4:\\\"view\\\";N;s:8:\\\"textView\\\";N;s:8:\\\"viewData\\\";a:0:{}s:11:\\\"attachments\\\";a:0:{}s:14:\\\"rawAttachments\\\";a:0:{}s:15:\\\"diskAttachments\\\";a:0:{}s:9:\\\"callbacks\\\";a:0:{}s:5:\\\"theme\\\";N;s:6:\\\"mailer\\\";s:4:\\\"smtp\\\";s:29:\\\"\\u0000*\\u0000assertionableRenderStrings\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\"}}', 0, NULL, 1709119670, 1709119670),
(126, 'default', '{\"uuid\":\"e99e00e4-4688-445e-a217-0fc1cf69dc3d\",\"displayName\":\"App\\\\Mail\\\\NotifApprovePoMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":13:{s:8:\\\"mailable\\\";O:27:\\\"App\\\\Mail\\\\NotifApprovePoMail\\\":31:{s:4:\\\"data\\\";O:29:\\\"Illuminate\\\\Support\\\\Collection\\\":2:{s:8:\\\"\\u0000*\\u0000items\\\";a:2:{i:0;O:8:\\\"stdClass\\\":34:{s:2:\\\"id\\\";i:358;s:5:\\\"ponum\\\";s:17:\\\"PO\\/20231009000008\\\";s:9:\\\"ext_ponum\\\";s:17:\\\"PO\\/20231009000008\\\";s:6:\\\"deptid\\\";i:1;s:6:\\\"potype\\\";N;s:5:\\\"podat\\\";s:10:\\\"2023-10-09\\\";s:13:\\\"delivery_date\\\";s:10:\\\"2023-10-09\\\";s:6:\\\"vendor\\\";s:6:\\\"300315\\\";s:4:\\\"note\\\";s:4:\\\"dsad\\\";s:8:\\\"currency\\\";N;s:11:\\\"approvestat\\\";s:1:\\\"O\\\";s:5:\\\"appby\\\";N;s:9:\\\"completed\\\";N;s:3:\\\"ppn\\\";s:4:\\\"0.00\\\";s:8:\\\"tf_price\\\";N;s:7:\\\"tf_dest\\\";N;s:11:\\\"tf_shipment\\\";N;s:6:\\\"tf_top\\\";N;s:10:\\\"tf_packing\\\";N;s:11:\\\"tf_shipdate\\\";N;s:9:\\\"createdon\\\";s:19:\\\"2023-10-09 09:10:45\\\";s:9:\\\"createdby\\\";s:28:\\\"fransiskusaditya88@gmail.com\\\";s:6:\\\"poitem\\\";i:1;s:8:\\\"material\\\";s:7:\\\"01B0643\\\";s:7:\\\"matdesc\\\";s:11:\\\"BOLT U-RELL\\\";s:8:\\\"quantity\\\";s:6:\\\"20.000\\\";s:5:\\\"grqty\\\";s:5:\\\"0.000\\\";s:7:\\\"openqty\\\";s:6:\\\"20.000\\\";s:4:\\\"unit\\\";s:3:\\\"PCS\\\";s:5:\\\"price\\\";s:10:\\\"500000.000\\\";s:8:\\\"grstatus\\\";s:1:\\\"O\\\";s:10:\\\"pocomplete\\\";N;s:7:\\\"duedate\\\";i:142;s:8:\\\"deptname\\\";s:2:\\\"IT\\\";}i:1;O:8:\\\"stdClass\\\":34:{s:2:\\\"id\\\";i:358;s:5:\\\"ponum\\\";s:17:\\\"PO\\/20231009000008\\\";s:9:\\\"ext_ponum\\\";s:17:\\\"PO\\/20231009000008\\\";s:6:\\\"deptid\\\";i:1;s:6:\\\"potype\\\";N;s:5:\\\"podat\\\";s:10:\\\"2023-10-09\\\";s:13:\\\"delivery_date\\\";s:10:\\\"2023-10-09\\\";s:6:\\\"vendor\\\";s:6:\\\"300315\\\";s:4:\\\"note\\\";s:4:\\\"dsad\\\";s:8:\\\"currency\\\";N;s:11:\\\"approvestat\\\";s:1:\\\"O\\\";s:5:\\\"appby\\\";N;s:9:\\\"completed\\\";N;s:3:\\\"ppn\\\";s:4:\\\"0.00\\\";s:8:\\\"tf_price\\\";N;s:7:\\\"tf_dest\\\";N;s:11:\\\"tf_shipment\\\";N;s:6:\\\"tf_top\\\";N;s:10:\\\"tf_packing\\\";N;s:11:\\\"tf_shipdate\\\";N;s:9:\\\"createdon\\\";s:19:\\\"2023-10-09 09:10:45\\\";s:9:\\\"createdby\\\";s:28:\\\"fransiskusaditya88@gmail.com\\\";s:6:\\\"poitem\\\";i:2;s:8:\\\"material\\\";s:7:\\\"01B1674\\\";s:7:\\\"matdesc\\\";s:28:\\\"BOLT CUTTING EDGE + END BEAT\\\";s:8:\\\"quantity\\\";s:6:\\\"40.000\\\";s:5:\\\"grqty\\\";s:5:\\\"0.000\\\";s:7:\\\"openqty\\\";s:6:\\\"40.000\\\";s:4:\\\"unit\\\";s:3:\\\"PCS\\\";s:5:\\\"price\\\";s:10:\\\"200000.000\\\";s:8:\\\"grstatus\\\";s:1:\\\"O\\\";s:10:\\\"pocomplete\\\";N;s:7:\\\"duedate\\\";i:142;s:8:\\\"deptname\\\";s:2:\\\"IT\\\";}}s:28:\\\"\\u0000*\\u0000escapeWhenCastingToString\\\";b:0;}s:4:\\\"poid\\\";i:358;s:5:\\\"ponum\\\";s:17:\\\"PO\\/20231009000008\\\";s:6:\\\"locale\\\";N;s:4:\\\"from\\\";a:0:{}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:28:\\\"fransiskusaditya88@gmail.com\\\";}}s:2:\\\"cc\\\";a:0:{}s:3:\\\"bcc\\\";a:0:{}s:7:\\\"replyTo\\\";a:0:{}s:7:\\\"subject\\\";N;s:8:\\\"markdown\\\";N;s:7:\\\"\\u0000*\\u0000html\\\";N;s:4:\\\"view\\\";N;s:8:\\\"textView\\\";N;s:8:\\\"viewData\\\";a:0:{}s:11:\\\"attachments\\\";a:0:{}s:14:\\\"rawAttachments\\\";a:0:{}s:15:\\\"diskAttachments\\\";a:0:{}s:9:\\\"callbacks\\\";a:0:{}s:5:\\\"theme\\\";N;s:6:\\\"mailer\\\";s:4:\\\"smtp\\\";s:29:\\\"\\u0000*\\u0000assertionableRenderStrings\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\"}}', 0, NULL, 1709119820, 1709119820);

-- --------------------------------------------------------

--
-- Struktur dari tabel `menugroups`
--

CREATE TABLE `menugroups` (
  `id` bigint UNSIGNED NOT NULL,
  `menugroup` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `groupicon` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `_index` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `createdby` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `updatedby` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `menugroups`
--

INSERT INTO `menugroups` (`id`, `menugroup`, `groupicon`, `_index`, `created_at`, `updated_at`, `createdby`, `updatedby`) VALUES
(1, 'MASTER', 'fa fa-database', 1, '2022-07-26 02:12:00', NULL, 'sys-admin', ''),
(2, 'SETTINGS', 'fa fa-gear', 8, '2022-07-26 02:12:09', NULL, 'sys-admin', 'husnulmub@gmail.com'),
(3, 'PURCHASE ORDER', 'fa fa-cart-shopping', 3, '2022-07-26 02:12:09', NULL, 'sys-admin', 'husnulmub@gmail.com'),
(6, 'LOGISTICS', 'fa fa-truck', 5, '2022-08-26 06:08:52', NULL, 'husnulmub@gmail.com', 'husnulmub@gmail.com'),
(7, 'APPROVAL', 'fa fa-circle-check', 6, '2022-10-04 00:10:39', NULL, 'husnulmub@gmail.com', 'husnulmub@gmail.com'),
(9, 'REPORTS', 'fa fa-list', 7, '2022-10-15 19:10:42', NULL, 'husnulmub@gmail.com', 'husnulmub@gmail.com'),
(12, 'PURCHASE REQ', 'fa fa-cart-shopping', 2, '2024-03-03 16:03:01', NULL, 'husnulmub@gmail.com', 'husnulmub@gmail.com'),
(13, 'QUOTATION', 'quotation', 4, '2024-03-25 20:03:54', NULL, 'husnulmub@gmail.com', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `menuroles`
--

CREATE TABLE `menuroles` (
  `menuid` int NOT NULL,
  `roleid` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `createdby` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `updatedby` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `menuroles`
--

INSERT INTO `menuroles` (`menuid`, `roleid`, `created_at`, `updated_at`, `createdby`, `updatedby`) VALUES
(1, 1, '2022-07-26 02:20:34', NULL, 'sys-admin', ''),
(1, 2, '2022-07-26 03:07:15', NULL, 'husnulmub@gmail.com', NULL),
(1, 48, '2023-06-07 23:06:03', NULL, 'husnulmub@gmail.com', NULL),
(2, 1, '2022-07-26 02:20:34', NULL, 'sys-admin', ''),
(2, 2, '2022-07-26 03:07:19', NULL, 'husnulmub@gmail.com', NULL),
(2, 13, '2023-03-14 23:03:40', NULL, 'husnulmub@gmail.com', NULL),
(2, 28, '2023-06-12 00:06:23', NULL, 'husnulmub@gmail.com', NULL),
(2, 39, '2023-06-12 00:06:13', NULL, 'husnulmub@gmail.com', NULL),
(2, 48, '2023-06-07 23:06:56', NULL, 'husnulmub@gmail.com', NULL),
(4, 1, '2022-10-01 08:10:43', NULL, 'husnulmub@gmail.com', NULL),
(4, 48, '2023-06-07 23:06:03', NULL, 'husnulmub@gmail.com', NULL),
(5, 1, '2022-07-26 02:21:32', NULL, 'sys-admin', ''),
(5, 48, '2023-06-07 23:06:04', NULL, 'husnulmub@gmail.com', NULL),
(7, 1, '2022-07-26 18:07:53', NULL, 'husnulmub@gmail.com', NULL),
(7, 48, '2023-06-07 23:06:05', NULL, 'husnulmub@gmail.com', NULL),
(20, 1, '2022-08-17 14:08:34', NULL, 'husnulmub@gmail.com', NULL),
(20, 48, '2023-06-07 23:06:05', NULL, 'husnulmub@gmail.com', NULL),
(25, 1, '2022-09-13 15:09:06', NULL, 'husnulmub@gmail.com', NULL),
(25, 17, '2023-04-08 00:04:10', NULL, 'husnulmub@gmail.com', NULL),
(25, 19, '2023-07-17 20:07:02', NULL, 'fransiskusaditya88@gmail.com', NULL),
(25, 45, '2023-07-17 20:07:14', NULL, 'fransiskusaditya88@gmail.com', NULL),
(25, 48, '2023-06-07 23:06:57', NULL, 'husnulmub@gmail.com', NULL),
(26, 1, '2022-09-20 06:09:12', NULL, 'husnulmub@gmail.com', NULL),
(26, 48, '2023-06-07 23:06:06', NULL, 'husnulmub@gmail.com', NULL),
(29, 1, '2022-10-01 22:10:56', NULL, 'husnulmub@gmail.com', NULL),
(29, 15, '2023-03-27 19:03:05', NULL, 'husnulmub@gmail.com', NULL),
(29, 39, '2023-06-07 00:06:51', NULL, 'husnulmub@gmail.com', NULL),
(30, 1, '2022-10-01 22:10:58', NULL, 'husnulmub@gmail.com', NULL),
(31, 1, '2022-10-03 23:10:25', NULL, 'husnulmub@gmail.com', NULL),
(31, 15, '2023-03-14 23:03:16', NULL, 'husnulmub@gmail.com', NULL),
(31, 17, '2023-03-23 00:03:33', NULL, 'husnulmub@gmail.com', NULL),
(31, 19, '2023-06-06 10:06:15', NULL, 'husnulmub@gmail.com', NULL),
(31, 23, '2023-06-06 23:06:57', NULL, 'husnulmub@gmail.com', NULL),
(31, 24, '2023-06-06 19:06:40', NULL, 'husnulmub@gmail.com', NULL),
(31, 28, '2023-07-17 20:07:14', NULL, 'fransiskusaditya88@gmail.com', NULL),
(31, 39, '2023-06-07 00:06:12', NULL, 'husnulmub@gmail.com', NULL),
(31, 49, '2023-08-09 19:08:39', NULL, 'fransiskusaditya88@gmail.com', NULL),
(32, 1, '2022-10-03 23:10:27', NULL, 'husnulmub@gmail.com', NULL),
(32, 17, '2023-03-23 00:03:29', NULL, 'husnulmub@gmail.com', NULL),
(32, 19, '2023-06-06 10:06:06', NULL, 'husnulmub@gmail.com', NULL),
(32, 23, '2023-06-06 23:06:59', NULL, 'husnulmub@gmail.com', NULL),
(32, 24, '2023-06-06 19:06:46', NULL, 'husnulmub@gmail.com', NULL),
(32, 49, '2023-08-09 19:08:45', NULL, 'fransiskusaditya88@gmail.com', NULL),
(35, 1, '2024-03-04 19:03:48', NULL, 'husnulmub@gmail.com', NULL),
(35, 7, '2022-10-04 00:10:47', NULL, 'husnulmub@gmail.com', NULL),
(35, 13, '2023-03-14 23:03:35', NULL, 'husnulmub@gmail.com', NULL),
(35, 39, '2023-06-07 00:06:30', NULL, 'husnulmub@gmail.com', NULL),
(35, 40, '2023-06-07 00:06:45', NULL, 'husnulmub@gmail.com', NULL),
(36, 1, '2024-03-04 19:03:54', NULL, 'husnulmub@gmail.com', NULL),
(36, 6, '2022-10-04 00:10:22', NULL, 'husnulmub@gmail.com', NULL),
(36, 16, '2023-03-23 00:03:14', NULL, 'husnulmub@gmail.com', NULL),
(36, 37, '2023-06-07 00:06:47', NULL, 'husnulmub@gmail.com', NULL),
(36, 38, '2023-06-07 00:06:07', NULL, 'husnulmub@gmail.com', NULL),
(36, 45, '2023-06-07 00:06:33', NULL, 'husnulmub@gmail.com', NULL),
(44, 1, '2022-10-25 20:10:43', NULL, 'husnulmub@gmail.com', NULL),
(44, 48, '2023-06-07 23:06:59', NULL, 'husnulmub@gmail.com', NULL),
(48, 1, '2022-11-06 21:11:24', NULL, 'husnulmub@gmail.com', NULL),
(48, 11, '2023-03-14 23:03:02', NULL, 'husnulmub@gmail.com', NULL),
(48, 13, '2023-03-14 23:03:55', NULL, 'husnulmub@gmail.com', NULL),
(48, 14, '2023-03-14 23:03:50', NULL, 'husnulmub@gmail.com', NULL),
(48, 15, '2023-03-14 23:03:38', NULL, 'husnulmub@gmail.com', NULL),
(48, 16, '2023-03-23 00:03:38', NULL, 'husnulmub@gmail.com', NULL),
(48, 17, '2023-03-23 00:03:41', NULL, 'husnulmub@gmail.com', NULL),
(48, 19, '2023-06-06 10:06:21', NULL, 'husnulmub@gmail.com', NULL),
(48, 22, '2023-07-13 00:07:51', NULL, 'fransiskusaditya88@gmail.com', NULL),
(48, 23, '2023-06-06 23:06:34', NULL, 'husnulmub@gmail.com', NULL),
(48, 24, '2023-06-06 19:06:24', NULL, 'husnulmub@gmail.com', NULL),
(48, 28, '2023-07-17 20:07:36', NULL, 'fransiskusaditya88@gmail.com', NULL),
(48, 36, '2023-06-07 02:06:40', NULL, 'husnulmub@gmail.com', NULL),
(48, 37, '2023-06-07 00:06:02', NULL, 'husnulmub@gmail.com', NULL),
(48, 38, '2023-06-07 00:06:18', NULL, 'husnulmub@gmail.com', NULL),
(48, 39, '2023-06-07 00:06:28', NULL, 'husnulmub@gmail.com', NULL),
(48, 40, '2023-06-07 00:06:02', NULL, 'husnulmub@gmail.com', NULL),
(48, 41, '2023-06-07 00:06:22', NULL, 'husnulmub@gmail.com', NULL),
(48, 43, '2023-06-07 02:06:53', NULL, 'husnulmub@gmail.com', NULL),
(48, 44, '2023-06-07 00:06:24', NULL, 'husnulmub@gmail.com', NULL),
(48, 45, '2023-06-07 00:06:51', NULL, 'husnulmub@gmail.com', NULL),
(48, 48, '2023-06-07 23:06:27', NULL, 'husnulmub@gmail.com', NULL),
(48, 49, '2023-08-09 19:08:54', NULL, 'fransiskusaditya88@gmail.com', NULL),
(48, 50, '2023-07-13 00:07:50', NULL, 'fransiskusaditya88@gmail.com', NULL),
(48, 57, '2023-08-13 18:08:24', NULL, 'fransiskusaditya88@gmail.com', NULL),
(49, 1, '2022-11-06 21:11:26', NULL, 'husnulmub@gmail.com', NULL),
(49, 11, '2023-03-14 23:03:06', NULL, 'husnulmub@gmail.com', NULL),
(49, 13, '2023-03-14 23:03:58', NULL, 'husnulmub@gmail.com', NULL),
(49, 14, '2023-03-14 23:03:52', NULL, 'husnulmub@gmail.com', NULL),
(49, 15, '2023-03-14 23:03:46', NULL, 'husnulmub@gmail.com', NULL),
(49, 16, '2023-03-23 00:03:40', NULL, 'husnulmub@gmail.com', NULL),
(49, 17, '2023-03-23 00:03:19', NULL, 'husnulmub@gmail.com', NULL),
(49, 19, '2023-06-06 10:06:24', NULL, 'husnulmub@gmail.com', NULL),
(49, 22, '2023-07-13 00:07:04', NULL, 'fransiskusaditya88@gmail.com', NULL),
(49, 23, '2023-06-06 23:06:47', NULL, 'husnulmub@gmail.com', NULL),
(49, 36, '2023-06-07 02:06:42', NULL, 'husnulmub@gmail.com', NULL),
(49, 37, '2023-06-07 00:06:04', NULL, 'husnulmub@gmail.com', NULL),
(49, 38, '2023-06-07 00:06:20', NULL, 'husnulmub@gmail.com', NULL),
(49, 40, '2023-06-07 00:06:04', NULL, 'husnulmub@gmail.com', NULL),
(49, 44, '2023-06-07 00:06:26', NULL, 'husnulmub@gmail.com', NULL),
(49, 45, '2023-06-07 00:06:57', NULL, 'husnulmub@gmail.com', NULL),
(49, 48, '2023-06-07 23:06:29', NULL, 'husnulmub@gmail.com', NULL),
(49, 49, '2023-08-09 19:08:00', NULL, 'fransiskusaditya88@gmail.com', NULL),
(49, 50, '2023-07-13 00:07:54', NULL, 'fransiskusaditya88@gmail.com', NULL),
(49, 57, '2023-08-13 18:08:26', NULL, 'fransiskusaditya88@gmail.com', NULL),
(50, 11, '2023-03-14 23:03:09', NULL, 'husnulmub@gmail.com', NULL),
(50, 13, '2023-03-14 23:03:02', NULL, 'husnulmub@gmail.com', NULL),
(50, 14, '2023-03-14 23:03:56', NULL, 'husnulmub@gmail.com', NULL),
(50, 15, '2023-03-14 23:03:48', NULL, 'husnulmub@gmail.com', NULL),
(50, 16, '2023-03-23 00:03:43', NULL, 'husnulmub@gmail.com', NULL),
(50, 17, '2023-03-23 00:03:21', NULL, 'husnulmub@gmail.com', NULL),
(50, 22, '2023-06-07 01:06:28', NULL, 'husnulmub@gmail.com', NULL),
(50, 36, '2023-06-07 02:06:46', NULL, 'husnulmub@gmail.com', NULL),
(50, 37, '2023-06-07 00:06:07', NULL, 'husnulmub@gmail.com', NULL),
(50, 38, '2023-06-07 00:06:23', NULL, 'husnulmub@gmail.com', NULL),
(50, 40, '2023-06-07 00:06:07', NULL, 'husnulmub@gmail.com', NULL),
(50, 41, '2023-06-07 01:06:03', NULL, 'husnulmub@gmail.com', NULL),
(50, 44, '2023-06-07 00:06:28', NULL, 'husnulmub@gmail.com', NULL),
(50, 45, '2023-06-07 00:06:01', NULL, 'husnulmub@gmail.com', NULL),
(50, 48, '2023-06-07 23:06:31', NULL, 'husnulmub@gmail.com', NULL),
(50, 50, '2023-07-13 00:07:58', NULL, 'fransiskusaditya88@gmail.com', NULL),
(51, 1, '2022-11-06 21:11:31', NULL, 'husnulmub@gmail.com', NULL),
(51, 11, '2023-03-14 23:03:15', NULL, 'husnulmub@gmail.com', NULL),
(51, 13, '2023-03-14 23:03:06', NULL, 'husnulmub@gmail.com', NULL),
(51, 14, '2023-03-14 23:03:59', NULL, 'husnulmub@gmail.com', NULL),
(51, 15, '2023-03-14 23:03:50', NULL, 'husnulmub@gmail.com', NULL),
(51, 16, '2023-03-23 00:03:45', NULL, 'husnulmub@gmail.com', NULL),
(51, 17, '2023-03-23 00:03:24', NULL, 'husnulmub@gmail.com', NULL),
(51, 19, '2023-06-06 10:06:56', NULL, 'husnulmub@gmail.com', NULL),
(51, 22, '2023-07-13 00:07:07', NULL, 'fransiskusaditya88@gmail.com', NULL),
(51, 36, '2023-06-07 02:06:48', NULL, 'husnulmub@gmail.com', NULL),
(51, 37, '2023-06-07 00:06:10', NULL, 'husnulmub@gmail.com', NULL),
(51, 38, '2023-06-07 00:06:25', NULL, 'husnulmub@gmail.com', NULL),
(51, 39, '2023-06-07 00:06:54', NULL, 'husnulmub@gmail.com', NULL),
(51, 40, '2023-06-07 00:06:10', NULL, 'husnulmub@gmail.com', NULL),
(51, 41, '2023-06-07 00:06:35', NULL, 'husnulmub@gmail.com', NULL),
(51, 44, '2023-06-07 00:06:29', NULL, 'husnulmub@gmail.com', NULL),
(51, 45, '2023-06-07 00:06:04', NULL, 'husnulmub@gmail.com', NULL),
(51, 48, '2023-06-07 23:06:32', NULL, 'husnulmub@gmail.com', NULL),
(51, 49, '2023-08-09 19:08:32', NULL, 'fransiskusaditya88@gmail.com', NULL),
(51, 50, '2023-07-13 01:07:24', NULL, 'fransiskusaditya88@gmail.com', NULL),
(52, 1, '2022-11-06 21:11:34', NULL, 'husnulmub@gmail.com', NULL),
(52, 11, '2023-03-14 23:03:20', NULL, 'husnulmub@gmail.com', NULL),
(52, 13, '2023-03-14 23:03:09', NULL, 'husnulmub@gmail.com', NULL),
(52, 14, '2023-03-14 23:03:02', NULL, 'husnulmub@gmail.com', NULL),
(52, 15, '2023-03-14 23:03:53', NULL, 'husnulmub@gmail.com', NULL),
(52, 16, '2023-03-23 00:03:48', NULL, 'husnulmub@gmail.com', NULL),
(52, 17, '2023-03-23 00:03:27', NULL, 'husnulmub@gmail.com', NULL),
(52, 19, '2023-06-06 10:06:04', NULL, 'husnulmub@gmail.com', NULL),
(52, 22, '2023-07-13 00:07:11', NULL, 'fransiskusaditya88@gmail.com', NULL),
(52, 36, '2023-06-07 02:06:50', NULL, 'husnulmub@gmail.com', NULL),
(52, 37, '2023-06-07 00:06:14', NULL, 'husnulmub@gmail.com', NULL),
(52, 38, '2023-06-07 00:06:27', NULL, 'husnulmub@gmail.com', NULL),
(52, 40, '2023-06-07 00:06:12', NULL, 'husnulmub@gmail.com', NULL),
(52, 44, '2023-06-07 00:06:31', NULL, 'husnulmub@gmail.com', NULL),
(52, 45, '2023-06-07 00:06:07', NULL, 'husnulmub@gmail.com', NULL),
(52, 48, '2023-06-07 23:06:34', NULL, 'husnulmub@gmail.com', NULL),
(52, 49, '2023-08-09 19:08:38', NULL, 'fransiskusaditya88@gmail.com', NULL),
(52, 50, '2023-07-13 01:07:02', NULL, 'fransiskusaditya88@gmail.com', NULL),
(53, 1, '2022-11-07 00:11:57', NULL, 'husnulmub@gmail.com', NULL),
(53, 11, '2023-03-14 23:03:24', NULL, 'husnulmub@gmail.com', NULL),
(53, 13, '2023-03-14 23:03:12', NULL, 'husnulmub@gmail.com', NULL),
(53, 14, '2023-03-14 23:03:05', NULL, 'husnulmub@gmail.com', NULL),
(53, 15, '2023-03-14 23:03:55', NULL, 'husnulmub@gmail.com', NULL),
(53, 16, '2023-03-23 00:03:50', NULL, 'husnulmub@gmail.com', NULL),
(53, 17, '2023-03-23 00:03:29', NULL, 'husnulmub@gmail.com', NULL),
(53, 19, '2023-06-06 10:06:37', NULL, 'husnulmub@gmail.com', NULL),
(53, 22, '2023-07-13 00:07:14', NULL, 'fransiskusaditya88@gmail.com', NULL),
(53, 36, '2023-06-07 02:06:52', NULL, 'husnulmub@gmail.com', NULL),
(53, 37, '2023-06-07 00:06:16', NULL, 'husnulmub@gmail.com', NULL),
(53, 38, '2023-06-07 00:06:30', NULL, 'husnulmub@gmail.com', NULL),
(53, 39, '2023-06-07 00:06:58', NULL, 'husnulmub@gmail.com', NULL),
(53, 40, '2023-06-07 00:06:15', NULL, 'husnulmub@gmail.com', NULL),
(53, 41, '2023-06-07 00:06:44', NULL, 'husnulmub@gmail.com', NULL),
(53, 44, '2023-06-07 00:06:32', NULL, 'husnulmub@gmail.com', NULL),
(53, 45, '2023-06-07 00:06:09', NULL, 'husnulmub@gmail.com', NULL),
(53, 48, '2023-06-07 23:06:35', NULL, 'husnulmub@gmail.com', NULL),
(53, 49, '2023-08-09 19:08:25', NULL, 'fransiskusaditya88@gmail.com', NULL),
(53, 50, '2023-07-13 01:07:28', NULL, 'fransiskusaditya88@gmail.com', NULL),
(53, 57, '2023-08-13 18:08:32', NULL, 'fransiskusaditya88@gmail.com', NULL),
(55, 15, '2023-03-14 23:03:27', NULL, 'husnulmub@gmail.com', NULL),
(55, 17, '2023-03-23 00:03:43', NULL, 'husnulmub@gmail.com', NULL),
(55, 23, '2023-06-06 23:06:21', NULL, 'husnulmub@gmail.com', NULL),
(55, 24, '2023-06-06 19:06:38', NULL, 'husnulmub@gmail.com', NULL),
(55, 39, '2023-06-07 00:06:32', NULL, 'husnulmub@gmail.com', NULL),
(56, 15, '2023-03-27 19:03:10', NULL, 'husnulmub@gmail.com', NULL),
(56, 17, '2023-03-23 00:03:39', NULL, 'husnulmub@gmail.com', NULL),
(56, 23, '2023-06-06 23:06:30', NULL, 'husnulmub@gmail.com', NULL),
(56, 24, '2023-06-06 19:06:31', NULL, 'husnulmub@gmail.com', NULL),
(60, 11, '2023-03-14 23:03:31', NULL, 'husnulmub@gmail.com', NULL),
(60, 13, '2023-03-14 23:03:24', NULL, 'husnulmub@gmail.com', NULL),
(60, 14, '2023-03-14 23:03:10', NULL, 'husnulmub@gmail.com', NULL),
(60, 15, '2023-03-14 23:03:00', NULL, 'husnulmub@gmail.com', NULL),
(60, 16, '2023-03-23 00:03:55', NULL, 'husnulmub@gmail.com', NULL),
(60, 17, '2023-03-23 00:03:35', NULL, 'husnulmub@gmail.com', NULL),
(60, 19, '2023-06-06 10:06:47', NULL, 'husnulmub@gmail.com', NULL),
(60, 22, '2023-07-13 00:07:18', NULL, 'fransiskusaditya88@gmail.com', NULL),
(60, 36, '2023-06-07 02:06:56', NULL, 'husnulmub@gmail.com', NULL),
(60, 37, '2023-06-07 00:06:20', NULL, 'husnulmub@gmail.com', NULL),
(60, 38, '2023-06-07 00:06:36', NULL, 'husnulmub@gmail.com', NULL),
(60, 40, '2023-06-07 00:06:22', NULL, 'husnulmub@gmail.com', NULL),
(60, 44, '2023-06-07 00:06:35', NULL, 'husnulmub@gmail.com', NULL),
(60, 45, '2023-06-07 00:06:14', NULL, 'husnulmub@gmail.com', NULL),
(60, 48, '2023-06-07 23:06:39', NULL, 'husnulmub@gmail.com', NULL),
(60, 50, '2023-07-13 01:07:32', NULL, 'fransiskusaditya88@gmail.com', NULL),
(64, 1, '2023-03-28 10:03:06', NULL, 'husnulmub@gmail.com', NULL),
(64, 11, '2023-03-28 10:03:24', NULL, 'husnulmub@gmail.com', NULL),
(64, 19, '2023-06-06 10:06:43', NULL, 'husnulmub@gmail.com', NULL),
(64, 22, '2023-07-13 00:07:22', NULL, 'fransiskusaditya88@gmail.com', NULL),
(64, 36, '2023-06-07 02:06:58', NULL, 'husnulmub@gmail.com', NULL),
(64, 37, '2023-06-07 00:06:22', NULL, 'husnulmub@gmail.com', NULL),
(64, 38, '2023-06-07 00:06:39', NULL, 'husnulmub@gmail.com', NULL),
(64, 39, '2023-06-07 00:06:01', NULL, 'husnulmub@gmail.com', NULL),
(64, 40, '2023-06-07 00:06:29', NULL, 'husnulmub@gmail.com', NULL),
(64, 44, '2023-06-07 00:06:37', NULL, 'husnulmub@gmail.com', NULL),
(64, 45, '2023-06-07 00:06:16', NULL, 'husnulmub@gmail.com', NULL),
(64, 48, '2023-06-07 23:06:41', NULL, 'husnulmub@gmail.com', NULL),
(64, 50, '2023-07-13 01:07:36', NULL, 'fransiskusaditya88@gmail.com', NULL),
(66, 19, '2023-06-06 10:06:00', NULL, 'husnulmub@gmail.com', NULL),
(66, 23, '2023-06-06 23:06:50', NULL, 'husnulmub@gmail.com', NULL),
(66, 24, '2023-06-06 19:06:15', NULL, 'husnulmub@gmail.com', NULL),
(66, 36, '2023-06-07 02:06:51', NULL, 'husnulmub@gmail.com', NULL),
(66, 37, '2023-06-07 02:06:09', NULL, 'husnulmub@gmail.com', NULL),
(66, 38, '2023-06-07 02:06:53', NULL, 'husnulmub@gmail.com', NULL),
(66, 39, '2023-06-07 00:06:21', NULL, 'husnulmub@gmail.com', NULL),
(66, 40, '2023-06-07 02:06:56', NULL, 'husnulmub@gmail.com', NULL),
(66, 41, '2023-06-07 02:06:25', NULL, 'husnulmub@gmail.com', NULL),
(66, 42, '2023-06-07 02:06:37', NULL, 'husnulmub@gmail.com', NULL),
(66, 43, '2023-06-07 02:06:25', NULL, 'husnulmub@gmail.com', NULL),
(66, 44, '2023-06-07 01:06:58', NULL, 'husnulmub@gmail.com', NULL),
(66, 45, '2023-06-07 01:06:31', NULL, 'husnulmub@gmail.com', NULL),
(66, 46, '2023-06-07 01:06:15', NULL, 'husnulmub@gmail.com', NULL),
(66, 47, '2023-06-07 01:06:58', NULL, 'husnulmub@gmail.com', NULL),
(66, 58, '2023-09-15 01:09:58', NULL, 'fransiskusaditya88@gmail.com', NULL),
(67, 19, '2023-06-06 10:06:03', NULL, 'husnulmub@gmail.com', NULL),
(67, 23, '2023-06-06 23:06:40', NULL, 'husnulmub@gmail.com', NULL),
(67, 24, '2023-06-06 19:06:19', NULL, 'husnulmub@gmail.com', NULL),
(67, 36, '2023-06-07 02:06:53', NULL, 'husnulmub@gmail.com', NULL),
(67, 37, '2023-06-07 02:06:11', NULL, 'husnulmub@gmail.com', NULL),
(67, 38, '2023-06-07 02:06:55', NULL, 'husnulmub@gmail.com', NULL),
(67, 44, '2023-06-07 02:06:01', NULL, 'husnulmub@gmail.com', NULL),
(67, 45, '2023-06-07 01:06:33', NULL, 'husnulmub@gmail.com', NULL),
(70, 1, '2023-06-12 09:06:33', NULL, 'husnulmub@gmail.com', NULL),
(74, 1, '2023-07-01 09:07:50', NULL, 'fransiskusaditya88@gmail.com', NULL),
(74, 22, '2023-07-13 00:07:31', NULL, 'fransiskusaditya88@gmail.com', NULL),
(74, 49, '2023-08-09 19:08:12', NULL, 'fransiskusaditya88@gmail.com', NULL),
(74, 50, '2023-07-13 01:07:45', NULL, 'fransiskusaditya88@gmail.com', NULL),
(74, 57, '2023-08-13 18:08:38', NULL, 'fransiskusaditya88@gmail.com', NULL),
(75, 1, '2023-07-10 07:07:20', NULL, 'fransiskusaditya88@gmail.com', NULL),
(75, 22, '2023-07-13 00:07:34', NULL, 'fransiskusaditya88@gmail.com', NULL),
(75, 50, '2023-07-13 01:07:49', NULL, 'fransiskusaditya88@gmail.com', NULL),
(78, 45, '2023-08-01 18:08:32', NULL, 'fransiskusaditya88@gmail.com', NULL),
(78, 55, '2023-08-01 17:08:00', NULL, 'fransiskusaditya88@gmail.com', NULL),
(79, 45, '2023-08-01 18:08:34', NULL, 'fransiskusaditya88@gmail.com', NULL),
(79, 56, '2023-08-01 17:08:45', NULL, 'fransiskusaditya88@gmail.com', NULL),
(81, 1, '2023-10-31 21:11:44', NULL, 'fransiskusaditya88@gmail.com', NULL),
(82, 1, '2023-10-31 21:11:47', NULL, 'fransiskusaditya88@gmail.com', NULL),
(85, 1, '2024-03-04 06:03:52', NULL, 'husnulmub@gmail.com', NULL),
(86, 1, '2024-03-04 19:03:45', NULL, 'husnulmub@gmail.com', NULL),
(87, 1, '2024-03-05 07:03:21', NULL, 'husnulmub@gmail.com', NULL),
(88, 1, '2024-03-18 08:03:32', NULL, 'husnulmub@gmail.com', NULL),
(89, 1, '2024-03-19 08:03:31', NULL, 'husnulmub@gmail.com', NULL),
(90, 1, '2024-03-25 20:03:21', NULL, 'husnulmub@gmail.com', NULL),
(91, 1, '2024-03-25 20:03:24', NULL, 'husnulmub@gmail.com', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `menus`
--

CREATE TABLE `menus` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `route` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `menugroup` int DEFAULT NULL,
  `menu_idx` int DEFAULT NULL,
  `icon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `createdby` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `updatedby` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `menus`
--

INSERT INTO `menus` (`id`, `name`, `route`, `menugroup`, `menu_idx`, `icon`, `created_at`, `updated_at`, `createdby`, `updatedby`) VALUES
(1, 'Approval Workflow', 'config/workflow', 2, 4, 'workflow.png', '2022-07-26 02:12:52', NULL, 'sys-admin', 'husnulmub@gmail.com'),
(2, 'Item Master', 'master/item', 1, 1, 'DB.png', '2022-07-26 02:12:52', NULL, 'sys-admin', 'husnulmub@gmail.com'),
(4, 'Users', 'config/users', 2, 1, 'UM01.png', '2022-07-26 02:12:52', NULL, 'sys-admin', ''),
(5, 'Roles', 'config/roles', 2, 3, 'MF06.png', '2022-07-26 02:12:52', NULL, 'sys-admin', ''),
(7, 'Menus', 'config/menus', 2, 2, 'CMDOPT.png', '2022-07-26 02:12:52', NULL, 'sys-admin', 'husnulmub@gmail.com'),
(20, 'General Setting', 'general/setting', 2, 5, 'setting.png', '2022-08-17 14:08:21', NULL, 'husnulmub@gmail.com', NULL),
(25, 'Vendor', 'master/vendor', 1, 2, 'DB.png', '2022-09-13 15:09:55', NULL, 'husnulmub@gmail.com', 'husnulmub@gmail.com'),
(26, 'Object Authorization', 'config/objectauth', 2, 6, 'CMDOPT.png', '2022-09-20 06:09:00', NULL, 'husnulmub@gmail.com', 'husnulmub@gmail.com'),
(29, 'Penerimaan PO', 'logistic/terimapo', 6, 5, 'IM1B.png', '2022-10-01 22:10:33', NULL, 'husnulmub@gmail.com', NULL),
(30, 'Pengeluaran Barang', 'logistic/pengeluaran', 6, 6, 'IM1A.png', '2022-10-01 22:10:33', NULL, 'husnulmub@gmail.com', NULL),
(31, 'Purchase Request', 'proc/pr', 12, 1, 'prlist.png', '2022-10-03 23:10:01', NULL, 'husnulmub@gmail.com', 'husnulmub@gmail.com'),
(32, 'Purchase Order', 'proc/po', 3, 2, 'polist.png', '2022-10-03 23:10:01', NULL, 'husnulmub@gmail.com', 'husnulmub@gmail.com'),
(35, 'Approve PR', 'approve/pr', 7, 2, 'approve.png', '2022-10-04 00:10:56', NULL, 'husnulmub@gmail.com', NULL),
(36, 'Approve PO', 'approve/po', 7, 3, 'approve.png', '2022-10-04 00:10:56', NULL, 'husnulmub@gmail.com', NULL),
(44, 'Master Gudang', 'master/warehouse', 1, 6, 'LOG.png', '2022-10-25 20:10:05', NULL, 'husnulmub@gmail.com', NULL),
(48, 'Report PR', 'report/pr', 9, 3, 'Report.png', '2022-11-06 21:11:42', NULL, 'husnulmub@gmail.com', NULL),
(49, 'Report PO', 'report/po', 9, 4, 'Report.png', '2022-11-06 21:11:57', NULL, 'husnulmub@gmail.com', NULL),
(50, 'Report WO', 'report/wo', 9, 5, 'Report.png', '2022-11-06 21:11:57', NULL, 'husnulmub@gmail.com', NULL),
(51, 'Penerimaan PO', 'report/grpo', 9, 6, 'Report.png', '2022-11-06 21:11:57', NULL, 'husnulmub@gmail.com', NULL),
(52, 'Pengeluaran', 'report/issue', 9, 7, 'Report.png', '2022-11-06 21:11:57', NULL, 'husnulmub@gmail.com', NULL),
(53, 'Stock', 'report/stock', 9, 8, 'Manuf.png', '2022-11-07 00:11:23', NULL, 'husnulmub@gmail.com', NULL),
(55, 'Print PR', 'printdoc/pr', 10, 2, 'Print.png', '2022-11-07 23:11:19', NULL, 'husnulmub@gmail.com', NULL),
(56, 'Print PO', 'printdoc/po', 10, 3, 'Print.png', '2022-11-07 23:11:19', NULL, 'husnulmub@gmail.com', NULL),
(58, 'Print Receipt PO', 'printdoc/grpo', 10, 9, 'Print.png', '2022-11-07 23:11:19', NULL, 'husnulmub@gmail.com', 'husnulmub@gmail.com'),
(60, 'Summary Budget', 'report/budgetsummary', 9, 10, 'MF06.png', '2023-01-29 06:01:59', NULL, 'husnulmub@gmail.com', NULL),
(64, 'Batch Stock', 'report/batchstock', 9, 11, 'LOG.png', '2023-03-28 10:03:46', NULL, 'husnulmub@gmail.com', NULL),
(66, 'Due Date PR', 'proc/pr/duedatepr', 12, 3, 'IM21.png', '2023-04-09 00:04:58', NULL, 'husnulmub@gmail.com', 'husnulmub@gmail.com'),
(67, 'Due Date PO', 'proc/po/duedatepo', 3, 4, 'IM21.png', '2023-04-09 00:04:58', NULL, 'husnulmub@gmail.com', NULL),
(70, 'Master Project', 'master/project', 1, 9, 'Manuf.png', '2023-06-12 09:06:19', NULL, 'husnulmub@gmail.com', NULL),
(74, 'Cost Per Project', 'report/cost02', 9, 13, 'Report.png', '2023-07-01 09:07:34', NULL, 'fransiskusaditya88@gmail.com', NULL),
(75, 'Detail Cost', 'report/cost03', 9, 13, 'Report.png', '2023-07-10 07:07:07', NULL, 'fransiskusaditya88@gmail.com', NULL),
(78, 'Reset Approval PR', 'cancel/approve/pr', 7, 7, 'wf.png', '2023-08-01 17:08:12', NULL, 'fransiskusaditya88@gmail.com', NULL),
(79, 'Reset Approval PO', 'cancel/approve/po', 7, 8, 'wf.png', '2023-08-01 17:08:12', NULL, 'fransiskusaditya88@gmail.com', NULL),
(81, 'Transfer Barang', 'logistic/transfer', 6, 8, 'IM1A.png', '2023-10-31 21:11:32', NULL, 'fransiskusaditya88@gmail.com', NULL),
(82, 'Transfer Barang', 'report/transfer', 9, 15, 'IM1B.png', '2023-10-31 21:11:32', NULL, 'fransiskusaditya88@gmail.com', NULL),
(85, 'Cost Master', 'master/costmaster', 1, 6, '21.png', '2024-03-04 06:03:33', NULL, 'husnulmub@gmail.com', NULL),
(86, 'Purchase Request List', 'proc/pr/list', 12, 2, 'purchase_order.png', '2024-03-04 19:03:52', NULL, 'husnulmub@gmail.com', NULL),
(87, 'Purchase Order List', 'po/list', 3, 3, 'purchase_order.png', '2024-03-05 07:03:04', NULL, 'husnulmub@gmail.com', 'husnulmub@gmail.com'),
(88, 'Master Approval', 'master/approval', 1, 7, 'UM01.png', '2024-03-18 08:03:49', NULL, 'husnulmub@gmail.com', NULL),
(89, 'Master Jabatan', 'master/jabatan', 1, 7, '19.png', '2024-03-19 08:03:33', NULL, 'husnulmub@gmail.com', NULL),
(90, 'Create Quotation', 'quotation', 13, 1, 'MF01.png', '2024-03-25 20:03:13', NULL, 'husnulmub@gmail.com', NULL),
(91, 'Quotation List', 'quotation/list', 13, 2, '21.png', '2024-03-25 20:03:05', NULL, 'husnulmub@gmail.com', NULL);

--
-- Trigger `menus`
--
DELIMITER $$
CREATE TRIGGER `deleteMenuAssignment` AFTER DELETE ON `menus` FOR EACH ROW DELETE FROM menuroles WHERE menuid = OLD.id
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `setMenuDisIndex` BEFORE INSERT ON `menus` FOR EACH ROW set NEW.menu_idx = (SELECT count(menugroup)+1 from menus WHERE menugroup = NEW.menugroup)
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2022_06_27_031456_create_file_types_table', 1),
(5, '2022_06_27_035543_create_roles_table', 1),
(6, '2022_06_27_035921_create_menugroups_table', 1),
(7, '2022_06_27_035945_create_menus_table', 1),
(8, '2022_06_27_040346_create_menuroles_table', 1),
(9, '2022_06_27_040422_create_userroles_table', 1),
(10, '2022_06_27_041244_create_activities_table', 1),
(11, '2022_06_27_041402_update_activities_add_field_document_table', 1),
(12, '2022_06_27_041507_create_documents_table', 1),
(13, '2022_06_27_042159_create_document_attachments_table', 1),
(14, '2022_08_08_114517_create_jobs_table', 2);

-- --------------------------------------------------------

--
-- Struktur dari tabel `nriv_po`
--

CREATE TABLE `nriv_po` (
  `prefix` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `month` varchar(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `year` int NOT NULL,
  `lastnumber` int NOT NULL,
  `createdon` datetime NOT NULL,
  `createdby` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `nriv_po`
--

INSERT INTO `nriv_po` (`prefix`, `month`, `year`, `lastnumber`, `createdon`, `createdby`) VALUES
('2', '03', 24, 1, '2024-03-07 16:03:48', 'sys-admin'),
('AA', '03', 24, 6, '2024-03-07 16:03:22', 'sys-admin'),
('GM', '03', 24, 2, '2024-03-10 00:03:38', 'sys-admin'),
('SQ', '03', 24, 2, '2024-03-07 16:03:32', 'sys-admin');

-- --------------------------------------------------------

--
-- Struktur dari tabel `nriv_pr`
--

CREATE TABLE `nriv_pr` (
  `year` int NOT NULL,
  `month` varchar(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prtype` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prefix` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `current_number` int NOT NULL,
  `createdby` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `createdon` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `nriv_pr`
--

INSERT INTO `nriv_pr` (`year`, `month`, `prtype`, `prefix`, `current_number`, `createdby`, `createdon`) VALUES
(24, '03', 'AA', 'AA', 6, 'sys-admin', '2024-03-06 08:03:54'),
(24, '03', 'GM', 'GM', 3, 'sys-admin', '2024-03-05 16:03:12'),
(24, '03', 'SQ', 'SQ', 4, 'sys-admin', '2024-03-05 16:03:51');

-- --------------------------------------------------------

--
-- Struktur dari tabel `object_auth`
--

CREATE TABLE `object_auth` (
  `object_name` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `object_description` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `createdon` date NOT NULL,
  `createdby` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `object_auth`
--

INSERT INTO `object_auth` (`object_name`, `object_description`, `createdon`, `createdby`) VALUES
('ALLOW_DISPLAY_PROJECT', 'Allow Display Project', '2024-03-05', 'husnulmub@gmail.com'),
('ALLOW_DOWNLOAD_DOC', 'Allow user to download Document Attachment', '2022-08-15', 'sys-admin'),
('ALLOW_POTYPE', 'Authorized PO Type', '2024-03-16', 'husnulmub@gmail.com'),
('ALLOW_PRTYPE', 'Authorized PR Type', '2024-03-05', 'husnulmub@gmail.com'),
('ALLOW_WAREHOUSE', 'Authorized Warehouse', '2024-03-05', 'husnulmub@gmail.com');

-- --------------------------------------------------------

--
-- Struktur dari tabel `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `roles`
--

CREATE TABLE `roles` (
  `id` bigint UNSIGNED NOT NULL,
  `rolename` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `rolestatus` int NOT NULL,
  `createdby` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `updatedby` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `roles`
--

INSERT INTO `roles` (`id`, `rolename`, `rolestatus`, `createdby`, `updatedby`, `created_at`, `updated_at`) VALUES
(1, 'SYS-ADMIN', 1, 'sys-admin', '', '2022-01-26 02:45:03', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `t_attachments`
--

CREATE TABLE `t_attachments` (
  `id` int NOT NULL,
  `doc_object` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `doc_number` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `efile` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `pathfile` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `createdby` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `createdon` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `t_attachments`
--

INSERT INTO `t_attachments` (`id`, `doc_object`, `doc_number`, `efile`, `pathfile`, `createdby`, `createdon`) VALUES
(1, 'PR', 'SPB/AA/03/2024/000002', 'MR8M Error.pdf', '/files/PR/MR8M Error.pdf', 'sys-admin', '2024-03-05 15:04:01'),
(2, 'PR', 'SPB/AA/03/2024/000002', 'General-Ledger-Accounting.pdf', '/files/PR/General-Ledger-Accounting.pdf', 'sys-admin', '2024-03-05 15:16:32'),
(3, 'PO', 'KRS/PO/AA/03/24/000001', 'MR8M Error.pdf', '/files/PO/MR8M Error.pdf', 'sys-admin', '2024-03-07 23:17:22'),
(4, 'PO', 'KRS/PO/AA/03/24/000005', 'MR8M Error.pdf', '/files/PO/MR8M Error.pdf', 'sys-admin', '2024-03-10 07:53:03'),
(6, 'PO', 'KRS/PO/AA/03/24/000006', 'MR8M Error.pdf', '/files/PO/MR8M Error.pdf', 'sys-admin', '2024-03-10 13:55:52'),
(7, 'PO', 'KRS/PO/AA/03/24/000001', 'Attachment1.pdf', '/files/PO/Attachment1.pdf', 'sys-admin', '2024-03-10 14:52:15'),
(8, 'PR', 'SPB/SQ/03/24/000002', 'Attachment2.pdf', '/files/PR/Attachment2.pdf', 'sys-admin', '2024-03-11 11:08:14'),
(9, 'PO', 'KRS/PO/AA/03/24/000006', 'Attachment1.pdf', '/files/PO/Attachment1.pdf', 'sys-admin', '2024-03-11 11:09:23');

-- --------------------------------------------------------

--
-- Struktur dari tabel `t_cost_code_master`
--

CREATE TABLE `t_cost_code_master` (
  `id` int NOT NULL,
  `cost_code` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cost_desc` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cost_group` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deleted` varchar(1) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'N',
  `createdon` datetime NOT NULL,
  `createdby` varchar(70) COLLATE utf8mb4_unicode_ci NOT NULL,
  `changedon` datetime DEFAULT NULL,
  `changedby` varchar(70) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `t_cost_code_master`
--

INSERT INTO `t_cost_code_master` (`id`, `cost_code`, `cost_desc`, `cost_group`, `deleted`, `createdon`, `createdby`, `changedon`, `changedby`) VALUES
(1, '1100', 'PMT & Supervisory Staff', '1000', 'N', '2024-03-04 14:35:12', 'sys-admin', '2024-03-04 14:35:12', ''),
(2, '1300', 'Indirect Worker', '1000', 'N', '2024-03-04 14:35:12', 'sys-admin', '2024-03-04 14:35:12', ''),
(3, '1500', 'Direct Worker', '1000', 'N', '2024-03-04 20:55:38', 'sys-admin', NULL, NULL),
(4, '2101', 'Cement', '2000', 'N', '2024-03-04 20:55:38', 'sys-admin', '2024-03-04 21:28:49', 'sys-admin');

-- --------------------------------------------------------

--
-- Struktur dari tabel `t_cost_group`
--

CREATE TABLE `t_cost_group` (
  `id` int NOT NULL,
  `cost_group` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cost_group_desc` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `createdon` datetime NOT NULL,
  `createdby` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `t_cost_group`
--

INSERT INTO `t_cost_group` (`id`, `cost_group`, `cost_group_desc`, `createdon`, `createdby`) VALUES
(1, '1000', 'SALARY', '2024-03-04 14:09:17', 'sys-admin'),
(2, '2000', 'MATERIAL & BULK MATERIAL', '2024-03-04 14:09:17', 'sys-admin'),
(3, '3000', 'TRANSPORT & EQUIPMENT', '2024-03-04 21:06:17', 'sys-admin'),
(4, '4000', 'TEMPORARY FACILITIES & EQUIPMENT', '2024-03-04 21:06:17', 'sys-admin'),
(5, '5000', 'CONSUMBALE', '2024-03-04 21:06:17', 'sys-admin'),
(6, '6000', 'SUBCONTRACTOR', '2024-03-04 21:06:17', 'sys-admin'),
(7, '7000', 'RUNNING COST', '2024-03-04 21:06:17', 'sys-admin'),
(8, '8000', 'BOND & INSURANCE', '2024-03-04 21:06:17', 'sys-admin'),
(9, '9000', 'SPARE', '2024-03-04 21:06:17', 'sys-admin');

-- --------------------------------------------------------

--
-- Struktur dari tabel `t_department`
--

CREATE TABLE `t_department` (
  `deptid` int NOT NULL,
  `department` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `createdon` date NOT NULL,
  `createdby` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `t_inv01`
--

CREATE TABLE `t_inv01` (
  `id` int NOT NULL,
  `docnum` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `docyear` int NOT NULL,
  `docdate` date NOT NULL,
  `postdate` date NOT NULL,
  `received_by` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `movement_code` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `remark` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `createdby` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `createdon` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `t_inv01`
--

INSERT INTO `t_inv01` (`id`, `docnum`, `docyear`, `docdate`, `postdate`, `received_by`, `movement_code`, `remark`, `createdby`, `createdon`) VALUES
(1, 'RPO/2024/03000001', 2024, '2024-03-11', '2024-03-11', 'Administrator', '101', 'Testing Terima PO', 'husnulmub@gmail.com', '2024-03-11 14:38:37'),
(2, 'RPO/2024/03000002', 2024, '2024-03-11', '2024-03-11', 'Administrator', '101', 'Test', 'husnulmub@gmail.com', '2024-03-11 14:51:21'),
(3, 'RPO/2024/03000003', 2024, '2024-03-14', '2024-03-14', 'Administrator', '101', 'terima PO', 'husnulmub@gmail.com', '2024-03-14 16:04:11');

-- --------------------------------------------------------

--
-- Struktur dari tabel `t_inv02`
--

CREATE TABLE `t_inv02` (
  `id` int NOT NULL,
  `docnum` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `docyear` int NOT NULL,
  `docitem` int NOT NULL,
  `movement_code` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `material` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `matdesc` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quantity` decimal(15,3) NOT NULL,
  `unit` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `unit_price` double NOT NULL,
  `total_price` double NOT NULL,
  `ponum` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `poitem` int DEFAULT NULL,
  `wonum` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `woitem` int DEFAULT NULL,
  `pbjnumber` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pbjitem` int DEFAULT NULL,
  `whscode` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `whscode2` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remark` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `shkzg` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `cancelled` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'N',
  `createdby` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `createdon` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `t_inv02`
--

INSERT INTO `t_inv02` (`id`, `docnum`, `docyear`, `docitem`, `movement_code`, `material`, `matdesc`, `batch_number`, `quantity`, `unit`, `unit_price`, `total_price`, `ponum`, `poitem`, `wonum`, `woitem`, `pbjnumber`, `pbjitem`, `whscode`, `whscode2`, `remark`, `shkzg`, `cancelled`, `createdby`, `createdon`) VALUES
(1, 'RPO/2024/03000001', 2024, 1, '101', '100001', 'Gas Oxygen', '202403000001', '1.000', 'Btl', 100000, 100000, 'KRS/PO/AA/03/24/000001', 1, NULL, NULL, NULL, NULL, '1', NULL, NULL, '+', 'N', 'husnulmub@gmail.com', '2024-03-11 14:38:37'),
(2, 'RPO/2024/03000001', 2024, 2, '101', '100002', 'Gas Argon', '202403000002', '2.000', 'Btl', 5000000, 10000000, 'KRS/PO/AA/03/24/000001', 2, NULL, NULL, NULL, NULL, '1', NULL, NULL, '+', 'N', 'husnulmub@gmail.com', '2024-03-11 14:38:37'),
(3, 'RPO/2024/03000001', 2024, 3, '101', '100006', 'Ballpoint Snowman V7', '202403000003', '5.000', 'Ktk', 3500, 17500, 'KRS/PO/AA/03/24/000001', 3, NULL, NULL, NULL, NULL, '1', NULL, NULL, '+', 'N', 'husnulmub@gmail.com', '2024-03-11 14:38:37'),
(4, 'RPO/2024/03000001', 2024, 4, '101', '100007', 'Materai Tempel', '202403000004', '7.000', 'Ea', 10000, 70000, 'KRS/PO/AA/03/24/000001', 4, NULL, NULL, NULL, NULL, '1', NULL, NULL, '+', 'N', 'husnulmub@gmail.com', '2024-03-11 14:38:37'),
(5, 'RPO/2024/03000002', 2024, 1, '101', '100008', 'Bi Di Pig Brush Magnet', '202403000005', '8.000', 'Ea', 15000, 120000, 'KRS/PO/AA/03/24/000001', 5, NULL, NULL, NULL, NULL, '2', NULL, NULL, '+', 'N', 'husnulmub@gmail.com', '2024-03-11 14:51:21'),
(6, 'RPO/2024/03000002', 2024, 2, '101', '100003', 'Bola Lampu Philips', '202403000006', '1.000', 'Ea', 35000, 35000, 'KRS/PO/AA/03/24/000006', 1, NULL, NULL, NULL, NULL, '1', NULL, NULL, '+', 'N', 'husnulmub@gmail.com', '2024-03-11 14:51:21'),
(7, 'RPO/2024/03000002', 2024, 3, '101', '100004', 'Wire Lock', '202403000007', '2.000', 'Set', 450000, 900000, 'KRS/PO/AA/03/24/000006', 2, NULL, NULL, NULL, NULL, '2', NULL, NULL, '+', 'N', 'husnulmub@gmail.com', '2024-03-11 14:51:21'),
(8, 'RPO/2024/03000003', 2024, 1, '101', '100001', 'Gas Oxygen', '202403000008', '3.000', 'Btl', 100000, 300000, 'KRS/PO/SQ/03/24/000002', 1, NULL, NULL, NULL, NULL, '2', NULL, NULL, '+', 'N', 'husnulmub@gmail.com', '2024-03-14 16:04:11'),
(9, 'RPO/2024/03000003', 2024, 2, '101', '100002', 'Gas Argon', '202403000009', '6.000', 'Btl', 5000000, 30000000, 'KRS/PO/SQ/03/24/000002', 2, NULL, NULL, NULL, NULL, '2', NULL, NULL, '+', 'N', 'husnulmub@gmail.com', '2024-03-14 16:04:11'),
(10, 'RPO/2024/03000003', 2024, 3, '101', '100003', 'Bola Lampu Philips', '2024030000010', '7.000', 'Ea', 35000, 245000, 'KRS/PO/SQ/03/24/000002', 3, NULL, NULL, NULL, NULL, '2', NULL, NULL, '+', 'N', 'husnulmub@gmail.com', '2024-03-14 16:04:11');

-- --------------------------------------------------------

--
-- Struktur dari tabel `t_inv_batch_stock`
--

CREATE TABLE `t_inv_batch_stock` (
  `material` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `whscode` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batchnum` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` decimal(18,3) NOT NULL,
  `unit` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_udpate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `t_inv_batch_stock`
--

INSERT INTO `t_inv_batch_stock` (`material`, `whscode`, `batchnum`, `quantity`, `unit`, `last_udpate`) VALUES
('100001', '1', '202403000001', '1.000', 'Btl', '2024-03-11 14:38:37'),
('100001', '2', '202403000008', '3.000', 'Btl', '2024-03-14 16:04:11'),
('100002', '1', '202403000002', '2.000', 'Btl', '2024-03-11 14:38:37'),
('100002', '2', '202403000009', '6.000', 'Btl', '2024-03-14 16:04:11'),
('100003', '1', '202403000006', '1.000', 'Ea', '2024-03-11 14:51:21'),
('100003', '2', '2024030000010', '7.000', 'Ea', '2024-03-14 16:04:11'),
('100004', '2', '202403000007', '2.000', 'Set', '2024-03-11 14:51:21'),
('100006', '1', '202403000003', '5.000', 'Ktk', '2024-03-11 14:38:37'),
('100007', '1', '202403000004', '7.000', 'Ea', '2024-03-11 14:38:37'),
('100008', '2', '202403000005', '8.000', 'Ea', '2024-03-11 14:51:21');

-- --------------------------------------------------------

--
-- Struktur dari tabel `t_inv_stock`
--

CREATE TABLE `t_inv_stock` (
  `material` varchar(70) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `whscode` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batchnum` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` decimal(18,3) NOT NULL,
  `unit` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_udpate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `t_inv_stock`
--

INSERT INTO `t_inv_stock` (`material`, `whscode`, `batchnum`, `quantity`, `unit`, `last_udpate`) VALUES
('100001', '1', '202403000001', '1.000', 'Btl', '2024-03-11 14:38:37'),
('100001', '2', '202403000008', '3.000', 'Btl', '2024-03-14 16:04:11'),
('100002', '1', '202403000002', '2.000', 'Btl', '2024-03-11 14:38:37'),
('100002', '2', '202403000009', '6.000', 'Btl', '2024-03-14 16:04:11'),
('100003', '1', '202403000006', '1.000', 'Ea', '2024-03-11 14:51:21'),
('100003', '2', '2024030000010', '7.000', 'Ea', '2024-03-14 16:04:11'),
('100004', '2', '202403000007', '2.000', 'Set', '2024-03-11 14:51:21'),
('100006', '1', '202403000003', '5.000', 'Ktk', '2024-03-11 14:38:37'),
('100007', '1', '202403000004', '7.000', 'Ea', '2024-03-11 14:38:37'),
('100008', '2', '202403000005', '8.000', 'Ea', '2024-03-11 14:51:21');

-- --------------------------------------------------------

--
-- Struktur dari tabel `t_jabatan`
--

CREATE TABLE `t_jabatan` (
  `id` int NOT NULL,
  `jabatan` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `createdon` date NOT NULL,
  `createdby` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `t_jabatan`
--

INSERT INTO `t_jabatan` (`id`, `jabatan`, `createdon`, `createdby`) VALUES
(1, 'Manager Proyek', '2024-03-19', 'husnulmub@gmail.com'),
(2, 'Manager Lapangan', '2024-03-19', 'husnulmub@gmail.com'),
(3, 'Ka. Teknik &amp;amp; Penged. Mutu', '2024-03-19', 'husnulmub@gmail.com'),
(5, 'Direktur', '2024-03-19', 'husnulmub@gmail.com'),
(6, 'Direktur Utama', '2024-03-19', 'husnulmub@gmail.com');

-- --------------------------------------------------------

--
-- Struktur dari tabel `t_master_approval`
--

CREATE TABLE `t_master_approval` (
  `id` int NOT NULL,
  `nama` varchar(70) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jabatan` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `createdby` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `createdon` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `t_master_approval`
--

INSERT INTO `t_master_approval` (`id`, `nama`, `jabatan`, `createdby`, `createdon`) VALUES
(1, 'J. Dion', 'Manager Proyek', 'sys-admin', '2024-03-18 22:46:55'),
(2, 'Rio Bagja', 'Manager Lapangan', 'sys-admin', '2024-03-18 22:46:55'),
(3, 'Yosep Mambela', 'Ka. Teknik & Penged.Mutu', 'sys-admin', '2024-03-18 22:46:55'),
(4, 'Yonas Darja, B.Sc, M.Eng', 'Direktur Utama', 'sys-admin', '2024-03-18 22:47:55'),
(5, 'Sutomo Ekarantio', 'Direktur', 'sys-admin', '2024-03-18 22:48:56'),
(6, 'Ir. Locky W', 'Project Coordinator', 'sys-admin', '2024-03-18 22:48:56'),
(7, 'Suhendra', 'Manager Pengadaan', 'sys-admin', '2024-03-18 22:48:56');

-- --------------------------------------------------------

--
-- Struktur dari tabel `t_material`
--

CREATE TABLE `t_material` (
  `id` int NOT NULL,
  `material` varchar(70) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `matdesc` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mattype` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `matgroup` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `matspec` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `partname` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `partnumber` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `color` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `size` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `matunit` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `minstock` decimal(15,2) DEFAULT NULL,
  `orderunit` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stdprice` decimal(15,2) DEFAULT NULL,
  `stdpriceusd` decimal(15,4) DEFAULT '0.0000',
  `last_purchase_price` decimal(18,2) NOT NULL DEFAULT '0.00',
  `active` tinyint(1) DEFAULT NULL,
  `matuniqid` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `createdon` datetime DEFAULT NULL,
  `createdby` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Material Master';

--
-- Dumping data untuk tabel `t_material`
--

INSERT INTO `t_material` (`id`, `material`, `matdesc`, `mattype`, `matgroup`, `matspec`, `partname`, `partnumber`, `color`, `size`, `matunit`, `minstock`, `orderunit`, `stdprice`, `stdpriceusd`, `last_purchase_price`, `active`, `matuniqid`, `createdon`, `createdby`) VALUES
(1, '100001', 'Gas Oxygen', '1', NULL, NULL, 'Gas Oxygen', '100001', NULL, NULL, 'Btl', NULL, NULL, NULL, '0.0000', '100000.00', NULL, '1709351514', '2024-03-02 03:03:54', 'husnulmub@gmail.com'),
(3, '100002', 'Gas Argon', '1', NULL, NULL, 'Gas Argon', '100002', NULL, NULL, 'Btl', NULL, NULL, NULL, '0.0000', '5000000.00', NULL, '1709351568', '2024-03-02 03:03:48', 'husnulmub@gmail.com'),
(4, '100003', 'Bola Lampu Philips', '3', NULL, 'TL -18 Watt', 'Bola Lampu Philips', '100003', NULL, NULL, 'Ea', NULL, NULL, NULL, '0.0000', '35000.00', NULL, '1709351615', '2024-03-02 03:03:35', 'husnulmub@gmail.com'),
(5, '100004', 'Wire Lock', '3', NULL, '500 CC', 'Wire Lock', '100004', NULL, NULL, 'Set', NULL, NULL, NULL, '0.0000', '450000.00', NULL, '1709351658', '2024-03-02 03:03:18', 'husnulmub@gmail.com'),
(6, '100005', 'Lampu Kodok Masko', '5', NULL, 'Ball Ice Lampu Kapal', 'Lampu Kodok Masko', '100005', NULL, NULL, 'Ea', NULL, NULL, NULL, '0.0000', '0.00', NULL, '1709352179', '2024-03-02 04:03:59', 'husnulmub@gmail.com'),
(7, '100006', 'Ballpoint Snowman V7', '2', NULL, 'Biru', 'Ballpoint Snowman V7', '100006', NULL, NULL, 'Ktk', NULL, NULL, NULL, '0.0000', '3500.00', NULL, '1709352223', '2024-03-02 04:03:43', 'husnulmub@gmail.com'),
(8, '100007', 'Materai Tempel', '2', NULL, 'RP. 10.000', 'Materai Tempel', '100007', NULL, NULL, 'Ea', NULL, NULL, NULL, '0.0000', '10000.00', NULL, '1709352288', '2024-03-02 04:03:48', 'husnulmub@gmail.com'),
(9, '100008', 'Bi Di Pig Brush Magnet', '4', NULL, 'Ø 6\"', 'Bi Di Pig Brush Magnet', '100008', NULL, NULL, 'Ea', NULL, NULL, NULL, '0.0000', '15000.00', NULL, '1709352421', '2024-03-02 04:03:01', 'husnulmub@gmail.com');

--
-- Trigger `t_material`
--
DELIMITER $$
CREATE TRIGGER `DELETE_MATERIAL` AFTER DELETE ON `t_material` FOR EACH ROW DELETE FROM t_material2 where material = OLD.material
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `INSERT_TO_ALT_UOM` AFTER INSERT ON `t_material` FOR EACH ROW INSERT INTO t_material2 VALUES(NEW.material,NEW.matunit,1,NEW.matunit,1,NEW.createdon,NEW.createdby)
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `t_material2`
--

CREATE TABLE `t_material2` (
  `material` varchar(70) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `altuom` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `convalt` decimal(15,2) NOT NULL,
  `baseuom` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `convbase` decimal(15,2) NOT NULL,
  `createdon` datetime NOT NULL,
  `createdby` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Material Alternative UOM';

--
-- Dumping data untuk tabel `t_material2`
--

INSERT INTO `t_material2` (`material`, `altuom`, `convalt`, `baseuom`, `convbase`, `createdon`, `createdby`) VALUES
('', 'Ea', '1.00', 'Ea', '1.00', '2024-03-02 04:03:10', 'husnulmub@gmail.com'),
('', 'Set', '1.00', 'Set', '1.00', '2024-03-02 04:03:56', 'husnulmub@gmail.com'),
('100001', 'Btl', '1.00', 'Btl', '1.00', '2024-03-02 03:03:54', 'husnulmub@gmail.com'),
('100002', 'Btl', '1.00', 'Btl', '1.00', '2024-03-02 03:03:48', 'husnulmub@gmail.com'),
('100003', 'Ea', '1.00', 'Ea', '1.00', '2024-03-02 04:03:10', 'husnulmub@gmail.com'),
('100004', 'Set', '1.00', 'Set', '1.00', '2024-03-02 04:03:56', 'husnulmub@gmail.com'),
('100005', 'Ea', '1.00', 'Ea', '1.00', '2024-03-02 04:03:59', 'husnulmub@gmail.com'),
('100006', 'Ktk', '1.00', 'Ktk', '1.00', '2024-03-02 04:03:43', 'husnulmub@gmail.com'),
('100007', 'Ea', '1.00', 'Ea', '1.00', '2024-03-02 04:03:48', 'husnulmub@gmail.com'),
('100008', 'Ea', '1.00', 'Ea', '1.00', '2024-03-02 04:03:01', 'husnulmub@gmail.com');

-- --------------------------------------------------------

--
-- Struktur dari tabel `t_materialgroup`
--

CREATE TABLE `t_materialgroup` (
  `matgroup` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `createdon` date NOT NULL,
  `createdby` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `t_materialtype`
--

CREATE TABLE `t_materialtype` (
  `id` int NOT NULL,
  `mattypedesc` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `createdon` datetime NOT NULL,
  `createdby` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Material Type';

--
-- Dumping data untuk tabel `t_materialtype`
--

INSERT INTO `t_materialtype` (`id`, `mattypedesc`, `createdon`, `createdby`) VALUES
(1, 'Gas', '2024-03-02 03:03:02', 'husnulmub@gmail.com'),
(2, 'ATK', '2024-03-02 03:03:02', 'husnulmub@gmail.com'),
(3, 'Electric', '2024-03-02 03:03:02', 'husnulmub@gmail.com'),
(4, 'Pipe', '2024-03-02 03:03:02', 'husnulmub@gmail.com'),
(5, 'Sparepart', '2024-03-02 03:03:59', 'husnulmub@gmail.com');

-- --------------------------------------------------------

--
-- Struktur dari tabel `t_nriv`
--

CREATE TABLE `t_nriv` (
  `object` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nyear` int NOT NULL,
  `fromnum` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tonumber` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `currentnum` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `t_nriv`
--

INSERT INTO `t_nriv` (`object`, `nyear`, `fromnum`, `tonumber`, `currentnum`) VALUES
('VENDOR', 0, '300000', '399999', '300000');

-- --------------------------------------------------------

--
-- Struktur dari tabel `t_nrivs`
--

CREATE TABLE `t_nrivs` (
  `object` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tahun` int NOT NULL,
  `bulan` varchar(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal` varchar(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `deptid` int NOT NULL DEFAULT '0',
  `lastnumber` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `createdby` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `createdon` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `t_nrivs`
--

INSERT INTO `t_nrivs` (`object`, `tahun`, `bulan`, `tanggal`, `deptid`, `lastnumber`, `createdby`, `createdon`) VALUES
('GRPO', 2024, '03', '01', 0, '3', 'husnulmub@gmail.com', '2024-03-11');

-- --------------------------------------------------------

--
-- Struktur dari tabel `t_po01`
--

CREATE TABLE `t_po01` (
  `id` int NOT NULL,
  `ponum` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `ext_ponum` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `potype` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `podat` date DEFAULT NULL,
  `delivery_date` date DEFAULT NULL,
  `vendor` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `note` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `idproject` int DEFAULT NULL,
  `currency` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `approvestat` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'O',
  `appby` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `completed` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ppn` decimal(15,2) DEFAULT '0.00',
  `tf_price` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tf_dest` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tf_shipment` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tf_top` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tf_packing` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tf_shipdate` date DEFAULT NULL,
  `dlv_terms` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `submitted` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'N',
  `is_posolar` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'N',
  `solar_pbbkb` double NOT NULL DEFAULT '0',
  `solar_oat` double NOT NULL DEFAULT '0',
  `solar_ppn_oat` double NOT NULL DEFAULT '0',
  `createdon` datetime DEFAULT NULL,
  `createdby` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `changedon` datetime DEFAULT NULL,
  `changedby` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Purchase Order Header';

--
-- Dumping data untuk tabel `t_po01`
--

INSERT INTO `t_po01` (`id`, `ponum`, `ext_ponum`, `potype`, `podat`, `delivery_date`, `vendor`, `note`, `idproject`, `currency`, `approvestat`, `appby`, `completed`, `ppn`, `tf_price`, `tf_dest`, `tf_shipment`, `tf_top`, `tf_packing`, `tf_shipdate`, `dlv_terms`, `submitted`, `is_posolar`, `solar_pbbkb`, `solar_oat`, `solar_ppn_oat`, `createdon`, `createdby`, `changedon`, `changedby`) VALUES
(7, 'KRS/PO/AA/03/24/000001', 'KRS/PO/AA/03/24/000001', 'AA', '2024-03-07', '2024-03-07', '1', 'Testing Update PO', 1, 'USD', 'A', NULL, NULL, '11.00', NULL, NULL, NULL, '45 Hari Setelah Barang diterima', NULL, NULL, NULL, 'N', '0', 0, 0, 0, '2024-03-07 23:17:22', 'sys-admin', '2024-03-10 14:52:36', 'sys-admin'),
(9, 'KRS/PO/SQ/03/24/000001', 'KRS/PO/SQ/03/24/000001', 'SQ', '2024-03-07', '2024-03-07', '1', 'Testing', 1, 'USD', 'O', NULL, NULL, '11.00', NULL, NULL, NULL, '7 Hari Setelah Invoice Diterima', NULL, NULL, 'Indent 5 Hari', 'N', '0', 0, 0, 0, '2024-03-07 23:31:32', 'sys-admin', '2024-03-24 13:51:34', 'sys-admin'),
(10, 'KRS/PO/AA/03/24/000002', 'KRS/PO/AA/03/24/000002', 'AA', '2024-03-09', '2024-03-09', '1', 'Test', 1, 'IDR', 'O', NULL, NULL, '0.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'N', 'N', 0, 0, 0, '2024-03-09 22:27:15', 'sys-admin', NULL, NULL),
(11, 'KRS/PO/AA/03/24/000003', 'KRS/PO/AA/03/24/000003', 'AA', '2024-03-10', '2024-03-10', '1', 'Coba', 1, 'IDR', 'O', NULL, NULL, '11.00', NULL, NULL, NULL, '45 Hari Setelah Barang diterima', NULL, NULL, NULL, 'N', 'N', 0, 0, 0, '2024-03-10 06:50:51', 'sys-admin', NULL, NULL),
(13, 'KRS/PO/GM/03/24/000001', 'KRS/PO/GM/03/24/000001', 'GM', '2024-03-10', '2024-03-10', '1', 'tes', 1, 'IDR', 'O', NULL, NULL, '0.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'N', 'N', 0, 0, 0, '2024-03-10 07:01:38', 'sys-admin', NULL, NULL),
(14, 'KRS/PO/GM/03/24/000002', 'KRS/PO/GM/03/24/000002', 'GM', '2024-03-10', '2024-03-10', '1', 'Test', 1, 'IDR', 'O', NULL, NULL, '0.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'N', 'N', 0, 0, 0, '2024-03-10 07:11:14', 'sys-admin', NULL, NULL),
(15, 'KRS/PO/AA/03/24/000004', 'KRS/PO/AA/03/24/000004', 'AA', '2024-03-10', '2024-03-10', '1', 'tes', 1, 'IDR', 'O', NULL, NULL, '0.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'N', 'N', 0, 0, 0, '2024-03-10 07:50:28', 'sys-admin', NULL, NULL),
(16, 'KRS/PO/AA/03/24/000005', 'KRS/PO/AA/03/24/000005', 'AA', '2024-03-10', '2024-03-10', '1', 'Test 2', 1, NULL, 'O', NULL, NULL, '11.00', NULL, NULL, NULL, '45 Hari Setelah Barang diterima', NULL, NULL, 'Indent 5 Hari', 'N', '0', 0, 0, 0, '2024-03-10 07:53:03', 'sys-admin', '2024-03-24 13:54:10', 'sys-admin'),
(18, 'KRS/PO/AA/03/24/000006', 'KRS/PO/AA/03/24/000006', 'AA', '2024-03-11', '2024-03-11', '1', 'Test', 2, 'IDR', 'A', NULL, NULL, '0.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'N', 'N', 0, 0, 0, '2024-03-10 13:55:52', 'sys-admin', NULL, NULL),
(19, 'KRS/PO/SQ/03/24/000002', 'KRS/PO/SQ/03/24/000002', 'SQ', '2024-03-14', '2024-03-14', '1', 'Tes', 1, 'IDR', 'A', NULL, NULL, '11.00', NULL, NULL, NULL, '45 Hari Setelah Barang diterima', NULL, NULL, NULL, 'N', 'N', 0, 0, 0, '2024-03-14 16:03:27', 'sys-admin', NULL, NULL);

--
-- Trigger `t_po01`
--
DELIMITER $$
CREATE TRIGGER `deleteitem` AFTER DELETE ON `t_po01` FOR EACH ROW DELETE FROM t_po02 WHERE ponum = OLD.ponum
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `t_po02`
--

CREATE TABLE `t_po02` (
  `ponum` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `poitem` int NOT NULL,
  `material` varchar(70) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `matdesc` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quantity` decimal(15,3) DEFAULT NULL,
  `unit` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` decimal(15,3) DEFAULT NULL,
  `grqty` decimal(15,3) DEFAULT '0.000',
  `prnum` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pritem` int DEFAULT NULL,
  `grstatus` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'O',
  `pocomplete` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `approvestat` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'N',
  `idproject` int DEFAULT '0',
  `cost_code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `createdon` date DEFAULT NULL,
  `createdby` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `changedon` datetime DEFAULT NULL,
  `changedby` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='PO Item';

--
-- Dumping data untuk tabel `t_po02`
--

INSERT INTO `t_po02` (`ponum`, `poitem`, `material`, `matdesc`, `quantity`, `unit`, `price`, `grqty`, `prnum`, `pritem`, `grstatus`, `pocomplete`, `approvestat`, `idproject`, `cost_code`, `createdon`, `createdby`, `changedon`, `changedby`) VALUES
('KRS/PO/AA/03/24/000001', 1, '100001', 'Gas Oxygen', '1.000', 'Btl', '100000.000', '1.000', 'SPB/SQ/03/24/000001', 1, 'F', NULL, 'A', 1, '1', '2024-03-07', 'sys-admin', '2024-03-10 14:52:36', 'sys-admin'),
('KRS/PO/AA/03/24/000001', 2, '100002', 'Gas Argon', '2.000', 'Btl', '5000000.000', '2.000', 'SPB/SQ/03/24/000001', 2, 'F', NULL, 'A', 1, '1', '2024-03-07', 'sys-admin', '2024-03-10 14:52:36', 'sys-admin'),
('KRS/PO/AA/03/24/000001', 3, '100006', 'Ballpoint Snowman V7', '5.000', 'Ktk', '3500.000', '5.000', '', 0, 'F', NULL, 'A', 1, '3', '2024-03-07', 'sys-admin', '2024-03-10 14:52:36', 'sys-admin'),
('KRS/PO/AA/03/24/000001', 4, '100007', 'Materai Tempel', '7.000', 'Ea', '10000.000', '7.000', '', 0, 'F', NULL, 'A', 1, '3', '2024-03-07', 'sys-admin', '2024-03-10 14:52:36', 'sys-admin'),
('KRS/PO/AA/03/24/000001', 5, '100008', 'Bi Di Pig Brush Magnet', '8.000', 'Ea', '15000.000', '8.000', '', 0, 'F', NULL, 'A', 1, '4', '2024-03-07', 'sys-admin', '2024-03-10 14:52:36', 'sys-admin'),
('KRS/PO/AA/03/24/000002', 1, '100004', 'Wire Lock', '40.000', 'Set', '450000.000', '0.000', 'SPB/GM/03/24/000002', 1, 'O', NULL, 'N', 1, '0', '2024-03-09', 'sys-admin', NULL, NULL),
('KRS/PO/AA/03/24/000003', 1, '100003', 'Bola Lampu Philips', '10.000', 'Ea', '35000.000', '0.000', 'SPB/AA/03/24/000002', 1, 'O', NULL, 'N', 2, '2', '2024-03-10', 'sys-admin', NULL, NULL),
('KRS/PO/AA/03/24/000003', 2, '100004', 'Wire Lock', '3.000', 'Set', '450000.000', '0.000', 'SPB/AA/03/24/000002', 2, 'O', NULL, 'N', 2, '1', '2024-03-10', 'sys-admin', NULL, NULL),
('KRS/PO/AA/03/24/000004', 1, '100001', 'Gas Oxygen', '1.000', 'Btl', '100000.000', '0.000', 'SPB/AA/03/24/000003', 1, 'O', NULL, 'N', 1, '1', '2024-03-10', 'sys-admin', NULL, NULL),
('KRS/PO/AA/03/24/000004', 2, '100002', 'Gas Argon', '1.000', 'Btl', '5000000.000', '0.000', 'SPB/AA/03/24/000003', 2, 'O', NULL, 'N', 1, '1', '2024-03-10', 'sys-admin', NULL, NULL),
('KRS/PO/AA/03/24/000005', 1, '100002', 'Gas Argon', '1.000', 'Btl', '5000000.000', '0.000', 'SPB/AA/03/24/000003', 2, 'O', NULL, 'N', 1, '1', '2024-03-10', 'sys-admin', '2024-03-24 13:54:10', 'sys-admin'),
('KRS/PO/AA/03/24/000005', 2, '100007', 'Materai Tempel', '2.000', 'Ea', '10000.000', '0.000', '0', 0, 'O', NULL, 'N', 1, '1', '2024-03-10', 'sys-admin', '2024-03-24 13:54:10', 'sys-admin'),
('KRS/PO/AA/03/24/000005', 3, '100008', 'Bi Di Pig Brush Magnet', '3.000', 'Ea', '15000.000', '0.000', '0', 0, 'O', NULL, 'N', 1, '1', '2024-03-10', 'sys-admin', '2024-03-24 13:54:10', 'sys-admin'),
('KRS/PO/AA/03/24/000006', 1, '100003', 'Bola Lampu Philips', '1.000', 'Ea', '35000.000', '1.000', '0', 0, 'F', NULL, 'A', 2, '1', '2024-03-10', 'sys-admin', NULL, NULL),
('KRS/PO/AA/03/24/000006', 2, '100004', 'Wire Lock', '2.000', 'Set', '450000.000', '2.000', '0', 0, 'F', NULL, 'A', 2, '1', '2024-03-10', 'sys-admin', NULL, NULL),
('KRS/PO/GM/03/24/000001', 1, '100007', 'Materai Tempel', '5.000', 'Ea', '10000.000', '0.000', 'SPB/GM/03/24/000003', 1, 'O', NULL, 'N', 2, '1', '2024-03-10', 'sys-admin', NULL, NULL),
('KRS/PO/GM/03/24/000001', 2, '100008', 'Bi Di Pig Brush Magnet', '8.000', 'Ea', '15000.000', '0.000', 'SPB/GM/03/24/000003', 2, 'O', NULL, 'N', 2, '2', '2024-03-10', 'sys-admin', NULL, NULL),
('KRS/PO/GM/03/24/000002', 1, '100007', 'Materai Tempel', '5.000', 'Ea', '10000.000', '0.000', 'SPB/GM/03/24/000003', 1, 'O', NULL, 'N', 2, '2', '2024-03-10', 'sys-admin', NULL, NULL),
('KRS/PO/GM/03/24/000002', 2, '100008', 'Bi Di Pig Brush Magnet', '2.000', 'Ea', '15000.000', '0.000', 'SPB/GM/03/24/000003', 2, 'O', NULL, 'N', 2, '3', '2024-03-10', 'sys-admin', NULL, NULL),
('KRS/PO/SQ/03/24/000001', 1, '100001', 'Gas Oxygen', '2.000', 'Btl', '100000.000', '0.000', 'SPB/AA/03/24/000001', 1, 'O', NULL, 'N', 1, '0', '2024-03-07', 'sys-admin', '2024-03-24 13:51:34', 'sys-admin'),
('KRS/PO/SQ/03/24/000001', 2, '100002', 'Gas Argon', '3.000', 'Btl', '5000000.000', '0.000', 'SPB/AA/03/24/000001', 2, 'O', NULL, 'N', 1, '0', '2024-03-07', 'sys-admin', '2024-03-24 13:51:34', 'sys-admin'),
('KRS/PO/SQ/03/24/000002', 1, '100001', 'Gas Oxygen', '3.000', 'Btl', '100000.000', '3.000', 'SPB/SQ/03/24/000003', 1, 'F', NULL, 'A', 1, '1', '2024-03-14', 'sys-admin', NULL, NULL),
('KRS/PO/SQ/03/24/000002', 2, '100002', 'Gas Argon', '6.000', 'Btl', '5000000.000', '6.000', 'SPB/SQ/03/24/000003', 2, 'F', NULL, 'A', 1, '2', '2024-03-14', 'sys-admin', NULL, NULL),
('KRS/PO/SQ/03/24/000002', 3, '100003', 'Bola Lampu Philips', '7.000', 'Ea', '35000.000', '7.000', 'SPB/SQ/03/24/000003', 3, 'F', NULL, 'A', 1, '2', '2024-03-14', 'sys-admin', NULL, NULL);

--
-- Trigger `t_po02`
--
DELIMITER $$
CREATE TRIGGER `deleteitempo` AFTER DELETE ON `t_po02` FOR EACH ROW UPDATE t_pr02 set pocreated = NULL WHERE prnum = OLD.prnum AND pritem = OLD.pritem
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `t_po03`
--

CREATE TABLE `t_po03` (
  `id` int NOT NULL,
  `ponum` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `costname` varchar(70) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `costvalue` decimal(23,2) NOT NULL,
  `is_posolar` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'N',
  `createdby` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `createdon` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `t_po03`
--

INSERT INTO `t_po03` (`id`, `ponum`, `costname`, `costvalue`, `is_posolar`, `createdby`, `createdon`) VALUES
(1, 'KRS/PO/AA/03/24/000003', 'Biaya Angkut', '16000.00', 'N', 'sys-admin', '2024-03-10 06:50:51');

-- --------------------------------------------------------

--
-- Struktur dari tabel `t_po_approval`
--

CREATE TABLE `t_po_approval` (
  `id` int NOT NULL,
  `ponum` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `poitem` int DEFAULT '0',
  `requester` int NOT NULL,
  `approver` int NOT NULL,
  `approver_level` int NOT NULL,
  `is_active` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'N',
  `approval_status` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'N',
  `approval_remark` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `approval_date` datetime DEFAULT NULL,
  `approved_by` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `createdon` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `t_po_approval`
--

INSERT INTO `t_po_approval` (`id`, `ponum`, `poitem`, `requester`, `approver`, `approver_level`, `is_active`, `approval_status`, `approval_remark`, `approval_date`, `approved_by`, `createdon`) VALUES
(1, 'KRS/PO/AA/03/24/000006', 0, 1, 1, 1, 'Y', 'A', 'Ok Approved', '2024-03-11 00:00:00', 'sys-admin', '2024-03-10'),
(4, 'KRS/PO/AA/03/24/000001', 0, 1, 1, 1, 'Y', 'A', NULL, '2024-03-11 00:00:00', 'sys-admin', '2024-03-10'),
(5, 'KRS/PO/SQ/03/24/000002', 0, 1, 1, 1, 'Y', 'A', 'Ok', '2024-03-14 16:03:44', 'sys-admin', '2024-03-14'),
(7, 'KRS/PO/SQ/03/24/000001', 0, 1, 1, 1, 'Y', 'N', NULL, NULL, NULL, '2024-03-24'),
(8, 'KRS/PO/AA/03/24/000005', 0, 1, 1, 1, 'Y', 'N', NULL, NULL, NULL, '2024-03-24');

-- --------------------------------------------------------

--
-- Struktur dari tabel `t_pr01`
--

CREATE TABLE `t_pr01` (
  `id` int NOT NULL,
  `prnum` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `typepr` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `note` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `prdate` date DEFAULT NULL,
  `relgroup` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `approvestat` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'N',
  `requestby` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `warehouse` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `idproject` int DEFAULT NULL,
  `remark` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `appby` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `disetujui` int DEFAULT NULL,
  `diketahui` int DEFAULT NULL,
  `createdon` datetime DEFAULT NULL,
  `changedon` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `createdby` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `changedby` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Purchase Requisition Header';

--
-- Dumping data untuk tabel `t_pr01`
--

INSERT INTO `t_pr01` (`id`, `prnum`, `typepr`, `note`, `prdate`, `relgroup`, `approvestat`, `requestby`, `warehouse`, `idproject`, `remark`, `appby`, `disetujui`, `diketahui`, `createdon`, `changedon`, `createdby`, `changedby`) VALUES
(11, 'SPB/SQ/03/24/000001', 'SQ', NULL, '2024-03-05', NULL, 'A', 'Administrator', NULL, 1, 'tes', NULL, NULL, NULL, '2024-03-05 23:27:51', '2024-03-05 23:27:51', 'sys-admin', NULL),
(12, 'SPB/GM/03/24/000001', 'SQ', NULL, '2024-03-05', NULL, 'R', 'Administrator', NULL, 2, 'Coba', NULL, NULL, NULL, '2024-03-05 23:28:12', '2024-03-05 23:28:12', 'sys-admin', NULL),
(13, 'SPB/AA/03/24/000001', 'AA', NULL, '2024-03-06', NULL, 'A', 'Administrator', NULL, 1, 'Test', NULL, NULL, NULL, '2024-03-06 15:15:54', '2024-03-06 15:15:54', 'sys-admin', NULL),
(14, 'SPB/GM/03/24/000002', 'SQ', NULL, '2024-03-07', NULL, 'A', 'Administrator', NULL, 2, 'tes', NULL, NULL, NULL, '2024-03-07 23:27:22', '2024-03-07 23:27:22', 'sys-admin', NULL),
(15, 'SPB/AA/03/24/000002', 'AA', NULL, '2024-03-10', NULL, 'A', 'Administrator', NULL, 2, 'Testing PR', NULL, NULL, NULL, '2024-03-10 06:44:57', '2024-03-10 06:44:57', 'sys-admin', NULL),
(16, 'SPB/GM/03/24/000003', 'SQ', NULL, '2024-03-10', NULL, 'A', 'Administrator', NULL, 2, 'tes', NULL, NULL, NULL, '2024-03-10 06:59:55', '2024-03-10 06:59:55', 'sys-admin', NULL),
(17, 'SPB/AA/03/24/000003', 'AA', NULL, '2024-03-10', NULL, 'A', 'Administrator', NULL, 1, 'test', NULL, NULL, NULL, '2024-03-10 07:49:20', '2024-03-10 07:49:20', 'sys-admin', NULL),
(18, 'SPB/AA/03/24/000004', 'AA', NULL, '2024-03-11', NULL, 'A', 'Administrator', NULL, 1, 'Tes', NULL, NULL, NULL, '2024-03-11 11:00:59', '2024-03-11 11:00:59', 'sys-admin', NULL),
(19, 'SPB/SQ/03/24/000002', 'SQ', NULL, '2024-03-11', NULL, 'A', 'Administrator', NULL, 1, 'tes', NULL, NULL, NULL, '2024-03-11 11:07:48', '2024-03-11 11:07:48', 'sys-admin', NULL),
(20, 'SPB/SQ/03/24/000003', 'SQ', NULL, '2024-03-14', NULL, 'A', 'Administrator', NULL, 1, 'Tes', NULL, NULL, NULL, '2024-03-14 16:01:49', '2024-03-14 16:01:49', 'sys-admin', NULL),
(22, 'SPB/AA/03/24/000006(GM)', 'AA', NULL, '2024-03-17', NULL, 'N', 'Administrator', NULL, 2, 'Testt', NULL, NULL, NULL, '2024-03-17 11:41:56', '2024-03-17 11:41:56', 'sys-admin', NULL),
(23, 'SPB/SQ/03/24/000004', 'SQ', NULL, '2024-03-17', NULL, 'N', 'Administrator', NULL, 1, 'Testing', NULL, 1, 2, '2024-03-17 16:46:36', '2024-03-18 23:06:28', 'sys-admin', 'sys-admin');

--
-- Trigger `t_pr01`
--
DELIMITER $$
CREATE TRIGGER `deletepritem` AFTER DELETE ON `t_pr01` FOR EACH ROW DELETE FROM t_pr02 WHERE prnum = OLD.prnum
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `t_pr02`
--

CREATE TABLE `t_pr02` (
  `prnum` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `pritem` int NOT NULL,
  `material` varchar(70) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `matdesc` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quantity` decimal(18,3) DEFAULT NULL,
  `unit` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `warehouse` int DEFAULT NULL,
  `pocreated` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'N',
  `approvestat` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remark` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `isdeleted` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'N',
  `idproject` int DEFAULT NULL,
  `createdon` datetime DEFAULT NULL,
  `createdby` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `changedon` datetime DEFAULT NULL,
  `changedby` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Purchase Request Item';

--
-- Dumping data untuk tabel `t_pr02`
--

INSERT INTO `t_pr02` (`prnum`, `pritem`, `material`, `matdesc`, `quantity`, `unit`, `warehouse`, `pocreated`, `approvestat`, `remark`, `isdeleted`, `idproject`, `createdon`, `createdby`, `changedon`, `changedby`) VALUES
('SPB/AA/03/24/000001', 1, '100001', 'Gas Oxygen', '2.000', 'Btl', NULL, 'Y', 'A', 'coba aja', 'N', 1, '2024-03-06 15:15:54', 'sys-admin', NULL, NULL),
('SPB/AA/03/24/000001', 2, '100002', 'Gas Argon', '3.000', 'Btl', NULL, 'Y', 'A', 'coba aja', 'N', 1, '2024-03-06 15:15:54', 'sys-admin', NULL, NULL),
('SPB/AA/03/24/000002', 1, '100003', 'Bola Lampu Philips', '10.000', 'Ea', NULL, 'N', 'A', 'coba aja', 'N', 2, '2024-03-10 06:44:57', 'sys-admin', NULL, NULL),
('SPB/AA/03/24/000002', 2, '100004', 'Wire Lock', '3.000', 'Set', NULL, 'N', 'A', 'coba aja', 'N', 2, '2024-03-10 06:44:57', 'sys-admin', NULL, NULL),
('SPB/AA/03/24/000003', 1, '100001', 'Gas Oxygen', '1.000', 'Btl', NULL, 'P', 'A', '1', 'N', 1, '2024-03-10 07:49:20', 'sys-admin', NULL, NULL),
('SPB/AA/03/24/000003', 2, '100002', 'Gas Argon', '2.000', 'Btl', NULL, 'Y', 'A', '1', 'N', 1, '2024-03-10 07:49:20', 'sys-admin', NULL, NULL),
('SPB/AA/03/24/000004', 1, '100001', 'Gas Oxygen', '60.000', 'Btl', NULL, 'N', 'A', 'coba aja', 'N', 1, '2024-03-11 11:00:59', 'sys-admin', NULL, NULL),
('SPB/AA/03/24/000004', 2, '100002', 'Gas Argon', '76.000', 'Btl', NULL, 'N', 'A', 'coba aja', 'N', 1, '2024-03-11 11:00:59', 'sys-admin', NULL, NULL),
('SPB/AA/03/24/000006(GM)', 1, '100001', 'Gas Oxygen', '2.000', 'Btl', NULL, 'N', NULL, 'coba aja', 'N', 2, '2024-03-17 11:41:56', 'sys-admin', NULL, NULL),
('SPB/AA/03/24/000006(GM)', 2, '100002', 'Gas Argon', '3.000', 'Btl', NULL, 'N', NULL, 'coba aja', 'N', 2, '2024-03-17 11:41:56', 'sys-admin', NULL, NULL),
('SPB/AA/03/24/000006(GM)', 3, '100003', 'Bola Lampu Philips', '4.000', 'Ea', NULL, 'N', NULL, 'coba aja', 'N', 2, '2024-03-17 11:41:56', 'sys-admin', NULL, NULL),
('SPB/GM/03/24/000001', 1, '100004', 'Wire Lock', '1.000', 'Set', NULL, 'N', 'R', '', 'N', 2, '2024-03-05 23:28:12', 'sys-admin', NULL, NULL),
('SPB/GM/03/24/000001', 2, '100005', 'Lampu Kodok Masko', '2.000', 'Ea', NULL, 'N', 'R', '', 'N', 2, '2024-03-05 23:28:12', 'sys-admin', NULL, NULL),
('SPB/GM/03/24/000001', 3, '100006', 'Ballpoint Snowman V7', '44.000', 'Ktk', NULL, 'N', 'R', '', 'N', 2, '2024-03-05 23:28:12', 'sys-admin', NULL, NULL),
('SPB/GM/03/24/000002', 1, '100004', 'Wire Lock', '100.000', 'Set', NULL, NULL, 'A', 'tes', 'N', 2, '2024-03-07 23:27:22', 'sys-admin', NULL, NULL),
('SPB/GM/03/24/000003', 1, '100007', 'Materai Tempel', '10.000', 'Ea', NULL, 'Y', 'A', 'coba aja', 'N', 2, '2024-03-10 06:59:55', 'sys-admin', NULL, NULL),
('SPB/GM/03/24/000003', 2, '100008', 'Bi Di Pig Brush Magnet', '10.000', 'Ea', NULL, 'Y', 'A', 'coba aja update', 'N', 2, '2024-03-10 06:59:55', 'sys-admin', NULL, NULL),
('SPB/SQ/03/24/000001', 1, '100001', 'Gas Oxygen', '1.000', 'Btl', NULL, 'Y', 'A', '', 'N', 1, '2024-03-05 23:27:51', 'sys-admin', NULL, NULL),
('SPB/SQ/03/24/000001', 2, '100002', 'Gas Argon', '2.000', 'Btl', NULL, 'Y', 'A', '', 'N', 1, '2024-03-05 23:27:51', 'sys-admin', NULL, NULL),
('SPB/SQ/03/24/000002', 1, '100001', 'Gas Oxygen', '1.000', 'Btl', NULL, 'N', 'A', '1', 'N', 1, '2024-03-11 11:07:48', 'sys-admin', NULL, NULL),
('SPB/SQ/03/24/000002', 2, '100002', 'Gas Argon', '1.000', 'Btl', NULL, 'N', 'A', '3', 'N', 1, '2024-03-11 11:07:48', 'sys-admin', NULL, NULL),
('SPB/SQ/03/24/000003', 1, '100001', 'Gas Oxygen', '3.000', 'Btl', NULL, 'P', 'A', 'Ket 1', 'N', 1, '2024-03-14 16:01:49', 'sys-admin', NULL, NULL),
('SPB/SQ/03/24/000003', 2, '100002', 'Gas Argon', '6.000', 'Btl', NULL, 'P', 'A', 'Ket 2', 'N', 1, '2024-03-14 16:01:49', 'sys-admin', NULL, NULL),
('SPB/SQ/03/24/000003', 3, '100003', 'Bola Lampu Philips', '7.000', 'Ea', NULL, 'P', 'A', 'Ket 3', 'N', 1, '2024-03-14 16:01:49', 'sys-admin', NULL, NULL),
('SPB/SQ/03/24/000004', 1, '100001', 'Gas Oxygen', '1.000', 'Btl', NULL, 'N', NULL, 'U/ Kep. Crane Barge', 'N', 1, '2024-03-17 16:46:36', 'sys-admin', '2024-03-18 23:06:28', 'sys-admin'),
('SPB/SQ/03/24/000004', 2, '100002', 'Gas Argon', '2.000', 'Btl', NULL, 'N', NULL, 'U/ Kep. Crane Barge', 'N', 1, '2024-03-17 16:46:36', 'sys-admin', '2024-03-18 23:06:28', 'sys-admin'),
('SPB/SQ/03/24/000004', 3, '100003', 'Bola Lampu Philips', '3.000', 'Ea', NULL, 'N', NULL, 'U/ Kep. Fabrikasi', 'N', 1, '2024-03-17 16:46:36', 'sys-admin', '2024-03-18 23:06:28', 'sys-admin'),
('SPB/SQ/03/24/000004', 4, '100004', 'Wire Lock', '4.000', 'Set', NULL, 'N', NULL, 'U/ Kep. Fabrikasi', 'N', 1, '2024-03-17 16:46:36', 'sys-admin', '2024-03-18 23:06:28', 'sys-admin'),
('SPB/SQ/03/24/000004', 5, '100005', 'Lampu Kodok Masko', '5.000', 'Ea', NULL, 'N', NULL, 'U/ Kep. CB. Dept\' Handil', 'N', 1, '2024-03-17 16:46:36', 'sys-admin', '2024-03-18 23:06:28', 'sys-admin'),
('SPB/SQ/03/24/000004', 6, '100006', 'Ballpoint Snowman V7', '7.000', 'Ktk', NULL, 'N', NULL, 'U/ Kep. CB. Dept\' Handil', 'N', 1, '2024-03-17 16:46:36', 'sys-admin', '2024-03-18 23:06:28', 'sys-admin'),
('SPB/SQ/03/24/000004', 7, '100007', 'Materai Tempel', '8.000', 'Ea', NULL, 'N', NULL, 'U/ Kep. CB. Dept\' Handil', 'N', 1, '2024-03-17 16:46:36', 'sys-admin', '2024-03-18 23:06:28', 'sys-admin'),
('SPB/SQ/03/24/000004', 8, '100008', 'Bi Di Pig Brush Magnet', '10.000', 'Ea', NULL, 'N', NULL, 'U/ Kep. Enviro Dept\' Handil', 'N', 1, '2024-03-17 16:46:36', 'sys-admin', '2024-03-18 23:06:28', 'sys-admin');

-- --------------------------------------------------------

--
-- Struktur dari tabel `t_projects`
--

CREATE TABLE `t_projects` (
  `id` int NOT NULL,
  `kode_project` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_project` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `project_manager` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `manager_lapangan` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nilai_project` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_project` varchar(1) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'O',
  `createdby` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `createdon` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `t_projects`
--

INSERT INTO `t_projects` (`id`, `kode_project`, `nama_project`, `project_manager`, `manager_lapangan`, `nilai_project`, `status_project`, `createdby`, `createdon`) VALUES
(1, 'SQ', 'MAHAKAM WELL CONNECTION (MECHANICAL) TENDER A ( SUB PACKAGE 1 )', 'J. Dion', 'Rio Bagja', '1 T', 'O', 'husnulmub@gmail.com', '2024-03-02 11:19:36'),
(2, 'GM', 'GRAND PROJECT 12345', 'J. Dion', 'Suhendra', '9 T', 'O', 'husnulmub@gmail.com', '2024-03-05 22:38:31'),
(3, 'SA', 'Test', 'Cs01 Update', 'Ml01 Coba', '2 M', 'O', 'husnulmub@gmail.com', '2024-03-27 11:39:46');

-- --------------------------------------------------------

--
-- Struktur dari tabel `t_pr_approval`
--

CREATE TABLE `t_pr_approval` (
  `id` int NOT NULL,
  `prnum` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `pritem` int DEFAULT '0',
  `requester` int NOT NULL,
  `approver` int NOT NULL,
  `approver_level` int NOT NULL,
  `is_active` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'N',
  `approval_status` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'N',
  `approval_remark` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `approval_date` datetime DEFAULT NULL,
  `approved_by` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `createdon` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `t_pr_approval`
--

INSERT INTO `t_pr_approval` (`id`, `prnum`, `pritem`, `requester`, `approver`, `approver_level`, `is_active`, `approval_status`, `approval_remark`, `approval_date`, `approved_by`, `createdon`) VALUES
(9, 'SPB/SQ/03/24/000001', 0, 1, 1, 1, 'Y', 'A', 'Test', '2024-03-06 15:07:46', 'sys-admin', '2024-03-05'),
(10, 'SPB/GM/03/24/000001', 0, 1, 1, 1, 'Y', 'R', 'Test Reject', '2024-03-06 15:09:04', 'sys-admin', '2024-03-05'),
(11, 'SPB/AA/03/24/000001', 0, 1, 1, 1, 'Y', 'A', 'Ok', '2024-03-07 23:26:24', 'sys-admin', '2024-03-06'),
(12, 'SPB/GM/03/24/000002', 0, 1, 1, 1, 'Y', 'A', 'ok', '2024-03-07 23:27:40', 'sys-admin', '2024-03-07'),
(13, 'SPB/AA/03/24/000002', 0, 1, 1, 1, 'Y', 'A', NULL, '2024-03-10 06:45:11', 'sys-admin', '2024-03-10'),
(14, 'SPB/GM/03/24/000003', 0, 1, 1, 1, 'Y', 'A', NULL, '2024-03-10 07:00:11', 'sys-admin', '2024-03-10'),
(15, 'SPB/AA/03/24/000003', 0, 1, 1, 1, 'Y', 'A', NULL, '2024-03-10 07:49:33', 'sys-admin', '2024-03-10'),
(16, 'SPB/AA/03/24/000004', 0, 1, 1, 1, 'Y', 'A', NULL, '2024-03-11 11:05:31', 'sys-admin', '2024-03-11'),
(17, 'SPB/SQ/03/24/000002', 0, 1, 1, 1, 'Y', 'A', 'Ok Approved', '2024-03-11 11:08:14', 'sys-admin', '2024-03-11'),
(18, 'SPB/SQ/03/24/000003', 0, 1, 1, 1, 'Y', 'A', 'Testing Approve', '2024-03-14 16:02:26', 'sys-admin', '2024-03-14'),
(20, 'SPB/AA/03/24/000006(GM)', 0, 1, 1, 1, 'Y', 'N', NULL, NULL, NULL, '2024-03-17'),
(22, 'SPB/SQ/03/24/000004', 0, 1, 1, 1, 'Y', 'N', NULL, NULL, NULL, '2024-03-18');

-- --------------------------------------------------------

--
-- Struktur dari tabel `t_purc_doctype`
--

CREATE TABLE `t_purc_doctype` (
  `id` int NOT NULL,
  `object` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `doctype` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `createdon` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `t_purc_doctype`
--

INSERT INTO `t_purc_doctype` (`id`, `object`, `doctype`, `description`, `createdon`) VALUES
(1, 'PO', 'AA', 'Head Office', '2024-03-16 07:37:18'),
(2, 'PO', 'SQ', 'Proyek', '2024-03-16 07:37:18'),
(3, 'PR', 'AA', 'Head Office', '2024-03-16 07:37:48'),
(4, 'PR', 'SQ', 'Proyek', '2024-03-16 07:37:48');

-- --------------------------------------------------------

--
-- Struktur dari tabel `t_reserv01`
--

CREATE TABLE `t_reserv01` (
  `resnum` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `resdate` date DEFAULT NULL,
  `note` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `requestor` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fromwhs` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `towhs` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `approvestat` int DEFAULT NULL,
  `createdon` datetime DEFAULT NULL,
  `createdby` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Reservation Header';

-- --------------------------------------------------------

--
-- Struktur dari tabel `t_reserv02`
--

CREATE TABLE `t_reserv02` (
  `resnum` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `resitem` int NOT NULL,
  `material` varchar(70) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `matdesc` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quantity` decimal(10,0) DEFAULT NULL,
  `unit` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fromwhs` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `towhs` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remark` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `movementstat` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `createdon` datetime DEFAULT NULL,
  `createdby` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Reservation Items';

-- --------------------------------------------------------

--
-- Struktur dari tabel `t_uom`
--

CREATE TABLE `t_uom` (
  `id` int NOT NULL,
  `uom` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `uomdesc` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `createdon` date NOT NULL,
  `createdby` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `t_uom`
--

INSERT INTO `t_uom` (`id`, `uom`, `uomdesc`, `createdon`, `createdby`) VALUES
(1, 'Ea', 'Ea', '2024-03-02', 'husnulmub@gmail.com'),
(2, 'Set', 'Set', '2024-03-02', 'husnulmub@gmail.com'),
(3, 'Btl', 'Botol', '2024-03-02', 'husnulmub@gmail.com'),
(4, 'Roll', 'Roll', '2024-03-02', 'husnulmub@gmail.com'),
(5, 'Unit', 'Unit', '2024-03-02', 'husnulmub@gmail.com'),
(6, 'Ktk', 'Ktk', '2024-03-02', 'husnulmub@gmail.com');

-- --------------------------------------------------------

--
-- Struktur dari tabel `t_vendor`
--

CREATE TABLE `t_vendor` (
  `id` int NOT NULL,
  `vendor_code` varchar(12) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Kode Vendor',
  `vendor_name` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nama Vendor',
  `vendor_pt` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vendor_profil` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `vendor_address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Alamat Vendor',
  `vendor_telp` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'No. Telp',
  `vendor_email` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Email',
  `contact_person` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_holder` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `no_rek` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `catatan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `vendor_id` int NOT NULL DEFAULT '0',
  `createdby` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `createdon` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Vendor Masters';

--
-- Dumping data untuk tabel `t_vendor`
--

INSERT INTO `t_vendor` (`id`, `vendor_code`, `vendor_name`, `vendor_pt`, `vendor_profil`, `vendor_address`, `vendor_telp`, `vendor_email`, `contact_person`, `bank_holder`, `bank`, `no_rek`, `catatan`, `vendor_id`, `createdby`, `createdon`) VALUES
(1, '300000', 'ABC 123', 'PT. ABC 123', NULL, 'Jalan. ABJ 123 Jakarta', '02100000', 'abc123@mail.com', 'Bp. Joko', '-', NULL, NULL, 'Vendor Gas & Electric', 1, 'husnulmub@gmail.com', '2024-03-02 04:03:39');

-- --------------------------------------------------------

--
-- Struktur dari tabel `t_warehouse`
--

CREATE TABLE `t_warehouse` (
  `id` int NOT NULL,
  `whscode` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `whsname` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `createdby` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `createdon` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `t_warehouse`
--

INSERT INTO `t_warehouse` (`id`, `whscode`, `whsname`, `address`, `createdby`, `createdon`) VALUES
(1, 'HO01', 'Head Office', 'Jalan Indonesi Maju 01', 'husnulmub@gmail.com', '2024-03-02'),
(2, 'SQ01', 'Gudang Proyek SQ 01', 'Gudang Proyek SQ 01', 'sys-admin', '2024-03-06');

-- --------------------------------------------------------

--
-- Struktur dari tabel `userroles`
--

CREATE TABLE `userroles` (
  `userid` int NOT NULL,
  `roleid` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `createdby` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `updatedby` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `userroles`
--

INSERT INTO `userroles` (`userid`, `roleid`, `created_at`, `updated_at`, `createdby`, `updatedby`) VALUES
(1, 1, '2022-07-26 02:19:44', NULL, 'sys-admin', ''),
(1, 6, '2022-10-04 00:10:14', NULL, 'husnulmub@gmail.com', NULL),
(1, 7, '2022-10-04 00:10:54', NULL, 'husnulmub@gmail.com', NULL),
(1, 8, '2022-10-04 00:10:26', NULL, 'husnulmub@gmail.com', NULL),
(1, 9, '2022-10-04 00:10:45', NULL, 'husnulmub@gmail.com', NULL),
(1, 53, '2023-08-01 17:08:48', NULL, 'fransiskusaditya88@gmail.com', NULL),
(1, 54, '2023-08-01 17:08:02', NULL, 'fransiskusaditya88@gmail.com', NULL),
(1, 55, '2023-08-01 17:08:51', NULL, 'fransiskusaditya88@gmail.com', NULL),
(1, 56, '2023-08-01 17:08:35', NULL, 'fransiskusaditya88@gmail.com', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `s_signfile` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `deptid` int DEFAULT NULL,
  `jabatanid` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `createdby` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `updatedby` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `username`, `email_verified_at`, `password`, `remember_token`, `s_signfile`, `deptid`, `jabatanid`, `created_at`, `updated_at`, `createdby`, `updatedby`) VALUES
(1, 'Administrator', 'husnulmub@gmail.com', 'sys-admin', NULL, '$2y$12$dp1pPPaPxBdDVG4.ddtWtuv0NxzrMkuBrwSQ0siGHwPaKA0Pq5ZC2', NULL, 'storage/files/e_signature/e-sign.png', NULL, 3, '2022-07-26 07:36:29', NULL, '', ''),
(84, 'Admin HO 1', 'ho01@mail.com', 'HO01', NULL, '$2y$12$khdxbElWu1APtYD85btBY.bowHJgj2heyPBm8HggKRxsBimshpenu', NULL, NULL, NULL, NULL, '2024-03-02 04:03:36', NULL, 'husnulmub@gmail.com', NULL),
(85, 'Purchasing HO 1', 'PURHO01@mail.com', 'PURHO01', NULL, '$2y$12$44TEyHwMEVHo.IIzfF5IAOIpuXVBQpoWBcGz9o1kO65nbVUg56oSe', NULL, NULL, NULL, NULL, '2024-03-02 04:03:27', NULL, 'husnulmub@gmail.com', NULL),
(86, 'Purhchasing Proyek SQ', 'PURSQ01@mail.com', 'PURSQ01', NULL, '$2y$12$hbtXTG8j3m1chCnmUkIB1ut6876Fdrn8VlQ0DxzjnictNYZu.q0L6', NULL, NULL, NULL, NULL, '2024-03-02 04:03:11', NULL, 'husnulmub@gmail.com', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `user_object_auth`
--

CREATE TABLE `user_object_auth` (
  `id` int NOT NULL,
  `userid` int NOT NULL,
  `object_name` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `object_val` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `createdon` date NOT NULL,
  `createdby` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `user_object_auth`
--

INSERT INTO `user_object_auth` (`id`, `userid`, `object_name`, `object_val`, `createdon`, `createdby`) VALUES
(4, 1, 'ALLOW_WAREHOUSE', '*', '2024-03-05', 'sys-admin'),
(6, 1, 'ALLOW_DOWNLOAD_DOC', 'Y', '2024-03-10', 'sys-admin'),
(7, 1, 'ALLOW_DISPLAY_PROJECT', '*', '2024-03-10', 'sys-admin'),
(12, 1, 'ALLOW_POTYPE', '*', '2024-03-16', 'sys-admin'),
(13, 1, 'ALLOW_PRTYPE', '*', '2024-03-16', 'sys-admin');

-- --------------------------------------------------------

--
-- Stand-in struktur untuk tampilan `v_approved_po`
-- (Lihat di bawah untuk tampilan aktual)
--
CREATE TABLE `v_approved_po` (
`id` int
,`ponum` varchar(60)
,`ext_ponum` varchar(25)
,`potype` varchar(15)
,`podat` date
,`vendor` varchar(10)
,`note` text
,`currency` varchar(10)
,`approvestat` varchar(10)
,`appby` varchar(50)
,`completed` varchar(1)
,`ppn` decimal(15,2)
,`tf_price` varchar(100)
,`createdon` datetime
,`createdby` varchar(50)
,`poitem` int
,`material` varchar(70)
,`matdesc` varchar(100)
,`quantity` decimal(15,3)
,`unit` varchar(10)
,`price` decimal(15,3)
,`grqty` decimal(15,3)
,`openqty` decimal(16,3)
,`prnum` varchar(60)
,`pritem` int
,`grstatus` varchar(1)
,`cscode` varchar(10)
,`cost_desc` varchar(100)
,`matspec` varchar(80)
);

-- --------------------------------------------------------

--
-- Stand-in struktur untuk tampilan `v_approved_prv2`
-- (Lihat di bawah untuk tampilan aktual)
--
CREATE TABLE `v_approved_prv2` (
`id` int
,`prnum` varchar(60)
,`typepr` varchar(10)
,`note` text
,`prdate` date
,`relgroup` varchar(10)
,`approvestat` varchar(10)
,`requestby` varchar(50)
,`warehouse` varchar(10)
,`remark` text
,`appby` varchar(50)
,`createdon` datetime
,`createdby` varchar(50)
,`pritem` int
,`material` varchar(70)
,`matdesc` varchar(100)
,`quantity` decimal(18,3)
,`unit` varchar(10)
,`pocreated` varchar(1)
,`poqty` decimal(18,3)
,`openqty` decimal(19,3)
,`last_purchase_price` decimal(18,2)
,`idproject` int
,`kode_project` varchar(60)
,`nama_project` text
,`itemtext` varchar(100)
);

-- --------------------------------------------------------

--
-- Stand-in struktur untuk tampilan `v_cost_master`
-- (Lihat di bawah untuk tampilan aktual)
--
CREATE TABLE `v_cost_master` (
`id` int
,`cost_code` varchar(10)
,`cost_desc` varchar(100)
,`cost_group` varchar(10)
,`deleted` varchar(1)
,`createdon` datetime
,`createdby` varchar(70)
,`changedon` datetime
,`changedby` varchar(70)
,`cost_group_desc` varchar(100)
);

-- --------------------------------------------------------

--
-- Stand-in struktur untuk tampilan `v_inv_batch_stock`
-- (Lihat di bawah untuk tampilan aktual)
--
CREATE TABLE `v_inv_batch_stock` (
`id` int
,`material` varchar(70)
,`matdesc` varchar(100)
,`matspec` varchar(80)
,`whsid` varchar(10)
,`whscode` varchar(10)
,`whsname` varchar(80)
,`batchnum` varchar(50)
,`quantity` decimal(18,3)
,`unit` varchar(10)
);

-- --------------------------------------------------------

--
-- Stand-in struktur untuk tampilan `v_inv_stock`
-- (Lihat di bawah untuk tampilan aktual)
--
CREATE TABLE `v_inv_stock` (
`material` varchar(70)
,`whscode` varchar(10)
,`quantity` decimal(18,3)
,`unit` varchar(10)
,`whsnum` varchar(10)
,`whsname` varchar(80)
,`matdesc` varchar(100)
,`matspec` varchar(80)
,`partnumber` varchar(50)
);

-- --------------------------------------------------------

--
-- Stand-in struktur untuk tampilan `v_inv_summary_stock`
-- (Lihat di bawah untuk tampilan aktual)
--
CREATE TABLE `v_inv_summary_stock` (
`rnum` bigint unsigned
,`id` int
,`material` varchar(70)
,`matdesc` varchar(100)
,`whsid` int
,`whscode` varchar(10)
,`whsname` varchar(80)
,`quantity` decimal(40,3)
,`unit` varchar(10)
);

-- --------------------------------------------------------

--
-- Stand-in struktur untuk tampilan `v_material`
-- (Lihat di bawah untuk tampilan aktual)
--
CREATE TABLE `v_material` (
`id` int
,`material` varchar(70)
,`matdesc` varchar(100)
,`mattype` varchar(20)
,`matgroup` varchar(20)
,`matspec` varchar(80)
,`partname` varchar(50)
,`partnumber` varchar(50)
,`color` varchar(50)
,`size` varchar(30)
,`matunit` varchar(15)
,`minstock` decimal(15,2)
,`orderunit` varchar(20)
,`stdprice` decimal(15,2)
,`stdpriceusd` decimal(15,4)
,`active` tinyint(1)
,`matuniqid` varchar(20)
,`createdon` datetime
,`createdby` varchar(50)
,`mattypedesc` varchar(50)
,`last_purchase_price` decimal(18,2)
);

-- --------------------------------------------------------

--
-- Stand-in struktur untuk tampilan `v_menuroles`
-- (Lihat di bawah untuk tampilan aktual)
--
CREATE TABLE `v_menuroles` (
`id` bigint unsigned
,`menuid` int
,`roleid` int
,`rolename` varchar(50)
,`name` varchar(100)
,`menugroup` int
,`group` varchar(50)
);

-- --------------------------------------------------------

--
-- Stand-in struktur untuk tampilan `v_menus`
-- (Lihat di bawah untuk tampilan aktual)
--
CREATE TABLE `v_menus` (
`id` bigint unsigned
,`name` varchar(100)
,`route` varchar(100)
,`menugroup` int
,`menu_idx` int
,`icon` varchar(255)
,`created_at` timestamp
,`updated_at` timestamp
,`createdby` varchar(50)
,`updatedby` varchar(50)
,`groupname` varchar(50)
);

-- --------------------------------------------------------

--
-- Stand-in struktur untuk tampilan `v_po01`
-- (Lihat di bawah untuk tampilan aktual)
--
CREATE TABLE `v_po01` (
`id` int
,`ponum` varchar(60)
,`ext_ponum` varchar(25)
,`potype` varchar(15)
,`podat` date
,`delivery_date` date
,`vendor` varchar(10)
,`note` text
,`idproject` int
,`currency` varchar(10)
,`approvestat` varchar(10)
,`appby` varchar(50)
,`completed` varchar(1)
,`ppn` decimal(15,2)
,`tf_price` varchar(100)
,`tf_dest` varchar(100)
,`tf_shipment` varchar(100)
,`tf_top` varchar(100)
,`tf_packing` varchar(100)
,`tf_shipdate` date
,`submitted` varchar(1)
,`is_posolar` varchar(1)
,`solar_pbbkb` double
,`solar_oat` double
,`solar_ppn_oat` double
,`createdon` datetime
,`createdby` varchar(50)
,`changedon` datetime
,`changedby` varchar(60)
,`kode_project` varchar(60)
,`nama_project` text
,`vendor_name` varchar(80)
,`vendor_code` varchar(12)
,`vendor_address` text
,`dlv_terms` varchar(80)
);

-- --------------------------------------------------------

--
-- Stand-in struktur untuk tampilan `v_po02`
-- (Lihat di bawah untuk tampilan aktual)
--
CREATE TABLE `v_po02` (
`ponum` varchar(60)
,`poitem` int
,`material` varchar(70)
,`matdesc` varchar(100)
,`quantity` decimal(15,3)
,`unit` varchar(10)
,`price` decimal(15,3)
,`grqty` decimal(15,3)
,`prnum` varchar(60)
,`pritem` int
,`grstatus` varchar(1)
,`pocomplete` varchar(1)
,`approvestat` varchar(1)
,`createdon` date
,`createdby` varchar(50)
,`totalprice` decimal(30,6)
);

-- --------------------------------------------------------

--
-- Stand-in struktur untuk tampilan `v_po_approval`
-- (Lihat di bawah untuk tampilan aktual)
--
CREATE TABLE `v_po_approval` (
`id` int
,`ponum` varchar(60)
,`ext_ponum` varchar(25)
,`potype` varchar(15)
,`podat` date
,`vendor` varchar(10)
,`vendor_code` varchar(12)
,`vendor_name` varchar(80)
,`note` text
,`currency` varchar(10)
,`approvestat` varchar(10)
,`appby` varchar(50)
,`completed` varchar(1)
,`ppn` decimal(15,2)
,`tf_price` varchar(100)
,`tf_dest` varchar(100)
,`tf_shipment` varchar(100)
,`tf_top` varchar(100)
,`tf_packing` varchar(100)
,`tf_shipdate` date
,`createdon` datetime
,`createdby` varchar(50)
,`requester` int
,`approver` int
,`approver_level` int
,`is_active` varchar(1)
,`approval_status` varchar(1)
,`approval_remark` text
,`approval_date` datetime
,`approved_by` varchar(50)
,`approver_name` varchar(100)
,`requester_name` varchar(100)
);

-- --------------------------------------------------------

--
-- Stand-in struktur untuk tampilan `v_pr01`
-- (Lihat di bawah untuk tampilan aktual)
--
CREATE TABLE `v_pr01` (
`id` int
,`prnum` varchar(60)
,`typepr` varchar(10)
,`note` text
,`prdate` date
,`relgroup` varchar(10)
,`approvestat` varchar(10)
,`requestby` varchar(50)
,`warehouse` varchar(10)
,`idproject` int
,`remark` text
,`appby` varchar(50)
,`createdon` datetime
,`changedon` datetime
,`createdby` varchar(50)
,`changedby` varchar(50)
,`pritem` int
,`material` varchar(70)
,`matdesc` varchar(100)
,`matspec` varchar(80)
,`quantity` decimal(18,3)
,`unit` varchar(10)
,`pocreated` varchar(1)
,`itemtext` varchar(100)
,`kode_project` varchar(60)
,`nama_project` text
,`nilai_project` varchar(50)
,`status_project` varchar(1)
);

-- --------------------------------------------------------

--
-- Stand-in struktur untuk tampilan `v_pr_approval01`
-- (Lihat di bawah untuk tampilan aktual)
--
CREATE TABLE `v_pr_approval01` (
`id` int
,`prnum` varchar(60)
,`pritem` int
,`typepr` varchar(10)
,`note` text
,`prdate` date
,`relgroup` varchar(10)
,`approvestat` varchar(10)
,`requestby` varchar(50)
,`warehouse` varchar(10)
,`idproject` int
,`kode_project` varchar(60)
,`nama_project` text
,`project_manager` varchar(80)
,`remark` text
,`appby` varchar(50)
,`createdon` datetime
,`createdby` varchar(50)
,`requester` int
,`approver` int
,`approver_level` int
,`is_active` varchar(1)
,`approval_status` varchar(1)
,`approval_remark` text
,`approval_date` datetime
,`approved_by` varchar(50)
,`approver_name` varchar(100)
,`requester_name` varchar(100)
);

-- --------------------------------------------------------

--
-- Stand-in struktur untuk tampilan `v_rgrpo`
-- (Lihat di bawah untuk tampilan aktual)
--
CREATE TABLE `v_rgrpo` (
`id` int
,`docnum` varchar(30)
,`docyear` int
,`docdate` date
,`postdate` date
,`received_by` varchar(50)
,`movement_code` varchar(10)
,`remark` text
,`createdby` varchar(50)
,`createdon` datetime
,`docitem` int
,`material` varchar(50)
,`matdesc` varchar(80)
,`matspec` varchar(80)
,`quantity` decimal(15,3)
,`unit` varchar(10)
,`unit_price` double
,`total_price` double
,`ponum` varchar(30)
,`poitem` int
,`whscode` varchar(10)
,`whsname` varchar(80)
,`vendor_name` varchar(80)
);

-- --------------------------------------------------------

--
-- Stand-in struktur untuk tampilan `v_rpo01`
-- (Lihat di bawah untuk tampilan aktual)
--
CREATE TABLE `v_rpo01` (
`id` int
,`ponum` varchar(60)
,`poitem` int
,`potype` varchar(15)
,`podat` date
,`delivery_date` date
,`vendor` varchar(10)
,`note` text
,`approvestat` varchar(10)
,`ppn` decimal(15,2)
,`kode_project` varchar(60)
,`nama_project` text
,`vendor_name` varchar(80)
,`material` varchar(70)
,`matdesc` varchar(100)
,`quantity` decimal(15,3)
,`unit` varchar(10)
,`price` decimal(15,3)
,`grqty` decimal(15,3)
,`prnum` varchar(60)
,`grstatus` varchar(1)
,`pocomplete` varchar(1)
,`totalprice` decimal(30,6)
,`createdby` varchar(50)
,`createdon` datetime
,`openqty` decimal(16,3)
);

-- --------------------------------------------------------

--
-- Stand-in struktur untuk tampilan `v_usermenus`
-- (Lihat di bawah untuk tampilan aktual)
--
CREATE TABLE `v_usermenus` (
`id` bigint unsigned
,`menu_desc` varchar(100)
,`route` varchar(100)
,`menugroup` int
,`menu_idx` int
,`groupname` varchar(50)
,`groupicon` varchar(50)
,`group_idx` int
,`roleid` int
,`rolename` varchar(50)
,`userid` int
,`name_of_user` varchar(100)
,`email` varchar(100)
,`username` varchar(100)
,`icon` varchar(255)
);

-- --------------------------------------------------------

--
-- Stand-in struktur untuk tampilan `v_userroles`
-- (Lihat di bawah untuk tampilan aktual)
--
CREATE TABLE `v_userroles` (
`roleid` int
,`rolename` varchar(50)
,`userid` int
,`name` varchar(100)
,`email` varchar(100)
,`username` varchar(100)
);

-- --------------------------------------------------------

--
-- Stand-in struktur untuk tampilan `v_users`
-- (Lihat di bawah untuk tampilan aktual)
--
CREATE TABLE `v_users` (
`id` bigint unsigned
,`name` varchar(100)
,`email` varchar(100)
,`username` varchar(100)
,`email_verified_at` timestamp
,`password` varchar(100)
,`remember_token` varchar(100)
,`s_signfile` text
,`deptid` int
,`jabatanid` int
,`created_at` timestamp
,`updated_at` timestamp
,`createdby` varchar(50)
,`updatedby` varchar(50)
,`department` varchar(80)
,`jabatan` varchar(50)
);

-- --------------------------------------------------------

--
-- Stand-in struktur untuk tampilan `v_user_obj_auth`
-- (Lihat di bawah untuk tampilan aktual)
--
CREATE TABLE `v_user_obj_auth` (
`userid` int
,`object_name` varchar(30)
,`object_val` varchar(10)
,`createdon` date
,`createdby` varchar(50)
,`object_description` varchar(80)
);

-- --------------------------------------------------------

--
-- Stand-in struktur untuk tampilan `v_workflows`
-- (Lihat di bawah untuk tampilan aktual)
--
CREATE TABLE `v_workflows` (
`id` int
,`object` varchar(50)
,`requester` int
,`approver` int
,`approver_level` int
,`createdby` varchar(50)
,`createdon` date
,`requester_name` varchar(100)
,`approver_name` varchar(100)
);

-- --------------------------------------------------------

--
-- Stand-in struktur untuk tampilan `v_workflow_assignments`
-- (Lihat di bawah untuk tampilan aktual)
--
CREATE TABLE `v_workflow_assignments` (
`workflow_group` int
,`wf_groupname` varchar(50)
,`approval_level` int
,`workflow_categories` int
,`wf_categoryname` varchar(50)
,`creator` varchar(100)
,`approver` varchar(100)
,`creatorid` int
,`approverid` int
,`approver_email` varchar(100)
);

-- --------------------------------------------------------

--
-- Struktur dari tabel `workflows`
--

CREATE TABLE `workflows` (
  `id` int NOT NULL,
  `object` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `requester` int NOT NULL,
  `approver` int NOT NULL,
  `approver_level` int NOT NULL,
  `createdby` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `createdon` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `workflows`
--

INSERT INTO `workflows` (`id`, `object`, `requester`, `approver`, `approver_level`, `createdby`, `createdon`) VALUES
(1, 'PR', 1, 1, 1, 'husnulmub@gmail.com', '2024-03-05'),
(2, 'PR', 84, 1, 1, 'husnulmub@gmail.com', '2024-03-05'),
(3, 'PO', 1, 1, 1, 'husnulmub@gmail.com', '2024-03-05');

-- --------------------------------------------------------

--
-- Struktur dari tabel `workflow_assignments`
--

CREATE TABLE `workflow_assignments` (
  `workflow_group` int NOT NULL,
  `approval_level` int NOT NULL,
  `workflow_categories` int NOT NULL,
  `creator` int NOT NULL,
  `approver` int NOT NULL,
  `createdon` datetime NOT NULL,
  `createdby` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `workflow_categories`
--

CREATE TABLE `workflow_categories` (
  `id` int NOT NULL,
  `workflow_category` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `createdon` datetime NOT NULL,
  `createdby` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `workflow_groups`
--

CREATE TABLE `workflow_groups` (
  `id` int NOT NULL,
  `workflow_group` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `createdon` datetime DEFAULT NULL,
  `createdby` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur untuk view `v_approved_po`
--
DROP TABLE IF EXISTS `v_approved_po`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_approved_po`  AS SELECT `a`.`id` AS `id`, `a`.`ponum` AS `ponum`, `a`.`ext_ponum` AS `ext_ponum`, `a`.`potype` AS `potype`, `a`.`podat` AS `podat`, `a`.`vendor` AS `vendor`, `a`.`note` AS `note`, `a`.`currency` AS `currency`, `a`.`approvestat` AS `approvestat`, `a`.`appby` AS `appby`, `a`.`completed` AS `completed`, `a`.`ppn` AS `ppn`, `a`.`tf_price` AS `tf_price`, `a`.`createdon` AS `createdon`, `a`.`createdby` AS `createdby`, `b`.`poitem` AS `poitem`, `b`.`material` AS `material`, `b`.`matdesc` AS `matdesc`, `b`.`quantity` AS `quantity`, `b`.`unit` AS `unit`, `b`.`price` AS `price`, `b`.`grqty` AS `grqty`, (`b`.`quantity` - `b`.`grqty`) AS `openqty`, `b`.`prnum` AS `prnum`, `b`.`pritem` AS `pritem`, `b`.`grstatus` AS `grstatus`, `c`.`cost_code` AS `cscode`, `c`.`cost_desc` AS `cost_desc`, `d`.`matspec` AS `matspec` FROM (((`v_po01` `a` join `t_po02` `b` on((`a`.`ponum` = `b`.`ponum`))) join `v_cost_master` `c` on((`b`.`cost_code` = `c`.`id`))) join `t_material` `d` on((`b`.`material` = `d`.`material`))) WHERE ((`a`.`approvestat` = 'A') AND (`b`.`grstatus` = 'O')) ;

-- --------------------------------------------------------

--
-- Struktur untuk view `v_approved_prv2`
--
DROP TABLE IF EXISTS `v_approved_prv2`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_approved_prv2`  AS SELECT `a`.`id` AS `id`, `a`.`prnum` AS `prnum`, `a`.`typepr` AS `typepr`, `a`.`note` AS `note`, `a`.`prdate` AS `prdate`, `a`.`relgroup` AS `relgroup`, `b`.`approvestat` AS `approvestat`, `a`.`requestby` AS `requestby`, `a`.`warehouse` AS `warehouse`, `a`.`remark` AS `remark`, `a`.`appby` AS `appby`, `a`.`createdon` AS `createdon`, `a`.`createdby` AS `createdby`, `b`.`pritem` AS `pritem`, `b`.`material` AS `material`, `b`.`matdesc` AS `matdesc`, `b`.`quantity` AS `quantity`, `b`.`unit` AS `unit`, `b`.`pocreated` AS `pocreated`, `fGetQuantityCreatedPRItem`(`a`.`prnum`,`b`.`pritem`) AS `poqty`, (`b`.`quantity` - `fGetQuantityCreatedPRItem`(`a`.`prnum`,`b`.`pritem`)) AS `openqty`, `c`.`last_purchase_price` AS `last_purchase_price`, `b`.`idproject` AS `idproject`, `d`.`kode_project` AS `kode_project`, `d`.`nama_project` AS `nama_project`, `b`.`remark` AS `itemtext` FROM (((`t_pr01` `a` join `t_pr02` `b` on((`a`.`prnum` = `b`.`prnum`))) join `t_material` `c` on((`b`.`material` = `c`.`material`))) join `t_projects` `d` on((`a`.`idproject` = `d`.`id`))) WHERE (`a`.`approvestat` = 'A') ;

-- --------------------------------------------------------

--
-- Struktur untuk view `v_cost_master`
--
DROP TABLE IF EXISTS `v_cost_master`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_cost_master`  AS SELECT `a`.`id` AS `id`, `a`.`cost_code` AS `cost_code`, `a`.`cost_desc` AS `cost_desc`, `a`.`cost_group` AS `cost_group`, `a`.`deleted` AS `deleted`, `a`.`createdon` AS `createdon`, `a`.`createdby` AS `createdby`, `a`.`changedon` AS `changedon`, `a`.`changedby` AS `changedby`, `b`.`cost_group_desc` AS `cost_group_desc` FROM (`t_cost_code_master` `a` join `t_cost_group` `b` on((`a`.`cost_group` = `b`.`cost_group`))) ;

-- --------------------------------------------------------

--
-- Struktur untuk view `v_inv_batch_stock`
--
DROP TABLE IF EXISTS `v_inv_batch_stock`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_inv_batch_stock`  AS SELECT `b`.`id` AS `id`, `a`.`material` AS `material`, `b`.`matdesc` AS `matdesc`, `b`.`matspec` AS `matspec`, `a`.`whscode` AS `whsid`, `c`.`whscode` AS `whscode`, `c`.`whsname` AS `whsname`, `a`.`batchnum` AS `batchnum`, `a`.`quantity` AS `quantity`, `a`.`unit` AS `unit` FROM ((`t_inv_stock` `a` join `t_material` `b` on((`a`.`material` = `b`.`material`))) join `t_warehouse` `c` on((`a`.`whscode` = `c`.`id`))) ;

-- --------------------------------------------------------

--
-- Struktur untuk view `v_inv_stock`
--
DROP TABLE IF EXISTS `v_inv_stock`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_inv_stock`  AS SELECT `a`.`material` AS `material`, `a`.`whscode` AS `whscode`, `a`.`quantity` AS `quantity`, `a`.`unit` AS `unit`, `c`.`whscode` AS `whsnum`, `c`.`whsname` AS `whsname`, `b`.`matdesc` AS `matdesc`, `b`.`matspec` AS `matspec`, `b`.`partnumber` AS `partnumber` FROM ((`t_inv_stock` `a` join `t_material` `b` on((`a`.`material` = `b`.`material`))) join `t_warehouse` `c` on((`a`.`whscode` = `c`.`id`))) ;

-- --------------------------------------------------------

--
-- Struktur untuk view `v_inv_summary_stock`
--
DROP TABLE IF EXISTS `v_inv_summary_stock`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_inv_summary_stock`  AS SELECT row_number()  (ORDER BY `b`.`id` ) AS `OVER` FROM ((`t_inv_stock` `a` join `t_material` `b` on((`a`.`material` = `b`.`material`))) join `t_warehouse` `c` on((`a`.`whscode` = `c`.`id`))) GROUP BY `b`.`id`, `a`.`material`, `b`.`matdesc`, `c`.`id`, `c`.`whscode`, `c`.`whsname`, `a`.`unit` ;

-- --------------------------------------------------------

--
-- Struktur untuk view `v_material`
--
DROP TABLE IF EXISTS `v_material`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_material`  AS SELECT `a`.`id` AS `id`, `a`.`material` AS `material`, `a`.`matdesc` AS `matdesc`, `a`.`mattype` AS `mattype`, `a`.`matgroup` AS `matgroup`, `a`.`matspec` AS `matspec`, `a`.`partname` AS `partname`, `a`.`partnumber` AS `partnumber`, `a`.`color` AS `color`, `a`.`size` AS `size`, `a`.`matunit` AS `matunit`, `a`.`minstock` AS `minstock`, `a`.`orderunit` AS `orderunit`, `a`.`stdprice` AS `stdprice`, `a`.`stdpriceusd` AS `stdpriceusd`, `a`.`active` AS `active`, `a`.`matuniqid` AS `matuniqid`, `a`.`createdon` AS `createdon`, `a`.`createdby` AS `createdby`, `b`.`mattypedesc` AS `mattypedesc`, `a`.`last_purchase_price` AS `last_purchase_price` FROM (`t_material` `a` join `t_materialtype` `b` on((`a`.`mattype` = `b`.`id`))) ;

-- --------------------------------------------------------

--
-- Struktur untuk view `v_menuroles`
--
DROP TABLE IF EXISTS `v_menuroles`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_menuroles`  AS SELECT `c`.`id` AS `id`, `a`.`menuid` AS `menuid`, `a`.`roleid` AS `roleid`, `c`.`rolename` AS `rolename`, `b`.`name` AS `name`, `b`.`menugroup` AS `menugroup`, `d`.`menugroup` AS `group` FROM (((`menuroles` `a` join `menus` `b` on((`a`.`menuid` = `b`.`id`))) join `roles` `c` on((`a`.`roleid` = `c`.`id`))) left join `menugroups` `d` on((`b`.`menugroup` = `d`.`id`))) ORDER BY `a`.`menuid` ASC ;

-- --------------------------------------------------------

--
-- Struktur untuk view `v_menus`
--
DROP TABLE IF EXISTS `v_menus`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_menus`  AS SELECT `a`.`id` AS `id`, `a`.`name` AS `name`, `a`.`route` AS `route`, `a`.`menugroup` AS `menugroup`, `a`.`menu_idx` AS `menu_idx`, `a`.`icon` AS `icon`, `a`.`created_at` AS `created_at`, `a`.`updated_at` AS `updated_at`, `a`.`createdby` AS `createdby`, `a`.`updatedby` AS `updatedby`, `b`.`menugroup` AS `groupname` FROM (`menus` `a` join `menugroups` `b` on((`a`.`menugroup` = `b`.`id`))) ORDER BY `a`.`menugroup` ASC, `a`.`menu_idx` ASC ;

-- --------------------------------------------------------

--
-- Struktur untuk view `v_po01`
--
DROP TABLE IF EXISTS `v_po01`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_po01`  AS SELECT `a`.`id` AS `id`, `a`.`ponum` AS `ponum`, `a`.`ext_ponum` AS `ext_ponum`, `a`.`potype` AS `potype`, `a`.`podat` AS `podat`, `a`.`delivery_date` AS `delivery_date`, `a`.`vendor` AS `vendor`, `a`.`note` AS `note`, `a`.`idproject` AS `idproject`, `a`.`currency` AS `currency`, `a`.`approvestat` AS `approvestat`, `a`.`appby` AS `appby`, `a`.`completed` AS `completed`, `a`.`ppn` AS `ppn`, `a`.`tf_price` AS `tf_price`, `a`.`tf_dest` AS `tf_dest`, `a`.`tf_shipment` AS `tf_shipment`, `a`.`tf_top` AS `tf_top`, `a`.`tf_packing` AS `tf_packing`, `a`.`tf_shipdate` AS `tf_shipdate`, `a`.`submitted` AS `submitted`, `a`.`is_posolar` AS `is_posolar`, `a`.`solar_pbbkb` AS `solar_pbbkb`, `a`.`solar_oat` AS `solar_oat`, `a`.`solar_ppn_oat` AS `solar_ppn_oat`, `a`.`createdon` AS `createdon`, `a`.`createdby` AS `createdby`, `a`.`changedon` AS `changedon`, `a`.`changedby` AS `changedby`, `b`.`kode_project` AS `kode_project`, `b`.`nama_project` AS `nama_project`, `c`.`vendor_name` AS `vendor_name`, `c`.`vendor_code` AS `vendor_code`, `c`.`vendor_address` AS `vendor_address`, `a`.`dlv_terms` AS `dlv_terms` FROM ((`t_po01` `a` join `t_projects` `b` on((`a`.`idproject` = `b`.`id`))) join `t_vendor` `c` on((`a`.`vendor` = `c`.`id`))) ;

-- --------------------------------------------------------

--
-- Struktur untuk view `v_po02`
--
DROP TABLE IF EXISTS `v_po02`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_po02`  AS SELECT `t_po02`.`ponum` AS `ponum`, `t_po02`.`poitem` AS `poitem`, `t_po02`.`material` AS `material`, `t_po02`.`matdesc` AS `matdesc`, `t_po02`.`quantity` AS `quantity`, `t_po02`.`unit` AS `unit`, `t_po02`.`price` AS `price`, `t_po02`.`grqty` AS `grqty`, `t_po02`.`prnum` AS `prnum`, `t_po02`.`pritem` AS `pritem`, `t_po02`.`grstatus` AS `grstatus`, `t_po02`.`pocomplete` AS `pocomplete`, `t_po02`.`approvestat` AS `approvestat`, `t_po02`.`createdon` AS `createdon`, `t_po02`.`createdby` AS `createdby`, (`t_po02`.`price` * `t_po02`.`quantity`) AS `totalprice` FROM `t_po02` ;

-- --------------------------------------------------------

--
-- Struktur untuk view `v_po_approval`
--
DROP TABLE IF EXISTS `v_po_approval`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_po_approval`  AS SELECT `a`.`id` AS `id`, `a`.`ponum` AS `ponum`, `a`.`ext_ponum` AS `ext_ponum`, `a`.`potype` AS `potype`, `a`.`podat` AS `podat`, `a`.`vendor` AS `vendor`, `c`.`vendor_code` AS `vendor_code`, `c`.`vendor_name` AS `vendor_name`, `a`.`note` AS `note`, `a`.`currency` AS `currency`, `a`.`approvestat` AS `approvestat`, `a`.`appby` AS `appby`, `a`.`completed` AS `completed`, `a`.`ppn` AS `ppn`, `a`.`tf_price` AS `tf_price`, `a`.`tf_dest` AS `tf_dest`, `a`.`tf_shipment` AS `tf_shipment`, `a`.`tf_top` AS `tf_top`, `a`.`tf_packing` AS `tf_packing`, `a`.`tf_shipdate` AS `tf_shipdate`, `a`.`createdon` AS `createdon`, `a`.`createdby` AS `createdby`, `b`.`requester` AS `requester`, `b`.`approver` AS `approver`, `b`.`approver_level` AS `approver_level`, `b`.`is_active` AS `is_active`, `b`.`approval_status` AS `approval_status`, `b`.`approval_remark` AS `approval_remark`, `b`.`approval_date` AS `approval_date`, `b`.`approved_by` AS `approved_by`, `fGetUserName`(`b`.`approver`) AS `approver_name`, `fGetUserName`(`b`.`requester`) AS `requester_name` FROM ((`t_po01` `a` join `t_po_approval` `b` on((`a`.`ponum` = `b`.`ponum`))) join `t_vendor` `c` on((`a`.`vendor` = `c`.`id`))) ;

-- --------------------------------------------------------

--
-- Struktur untuk view `v_pr01`
--
DROP TABLE IF EXISTS `v_pr01`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_pr01`  AS SELECT `a`.`id` AS `id`, `a`.`prnum` AS `prnum`, `a`.`typepr` AS `typepr`, `a`.`note` AS `note`, `a`.`prdate` AS `prdate`, `a`.`relgroup` AS `relgroup`, `a`.`approvestat` AS `approvestat`, `a`.`requestby` AS `requestby`, `a`.`warehouse` AS `warehouse`, `a`.`idproject` AS `idproject`, `a`.`remark` AS `remark`, `a`.`appby` AS `appby`, `a`.`createdon` AS `createdon`, `a`.`changedon` AS `changedon`, `a`.`createdby` AS `createdby`, `a`.`changedby` AS `changedby`, `b`.`pritem` AS `pritem`, `b`.`material` AS `material`, `d`.`matdesc` AS `matdesc`, `d`.`matspec` AS `matspec`, `b`.`quantity` AS `quantity`, `b`.`unit` AS `unit`, `b`.`pocreated` AS `pocreated`, `b`.`remark` AS `itemtext`, `c`.`kode_project` AS `kode_project`, `c`.`nama_project` AS `nama_project`, `c`.`nilai_project` AS `nilai_project`, `c`.`status_project` AS `status_project` FROM (((`t_pr01` `a` join `t_pr02` `b` on((`a`.`prnum` = `b`.`prnum`))) join `t_projects` `c` on((`a`.`idproject` = `c`.`id`))) join `t_material` `d` on((`b`.`material` = `d`.`material`))) ;

-- --------------------------------------------------------

--
-- Struktur untuk view `v_pr_approval01`
--
DROP TABLE IF EXISTS `v_pr_approval01`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_pr_approval01`  AS SELECT `a`.`id` AS `id`, `a`.`prnum` AS `prnum`, `b`.`pritem` AS `pritem`, `a`.`typepr` AS `typepr`, `a`.`note` AS `note`, `a`.`prdate` AS `prdate`, `a`.`relgroup` AS `relgroup`, `a`.`approvestat` AS `approvestat`, `a`.`requestby` AS `requestby`, `a`.`warehouse` AS `warehouse`, `a`.`idproject` AS `idproject`, `c`.`kode_project` AS `kode_project`, `c`.`nama_project` AS `nama_project`, `c`.`project_manager` AS `project_manager`, `a`.`remark` AS `remark`, `a`.`appby` AS `appby`, `a`.`createdon` AS `createdon`, `a`.`createdby` AS `createdby`, `b`.`requester` AS `requester`, `b`.`approver` AS `approver`, `b`.`approver_level` AS `approver_level`, `b`.`is_active` AS `is_active`, `b`.`approval_status` AS `approval_status`, `b`.`approval_remark` AS `approval_remark`, `b`.`approval_date` AS `approval_date`, `b`.`approved_by` AS `approved_by`, `fGetUserName`(`b`.`approver`) AS `approver_name`, `fGetUserName`(`b`.`requester`) AS `requester_name` FROM ((`t_pr01` `a` join `t_pr_approval` `b` on((`a`.`prnum` = `b`.`prnum`))) join `t_projects` `c` on((`a`.`idproject` = `c`.`id`))) ;

-- --------------------------------------------------------

--
-- Struktur untuk view `v_rgrpo`
--
DROP TABLE IF EXISTS `v_rgrpo`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_rgrpo`  AS SELECT `b`.`id` AS `id`, `a`.`docnum` AS `docnum`, `a`.`docyear` AS `docyear`, `a`.`docdate` AS `docdate`, `a`.`postdate` AS `postdate`, `a`.`received_by` AS `received_by`, `a`.`movement_code` AS `movement_code`, `a`.`remark` AS `remark`, `a`.`createdby` AS `createdby`, `a`.`createdon` AS `createdon`, `b`.`docitem` AS `docitem`, `b`.`material` AS `material`, `b`.`matdesc` AS `matdesc`, `g`.`matspec` AS `matspec`, `b`.`quantity` AS `quantity`, `b`.`unit` AS `unit`, `b`.`unit_price` AS `unit_price`, `b`.`total_price` AS `total_price`, `b`.`ponum` AS `ponum`, `b`.`poitem` AS `poitem`, `b`.`whscode` AS `whscode`, `d`.`whsname` AS `whsname`, `f`.`vendor_name` AS `vendor_name` FROM ((((`t_inv01` `a` join `t_inv02` `b` on(((`a`.`docnum` = `b`.`docnum`) and (`a`.`docyear` = `b`.`docyear`)))) join `t_warehouse` `d` on((`b`.`whscode` = `d`.`id`))) join `v_po01` `f` on((`b`.`ponum` = `f`.`ponum`))) join `t_material` `g` on((`b`.`material` = `g`.`material`))) WHERE (`a`.`movement_code` = '101') ORDER BY `b`.`id` ASC ;

-- --------------------------------------------------------

--
-- Struktur untuk view `v_rpo01`
--
DROP TABLE IF EXISTS `v_rpo01`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_rpo01`  AS SELECT `a`.`id` AS `id`, `a`.`ponum` AS `ponum`, `b`.`poitem` AS `poitem`, `a`.`potype` AS `potype`, `a`.`podat` AS `podat`, `a`.`delivery_date` AS `delivery_date`, `a`.`vendor` AS `vendor`, `a`.`note` AS `note`, `a`.`approvestat` AS `approvestat`, `a`.`ppn` AS `ppn`, `a`.`kode_project` AS `kode_project`, `a`.`nama_project` AS `nama_project`, `a`.`vendor_name` AS `vendor_name`, `b`.`material` AS `material`, `b`.`matdesc` AS `matdesc`, `b`.`quantity` AS `quantity`, `b`.`unit` AS `unit`, `b`.`price` AS `price`, `b`.`grqty` AS `grqty`, `b`.`prnum` AS `prnum`, `b`.`grstatus` AS `grstatus`, `b`.`pocomplete` AS `pocomplete`, `b`.`totalprice` AS `totalprice`, `a`.`createdby` AS `createdby`, `a`.`createdon` AS `createdon`, (`b`.`quantity` - `b`.`grqty`) AS `openqty` FROM (`v_po01` `a` join `v_po02` `b` on((`a`.`ponum` = `b`.`ponum`))) ;

-- --------------------------------------------------------

--
-- Struktur untuk view `v_usermenus`
--
DROP TABLE IF EXISTS `v_usermenus`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_usermenus`  AS SELECT `a`.`id` AS `id`, `a`.`name` AS `menu_desc`, `a`.`route` AS `route`, `a`.`menugroup` AS `menugroup`, `a`.`menu_idx` AS `menu_idx`, `g`.`menugroup` AS `groupname`, `g`.`groupicon` AS `groupicon`, `g`.`_index` AS `group_idx`, `b`.`roleid` AS `roleid`, `c`.`rolename` AS `rolename`, `d`.`userid` AS `userid`, `f`.`name` AS `name_of_user`, `f`.`email` AS `email`, `f`.`username` AS `username`, `a`.`icon` AS `icon` FROM (((((`menus` `a` join `menuroles` `b` on((`a`.`id` = `b`.`menuid`))) join `roles` `c` on((`b`.`roleid` = `c`.`id`))) join `userroles` `d` on((`b`.`roleid` = `d`.`roleid`))) join `users` `f` on((`d`.`userid` = `f`.`id`))) left join `menugroups` `g` on((`a`.`menugroup` = `g`.`id`))) ORDER BY `a`.`menu_idx` ASC, `g`.`_index` ASC ;

-- --------------------------------------------------------

--
-- Struktur untuk view `v_userroles`
--
DROP TABLE IF EXISTS `v_userroles`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_userroles`  AS SELECT `a`.`roleid` AS `roleid`, `c`.`rolename` AS `rolename`, `a`.`userid` AS `userid`, `b`.`name` AS `name`, `b`.`email` AS `email`, `b`.`username` AS `username` FROM ((`userroles` `a` join `users` `b` on((`a`.`userid` = `b`.`id`))) join `roles` `c` on((`a`.`roleid` = `c`.`id`))) ORDER BY `c`.`id` ASC ;

-- --------------------------------------------------------

--
-- Struktur untuk view `v_users`
--
DROP TABLE IF EXISTS `v_users`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_users`  AS SELECT `a`.`id` AS `id`, `a`.`name` AS `name`, `a`.`email` AS `email`, `a`.`username` AS `username`, `a`.`email_verified_at` AS `email_verified_at`, `a`.`password` AS `password`, `a`.`remember_token` AS `remember_token`, `a`.`s_signfile` AS `s_signfile`, `a`.`deptid` AS `deptid`, `a`.`jabatanid` AS `jabatanid`, `a`.`created_at` AS `created_at`, `a`.`updated_at` AS `updated_at`, `a`.`createdby` AS `createdby`, `a`.`updatedby` AS `updatedby`, `b`.`department` AS `department`, `c`.`jabatan` AS `jabatan` FROM ((`users` `a` left join `t_department` `b` on((`a`.`deptid` = `b`.`deptid`))) left join `t_jabatan` `c` on((`a`.`jabatanid` = `c`.`id`))) ;

-- --------------------------------------------------------

--
-- Struktur untuk view `v_user_obj_auth`
--
DROP TABLE IF EXISTS `v_user_obj_auth`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_user_obj_auth`  AS SELECT `a`.`userid` AS `userid`, `a`.`object_name` AS `object_name`, `a`.`object_val` AS `object_val`, `a`.`createdon` AS `createdon`, `a`.`createdby` AS `createdby`, `b`.`object_description` AS `object_description` FROM (`user_object_auth` `a` join `object_auth` `b` on((`a`.`object_name` = `b`.`object_name`))) ;

-- --------------------------------------------------------

--
-- Struktur untuk view `v_workflows`
--
DROP TABLE IF EXISTS `v_workflows`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_workflows`  AS SELECT `workflows`.`id` AS `id`, `workflows`.`object` AS `object`, `workflows`.`requester` AS `requester`, `workflows`.`approver` AS `approver`, `workflows`.`approver_level` AS `approver_level`, `workflows`.`createdby` AS `createdby`, `workflows`.`createdon` AS `createdon`, `fGetUserName`(`workflows`.`requester`) AS `requester_name`, `fGetUserName`(`workflows`.`approver`) AS `approver_name` FROM `workflows` ;

-- --------------------------------------------------------

--
-- Struktur untuk view `v_workflow_assignments`
--
DROP TABLE IF EXISTS `v_workflow_assignments`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_workflow_assignments`  AS SELECT `a`.`workflow_group` AS `workflow_group`, `b`.`workflow_group` AS `wf_groupname`, `a`.`approval_level` AS `approval_level`, `a`.`workflow_categories` AS `workflow_categories`, `c`.`workflow_category` AS `wf_categoryname`, `fGetUserName`(`a`.`creator`) AS `creator`, `fGetUserName`(`a`.`approver`) AS `approver`, `a`.`creator` AS `creatorid`, `a`.`approver` AS `approverid`, `fGetEmail`(`a`.`approver`) AS `approver_email` FROM ((`workflow_assignments` `a` join `workflow_groups` `b` on((`a`.`workflow_group` = `b`.`id`))) join `workflow_categories` `c` on((`a`.`workflow_categories` = `c`.`id`))) ORDER BY `a`.`workflow_group` ASC, `a`.`approval_level` ASC ;

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `dcn_nriv`
--
ALTER TABLE `dcn_nriv`
  ADD PRIMARY KEY (`year`,`month`,`object`);

--
-- Indeks untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indeks untuk tabel `file_types`
--
ALTER TABLE `file_types`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `general_setting`
--
ALTER TABLE `general_setting`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indeks untuk tabel `menugroups`
--
ALTER TABLE `menugroups`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `menuroles`
--
ALTER TABLE `menuroles`
  ADD PRIMARY KEY (`menuid`,`roleid`);

--
-- Indeks untuk tabel `menus`
--
ALTER TABLE `menus`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `nriv_po`
--
ALTER TABLE `nriv_po`
  ADD PRIMARY KEY (`prefix`,`month`,`year`);

--
-- Indeks untuk tabel `nriv_pr`
--
ALTER TABLE `nriv_pr`
  ADD PRIMARY KEY (`year`,`month`,`prtype`,`prefix`);

--
-- Indeks untuk tabel `object_auth`
--
ALTER TABLE `object_auth`
  ADD PRIMARY KEY (`object_name`);

--
-- Indeks untuk tabel `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indeks untuk tabel `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `t_attachments`
--
ALTER TABLE `t_attachments`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `t_cost_code_master`
--
ALTER TABLE `t_cost_code_master`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `t_cost_group`
--
ALTER TABLE `t_cost_group`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cost_group` (`cost_group`),
  ADD KEY `cost_group_2` (`cost_group`);

--
-- Indeks untuk tabel `t_department`
--
ALTER TABLE `t_department`
  ADD PRIMARY KEY (`deptid`);

--
-- Indeks untuk tabel `t_inv01`
--
ALTER TABLE `t_inv01`
  ADD PRIMARY KEY (`id`,`docnum`,`docyear`),
  ADD KEY `docnum` (`docnum`,`docyear`);

--
-- Indeks untuk tabel `t_inv02`
--
ALTER TABLE `t_inv02`
  ADD PRIMARY KEY (`id`,`docnum`,`docyear`,`docitem`),
  ADD KEY `docnum` (`docnum`,`docyear`,`docitem`);

--
-- Indeks untuk tabel `t_inv_batch_stock`
--
ALTER TABLE `t_inv_batch_stock`
  ADD PRIMARY KEY (`material`,`whscode`,`batchnum`);

--
-- Indeks untuk tabel `t_inv_stock`
--
ALTER TABLE `t_inv_stock`
  ADD PRIMARY KEY (`material`,`whscode`,`batchnum`);

--
-- Indeks untuk tabel `t_jabatan`
--
ALTER TABLE `t_jabatan`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `t_master_approval`
--
ALTER TABLE `t_master_approval`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `t_material`
--
ALTER TABLE `t_material`
  ADD PRIMARY KEY (`id`,`material`),
  ADD UNIQUE KEY `material` (`material`),
  ADD KEY `matuniqid` (`matuniqid`);

--
-- Indeks untuk tabel `t_material2`
--
ALTER TABLE `t_material2`
  ADD PRIMARY KEY (`material`,`altuom`);

--
-- Indeks untuk tabel `t_materialgroup`
--
ALTER TABLE `t_materialgroup`
  ADD PRIMARY KEY (`matgroup`);

--
-- Indeks untuk tabel `t_materialtype`
--
ALTER TABLE `t_materialtype`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `t_nriv`
--
ALTER TABLE `t_nriv`
  ADD PRIMARY KEY (`object`,`nyear`);

--
-- Indeks untuk tabel `t_nrivs`
--
ALTER TABLE `t_nrivs`
  ADD PRIMARY KEY (`object`,`tahun`,`bulan`,`tanggal`,`deptid`);

--
-- Indeks untuk tabel `t_po01`
--
ALTER TABLE `t_po01`
  ADD PRIMARY KEY (`id`,`ponum`),
  ADD KEY `podat` (`podat`,`vendor`),
  ADD KEY `ponum` (`ponum`);

--
-- Indeks untuk tabel `t_po02`
--
ALTER TABLE `t_po02`
  ADD PRIMARY KEY (`ponum`,`poitem`),
  ADD KEY `material` (`material`,`prnum`,`pritem`),
  ADD KEY `ponum` (`ponum`,`poitem`);

--
-- Indeks untuk tabel `t_po03`
--
ALTER TABLE `t_po03`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `t_po_approval`
--
ALTER TABLE `t_po_approval`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ponum` (`ponum`,`poitem`);

--
-- Indeks untuk tabel `t_pr01`
--
ALTER TABLE `t_pr01`
  ADD PRIMARY KEY (`id`,`prnum`),
  ADD UNIQUE KEY `prnum_2` (`prnum`),
  ADD KEY `prnum` (`prnum`),
  ADD KEY `typepr` (`typepr`),
  ADD KEY `prdate` (`prdate`),
  ADD KEY `warehouse` (`warehouse`);

--
-- Indeks untuk tabel `t_pr02`
--
ALTER TABLE `t_pr02`
  ADD PRIMARY KEY (`prnum`,`pritem`),
  ADD KEY `material` (`material`),
  ADD KEY `prnum` (`prnum`),
  ADD KEY `pritem` (`pritem`);

--
-- Indeks untuk tabel `t_projects`
--
ALTER TABLE `t_projects`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_project` (`kode_project`);

--
-- Indeks untuk tabel `t_pr_approval`
--
ALTER TABLE `t_pr_approval`
  ADD PRIMARY KEY (`id`),
  ADD KEY `prnum` (`prnum`,`pritem`);

--
-- Indeks untuk tabel `t_purc_doctype`
--
ALTER TABLE `t_purc_doctype`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `t_reserv01`
--
ALTER TABLE `t_reserv01`
  ADD PRIMARY KEY (`resnum`),
  ADD KEY `resdate` (`resdate`);

--
-- Indeks untuk tabel `t_reserv02`
--
ALTER TABLE `t_reserv02`
  ADD PRIMARY KEY (`resnum`,`resitem`),
  ADD KEY `material` (`material`,`fromwhs`,`towhs`);

--
-- Indeks untuk tabel `t_uom`
--
ALTER TABLE `t_uom`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uom` (`uom`);

--
-- Indeks untuk tabel `t_vendor`
--
ALTER TABLE `t_vendor`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `vendor_code` (`vendor_code`);

--
-- Indeks untuk tabel `t_warehouse`
--
ALTER TABLE `t_warehouse`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `whscode` (`whscode`);

--
-- Indeks untuk tabel `userroles`
--
ALTER TABLE `userroles`
  ADD PRIMARY KEY (`userid`,`roleid`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_username_unique` (`username`);

--
-- Indeks untuk tabel `user_object_auth`
--
ALTER TABLE `user_object_auth`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `workflows`
--
ALTER TABLE `workflows`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `workflow_assignments`
--
ALTER TABLE `workflow_assignments`
  ADD PRIMARY KEY (`workflow_group`,`approval_level`,`workflow_categories`,`creator`,`approver`);

--
-- Indeks untuk tabel `workflow_categories`
--
ALTER TABLE `workflow_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `workflow_groups`
--
ALTER TABLE `workflow_groups`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT untuk tabel `file_types`
--
ALTER TABLE `file_types`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `general_setting`
--
ALTER TABLE `general_setting`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=127;

--
-- AUTO_INCREMENT untuk tabel `menugroups`
--
ALTER TABLE `menugroups`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT untuk tabel `menus`
--
ALTER TABLE `menus`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=92;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT untuk tabel `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT untuk tabel `t_attachments`
--
ALTER TABLE `t_attachments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `t_cost_code_master`
--
ALTER TABLE `t_cost_code_master`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `t_cost_group`
--
ALTER TABLE `t_cost_group`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `t_department`
--
ALTER TABLE `t_department`
  MODIFY `deptid` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `t_inv01`
--
ALTER TABLE `t_inv01`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `t_inv02`
--
ALTER TABLE `t_inv02`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `t_jabatan`
--
ALTER TABLE `t_jabatan`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `t_master_approval`
--
ALTER TABLE `t_master_approval`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `t_material`
--
ALTER TABLE `t_material`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `t_materialtype`
--
ALTER TABLE `t_materialtype`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `t_po01`
--
ALTER TABLE `t_po01`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT untuk tabel `t_po03`
--
ALTER TABLE `t_po03`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `t_po_approval`
--
ALTER TABLE `t_po_approval`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `t_pr01`
--
ALTER TABLE `t_pr01`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT untuk tabel `t_projects`
--
ALTER TABLE `t_projects`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `t_pr_approval`
--
ALTER TABLE `t_pr_approval`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT untuk tabel `t_purc_doctype`
--
ALTER TABLE `t_purc_doctype`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `t_uom`
--
ALTER TABLE `t_uom`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `t_vendor`
--
ALTER TABLE `t_vendor`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `t_warehouse`
--
ALTER TABLE `t_warehouse`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=87;

--
-- AUTO_INCREMENT untuk tabel `user_object_auth`
--
ALTER TABLE `user_object_auth`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT untuk tabel `workflows`
--
ALTER TABLE `workflows`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `workflow_categories`
--
ALTER TABLE `workflow_categories`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `workflow_groups`
--
ALTER TABLE `workflow_groups`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
