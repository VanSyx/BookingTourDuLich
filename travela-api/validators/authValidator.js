/**
 * ============================================
 * VALIDATOR: XÁC THỰC (Authentication)
 * ============================================
 * Quy tắc validate cho register và login.
 */

const { body } = require('express-validator');

const authValidator = {
  /**
   * Validate đăng ký tài khoản
   * - username: bắt buộc, 3-50 ký tự, chỉ chữ/số/gạch dưới
   * - email: bắt buộc, đúng định dạng email
   * - password: bắt buộc, tối thiểu 6 ký tự
   */
  register: [
    body('username')
      .notEmpty().withMessage('Tên đăng nhập không được để trống.')
      .isLength({ min: 3, max: 50 }).withMessage('Tên đăng nhập phải từ 3 đến 50 ký tự.')
      .matches(/^[a-zA-Z0-9_]+$/).withMessage('Tên đăng nhập chỉ được chứa chữ cái, số và dấu gạch dưới.'),

    body('email')
      .notEmpty().withMessage('Email không được để trống.')
      .isEmail().withMessage('Email không đúng định dạng.')
      .normalizeEmail(),

    body('password')
      .notEmpty().withMessage('Mật khẩu không được để trống.')
      .isLength({ min: 6 }).withMessage('Mật khẩu phải có ít nhất 6 ký tự.')
  ],

  /**
   * Validate đăng nhập
   * - username: bắt buộc
   * - password: bắt buộc
   */
  login: [
    body('username')
      .notEmpty().withMessage('Vui lòng nhập tên đăng nhập.'),

    body('password')
      .notEmpty().withMessage('Vui lòng nhập mật khẩu.')
  ]
};

module.exports = authValidator;
