/**
 * ============================================
 * MIDDLEWARE: XÁC THỰC ADMIN (JWT)
 * ============================================
 * Kiểm tra JWT token dành riêng cho admin.
 * Sử dụng secret key riêng (JWT_ADMIN_SECRET).
 * Gắn req.admin = { adminId, username } nếu hợp lệ.
 */

const jwt = require('jsonwebtoken');
const { sendError } = require('../utils/response');

const adminAuth = (req, res, next) => {
  try {
    // Lấy token từ header Authorization: Bearer <token>
    const authHeader = req.headers.authorization;
    if (!authHeader || !authHeader.startsWith('Bearer ')) {
      return sendError(res, 'Quyền truy cập bị từ chối. Vui lòng đăng nhập admin.', 401);
    }

    const token = authHeader.split(' ')[1];

    // Xác minh token với admin secret
    const decoded = jwt.verify(token, process.env.JWT_ADMIN_SECRET);

    // Gắn thông tin admin vào request
    req.admin = {
      adminId: decoded.adminId,
      username: decoded.username
    };

    next();
  } catch (error) {
    if (error.name === 'TokenExpiredError') {
      return sendError(res, 'Phiên đăng nhập admin đã hết hạn.', 401);
    }
    return sendError(res, 'Token admin không hợp lệ.', 403);
  }
};

module.exports = adminAuth;
