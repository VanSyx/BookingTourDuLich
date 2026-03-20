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
   * Xử lý callback từ MoMo
   */
  momoCallback: async (req, res) => {
    try {
      const { resultCode, transId } = req.query;

      if (resultCode === '0') {
        return sendSuccess(res, 'Thanh toán MoMo thành công.', { transactionId: transId });
      } else {
        return sendError(res, 'Thanh toán MoMo thất bại.', 400);
      }
    } catch (error) {
      console.error('MomoCallback error:', error);
      return sendError(res, 'Có lỗi xảy ra.', 500);
    }
  }
};

module.exports = PaymentController;
