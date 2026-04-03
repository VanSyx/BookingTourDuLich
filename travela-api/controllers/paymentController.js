/**
 * ============================================
 * CONTROLLER: THANH TOÁN (Payment)
 * ============================================
 * Xử lý thanh toán MoMo.
 */

const { createMomoPayment } = require('../services/momoService');
const { sendSuccess, sendError } = require('../utils/response');

const PaymentController = {
  /**
   * POST /api/payments/momo
   * Tạo yêu cầu thanh toán MoMo
   * Body: { amount, tourId }
   */
  createMomo: async (req, res) => {
    try {
      const { amount, tourId } = req.body;
      const paymentAmount = amount || 10000; // Mặc định 10000 cho test

      const momoData = createMomoPayment(paymentAmount, `Thanh toán tour #${tourId}`);

      // Gọi API MoMo
      const fetch = (await import('node-fetch')).default;
      const response = await fetch(momoData.endpoint, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(momoData.data)
      });

      const body = await response.json();

      if (body.payUrl) {
        return sendSuccess(res, 'Tạo yêu cầu thanh toán MoMo thành công.', { payUrl: body.payUrl });
      } else {
        return sendError(res, 'Lỗi từ MoMo.', 400);
      }
    } catch (error) {
      console.error('CreateMomoPayment error:', error);
      return sendError(res, 'Có lỗi xảy ra khi tạo thanh toán.', 500);
    }
  },

  /**
   * GET /api/payments/momo/callback
   * ✅ FIXED: Xử lý callback từ MoMo + Update DB
   */
  momoCallback: async (req, res) => {
    try {
      const { resultCode, transId } = req.query;
      
      // ✅ STEP 1: Validate input
      if (!transId) {
        return sendError(res, 'Thiếu transactionId.', 400);
      }
      
      // ✅ STEP 2: Import models
      const CheckoutModel = require('../models/checkoutModel');
      const BookingModel = require('../models/bookingModel');
      
      // ✅ STEP 3: Find the checkout record by transactionId
      const checkout = await CheckoutModel.getByTransactionId(transId);
      
      if (!checkout) {
        console.warn(`MomoCallback: Transaction ${transId} not found in database`);
        return sendError(res, 'Không tìm thấy giao dịch này trong hệ thống.', 404);
      }
      
      // ✅ STEP 4: Determine payment status based on resultCode
      let paymentStatus = 'n'; // default: unpaid
      let successMessage = '';
      
      if (resultCode === '0') {
        // MoMo payment successful
        paymentStatus = 'y'; // mark as paid
        successMessage = 'Thanh toán MoMo thành công! Booking của bạn đã được xác nhận.';
      } else {
        successMessage = 'Thanh toán MoMo thất bại. Vui lòng thử lại.';
      }
      
      // ✅ STEP 5: Update payment status in database
      const updated = await CheckoutModel.updatePaymentStatus(
        checkout.bookingId, 
        paymentStatus
      );
      
      if (!updated) {
        console.error(`MomoCallback: Failed to update payment status for booking ${checkout.bookingId}`);
        return sendError(res, 'Lỗi cập nhật trạng thái thanh toán. Vui lòng liên hệ support.', 500);
      }
      
      // ✅ STEP 6: Get booking info to return
      const booking = await BookingModel.getById(checkout.bookingId);
      
      // ✅ STEP 7: Return response
      if (paymentStatus === 'y') {
        return sendSuccess(res, successMessage, {
          bookingId: checkout.bookingId,
          bookingStatus: booking ? booking.bookingStatus : 'b',
          transactionId: transId,
          paymentStatus: 'y'
        });
      } else {
        return sendError(res, successMessage, 400);
      }
      
    } catch (error) {
      console.error('MomoCallback error:', error);
      return sendError(res, 'Có lỗi xảy ra khi xử lý callback. Vui lòng liên hệ support.', 500);
    }
  }
};

module.exports = PaymentController;
