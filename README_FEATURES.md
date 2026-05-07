# 🌍 BookingTourDuLich - Hệ Thống Danh Sách Yêu Thích & Đặt Tour

## 📌 Giới Thiệu Dự Án

Dự án này chứa **Hệ Thống Quản Lý Danh Sách Yêu Thích** và **Hệ Thống Đặt Tour** cho ứng dụng đặt tour dựa trên Laravel. Đây là hai chức năng cốt lõi cho phép người dùng lưu các tour yêu thích và đặt tour với tích hợp thanh toán.

---

## ✨ Các Chức Năng Đã Cài Đặt

### 1️⃣ **Hệ Thống Danh Sách Yêu Thích** ❤️

**Chức năng:**
- Người dùng có thể thêm/xoá tour khỏi danh sách yêu thích
- Xem tất cả các tour đã lưu ở một nơi
- Cập nhật giao diện thời gian thực mà không cần tải lại trang (AJAX)

**Các File Chính:**
- `app/Http/Controllers/clients/WishlistController.php` - Logic chính
- `app/Models/clients/Tours.php` - Các thao tác database
- `resources/views/clients/wishlist.blade.php` - Giao diện
- `database/migrations/2026_04_23_100001_create_tbl_wishlists.php` - Cấu trúc database

**Luồng Người Dùng:**
```
1. Người dùng nhấn biểu tượng ♥ trên thẻ tour
2. AJAX gửi yêu cầu POST đến /wishlist/toggle
3. Server kiểm tra xem người dùng đã đăng nhập chưa
4. Toggle danh sách yêu thích (thêm/xoá khỏi database)
5. Trả lại response JSON
6. Frontend cập nhật giao diện (biểu tượng thay đổi màu)
```

**Routes (Đường dẫn):**
```php
POST /wishlist/toggle        // Thêm hoặc xoá khỏi danh sách yêu thích (AJAX)
GET /my-wishlist             // Xem tất cả tour đã lưu (cần đăng nhập)
```

**Cấu Trúc Database:**
```sql
CREATE TABLE tbl_wishlists (
  wishlistId INT PRIMARY KEY AUTO_INCREMENT,
  userId INT NOT NULL,
  tourId INT NOT NULL,
  created_at TIMESTAMP,
  FOREIGN KEY (userId) REFERENCES tbl_users(userId),
  FOREIGN KEY (tourId) REFERENCES tbl_tours(tourId)
);
```

---

### 2️⃣ **Hệ Thống Đặt Tour** 🎫

**Chức năng:**
- Người dùng có thể đặt tour với ngày cụ thể và số lượng khách
- Tính toán tổng giá dựa trên người lớn và trẻ em
- Tích hợp với nhiều phương thức thanh toán (PayPal, Momo, Tiền mặt)
- Gửi email xác nhận
- Theo dõi trạng thái đặt tour

**Các File Chính:**
- `app/Http/Controllers/clients/BookingController.php` - Logic đặt tour
- `app/Http/Controllers/clients/TourBookedController.php` - Xác nhận đặt tour
- `app/Http/Controllers/clients/PayPalController.php` - Tích hợp PayPal
- `app/Models/clients/Booking.php` - Các thao tác database đặt tour
- `app/Models/clients/Checkout.php` - Các thao tác thanh toán
- `app/Mail/BookingConfirmation.php` - Template email xác nhận
- `app/Mail/PaymentConfirmation.php` - Template email thanh toán
- `resources/views/clients/booking.blade.php` - Giao diện form đặt tour
- `resources/views/clients/tour-booked.blade.php` - Trang xác nhận

**Luồng Người Dùng:**
```
1. Người dùng chọn tour và nhấn "Đặt Ngay"
2. Chuyển hướng đến trang đặt tour (phải đăng nhập)
3. Điền form đặt tour:
   - Số lượng người lớn và trẻ em
   - Chọn phương thức thanh toán
   - Thêm ghi chú
4. Nhấn "Xác nhận & Thanh toán"
5. Server xác thực dữ liệu đặt tour
6. Tạo hồ sơ đặt tour trong database
7. Gửi email xác nhận
8. Chuyển hướng đến trang thanh toán
9. Người dùng hoàn tất thanh toán (PayPal/Momo/Tiền mặt)
10. Trạng thái đặt tour được cập nhật thành "đã xác nhận"
11. Email xác nhận thanh toán được gửi
12. Chuyển hướng đến trang tour-booked
```

