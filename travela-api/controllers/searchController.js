/**
 * ============================================
 * CONTROLLER: TÌM KIẾM (Search)
 * ============================================
 */

const TourModel = require('../models/tourModel');
const { sendSuccess, sendError } = require('../utils/response');
const { destinationMap, formatDateToMySQL } = require('../utils/helpers');

const SearchController = {
  /**
   * GET /api/search
   * Tìm kiếm theo điểm đến & ngày tháng
   * Query: ?destination, start_date, end_date
   */
  search: async (req, res) => {
    try {
      const { destination, start_date, end_date } = req.query;

      const searchData = {};

      // Chuyển mã điểm đến sang tên đầy đủ
      if (destination && destinationMap[destination]) {
        searchData.destination = destinationMap[destination];
      } else if (destination) {
        searchData.destination = destination;
      }

      // Chuyển đổi ngày
      if (start_date) searchData.startDate = formatDateToMySQL(start_date);
      if (end_date) searchData.endDate = formatDateToMySQL(end_date);

      const tours = await TourModel.search(searchData);

      return sendSuccess(res, `Tìm thấy ${tours.length} tour.`, { tours, total: tours.length });

    } catch (error) {
      console.error('Search error:', error);
      return sendError(res, 'Có lỗi xảy ra khi tìm kiếm.', 500);
    }
  },

  /**
   * GET /api/search/keyword
   * Tìm kiếm bằng từ khóa
   * Query: ?keyword
   */
  searchByKeyword: async (req, res) => {
    try {
      const { keyword } = req.query;

      if (!keyword) {
        return sendError(res, 'Vui lòng nhập từ khóa tìm kiếm.', 400);
      }

      const tours = await TourModel.search({ keyword });

      return sendSuccess(res, `Tìm thấy ${tours.length} tour.`, { tours, total: tours.length });

    } catch (error) {
      console.error('SearchByKeyword error:', error);
      return sendError(res, 'Có lỗi xảy ra khi tìm kiếm.', 500);
    }
  }
};

module.exports = SearchController;
