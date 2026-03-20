/**
 * ============================================
 * MODEL: BOOKING (tbl_booking)
 * ============================================
 */

const { pool } = require('../config/db');

const BookingModel = {
  /**
   * Tạo booking mới
   * @param {Object} data - { tourId, userId, numAdults, numChildren, totalPrice }
   * @returns {number} - bookingId
   */
  create: async (data) => {
    const bookingData = {
      tourId: data.tourId,
      userId: data.userId,
      numAdults: data.numAdults || 0,
      numChildren: data.numChildren || 0,
      totalPrice: data.totalPrice,
      bookingStatus: 'b' // 'b' = new booking
    };
    const [result] = await pool.query('INSERT INTO tbl_booking SET ?', [bookingData]);
    return result.insertId;
  },

  /**
   * Lấy danh sách booking của user (kèm thông tin tour)
   */
  getByUserId: async (userId) => {
    const [rows] = await pool.query(`
      SELECT b.*, t.title, t.destination, t.startDate, t.endDate, t.time,
        (SELECT imageUrl FROM tbl_images WHERE tourId = t.tourId LIMIT 1) as thumbnail,
        c.paymentMethod, c.paymentStatus
      FROM tbl_booking b
      JOIN tbl_tours t ON b.tourId = t.tourId
      LEFT JOIN tbl_checkout c ON b.bookingId = c.bookingId
      WHERE b.userId = ?
      ORDER BY b.bookingDate DESC
    `, [userId]);
    return rows;
  },

  /**
   * Lấy chi tiết 1 booking (kèm tour + checkout)
   */
  getById: async (bookingId) => {
    const [rows] = await pool.query(`
      SELECT b.*, t.title, t.destination, t.startDate, t.endDate, t.time,
        t.priceAdult, t.priceChild,
        (SELECT imageUrl FROM tbl_images WHERE tourId = t.tourId LIMIT 1) as thumbnail,
        c.checkoutId, c.paymentMethod, c.paymentStatus, c.amount, c.transactionId
      FROM tbl_booking b
      JOIN tbl_tours t ON b.tourId = t.tourId
      LEFT JOIN tbl_checkout c ON b.bookingId = c.bookingId
      WHERE b.bookingId = ?
    `, [bookingId]);
    return rows[0] || null;
  },

  /**
   * Lấy tất cả bookings (admin)
   */
  getAll: async () => {
    const [rows] = await pool.query(`
      SELECT b.*, t.title, t.destination, t.startDate, t.endDate,
        u.username, u.fullName, u.email,
        c.paymentMethod, c.paymentStatus
      FROM tbl_booking b
      JOIN tbl_tours t ON b.tourId = t.tourId
      JOIN tbl_users u ON b.userId = u.userId
      LEFT JOIN tbl_checkout c ON b.bookingId = c.bookingId
      ORDER BY b.bookingDate DESC
    `);
    return rows;
  },

  /**
   * Lấy hóa đơn chi tiết (admin booking detail)
   */
  getInvoice: async (bookingId) => {
    const [rows] = await pool.query(`
      SELECT b.*, t.title as tourTitle, t.destination, t.startDate, t.endDate,
        t.priceAdult, t.priceChild, t.time,
        u.username, u.fullName, u.email, u.phoneNumber, u.address,
        c.checkoutId, c.paymentMethod, c.paymentStatus, c.amount, c.transactionId
      FROM tbl_booking b
      JOIN tbl_tours t ON b.tourId = t.tourId
      JOIN tbl_users u ON b.userId = u.userId
      LEFT JOIN tbl_checkout c ON b.bookingId = c.bookingId
      WHERE b.bookingId = ?
    `, [bookingId]);
    return rows[0] || null;
  },

  /**
   * Cập nhật trạng thái booking
   * @param {number} bookingId
   * @param {string} status - 'b'=new, 'y'=paid, 'f'=finished, 'c'=cancelled
   */
  updateStatus: async (bookingId, status) => {
    const [result] = await pool.query(
      'UPDATE tbl_booking SET bookingStatus = ? WHERE bookingId = ?',
      [status, bookingId]
    );
    return result.affectedRows;
  },

  /**
   * Hủy booking
   */
  cancel: async (bookingId) => {
    const [result] = await pool.query(
      "UPDATE tbl_booking SET bookingStatus = 'c' WHERE bookingId = ?",
      [bookingId]
    );
    return result.affectedRows;
  },

  /**
   * Kiểm tra user đã đặt và hoàn thành tour chưa
   */
  checkBooking: async (tourId, userId) => {
    const [rows] = await pool.query(
      "SELECT * FROM tbl_booking WHERE tourId = ? AND userId = ? AND bookingStatus = 'f'",
      [tourId, userId]
    );
    return rows.length > 0;
  },

  /**
   * Đếm tổng bookings (cho dashboard)
   */
  count: async () => {
    const [rows] = await pool.query('SELECT COUNT(*) as total FROM tbl_booking');
    return rows[0].total;
  },

  /**
   * Tính tổng doanh thu
   */
  totalRevenue: async () => {
    const [rows] = await pool.query(
      "SELECT COALESCE(SUM(totalPrice), 0) as total FROM tbl_booking WHERE bookingStatus != 'c'"
    );
    return rows[0].total;
  }
};

module.exports = BookingModel;
