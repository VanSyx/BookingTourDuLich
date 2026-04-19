@include('clients.blocks.header')
@include('clients.blocks.banner')

<section class="container" style="margin-top:50px; margin-bottom: 100px">
    {{-- <h1 class="text-center booking-header">Tổng Quan Về Chuyến Đi</h1> --}}

    <form action="{{ route('create-booking') }}" method="post" class="booking-container">
        @csrf
        <!-- Contact Information -->
        <div class="booking-info">
            <h2 class="booking-header">Thông Tin Liên Lạc</h2>
            <div class="booking__infor">
                <div class="form-group">
                    <label for="username">Họ và tên*</label>
                    <input type="text" id="username" placeholder="Nhập Họ và tên" name="fullName" required>
                    <span class="error-message" id="usernameError"></span>
                </div>

                <div class="form-group">
                    <label for="email">Email*</label>
                    <input type="email" id="email" placeholder="sample@gmail.com" name="email" required>
                    <span class="error-message" id="emailError"></span>
                </div>

                <div class="form-group">
                    <label for="tel">Số điện thoại*</label>
                    <input type="number" id="tel" placeholder="Nhập số điện thoại liên hệ" name="tel"
                        required>
                    <span class="error-message" id="telError"></span>
                </div>

                <div class="form-group">
                    <label for="address">Địa chỉ*</label>
                    <input type="text" id="address" placeholder="Nhập địa chỉ liên hệ" name="address" required>
                    <span class="error-message" id="addressError"></span>
                </div>

            </div>


            <!-- Passenger Details -->
            <h2 class="booking-header">Hành Khách</h2>

            <div class="booking__quantity">
                <div class="form-group quantity-selector">
                    <label>Người lớn</label>
                    <div class="input__quanlity">
                        <button type="button" class="quantity-btn">-</button>
                        <input type="number" class="quantity-input" value="1" min="1" id="numAdults"
                            name="numAdults" data-price-adults="{{ $tour->priceAdult }}" readonly>
                        <button type="button" class="quantity-btn">+</button>
                    </div>
                </div>

                <div class="form-group quantity-selector">
                    <label>Trẻ em</label>
                    <div class="input__quanlity">
                        <button type="button" class="quantity-btn">-</button>
                        <input type="number" class="quantity-input" value="0" min="0" id="numChildren"
                            name="numChildren" data-price-children="{{ $tour->priceChild }}" readonly>
                        <button type="button" class="quantity-btn">+</button>
                    </div>
                </div>
            </div>
            <!-- Privacy Agreement Section -->
            <div class="privacy-section">
                <p>Bằng cách nhấp chuột vào nút "ĐỒNG Ý" dưới đây, Khách hàng đồng ý rằng các điều kiện điều khoản
                    này sẽ được áp dụng. Vui lòng đọc kỹ điều kiện điều khoản trước khi lựa chọn sử dụng dịch vụ của
                    Travela.</p>
                <div class="privacy-checkbox">
                    <input type="checkbox" id="agree" name="agree" required>
                    <label for="agree">Tôi đã đọc và đồng ý với <a href="#" target="_blank">Điều khoản thanh
                            toán</a></label>
                </div>
            </div>
            <!-- Payment Method -->
            <h2 class="booking-header">Phương Thức Thanh Toán</h2>

            <label class="payment-option">
                <input type="radio" name="payment" value="office-payment" required>
                <img src="{{ asset('clients/assets/images/contact/icon.png') }}" alt="Office Payment">
                Thanh toán tại văn phòng
            </label>

            <label class="payment-option">
                <input type="radio" name="payment" value="paypal-payment" required>
                <img src="{{ asset('clients/assets/images/booking/cong-thanh-toan-paypal.jpg') }}" alt="PayPal">
                Thanh toán bằng PayPal
            </label>

            <label class="payment-option">
                <input type="radio" name="payment" value="momo-payment" required>
                <img src="{{ asset('clients/assets/images/booking/thanh-toan-momo.jpg') }}" alt="MoMo">
                Thanh toán bằng Momo
                @if (!is_null($transIdMomo))
                    <input type="hidden" name="transactionIdMomo" value="{{ $transIdMomo }}">
                @endif
            </label>

            <input type="hidden" name="payment_hidden" id="payment_hidden">
        </div>

        <!-- Order Summary -->
        <div class="booking-summary">
            <div class="summary-section">
                <div>
                    <p>Mã tour : {{ $tour->tourId }}</p>
                    <input type="hidden" name="tourId" id="tourId" value="{{ $tour->tourId }}">
                    <h5 class="widget-title">{{ $tour->title }}</h5>
                    <p>Ngày khởi hành : {{ date('d-m-Y', strtotime($tour->startDate)) }}</p>
                    <p>Ngày kết thúc : {{ date('d-m-Y', strtotime($tour->endDate)) }}</p>
                    <p class="quantityAvailable">Số chỗ còn nhận : {{ $tour->quantity }}</p>
                </div>

                <div class="order-summary">
                    <div class="summary-item">
                        <span>Người lớn:</span>
                        <div>
                            <span class="quantity__adults">1</span>
                            <span>X</span>
                            <span class="total-price">0 VNĐ</span>
                        </div>
                    </div>
                    <div class="summary-item">
                        <span>Trẻ em:</span>
                        <div>
                            <span class="quantity__children">0</span>
                            <span>X</span>
                            <span class="total-price">0 VNĐ</span>
                        </div>
                    </div>
                    <div class="summary-item">
                        <span>Giảm giá:</span>
                        <div>
                            <span class="total-price">0 VNĐ</span>
                        </div>
                    </div>
                    <div class="summary-item total-price">
                        <span>Tổng cộng:</span>
                        <span>0 VNĐ</span>
                        <input type="hidden" class="totalPrice" name="totalPrice" value="">
                    </div>
                </div>
                <div class="order-coupon">
                    <input type="text" placeholder="Mã giảm giá" style="width: 65%;">
                    <button style="width: 30%" class="booking-btn btn-coupon">Áp dụng</button>
                </div>

                <div id="paypal-button-container"></div>

                <button type="submit" class="booking-btn btn-submit-booking">Xác Nhận</button>

                <button id="btn-momo-payment" class="booking-btn" style="display: none;"
                    data-urlmomo = "{{ route('createMomoPayment') }}">Thanh toán với Momo <img src="{{ asset('clients/assets/images/booking/icon-thanh-toan-momo.png') }}" alt="" style="width: 10%"></button>

            </div>
        </div>
    </form>
