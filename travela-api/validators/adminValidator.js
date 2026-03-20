/**
 * ============================================
 * VALIDATOR: ADMIN
 * ============================================
 * Quy tắc validate cho các endpoint admin.
 */

const { body } = require('express-validator');

const adminValidator = {
  /**
   * Validate đăng nhập admin
   */
  login: [
    body('username')
      .notEmpty().withMessage('Vui lòng nhập tên đăng nhập.'),

    body('password')
      .notEmpty().withMessage('Vui lòng nhập mật khẩu.')
  ],

  /**
   * Validate tạo tour (admin)
   * - name: bắt buộc, max 255 ký tự
   * - destination: bắt buộc, max 255 ký tự
   * - domain: bắt buộc, 1 trong 'b','t','n'
   * - number (quantity): bắt buộc, số nguyên >= 1
   * - price_adult: bắt buộc, số dương
   * - price_child: bắt buộc, số >= 0
   * - start_date, end_date: bắt buộc
   * - description: tùy chọn
   */
  createTour: [
    body('name')
      .notEmpty().withMessage('Tên tour không được để trống.')
      .isLength({ max: 255 }).withMessage('Tên tour không được vượt quá 255 ký tự.')
      .trim(),

    body('destination')
      .notEmpty().withMessage('Điểm đến không được để trống.')
      .isLength({ max: 255 }).withMessage('Điểm đến không được vượt quá 255 ký tự.')
      .trim(),

    body('domain')
      .notEmpty().withMessage('Miền không được để trống.')
      .isIn(['b', 't', 'n']).withMessage('Miền phải là "b" (Bắc), "t" (Trung), hoặc "n" (Nam).'),

    body('number')
      .notEmpty().withMessage('Số lượng chỗ không được để trống.')
      .isInt({ min: 1 }).withMessage('Số lượng chỗ phải là số nguyên >= 1.'),

    body('price_adult')
      .notEmpty().withMessage('Giá người lớn không được để trống.')
      .isFloat({ min: 0 }).withMessage('Giá người lớn phải là số >= 0.'),

    body('price_child')
      .notEmpty().withMessage('Giá trẻ em không được để trống.')
      .isFloat({ min: 0 }).withMessage('Giá trẻ em phải là số >= 0.'),

    body('start_date')
      .notEmpty().withMessage('Ngày bắt đầu không được để trống.'),

    body('end_date')
      .notEmpty().withMessage('Ngày kết thúc không được để trống.')
  ],

  /**
   * Validate thay đổi trạng thái user
   * - status: bắt buộc, 'b' hoặc 'd'
   */
  changeUserStatus: [
    body('status')
      .notEmpty().withMessage('Trạng thái không được để trống.')
      .isIn(['b', 'd']).withMessage('Trạng thái phải là "b" (chặn) hoặc "d" (xóa).')
  ],

  /**
   * Validate phản hồi liên hệ
   * - replyMessage: bắt buộc, 1-2000 ký tự
   */
  replyContact: [
    body('replyMessage')
      .notEmpty().withMessage('Vui lòng nhập nội dung phản hồi.')
      .isLength({ max: 2000 }).withMessage('Phản hồi không được vượt quá 2000 ký tự.')
      .trim()
  ],

  /**
   * Validate thêm timeline
   * - timelines: bắt buộc, mảng không rỗng
   */
  addTimeline: [
    body('timelines')
      .isArray({ min: 1 }).withMessage('Dữ liệu lịch trình phải là mảng và có ít nhất 1 mục.'),

    body('timelines.*.title')
      .notEmpty().withMessage('Tiêu đề lịch trình không được để trống.'),

    body('timelines.*.description')
      .optional()
      .trim()
  ],

  /**
   * Validate gửi hóa đơn
   * - email: tùy chọn, đúng format
   */
  sendInvoice: [
    body('email')
      .optional()
      .isEmail().withMessage('Email không đúng định dạng.')
  ],

  /**
   * Validate thanh toán MoMo
   * - amount: bắt buộc, số dương
   * - tourId: bắt buộc
   */
  momoPayment: [
    body('amount')
      .notEmpty().withMessage('Số tiền không được để trống.')
      .isFloat({ min: 1000 }).withMessage('Số tiền phải >= 1,000 VNĐ.'),

    body('tourId')
      .notEmpty().withMessage('Mã tour không được để trống.')
      .isInt({ min: 1 }).withMessage('Mã tour phải là số nguyên dương.')
  ]
};

module.exports = adminValidator;
