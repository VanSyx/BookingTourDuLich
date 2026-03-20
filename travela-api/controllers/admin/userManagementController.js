/**
 * ============================================
 * CONTROLLER: ADMIN - QUẢN LÝ NGƯỜI DÙNG
 * ============================================
 */

const UserModel = require('../../models/userModel');
const { sendSuccess, sendError } = require('../../utils/response');

const UserManagementController = {
  /**
   * GET /api/admin/users
   * Lấy danh sách tất cả người dùng
   */
  getAll: async (req, res) => {
    try {
      const users = await UserModel.getAll();

      // Xử lý hiển thị
      const processedUsers = users.map(user => ({
        ...user,
        password: undefined,
        activation_token: undefined,
        fullName: user.fullName || 'Unnamed',
        avatar: user.avatar || 'default.png',
        isActiveText: user.isActive === 'y' ? 'Đã kích hoạt' : 'Chưa kích hoạt'
      }));

      return sendSuccess(res, 'Lấy danh sách người dùng thành công.', processedUsers);
    } catch (error) {
      console.error('AdminGetUsers error:', error);
      return sendError(res, 'Có lỗi xảy ra.', 500);
    }
  },

  /**
   * PUT /api/admin/users/:id/activate
   * Kích hoạt tài khoản người dùng
   */
  activate: async (req, res) => {
    try {
      const userId = Number(req.params.id);
      const updated = await UserModel.activateByAdmin(userId);

      if (!updated) {
        return sendError(res, 'Có lỗi xảy ra khi kích hoạt.', 500);
      }

      return sendSuccess(res, 'Người dùng đã được kích hoạt thành công!');
    } catch (error) {
      console.error('AdminActivateUser error:', error);
      return sendError(res, 'Có lỗi xảy ra.', 500);
    }
  },

  /**
   * PUT /api/admin/users/:id/status
   * Thay đổi trạng thái (block/delete)
   * Body: { status } - 'b'=block, 'd'=delete
   */
  changeStatus: async (req, res) => {
    try {
      const userId = Number(req.params.id);
      const { status } = req.body;

      if (!['b', 'd'].includes(status)) {
        return sendError(res, 'Trạng thái không hợp lệ. Dùng "b" (chặn) hoặc "d" (xóa).', 400);
      }

      const updated = await UserModel.changeStatus(userId, status);
      if (!updated) {
        return sendError(res, 'Có lỗi xảy ra.', 500);
      }

      const statusText = status === 'b' ? 'Đã chặn' : 'Đã xóa';
      return sendSuccess(res, 'Cập nhật trạng thái thành công!', { status: statusText });
    } catch (error) {
      console.error('AdminChangeStatus error:', error);
      return sendError(res, 'Có lỗi xảy ra.', 500);
    }
  }
};

module.exports = UserManagementController;
