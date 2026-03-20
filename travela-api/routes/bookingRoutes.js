/**
 * ROUTES: Đặt tour & Thanh toán (Booking)
 */
const express = require('express');
const router = express.Router();
const BookingController = require('../controllers/bookingController');
const auth = require('../middleware/auth');

// Tất cả routes cần đăng nhập
router.use(auth);

// POST /api/bookings - Tạo booking mới
router.post('/', BookingController.create);

// GET /api/bookings - Danh sách bookings của user
router.get('/', BookingController.getMyBookings);

// GET /api/bookings/:id - Chi tiết booking
router.get('/:id', BookingController.getById);

// POST /api/bookings/:id/cancel - Hủy booking
router.post('/:id/cancel', BookingController.cancel);

// POST /api/bookings/check - Kiểm tra đã đặt tour chưa
router.post('/check', BookingController.checkBooking);

module.exports = router;
