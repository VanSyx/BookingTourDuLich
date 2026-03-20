/**
 * ============================================
 * MODEL: TOUR (tbl_tours)
 * ============================================
 * Các thao tác CSDL liên quan đến bảng tbl_tours.
 * Bao gồm join với tbl_images, tbl_timeline, tbl_reviews.
 */

const { pool } = require('../config/db');

const TourModel = {
  /**
   * Lấy tất cả tours với phân trang + ảnh đầu tiên + rating trung bình
   * @param {number} page - Trang hiện tại
   * @param {number} limit - Số tour/trang
   */
  getAll: async (page = 1, limit = 9) => {
    const offset = (page - 1) * limit;

    // Đếm tổng số tour
    const [countResult] = await pool.query(
      'SELECT COUNT(*) as total FROM tbl_tours WHERE availability = 1'
    );
    const total = countResult[0].total;

    // Lấy danh sách tours có ảnh đại diện và rating
    const [rows] = await pool.query(`
      SELECT t.*,
        (SELECT imageUrl FROM tbl_images WHERE tourId = t.tourId LIMIT 1) as thumbnail,
        (SELECT ROUND(AVG(rating), 1) FROM tbl_reviews WHERE tourId = t.tourId) as averageRating,
        (SELECT COUNT(*) FROM tbl_reviews WHERE tourId = t.tourId) as reviewCount
      FROM tbl_tours t
      WHERE t.availability = 1
      ORDER BY t.tourId DESC
      LIMIT ? OFFSET ?
    `, [limit, offset]);

    return {
      tours: rows,
      pagination: {
        total,
        page,
        limit,
        totalPages: Math.ceil(total / limit)
      }
    };
  },

  /**
   * Lấy tất cả tours (cho admin, không phân trang)
   */
  getAllAdmin: async () => {
    const [rows] = await pool.query(`
      SELECT t.*,
        (SELECT imageUrl FROM tbl_images WHERE tourId = t.tourId LIMIT 1) as thumbnail,
        (SELECT ROUND(AVG(rating), 1) FROM tbl_reviews WHERE tourId = t.tourId) as averageRating
      FROM tbl_tours t
      ORDER BY t.tourId DESC
    `);
    return rows;
  },

  /**
   * Lấy chi tiết 1 tour (kèm images, timeline, reviews)
   * @param {number} tourId
   */
  getById: async (tourId) => {
    // Thông tin tour
    const [tours] = await pool.query('SELECT * FROM tbl_tours WHERE tourId = ?', [tourId]);
    if (!tours[0]) return null;

    // Ảnh tour
    const [images] = await pool.query('SELECT * FROM tbl_images WHERE tourId = ?', [tourId]);

    // Lịch trình tour
    const [timeline] = await pool.query(
      'SELECT * FROM tbl_timeline WHERE tourId = ? ORDER BY timelineId ASC',
      [tourId]
    );

    // Thống kê reviews
    const [stats] = await pool.query(`
      SELECT ROUND(AVG(rating), 1) as averageRating, COUNT(*) as reviewCount
      FROM tbl_reviews WHERE tourId = ?
    `, [tourId]);

    return {
      ...tours[0],
      images,
      timeline,
      averageRating: stats[0].averageRating || 0,
      reviewCount: stats[0].reviewCount || 0
    };
  },

  /**
   * Lọc tours theo điều kiện
   * @param {Array} conditions - Mảng điều kiện [field, operator, value]
   * @param {Array} sorting - [field, direction] VD: ['priceAdult', 'ASC']
   */
  filter: async (conditions = [], sorting = []) => {
    let query = `
      SELECT t.*,
        (SELECT imageUrl FROM tbl_images WHERE tourId = t.tourId LIMIT 1) as thumbnail,
        (SELECT ROUND(AVG(rating), 1) FROM tbl_reviews WHERE tourId = t.tourId) as averageRating,
        (SELECT COUNT(*) FROM tbl_reviews WHERE tourId = t.tourId) as reviewCount
      FROM tbl_tours t
      WHERE t.availability = 1
    `;
    const params = [];

    // Thêm điều kiện lọc
    conditions.forEach(([field, operator, value]) => {
      if (field === 'averageRating') {
        query += ` AND (SELECT ROUND(AVG(rating)) FROM tbl_reviews WHERE tourId = t.tourId) ${operator} ?`;
      } else {
        query += ` AND t.${field} ${operator} ?`;
      }
      params.push(value);
    });

    // Thêm sắp xếp
    if (sorting.length === 2) {
      query += ` ORDER BY t.${sorting[0]} ${sorting[1]}`;
    } else {
      query += ' ORDER BY t.tourId DESC';
    }

    const [rows] = await pool.query(query, params);
    return rows;
  },

  /**
   * Tìm kiếm tours theo điểm đến và ngày
   * @param {Object} searchData - { destination, startDate, endDate, keyword }
   */
  search: async (searchData) => {
    let query = `
      SELECT t.*,
        (SELECT imageUrl FROM tbl_images WHERE tourId = t.tourId LIMIT 1) as thumbnail,
        (SELECT ROUND(AVG(rating), 1) FROM tbl_reviews WHERE tourId = t.tourId) as averageRating
      FROM tbl_tours t
      WHERE t.availability = 1
    `;
    const params = [];

    if (searchData.destination) {
      query += ' AND t.destination LIKE ?';
      params.push(`%${searchData.destination}%`);
    }
    if (searchData.startDate) {
      query += ' AND t.startDate >= ?';
      params.push(searchData.startDate);
    }
    if (searchData.endDate) {
      query += ' AND t.endDate <= ?';
      params.push(searchData.endDate);
    }
    if (searchData.keyword) {
      query += ' AND (t.title LIKE ? OR t.description LIKE ? OR t.destination LIKE ?)';
      params.push(`%${searchData.keyword}%`, `%${searchData.keyword}%`, `%${searchData.keyword}%`);
    }

    query += ' ORDER BY t.tourId DESC';
    const [rows] = await pool.query(query, params);
    return rows;
  },

  /**
   * Tìm tours theo mảng tourId (cho recommendations)
   */
  findByIds: async (tourIds) => {
    if (!tourIds || tourIds.length === 0) return [];
    const [rows] = await pool.query(`
      SELECT t.*,
        (SELECT imageUrl FROM tbl_images WHERE tourId = t.tourId LIMIT 1) as thumbnail
      FROM tbl_tours t
      WHERE t.tourId IN (?)
    `, [tourIds]);
    return rows;
  },

  /**
   * Lấy tours phổ biến (nhiều booking nhất)
   */
  getPopular: async (limit = 2) => {
    const [rows] = await pool.query(`
      SELECT t.*,
        (SELECT imageUrl FROM tbl_images WHERE tourId = t.tourId LIMIT 1) as thumbnail,
        (SELECT ROUND(AVG(rating), 1) FROM tbl_reviews WHERE tourId = t.tourId) as averageRating,
        (SELECT COUNT(*) FROM tbl_booking WHERE tourId = t.tourId) as bookingCount
      FROM tbl_tours t
      WHERE t.availability = 1
      ORDER BY bookingCount DESC
      LIMIT ?
    `, [limit]);
    return rows;
  },

  /**
   * Lấy số lượng tour theo từng miền (domain)
   */
  getDomainCounts: async () => {
    const [rows] = await pool.query(`
      SELECT domain, COUNT(*) as count
      FROM tbl_tours
      WHERE availability = 1
      GROUP BY domain
    `);
    return rows;
  },

  /**
   * Tạo tour mới
   */
  create: async (data) => {
    const [result] = await pool.query('INSERT INTO tbl_tours SET ?', [data]);
    return result.insertId;
  },

  /**
   * Cập nhật tour
   */
  update: async (tourId, data) => {
    const [result] = await pool.query('UPDATE tbl_tours SET ? WHERE tourId = ?', [data, tourId]);
    return result.affectedRows;
  },

  /**
   * Xóa tour (cascade xóa images, timeline, reviews, booking)
   */
  delete: async (tourId) => {
    const [result] = await pool.query('DELETE FROM tbl_tours WHERE tourId = ?', [tourId]);
    return result.affectedRows;
  },

  /**
   * Cập nhật số lượng chỗ
   */
  updateQuantity: async (tourId, quantity) => {
    const [result] = await pool.query(
      'UPDATE tbl_tours SET quantity = ? WHERE tourId = ?',
      [quantity, tourId]
    );
    return result.affectedRows;
  }
};

module.exports = TourModel;
