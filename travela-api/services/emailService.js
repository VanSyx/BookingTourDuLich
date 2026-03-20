/**
 * ============================================
 * SERVICE: GỬI EMAIL (Nodemailer)
 * ============================================
 * Dùng để gửi email kích hoạt tài khoản và hóa đơn.
 */

const nodemailer = require('nodemailer');

// Tạo transporter với cấu hình SMTP
const transporter = nodemailer.createTransport({
  host: process.env.MAIL_HOST || 'smtp.gmail.com',
  port: process.env.MAIL_PORT || 587,
  secure: false, // true cho port 465, false cho port 587
  auth: {
    user: process.env.MAIL_USER,
    pass: process.env.MAIL_PASSWORD
  }
});

/**
 * Gửi email kích hoạt tài khoản
 * @param {string} toEmail - Email người nhận
 * @param {string} activationLink - Link kích hoạt
 */
const sendActivationEmail = async (toEmail, activationLink) => {
  const mailOptions = {
    from: process.env.MAIL_FROM || 'Travela <noreply@travela.com>',
    to: toEmail,
    subject: 'Kích hoạt tài khoản Travela',
    html: `
      <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;">
        <h2 style="color: #2563eb;">🌍 Chào mừng bạn đến với Travela!</h2>
        <p>Cảm ơn bạn đã đăng ký tài khoản. Vui lòng nhấn nút bên dưới để kích hoạt:</p>
        <a href="${activationLink}"
           style="display: inline-block; padding: 12px 24px; background-color: #2563eb;
                  color: white; text-decoration: none; border-radius: 6px; margin: 16px 0;">
          Kích hoạt tài khoản
        </a>
        <p style="color: #666;">Nếu bạn không đăng ký tài khoản, vui lòng bỏ qua email này.</p>
      </div>
    `
  };

  return transporter.sendMail(mailOptions);
};

/**
 * Gửi email hóa đơn booking
 * @param {string} toEmail - Email người nhận
 * @param {Object} invoiceData - Dữ liệu hóa đơn
 */
const sendInvoiceEmail = async (toEmail, invoiceData) => {
  const mailOptions = {
    from: process.env.MAIL_FROM || 'Travela <noreply@travela.com>',
    to: toEmail,
    subject: `Hóa đơn đặt tour - ${invoiceData.fullName}`,
    html: `
      <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;">
        <h2 style="color: #2563eb;">🧾 Hóa đơn đặt Tour - Travela</h2>
        <table style="width: 100%; border-collapse: collapse;">
          <tr><td style="padding: 8px; border: 1px solid #ddd;"><strong>Mã đơn:</strong></td>
              <td style="padding: 8px; border: 1px solid #ddd;">${invoiceData.bookingId}</td></tr>
          <tr><td style="padding: 8px; border: 1px solid #ddd;"><strong>Tour:</strong></td>
              <td style="padding: 8px; border: 1px solid #ddd;">${invoiceData.tourTitle}</td></tr>
          <tr><td style="padding: 8px; border: 1px solid #ddd;"><strong>Khách hàng:</strong></td>
              <td style="padding: 8px; border: 1px solid #ddd;">${invoiceData.fullName}</td></tr>
          <tr><td style="padding: 8px; border: 1px solid #ddd;"><strong>Người lớn:</strong></td>
              <td style="padding: 8px; border: 1px solid #ddd;">${invoiceData.numAdults}</td></tr>
          <tr><td style="padding: 8px; border: 1px solid #ddd;"><strong>Trẻ em:</strong></td>
              <td style="padding: 8px; border: 1px solid #ddd;">${invoiceData.numChildren}</td></tr>
          <tr><td style="padding: 8px; border: 1px solid #ddd;"><strong>Tổng tiền:</strong></td>
              <td style="padding: 8px; border: 1px solid #ddd; color: #e11d48; font-weight: bold;">
                ${Number(invoiceData.totalPrice).toLocaleString('vi-VN')} VNĐ</td></tr>
          <tr><td style="padding: 8px; border: 1px solid #ddd;"><strong>Thanh toán:</strong></td>
              <td style="padding: 8px; border: 1px solid #ddd;">${invoiceData.paymentMethod}</td></tr>
        </table>
        <p style="margin-top: 16px; color: #666;">Cảm ơn bạn đã sử dụng dịch vụ Travela!</p>
      </div>
    `
  };

  return transporter.sendMail(mailOptions);
};

module.exports = { sendActivationEmail, sendInvoiceEmail };
