-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 11, 2022 at 10:58 AM
-- Server version: 8.0.29
-- PHP Version: 7.4.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_eproc`
--

DELIMITER $$
--
-- Functions
--
CREATE DEFINER=`root`@`localhost` FUNCTION `fGetDatabaseLocalDatetime` () RETURNS DATETIME BEGIN
DECLARE _return datetime;
SET _return = (SELECT now());
return (_return);
END$$

CREATE DEFINER=`root`@`localhost` FUNCTION `fGetEmail` (`pUserid` INT) RETURNS VARCHAR(80) CHARSET utf8mb4 COLLATE utf8mb4_general_ci BEGIN
    DECLARE hasil VARCHAR(80);
	
    SET hasil = (SELECT email from users where id = pUserid);
    	-- return the customer level
	RETURN (hasil);
END$$

CREATE DEFINER=`root`@`localhost` FUNCTION `fGetMaxVersion` (`pDcn` VARCHAR(30)) RETURNS INT BEGIN
	DECLARE result int;
    
    set result = (SELECT max(doc_version) FROM document_versions WHERE dcn_number = pDcn);
    
    return result;
END$$

CREATE DEFINER=`root`@`localhost` FUNCTION `fGetUserName` (`pUserid` INT) RETURNS VARCHAR(50) CHARSET utf8mb4 COLLATE utf8mb4_general_ci BEGIN
    DECLARE hasil VARCHAR(50);
	
    SET hasil = (SELECT name from users where id = pUserid);
    	-- return the customer level
	RETURN (hasil);
END$$

CREATE DEFINER=`root`@`localhost` FUNCTION `fGetUserSignature` (`pUserName` VARCHAR(50)) RETURNS TEXT CHARSET utf8mb4 COLLATE utf8mb4_general_ci BEGIN
	DECLARE hasil text;
    
     SET hasil = (SELECT s_signfile from users where username = pUserName);
    
    RETURN (hasil);
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `dcn_nriv`
--

CREATE TABLE `dcn_nriv` (
  `year` int NOT NULL,
  `object` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `current_number` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `createdon` date NOT NULL,
  `createdby` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dcn_nriv`
--