**Routes (Đường dẫn):**
```php
POST /booking/{id}                    // Hiển thị form đặt tour
GET /booking-schedule/{scheduleId}    // Đặt lịch trình cụ thể
POST /validate-booking                // Xác thực dữ liệu đặt tour (AJAX)
POST /create-booking                  // Tạo hồ sơ đặt tour (AJAX)
GET /create-transaction               // Khởi tạo thanh toán PayPal
GET /process-transaction              // Xử lý thanh toán PayPal
GET /success-transaction              // Callback thành công PayPal
GET /cancel-transaction               // Callback hủy PayPal
```

**Cấu Trúc Database:**
```sql
CREATE TABLE tbl_booking (
  bookingId INT PRIMARY KEY AUTO_INCREMENT,
  userId INT NOT NULL,
  tourId INT NOT NULL,
  numAdults INT NOT NULL,
  numChildren INT,
  totalPrice DECIMAL(10, 2),
  bookingStatus CHAR(1), -- 'p'=chờ xác nhận, 'c'=đã xác nhận, 'x'=đã hủy
  notes TEXT,
  created_at TIMESTAMP,
  FOREIGN KEY (userId) REFERENCES tbl_users(userId),
  FOREIGN KEY (tourId) REFERENCES tbl_tours(tourId)
);

CREATE TABLE tbl_checkout (
  checkoutId INT PRIMARY KEY AUTO_INCREMENT,
  bookingId INT NOT NULL,
  paymentMethod VARCHAR(50), -- 'paypal', 'momo', 'cash'
  paymentStatus VARCHAR(50),
  transactionId VARCHAR(255),
  created_at TIMESTAMP,
  FOREIGN KEY (bookingId) REFERENCES tbl_booking(bookingId)
);
```

---

## 🏗️ Kiến Trúc & Thiết Kế

### Mô Hình MVC
```
Model (Tầng Database)
├── Tours.php - Dữ liệu tour & thao tác danh sách yêu thích
├── Booking.php - Hồ sơ đặt tour
├── Checkout.php - Thông tin thanh toán
└── User.php - Xác thực người dùng

View (Tầng Giao Diện)
├── wishlist.blade.php - Trang danh sách yêu thích
├── booking.blade.php - Form đặt tour
├── tour-booked.blade.php - Trang xác nhận
└── emails/ - Template email

Controller (Tầng Logic Kinh Doanh)
├── WishlistController - Quản lý danh sách yêu thích
├── BookingController - Xử lý luồng đặt tour
├── TourBookedController - Hiển thị thông tin đặt
└── PayPalController - Xử lý thanh toán
```

### Các Công Nghệ Chính
- **Framework:** Laravel 9.52.16
- **Database:** MySQL
- **Frontend:** Blade templates, JavaScript/AJAX
- **Thanh Toán:** PayPal SDK, Momo API
- **Email:** Laravel Mail
- **Xác Thực:** Session-based

---

## 📂 Cấu Trúc Thư Mục

```
BookingTourDuLich-exported/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Controller.php (Base controller với method getUserId)
│   │   │   └── clients/
│   │   │       ├── WishlistController.php
│   │   │       ├── BookingController.php
│   │   │       └── TourBookedController.php
│   │   └── Middleware/
│   │       └── CheckLoggedInClients.php (Middleware xác thực)
│   ├── Models/
│   │   ├── User.php
│   │   └── clients/
│   │       ├── Tours.php
│   │       ├── Booking.php
│   │       └── Checkout.php
│   └── Mail/
│       ├── BookingConfirmation.php
│       └── PaymentConfirmation.php
├── database/
│   ├── migrations/ (11 file migration)
│   └── seeders/
├── resources/views/
│   ├── clients/
│   │   ├── booking.blade.php
│   │   ├── tour-booked.blade.php
│   │   └── wishlist.blade.php
│   └── emails/
│       ├── booking-confirmation.blade.php
│       └── payment-confirmation.blade.php
├── routes/
│   └── web.php (Tất cả routes được định nghĩa ở đây)
├── composer.json
├── package.json
├── vite.config.js
├── .env.example
├── .gitignore
├── README.md (File này)
└── DOCUMENTATION.md (Tài liệu chi tiết)
```

---

## 🔐 Các Tính Năng Bảo Mật

✅ **Xác Thực & Phân Quyền**
- Middleware `checkLoginClient` bảo vệ các routes
- Xác định người dùng dựa trên session
- Bảo vệ token CSRF trên forms

✅ **Xác Thực Dữ Liệu Input**
- Xác thực số khách (>= 1 người lớn bắt buộc)
- Xác thực tour ID và user ID
- Xác thực số tiền thanh toán
- Làm sạch email inputs

