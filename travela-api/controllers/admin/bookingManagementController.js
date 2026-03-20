/**
 * ============================================
 * CONTROLLER: ADMIN - QUẢN LÝ BOOKING
 * ============================================
 */

const BookingModel = require('../../models/bookingModel');
const CheckoutModel = require('../../models/checkoutModel');
const { sendSuccess, sendError } = require('../../utils/response');
const { sendInvoiceEmail } = require('../../services/emailService');

const BookingManagementController = {
  /**
   * GET /api/admin/bookings
   * Lấy danh sách tất cả bookings
   */
  getAll: async (req, res) => {
    try {
      const bookings = await BookingModel.getAll();
      return sendSuccess(res, 'Lấy danh sách bookings thành công.', bookings);
    } catch (error) {
      console.error('AdminGetBookings error:', error);
      return sendError(res, 'Có lỗi xảy ra.', 500);
    }
  },

  /**
   * GET /api/admin/bookings/:id
   * Xem chi tiết hóa đơn
   */
  getDetail: async (req, res) => {
    try {
      const invoice = await BookingModel.getInvoice(Number(req.params.id));
      if (!invoice) {
        return sendError(res, 'Không tìm thấy đơn đặt.', 404);
      }

      if (!invoice.transactionId) {
        invoice.transactionId = 'Thanh toán tại công ty Travela';
      }

      return sendSuccess(res, 'Lấy chi tiết đơn đặt thành công.', invoice);
    } catch (error) {
      console.error('AdminGetBookingDetail error:', error);
      return sendError(res, 'Có lỗi xảy ra.', 500);
    }
  },

  /**
   * PUT /api/admin/bookings/:id/confirm
   * Xác nhận booking (status → 'y')
   */
  confirm: async (req, res) => {
    try {
      const bookingId = Number(req.params.id);
      const updated = await BookingModel.updateStatus(bookingId, 'y');

      if (!updated) {
        return sendError(res, 'Cập nhật thất bại.', 500);
      }

      return sendSuccess(res, 'Xác nhận booking thành công.');
    } catch (error) {
      console.error('AdminConfirmBooking error:', error);
      return sendError(res, 'Có lỗi xảy ra.', 500);
    }
  },

  /**
   * PUT /api/admin/bookings/:id/finish
   * Đánh dấu booking hoàn thành (status → 'f')
   */
  finish: async (req, res) => {
    try {
      const bookingId = Number(req.params.id);
      const updated = await BookingModel.updateStatus(bookingId, 'f');

      if (!updated) {
        return sendError(res, 'Cập nhật thất bại.', 500);
      }

      return sendSuccess(res, 'Đánh dấu hoàn thành thành công.');
    } catch (error) {
      console.error('AdminFinishBooking error:', error);
      return sendError(res, 'Có lỗi xảy ra.', 500);
    }
  },

  /**
   * PUT /api/admin/bookings/:id/payment
   * Xác nhận đã nhận tiền (paymentStatus → 'y')
   */
  receivedMoney: async (req, res) => {
    try {
      const bookingId = Number(req.params.id);
      const updated = await CheckoutModel.updatePaymentStatus(bookingId, 'y');

      if (!updated) {
        return sendError(res, 'Cập nhật thất bại.', 500);
      }

      return sendSuccess(res, 'Xác nhận thanh toán thành công.');
    } catch (error) {
      console.error('AdminReceivedMoney error:', error);
      return sendError(res, 'Có lỗi xảy ra.', 500);
    }
  },

  /**
   * POST /api/admin/bookings/:id/send-invoice
   * Gửi hóa đơn qua email
   * Body: { email }
   */
  sendInvoice: async (req, res) => {
    try {
      const bookingId = Number(req.params.id);
      const invoice = await BookingModel.getInvoice(bookingId);

      if (!invoice) {
        return sendError(res, 'Không tìm thấy đơn đặt.', 404);
      }

      if (!invoice.transactionId) {
        invoice.transactionId = 'Thanh toán tại công ty Travela';
      }

      await sendInvoiceEmail(invoice.email, invoice);

      return sendSuccess(res, 'Hóa đơn đã được gửi qua email thành công.');
    } catch (error) {
      console.error('AdminSendInvoice error:', error);
      return sendError(res, 'Không thể gửi email: ' + error.message, 500);
    }
  }
};

module.exports = BookingManagementController;
