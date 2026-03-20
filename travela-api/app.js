/**
 * ============================================
 * APP.JS - ENTRY POINT CỦA SERVER
 * ============================================
 *
 * Travela API - RESTful Backend
 * Dự án: BookingTourDuLich
 * Tech: Node.js + Express + MySQL (XAMPP)
 *
 * Khởi chạy: node app.js
 * Dev mode:  npm run dev (nodemon)
 */

const express = require('express');
const cors = require('cors');
const path = require('path');
require('dotenv').config();

// Import cấu hình database
const { testConnection } = require('./config/db');

// Import routes
const authRoutes = require('./routes/authRoutes');
const userRoutes = require('./routes/userRoutes');
const tourRoutes = require('./routes/tourRoutes');
const bookingRoutes = require('./routes/bookingRoutes');
const paymentRoutes = require('./routes/paymentRoutes');
const reviewRoutes = require('./routes/reviewRoutes');
const contactRoutes = require('./routes/contactRoutes');
const searchRoutes = require('./routes/searchRoutes');
const adminRoutes = require('./routes/admin');

// ============================================
// KHỞI TẠO EXPRESS APP
// ============================================
const app = express();
const PORT = process.env.PORT || 5000;

// ============================================
// MIDDLEWARE CƠ BẢN
// ============================================

// Cho phép Cross-Origin requests (CORS)
app.use(cors());

// Parse JSON body
app.use(express.json());

// Parse URL-encoded body (form data)
app.use(express.urlencoded({ extended: true }));

// Phục vụ file tĩnh (ảnh upload)
app.use('/uploads', express.static(path.join(__dirname, 'uploads')));

// ============================================
// LOG REQUEST (Development)
// ============================================
app.use((req, res, next) => {
  console.log(`[${new Date().toLocaleTimeString()}] ${req.method} ${req.url}`);
  next();
});

// ============================================
// ĐĂNG KÝ ROUTES
// ============================================

// Route gốc - kiểm tra server hoạt động
app.get('/api', (req, res) => {
  res.json({
    success: true,
    message: '🌍 Chào mừng đến với Travela API!',
    version: '1.0.0',
    endpoints: {
      auth: '/api/auth',
      users: '/api/users',
      tours: '/api/tours',
      bookings: '/api/bookings',
      payments: '/api/payments',
      reviews: '/api/reviews',
      contacts: '/api/contacts',
      search: '/api/search',
      admin: '/api/admin'
    }
  });
});

// Client routes
app.use('/api/auth', authRoutes);         // Xác thực
app.use('/api/users', userRoutes);        // Hồ sơ người dùng
app.use('/api/tours', tourRoutes);        // Tours
app.use('/api/bookings', bookingRoutes);  // Đặt tour
app.use('/api/payments', paymentRoutes);  // Thanh toán
app.use('/api/reviews', reviewRoutes);    // Đánh giá
app.use('/api/contacts', contactRoutes);  // Liên hệ
app.use('/api/search', searchRoutes);     // Tìm kiếm

// Admin routes
app.use('/api/admin', adminRoutes);       // Tất cả admin routes

// ============================================
// XỬ LÝ ROUTE KHÔNG TỒN TẠI (404)
// ============================================
app.use('*', (req, res) => {
  res.status(404).json({
    success: false,
    message: `Route ${req.originalUrl} không tồn tại.`
  });
});

// ============================================
// XỬ LÝ LỖI TOÀN CỤC
// ============================================
app.use((err, req, res, next) => {
  console.error('❌ Server Error:', err.stack);

  // Lỗi Multer (upload file)
  if (err.code === 'LIMIT_FILE_SIZE') {
    return res.status(400).json({
      success: false,
      message: 'File quá lớn. Giới hạn tối đa 5MB.'
    });
  }

  res.status(500).json({
    success: false,
    message: 'Lỗi server nội bộ.'
  });
});

// ============================================
// KHỞI ĐỘNG SERVER
// ============================================
const startServer = async () => {
  // Kiểm tra kết nối database trước
  await testConnection();

  app.listen(PORT, () => {
    console.log('==========================================');
    console.log(`🚀 Travela API Server đang chạy!`);
    console.log(`📡 URL: http://localhost:${PORT}/api`);
    console.log(`📋 Environment: ${process.env.NODE_ENV || 'development'}`);
    console.log('==========================================');
  });
};

startServer();

module.exports = app;
