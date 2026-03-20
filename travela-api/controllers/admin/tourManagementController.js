/**
 * ============================================
 * CONTROLLER: ADMIN - QUẢN LÝ TOURS
 * ============================================
 */

const TourModel = require('../../models/tourModel');
const ImageModel = require('../../models/imageModel');
const TimelineModel = require('../../models/timelineModel');
const { sendSuccess, sendError } = require('../../utils/response');
const { formatDateToMySQL, calculateDuration } = require('../../utils/helpers');

const TourManagementController = {
  /**
   * GET /api/admin/tours
   * Lấy danh sách tất cả tours (admin)
   */
  getAll: async (req, res) => {
    try {
      const tours = await TourModel.getAllAdmin();
      return sendSuccess(res, 'Lấy danh sách tours thành công.', tours);
    } catch (error) {
      console.error('AdminGetTours error:', error);
      return sendError(res, 'Có lỗi xảy ra.', 500);
    }
  },

  /**
   * GET /api/admin/tours/:id
   * Lấy chi tiết tour để chỉnh sửa
   */
  getById: async (req, res) => {
    try {
      const tourId = Number(req.params.id);
      const tour = await TourModel.getById(tourId);

      if (!tour) {
        return sendError(res, 'Không tìm thấy tour.', 404);
      }

      // Kiểm tra tour đã/đang diễn ra
      const today = new Date();
      const startDate = new Date(tour.startDate);
      if (startDate <= today) {
        return sendSuccess(res, 'Tour đã hoặc đang diễn ra.', {
          ...tour,
          editable: false
        });
      }

      return sendSuccess(res, 'Lấy chi tiết tour thành công.', {
        ...tour,
        editable: true
      });
    } catch (error) {
      console.error('AdminGetTourById error:', error);
      return sendError(res, 'Có lỗi xảy ra.', 500);
    }
  },

  /**
   * POST /api/admin/tours
   * Tạo tour mới
   * Body: { name, destination, domain, number, price_adult, price_child,
   *         start_date, end_date, description }
   */
  create: async (req, res) => {
    try {
      const { name, destination, domain, number: quantity, price_adult, price_child,
              start_date, end_date, description } = req.body;

      // Chuyển đổi ngày
      const startDate = formatDateToMySQL(start_date) || start_date;
      const endDate = formatDateToMySQL(end_date) || end_date;

      // Tính thời gian tour
      const time = calculateDuration(startDate, endDate);

      const tourData = {
        title: name,
        time,
        description,
        quantity,
        priceAdult: price_adult,
        priceChild: price_child,
        destination,
        domain,
        availability: 0, // Chưa sẵn sàng cho đến khi thêm ảnh + timeline
        startDate,
        endDate
      };

      const tourId = await TourModel.create(tourData);

      return sendSuccess(res, 'Tạo tour thành công!', { tourId }, 201);

    } catch (error) {
      console.error('AdminCreateTour error:', error);
      return sendError(res, 'Có lỗi xảy ra khi tạo tour.', 500);
    }
  },

  /**
   * PUT /api/admin/tours/:id
   * Cập nhật tour
   * Body: { name, destination, domain, number, price_adult, price_child,
   *         description, images: [...], timeline: [{title, itinerary}] }
   */
  update: async (req, res) => {
    try {
      const tourId = Number(req.params.id);
      const { name, destination, domain, number: quantity, price_adult, price_child,
              description, images, timeline } = req.body;

      const tourData = {
        title: name,
        description,
        quantity,
        priceAdult: price_adult,
        priceChild: price_child,
        destination,
        domain
      };

      // Xóa timeline và images cũ
      await TimelineModel.deleteByTourId(tourId);
      await ImageModel.deleteByTourId(tourId);

      // Cập nhật thông tin tour
      await TourModel.update(tourId, tourData);

      // Thêm lại images
      if (images && Array.isArray(images)) {
        for (const imageUrl of images) {
          await ImageModel.create({ tourId, imageUrl });
        }
      }

      // Thêm lại timeline
      if (timeline && Array.isArray(timeline)) {
        for (const item of timeline) {
          await TimelineModel.create({
            tourId,
            title: item.title,
            description: item.itinerary || item.description
          });
        }
      }

      return sendSuccess(res, 'Cập nhật tour thành công!');

    } catch (error) {
      console.error('AdminUpdateTour error:', error);
      return sendError(res, 'Có lỗi xảy ra khi cập nhật.', 500);
    }
  },

  /**
   * DELETE /api/admin/tours/:id
   * Xóa tour
   */
  delete: async (req, res) => {
    try {
      const tourId = Number(req.params.id);
      const deleted = await TourModel.delete(tourId);

      if (!deleted) {
        return sendError(res, 'Không thể xóa tour. Tour có thể đã có booking.', 400);
      }

      return sendSuccess(res, 'Xóa tour thành công!');

    } catch (error) {
      // Foreign key constraint error
      if (error.code === 'ER_ROW_IS_REFERENCED_2') {
        return sendError(res, 'Không thể xóa tour vì có booking liên quan.', 400);
      }
      console.error('AdminDeleteTour error:', error);
      return sendError(res, 'Có lỗi xảy ra khi xóa.', 500);
    }
  },

  /**
   * POST /api/admin/tours/:id/images
   * Upload ảnh cho tour (multer middleware xử lý file)
   */
  uploadImage: async (req, res) => {
    try {
      const tourId = Number(req.params.id);

      if (!req.file) {
        return sendError(res, 'Vui lòng chọn ảnh.', 400);
      }

      const filename = req.file.filename;
      await ImageModel.create({ tourId, imageUrl: filename });

      return sendSuccess(res, 'Upload ảnh thành công!', {
        filename,
        tourId
      });

    } catch (error) {
      console.error('UploadTourImage error:', error);
      return sendError(res, 'Có lỗi xảy ra khi upload.', 500);
    }
  },

  /**
   * POST /api/admin/tours/:id/timeline
   * Thêm lịch trình cho tour
   * Body: { timelines: [{ title, description }] }
   */
  addTimeline: async (req, res) => {
    try {
      const tourId = Number(req.params.id);
      const { timelines } = req.body;

      if (!timelines || !Array.isArray(timelines)) {
        return sendError(res, 'Dữ liệu lịch trình không hợp lệ.', 400);
      }

      for (const item of timelines) {
        await TimelineModel.create({
          tourId,
          title: item.title,
          description: item.description
        });
      }

      // Đánh dấu tour sẵn sàng
      await TourModel.update(tourId, { availability: 1 });

      return sendSuccess(res, 'Thêm lịch trình thành công!', 201);

    } catch (error) {
      console.error('AddTimeline error:', error);
      return sendError(res, 'Có lỗi xảy ra.', 500);
    }
  }
};

module.exports = TourManagementController;
