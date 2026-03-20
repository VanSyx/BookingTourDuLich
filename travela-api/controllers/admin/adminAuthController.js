/**
 * ============================================
 * CONTROLLER: ADMIN - XÁC THỰC & DASHBOARD
 * ============================================
 */

const jwt = require('jsonwebtoken');
const AdminModel = require('../../models/adminModel');
const BookingModel = require('../../models/bookingModel');
const TourModel = require('../../models/tourModel');
const UserModel = require('../../models/userModel');
const ContactModel = require('../../models/contactModel');
const { sendSuccess, sendError } = require('../../utils/response');

const AdminAuthController = {
  /**
   * POST /api/admin/auth/login
   * Đăng nhập admin
   * Body: { username, password }
   */
  login: async (req, res) => {
    try {
      const { username, password } = req.body;

      if (!username || !password) {
        return sendError(res, 'Vui lòng nhập tên đăng nhập và mật khẩu.', 400);
      }

      const admin = await AdminModel.findByUsername(username);
      if (!admin) {
        return sendError(res, 'Thông tin đăng nhập không chính xác.', 401);
      }

      // So sánh password (MD5 - tương thích với hệ thống cũ)
      const crypto = require('crypto');
      const hashedPassword = crypto.createHash('md5').update(password).digest('hex');

      if (hashedPassword !== admin.password) {
        return sendError(res, 'Thông tin đăng nhập không chính xác.', 401);
      }

      // Tạo JWT token cho admin
      const token = jwt.sign(
        { adminId: admin.adminId, username: admin.username },
        process.env.JWT_ADMIN_SECRET,
        { expiresIn: process.env.JWT_ADMIN_EXPIRES_IN || '1d' }
      );

      return sendSuccess(res, 'Đăng nhập admin thành công!', {
        token,
        admin: {
          adminId: admin.adminId,
          username: admin.username,
          email: admin.email,
          fullName: admin.fullName
        }
      });

    } catch (error) {
      console.error('AdminLogin error:', error);
      return sendError(res, 'Có lỗi xảy ra.', 500);
    }
  },

  /**
   * GET /api/admin/dashboard
   * Lấy thống kê tổng quan
   */
  dashboard: async (req, res) => {
    try {
      const [totalBookings, totalRevenue, allUsers, allTours, allContacts] = await Promise.all([
        BookingModel.count(),
        BookingModel.totalRevenue(),
        UserModel.getAll(),
        TourModel.getAllAdmin(),
        ContactModel.getAll()
      ]);

      return sendSuccess(res, 'Lấy thống kê thành công.', {
        totalBookings,
        totalRevenue: Number(totalRevenue),
        totalUsers: allUsers.length,
        totalTours: allTours.length,
        totalContacts: allContacts.length
      });

    } catch (error) {
      console.error('Dashboard error:', error);
      return sendError(res, 'Có lỗi xảy ra.', 500);
    }
  },

  /**
   * GET /api/admin/profile
   * Lấy thông tin admin
   */
  getProfile: async (req, res) => {
    try {
      const admin = await AdminModel.findById(req.admin.adminId);
      if (!admin) {
        return sendError(res, 'Không tìm thấy admin.', 404);
      }
      const { password, ...adminData } = admin;
      return sendSuccess(res, 'Lấy thông tin thành công.', adminData);
    } catch (error) {
      console.error('AdminGetProfile error:', error);
      return sendError(res, 'Có lỗi xảy ra.', 500);
    }
  },

  /**
   * PUT /api/admin/profile
   * Cập nhật thông tin admin
   * Body: { fullName, email, address }
   */
  updateProfile: async (req, res) => {
    try {
      const { fullName, email, address } = req.body;
      const dataUpdate = {};
      if (fullName !== undefined) dataUpdate.fullName = fullName;
      if (email !== undefined) dataUpdate.email = email;
      if (address !== undefined) dataUpdate.address = address;

      await AdminModel.update(req.admin.adminId, dataUpdate);
      return sendSuccess(res, 'Cập nhật thông tin admin thành công!');
    } catch (error) {
      console.error('AdminUpdateProfile error:', error);
      return sendError(res, 'Có lỗi xảy ra.', 500);
    }
  }
};

module.exports = AdminAuthController;
