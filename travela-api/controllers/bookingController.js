/**
 * ============================================
 * CONTROLLER: ĐẶT TOUR & THANH TOÁN (Booking)
 * ============================================
 * Xử lý tạo booking, hủy booking, xem danh sách.
 * Tất cả endpoints yêu cầu auth middleware.
 */

const BookingModel = require('../models/bookingModel');
const CheckoutModel = require('../models/checkoutModel');
const TourModel = require('../models/tourModel');
const { sendSuccess, sendError } = require('../utils/response');

const BookingController = {
  /**
   * POST /api/bookings
   * Tạo booking mới + checkout
   * Body: { tourId, numAdults, numChildren, totalPrice, paymentMethod, transactionId? }
   */
  create: async (req, res) => {
    try {
      const { tourId, numAdults, numChildren, totalPrice, paymentMethod, transactionId } = req.body;
      const userId = req.user.userId;

      // Validate
      if (!tourId || !totalPrice) {
        return sendError(res, 'Thông tin đặt tour không đầy đủ.', 400);
      }

      // Kiểm tra tour tồn tại và còn chỗ
      const tour = await TourModel.getById(tourId);
      if (!tour) {
        return sendError(res, 'Không tìm thấy tour.', 404);
      }

      const totalPeople = (Number(numAdults) || 0) + (Number(numChildren) || 0);
      if (tour.quantity < totalPeople) {
        return sendError(res, 'Tour không còn đủ chỗ trống.', 400);
      }

      // Tạo booking
      const bookingId = await BookingModel.create({
        tourId,
        userId,
        numAdults: numAdults || 0,
        numChildren: numChildren || 0,
        totalPrice
      });

      // Tạo checkout
      const checkoutData = {
        bookingId,
        paymentMethod: paymentMethod || 'cash',
        amount: totalPrice,
        paymentStatus: (paymentMethod === 'paypal-payment' || paymentMethod === 'momo-payment') ? 'y' : 'n'
      };
      if (transactionId) {
        checkoutData.transactionId = transactionId;
      }

      const checkoutId = await CheckoutModel.create(checkoutData);

      // Cập nhật số lượng chỗ còn lại
      await TourModel.updateQuantity(tourId, tour.quantity - totalPeople);

      return sendSuccess(res, 'Đặt tour thành công!', {
        bookingId,
        checkoutId,
        bookingStatus: 'b',
        paymentStatus: checkoutData.paymentStatus
      }, 201);

    } catch (error) {
      console.error('CreateBooking error:', error);
      return sendError(res, 'Có lỗi xảy ra khi đặt tour.', 500);
    }
  },

  /**
   * GET /api/bookings
   * Lấy danh sách bookings của user đang đăng nhập
   */
  getMyBookings: async (req, res) => {
    try {
      const bookings = await BookingModel.getByUserId(req.user.userId);
      return sendSuccess(res, 'Lấy danh sách đặt tour thành công.', bookings);
    } catch (error) {
      console.error('GetMyBookings error:', error);
      return sendError(res, 'Có lỗi xảy ra.', 500);
    }
  },

  /**
   * GET /api/bookings/:id
   * Xem chi tiết 1 booking
   */
  getById: async (req, res) => {
    try {
      const booking = await BookingModel.getById(Number(req.params.id));
      if (!booking) {
        return sendError(res, 'Không tìm thấy đơn đặt tour.', 404);
      }
      return sendSuccess(res, 'Lấy chi tiết đơn đặt thành công.', booking);
    } catch (error) {
      console.error('GetBookingById error:', error);
      return sendError(res, 'Có lỗi xảy ra.', 500);
    }
  },

  /**
   * POST /api/bookings/:id/cancel
   * Hủy booking (trả lại số lượng chỗ)
   * Body: { tourId, numAdults, numChildren }
   */
  cancel: async (req, res) => {
    try {
      const bookingId = Number(req.params.id);
      const { tourId, numAdults, numChildren } = req.body;

      // Lấy tour hiện tại
      const tour = await TourModel.getById(tourId);
      if (!tour) {
        return sendError(res, 'Không tìm thấy tour.', 404);
      }

      // Hủy booking
      const cancelled = await BookingModel.cancel(bookingId);
      if (!cancelled) {
        return sendError(res, 'Có lỗi xảy ra khi hủy.', 500);
      }

      // Trả lại số lượng chỗ
      const returnQuantity = (Number(numAdults) || 0) + (Number(numChildren) || 0);
      await TourModel.updateQuantity(tourId, tour.quantity + returnQuantity);

      return sendSuccess(res, 'Hủy booking thành công!');

    } catch (error) {
      console.error('CancelBooking error:', error);
      return sendError(res, 'Có lỗi xảy ra khi hủy.', 500);
    }
  },

  /**
   * POST /api/bookings/check
   * Kiểm tra user đã hoàn thành tour chưa (để cho phép đánh giá)
   * Body: { tourId }
   */
  checkBooking: async (req, res) => {
    try {
      const { tourId } = req.body;
      const hasCompleted = await BookingModel.checkBooking(tourId, req.user.userId);
      return sendSuccess(res, hasCompleted ? 'Bạn có thể đánh giá.' : 'Bạn chưa thể đánh giá.', {
        canReview: hasCompleted
      });
    } catch (error) {
      console.error('CheckBooking error:', error);
      return sendError(res, 'Có lỗi xảy ra.', 500);
    }
  }
};

module.exports = BookingController;
