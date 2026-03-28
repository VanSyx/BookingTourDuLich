-- ============================================
-- TRAVELA DATABASE DESIGN
-- ============================================
-- Dựa trên Class Diagram + Codebase hiện tại
-- Bao gồm các bảng mới: invoice, promotions, history, chat, wishlists
-- Và bổ sung các cột thiếu cho các bảng cũ

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- --------------------------------------------------------
-- 1. BẢNG USERS
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `tbl_users` (
  `userId` INT(11) NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(50) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `email` VARCHAR(100) DEFAULT NULL,
  `google_id` VARCHAR(100) DEFAULT NULL,
  `activation_token` VARCHAR(255) DEFAULT NULL,
  `isActive` CHAR(1) DEFAULT 'n',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP() ON UPDATE CURRENT_TIMESTAMP(),
  `avatar` VARCHAR(255) DEFAULT 'default.png',
  `fullName` VARCHAR(100) DEFAULT NULL,
  `address` VARCHAR(255) DEFAULT NULL,
  `phoneNumber` VARCHAR(20) DEFAULT NULL,
  `role` VARCHAR(20) DEFAULT 'user',
  `status` VARCHAR(10) DEFAULT NULL COMMENT 'b=block, d=delete',
  PRIMARY KEY (`userId`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- 2. BẢNG ADMIN
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `tbl_admin` (
  `adminId` INT(11) NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(45) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `email` VARCHAR(50) NOT NULL,
  `fullName` VARCHAR(100) DEFAULT NULL,
  `address` VARCHAR(255) DEFAULT NULL,
  `role` VARCHAR(20) DEFAULT 'admin',
  `createdDate` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
  PRIMARY KEY (`adminId`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- 3. BẢNG TOURS
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `tbl_tours` (
  `tourId` INT(11) NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(255) DEFAULT NULL,
  `description` TEXT DEFAULT NULL,
  `priceAdult` DECIMAL(12,2) DEFAULT NULL,
  `priceChild` DECIMAL(12,2) DEFAULT NULL,
  `time` VARCHAR(100) DEFAULT NULL COMMENT 'VD: 4 ngày 3 đêm',
  `duration` VARCHAR(100) DEFAULT NULL,
  `destination` VARCHAR(255) DEFAULT NULL,
  `domain` CHAR(1) DEFAULT NULL COMMENT 'b=Bắc, t=Trung, n=Nam',
  `quantity` INT(11) DEFAULT NULL,
  `availability` TINYINT(1) DEFAULT 1,
  `startDate` DATE DEFAULT NULL,
  `endDate` DATE DEFAULT NULL,
  PRIMARY KEY (`tourId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- 4. BẢNG IMAGES
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `tbl_images` (
  `imageId` INT(11) NOT NULL AUTO_INCREMENT,
  `tourId` INT(11) NOT NULL,
  `imageUrl` VARCHAR(255) DEFAULT NULL,
  `description` VARCHAR(255) DEFAULT NULL,
  `uploadDate` TIMESTAMP DEFAULT CURRENT_TIMESTAMP(),
  PRIMARY KEY (`imageId`),
  KEY `tourId` (`tourId`),
  CONSTRAINT `fk_images_tour` FOREIGN KEY (`tourId`) REFERENCES `tbl_tours` (`tourId`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- 5. BẢNG TIMELINE
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `tbl_timeline` (
  `timelineId` INT(11) NOT NULL AUTO_INCREMENT,
  `tourId` INT(11) NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `description` TEXT DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP(),
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP() ON UPDATE CURRENT_TIMESTAMP(),
  PRIMARY KEY (`timelineId`),
  KEY `tourId` (`tourId`),
  CONSTRAINT `fk_timeline_tour` FOREIGN KEY (`tourId`) REFERENCES `tbl_tours` (`tourId`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- 6. BẢNG PROMOTIONS (MỚI)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `tbl_promotions` (
  `promotionId` INT(11) NOT NULL AUTO_INCREMENT,
  `code` VARCHAR(50) NOT NULL COMMENT 'Mã nhập VD: SALE20',
  `description` VARCHAR(255) DEFAULT NULL,
  `discountAmount` DECIMAL(12,2) DEFAULT 0 COMMENT 'Giảm tiền cố định',
  `discountPercent` INT(11) DEFAULT 0 COMMENT 'Giảm phần trăm',
  `startDate` DATE DEFAULT NULL,
  `endDate` DATE DEFAULT NULL,
  `quantity` INT(11) DEFAULT NULL COMMENT 'Số lượt sử dụng',
  `isActive` TINYINT(1) DEFAULT 1,
  PRIMARY KEY (`promotionId`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- 7. BẢNG BOOKING
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `tbl_booking` (
  `bookingId` INT(11) NOT NULL AUTO_INCREMENT,
  `tourId` INT(11) NOT NULL,
  `userId` INT(11) NOT NULL,
  `promotionId` INT(11) DEFAULT NULL,
  `fullName` VARCHAR(100) DEFAULT NULL,
  `email` VARCHAR(100) DEFAULT NULL,
  `phoneNumber` VARCHAR(20) DEFAULT NULL,
  `address` VARCHAR(255) DEFAULT NULL,
  `numAdults` INT(11) DEFAULT 0,
  `numChildren` INT(11) DEFAULT 0,
  `totalPrice` DECIMAL(12,2) DEFAULT NULL,
  `depositAmount` DECIMAL(12,2) DEFAULT NULL,
  `bookingStatus` CHAR(1) DEFAULT 'b' COMMENT 'b=mới, y=paid, f=xong, c=hủy',
  `specialRequests` TEXT DEFAULT NULL,
  `bookingDate` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
  PRIMARY KEY (`bookingId`),
  KEY `tourId` (`tourId`),
  KEY `userId` (`userId`),
  KEY `promotionId` (`promotionId`),
  CONSTRAINT `fk_booking_tour` FOREIGN KEY (`tourId`) REFERENCES `tbl_tours` (`tourId`) ON DELETE CASCADE,
  CONSTRAINT `fk_booking_user` FOREIGN KEY (`userId`) REFERENCES `tbl_users` (`userId`) ON DELETE CASCADE,
  CONSTRAINT `fk_booking_promotion` FOREIGN KEY (`promotionId`) REFERENCES `tbl_promotions` (`promotionId`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- 8. BẢNG CHECKOUT
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `tbl_checkout` (
  `checkoutId` INT(11) NOT NULL AUTO_INCREMENT,
  `bookingId` INT(11) NOT NULL,
  `paymentMethod` VARCHAR(50) DEFAULT NULL COMMENT 'paypal/momo/office',
  `paymentStatus` CHAR(1) DEFAULT 'n' COMMENT 'y=paid, n=unpaid',
  `amount` DECIMAL(12,2) DEFAULT NULL,
  `transactionId` VARCHAR(100) DEFAULT NULL COMMENT 'Mã giao dịch bên thứ 3',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
  PRIMARY KEY (`checkoutId`),
  KEY `bookingId` (`bookingId`),
  CONSTRAINT `fk_checkout_booking` FOREIGN KEY (`bookingId`) REFERENCES `tbl_booking` (`bookingId`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- 9. BẢNG INVOICE (MỚI)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `tbl_invoice` (
  `invoiceId` INT(11) NOT NULL AUTO_INCREMENT,
  `bookingId` INT(11) NOT NULL,
  `amount` DECIMAL(12,2) DEFAULT NULL,
  `dateIssued` DATE DEFAULT NULL,
  `details` TEXT DEFAULT NULL,
  PRIMARY KEY (`invoiceId`),
  KEY `bookingId` (`bookingId`),
  CONSTRAINT `fk_invoice_booking` FOREIGN KEY (`bookingId`) REFERENCES `tbl_booking` (`bookingId`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- 10. BẢNG REVIEWS
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `tbl_reviews` (
  `reviewId` INT(11) NOT NULL AUTO_INCREMENT,
  `tourId` INT(11) NOT NULL,
  `userId` INT(11) NOT NULL,
  `rating` INT(11) DEFAULT NULL CHECK (`rating` BETWEEN 1 AND 5),
  `content` TEXT DEFAULT NULL,
  `timestamp` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
  PRIMARY KEY (`reviewId`),
  KEY `tourId` (`tourId`),
  KEY `userId` (`userId`),
  CONSTRAINT `fk_reviews_tour` FOREIGN KEY (`tourId`) REFERENCES `tbl_tours` (`tourId`) ON DELETE CASCADE,
  CONSTRAINT `fk_reviews_user` FOREIGN KEY (`userId`) REFERENCES `tbl_users` (`userId`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- 11. BẢNG CONTACT
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `tbl_contact` (
  `contactId` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) DEFAULT NULL,
  `email` VARCHAR(100) DEFAULT NULL,
  `message` TEXT DEFAULT NULL,
  `isReply` CHAR(1) DEFAULT 'n',
  PRIMARY KEY (`contactId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- 12. BẢNG HISTORY (MỚI)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `tbl_history` (
  `historyId` INT(11) NOT NULL AUTO_INCREMENT,
  `userId` INT(11) NOT NULL,
  `tourId` INT(11) DEFAULT NULL,
  `action` VARCHAR(255) DEFAULT NULL COMMENT 'VD: Xem tour, Đặt tour',
  `actionType` VARCHAR(50) DEFAULT NULL COMMENT 'view/book/cancel/review',
  `timestamp` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
  PRIMARY KEY (`historyId`),
  KEY `userId` (`userId`),
  KEY `tourId` (`tourId`),
  CONSTRAINT `fk_history_user` FOREIGN KEY (`userId`) REFERENCES `tbl_users` (`userId`) ON DELETE CASCADE,
  CONSTRAINT `fk_history_tour` FOREIGN KEY (`tourId`) REFERENCES `tbl_tours` (`tourId`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- 13. BẢNG CHAT (MỚI)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `tbl_chat` (
  `chatId` INT(11) NOT NULL AUTO_INCREMENT,
  `userId` INT(11) NOT NULL,
  `adminId` INT(11) DEFAULT NULL,
  `message` TEXT NOT NULL,
  `readStatus` TINYINT(1) DEFAULT 0 COMMENT '0=unread, 1=read',
  `createdDate` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
  `ipAddress` VARCHAR(45) DEFAULT NULL,
  PRIMARY KEY (`chatId`),
  KEY `userId` (`userId`),
  KEY `adminId` (`adminId`),
  CONSTRAINT `fk_chat_user` FOREIGN KEY (`userId`) REFERENCES `tbl_users` (`userId`) ON DELETE CASCADE,
  CONSTRAINT `fk_chat_admin` FOREIGN KEY (`adminId`) REFERENCES `tbl_admin` (`adminId`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- 14. BẢNG WISHLISTS (MỚI)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `tbl_wishlists` (
  `wishlistId` INT(11) NOT NULL AUTO_INCREMENT,
  `userId` INT(11) NOT NULL,
  `tourId` INT(11) NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
  PRIMARY KEY (`wishlistId`),
  UNIQUE KEY `unique_user_tour` (`userId`, `tourId`),
  KEY `tourId` (`tourId`),
  CONSTRAINT `fk_wishlists_user` FOREIGN KEY (`userId`) REFERENCES `tbl_users` (`userId`) ON DELETE CASCADE,
  CONSTRAINT `fk_wishlists_tour` FOREIGN KEY (`tourId`) REFERENCES `tbl_tours` (`tourId`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- DỮ LIỆU MẪU
-- --------------------------------------------------------

INSERT INTO `tbl_admin` (`adminId`, `username`, `password`, `email`, `createdDate`, `fullName`, `address`, `role`) VALUES
(1, 'admin', 'e10adc3949ba59abbe56e057f20f883e', 'admin@gmail.com', NOW(), 'Admin Travela', NULL, 'admin');

INSERT INTO `tbl_promotions` (`code`, `description`, `discountPercent`, `startDate`, `endDate`, `quantity`) VALUES
('WELCOME10', 'Giảm 10% cho khách hàng mới', 10, '2026-01-01', '2026-12-31', 100),
('SUMMER50K', 'Giảm 50.000đ cho tour mùa hè', 0, '2026-06-01', '2026-08-31', 50);

UPDATE `tbl_promotions` SET `discountAmount` = 50000 WHERE `code` = 'SUMMER50K';

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
