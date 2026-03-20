/**
 * ============================================
 * MODEL: LỊCH TRÌNH TOUR (tbl_timeline)
 * ============================================
 */

const { pool } = require('../config/db');

const TimelineModel = {
  /** Thêm lịch trình cho tour */
  create: async (data) => {
    const [result] = await pool.query('INSERT INTO tbl_timeline SET ?', [data]);
    return result.insertId;
  },

  /** Lấy lịch trình của 1 tour */
  getByTourId: async (tourId) => {
    const [rows] = await pool.query(
      'SELECT * FROM tbl_timeline WHERE tourId = ? ORDER BY timelineId ASC',
      [tourId]
    );
    return rows;
  },

  /** Xóa tất cả lịch trình của 1 tour */
  deleteByTourId: async (tourId) => {
    const [result] = await pool.query('DELETE FROM tbl_timeline WHERE tourId = ?', [tourId]);
    return result.affectedRows;
  }
};

module.exports = TimelineModel;
