/**
 * ============================================
 * CONTROLLER: TOURS (Phía khách hàng)
 * ============================================
 * Xử lý danh sách tour, chi tiết, lọc, phổ biến.
 */

const TourModel = require('../models/tourModel');
const { sendSuccess, sendError } = require('../utils/response');
const { timeMap } = require('../utils/helpers');

const TourController = {
  /**
   * GET /api/tours
   * Lấy danh sách tours (phân trang, lọc)
   * Query: ?page, limit, minPrice, maxPrice, domain, star, time, sorting
   */
  getAll: async (req, res) => {
    try {
      const { page = 1, limit = 9, minPrice, maxPrice, domain, star, time, sorting } = req.query;

      // Nếu có filter params → dùng filter
      const hasFilter = minPrice || maxPrice || domain || star || time || sorting;

      if (hasFilter) {
        const conditions = [];
        const sortingArr = [];

        // Lọc theo giá
        if (minPrice && maxPrice) {
          conditions.push(['priceAdult', '>=', Number(minPrice)]);
          conditions.push(['priceAdult', '<=', Number(maxPrice)]);
        }

        // Lọc theo miền
        if (domain) {
          conditions.push(['domain', '=', domain]);
        }

        // Lọc theo sao
        if (star) {
          conditions.push(['averageRating', '=', Number(star)]);
        }

        // Lọc theo thời gian
        if (time && timeMap[time]) {
          conditions.push(['time', '=', timeMap[time]]);
        }

        // Sắp xếp
        if (sorting) {
          switch (sorting) {
            case 'new': sortingArr.push('tourId', 'DESC'); break;
            case 'old': sortingArr.push('tourId', 'ASC'); break;
            case 'high-to-low': sortingArr.push('priceAdult', 'DESC'); break;
            case 'low-to-high': sortingArr.push('priceAdult', 'ASC'); break;
          }
        }

        const tours = await TourModel.filter(conditions, sortingArr);
        return sendSuccess(res, 'Lọc tours thành công.', { tours, total: tours.length });
      }

      // Mặc định: phân trang
      const data = await TourModel.getAll(Number(page), Number(limit));
      return sendSuccess(res, 'Lấy danh sách tours thành công.', data);

    } catch (error) {
      console.error('GetAllTours error:', error);
      return sendError(res, 'Có lỗi xảy ra.', 500);
    }
  },

  /**
   * GET /api/tours/popular
   * Lấy danh sách tours phổ biến
   */
  getPopular: async (req, res) => {
    try {
      const limit = Number(req.query.limit) || 4;
      const tours = await TourModel.getPopular(limit);
      return sendSuccess(res, 'Lấy tours phổ biến thành công.', tours);
    } catch (error) {
      console.error('GetPopular error:', error);
      return sendError(res, 'Có lỗi xảy ra.', 500);
    }
  },

  /**
   * GET /api/tours/domains
   * Lấy số lượng tour theo miền
   */
  getDomains: async (req, res) => {
    try {
      const domains = await TourModel.getDomainCounts();
      const domainsCount = {
        mien_bac: 0,
        mien_trung: 0,
        mien_nam: 0
      };

      domains.forEach(d => {
        if (d.domain === 'b') domainsCount.mien_bac = d.count;
        if (d.domain === 't') domainsCount.mien_trung = d.count;
        if (d.domain === 'n') domainsCount.mien_nam = d.count;
      });

      return sendSuccess(res, 'Lấy thống kê miền thành công.', domainsCount);
    } catch (error) {
      console.error('GetDomains error:', error);
      return sendError(res, 'Có lỗi xảy ra.', 500);
    }
  },

  /**
   * GET /api/tours/:id
   * Lấy chi tiết 1 tour (kèm images, timeline, reviews)
   */
  getById: async (req, res) => {
    try {
      const { id } = req.params;
      const tour = await TourModel.getById(Number(id));

      if (!tour) {
        return sendError(res, 'Không tìm thấy tour.', 404);
      }

      return sendSuccess(res, 'Lấy chi tiết tour thành công.', tour);

    } catch (error) {
      console.error('GetTourById error:', error);
      return sendError(res, 'Có lỗi xảy ra.', 500);
    }
  }
};

module.exports = TourController;
