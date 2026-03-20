/**
 * ============================================
 * MODEL: CHECKOUT (tbl_checkout)
 * ============================================
 */

const { pool } = require('../config/db');

const CheckoutModel = {
  /**
   * Tạo checkout mới
   */
  create: async (data) => {
    const [result] = await pool.query('INSERT INTO tbl_checkout SET ?', [data]);
    return result.insertId;
  },

  /**
   * Lấy checkout theo bookingId
   */
  getByBookingId: async (bookingId) => {
    const [rows] = await pool.query('SELECT * FROM tbl_checkout WHERE bookingId = ?', [bookingId]);
    return rows[0] || null;
  },

  /**
   * Cập nhật trạng thái thanh toán
   */
  updatePaymentStatus: async (bookingId, status) => {
    const [result] = await pool.query(
      'UPDATE tbl_checkout SET paymentStatus = ? WHERE bookingId = ?',
      [status, bookingId]
    );
    return result.affectedRows;
  }
};

module.exports = CheckoutModel;
