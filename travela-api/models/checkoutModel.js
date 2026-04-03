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
  },

  /**
   * ✅ NEW: Lấy checkout theo transactionId
   * Dùng cho MoMo callback lookup
   */
  getByTransactionId: async (transactionId) => {
    if (!transactionId) return null;
    const [rows] = await pool.query(
      'SELECT * FROM tbl_checkout WHERE transactionId = ?', 
      [transactionId]
    );
    return rows[0] || null;
  }
};

module.exports = CheckoutModel;
