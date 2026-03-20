/**
 * ============================================
 * SERVICE: THANH TOÁN MOMO
 * ============================================
 * Tích hợp cổng thanh toán MoMo (môi trường test).
 */

const crypto = require('crypto');

/**
 * Tạo yêu cầu thanh toán MoMo
 * @param {number} amount - Số tiền thanh toán
 * @param {string} orderInfo - Mô tả đơn hàng
 * @returns {Object} - Dữ liệu request gửi tới MoMo API
 */
const createMomoPayment = (amount, orderInfo = 'Thanh toán đơn hàng Travela') => {
  const partnerCode = process.env.MOMO_PARTNER_CODE;
  const accessKey = process.env.MOMO_ACCESS_KEY;
  const secretKey = process.env.MOMO_SECRET_KEY;
  const redirectUrl = process.env.MOMO_REDIRECT_URL;
  const ipnUrl = process.env.MOMO_IPN_URL;
  const requestType = 'payWithATM';
  const requestId = Date.now().toString();
  const orderId = Date.now().toString();
  const extraData = '';

  // Tạo chuỗi rawHash theo thứ tự quy định của MoMo
  const rawHash = `accessKey=${accessKey}&amount=${amount}&extraData=${extraData}` +
    `&ipnUrl=${ipnUrl}&orderId=${orderId}&orderInfo=${orderInfo}` +
    `&partnerCode=${partnerCode}&redirectUrl=${redirectUrl}` +
    `&requestId=${requestId}&requestType=${requestType}`;

  // Tạo chữ ký HMAC SHA256
  const signature = crypto.createHmac('sha256', secretKey).update(rawHash).digest('hex');

  return {
    endpoint: process.env.MOMO_ENDPOINT,
    data: {
      partnerCode,
      partnerName: 'Travela',
      storeId: 'TravelaStore',
      requestId,
      amount,
      orderId,
      orderInfo,
      redirectUrl,
      ipnUrl,
      lang: 'vi',
      extraData,
      requestType,
      signature
    }
  };
};

module.exports = { createMomoPayment };
