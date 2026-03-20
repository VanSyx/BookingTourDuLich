/**
 * ROUTES: Admin - Tất cả routes admin
 * Gộp các route admin vào 1 file cho gọn.
 */
const express = require('express');
const router = express.Router();
const adminAuth = require('../../middleware/adminAuth');
const { uploadTourImage } = require('../../middleware/upload');

// Controllers
const AdminAuthController = require('../../controllers/admin/adminAuthController');
const TourManagementController = require('../../controllers/admin/tourManagementController');
const BookingManagementController = require('../../controllers/admin/bookingManagementController');
const UserManagementController = require('../../controllers/admin/userManagementController');
const ContactManagementController = require('../../controllers/admin/contactManagementController');

// ============================================
// XÁC THỰC ADMIN (không cần middleware)
// ============================================
router.post('/auth/login', AdminAuthController.login);

// ============================================
// TẤT CẢ ROUTES BÊN DƯỚI CẦN ADMIN AUTH
// ============================================
router.use(adminAuth);

// --- Dashboard ---
router.get('/dashboard', AdminAuthController.dashboard);

// --- Admin Profile ---
router.get('/profile', AdminAuthController.getProfile);
router.put('/profile', AdminAuthController.updateProfile);

// --- Quản lý Tours ---
router.get('/tours', TourManagementController.getAll);
router.get('/tours/:id', TourManagementController.getById);
router.post('/tours', TourManagementController.create);
router.put('/tours/:id', TourManagementController.update);
router.delete('/tours/:id', TourManagementController.delete);
router.post('/tours/:id/images', uploadTourImage, TourManagementController.uploadImage);
router.post('/tours/:id/timeline', TourManagementController.addTimeline);

// --- Quản lý Booking ---
router.get('/bookings', BookingManagementController.getAll);
router.get('/bookings/:id', BookingManagementController.getDetail);
router.put('/bookings/:id/confirm', BookingManagementController.confirm);
router.put('/bookings/:id/finish', BookingManagementController.finish);
router.put('/bookings/:id/payment', BookingManagementController.receivedMoney);
router.post('/bookings/:id/send-invoice', BookingManagementController.sendInvoice);

// --- Quản lý Users ---
router.get('/users', UserManagementController.getAll);
router.put('/users/:id/activate', UserManagementController.activate);
router.put('/users/:id/status', UserManagementController.changeStatus);

// --- Quản lý Contact ---
router.get('/contacts', ContactManagementController.getAll);
router.post('/contacts/:id/reply', ContactManagementController.reply);

module.exports = router;
