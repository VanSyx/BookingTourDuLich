/**
 * ============================================
 * MIDDLEWARE: UPLOAD FILE (Multer)
 * ============================================
 * Cấu hình Multer để upload ảnh (avatar, tour images).
 * - Lưu vào thư mục uploads/avatars/ hoặc uploads/tours/
 * - Chỉ cho phép: jpeg, png, jpg, gif
 * - Giới hạn kích thước: 5MB
 */

const multer = require('multer');
const path = require('path');

/**
 * Tạo cấu hình storage cho Multer
 * @param {string} folder - Thư mục con trong uploads/ (VD: 'avatars', 'tours')
 */
const createStorage = (folder) => {
  return multer.diskStorage({
    // Đường dẫn lưu file
    destination: (req, file, cb) => {
      cb(null, path.join(__dirname, '..', 'uploads', folder));
    },
    // Đặt tên file: originalname_timestamp.extension
    filename: (req, file, cb) => {
      const originalName = path.parse(file.originalname).name.replace(/[^A-Za-z0-9_\-]/g, '_');
      const ext = path.extname(file.originalname);
      const filename = `${originalName}_${Date.now()}${ext}`;
      cb(null, filename);
    }
  });
};

/**
 * Bộ lọc file - chỉ cho phép ảnh
 */
const fileFilter = (req, file, cb) => {
  const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
  if (allowedTypes.includes(file.mimetype)) {
    cb(null, true);
  } else {
    cb(new Error('Chỉ cho phép upload file ảnh (jpeg, png, jpg, gif)'), false);
  }
};

// Upload avatar (1 file, max 5MB)
const uploadAvatar = multer({
  storage: createStorage('avatars'),
  fileFilter,
  limits: { fileSize: 5 * 1024 * 1024 } // 5MB
}).single('avatar');

// Upload ảnh tour (1 file mỗi lần, max 5MB)
const uploadTourImage = multer({
  storage: createStorage('tours'),
  fileFilter,
  limits: { fileSize: 5 * 1024 * 1024 } // 5MB
}).single('image');

module.exports = { uploadAvatar, uploadTourImage };
