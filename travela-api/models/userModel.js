/**
 * ============================================
 * MODEL: NGƯỜI DÙNG (tbl_users)
 * ============================================
 * Các thao tác CSDL liên quan đến bảng tbl_users.
 */

const { pool } = require('../config/db');

const UserModel = {
  /**
   * Tìm user theo username
   * @param {string} username
   * @returns {Object|null}
   */
  findByUsername: async (username) => {
    const [rows] = await pool.query('SELECT * FROM tbl_users WHERE username = ?', [username]);
    return rows[0] || null;
  },

  /**
   * Tìm user theo userId
   * @param {number} userId
   * @returns {Object|null}
   */
  findById: async (userId) => {
    const [rows] = await pool.query('SELECT * FROM tbl_users WHERE userId = ?', [userId]);
    return rows[0] || null;
  },

  /**
   * Tìm user theo email
   * @param {string} email
   * @returns {Object|null}
   */
  findByEmail: async (email) => {
    const [rows] = await pool.query('SELECT * FROM tbl_users WHERE email = ?', [email]);
    return rows[0] || null;
  },

  /**
   * Kiểm tra username hoặc email đã tồn tại chưa
   * @param {string} username
   * @param {string} email
   * @returns {Object|null}
   */
  checkExist: async (username, email) => {
    const [rows] = await pool.query(
      'SELECT * FROM tbl_users WHERE username = ? OR email = ?',
      [username, email]
    );
    return rows[0] || null;
  },

  /**
   * Tạo user mới
   * @param {Object} data - { username, email, password, activation_token }
   * @returns {number} - insertId
   */
  create: async (data) => {
    const [result] = await pool.query('INSERT INTO tbl_users SET ?', [data]);
    return result.insertId;
  },

  /**
   * Cập nhật thông tin user
   * @param {number} userId
   * @param {Object} data - Dữ liệu cần cập nhật
   * @returns {number} - Số dòng bị ảnh hưởng
   */
  update: async (userId, data) => {
    const [result] = await pool.query('UPDATE tbl_users SET ? WHERE userId = ?', [data, userId]);
    return result.affectedRows;
  },

  /**
   * Tìm user theo activation token
   * @param {string} token
   * @returns {Object|null}
   */
  findByToken: async (token) => {
    const [rows] = await pool.query(
      'SELECT * FROM tbl_users WHERE activation_token = ?',
      [token]
    );
    return rows[0] || null;
  },

  /**
   * Kích hoạt tài khoản user
   * @param {string} token
   * @returns {number}
   */
  activate: async (token) => {
    const [result] = await pool.query(
      "UPDATE tbl_users SET isActive = 'y' WHERE activation_token = ?",
      [token]
    );
    return result.affectedRows;
  },

  /**
   * Lấy userId theo username
   * @param {string} username
   * @returns {number|null}
   */
  getUserId: async (username) => {
    const [rows] = await pool.query('SELECT userId FROM tbl_users WHERE username = ?', [username]);
    return rows[0] ? rows[0].userId : null;
  },

  /**
   * Lấy tất cả users (dành cho admin)
   * @returns {Array}
   */
  getAll: async () => {
    const [rows] = await pool.query('SELECT * FROM tbl_users ORDER BY created_at DESC');
    return rows;
  },

  /**
   * Kích hoạt user bởi admin
   * @param {number} userId
   * @returns {number}
   */
  activateByAdmin: async (userId) => {
    const [result] = await pool.query(
      "UPDATE tbl_users SET isActive = 'y' WHERE userId = ?",
      [userId]
    );
    return result.affectedRows;
  },

  /**
   * Đổi trạng thái user (block/delete)
   * @param {number} userId
   * @param {string} status - 'b'=block, 'd'=delete
   * @returns {number}
   */
  changeStatus: async (userId, status) => {
    const [result] = await pool.query(
      'UPDATE tbl_users SET status = ? WHERE userId = ?',
      [status, userId]
    );
    return result.affectedRows;
  }
};

module.exports = UserModel;
