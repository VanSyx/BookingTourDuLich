/**
 * ============================================
 * CONTROLLER: HỒ SƠ NGƯỜI DÙNG (User Profile)
 * ============================================
 * Tất cả endpoints yêu cầu auth middleware.
 */

const bcrypt = require('bcryptjs');
const UserModel = require('../models/userModel');
const { sendSuccess, sendError } = require('../utils/response');

const UserController = {
  /**
   * GET /api/users/profile
   * Xem thông tin cá nhân
   */
  getProfile: async (req, res) => {
    try {
      const user = await UserModel.findById(req.user.userId);
      if (!user) {
        return sendError(res, 'Không tìm thấy người dùng.', 404);
      }

      // Loại bỏ password trước khi trả về
      const { password, activation_token, ...userData } = user;
      return sendSuccess(res, 'Lấy thông tin thành công.', userData);

    } catch (error) {
      console.error('GetProfile error:', error);
      return sendError(res, 'Có lỗi xảy ra.', 500);
    }
  },

  /**
   * PUT /api/users/profile
   * Cập nhật thông tin cá nhân
   * Body: { fullName, address, email, phone }
   */
  updateProfile: async (req, res) => {
    try {
      const { fullName, address, email, phone } = req.body;

      const dataUpdate = {};
      if (fullName !== undefined) dataUpdate.fullName = fullName;
      if (address !== undefined) dataUpdate.address = address;
      if (email !== undefined) dataUpdate.email = email;
      if (phone !== undefined) dataUpdate.phoneNumber = phone;

      if (Object.keys(dataUpdate).length === 0) {
        return sendError(res, 'Bạn chưa thay đổi thông tin nào.', 400);
      }

      const updated = await UserModel.update(req.user.userId, dataUpdate);
      if (!updated) {
        return sendError(res, 'Bạn chưa thay đổi thông tin nào, vui lòng kiểm tra lại!', 400);
      }

      // Trả về user mới
      const user = await UserModel.findById(req.user.userId);
      const { password, activation_token, ...userData } = user;

      return sendSuccess(res, 'Cập nhật thông tin thành công!', userData);

    } catch (error) {
      console.error('UpdateProfile error:', error);
      return sendError(res, 'Có lỗi xảy ra khi cập nhật.', 500);
    }
  },

  /**
   * PUT /api/users/change-password
   * Đổi mật khẩu
   * Body: { oldPass, newPass }
   */
  changePassword: async (req, res) => {
    try {
      const { oldPass, newPass } = req.body;

      if (!oldPass || !newPass) {
        return sendError(res, 'Vui lòng nhập đầy đủ mật khẩu cũ và mới.', 400);
      }

      const user = await UserModel.findById(req.user.userId);

      // So sánh mật khẩu cũ
      const isOldPassValid = await bcrypt.compare(oldPass, user.password);
      if (!isOldPassValid) {
        return sendError(res, 'Mật khẩu cũ không chính xác.', 400);
      }

      // Mã hóa mật khẩu mới
      const hashedNewPass = await bcrypt.hash(newPass, 10);
      const updated = await UserModel.update(req.user.userId, { password: hashedNewPass });

      if (!updated) {
        return sendError(res, 'Mật khẩu mới trùng với mật khẩu cũ!', 400);
      }

      return sendSuccess(res, 'Đổi mật khẩu thành công!');

    } catch (error) {
      console.error('ChangePassword error:', error);
      return sendError(res, 'Có lỗi xảy ra khi đổi mật khẩu.', 500);
    }
  },

  /**
   * PUT /api/users/change-avatar
   * Đổi ảnh đại diện
   * Body: FormData { avatar: File }
   */
  changeAvatar: async (req, res) => {
    try {
      if (!req.file) {
        return sendError(res, 'Vui lòng chọn ảnh đại diện.', 400);
      }

      const filename = req.file.filename;
      const updated = await UserModel.update(req.user.userId, { avatar: filename });

      if (!updated) {
        return sendError(res, 'Có vấn đề khi cập nhật ảnh!', 500);
      }

      return sendSuccess(res, 'Cập nhật ảnh đại diện thành công!', { avatar: filename });

    } catch (error) {
      console.error('ChangeAvatar error:', error);
      return sendError(res, 'Có lỗi xảy ra khi cập nhật ảnh.', 500);
    }
  }
};

module.exports = UserController;
