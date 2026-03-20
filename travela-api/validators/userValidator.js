/**
 * ============================================
 * VALIDATOR: HỒ SƠ NGƯỜI DÙNG (User Profile)
 * ============================================
 */

const { body } = require('express-validator');

const userValidator = {
  /**
   * Validate cập nhật hồ sơ
   * - fullName: tùy chọn, max 100 ký tự
   * - email: tùy chọn, đúng định dạng
   * - phone: tùy chọn, 10-11 số
   * - address: tùy chọn, max 255 ký tự
   */
  updateProfile: [
    body('fullName')
      .optional()
      .isLength({ max: 100 }).withMessage('Họ tên không được vượt quá 100 ký tự.')
      .trim(),

    body('email')
      .optional()
      .isEmail().withMessage('Email không đúng định dạng.')
      .normalizeEmail(),

    body('phone')
      .optional()
      .matches(/^[0-9]{10,11}$/).withMessage('Số điện thoại phải có 10-11 chữ số.'),

    body('address')
      .optional()
      .isLength({ max: 255 }).withMessage('Địa chỉ không được vượt quá 255 ký tự.')
      .trim()
  ],

  /**
   * Validate đổi mật khẩu
   * - oldPass: bắt buộc
   * - newPass: bắt buộc, tối thiểu 6 ký tự
   */
  changePassword: [
    body('oldPass')
      .notEmpty().withMessage('Vui lòng nhập mật khẩu cũ.'),

    body('newPass')
      .notEmpty().withMessage('Vui lòng nhập mật khẩu mới.')
      .isLength({ min: 6 }).withMessage('Mật khẩu mới phải có ít nhất 6 ký tự.')
  ]
};

module.exports = userValidator;
