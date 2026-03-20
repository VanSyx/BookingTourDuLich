/**
 * ============================================
 * MODEL: HÌNH ẢNH TOUR (tbl_images)
 * ============================================
 */

const { pool } = require('../config/db');

const ImageModel = {
  /** Thêm ảnh cho tour */
  create: async (data) => {
    const [result] = await pool.query('INSERT INTO tbl_images SET ?', [data]);
    return result.insertId;
  },

  /** Lấy tất cả ảnh của 1 tour */
  getByTourId: async (tourId) => {
    const [rows] = await pool.query('SELECT * FROM tbl_images WHERE tourId = ?', [tourId]);
    return rows;
  },

  /** Xóa tất cả ảnh của 1 tour */
  deleteByTourId: async (tourId) => {
    const [result] = await pool.query('DELETE FROM tbl_images WHERE tourId = ?', [tourId]);
    return result.affectedRows;
  }
};

module.exports = ImageModel;
