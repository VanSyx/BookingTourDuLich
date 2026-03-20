/**
 * ============================================
 * VALIDATOR: ĐÁNH GIÁ (Review)
 * ============================================
 */

const { body } = require('express-validator');

const reviewValidator = {
  /**
   * Validate tạo review
   * - tourId: bắt buộc, số nguyên dương
   * - rating: bắt buộc, 1-5
   * - message: tùy chọn, max 1000 ký tự
   */
  create: [
    body('tourId')
      .notEmpty().withMessage('Mã tour không được để trống.')
      .isInt({ min: 1 }).withMessage('Mã tour phải là số nguyên dương.'),

    body('rating')
      .notEmpty().withMessage('Vui lòng chọn số sao đánh giá.')
      .isInt({ min: 1, max: 5 }).withMessage('Rating phải từ 1 đến 5.'),

    body('message')
      .optional()
      .isLength({ max: 1000 }).withMessage('Nội dung đánh giá không được vượt quá 1000 ký tự.')
      .trim()
  ]
};

module.exports = reviewValidator;
