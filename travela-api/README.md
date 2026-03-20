# 🌍 Travela API - RESTful Backend

API Backend cho dự án **BookingTourDuLich (Travela)** sử dụng Node.js + Express + MySQL.

## 🛠 Cài đặt & Chạy

### Yêu cầu
- **Node.js** >= 16
- **XAMPP** (MySQL chạy trên port 3306)
- Database `travela` đã import từ file `travela.sql`

### Cài đặt
```bash
cd travela-api
npm install
```

### Cấu hình
Sửa file `.env` cho phù hợp (đặc biệt `DB_PASSWORD` và `MAIL_*`).

### Chạy server
```bash
# Production
npm start

# Development (auto-restart)
npm run dev
```

Server chạy tại: **http://localhost:5000/api**

---

## 📁 Cấu trúc thư mục

```
travela-api/
├── app.js                     # Entry point
├── .env                       # Config biến môi trường
├── config/db.js               # Kết nối MySQL
├── middleware/
│   ├── auth.js                # JWT cho user
│   ├── adminAuth.js           # JWT cho admin
│   └── upload.js              # Upload file (Multer)
├── models/                    # Tầng truy vấn CSDL
├── controllers/               # Xử lý logic
│   └── admin/                 # Controllers admin
├── routes/                    # Định nghĩa endpoints
│   └── admin/index.js         # Gộp routes admin
├── services/                  # Email, MoMo payment
├── utils/                     # Helpers, response format
└── uploads/                   # Thư mục chứa ảnh upload
```

---

## 📡 API Endpoints

### 🔐 Authentication (`/api/auth`)
| Method | Endpoint | Auth | Mô tả |
|--------|----------|------|--------|
| POST | `/api/auth/register` | ❌ | Đăng ký |
| POST | `/api/auth/login` | ❌ | Đăng nhập → JWT token |
| GET | `/api/auth/activate/:token` | ❌ | Kích hoạt tài khoản |
| GET | `/api/auth/profile` | ✅ | Thông tin user |

### 👤 User Profile (`/api/users`)
| Method | Endpoint | Auth | Mô tả |
|--------|----------|------|--------|
| GET | `/api/users/profile` | ✅ | Xem hồ sơ |
| PUT | `/api/users/profile` | ✅ | Cập nhật hồ sơ |
| PUT | `/api/users/change-password` | ✅ | Đổi mật khẩu |
| PUT | `/api/users/change-avatar` | ✅ | Đổi avatar (FormData) |

### 🏝 Tours (`/api/tours`)
| Method | Endpoint | Auth | Mô tả |
|--------|----------|------|--------|
| GET | `/api/tours` | ❌ | Danh sách (phân trang, lọc) |
| GET | `/api/tours/popular` | ❌ | Tours phổ biến |
| GET | `/api/tours/domains` | ❌ | Thống kê theo miền |
| GET | `/api/tours/:id` | ❌ | Chi tiết tour |

### 📋 Booking (`/api/bookings`)
| Method | Endpoint | Auth | Mô tả |
|--------|----------|------|--------|
| POST | `/api/bookings` | ✅ | Tạo booking |
| GET | `/api/bookings` | ✅ | Bookings của tôi |
| GET | `/api/bookings/:id` | ✅ | Chi tiết booking |
| POST | `/api/bookings/:id/cancel` | ✅ | Hủy booking |
| POST | `/api/bookings/check` | ✅ | Kiểm tra đã hoàn thành |

### ⭐ Reviews (`/api/reviews`)
| Method | Endpoint | Auth | Mô tả |
|--------|----------|------|--------|
| POST | `/api/reviews` | ✅ | Gửi đánh giá |
| GET | `/api/reviews/tour/:tourId` | ❌ | Reviews theo tour |

### 📞 Contact (`/api/contacts`)
| Method | Endpoint | Auth | Mô tả |
|--------|----------|------|--------|
| POST | `/api/contacts` | ❌ | Gửi liên hệ |

### 🔍 Search (`/api/search`)
| Method | Endpoint | Auth | Mô tả |
|--------|----------|------|--------|
| GET | `/api/search?destination&start_date&end_date` | ❌ | Tìm theo điểm đến |
| GET | `/api/search/keyword?keyword` | ❌ | Tìm theo từ khóa |

### 🔧 Admin (`/api/admin`)
| Method | Endpoint | Auth | Mô tả |
|--------|----------|------|--------|
| POST | `/api/admin/auth/login` | ❌ | Đăng nhập admin |
| GET | `/api/admin/dashboard` | 🔒 | Thống kê |
| GET/POST/PUT/DELETE | `/api/admin/tours[/:id]` | 🔒 | CRUD Tours |
| GET/PUT/POST | `/api/admin/bookings[/:id]` | 🔒 | Quản lý Booking |
| GET/PUT | `/api/admin/users[/:id]` | 🔒 | Quản lý Users |
| GET/POST | `/api/admin/contacts[/:id]` | 🔒 | Quản lý Liên hệ |

> ✅ = JWT User | 🔒 = JWT Admin | ❌ = Không cần auth

---

## 📦 Response Format

Tất cả API trả về JSON cùng format:

```json
{
  "success": true,
  "message": "Thao tác thành công",
  "data": { ... }
}
```

```json
{
  "success": false,
  "message": "Mô tả lỗi"
}
```

---

## 🧪 Test API với Postman

1. Import file `postman_collection.json` vào Postman
2. Đăng nhập (`POST /api/auth/login`) → copy `token` từ response
3. Thêm header `Authorization: Bearer <token>` cho các request cần auth
4. Với admin, dùng `POST /api/admin/auth/login` → token riêng