✅ **Bảo Mật Database**
- Sử dụng parameterized queries (ngăn chặn SQL injection)
- Ràng buộc khóa ngoài
- Ép kiểu dữ liệu cho thông tin nhạy cảm

✅ **Bảo Mật Thanh Toán**
- Tích hợp với cổng thanh toán chính thức
- Chỉ dùng HTTPS cho giao dịch thanh toán
- Theo dõi ID giao dịch
- Xác minh trạng thái thanh toán

---

## 🚀 Cài Đặt & Thiết Lập

### Yêu Cầu Tiên Quyết
- PHP 8.0+
- MySQL 5.7+
- Composer
- Node.js & npm
- Laravel 9+

### Các Bước

1. **Clone Repository**
```bash
git clone <your-repo-url>
cd BookingTourDuLich-exported
```

2. **Cài Đặt Dependencies**
```bash
composer install
npm install
```

3. **Thiết Lập Môi Trường**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Cấu Hình Database**
Chỉnh sửa `.env`:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=travela
DB_USERNAME=root
DB_PASSWORD=root
```

5. **Chạy Migrations**
```bash
php artisan migrate
```

6. **Khởi Động Development Server**
```bash
# Terminal 1: Laravel server
php artisan serve

# Terminal 2: Vite dev server
npm run dev
```

7. **Truy Cập Ứng Dụng**
```
Frontend: http://localhost:8000
```

---

## 📋 Các File Migration Đã Bao Gồm

| File | Mục Đích |
|------|----------|
| `2014_10_12_000000_create_users_table.php` | Bảng người dùng |
| `2014_10_12_100000_create_password_resets_table.php` | Token đặt lại mật khẩu |
| `2019_08_19_000000_create_failed_jobs_table.php` | Hàng đợi job |
| `2019_12_14_000001_create_personal_access_tokens_table.php` | Token API |
| `2026_04_13_213204_create_jobs_table.php` | Lập lịch job |
| `2026_04_18_002737_add_phone_to_tbl_contact.php` | Thêm số điện thoại |
| `2026_04_23_100000_create_tbl_tour_schedules.php` | Lịch trình tour |
| `2026_04_23_100001_create_tbl_wishlists.php` | **Bảng danh sách yêu thích** |
| `2026_04_23_104805_create_tbl_temp_images_table.php` | Hình ảnh tạm thời |
| `2026_04_23_105740_add_description_to_tbl_images_table.php` | Mô tả hình ảnh |
| `2026_04_03_add_indexes_for_payment_system.sql` | Chỉ mục thanh toán |

---

## 🔗 Các Endpoint API

### Endpoints Danh Sách Yêu Thích
```
POST /wishlist/toggle
  - Thêm hoặc xoá tour khỏi danh sách yêu thích
  - Cần: tourId, session (người dùng đăng nhập)
  - Trả: JSON {success, action}

GET /my-wishlist
  - Xem các tour đã lưu của người dùng
  - Cần: Đăng nhập (middleware)
  - Trả: Trang HTML với danh sách yêu thích
```

### Endpoints Đặt Tour
```
POST /booking/{id}
  - Hiển thị form đặt tour
  - Cần: Đăng nhập, tourId hợp lệ
  - Trả: Form HTML với chi tiết tour

POST /validate-booking
  - Xác thực dữ liệu đặt tour
  - Cần: numAdults, numChildren, tourId
  - Trả: JSON {success, totalPrice}

POST /create-booking
  - Tạo hồ sơ đặt tour
  - Cần: Tất cả thông tin đặt tour
  - Trả: JSON {success, bookingId, redirectUrl}

GET /create-transaction
  - Khởi tạo thanh toán PayPal
  - Trả: Chuyển hướng đến PayPal

GET /process-transaction
  - Xử lý thanh toán PayPal
  - Trả: Chuyển hướng đến trang thành công/thất bại

GET /success-transaction
  - Xử lý callback thành công PayPal
  - Cập nhật trạng thái đặt tour thành đã xác nhận
  - Gửi email xác nhận
