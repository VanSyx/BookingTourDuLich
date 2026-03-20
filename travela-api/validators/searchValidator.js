/**
 * ============================================
 * VALIDATOR: TÌM KIẾM (Search)
 * ============================================
 */

const { query } = require('express-validator');

const searchValidator = {
  /**
   * Validate tìm kiếm theo điểm đến
   * - destination: tùy chọn, string
   * - start_date: tùy chọn, đúng format dd/mm/yyyy
   * - end_date: tùy chọn, đúng format dd/mm/yyyy
   */
  search: [
    query('destination')
      .optional()
      .isLength({ max: 100 }).withMessage('Điểm đến không được vượt quá 100 ký tự.')
      .trim(),

    query('start_date')
      .optional()
      .matches(/^\d{2}\/\d{2}\/\d{4}$/).withMessage('Ngày bắt đầu phải theo format dd/mm/yyyy.'),

    query('end_date')
      .optional()
      .matches(/^\d{2}\/\d{2}\/\d{4}$/).withMessage('Ngày kết thúc phải theo format dd/mm/yyyy.')
  ],

  /**
   * Validate tìm kiếm theo từ khóa
   * - keyword: bắt buộc, 1-200 ký tự
   */
  keyword: [
    query('keyword')
      .notEmpty().withMessage('Vui lòng nhập từ khóa tìm kiếm.')
      .isLength({ max: 200 }).withMessage('Từ khóa không được vượt quá 200 ký tự.')
      .trim()
  ]
};

module.exports = searchValidator;
