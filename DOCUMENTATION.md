# 📚 DOCUMENTATION: Wishlist & Booking System

## 1️⃣ KIẾN TRÚC MVC (Model-View-Controller)

```
MVC Pattern:
├── Model (Database Layer)
│   ├── Tours.php → Quản lý dữ liệu tour
│   ├── Booking.php → Quản lý đặt tour
│   ├── Checkout.php → Quản lý thanh toán
│   └── User.php → Quản lý người dùng
│
├── View (UI Layer)
│   ├── wishlist.blade.php → Giao diện wishlist
│   ├── booking.blade.php → Giao diện đặt tour
│   ├── tour-booked.blade.php → Giao diện xác nhận
│   └── emails/ → Email xác nhận
│
└── Controller (Logic Layer)
    ├── WishlistController.php → Xử lý logic wishlist
    ├── BookingController.php → Xử lý logic đặt tour
    └── TourBookedController.php → Xử lý thông tin đặt
```

---

## 2️⃣ LUỒNG WISHLIST (Danh Sách Yêu Thích)

### 2.1 - Luồng UI/UX
```
1. User vào trang Tour
   ↓
2. Nhấn nút ♥ (Thêm vào Wishlist)
   ↓
3. JavaScript gửi POST request đến /wishlist/toggle
   ↓
4. Server kiểm tra user đã login chưa
   ├─ Chưa login → Trả JSON lỗi (401)
   └─ Đã login → Xử lý toggle
   ↓
5. Server kiểm tra tour có trong wishlist chưa
   ├─ Có → Xoá khỏi wishlist
   └─ Không → Thêm vào wishlist
   ↓
6. Trả JSON response về client
   ↓
7. JavaScript update UI (thay đổi màu icon)
```

### 2.2 - Code Flow Chi Tiết

**Step 1: Frontend gửi request**
```javascript
// resources/views/clients/wishlist.blade.php
POST /wishlist/toggle
{
  "tourId": 5,
  "csrfToken": "..."
}
```

**Step 2: Route định hướng**
```php
// routes/web.php
Route::post('/wishlist/toggle', [WishlistController::class, 'toggle'])
  ->name('wishlist.toggle');
```

**Step 3: Controller xử lý logic**
```php
// app/Http/Controllers/clients/WishlistController.php

public function toggle(Request $request)
{
  // 1. Kiểm tra authentication (Session)
  if (!$request->session()->has('username')) {
    return response()->json([
      'success' => false,
      'auth' => false,
      'message' => 'Vui lòng đăng nhập'
    ], 401);
  }

  // 2. Lấy thông tin từ request
  $tourId = $request->input('tourId');
  $userId = $this->getUserId(); // Lấy từ session
  
  // 3. Kiểm tra tour có tồn tại trong wishlist không
  $exists = $this->tours->checkWishlist($userId, $tourId);
  
  // 4. Toggle: Nếu có thì xoá, không thì thêm
  if ($exists) {
    $this->tours->removeWishlist($userId, $tourId); // Xoá
    return response()->json([
      'success' => true,
      'action' => 'removed'
    ]);
  } else {
    $this->tours->addWishlist($userId, $tourId); // Thêm
    return response()->json([
      'success' => true,
      'action' => 'added'
    ]);
  }
}
```

**Step 4: Model thao tác Database**
```php
// app/Models/clients/Tours.php

public function addWishlist($userId, $tourId)
{
  return DB::table('tbl_wishlists')->insert([
    'userId' => $userId,
    'tourId' => $tourId,
    'created_at' => now()
  ]);
}

public function removeWishlist($userId, $tourId)
{
  return DB::table('tbl_wishlists')
    ->where('userId', $userId)
    ->where('tourId', $tourId)
    ->delete();
}
```

**Step 5: Database lưu dữ liệu**
```sql
-- Table structure
CREATE TABLE tbl_wishlists (
  wishlistId INT PRIMARY KEY AUTO_INCREMENT,
  userId INT NOT NULL,
  tourId INT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (userId) REFERENCES tbl_users(userId),
  FOREIGN KEY (tourId) REFERENCES tbl_tours(tourId)
);
```

