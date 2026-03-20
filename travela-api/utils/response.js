/**
 * ============================================
 * UTILITY: CHUẨN HÓA RESPONSE JSON
 * ============================================
 * Tất cả API trả về cùng định dạng:
 * { success: true/false, message: "...", data: {...} }
 */

/**
 * Trả về response thành công
 * @param {Object} res - Express response object
 * @param {string} message - Thông điệp thành công
 * @param {Object} data - Dữ liệu trả về
 * @param {number} statusCode - HTTP status code (mặc định 200)
 */
const sendSuccess = (res, message = 'Thành công', data = null, statusCode = 200) => {
  const response = {
    success: true,
    message
  };
  if (data !== null) {
    response.data = data;
  }
  return res.status(statusCode).json(response);
};

/**
 * Trả về response lỗi
 * @param {Object} res - Express response object
 * @param {string} message - Thông điệp lỗi
 * @param {number} statusCode - HTTP status code (mặc định 500)
 */
const sendError = (res, message = 'Có lỗi xảy ra', statusCode = 500) => {
  return res.status(statusCode).json({
    success: false,
    message
  });
};

module.exports = { sendSuccess, sendError };
