/**
 * ============================================
 * CONTROLLER: ADMIN - QUẢN LÝ LIÊN HỆ
 * ============================================
 */

const ContactModel = require('../../models/contactModel');
const { sendSuccess, sendError } = require('../../utils/response');
const { sendInvoiceEmail } = require('../../services/emailService');
const nodemailer = require('nodemailer');

const ContactManagementController = {
  /**
   * GET /api/admin/contacts
   * Lấy danh sách tất cả liên hệ
   */
  getAll: async (req, res) => {
    try {
      const contacts = await ContactModel.getAll();
      return sendSuccess(res, 'Lấy danh sách liên hệ thành công.', contacts);
    } catch (error) {
      console.error('AdminGetContacts error:', error);
      return sendError(res, 'Có lỗi xảy ra.', 500);
    }
  },

  /**
   * POST /api/admin/contacts/:id/reply
   * Phản hồi tin nhắn liên hệ
   * Body: { replyMessage }
   */
  reply: async (req, res) => {
    try {
      const contactId = Number(req.params.id);
      const { replyMessage } = req.body;

      if (!replyMessage) {
        return sendError(res, 'Vui lòng nhập nội dung phản hồi.', 400);
      }

      // Lấy thông tin liên hệ
      const contact = await ContactModel.getById(contactId);
      if (!contact) {
        return sendError(res, 'Không tìm thấy liên hệ.', 404);
      }

      // Gửi email phản hồi
      const transporter = nodemailer.createTransport({
        host: process.env.MAIL_HOST || 'smtp.gmail.com',
        port: process.env.MAIL_PORT || 587,
        secure: false,
        auth: { user: process.env.MAIL_USER, pass: process.env.MAIL_PASSWORD }
      });

      await transporter.sendMail({
        from: process.env.MAIL_FROM || 'Travela <noreply@travela.com>',
        to: contact.email,
        subject: 'Phản hồi từ Travela',
        html: `
          <div style="font-family: Arial, sans-serif;">
            <h2>Xin chào ${contact.name},</h2>
            <p>${replyMessage}</p>
            <hr>
            <p style="color: #666;">Tin nhắn của bạn: "${contact.message}"</p>
            <p>Trân trọng,<br>Đội ngũ Travela</p>
          </div>
        `
      });

      // Đánh dấu đã phản hồi
      await ContactModel.markReplied(contactId);

      return sendSuccess(res, 'Phản hồi thành công!');
    } catch (error) {
      console.error('AdminReplyContact error:', error);
      return sendError(res, 'Có lỗi xảy ra: ' + error.message, 500);
    }
  }
};

module.exports = ContactManagementController;