### 2.3 - Xem Danh Sách Yêu Thích
```
User nhấn "My Wishlist"
  ↓
GET /my-wishlist (có middleware checkLoginClient)
  ↓
WishlistController@index
  ↓
Lấy userId từ session
  ↓
Tours model gọi getWishlistByUser($userId)
  ↓
Query: SELECT * FROM tbl_wishlists 
       WHERE userId = ? 
       JOIN tbl_tours ...
  ↓
Trả dữ liệu về View
  ↓
Render wishlist.blade.php
```

---

## 3️⃣ LUỒNG BOOKING (Đặt Tour)

### 3.1 - Luồng Tổng Quát
```
1. User chọn tour từ tours page
   ↓
2. Nhấn "Đặt ngay"
   ↓
3. Redirect tới trang booking (middleware check login)
   ↓
4. Hiển thị form booking (BookingController@index)
   ├─ Tour details
   ├─ Giá tiền
   ├─ Form: Số người lớn, trẻ em, ghi chú
   └─ Phương thức thanh toán (Momo, PayPal)
   ↓
5. User nhấn "Xác nhận & Thanh toán"
   ↓
6. Validate dữ liệu (validateBooking)
   ├─ Kiểm tra số người
   ├─ Kiểm tra user
   └─ Kiểm tra giá tiền
   ↓
7. Tạo booking record (createBooking)
   ├─ INSERT vào tbl_booking
   ├─ INSERT vào tbl_checkout
   └─ Gửi email xác nhận
   ↓
8. Redirect tới trang thanh toán
   ├─ Nếu Momo → Gọi API Momo
   ├─ Nếu PayPal → Gọi API PayPal
   └─ Nếu Tiền mặt → Chuyển tới trang chờ xác nhận
   ↓
9. User thanh toán
   ↓
10. Callback từ payment gateway
    ├─ Update trạng thái booking → "confirmed"
    ├─ Gửi email payment confirmation
    └─ Redirect tới tour-booked page
```

### 3.2 - Code Flow Chi Tiết

**Phase 1: Hiển thị Form Booking**
```php
// app/Http/Controllers/clients/BookingController.php

public function index($id)
{
  // 1. Lấy thông tin tour
  $tour = $this->tour->getTourDetail($id);
  
  if (!$tour) {
    return redirect()->route('tours')
      ->with('error', 'Không tìm thấy tour');
  }
  
  // 2. Lấy PayPal config
  $paypalClientId = config('paypal.sandbox.client_id');
  
  // 3. Render form booking
  return view('clients.booking', compact(
    'tour',
    'paypalClientId'
  ));
}
```

**Phase 2: Validate Booking Data**
```php
public function validateBooking(Request $req)
{
  $numAdults = (int) $req->input('numAdults');
  $numChildren = (int) $req->input('numChildren');
  $tourId = $req->input('tourId');
  $userId = $this->getUserId();
  
  // 1. Kiểm tra user đã login
  if (!$userId) {
    return response()->json([
      'success' => false,
      'message' => 'Vui lòng đăng nhập'
    ], 401);
  }
  
  // 2. Kiểm tra số người
  if ($numAdults < 1) {
    return response()->json([
      'success' => false,
      'message' => 'Phải có ít nhất 1 người lớn'
    ]);
  }
  
  // 3. Lấy giá tour
  $tour = $this->tour->getTourDetail($tourId);
  $totalPrice = ($numAdults * $tour->priceAdult) 
              + ($numChildren * $tour->priceChild);
  
  // 4. Trả về validation result
  return response()->json([
    'success' => true,
    'totalPrice' => $totalPrice
  ]);
}
```

