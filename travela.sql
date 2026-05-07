-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th4 23, 2026 lúc 08:15 PM
-- Phiên bản máy phục vụ: 10.5.27-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `travela`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2026_04_13_213204_create_jobs_table', 2),
(6, '2026_04_18_002737_add_phone_to_tbl_contact', 2),
(7, '2026_04_23_100000_create_tbl_tour_schedules', 3),
(8, '2026_04_23_100001_create_tbl_wishlists', 4),
(9, '2026_04_23_104805_create_tbl_temp_images_table', 5),
(10, '2026_04_23_105740_add_description_to_tbl_images_table', 6);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_admin`
--

CREATE TABLE `tbl_admin` (
  `adminId` int(11) NOT NULL,
  `username` varchar(45) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(50) NOT NULL,
  `createdDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `fullName` varchar(100) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `tbl_admin`
--

INSERT INTO `tbl_admin` (`adminId`, `username`, `password`, `email`, `createdDate`, `fullName`, `address`) VALUES
(3, 'admin', 'e10adc3949ba59abbe56e057f20f883e', 'admin@gmail.com', '2026-03-06 01:54:43', NULL, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_booking`
--

CREATE TABLE `tbl_booking` (
  `bookingId` int(11) NOT NULL,
  `tourId` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `numAdults` int(11) DEFAULT 0,
  `numChildren` int(11) DEFAULT 0,
  `totalPrice` decimal(12,2) DEFAULT NULL,
  `bookingStatus` char(1) DEFAULT NULL COMMENT 'b=new, f=finished, c=cancelled, y=paid',
  `bookingDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `address` varchar(255) DEFAULT NULL,
  `fullName` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phoneNumber` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `tbl_booking`
--

INSERT INTO `tbl_booking` (`bookingId`, `tourId`, `userId`, `numAdults`, `numChildren`, `totalPrice`, `bookingStatus`, `bookingDate`, `address`, `fullName`, `email`, `phoneNumber`) VALUES
(4, 6, 1, 0, 0, NULL, NULL, '2026-03-20 03:56:56', NULL, NULL, NULL, NULL),
(5, 6, 1, 0, 0, NULL, NULL, '2026-03-20 03:57:18', NULL, NULL, NULL, NULL),
(7, 7, 1, 0, 0, NULL, NULL, '2026-03-20 04:40:47', NULL, NULL, NULL, NULL),
(8, 7, 1, 0, 0, NULL, NULL, '2026-03-20 04:42:26', NULL, NULL, NULL, NULL),
(9, 6, 1, 0, 0, NULL, NULL, '2026-03-22 01:41:55', NULL, NULL, NULL, NULL),
(10, 7, 1, 0, 0, NULL, NULL, '2026-03-22 02:08:37', NULL, NULL, NULL, NULL),
(11, 8, 1, 0, 0, NULL, NULL, '2026-04-01 12:11:04', NULL, NULL, NULL, NULL),
(12, 11, 1, 3, 1, 525000.00, NULL, '2026-04-23 17:59:34', 'Ngũ Hành Sơn', 'hoàng ngọc tuệ', 'hoangngoctue2@gmail.com', '0935614704'),
(13, 11, 1, 3, 1, 525000.00, NULL, '2026-04-23 18:02:11', 'Ngũ Hành Sơn', 'hoàng ngọc tuệ', 'hoangngoctue2@gmail.com', '0935614704');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_checkout`
--

CREATE TABLE `tbl_checkout` (
  `checkoutId` int(11) NOT NULL,
  `bookingId` int(11) NOT NULL,
  `paymentMethod` varchar(50) DEFAULT NULL,
  `paymentStatus` char(1) DEFAULT NULL COMMENT 'y=paid, n=unpaid',
  `amount` decimal(12,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `tbl_checkout`
--

INSERT INTO `tbl_checkout` (`checkoutId`, `bookingId`, `paymentMethod`, `paymentStatus`, `amount`, `created_at`) VALUES
(4, 5, NULL, 'n', 300000.00, '2026-03-20 03:57:18'),
(6, 7, 'office-payment', 'n', 3005000.00, '2026-03-20 04:40:47'),
(7, 8, 'office-payment', 'n', 2000000.00, '2026-03-20 04:42:26'),
(8, 9, 'office-payment', 'n', 375000.00, '2026-03-22 01:41:55'),
(9, 10, 'office-payment', 'n', 3010000.00, '2026-03-22 02:08:37'),
(10, 11, 'office-payment', 'n', 1500000.00, '2026-04-01 12:11:04'),
(11, 13, 'momo-payment', 'y', 525000.00, '2026-04-23 18:02:11');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_contact`
--

CREATE TABLE `tbl_contact` (
  `contactId` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `phoneNumber` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `isReply` char(1) DEFAULT 'n'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_images`
--

CREATE TABLE `tbl_images` (
  `imageId` int(11) NOT NULL,
  `tourId` int(11) NOT NULL,
  `imageUrl` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `tbl_images`
--

INSERT INTO `tbl_images` (`imageId`, `tourId`, `imageUrl`, `description`) VALUES
(1, 6, 'cau-vang-da-nang_1775281412.png', NULL),
(2, 6, 'ba-na-hill-da-nang-1_1775407853.png', NULL),
(3, 6, 'ben-trong-ba-na-hills-da-nang_1775407854.png', NULL),
(4, 7, 'khung-canh-da-nang-tu-tren-cao_1775281411.png', NULL),
(5, 7, 'cau-vang-da-nang_1775407854.png', NULL),
(6, 7, 'ba-na-hill-da-nang-1_1775408993.png', NULL),
(7, 8, '4b3fb9fc016382fe6efd1b9632e1d8b2_1775651556.jpg', NULL),
(8, 8, 'a717a66ed9500aff2449dbb09fd802c5_1775651556.jpg', NULL),
(9, 8, 'd88d0ece9a70bdce144cb24c5dd501dd_1775651556.jpg', NULL),
(10, 7, 'cau-vang-da-nang_1775281412.png', NULL),
(11, 7, 'ben-trong-ba-na-hills-da-nang_1775407854.png', NULL),
(12, 6, 'ba-na-hill-da-nang_1775281413.png', NULL),
(13, 6, 'cau-vang-da-nang_1775407854.png', NULL),
(14, 8, '6e13e3987941f56ed99a91184a78e838_1775650422.jpg', NULL),
(15, 8, 'ae5280550f8e1a6636987f7aec2f0a40_1775650421.jpg', NULL),
(21, 11, 'a717a66ed9500aff2449dbb09fd802c5_1775651556_1776916002_1776965195.jpg', 'a717a66ed9500aff2449dbb09fd802c5_1775651556_1776916002'),
(22, 11, 'ae5280550f8e1a6636987f7aec2f0a40_1775650421_1776965195.jpg', 'ae5280550f8e1a6636987f7aec2f0a40_1775650421'),
(23, 11, 'bai-sao-phu-quoc_1775045528_-_Copy_1776916579_1776965196.jpg', 'bai-sao-phu-quoc_1775045528_-_Copy_1776916579'),
(24, 11, 'cot-moc-so-0-ha-giang_1775206845_1776965196.jpg', 'cot-moc-so-0-ha-giang_1775206845'),
(25, 11, 'ba-na-hill-da-nang_1775281413_1776965197.png', 'ba-na-hill-da-nang_1775281413');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_reviews`
--

CREATE TABLE `tbl_reviews` (
  `reviewId` int(11) NOT NULL,
  `tourId` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `rating` int(11) DEFAULT NULL CHECK (`rating` between 1 and 5),
  `content` text DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_temp_images`
--

CREATE TABLE `tbl_temp_images` (
  `tempImageId` bigint(20) UNSIGNED NOT NULL,
  `tourId` bigint(20) UNSIGNED NOT NULL,
  `imageTempURL` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_timeline`
--

CREATE TABLE `tbl_timeline` (
  `timelineId` int(11) NOT NULL,
  `tourId` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `tbl_timeline`
--

INSERT INTO `tbl_timeline` (`timelineId`, `tourId`, `title`, `description`, `created_at`, `updated_at`) VALUES
(1, 2, 'day 1', NULL, '2026-03-06 02:29:54', '2026-03-06 02:29:54'),
(2, 4, 'day 1', '<p>tham quan vịnh Hạ Long</p>', '2026-03-06 02:34:51', '2026-03-06 02:34:51'),
(3, 4, 'day 2', '<p>da nang</p>', '2026-03-06 02:34:51', '2026-03-06 02:34:51'),
(4, 6, 'a', NULL, '2026-03-20 03:33:15', '2026-03-20 03:33:15'),
(5, 6, 'b', NULL, '2026-03-20 03:33:15', '2026-03-20 03:33:15'),
(6, 6, 'c', NULL, '2026-03-20 03:33:15', '2026-03-20 03:33:15'),
(7, 6, 'd', NULL, '2026-03-20 03:33:15', '2026-03-20 03:33:15'),
(8, 6, 'e', NULL, '2026-03-20 03:33:15', '2026-03-20 03:33:15'),
(9, 6, 'f', NULL, '2026-03-20 03:33:15', '2026-03-20 03:33:15'),
(10, 6, 'g', NULL, '2026-03-20 03:33:15', '2026-03-20 03:33:15'),
(11, 6, 'h', NULL, '2026-03-20 03:33:15', '2026-03-20 03:33:15'),
(12, 7, 'a', NULL, '2026-03-20 04:17:08', '2026-03-20 04:17:08'),
(13, 7, 'b', NULL, '2026-03-20 04:17:08', '2026-03-20 04:17:08'),
(14, 8, 'day 1', '<p>đi eo gió</p>', '2026-03-22 02:44:58', '2026-03-22 02:44:58'),
(15, 8, 'day 2', '<p>đi Hòn khô</p>', '2026-03-22 02:44:58', '2026-03-22 02:44:58'),
(16, 8, 'day 3', '<p>đi kỳ co</p>', '2026-03-22 02:44:58', '2026-03-22 02:44:58'),
(17, 11, 'day 1', '<p>tap&nbsp;</p>', '2026-04-23 17:27:08', '2026-04-23 17:27:08'),
(18, 11, 'day 2', '<p>di bo</p>', '2026-04-23 17:27:08', '2026-04-23 17:27:08');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_tours`
--

CREATE TABLE `tbl_tours` (
  `tourId` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `priceAdult` decimal(10,2) DEFAULT NULL,
  `priceChild` decimal(10,2) DEFAULT NULL,
  `time` varchar(100) DEFAULT NULL,
  `duration` varchar(100) DEFAULT NULL,
  `destination` varchar(255) DEFAULT NULL,
  `domain` char(1) DEFAULT NULL COMMENT 'b=bac, t=trung, n=nam',
  `quantity` int(11) DEFAULT NULL,
  `availability` tinyint(1) DEFAULT 1,
  `startDate` date DEFAULT NULL,
  `endDate` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `tbl_tours`
--

INSERT INTO `tbl_tours` (`tourId`, `title`, `description`, `priceAdult`, `priceChild`, `time`, `duration`, `destination`, `domain`, `quantity`, `availability`, `startDate`, `endDate`) VALUES
(6, 'ben', '<p>snvv</p>', 150000.00, 75000.00, '10 ngày 9 đêm', NULL, 'tp đà nẵng', 't', 9995, 1, '2026-03-21', '2026-03-31'),
(7, 'nhanvienmoiden', '<p>giacat co</p>', 1000000.00, 5000.00, '3 ngày 2 đêm', NULL, 'tp đà nẵng', 't', 123445, 1, '2026-03-30', '2026-04-02'),
(8, 'Tour Du Lịch Kỳ Co + Biển Hồ + Eo Gió', '<p>Du lịch 3 ngày 2 đêm tại QN BĐ</p>', 1500000.00, 150000.00, '9 ngày 8 đêm', NULL, 'Bình Định(Quy Nhơn)', 't', 999999, 1, '2026-03-22', '2026-03-31'),
(10, 'Test Tour Fix', '<p>Testing tour fix description.</p>', 500000.00, 250000.00, '8 ngày 7 đêm', NULL, 'Da Nang', 't', 50, 0, '2026-04-24', '2026-05-01'),
(11, 'hai', '<p>re</p>', 150000.00, 75000.00, '7 ngày 6 đêm', NULL, 'tp đà nẵng', 't', 1111107, 1, '2026-04-26', '2026-05-02');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_tour_schedules`
--

CREATE TABLE `tbl_tour_schedules` (
  `scheduleId` int(10) UNSIGNED NOT NULL,
  `tourId` int(11) NOT NULL,
  `startDate` date NOT NULL,
  `endDate` date NOT NULL,
  `priceAdult` decimal(12,2) DEFAULT NULL,
  `priceChild` decimal(12,2) DEFAULT NULL,
  `quantity` int(11) NOT NULL DEFAULT 100,
  `note` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `tbl_tour_schedules`
--

INSERT INTO `tbl_tour_schedules` (`scheduleId`, `tourId`, `startDate`, `endDate`, `priceAdult`, `priceChild`, `quantity`, `note`, `created_at`, `updated_at`) VALUES
(1, 6, '2026-05-10', '2026-05-20', 150000.00, 75000.00, 20, NULL, '2026-04-23 02:23:22', '2026-04-23 02:23:22'),
(2, 6, '2026-05-25', '2026-06-04', 160000.00, 80000.00, 15, 'Khuyến mãi cuối tháng', '2026-04-23 02:23:22', '2026-04-23 02:23:22'),
(3, 6, '2026-06-15', '2026-06-25', 150000.00, 75000.00, 25, NULL, '2026-04-23 02:23:22', '2026-04-23 02:23:22'),
(4, 7, '2026-05-05', '2026-05-08', 1000000.00, 500000.00, 30, NULL, '2026-04-23 02:23:22', '2026-04-23 02:23:22'),
(5, 7, '2026-05-20', '2026-05-23', 1100000.00, 550000.00, 20, 'Lễ 30/4 - 1/5', '2026-04-23 02:23:22', '2026-04-23 02:23:22'),
(6, 7, '2026-06-10', '2026-06-13', 1000000.00, 500000.00, 35, NULL, '2026-04-23 02:23:22', '2026-04-23 02:23:22'),
(7, 8, '2026-05-15', '2026-05-18', 1500000.00, 750000.00, 25, NULL, '2026-04-23 02:23:22', '2026-04-23 02:23:22'),
(8, 8, '2026-05-28', '2026-05-31', 1600000.00, 800000.00, 20, 'Mùa hè', '2026-04-23 02:23:22', '2026-04-23 02:23:22'),
(9, 8, '2026-06-20', '2026-06-23', 1500000.00, 750000.00, 30, NULL, '2026-04-23 02:23:22', '2026-04-23 02:23:22'),
(10, 10, '2026-04-24', '2026-05-01', 500000.00, 250000.00, 50, NULL, '2026-04-23 17:33:17', '2026-04-23 17:33:17'),
(11, 11, '2026-04-26', '2026-05-02', 150000.00, 75000.00, 1111111, NULL, '2026-04-23 17:33:17', '2026-04-23 17:33:17'),
(12, 11, '2026-05-03', '2026-05-13', 150000.00, 75000.00, 50, 'blabla', '2026-04-23 18:03:08', '2026-04-23 18:03:08');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_users`
--

CREATE TABLE `tbl_users` (
  `userId` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `google_id` varchar(100) DEFAULT NULL,
  `activation_token` varchar(255) DEFAULT NULL,
  `isActive` char(1) DEFAULT 'n',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `avatar` varchar(255) DEFAULT NULL,
  `fullName` varchar(100) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `phoneNumber` int(11) DEFAULT NULL,
  `status` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `tbl_users`
--

INSERT INTO `tbl_users` (`userId`, `username`, `password`, `email`, `google_id`, `activation_token`, `isActive`, `created_at`, `avatar`, `fullName`, `address`, `phoneNumber`, `status`) VALUES
(1, 'a', 'e10adc3949ba59abbe56e057f20f883e', 'a@gmail.com', NULL, 'ulcTeRjDjLjxwmi0e22WGLnVUgzWDZ0TbkORFhbDFPWSwYPFQbuWrmvFQsqe', 'y', '2026-03-06 01:07:07', '1776967526.png', 'tue', 'ngu hanh son', 1264402926, ''),
(2, 'b', 'e10adc3949ba59abbe56e057f20f883e', 'b@gmail.com', NULL, 'MpDDxyJCt6cNMpPWoOAPclBUbG1RFntlNLY6sx4gIvUD62QeVv1R6zHBnkDl', 'y', '2026-03-20 03:11:26', NULL, NULL, NULL, NULL, NULL),
(3, 'c', 'e10adc3949ba59abbe56e057f20f883e', 'c@gmai.com', NULL, 'wCpnpCgaozQ5biOAQ9ZWwCSF5vD7JFzT3IwMy3bJs26IKRiShxFYMEjM0Va0', 'y', '2026-03-20 03:23:19', NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_wishlists`
--

CREATE TABLE `tbl_wishlists` (
  `wishlistId` int(10) UNSIGNED NOT NULL,
  `tourId` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Chỉ mục cho bảng `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Chỉ mục cho bảng `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`email`);

--
-- Chỉ mục cho bảng `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Chỉ mục cho bảng `tbl_admin`
--
ALTER TABLE `tbl_admin`
  ADD PRIMARY KEY (`adminId`);

--
-- Chỉ mục cho bảng `tbl_booking`
--
ALTER TABLE `tbl_booking`
  ADD PRIMARY KEY (`bookingId`),
  ADD KEY `tourId` (`tourId`),
  ADD KEY `userId` (`userId`);

--
-- Chỉ mục cho bảng `tbl_checkout`
--
ALTER TABLE `tbl_checkout`
  ADD PRIMARY KEY (`checkoutId`),
  ADD KEY `bookingId` (`bookingId`);

--
-- Chỉ mục cho bảng `tbl_contact`
--
ALTER TABLE `tbl_contact`
  ADD PRIMARY KEY (`contactId`);

--
-- Chỉ mục cho bảng `tbl_images`
--
ALTER TABLE `tbl_images`
  ADD PRIMARY KEY (`imageId`),
  ADD KEY `tourId` (`tourId`);

--
-- Chỉ mục cho bảng `tbl_reviews`
--
ALTER TABLE `tbl_reviews`
  ADD PRIMARY KEY (`reviewId`),
  ADD KEY `tourId` (`tourId`),
  ADD KEY `userId` (`userId`);

--
-- Chỉ mục cho bảng `tbl_temp_images`
--
ALTER TABLE `tbl_temp_images`
  ADD PRIMARY KEY (`tempImageId`);

--
-- Chỉ mục cho bảng `tbl_timeline`
--
ALTER TABLE `tbl_timeline`
  ADD PRIMARY KEY (`timelineId`);

--
-- Chỉ mục cho bảng `tbl_tours`
--
ALTER TABLE `tbl_tours`
  ADD PRIMARY KEY (`tourId`);

--
-- Chỉ mục cho bảng `tbl_tour_schedules`
--
ALTER TABLE `tbl_tour_schedules`
  ADD PRIMARY KEY (`scheduleId`),
  ADD KEY `tbl_tour_schedules_tourid_foreign` (`tourId`);

--
-- Chỉ mục cho bảng `tbl_users`
--
ALTER TABLE `tbl_users`
  ADD PRIMARY KEY (`userId`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Chỉ mục cho bảng `tbl_wishlists`
--
ALTER TABLE `tbl_wishlists`
  ADD PRIMARY KEY (`wishlistId`),
  ADD KEY `tbl_wishlists_tourid_foreign` (`tourId`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT cho bảng `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `tbl_admin`
--
ALTER TABLE `tbl_admin`
  MODIFY `adminId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `tbl_booking`
--
ALTER TABLE `tbl_booking`
  MODIFY `bookingId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT cho bảng `tbl_checkout`
--
ALTER TABLE `tbl_checkout`
  MODIFY `checkoutId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT cho bảng `tbl_contact`
--
ALTER TABLE `tbl_contact`
  MODIFY `contactId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `tbl_images`
--
ALTER TABLE `tbl_images`
  MODIFY `imageId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT cho bảng `tbl_reviews`
--
ALTER TABLE `tbl_reviews`
  MODIFY `reviewId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `tbl_temp_images`
--
ALTER TABLE `tbl_temp_images`
  MODIFY `tempImageId` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `tbl_timeline`
--
ALTER TABLE `tbl_timeline`
  MODIFY `timelineId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT cho bảng `tbl_tours`
--
ALTER TABLE `tbl_tours`
  MODIFY `tourId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT cho bảng `tbl_tour_schedules`
--
ALTER TABLE `tbl_tour_schedules`
  MODIFY `scheduleId` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT cho bảng `tbl_users`
--
ALTER TABLE `tbl_users`
  MODIFY `userId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `tbl_wishlists`
--
ALTER TABLE `tbl_wishlists`
  MODIFY `wishlistId` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `tbl_booking`
--
ALTER TABLE `tbl_booking`
  ADD CONSTRAINT `tbl_booking_ibfk_1` FOREIGN KEY (`tourId`) REFERENCES `tbl_tours` (`tourId`) ON DELETE CASCADE,
  ADD CONSTRAINT `tbl_booking_ibfk_2` FOREIGN KEY (`userId`) REFERENCES `tbl_users` (`userId`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `tbl_checkout`
--
ALTER TABLE `tbl_checkout`
  ADD CONSTRAINT `tbl_checkout_ibfk_1` FOREIGN KEY (`bookingId`) REFERENCES `tbl_booking` (`bookingId`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `tbl_images`
--
ALTER TABLE `tbl_images`
  ADD CONSTRAINT `tbl_images_ibfk_1` FOREIGN KEY (`tourId`) REFERENCES `tbl_tours` (`tourId`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `tbl_reviews`
--
ALTER TABLE `tbl_reviews`
  ADD CONSTRAINT `tbl_reviews_ibfk_1` FOREIGN KEY (`tourId`) REFERENCES `tbl_tours` (`tourId`) ON DELETE CASCADE,
  ADD CONSTRAINT `tbl_reviews_ibfk_2` FOREIGN KEY (`userId`) REFERENCES `tbl_users` (`userId`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `tbl_tour_schedules`
--
ALTER TABLE `tbl_tour_schedules`
  ADD CONSTRAINT `tbl_tour_schedules_tourid_foreign` FOREIGN KEY (`tourId`) REFERENCES `tbl_tours` (`tourId`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `tbl_wishlists`
--
ALTER TABLE `tbl_wishlists`
  ADD CONSTRAINT `tbl_wishlists_tourid_foreign` FOREIGN KEY (`tourId`) REFERENCES `tbl_tours` (`tourId`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
