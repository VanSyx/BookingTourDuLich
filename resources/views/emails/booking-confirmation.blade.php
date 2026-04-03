<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác nhận đặt tour</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        .header h1 {
            font-size: 24px;
            margin-bottom: 10px;
        }
        .content {
            padding: 30px 20px;
        }
        .greeting {
            font-size: 16px;
            margin-bottom: 20px;
            color: #333;
        }
        .booking-info {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 5px;
            margin: 20px 0;
            border-left: 4px solid #667eea;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }
        .info-row:last-child {
            border-bottom: none;
        }
        .info-label {
            font-weight: bold;
            color: #666;
            width: 40%;
        }
        .info-value {
            color: #333;
            text-align: right;
            width: 60%;
        }
        .tour-details {
            background-color: #f0f4ff;
            padding: 20px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .tour-title {
            font-size: 18px;
            font-weight: bold;
            color: #667eea;
            margin-bottom: 15px;
        }
        .price-box {
            background-color: #667eea;
            color: white;
            padding: 20px;
            border-radius: 5px;
            text-align: center;
            margin: 20px 0;
            font-size: 18px;
        }
        .price-amount {
            font-size: 28px;
            font-weight: bold;
        }
        .next-steps {
            margin: 20px 0;
            padding: 20px;
            background-color: #fff3cd;
            border-radius: 5px;
            border-left: 4px solid #ffc107;
        }
        .next-steps h3 {
            color: #856404;
            margin-bottom: 10px;
        }
        .next-steps ul {
            margin-left: 20px;
            color: #856404;
        }
        .next-steps li {
            margin-bottom: 8px;
        }
        .footer {
            background-color: #f9f9f9;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #999;
            border-top: 1px solid #eee;
        }
        .button {
            display: inline-block;
            background-color: #667eea;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
            font-weight: bold;
        }
        .button:hover {
            background-color: #5568d3;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>✅ Xác nhận đặt tour thành công!</h1>
            <p>Mã đặt tour: <strong>#{{ $bookingId }}</strong></p>
        </div>

        <!-- Content -->
        <div class="content">
            <!-- Greeting -->
            <div class="greeting">
                <p>Xin chào <strong>{{ $fullName }}</strong>,</p>
                <p>Cảm ơn bạn đã tin tưởng Travela. Chúng tôi đã nhận được yêu cầu đặt tour của bạn!</p>
            </div>

            <!-- Tour Details -->
            <div class="tour-details">
                <div class="tour-title">📍 Chi tiết tour:</div>
                <div class="info-row">
                    <span class="info-label">Tên tour:</span>
                    <span class="info-value"><strong>{{ $tourTitle }}</strong></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Điểm đến:</span>
                    <span class="info-value">{{ $tourDestination }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Ngày khởi hành:</span>
                    <span class="info-value">{{ $tourStartDate }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Ngày kết thúc:</span>
                    <span class="info-value">{{ $tourEndDate }}</span>
                </div>
            </div>

            <!-- Booking Info -->
            <div class="booking-info">
                <div class="tour-title">📋 Thông tin đặt tour:</div>
                <div class="info-row">
                    <span class="info-label">Khách người lớn:</span>
                    <span class="info-value">{{ $numAdults }} người</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Trẻ em:</span>
                    <span class="info-value">{{ $numChildren }} người</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Ngày đặt:</span>
                    <span class="info-value">{{ $bookingDate }}</span>
                </div>
            </div>

            <!-- Price -->
            <div class="price-box">
                <div class="price-label">Tổng giá tiền:</div>
                <div class="price-amount">{{ $totalPrice }} ₫</div>
            </div>

            <!-- Next Steps -->
            <div class="next-steps">
                <h3>📌 Các bước tiếp theo:</h3>
                <ul>
                    <li><strong>Kiểm tra trang cá nhân:</strong> Đăng nhập vào tài khoản để xem chi tiết đặt tour</li>
                    <li><strong>Xác nhận thanh toán:</strong> Chuyển tiền theo hình thức bạn chọn</li>
                    <li><strong>Chờ xác nhận:</strong> Đội ngũ Travela sẽ liên hệ xác nhận trong 24 giờ</li>
                    <li><strong>Chuẩn bị cho chuyến du lịch:</strong> Kiểm tra hành lý và thông tin liên hệ</li>
                </ul>
            </div>

            <!-- Call to Action -->
            <center>
                <a href="{{ url('/') }}" class="button">Xem chi tiết đặt tour</a>
            </center>

            <!-- Support -->
            <div style="margin-top: 30px; text-align: center; color: #666;">
                <p>Nếu bạn có bất kỳ câu hỏi nào, vui lòng liên hệ với chúng tôi:</p>
                <p>
                    📧 Email: <strong>support@travela.vn</strong><br>
                    ☎️ Điện thoại: <strong>1800-1234</strong><br>
                    💬 Chat: <a href="https://travela.vn" style="color: #667eea;">travela.vn/chat</a>
                </p>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>&copy; 2026 Travela.vn | Một phần của VietnamTravel Company</p>
            <p>Email này được gửi tới: <strong>{{ $userEmail ?? 'your-email@example.com' }}</strong></p>
            <p>Bạn nhận được email này vì là khách hàng của Travela.</p>
        </div>
    </div>
</body>
</html>