**Phase 3: Tạo Booking**
```php
public function createBooking(Request $req)
{
  $userId = $this->getUserId();
  
  // 1. Chuẩn bị dữ liệu
  $bookingData = [
    'userId' => $userId,
    'tourId' => $req->input('tourId'),
    'numAdults' => $req->input('numAdults'),
    'numChildren' => $req->input('numChildren'),
    'totalPrice' => $req->input('totalPrice'),
    'bookingStatus' => 'p', // pending (chờ xác nhận)
    'created_at' => now()
  ];
  
  // 2. INSERT vào database
  $bookingId = $this->booking->createBooking($bookingData);
  
  // 3. Tạo checkout record
  $checkoutData = [
    'bookingId' => $bookingId,
    'paymentMethod' => $req->input('paymentMethod'),
    'paymentStatus' => 'pending'
  ];
  $this->checkout->create($checkoutData);
  
  // 4. Gửi email xác nhận
  Mail::to($user->email)->send(
    new BookingConfirmation($bookingData)
  );
  
  // 5. Trả về response
  return response()->json([
    'success' => true,
    'bookingId' => $bookingId,
    'redirectUrl' => route('payment.page', ['bookingId' => $bookingId])
  ]);
}
```

**Phase 4: Payment Gateway Integration**

**PayPal Flow:**
```php
// PayPalController.php

public function createTransaction(Request $request)
{
  // 1. Lấy booking info
  $booking = Booking::find($request->bookingId);
  
  // 2. Tạo order trên PayPal
  $response = Http::post('https://api-m.paypal.com/v2/checkout/orders', [
    'intent' => 'CAPTURE',
    'purchase_units' => [[
      'amount' => [
        'currency_code' => 'USD',
        'value' => $booking->totalPrice
      ]
    ]]
  ]);
  
  // 3. Redirect user tới PayPal
  return redirect($response['links'][1]['href']);
}

public function processTransaction(Request $request)
{
  // 1. Lấy Order ID từ PayPal
  $orderId = $request->token;
  
  // 2. Capture payment
  $response = Http::post(
    "https://api-m.paypal.com/v2/checkout/orders/{$orderId}/capture",
    []
  );
  
  // 3. Nếu thành công
  if ($response['status'] === 'COMPLETED') {
    // Update booking status
    $booking->update(['bookingStatus' => 'c']); // confirmed
    
    // Send email
    Mail::to($booking->user->email)->send(
      new PaymentConfirmation($booking)
    );
    
    return redirect()->route('tour-booked', ['bookingId' => $booking->id]);
  }
}
```

**Phase 5: Database Tables**
```sql
-- Booking Table
CREATE TABLE tbl_booking (
  bookingId INT PRIMARY KEY AUTO_INCREMENT,
  userId INT NOT NULL,
  tourId INT NOT NULL,
  numAdults INT,
  numChildren INT,
  totalPrice DECIMAL(10, 2),
  bookingStatus CHAR(1), -- 'p' (pending), 'c' (confirmed), 'x' (cancelled)
  created_at TIMESTAMP
);

-- Checkout Table
CREATE TABLE tbl_checkout (
  checkoutId INT PRIMARY KEY AUTO_INCREMENT,
  bookingId INT NOT NULL,
  paymentMethod VARCHAR(50), -- 'momo', 'paypal', 'cash'
  paymentStatus VARCHAR(50),
  transactionId VARCHAR(255),
  created_at TIMESTAMP,
  FOREIGN KEY (bookingId) REFERENCES tbl_booking(bookingId)
);
```

---

## 4️⃣ THUẬT NGỮ QUAN TRỌNG

### Core Concepts
| Thuật ngữ | Ý nghĩa | Ví dụ |
|-----------|---------|--------|
| **Route** | Đường dẫn URL | `/my-wishlist`, `/booking/{id}` |
| **Controller** | Xử lý logic | WishlistController, BookingController |
| **Model** | Thao tác database | Tours::addWishlist() |
| **View** | Giao diện HTML | wishlist.blade.php |
| **Middleware** | Bộ lọc request | checkLoginClient |
| **Migration** | Định nghĩa database | create_tbl_wishlists.php |

### Authentication & Authorization
| Thuật ngữ | Ý nghĩa |
|-----------|---------|
| **Session** | Lưu trữ thông tin user sau login |
| **Middleware checkLoginClient** | Kiểm tra user đã login |
| **userId** | ID duy nhất của user |
| **Authentication** | Xác định user là ai |
| **Authorization** | Xác định user có quyền gì |

