/**
 * ROUTES: Hồ sơ người dùng (User Profile)
 */
const express = require('express');
const router = express.Router();
const UserController = require('../controllers/userController');
const auth = require('../middleware/auth');
const { uploadAvatar } = require('../middleware/upload');

// Tất cả routes cần đăng nhập
router.use(auth);

// GET /api/users/profile - Xem thông tin
router.get('/profile', UserController.getProfile);

// PUT /api/users/profile - Cập nhật thông tin
router.put('/profile', UserController.updateProfile);

// PUT /api/users/change-password - Đổi mật khẩu
router.put('/change-password', UserController.changePassword);

// PUT /api/users/change-avatar - Đổi ảnh đại diện
router.put('/change-avatar', uploadAvatar, UserController.changeAvatar);

module.exports = router;
