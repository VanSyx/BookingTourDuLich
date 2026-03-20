/**
 * ============================================
 * MODEL: LIÊN HỆ (tbl_contact)
 * ============================================
 */

const { pool } = require('../config/db');

const ContactModel = {
  /**
   * Tạo liên hệ mới
   */
  create: async (data) => {
    const [result] = await pool.query('INSERT INTO tbl_contact SET ?', [data]);
    return result.insertId;
  },

  /**
   * Lấy tất cả liên hệ (admin)
   */
  getAll: async () => {
    const [rows] = await pool.query('SELECT * FROM tbl_contact ORDER BY contactId DESC');
    return rows;
  },

  /**
   * Lấy chi tiết 1 liên hệ
   */
  getById: async (contactId) => {
    const [rows] = await pool.query('SELECT * FROM tbl_contact WHERE contactId = ?', [contactId]);
    return rows[0] || null;
  },

  /**
   * Đánh dấu đã phản hồi
   */
  markReplied: async (contactId) => {
    const [result] = await pool.query(
      "UPDATE tbl_contact SET isReply = 'y' WHERE contactId = ?",
      [contactId]
    );
    return result.affectedRows;
  }
};

module.exports = ContactModel;
