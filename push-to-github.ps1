# PowerShell Script để đẩy code lên GitHub nhánh devel

Write-Host "========================================" -ForegroundColor Green
Write-Host "   PUSH CODE LEN GITHUB (Nhanh Devel)" -ForegroundColor Green
Write-Host "========================================" -ForegroundColor Green
Write-Host ""

# Kiểm tra xem có phải trong thư mục git không
if (-not (Test-Path .git)) {
    Write-Host "ERROR: Không tìm thấy .git folder" -ForegroundColor Red
    Write-Host "Vui lòng chạy script này từ thư mục root của project"
    Read-Host "Nhấn Enter để thoát"
    exit 1
}

# Step 1: Kiểm tra git status
Write-Host "Step 1: Kiểm tra Git Status" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
git status
Write-Host ""

# Step 2: Thêm tất cả file
Write-Host "Step 2: Thêm tất cả file" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
git add .
Write-Host "✓ OK: Đã add tất cả file" -ForegroundColor Green
Write-Host ""

# Step 3: Xem các file sẽ được commit
Write-Host "Step 3: Xem các file sẽ được commit" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
git status
Write-Host ""

# Step 4: Tạo commit
Write-Host "Step 4: Tạo Commit" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan

$commitMessage = @"
feat: Wishlist & Booking System - Danh sách yêu thích và hệ thống đặt tour

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
✅ CSRF protection
"@

git commit -m $commitMessage

if ($LASTEXITCODE -ne 0) {
    Write-Host "ERROR: Commit thất bại" -ForegroundColor Red
    Read-Host "Nhấn Enter để thoát"
    exit 1
}

Write-Host "✓ OK: Đã tạo commit" -ForegroundColor Green
Write-Host ""

# Step 5: Đẩy lên nhánh devel
Write-Host "Step 5: Đẩy code lên nhánh devel" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
git push -u origin devel

if ($LASTEXITCODE -ne 0) {
    Write-Host "ERROR: Push thất bại" -ForegroundColor Red
    Write-Host "Vui lòng kiểm tra:" -ForegroundColor Yellow
    Write-Host "1. Kết nối Internet"
    Write-Host "2. GitHub credentials"
    Write-Host "3. Quyền truy cập repository"
    Read-Host "Nhấn Enter để thoát"
    exit 1
}

Write-Host "✓ OK: Đã push thành công" -ForegroundColor Green
Write-Host ""

# Step 6: Xem log commit mới
Write-Host "Step 6: Xem commit log" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
git log --oneline -1
Write-Host ""

Write-Host "========================================" -ForegroundColor Green
Write-Host "     ✅ PUSH THÀNH CÔNG!" -ForegroundColor Green
Write-Host "========================================" -ForegroundColor Green
Write-Host ""
Write-Host "Repository: devel branch" -ForegroundColor Yellow
Write-Host "Thời gian: $(Get-Date)" -ForegroundColor Yellow
Write-Host ""

Read-Host "Nhấn Enter để thoát"