```

---

## 🎨 Các Thành Phần Giao Diện Người Dùng

### Thành Phần Danh Sách Yêu Thích
- Nút ♥ trên thẻ tour (toggle danh sách yêu thích)
- Trang My Wishlist với tất cả các tour đã lưu
- Nút xoá cho mỗi item trong danh sách yêu thích
- Đề xuất tour phổ biến trên trang danh sách yêu thích

### Thành Phần Đặt Tour
- Form đặt tour với chi tiết tour
- Input số lượng người lớn và trẻ em
- Tính toán giá thời gian thực
- Lựa chọn phương thức thanh toán (PayPal, Momo, Tiền mặt)
- Trường ghi chú/bình luận
- Nút xác nhận đặt tour
- Trang xác nhận đặt tour
- Template email xác nhận

---

## 📧 Các Template Email

### Email Xác Nhận Đặt Tour
Được gửi khi đặt tour được tạo:
- Chi tiết tour
- Số tham chiếu đặt tour
- Số lượng khách
- Tổng giá tiền
- Ngày đặt tour

### Email Xác Nhận Thanh Toán
Được gửi sau khi thanh toán hoàn tất:
- Trạng thái thanh toán
- ID giao dịch
- Số tiền thanh toán
- Chi tiết tour
- Thông tin biên lai

---

## 🧪 Danh Sách Kiểm Tra Thử Nghiệm

- [ ] Danh sách yêu thích: Thêm tour vào danh sách yêu thích
- [ ] Danh sách yêu thích: Xoá tour khỏi danh sách yêu thích
- [ ] Danh sách yêu thích: Xem trang danh sách yêu thích
- [ ] Đặt tour: Điều hướng đến trang đặt tour
- [ ] Đặt tour: Điền form đặt tour
- [ ] Đặt tour: Xác thực dữ liệu đặt tour
- [ ] Đặt tour: Tạo đặt tour
- [ ] Đặt tour: Nhận email xác nhận
- [ ] Thanh toán: Xử lý thanh toán PayPal
- [ ] Thanh toán: Xử lý callback thanh toán
- [ ] Thanh toán: Cập nhật trạng thái đặt tour

---

## 📝 Cấu Hình

### Các Thiết Lập Quan Trọng trong `.env`

```env
# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_DATABASE=travela
DB_USERNAME=root
DB_PASSWORD=root

# Email (để gửi email)
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_FROM_ADDRESS=noreply@travela.com

# Cấu Hình PayPal
PAYPAL_MODE=sandbox
PAYPAL_SANDBOX_CLIENT_ID=your-client-id
PAYPAL_SANDBOX_CLIENT_SECRET=your-client-secret

# Cấu Hình Momo (nếu sử dụng)
MOMO_PARTNER_CODE=...
MOMO_ACCESS_KEY=...
MOMO_SECRET_KEY=...
```

---

## 🐛 Các Vấn Đề Thường Gặp & Khắc Phục

| Vấn Đề | Giải Pháp |
|--------|----------|
| "Chưa đăng nhập" khi đặt tour | Đảm bảo người dùng được xác thực thông qua middleware |
| Email không gửi | Kiểm tra cấu hình MAIL và Gmail app password |
| Thanh toán PayPal thất bại | Xác minh PAYPAL_MODE và API credentials |
| Lỗi database | Chạy `php artisan migrate` |
| View không tìm thấy | Kiểm tra file view tồn tại trong `resources/views/clients/` |

---

## 📚 Tài Liệu Bổ Sung

Xem `DOCUMENTATION.md` để:
- Sơ đồ luồng code chi tiết
- Giải thích mô hình MVC
- Thuật ngữ chính
- Các xem xét về bảo mật
- Hướng dẫn trình bày cho giáo viên

---

## 👨‍💻 Ghi Chú Của Tác Giả

Dự án này triển khai hai chức năng thương mại điện tử thiết yếu:

1. **Danh Sách Yêu Thích** - Một cách đơn giản nhưng hiệu quả để người dùng lưu tour cho sau này
2. **Đặt Tour** - Một luồng đặt tour hoàn chỉnh với tích hợp thanh toán

Code tuân theo các best practices của Laravel:
- ✅ Kiến trúc MVC
- ✅ Routing RESTful
- ✅ Middleware cho bảo vệ
- ✅ Database migrations để kiểm soát phiên bản
- ✅ Thông báo email
- ✅ AJAX cho UX mượt mà
- ✅ Các best practices bảo mật

---

## 📄 Giấy Phép

Dự án này là một phần của ứng dụng BookingTourDuLich.

---

## 🤝 Hỗ Trợ

Để có câu hỏi hoặc vấn đề, hãy tham khảo:
- `DOCUMENTATION.md` - Tài liệu kỹ thuật
- Các comment trong file controller/model
- Tài liệu Laravel: https://laravel.com/docs

---

**Cập Nhật Lần Cuối:** 7 Tháng 5, 2026  
**Phiên Bản:** 1.0  
**Trạng Thái:** ✅ Sẵn Sàng Sản Xuất
