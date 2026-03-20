/**
 * ============================================
 * MIDDLEWARE: XÁC THỰC NGƯỜI DÙNG (JWT)
 * ============================================
 * Kiểm tra JWT token từ header Authorization.
 * Nếu hợp lệ, gắn thông tin user vào req.user.
 * Sử dụng cho các route cần đăng nhập (client).
 */

const jwt = require('jsonwebtoken');
const { sendError } = require('../utils/response');

const auth = (req, res, next) => {
  try {
    // Lấy token từ header Authorization: Bearer <token>
    const authHeader = req.headers.authorization;
    if (!authHeader || !authHeader.startsWith('Bearer ')) {
      return sendError(res, 'Vui lòng đăng nhập để tiếp tục.', 401);
    }

    const token = authHeader.split(' ')[1];

    // Xác minh token
    const decoded = jwt.verify(token, process.env.JWT_SECRET);

    // Gắn thông tin user vào request để các controller sử dụng
    req.user = {
      userId: decoded.userId,
      username: decoded.username
    };

    next(); // Tiếp tục xử lý request
  } catch (error) {
    if (error.name === 'TokenExpiredError') {
      return sendError(res, 'Phiên đăng nhập đã hết hạn. Vui lòng đăng nhập lại.', 401);
    }
    return sendError(res, 'Token không hợp lệ.', 401);
  }
};

module.exports = auth;
