/**
 * ============================================
 * MIDDLEWARE: XỬ LÝ LỖI VALIDATION
 * ============================================
 * Dùng chung cho tất cả routes có validation.
 * Kiểm tra kết quả từ express-validator,
 * nếu có lỗi → trả về danh sách lỗi rõ ràng.
 */

const { validationResult } = require('express-validator');

const validate = (req, res, next) => {
  const errors = validationResult(req);
  if (!errors.isEmpty()) {
    // Lấy message lỗi đầu tiên hoặc gộp tất cả
    const errorMessages = errors.array().map(err => err.msg);
    return res.status(400).json({
      success: false,
      message: errorMessages[0], // Trả message lỗi đầu tiên
      errors: errorMessages       // Trả tất cả lỗi để frontend hiển thị
    });
  }
  next();
};

module.exports = validate;
