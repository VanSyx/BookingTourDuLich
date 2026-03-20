/**
 * ============================================
 * CONTROLLER: ĐÁNH GIÁ (Reviews)
 * ============================================
 */

const ReviewModel = require('../models/reviewModel');
const BookingModel = require('../models/bookingModel');
const { sendSuccess, sendError } = require('../utils/response');

const ReviewController = {
  /**
   * POST /api/reviews
   * Gửi đánh giá cho tour (cần auth + đã hoàn thành tour)
   * Body: { tourId, message, rating }
   */
  create: async (req, res) => {
    try {
      const { tourId, message, rating } = req.body;
      const userId = req.user.userId;

      // Validate
      if (!tourId || !rating) {
        return sendError(res, 'Vui lòng nhập đầy đủ thông tin đánh giá.', 400);
      }
      if (rating < 1 || rating > 5) {
        return sendError(res, 'Rating phải từ 1 đến 5.', 400);
      }

      // Kiểm tra user đã review chưa
      const hasReviewed = await ReviewModel.checkExist(tourId, userId);
      if (hasReviewed) {
        return sendError(res, 'Bạn đã đánh giá tour này rồi.', 400);
      }

      // Tạo review
      const reviewId = await ReviewModel.create({
        tourId,
        userId,
        rating,
        content: message
      });

      // Lấy danh sách reviews mới + thống kê
      const reviews = await ReviewModel.getByTourId(tourId);
      const stats = await ReviewModel.getStats(tourId);

      return sendSuccess(res, 'Đánh giá của bạn đã được gửi thành công!', {
        reviewId,
        reviews,
        averageRating: stats.averageRating,
        reviewCount: stats.reviewCount
      }, 201);

    } catch (error) {
      console.error('CreateReview error:', error);
      return sendError(res, 'Có lỗi xảy ra.', 500);
    }
  },

  /**
   * GET /api/reviews/tour/:tourId
   * Lấy tất cả reviews của 1 tour
   */
  getByTour: async (req, res) => {
    try {
      const { tourId } = req.params;
      const reviews = await ReviewModel.getByTourId(Number(tourId));
      const stats = await ReviewModel.getStats(Number(tourId));

      return sendSuccess(res, 'Lấy đánh giá thành công.', {
        reviews,
        averageRating: stats.averageRating || 0,
        reviewCount: stats.reviewCount || 0
      });
    } catch (error) {
      console.error('GetReviewsByTour error:', error);
      return sendError(res, 'Có lỗi xảy ra.', 500);
    }
  }
};

module.exports = ReviewController;
