/**
 * ROUTES: Đánh giá (Reviews)
 */
const express = require('express');
const router = express.Router();
const ReviewController = require('../controllers/reviewController');
const auth = require('../middleware/auth');

// POST /api/reviews - Gửi đánh giá (cần auth)
router.post('/', auth, ReviewController.create);

// GET /api/reviews/tour/:tourId - Lấy reviews theo tour
router.get('/tour/:tourId', ReviewController.getByTour);

module.exports = router;
