/**
 * ROUTES: Tours (Phía khách hàng)
 */
const express = require('express');
const router = express.Router();
const TourController = require('../controllers/tourController');

// GET /api/tours - Danh sách tours (phân trang, lọc)
router.get('/', TourController.getAll);

// GET /api/tours/popular - Tours phổ biến
router.get('/popular', TourController.getPopular);

// GET /api/tours/domains - Số lượng theo miền
router.get('/domains', TourController.getDomains);

// GET /api/tours/:id - Chi tiết 1 tour
router.get('/:id', TourController.getById);

module.exports = router;
