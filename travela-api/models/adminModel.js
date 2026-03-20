/**
 * ============================================
 * MODEL: ADMIN (tbl_admin)
 * ============================================
 * Các thao tác CSDL liên quan đến bảng tbl_admin.
 */

const { pool } = require('../config/db');

const AdminModel = {
  /**
   * Tìm admin theo username
   */
  findByUsername: async (username) => {
    const [rows] = await pool.query('SELECT * FROM tbl_admin WHERE username = ?', [username]);
    return rows[0] || null;
  },

  /**
   * Tìm admin theo adminId
   */
  findById: async (adminId) => {
    const [rows] = await pool.query('SELECT * FROM tbl_admin WHERE adminId = ?', [adminId]);
    return rows[0] || null;
  },

  /**
   * Cập nhật thông tin admin
   */
  update: async (adminId, data) => {
    const [result] = await pool.query('UPDATE tbl_admin SET ? WHERE adminId = ?', [data, adminId]);
    return result.affectedRows;
  }
};

module.exports = AdminModel;
