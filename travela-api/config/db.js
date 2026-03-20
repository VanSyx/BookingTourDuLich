/**
 * ============================================
 * CẤU HÌNH KẾT NỐI CƠ SỞ DỮ LIỆU MYSQL
 * ============================================
 * Sử dụng mysql2 với connection pool để tối ưu hiệu suất.
 * Kết nối tới CSDL 'travela' trên XAMPP MySQL (127.0.0.1:3306).
 */

const mysql = require('mysql2/promise');
require('dotenv').config();

// Tạo connection pool - tái sử dụng kết nối thay vì tạo mới mỗi lần query
const pool = mysql.createPool({
  host: process.env.DB_HOST || '127.0.0.1',
  port: process.env.DB_PORT || 3306,
  user: process.env.DB_USER || 'root',
  password: process.env.DB_PASSWORD || '',
  database: process.env.DB_NAME || 'travela',
  waitForConnections: true,   // Đợi nếu tất cả kết nối đang bận
  connectionLimit: 10,        // Tối đa 10 kết nối đồng thời
  queueLimit: 0,              // Không giới hạn hàng đợi
  charset: 'utf8mb4'          // Hỗ trợ tiếng Việt & emoji
});

/**
 * Kiểm tra kết nối database khi khởi động server
 */
const testConnection = async () => {
  try {
    const connection = await pool.getConnection();
    console.log('✅ Kết nối MySQL thành công! Database:', process.env.DB_NAME);
    connection.release();
  } catch (error) {
    console.error('❌ Lỗi kết nối MySQL:', error.message);
    process.exit(1); // Thoát nếu không kết nối được DB
  }
};

module.exports = { pool, testConnection };
