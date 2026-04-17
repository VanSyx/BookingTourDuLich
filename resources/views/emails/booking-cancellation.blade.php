<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông báo huỷ tour</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; color: #333; }
        .container { max-width: 600px; margin: 20px auto; background-color: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .header { background: linear-gradient(135deg, #e53935, #b71c1c); color: #fff; padding: 30px 20px; text-align: center; }
        .header h1 { font-size: 22px; margin-bottom: 8px; }
        .content { padding: 30px 20px; }
        .info-box { background: #fff3f3; border-left: 4px solid #e53935; border-radius: 5px; padding: 20px; margin: 20px 0; }
        .info-row { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #f5c6c6; }
        .info-row:last-child { border-bottom: none; }
        .info-label { font-weight: bold; color: #888; width: 45%; }
        .info-value { color: #333; text-align: right; width: 55%; }
        .notice-box { background: #fff8e1; border-left: 4px solid #ffc107; border-radius: 5px; padding: 20px; margin: 20px 0; }
        .notice-box h3 { color: #856404; margin-bottom: 10px; }
        .notice-box ul { margin-left: 20px; color: #856404; }
        .notice-box li { margin-bottom: 6px; }
        .footer { background: #f9f9f9; padding: 20px; text-align: center; font-size: 12px; color: #999; border-top: 1px solid #eee; }
        .button { display: inline-block; background: #667eea; color: #fff; padding: 12px 28px; text-decoration: none; border-radius: 5px; margin: 16px 0; font-weight: bold; }
    </style>
</head>
<body>
<div class="container">
    <!-- Header -->
    <div class="header">
        <h1>❌ Thông báo huỷ đặt tour</h1>
        <p>Mã đặt tour: <strong>#{{ $bookingId }}</strong></p>
    </div>

    <!-- Content -->
    <div class="content">
        <p style="font-size:16px; margin-bottom:20px;">
            Xin chào <strong>{{ $fullName }}</strong>,
        </p>
        <p style="color:#555; margin-bottom:20px;">
            @if($cancelledBy === 'admin')
                Chúng tôi xin thông báo rằng đơn đặt tour của bạn đã được <strong>Admin Travela huỷ</strong>.
                Nếu bạn đã thanh toán, chúng tôi sẽ liên hệ để xử lý hoàn tiền trong thời gian sớm nhất.
            @else
                Đơn đặt tour của bạn đã được <strong>huỷ thành công</strong> theo yêu cầu của bạn.
            @endif
        </p>

        <!-- Tour Info -->
        <div class="info-box">
            <p style="font-weight:700; color:#e53935; margin-bottom:12px;">📋 Thông tin tour bị huỷ:</p>
            <div class="info-row">
                <span class="info-label">Tên tour:</span>
                <span class="info-value"><strong>{{ $tourTitle }}</strong></span>
            </div>
            <div class="info-row">
                <span class="info-label">Ngày khởi hành:</span>
                <span class="info-value">{{ $tourStartDate }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Ngày kết thúc:</span>
                <span class="info-value">{{ $tourEndDate }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Số khách người lớn:</span>
                <span class="info-value">{{ $numAdults }} người</span>
            </div>
            <div class="info-row">
                <span class="info-label">Số trẻ em:</span>
                <span class="info-value">{{ $numChildren }} người</span>
            </div>
            <div class="info-row">
                <span class="info-label">Tổng tiền:</span>
                <span class="info-value"><strong>{{ $totalPrice }} ₫</strong></span>
            </div>
            <div class="info-row">
                <span class="info-label">Ngày huỷ:</span>
                <span class="info-value">{{ $cancelDate }}</span>
            </div>
        </div>

        <!-- Notice -->
        <div class="notice-box">
            <h3>📌 Lưu ý:</h3>
            <ul>
                @if($cancelledBy === 'admin')
                <li>Nếu bạn đã thanh toán, chúng tôi sẽ hoàn tiền trong <strong>3–5 ngày làm việc</strong>.</li>
                <li>Vui lòng liên hệ đội ngũ hỗ trợ nếu có thắc mắc.</li>
                @else
                <li>Bạn có thể đặt lại tour khác bất cứ lúc nào.</li>
                <li>Nếu đã thanh toán, vui lòng liên hệ để yêu cầu hoàn tiền.</li>
                @endif
                <li>Cảm ơn bạn đã tin tưởng dịch vụ của <strong>Travela</strong>.</li>
            </ul>
        </div>

        <!-- CTA -->
        <center>
            <a href="{{ url('/') }}" class="button">Khám phá tour khác</a>
        </center>

        <!-- Contact -->
        <div style="margin-top:24px; text-align:center; color:#666; font-size:14px;">
            <p>Cần hỗ trợ? Liên hệ ngay với chúng tôi:</p>
            <p style="margin-top:8px;">
                📧 <strong>support@travela.vn</strong><br>
                ☎️ <strong>1800-1234</strong>
            </p>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>&copy; 2026 Travela.vn | Một phần của VietnamTravel Company</p>
        <p>Email này được gửi tới: <strong>{{ $userEmail }}</strong></p>
    </div>
</div>
</body>
</html>
