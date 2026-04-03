<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác nhận thanh toán</title>
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
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        .header h1 {
            font-size: 24px;
            margin-bottom: 10px;
        }
        .payment-status {
            font-size: 14px;
            background-color: rgba(255, 255, 255, 0.2);
            padding: 8px 15px;
            border-radius: 20px;
            display: inline-block;
        }
        .content {
            padding: 30px 20px;
        }
        .greeting {
            font-size: 16px;
            margin-bottom: 20px;
            color: #333;
        }
        .payment-info {
            background-color: #f0fdf4;
            padding: 20px;
            border-radius: 5px;
            margin: 20px 0;
            border-left: 4px solid #10b981;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #d1fae5;
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
            font-weight: 500;
        }
        .amount-box {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 25px;
            border-radius: 5px;
            text-align: center;
            margin: 25px 0;
        }
        .amount-label {
            font-size: 14px;
            margin-bottom: 8px;
        }
        .amount-value {
            font-size: 32px;
            font-weight: bold;
        }
        .booking-details {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .section-title {
            font-size: 16px;
            font-weight: bold;
            color: #10b981;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
        }
        .section-title::before {
            content: "📋";
            margin-right: 10px;
            font-size: 18px;
        }
        .payment-method-badge {
            display: inline-block;
            background-color: #e0e7ff;
            color: #4f46e5;
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: bold;
        }
        .transaction-id {
            background-color: #f3f4f6;
            padding: 12px;
            border-radius: 4px;
            font-family: 'Courier New', monospace;
            word-break: break-all;
            margin-top: 8px;
        }
        .next-steps {
            background-color: #fef3c7;
            padding: 20px;
            border-radius: 5px;
            margin: 20px 0;
            border-left: 4px solid #f59e0b;
        }
        .next-steps h3 {
            color: #92400e;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
        }
        .next-steps h3::before {
            content: "✓";
            margin-right: 10px;
            font-size: 18px;
        }
        .next-steps ul {
            margin-left: 20px;
            color: #92400e;
        }
        .next-steps li {
            margin-bottom: 8px;
            line-height: 1.6;
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
            background-color: #10b981;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
            font-weight: bold;
        }
        .button:hover {
            background-color: #059669;
            text-decoration: none;
        }
        .pending {
            background-color: #fef3c7 !important;
            border-left-color: #f59e0b !important;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>💳 Xác nhận thanh toán thành công!</h1>
            <div class="payment-status">
                Trạng thái: <strong>{{ $paymentStatus }}</strong>
            </div>
        </div>

        <!-- Content -->
        <div class="content">
            <!-- Greeting -->
            <div class="greeting">
                <p>Xin chào <strong>{{ $fullName }}</strong>,</p>
                <p>Chúng tôi đã xác nhận thanh toán của bạn cho tour <strong>{{ $tourTitle }}</strong>!</p>
            </div>

            <!-- Payment Details -->
            <div class="payment-info">
                <div class="section-title">Thông tin thanh toán</div>
                
                <div class="info-row">
                    <span class="info-label">Mã đặt tour:</span>
                    <span class="info-value">#{{ $bookingId }}</span>
                </div>
                
                <div class="info-row">
                    <span class="info-label">Phương thức:</span>
                    <span class="info-value">
                        @php
                            $methodName = 'Chưa xác định';
                            $methodBadge = '';
                            if ($paymentMethod === 'paypal-payment') {
                                $methodName = 'PayPal';
                                $methodBadge = '<span class="payment-method-badge" style="background-color: #fef3c7; color: #92400e;">🔵 PayPal</span>';
                            } elseif ($paymentMethod === 'momo-payment') {
                                $methodName = 'Ví MoMo';
                                $methodBadge = '<span class="payment-method-badge" style="background-color: #ffedd5; color: #9a3412;">🟠 MoMo</span>';
                            } elseif ($paymentMethod === 'cash' || $paymentMethod === 'office') {
                                $methodName = 'Thanh toán tại quầy';
                                $methodBadge = '<span class="payment-method-badge" style="background-color: #dbeafe; color: #1e40af;">💰 Tiền mặt</span>';
                            }
                            echo $methodBadge;
                        @endphp
                    </span>
                </div>
                
                <div class="info-row">
                    <span class="info-label">Ngày thanh toán:</span>
                    <span class="info-value">{{ $paymentDate }}</span>
                </div>
                
                @if($transactionId)
                <div class="info-row">
                    <span class="info-label">Mã giao dịch:</span>
                    <span class="info-value" style="word-break: break-all;">
                        <div class="transaction-id">{{ $transactionId }}</div>
                    </span>
                </div>
                @endif
            </div>

            <!-- Amount -->
            <div class="amount-box">
                <div class="amount-label">Số tiền thanh toán</div>
                <div class="amount-value">{{ $amount }} ₫</div>
            </div>

            <!-- Booking Details -->
            <div class="booking-details">
                <div class="section-title">Thông tin chuyến đi</div>
                
                <div class="info-row">
                    <span class="info-label">Tour:</span>
                    <span class="info-value">{{ $tourTitle }}</span>
                </div>
                
                <div class="info-row">
                    <span class="info-label">Điểm đến:</span>
                    <span class="info-value">{{ $tourDestination }}</span>
                </div>
                
                <div class="info-row">
                    <span class="info-label">Ngày khởi hành:</span>
                    <span class="info-value">{{ $tourStartDate }}</span>
                </div>
            </div>

            <!-- Next Steps -->
            <div class="next-steps">
                <h3>Các bước tiếp theo</h3>
                <ul>
                    <li>Kiểm tra email xác nhận đặt tour (nếu chưa nhận)</li>
                    <li>Đăng nhập tài khoản để xem chi tiết chuyến đi</li>
                    <li>Chuẩn bị hành lý và tài liệu cần thiết</li>
                    <li>Liên hệ với chúng tôi nếu có thắc mắc</li>
                </ul>
            </div>

            <!-- Call to Action -->
            <center>
                <a href="{{ url('/') }}" class="button">Xem chi tiết chuyến đi</a>
            </center>

            <!-- Support -->
            <div style="margin-top: 30px; text-align: center; color: #666; border-top: 1px solid #eee; padding-top: 20px;">
                <p><strong>Liên hệ hỗ trợ:</strong></p>
                <p>
                    📧 Email: <strong>payment@travela.vn</strong><br>
                    ☎️ Điện thoại: <strong>1800-1234</strong><br>
                    💬 Chat: <a href="https://travela.vn" style="color: #10b981;">travela.vn/support</a>
                </p>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>&copy; 2026 Travela.vn | Một phần của VietnamTravel Company</p>
            <p>Email này được gửi tự động. Vui lòng không trả lời email này.</p>
        </div>
    </div>
</body>
</html>
