/**
 * ============================================
 * VALIDATOR: LIÊN HỆ (Contact)
 * ============================================
 */

const { body } = require('express-validator');

const contactValidator = {
  /**
   * Validate gửi liên hệ
   * - name: bắt buộc, max 100 ký tự
   * - email: bắt buộc, đúng định dạng
   * - phone: tùy chọn, 10-11 số
   * - message: bắt buộc, 10-2000 ký tự
   */
  create: [
    body('name')
      .notEmpty().withMessage('Họ tên không được để trống.')
      .isLength({ max: 100 }).withMessage('Họ tên không được vượt quá 100 ký tự.')
      .trim(),

    body('email')
      .notEmpty().withMessage('Email không được để trống.')
      .isEmail().withMessage('Email không đúng định dạng.')
      .normalizeEmail(),

    body('phone')
      .optional()
      .matches(/^[0-9]{10,11}$/).withMessage('Số điện thoại phải có 10-11 chữ số.'),

    body('message')
      .notEmpty().withMessage('Nội dung tin nhắn không được để trống.')
      .isLength({ min: 10, max: 2000 }).withMessage('Nội dung tin nhắn phải từ 10 đến 2000 ký tự.')
      .trim()
  ]
};

module.exports = contactValidator;
