/**
 * ROUTES: Thanh toán (Payment)
 */
const express = require('express');
const router = express.Router();
const PaymentController = require('../controllers/paymentController');
const auth = require('../middleware/auth');

// POST /api/payments/momo - Tạo thanh toán MoMo
router.post('/momo', auth, PaymentController.createMomo);

// GET /api/payments/momo/callback - Callback MoMo
router.get('/momo/callback', PaymentController.momoCallback);

module.exports = router;
