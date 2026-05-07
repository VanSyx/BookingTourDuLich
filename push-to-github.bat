@echo off
REM Script để đẩy code BookingTourDuLich lên nhánh devel trên GitHub

echo.
echo ========================================
echo     PUSH CODE LEN GITHUB (Nhanh Devel)
echo ========================================
echo.

REM Kiểm tra xem có phải trong thư mục git không
if not exist .git (
    echo ERROR: Không tìm thấy .git folder
    echo Vui lòng chạy script này từ thư mục root của project
    pause
    exit /b 1
)

REM Hiển thị status hiện tại
echo Step 1: Kiểm tra Git Status
echo ========================================
git status
echo.

REM Thêm tất cả file mới
echo Step 2: Thêm tất cả file
echo ========================================
git add .
echo OK: Đã add tất cả file
echo.

REM Kiểm tra staged changes
echo Step 3: Xem các file sẽ được commit
echo ========================================
git status
echo.

REM Commit với message
echo Step 4: Tạo Commit
echo ========================================
git commit -m "feat: Wishlist & Booking System - Danh sách yêu thích và hệ thống đặt tour

- Thêm WishlistController: Quản lý danh sách yêu thích (thêm/xoá tour)
- Thêm BookingController: Xử lý luồng đặt tour từ A-Z
- Thêm TourBookedController: Hiển thị thông tin đặt tour đã xác nhận
- Thêm PayPalController: Tích hợp thanh toán PayPal
- Thêm Models: Tours, Booking, Checkout, User
- Thêm Mail Templates: BookingConfirmation, PaymentConfirmation
- Thêm Views: wishlist.blade.php, booking.blade.php, tour-booked.blade.php
- Thêm Middleware: CheckLoggedInClients
- Thêm Migrations: 11 file migration cho database
- Thêm Documentation: DOCUMENTATION.md, README_FEATURES.md

Features:
✅ Danh sách yêu thích với AJAX toggle
✅ Đặt tour với tính toán giá thời gian thực
✅ Tích hợp thanh toán PayPal
✅ Gửi email xác nhận
✅ Theo dõi trạng thái đặt tour
✅ Xác thực & phân quyền
✅ Input validation
✅ CSRF protection"

if %errorlevel% neq 0 (
    echo ERROR: Commit thất bại
    pause
    exit /b 1
)
echo OK: Đã tạo commit
echo.

REM Đẩy lên nhánh devel
echo Step 5: Đẩy code lên nhánh devel
echo ========================================
git push -u origin devel
if %errorlevel% neq 0 (
    echo ERROR: Push thất bại
    echo Vui lòng kiểm tra:
    echo 1. Kết nối Internet
    echo 2. GitHub credentials
    echo 3. Quyền truy cập repository
    pause
    exit /b 1
)
echo OK: Đã push thành công
echo.

REM Hiển thị log commit mới
echo Step 6: Xem commit log
echo ========================================
git log --oneline -1
echo.

echo ========================================
echo     ✅ PUSH THÀNH CÔNG!
echo ========================================
echo.
echo Repository: devel branch
echo Thời gian: %date% %time%
echo.
pause
