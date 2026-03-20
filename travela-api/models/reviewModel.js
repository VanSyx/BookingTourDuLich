/**
 * ============================================
 * MODEL: ĐÁNH GIÁ (tbl_reviews)
 * ============================================
 */

const { pool } = require('../config/db');

const ReviewModel = {
  /**
   * Tạo đánh giá mới
   */
  create: async (data) => {
    const reviewData = {
      tourId: data.tourId,
      userId: data.userId,
      rating: data.rating,
      content: data.content || data.comment
    };
    const [result] = await pool.query('INSERT INTO tbl_reviews SET ?', [reviewData]);
    return result.insertId;
  },

  /**
   * Lấy tất cả reviews của 1 tour (kèm thông tin user)
   */
  getByTourId: async (tourId) => {
    const [rows] = await pool.query(`
      SELECT r.*, u.username, u.fullName, u.avatar
      FROM tbl_reviews r
      JOIN tbl_users u ON r.userId = u.userId
      WHERE r.tourId = ?
      ORDER BY r.timestamp DESC
    `, [tourId]);
    return rows;
  },

  /**
   * Thống kê reviews của 1 tour
   */
  getStats: async (tourId) => {
    const [rows] = await pool.query(`
      SELECT ROUND(AVG(rating), 1) as averageRating, COUNT(*) as reviewCount
      FROM tbl_reviews WHERE tourId = ?
    `, [tourId]);
    return rows[0];
  },

  /**
   * Kiểm tra user đã review tour chưa
   */
  checkExist: async (tourId, userId) => {
    const [rows] = await pool.query(
      'SELECT * FROM tbl_reviews WHERE tourId = ? AND userId = ?',
      [tourId, userId]
    );
    return rows.length > 0;
  }
};

module.exports = ReviewModel;