INSERT INTO `dcn_nriv` (`year`, `object`, `current_number`, `createdon`, `createdby`) VALUES
(2022, 'CP', '5', '2022-09-15', 'husnulmub@gmail.com'),
(2022, 'WI', '3', '2022-09-15', 'husnulmub@gmail.com'),
(2022, 'WS', '2', '2022-09-15', 'husnulmub@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `file_types`
--

CREATE TABLE `file_types` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `no_of_files` int NOT NULL,
  `labels` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `file_validations` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `file_maxsize` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `createdby` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `updatedby` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `general_setting`
--

CREATE TABLE `general_setting` (
  `id` int NOT NULL,
  `setting_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `setting_value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `createdby` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `createdon` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `general_setting`
--

INSERT INTO `general_setting` (`id`, `setting_name`, `setting_value`, `createdby`, `createdon`) VALUES
(1, 'COMPANY_LOGO', 'storage/files/companylogo/sample-logo.jpg', 'sys-admin', '2022-08-17 22:19:52'),
(2, 'IPD_MODEL_API', 'http://192.168.88.1:8181/ipd-system/ipdfordms/searchAssycode', 'sys-admin', '2022-08-17 22:19:52');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
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
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`id`, `queue`, `payload`, `attempts`, `reserved_at`, `available_at`, `created_at`) VALUES
(94, 'default', '{\"uuid\":\"4d593ee4-3575-4197-af06-9cac10fe2d6e\",\"displayName\":\"App\\\\Mail\\\\MailRejected\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":13:{s:8:\\\"mailable\\\";O:21:\\\"App\\\\Mail\\\\MailRejected\\\":29:{s:4:\\\"data\\\";a:11:{s:5:\\\"email\\\";s:19:\\\"husnulmub@gmail.com\\\";s:5:\\\"docID\\\";i:10;s:7:\\\"version\\\";s:1:\\\"1\\\";s:7:\\\"dcnNumb\\\";s:16:\\\"DCN-CP-22-000005\\\";s:8:\\\"docTitle\\\";s:21:\\\"Production Procedures\\\";s:7:\\\"docCrdt\\\";s:10:\\\"23-09-2022\\\";s:7:\\\"docCrby\\\";s:9:\\\"sys-admin\\\";s:7:\\\"subject\\\";s:34:\\\"Document Rejected DCN-CP-22-000005\\\";s:6:\\\"remark\\\";s:11:\\\"Test Reject\\\";s:10:\\\"rejectedby\\\";s:13:\\\"Administrator\\\";s:4:\\\"body\\\";s:78:\\\"Your document has been rejected. Please check below remarks for your reference\\\";}s:6:\\\"locale\\\";N;s:4:\\\"from\\\";a:0:{}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:19:\\\"husnulmub@gmail.com\\\";}}s:2:\\\"cc\\\";a:0:{}s:3:\\\"bcc\\\";a:0:{}s:7:\\\"replyTo\\\";a:0:{}s:7:\\\"subject\\\";N;s:8:\\\"markdown\\\";N;s:7:\\\"\\u0000*\\u0000html\\\";N;s:4:\\\"view\\\";N;s:8:\\\"textView\\\";N;s:8:\\\"viewData\\\";a:0:{}s:11:\\\"attachments\\\";a:0:{}s:14:\\\"rawAttachments\\\";a:0:{}s:15:\\\"diskAttachments\\\";a:0:{}s:9:\\\"callbacks\\\";a:0:{}s:5:\\\"theme\\\";N;s:6:\\\"mailer\\\";s:4:\\\"smtp\\\";s:29:\\\"\\u0000*\\u0000assertionableRenderStrings\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\"}}', 0, NULL, 1664295694, 1664295694);

-- --------------------------------------------------------

--
-- Table structure for table `menugroups`
--

CREATE TABLE `menugroups` (
  `id` bigint UNSIGNED NOT NULL,
  `menugroup` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `groupicon` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `_index` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `createdby` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `updatedby` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menugroups`
--

INSERT INTO `menugroups` (`id`, `menugroup`, `groupicon`, `_index`, `created_at`, `updated_at`, `createdby`, `updatedby`) VALUES
(1, 'MASTER', 'fa fa-database', 1, '2022-07-26 02:12:00', NULL, 'sys-admin', ''),
(2, 'SETTINGS', 'fa fa-gear', 6, '2022-07-26 02:12:09', NULL, 'sys-admin', 'husnulmub@gmail.com'),
(3, 'PROCUREMENT', 'fa fa-cart-shopping', 2, '2022-07-26 02:12:09', NULL, 'sys-admin', 'husnulmub@gmail.com'),
(5, 'REPORTS', NULL, 4, '2022-07-26 23:07:03', NULL, 'husnulmub@gmail.com', 'husnulmub@gmail.com'),
(6, 'LOGISTICS', NULL, 3, '2022-08-26 06:08:52', NULL, 'husnulmub@gmail.com', 'husnulmub@gmail.com'),
(7, 'APPROVAL', 'fa fa-check', 5, '2022-10-04 00:10:39', NULL, 'husnulmub@gmail.com', 'husnulmub@gmail.com'),
(8, 'FINANCE', 'fa fa-money', 5, '2022-10-10 00:10:44', NULL, 'husnulmub@gmail.com', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `menuroles`
--

CREATE TABLE `menuroles` (
  `menuid` int NOT NULL,
  `roleid` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `createdby` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `updatedby` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menuroles`
--

INSERT INTO `menuroles` (`menuid`, `roleid`, `created_at`, `updated_at`, `createdby`, `updatedby`) VALUES
(1, 1, '2022-07-26 02:20:34', NULL, 'sys-admin', ''),
(1, 2, '2022-07-26 03:07:15', NULL, 'husnulmub@gmail.com', NULL),
(2, 1, '2022-07-26 02:20:34', NULL, 'sys-admin', ''),
(2, 2, '2022-07-26 03:07:19', NULL, 'husnulmub@gmail.com', NULL),
(3, 1, '2022-07-26 02:21:08', NULL, 'sys-admin', ''),
(4, 1, '2022-10-01 08:10:43', NULL, 'husnulmub@gmail.com', NULL),
(5, 1, '2022-07-26 02:21:32', NULL, 'sys-admin', ''),
(7, 1, '2022-07-26 18:07:53', NULL, 'husnulmub@gmail.com', NULL),
(20, 1, '2022-08-17 14:08:34', NULL, 'husnulmub@gmail.com', NULL),
(25, 1, '2022-09-13 15:09:06', NULL, 'husnulmub@gmail.com', NULL),
(26, 1, '2022-09-20 06:09:12', NULL, 'husnulmub@gmail.com', NULL),
(29, 1, '2022-10-01 22:10:56', NULL, 'husnulmub@gmail.com', NULL),
(30, 1, '2022-10-01 22:10:58', NULL, 'husnulmub@gmail.com', NULL),
(31, 1, '2022-10-03 23:10:25', NULL, 'husnulmub@gmail.com', NULL),
(32, 1, '2022-10-03 23:10:27', NULL, 'husnulmub@gmail.com', NULL),
(33, 1, '2022-10-04 00:10:43', NULL, 'husnulmub@gmail.com', NULL),
(34, 8, '2022-10-04 00:10:35', NULL, 'husnulmub@gmail.com', NULL),
(35, 7, '2022-10-04 00:10:47', NULL, 'husnulmub@gmail.com', NULL),
(36, 6, '2022-10-04 00:10:22', NULL, 'husnulmub@gmail.com', NULL),
(37, 9, '2022-10-04 00:10:52', NULL, 'husnulmub@gmail.com', NULL),
(38, 1, '2022-10-10 00:10:27', NULL, 'husnulmub@gmail.com', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `menus`
--

CREATE TABLE `menus` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `route` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `menugroup` int DEFAULT NULL,
  `menu_idx` int DEFAULT NULL,
  `icon` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `createdby` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `updatedby` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menus`
--

INSERT INTO `menus` (`id`, `name`, `route`, `menugroup`, `menu_idx`, `icon`, `created_at`, `updated_at`, `createdby`, `updatedby`) VALUES
(1, 'Approval Workflow', 'config/workflow', 2, 4, 'workflow.png', '2022-07-26 02:12:52', NULL, 'sys-admin', 'husnulmub@gmail.com'),
(2, 'Item Master', 'master/item', 1, 1, 'DB.png', '2022-07-26 02:12:52', NULL, 'sys-admin', 'husnulmub@gmail.com'),
(3, 'Department', 'master/department', 1, 4, 'Manuf.png', '2022-07-26 02:12:52', NULL, 'sys-admin', 'husnulmub@gmail.com'),
(4, 'Users', 'config/users', 2, 1, 'UM01.png', '2022-07-26 02:12:52', NULL, 'sys-admin', ''),
(5, 'Roles', 'config/roles', 2, 3, 'MF06.png', '2022-07-26 02:12:52', NULL, 'sys-admin', ''),
(7, 'Menus', 'config/menus', 2, 2, 'CMDOPT.png', '2022-07-26 02:12:52', NULL, 'sys-admin', 'husnulmub@gmail.com'),
(20, 'General Setting', 'general/setting', 2, 5, 'setting.png', '2022-08-17 14:08:21', NULL, 'husnulmub@gmail.com', NULL),
(25, 'Vendor', 'master/vendor', 1, 2, 'DB.png', '2022-09-13 15:09:55', NULL, 'husnulmub@gmail.com', 'husnulmub@gmail.com'),
(26, 'Object Authorization', 'config/objectauth', 2, 6, 'CMDOPT.png', '2022-09-20 06:09:00', NULL, 'husnulmub@gmail.com', 'husnulmub@gmail.com'),
(27, 'Customer', 'master/customer', 1, 3, 'DB.png', '2022-10-01 08:10:54', NULL, 'husnulmub@gmail.com', NULL),
(29, 'Penerimaan PO', 'logistic/terimapo', 6, 1, 'IM1B.png', '2022-10-01 22:10:33', NULL, 'husnulmub@gmail.com', NULL),
(30, 'Pengeluaran Barang', 'logistic/pengeluaran', 6, 2, 'IM1A.png', '2022-10-01 22:10:33', NULL, 'husnulmub@gmail.com', NULL),
(31, 'Purchase Request', 'proc/pr', 3, 1, 'prlist.png', '2022-10-03 23:10:01', NULL, 'husnulmub@gmail.com', NULL),
(32, 'Purchase Order', 'proc/po', 3, 2, 'polist.png', '2022-10-03 23:10:01', NULL, 'husnulmub@gmail.com', NULL),
(33, 'Work Order', 'logistic/wo', 6, 3, 'MF01.png', '2022-10-04 00:10:18', NULL, 'husnulmub@gmail.com', NULL),
(34, 'Approve PBJ', 'approve/pbj', 7, 1, 'approve.png', '2022-10-04 00:10:56', NULL, 'husnulmub@gmail.com', NULL),
(35, 'Approve PR', 'approve/pr', 7, 2, 'approve.png', '2022-10-04 00:10:56', NULL, 'husnulmub@gmail.com', NULL),
(36, 'Approve PO', 'approve/po', 7, 3, 'approve.png', '2022-10-04 00:10:56', NULL, 'husnulmub@gmail.com', NULL),
(37, 'Approve SPK', 'approve/spk', 7, 4, 'approve.png', '2022-10-04 00:10:56', NULL, 'husnulmub@gmail.com', NULL),
(38, 'Budgeting', 'finance/budgeting', 8, 1, NULL, '2022-10-10 00:10:04', NULL, 'husnulmub@gmail.com', NULL);

--
-- Triggers `menus`
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
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `migrations`
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
-- Table structure for table `object_auth`
--

CREATE TABLE `object_auth` (
  `object_name` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `object_description` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `createdon` date NOT NULL,
  `createdby` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `object_auth`
--

INSERT INTO `object_auth` (`object_name`, `object_description`, `createdon`, `createdby`) VALUES
('ALLOW_CHANGE_DOC', 'Allow user to change Document', '2022-08-15', 'sys-admin'),
('ALLOW_DISPLAY_ALL_DOC', 'Allow user to display all document', '2022-08-15', 'sys-admin'),
('ALLOW_DISPLAY_APP_DOC', 'Allow Display Approved Document', '2022-08-15', 'sys-admin'),
('ALLOW_DISPLAY_OBS_DOC', 'Allow Display Obsolete Document', '2022-08-15', 'sys-admin'),
('ALLOW_DOWNLOAD_DOC', 'Allow user to download Document Attachment', '2022-08-15', 'sys-admin'),
('ALLOW_DOWNLOAD_ORIGINAL_DOC', 'Allow Download Original Document', '2022-09-18', 'sys-admin'),
('ALLOW_UPLOAD_ORIGINAL_DOC', 'Allow Upload Original Document', '2022-09-18', 'sys-admin');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint UNSIGNED NOT NULL,
  `rolename` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `rolestatus` int NOT NULL,
  `createdby` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `updatedby` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `rolename`, `rolestatus`, `createdby`, `updatedby`, `created_at`, `updated_at`) VALUES
(1, 'SYS-ADMIN', 1, 'sys-admin', '', '2022-01-26 02:45:03', NULL),
(4, 'LOGISTIK', 1, 'husnulmub@gmail.com', NULL, '2022-10-04 00:10:00', NULL),
(5, 'PURCHASING', 1, 'husnulmub@gmail.com', NULL, '2022-10-04 00:10:10', NULL),
(6, 'PO_APPROVAL', 1, 'husnulmub@gmail.com', NULL, '2022-10-04 00:10:46', NULL),
(7, 'PR_APPROVAL', 1, 'husnulmub@gmail.com', NULL, '2022-10-04 00:10:55', NULL),
(8, 'PBJ_APPROVAL', 1, 'husnulmub@gmail.com', NULL, '2022-10-04 00:10:28', NULL),
(9, 'SPK_APPROVAL', 1, 'husnulmub@gmail.com', NULL, '2022-10-04 00:10:42', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `t_department`
--

CREATE TABLE `t_department` (
  `deptid` int NOT NULL,
  `department` varchar(80) COLLATE utf8mb4_general_ci NOT NULL,
  `createdon` date NOT NULL,
  `createdby` varchar(50) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `t_department`
--

INSERT INTO `t_department` (`deptid`, `department`, `createdon`, `createdby`) VALUES
(1, 'IT - Dept Test Update', '2022-10-04', 'husnulmub@gmail.com'),
(2, 'Purchasing', '2022-10-04', 'husnulmub@gmail.com'),
(3, 'Logistik', '2022-10-04', 'husnulmub@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `t_material`
--

CREATE TABLE `t_material` (
  `material` varchar(70) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `matdesc` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `mattype` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `matgroup` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `partname` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `partnumber` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `color` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `size` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `matunit` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `minstock` decimal(15,2) DEFAULT NULL,
  `orderunit` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `stdprice` decimal(15,2) DEFAULT NULL,
  `stdpriceusd` decimal(15,4) DEFAULT '0.0000',
  `active` tinyint(1) DEFAULT NULL,
  `matuniqid` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `createdon` datetime DEFAULT NULL,
  `createdby` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Material Master';

--
-- Dumping data for table `t_material`
--

INSERT INTO `t_material` (`material`, `matdesc`, `mattype`, `matgroup`, `partname`, `partnumber`, `color`, `size`, `matunit`, `minstock`, `orderunit`, `stdprice`, `stdpriceusd`, `active`, `matuniqid`, `createdon`, `createdby`) VALUES
('MAT01', 'Test Material update material', '1', NULL, 'Testing part update', 'part01', NULL, NULL, 'PC', NULL, NULL, NULL, '0.0000', NULL, '1665461906', '2022-10-11 04:10:02', 'husnulmub@gmail.com');

--
-- Triggers `t_material`
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
-- Table structure for table `t_material2`
--

CREATE TABLE `t_material2` (
  `material` varchar(70) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `altuom` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `convalt` decimal(15,2) NOT NULL,
  `baseuom` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `convbase` decimal(15,2) NOT NULL,
  `createdon` datetime NOT NULL,
  `createdby` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Material Alternative UOM';

--
-- Dumping data for table `t_material2`
--

INSERT INTO `t_material2` (`material`, `altuom`, `convalt`, `baseuom`, `convbase`, `createdon`, `createdby`) VALUES
('MAT01', 'PACK', '1.00', 'PC', '20.00', '2022-10-11 07:10:56', 'husnulmub@gmail.com'),
('MAT01', 'PC', '1.00', 'PC', '1.00', '2022-10-11 07:10:56', 'husnulmub@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `t_materialgroup`
--

CREATE TABLE `t_materialgroup` (
  `matgroup` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `description` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `createdon` date NOT NULL,
  `createdby` varchar(50) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `t_materialtype`
--

CREATE TABLE `t_materialtype` (
  `id` int NOT NULL,
  `mattypedesc` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `createdon` datetime NOT NULL,
  `createdby` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Material Type';

--
-- Dumping data for table `t_materialtype`
--

INSERT INTO `t_materialtype` (`id`, `mattypedesc`, `createdon`, `createdby`) VALUES
(1, 'Sparepart', '2022-10-08 06:10:41', 'husnulmub@gmail.com'),
(2, 'ATK', '2022-10-08 06:10:41', 'husnulmub@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `t_nriv`
--

CREATE TABLE `t_nriv` (
  `object` varchar(15) NOT NULL,
  `fromnum` varchar(15) NOT NULL,
  `tonumber` varchar(15) NOT NULL,
  `currentnum` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `t_nriv`
--

INSERT INTO `t_nriv` (`object`, `fromnum`, `tonumber`, `currentnum`) VALUES
('BARANG', '1000000000', '1999999999', ''),
('GRPO', '4000000000', '4999999999', '4000000003'),
('IV', '5000000000', '5999999999', ''),
('JURNAL', '6000000000', '6999999999', ''),
('PO', '2000000000', '2999999999', '2000000001'),
('PR', '1000000000', '3999999999', '1000000002'),
('RSRV', '3000000000', '4999999999', '3000000013'),
('VENDOR', '3000000000', '3999999999', '3000000001');

-- --------------------------------------------------------

--
-- Table structure for table `t_po01`
--

CREATE TABLE `t_po01` (
  `ponum` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `ext_ponum` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `potype` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `podat` date DEFAULT NULL,
  `vendor` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `note` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `currency` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `approvestat` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `appby` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `completed` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ppn` decimal(15,2) DEFAULT '0.00',
  `tf_price` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tf_dest` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tf_shipment` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tf_top` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tf_packing` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tf_shipdate` date DEFAULT NULL,
  `createdon` datetime DEFAULT NULL,
  `createdby` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Purchase Order Header';

--
-- Triggers `t_po01`
--
DELIMITER $$
CREATE TRIGGER `deleteitem` AFTER DELETE ON `t_po01` FOR EACH ROW DELETE FROM t_po02 WHERE ponum = OLD.ponum
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `t_po02`
--

CREATE TABLE `t_po02` (
  `ponum` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `poitem` int NOT NULL,
  `material` varchar(70) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `matdesc` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `quantity` decimal(15,2) DEFAULT NULL,
  `unit` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `price` decimal(15,2) DEFAULT NULL,
  `grqty` decimal(15,2) DEFAULT NULL,
  `prnum` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `pritem` int DEFAULT NULL,
  `grstatus` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `pocomplete` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `approvestat` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `createdon` date DEFAULT NULL,
  `createdby` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='PO Item';

--
-- Triggers `t_po02`
--
DELIMITER $$
CREATE TRIGGER `UpdatePRStatus` AFTER INSERT ON `t_po02` FOR EACH ROW CALL sp_UpdatePRStatus(NEW.prnum,NEW.pritem)
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `deleteitempo` AFTER DELETE ON `t_po02` FOR EACH ROW UPDATE t_pr02 set pocreated = NULL WHERE prnum = OLD.prnum AND pritem = OLD.pritem
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `t_pr01`
--

CREATE TABLE `t_pr01` (
  `prnum` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `typepr` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `note` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `prdate` date DEFAULT NULL,
  `relgroup` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `approvestat` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `requestby` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `warehouse` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `idproject` int DEFAULT NULL,
  `appby` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `createdon` datetime DEFAULT NULL,
  `createdby` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Purchase Requisition Header';

--
-- Triggers `t_pr01`
--
DELIMITER $$
CREATE TRIGGER `deletepritem` AFTER DELETE ON `t_pr01` FOR EACH ROW DELETE FROM t_pr02 WHERE prnum = OLD.prnum
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `t_pr02`
--

CREATE TABLE `t_pr02` (
  `prnum` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `pritem` int NOT NULL,
  `material` varchar(70) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `matdesc` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `quantity` decimal(18,2) DEFAULT NULL,
  `unit` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `warehouse` int DEFAULT NULL,
  `pocreated` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `approvestat` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `remark` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `createdon` datetime DEFAULT NULL,
  `createdby` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Purchase Order Item';

-- --------------------------------------------------------

--
-- Table structure for table `t_reserv01`
--

CREATE TABLE `t_reserv01` (
  `resnum` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `resdate` date DEFAULT NULL,
  `note` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `requestor` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `fromwhs` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `towhs` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `approvestat` int DEFAULT NULL,
  `createdon` date DEFAULT NULL,
  `createdby` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Reservation Header';

-- --------------------------------------------------------

--
-- Table structure for table `t_reserv02`
--

CREATE TABLE `t_reserv02` (
  `resnum` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `resitem` int NOT NULL,
  `material` varchar(70) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `matdesc` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `quantity` decimal(10,0) DEFAULT NULL,
  `unit` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `fromwhs` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `towhs` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `remark` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `movementstat` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `createdon` date DEFAULT NULL,
  `createdby` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Reservation Items';

-- --------------------------------------------------------

--
-- Table structure for table `t_uom`
--

CREATE TABLE `t_uom` (
  `uom` varchar(5) COLLATE utf8mb4_general_ci NOT NULL,
  `uomdesc` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `createdon` date NOT NULL,
  `createdby` varchar(50) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `t_uom`
--

INSERT INTO `t_uom` (`uom`, `uomdesc`, `createdon`, `createdby`) VALUES
('KG', 'Kilogram', '2022-10-08', 'husnulmub@gmail.com'),
('LSN', 'Lusin', '2022-10-08', 'husnulmub@gmail.com'),
('M', 'Meter', '2022-10-08', 'husnulmub@gmail.com'),
('PACK', 'Pack', '2022-10-08', 'husnulmub@gmail.com'),
('PC', 'Pieces', '2022-10-08', 'husnulmub@gmail.com'),
('TON', 'Ton', '2022-10-08', 'husnulmub@gmail.com'),
('Unit', 'Unit', '2022-10-08', 'husnulmub@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `t_vendor`
--

CREATE TABLE `t_vendor` (
  `vendor_code` varchar(12) COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Kode Vendor',
  `vendor_name` varchar(80) COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Nama Vendor',
  `vendor_address` text COLLATE utf8mb4_general_ci COMMENT 'Alamat Vendor',
  `vendor_telp` varchar(15) COLLATE utf8mb4_general_ci NOT NULL COMMENT 'No. Telp',
  `vendor_email` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Email',
  `contact_person` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `createdby` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `createdon` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Vendor Masters';

-- --------------------------------------------------------

--
-- Table structure for table `userroles`
--

CREATE TABLE `userroles` (
  `userid` int NOT NULL,
  `roleid` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `createdby` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `updatedby` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `userroles`
--

INSERT INTO `userroles` (`userid`, `roleid`, `created_at`, `updated_at`, `createdby`, `updatedby`) VALUES
(1, 1, '2022-07-26 02:19:44', NULL, 'sys-admin', ''),
(1, 6, '2022-10-04 00:10:14', NULL, 'husnulmub@gmail.com', NULL),
(1, 7, '2022-10-04 00:10:54', NULL, 'husnulmub@gmail.com', NULL),
(1, 8, '2022-10-04 00:10:26', NULL, 'husnulmub@gmail.com', NULL),
(1, 9, '2022-10-04 00:10:45', NULL, 'husnulmub@gmail.com', NULL),
(2, 3, '2022-08-05 01:08:02', NULL, 'husnulmub@gmail.com', NULL),
(3, 2, '2022-07-26 03:07:14', NULL, 'husnulmub@gmail.com', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `username` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `s_signfile` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `createdby` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `updatedby` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `username`, `email_verified_at`, `password`, `remember_token`, `s_signfile`, `created_at`, `updated_at`, `createdby`, `updatedby`) VALUES
(1, 'Administrator', 'husnulmub@gmail.com', 'sys-admin', NULL, '$2y$12$PSL31tVJTgNX4GyT8w/QCey2ZPD6DfcamWpZ3hy/WHhOxcWQSNjDi', NULL, 'storage/files/e_signature/esign2.png', '2022-07-26 07:36:29', NULL, '', ''),
(2, 'creator1', 'creator1@mail.com', 'creator1', NULL, '$2y$12$tB8SUN5MbAJtZ.j/cIAQ0uwEvmu/o/S/L4UEHMW42fuaBn7RWnSC.', NULL, NULL, NULL, NULL, 'husnulmub@gmail.com', NULL),
(3, 'Approval1 Update', 'approval1@mail.com', 'approval1', NULL, '$2y$12$tB8SUN5MbAJtZ.j/cIAQ0uwEvmu/o/S/L4UEHMW42fuaBn7RWnSC.', NULL, 'storage/files/e_signature/esign3.png', NULL, NULL, 'husnulmub@gmail.com', NULL),
(5, 'Admin2', 'husnulm15@gmail.com', 'admin2', NULL, '$2y$12$tB8SUN5MbAJtZ.j/cIAQ0uwEvmu/o/S/L4UEHMW42fuaBn7RWnSC.', NULL, 'storage/files/e_signature/esign3.png', '2022-08-15 02:08:27', NULL, 'husnulmub@gmail.com', NULL),
(7, 'Test User1', 'user1@gmail.com', 'user1', NULL, '$2y$12$tB8SUN5MbAJtZ.j/cIAQ0uwEvmu/o/S/L4UEHMW42fuaBn7RWnSC.', NULL, 'storage/files/e_signature/esign1.png', '2022-08-16 08:08:50', NULL, 'husnulmub@gmail.com', NULL),
(8, 'testt', 'testmail@mail.com', 'tess', NULL, '$2y$12$YWbmigtH8OxYi4X4cXG/Wu2eHkfND.1.tGVkQY7uqeF8aGGnqCahe', NULL, NULL, '2022-09-14 01:09:23', NULL, 'husnulmub@gmail.com', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_object_auth`
--

CREATE TABLE `user_object_auth` (
  `userid` int NOT NULL,
  `object_name` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `object_val` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `createdon` date NOT NULL,
  `createdby` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_object_auth`
--

INSERT INTO `user_object_auth` (`userid`, `object_name`, `object_val`, `createdon`, `createdby`) VALUES
(1, 'ALLOW_CHANGE_DOC', 'N', '2022-08-15', 'sys-admin'),
(1, 'ALLOW_DISPLAY_ALL_DOC', 'Y', '2022-08-19', 'sys-admin'),
(1, 'ALLOW_DOWNLOAD_DOC', 'Y', '2022-09-19', 'sys-admin'),
(1, 'ALLOW_DOWNLOAD_ORIGINAL_DOC', 'Y', '2022-09-19', 'sys-admin'),
(1, 'ALLOW_UPLOAD_ORIGINAL_DOC', 'Y', '2022-09-19', 'sys-admin'),
(5, 'ALLOW_CHANGE_DOC', 'Y', '2022-08-15', 'sys-admin'),
(5, 'ALLOW_DOWNLOAD_DOC', 'N', '2022-08-15', 'sys-admin'),
(7, 'ALLOW_DISPLAY_OBS_DOC', 'Y', '2022-08-22', 'sys-admin');

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_material`
-- (See below for the actual view)
--
CREATE TABLE `v_material` (
`material` varchar(70)
,`matdesc` varchar(100)
,`mattype` varchar(20)
,`matgroup` varchar(20)
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
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_menuroles`
-- (See below for the actual view)
--
CREATE TABLE `v_menuroles` (
`menuid` int
,`roleid` int
,`rolename` varchar(50)
,`name` varchar(100)
,`menugroup` int
,`group` varchar(50)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_usermenus`
-- (See below for the actual view)
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
-- Stand-in structure for view `v_userroles`
-- (See below for the actual view)
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
-- Stand-in structure for view `v_user_obj_auth`
-- (See below for the actual view)
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
-- Structure for view `v_material`
--
DROP TABLE IF EXISTS `v_material`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_material`  AS SELECT `a`.`material` AS `material`, `a`.`matdesc` AS `matdesc`, `a`.`mattype` AS `mattype`, `a`.`matgroup` AS `matgroup`, `a`.`partname` AS `partname`, `a`.`partnumber` AS `partnumber`, `a`.`color` AS `color`, `a`.`size` AS `size`, `a`.`matunit` AS `matunit`, `a`.`minstock` AS `minstock`, `a`.`orderunit` AS `orderunit`, `a`.`stdprice` AS `stdprice`, `a`.`stdpriceusd` AS `stdpriceusd`, `a`.`active` AS `active`, `a`.`matuniqid` AS `matuniqid`, `a`.`createdon` AS `createdon`, `a`.`createdby` AS `createdby`, `b`.`mattypedesc` AS `mattypedesc` FROM (`t_material` `a` join `t_materialtype` `b` on((`a`.`mattype` = `b`.`id`))) ;

-- --------------------------------------------------------

--
-- Structure for view `v_menuroles`
--
DROP TABLE IF EXISTS `v_menuroles`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_menuroles`  AS SELECT `a`.`menuid` AS `menuid`, `a`.`roleid` AS `roleid`, `c`.`rolename` AS `rolename`, `b`.`name` AS `name`, `b`.`menugroup` AS `menugroup`, `d`.`menugroup` AS `group` FROM (((`menuroles` `a` join `menus` `b` on((`a`.`menuid` = `b`.`id`))) join `roles` `c` on((`a`.`roleid` = `c`.`id`))) left join `menugroups` `d` on((`b`.`menugroup` = `d`.`id`))) ORDER BY `a`.`menuid` ASC ;

-- --------------------------------------------------------

--
-- Structure for view `v_usermenus`
--
DROP TABLE IF EXISTS `v_usermenus`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_usermenus`  AS SELECT `a`.`id` AS `id`, `a`.`name` AS `menu_desc`, `a`.`route` AS `route`, `a`.`menugroup` AS `menugroup`, `a`.`menu_idx` AS `menu_idx`, `g`.`menugroup` AS `groupname`, `g`.`groupicon` AS `groupicon`, `g`.`_index` AS `group_idx`, `b`.`roleid` AS `roleid`, `c`.`rolename` AS `rolename`, `d`.`userid` AS `userid`, `f`.`name` AS `name_of_user`, `f`.`email` AS `email`, `f`.`username` AS `username`, `a`.`icon` AS `icon` FROM (((((`menus` `a` join `menuroles` `b` on((`a`.`id` = `b`.`menuid`))) join `roles` `c` on((`b`.`roleid` = `c`.`id`))) join `userroles` `d` on((`b`.`roleid` = `d`.`roleid`))) join `users` `f` on((`d`.`userid` = `f`.`id`))) left join `menugroups` `g` on((`a`.`menugroup` = `g`.`id`))) ORDER BY `a`.`menu_idx` ASC, `g`.`_index` ASC ;

-- --------------------------------------------------------

--
-- Structure for view `v_userroles`
--
DROP TABLE IF EXISTS `v_userroles`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_userroles`  AS SELECT `a`.`roleid` AS `roleid`, `c`.`rolename` AS `rolename`, `a`.`userid` AS `userid`, `b`.`name` AS `name`, `b`.`email` AS `email`, `b`.`username` AS `username` FROM ((`userroles` `a` join `users` `b` on((`a`.`userid` = `b`.`id`))) join `roles` `c` on((`a`.`roleid` = `c`.`id`))) ORDER BY `c`.`id` ASC ;

-- --------------------------------------------------------

--
-- Structure for view `v_user_obj_auth`
--
DROP TABLE IF EXISTS `v_user_obj_auth`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_user_obj_auth`  AS SELECT `a`.`userid` AS `userid`, `a`.`object_name` AS `object_name`, `a`.`object_val` AS `object_val`, `a`.`createdon` AS `createdon`, `a`.`createdby` AS `createdby`, `b`.`object_description` AS `object_description` FROM (`user_object_auth` `a` join `object_auth` `b` on((`a`.`object_name` = `b`.`object_name`))) ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `dcn_nriv`
--
ALTER TABLE `dcn_nriv`
  ADD PRIMARY KEY (`year`,`object`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `file_types`
--
ALTER TABLE `file_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `general_setting`
--
ALTER TABLE `general_setting`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `menugroups`
--
ALTER TABLE `menugroups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `menuroles`
--
ALTER TABLE `menuroles`
  ADD PRIMARY KEY (`menuid`,`roleid`);

--
-- Indexes for table `menus`
--
ALTER TABLE `menus`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `object_auth`
--
ALTER TABLE `object_auth`
  ADD PRIMARY KEY (`object_name`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `t_department`
--
ALTER TABLE `t_department`
  ADD PRIMARY KEY (`deptid`);

--
-- Indexes for table `t_material`
--
ALTER TABLE `t_material`
  ADD PRIMARY KEY (`material`),
  ADD KEY `matuniqid` (`matuniqid`);

--
-- Indexes for table `t_material2`
--
ALTER TABLE `t_material2`
  ADD PRIMARY KEY (`material`,`altuom`);

--
-- Indexes for table `t_materialgroup`
--
ALTER TABLE `t_materialgroup`
  ADD PRIMARY KEY (`matgroup`);

--
-- Indexes for table `t_materialtype`
--
ALTER TABLE `t_materialtype`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `t_nriv`
--
ALTER TABLE `t_nriv`
  ADD PRIMARY KEY (`object`);

--
-- Indexes for table `t_po01`
--
ALTER TABLE `t_po01`
  ADD PRIMARY KEY (`ponum`),
  ADD KEY `podat` (`podat`,`vendor`);

--
-- Indexes for table `t_po02`
--
ALTER TABLE `t_po02`
  ADD PRIMARY KEY (`ponum`,`poitem`),
  ADD KEY `material` (`material`,`prnum`,`pritem`);

--
-- Indexes for table `t_pr01`
--
ALTER TABLE `t_pr01`
  ADD PRIMARY KEY (`prnum`),
  ADD KEY `prnum` (`prnum`),
  ADD KEY `typepr` (`typepr`),
  ADD KEY `prdate` (`prdate`),
  ADD KEY `warehouse` (`warehouse`);

--
-- Indexes for table `t_pr02`
--
ALTER TABLE `t_pr02`
  ADD PRIMARY KEY (`prnum`,`pritem`),
  ADD KEY `material` (`material`),
  ADD KEY `prnum` (`prnum`);

--
-- Indexes for table `t_reserv01`
--
ALTER TABLE `t_reserv01`
  ADD PRIMARY KEY (`resnum`),
  ADD KEY `resdate` (`resdate`);

--
-- Indexes for table `t_reserv02`
--
ALTER TABLE `t_reserv02`
  ADD PRIMARY KEY (`resnum`,`resitem`),
  ADD KEY `material` (`material`,`fromwhs`,`towhs`);

--
-- Indexes for table `t_uom`
--
ALTER TABLE `t_uom`
  ADD PRIMARY KEY (`uom`);

--
-- Indexes for table `t_vendor`
--
ALTER TABLE `t_vendor`
  ADD PRIMARY KEY (`vendor_code`);

--
-- Indexes for table `userroles`
--
ALTER TABLE `userroles`
  ADD PRIMARY KEY (`userid`,`roleid`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_username_unique` (`username`);

--
-- Indexes for table `user_object_auth`
--
ALTER TABLE `user_object_auth`
  ADD PRIMARY KEY (`userid`,`object_name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `file_types`
--
ALTER TABLE `file_types`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `general_setting`
--
ALTER TABLE `general_setting`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=95;

--
-- AUTO_INCREMENT for table `menugroups`
--
ALTER TABLE `menugroups`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `menus`
--
ALTER TABLE `menus`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `t_department`
--
ALTER TABLE `t_department`
  MODIFY `deptid` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `t_materialtype`
--
ALTER TABLE `t_materialtype`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
