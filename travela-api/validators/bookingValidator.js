/**
 * ============================================
 * VALIDATOR: ĐẶT TOUR (Booking)
 * ============================================
 */

const { body } = require('express-validator');

const bookingValidator = {
  /**
   * Validate tạo booking
   * - tourId: bắt buộc, số nguyên dương
   * - numAdults: tùy chọn, số nguyên >= 0
   * - numChildren: tùy chọn, số nguyên >= 0
   * - totalPrice: bắt buộc, số dương
   * - paymentMethod: tùy chọn, enum
   */
  create: [
    body('tourId')
      .notEmpty().withMessage('Mã tour không được để trống.')
      .isInt({ min: 1 }).withMessage('Mã tour phải là số nguyên dương.'),

    body('numAdults')
      .optional()
      .isInt({ min: 0 }).withMessage('Số người lớn phải là số nguyên >= 0.'),

    body('numChildren')
      .optional()
      .isInt({ min: 0 }).withMessage('Số trẻ em phải là số nguyên >= 0.'),

    body('totalPrice')
      .notEmpty().withMessage('Tổng giá không được để trống.')
      .isFloat({ min: 0 }).withMessage('Tổng giá phải là số dương.'),

    body('paymentMethod')
      .optional()
      .isIn(['cash', 'momo-payment', 'paypal-payment']).withMessage('Phương thức thanh toán không hợp lệ. Chọn: cash, momo-payment, paypal-payment.')
  ],

  /**
   * Validate hủy booking
   * - tourId: bắt buộc
   * - numAdults, numChildren: tùy chọn
   */
  cancel: [
    body('tourId')
      .notEmpty().withMessage('Mã tour không được để trống.')
      .isInt({ min: 1 }).withMessage('Mã tour phải là số nguyên dương.'),

    body('numAdults')
      .optional()
      .isInt({ min: 0 }).withMessage('Số người lớn phải là số nguyên >= 0.'),

    body('numChildren')
      .optional()
      .isInt({ min: 0 }).withMessage('Số trẻ em phải là số nguyên >= 0.')
  ],

  /**
   * Validate checkBooking
   * - tourId: bắt buộc
   */
  check: [
    body('tourId')
      .notEmpty().withMessage('Mã tour không được để trống.')
      .isInt({ min: 1 }).withMessage('Mã tour phải là số nguyên dương.')
  ]
};

module.exports = bookingValidator;