### Database Concepts
| Thuật ngữ | Ý nghĩa |
|-----------|---------|
| **Foreign Key** | Liên kết giữa 2 bảng |
| **Primary Key** | Khóa chính (ID duy nhất) |
| **JOIN** | Nối dữ liệu từ 2 bảng |
| **INSERT** | Thêm dữ liệu mới |
| **UPDATE** | Sửa dữ liệu |
| **DELETE** | Xoá dữ liệu |

### API & Payment
| Thuật ngữ | Ý nghĩa |
|-----------|---------|
| **API** | Giao diện để 2 app giao tiếp |
| **Callback** | Hàm được gọi sau khi payment hoàn thành |
| **Transaction** | Giao dịch thanh toán |
| **Payment Gateway** | Cổng thanh toán (PayPal, Momo) |
| **AJAX** | Gửi request không reload page |
| **JSON** | Format dữ liệu (key-value pairs) |

### Status Codes
```
Wishlist:
- 200 OK: Thành công
- 401 Unauthorized: Chưa login
- 404 Not Found: Tour không tìm thấy

Booking:
- 'p' (pending): Chờ xác nhận
- 'c' (confirmed): Đã xác nhận
- 'x' (cancelled): Đã hủy
```

---

## 5️⃣ DEPENDENCIES & LIBRARIES

```php
// Wishlist dependencies
- Session (built-in) → Lưu user info
- Database → tbl_wishlists, tbl_tours
- JavaScript/AJAX → Toggle action

// Booking dependencies
- Session → User authentication
- Database → tbl_booking, tbl_checkout, tbl_tours
- Laravel Mail → Gửi email
- PayPal SDK → Tích hợp thanh toán
- Momo API → Tích hợp thanh toán
- Carbon → Xử lý ngày tháng
```

---

## 6️⃣ SECURITY CONSIDERATIONS

```
1. CSRF Protection
   - Form có token để chống CSRF attack
   - Route::post() tự động check CSRF token

2. Authentication
   - Middleware checkLoginClient bảo vệ route
   - Controller kiểm tra session

3. Input Validation
   - Validate số người (>= 1)
   - Validate tourId, userId
   - Validate giá tiền

4. SQL Injection Prevention
   - Dùng parameterized queries
   - Không concatenate user input vào SQL

5. Password Security
   - Dùng hashing (bcrypt)
   - Không lưu plain text password
```

---

## 7️⃣ FILE STRUCTURE RECAP

```
BookingTourDuLich-exported/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Controller.php (Base class)
│   │   │   └── clients/
│   │   │       ├── WishlistController.php
│   │   │       ├── BookingController.php
│   │   │       └── TourBookedController.php
│   │   └── Middleware/
│   │       └── CheckLoggedInClients.php
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
│   ├── migrations/ (11 files)
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
│   └── web.php
└── [Config files]
```

---

## 8️⃣ HOW TO PRESENT TO TEACHER

### Slide 1: Overview
- Giải thích 2 chức năng chính
- Kiến trúc MVC
- Các công nghệ sử dụng

### Slide 2: Wishlist Flow
- Vẽ sơ đồ: User → Controller → Model → Database
- Giải thích toggle action
- Database schema

### Slide 3: Booking Flow
- Vẽ sơ đồ chi tiết 10 steps
- Payment gateway integration
- Database schema

### Slide 4: Security & Best Practices
- Authentication/Authorization
- Input validation
- SQL injection prevention

### Slide 5: Demo
- Chạy code trực tiếp
- Giải thích từng step

---

## 9️⃣ KEY TAKEAWAYS

✅ **Wishlist System:**
- Simple AJAX toggle
- Database: userId + tourId relationship
- No payment involved

✅ **Booking System:**
- Complex flow with multiple steps
- Payment gateway integration (PayPal, Momo)
- Email notifications
- Status tracking (pending → confirmed → cancelled)

✅ **Database Design:**
- Normalization (3NF)
- Foreign keys for relationships
- Proper status fields

✅ **Code Quality:**
- MVC pattern
- Separation of concerns
- Security best practices
- Error handling
