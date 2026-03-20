/**
 * ROUTES: Xác thực (Authentication)
 */
const express = require('express');
const router = express.Router();
const AuthController = require('../controllers/authController');
const auth = require('../middleware/auth');

// POST /api/auth/register - Đăng ký tài khoản
router.post('/register', AuthController.register);

// POST /api/auth/login - Đăng nhập
router.post('/login', AuthController.login);

// GET /api/auth/activate/:token - Kích hoạt tài khoản
router.get('/activate/:token', AuthController.activate);

// GET /api/auth/profile - Lấy thông tin user (cần auth)
router.get('/profile', auth, AuthController.getProfile);

module.exports = router;
