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

      // ✅ SET TRANSACTION ID
      if (transactionId) {
        checkoutData.transactionId = transactionId;
      } else if (paymentMethod === 'cash' || paymentMethod === 'office') {
        // ✅ Generate reference code for cash payment
        // Format: CASH-20260402120530-12345
        const now = new Date();
        const timestamp = now.getFullYear().toString() +
                         String(now.getMonth() + 1).padStart(2, '0') +
                         String(now.getDate()).padStart(2, '0') +
                         String(now.getHours()).padStart(2, '0') +
                         String(now.getMinutes()).padStart(2, '0') +
                         String(now.getSeconds()).padStart(2, '0');
        checkoutData.transactionId = `CASH-${timestamp}-${bookingId}`;
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
   * Hủy booking với các kiểm tra an toàn
   * Body: { tourId, numAdults, numChildren }
   */
  cancel: async (req, res) => {
    try {
      const bookingId = Number(req.params.id);
      const { tourId, numAdults, numChildren } = req.body;

      // ✅ STEP 1: Validate input
      if (!tourId || numAdults === undefined || numChildren === undefined) {
        return sendError(res, 'Lỗi: Thông tin không đầy đủ.', 400);
      }

      // ✅ STEP 2: Get booking details
      const booking = await BookingModel.getById(bookingId);
      if (!booking) {
        return sendError(res, 'Không tìm thấy đơn đặt tour.', 404);
      }

      // ✅ STEP 3: Check booking status - only 'b' (new) can be cancelled
      if (booking.bookingStatus !== 'b') {
        return sendError(res,
          `Không thể hủy booking với trạng thái '${booking.bookingStatus}'. Chỉ booking mới (chưa xác nhận) mới có thể hủy.`,
          400);
      }

      // ✅ STEP 4: Check if tour has started
      const tour = await TourModel.getById(tourId);
      if (!tour) {
        return sendError(res, 'Không tìm thấy tour.', 404);
      }

      const tourStartDate = new Date(tour.startDate);
      const now = new Date();
      if (tourStartDate <= now) {
        return sendError(res, 'Không thể hủy tour đã khởi hành.', 400);
      }

      // ✅ STEP 5: Check payment status (warning for refund needed)
      const checkout = await CheckoutModel.getByBookingId(bookingId);
      if (checkout && checkout.paymentStatus === 'y') {
        // TODO: Implement full refund logic in future
        console.warn(`[REFUND NEEDED] BookingId: ${bookingId}, Amount: ${checkout.amount}, Method: ${checkout.paymentMethod}`);
        // For now: notify support team
        // In future: auto-refund via PayPal/MoMo API
      }

      // ✅ STEP 6: Cancel booking (update status to 'c')
      const cancelled = await BookingModel.cancel(bookingId);
      if (!cancelled) {
        return sendError(res, 'Có lỗi xảy ra khi hủy đơn đặt.', 500);
      }

      // ✅ STEP 7: Return seats to tour
      const returnQuantity = (Number(numAdults) || 0) + (Number(numChildren) || 0);
      await TourModel.updateQuantity(tourId, tour.quantity + returnQuantity);

      // ✅ STEP 8: Success response
      return sendSuccess(res, 'Hủy đơn đặt tour thành công! Ghế của bạn đã được hoàn lại.', {
        bookingId,
        seatsReturned: returnQuantity,
        newTourQuantity: tour.quantity + returnQuantity
      });

    } catch (error) {
      console.error('CancelBooking error:', error);
      return sendError(res, 'Có lỗi xảy ra khi hủy đơn đặt.', 500);
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
