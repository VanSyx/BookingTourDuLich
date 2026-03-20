/**
 * ============================================
 * CONTROLLER: XÁC THỰC (Authentication)
 * ============================================
 * Xử lý đăng ký, đăng nhập, kích hoạt tài khoản.
 * Sử dụng JWT token cho xác thực.
 */

const bcrypt = require('bcryptjs');
const jwt = require('jsonwebtoken');
const UserModel = require('../models/userModel');
const { sendSuccess, sendError } = require('../utils/response');
const { generateRandomString } = require('../utils/helpers');
const { sendActivationEmail } = require('../services/emailService');

const AuthController = {
  /**
   * POST /api/auth/register
   * Đăng ký tài khoản mới
   * Body: { username, email, password }
   */
  register: async (req, res) => {
    try {
      const { username, email, password } = req.body;

      // Validate dữ liệu đầu vào
      if (!username || !email || !password) {
        return sendError(res, 'Vui lòng nhập đầy đủ thông tin.', 400);
      }

      // Kiểm tra username/email đã tồn tại chưa
      const existingUser = await UserModel.checkExist(username, email);
      if (existingUser) {
        return sendError(res, 'Tên người dùng hoặc email đã tồn tại!', 400);
      }

      // Mã hóa mật khẩu bằng bcrypt (bảo mật hơn MD5)
      const hashedPassword = await bcrypt.hash(password, 10);

      // Tạo token kích hoạt
      const activationToken = generateRandomString(60);

      // Lưu user vào CSDL
      const userId = await UserModel.create({
        username,
        email,
        password: hashedPassword,
        activation_token: activationToken,
        avatar: 'default.png'
      });

      // Gửi email kích hoạt (không chặn response)
      const activationLink = `http://localhost:5000/api/auth/activate/${activationToken}`;
      sendActivationEmail(email, activationLink).catch(err => {
        console.error('Lỗi gửi email kích hoạt:', err.message);
      });

      return sendSuccess(res, 'Đăng ký thành công! Vui lòng kiểm tra email để kích hoạt tài khoản.', {
        userId,
        username,
        email
      }, 201);

    } catch (error) {
      console.error('Register error:', error);
      return sendError(res, 'Có lỗi xảy ra khi đăng ký.', 500);
    }
  },

  /**
   * POST /api/auth/login
   * Đăng nhập tài khoản
   * Body: { username, password }
   * Response: { token, user }
   */
  login: async (req, res) => {
    try {
      const { username, password } = req.body;

      // Validate
      if (!username || !password) {
        return sendError(res, 'Vui lòng nhập tên đăng nhập và mật khẩu.', 400);
      }

      // Tìm user
      const user = await UserModel.findByUsername(username);
      if (!user) {
        return sendError(res, 'Thông tin tài khoản không chính xác!', 401);
      }

      // Kiểm tra tài khoản bị chặn
      if (user.status === 'b') {
        return sendError(res, 'Tài khoản của bạn đã bị chặn.', 403);
      }

      // So sánh mật khẩu
      const isPasswordValid = await bcrypt.compare(password, user.password);
      if (!isPasswordValid) {
        return sendError(res, 'Thông tin tài khoản không chính xác!', 401);
      }

      // Tạo JWT token
      const token = jwt.sign(
        { userId: user.userId, username: user.username },
        process.env.JWT_SECRET,
        { expiresIn: process.env.JWT_EXPIRES_IN || '7d' }
      );

      return sendSuccess(res, 'Đăng nhập thành công!', {
        token,
        user: {
          userId: user.userId,
          username: user.username,
          email: user.email,
          fullName: user.fullName,
          avatar: user.avatar,
          isActive: user.isActive
        }
      });

    } catch (error) {
      console.error('Login error:', error);
      return sendError(res, 'Có lỗi xảy ra khi đăng nhập.', 500);
    }
  },

  /**
   * GET /api/auth/activate/:token
   * Kích hoạt tài khoản qua email link
   */
  activate: async (req, res) => {
    try {
      const { token } = req.params;

      // Tìm user theo activation token
      const user = await UserModel.findByToken(token);
      if (!user) {
        return sendError(res, 'Mã kích hoạt không hợp lệ!', 400);
      }

      // Kích hoạt tài khoản
      await UserModel.activate(token);

      return sendSuccess(res, 'Tài khoản đã được kích hoạt thành công!');

    } catch (error) {
      console.error('Activate error:', error);
      return sendError(res, 'Có lỗi xảy ra khi kích hoạt tài khoản.', 500);
    }
  },

  /**
   * GET /api/auth/profile
   * Lấy thông tin user đang đăng nhập (cần auth middleware)
   */
  getProfile: async (req, res) => {
    try {
      const user = await UserModel.findById(req.user.userId);
      if (!user) {
        return sendError(res, 'Không tìm thấy người dùng.', 404);
      }

      return sendSuccess(res, 'Lấy thông tin thành công.', {
        userId: user.userId,
        username: user.username,
        email: user.email,
        fullName: user.fullName,
        avatar: user.avatar,
        address: user.address,
        phoneNumber: user.phoneNumber,
        isActive: user.isActive,
        created_at: user.created_at
      });

    } catch (error) {
      console.error('GetProfile error:', error);
      return sendError(res, 'Có lỗi xảy ra.', 500);
    }
  }
};

module.exports = AuthController;