</section>

{{-- MoMo callback: nếu có transIdMomo thì inject booking data và tự submit --}}
@if(!empty($transIdMomo))
<script>
    function initializeMomoCallback() {
        if (typeof jQuery === 'undefined') {
            setTimeout(initializeMomoCallback, 50);
            return;
        }
        
        var $ = jQuery;
        $(document).ready(function() {
            var $form = $(".booking-container");


        // Inject booking data từ PHP session vào form (không phụ thuộc localStorage)
        @if(!empty($momoBookingData))
        var momoData = @json($momoBookingData);
        $.each(momoData, function(key, val) {
            $form.find('[name="' + key + '"]').val(val);
        });
        // Đảm bảo payment_hidden đúng
        $form.find('[name="payment_hidden"]').val('momo-payment');
        @endif

        // Thêm transactionId MoMo
        $form.append($('<input>', { type: 'hidden', name: 'transactionIdMomo', value: '{{ $transIdMomo }}' }));
        // Đảm bảo _token có
        if ($form.find('[name="_token"]').length === 0) {
            $form.append($('<input>', { type: 'hidden', name: '_token', value: '{{ csrf_token() }}' }));
        }

        // Hiển thị overlay thông báo thành công
        $('body').append(
            '<div id="momo-overlay" style="position:fixed;top:0;left:0;width:100%;height:100%;' +
            'background:rgba(0,0,0,0.65);z-index:99999;display:flex;align-items:center;justify-content:center;">' +
            '<div style="background:#fff;border-radius:14px;padding:40px 36px;text-align:center;' +
            'max-width:440px;width:90%;box-shadow:0 10px 40px rgba(0,0,0,0.25);">' +
            '<div style="font-size:60px;margin-bottom:8px;">&#127881;</div>' +
            '<h3 style="color:#ae2070;margin:0 0 10px;font-size:22px;">Thanh toán MoMo thành công!</h3>' +
            '<p style="color:#555;margin-bottom:20px;line-height:1.6;">Giao dịch của bạn đã được xác nhận.<br>Hệ thống đang hoàn tất đặt tour cho bạn...</p>' +
            '<div id="momo-status" style="background:#f0f9f0;border:1px solid #c3e6c3;border-radius:8px;padding:12px;color:#2e7d32;font-weight:600;">' +
            '<i class="fa fa-spinner fa-spin"></i>&nbsp; Đang xử lý đặt tour...</div>' +
            '</div></div>'
        );

        // Tự động submit sau 1 giây
        setTimeout(function() {
            var actionUrl = $form.attr("action");
            $.ajax({
                url: actionUrl,
                method: "POST",
                data: $form.serialize(),
                success: function(response) {
                    if (response.success) {
                        $('#momo-status').html('<i class="fa fa-check-circle"></i>&nbsp; Đặt tour thành công! Đang chuyển hướng...')
                            .css({'background':'#e8f5e9','border-color':'#a5d6a7','color':'#1b5e20'});
                        // Xóa session MoMo
                        session_clear: true;
                        setTimeout(function() {
                            window.location.href = response.redirectUrl;
                        }, 1000);
                    } else {
                        $('#momo-overlay').remove();
                        if (typeof toastr !== 'undefined') {
                            toastr.error(response.message || 'Đặt tour không thành công. Vui lòng liên hệ hỗ trợ.');
                        } else {
                            alert(response.message || 'Đặt tour không thành công. Vui lòng liên hệ hỗ trợ.');
                        }
                    }
                },
                error: function(xhr) {
                    $('#momo-overlay').remove();
                    var msg = xhr.responseJSON && xhr.responseJSON.message
                              ? xhr.responseJSON.message
                              : 'Có lỗi xảy ra. Vui lòng liên hệ hỗ trợ: 1800-1234.';
                    if (typeof toastr !== 'undefined') {
                        toastr.error(msg);
                    } else {
                        alert(msg);
                    }
                }
            });
        }, 1000);
    });
    }
    initializeMomoCallback();
</script>
@endif

<!-- ✅ NEW: PayPal SDK Script -->
@if (!empty($paypalClientId))
<script src="https://www.paypal.com/sdk/js?client-id={{ $paypalClientId }}&currency=USD"></script>
<script>
    console.log("✅ PayPal SDK loaded with Client ID");
</script>
@else
<script>
    console.warn("⚠️ PayPal Client ID not configured. PayPal payment will not work.");
</script>
@endif

@include('clients.blocks.footer')
