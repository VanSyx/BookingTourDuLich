/**
 * ============================================
 * CONTROLLER: LIÊN HỆ (Contact)
 * ============================================
 */

const ContactModel = require('../models/contactModel');
const { sendSuccess, sendError } = require('../utils/response');

const ContactController = {
  /**
   * POST /api/contacts
   * Gửi tin nhắn liên hệ
   * Body: { name, phone, email, message }
   */
  create: async (req, res) => {
    try {
      const { name, phone, email, message } = req.body;

      if (!name || !email || !message) {
        return sendError(res, 'Vui lòng nhập đầy đủ thông tin.', 400);
      }

      const contactId = await ContactModel.create({
        name,
        phoneNumber: phone,
        email,
        message
      });

      return sendSuccess(res, 'Gửi tin nhắn thành công! Chúng tôi sẽ sớm liên hệ với bạn.', {
        contactId
      }, 201);

    } catch (error) {
      console.error('CreateContact error:', error);
      return sendError(res, 'Có lỗi xảy ra.', 500);
    }
  }
};

module.exports = ContactController;
