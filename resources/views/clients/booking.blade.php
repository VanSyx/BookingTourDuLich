@include('clients.blocks.header')
@include('clients.blocks.banner')

<style>
/* ===== BOOKING PAGE STYLES ===== */
.booking-page { background: #f5f7fa; min-height: 80vh; padding: 40px 0 80px; }
.booking-steps { display: flex; align-items: center; justify-content: center; gap: 0; margin-bottom: 36px; }
.step-item { display: flex; align-items: center; gap: 10px; color: #aaa; font-weight: 600; font-size: 14px; }
.step-item.active { color: #1a3a5c; }
.step-item.done { color: #2e7d32; }
.step-icon { width: 44px; height: 44px; border-radius: 50%; background: #e0e0e0; display: flex;
  align-items: center; justify-content: center; font-size: 20px; }
.step-item.active .step-icon { background: #1a3a5c; color: #fff; }
.step-item.done .step-icon { background: #2e7d32; color: #fff; }
.step-arrow { width: 60px; height: 2px; background: #ddd; margin: 0 8px; }

.booking-layout { display: grid; grid-template-columns: 1fr 360px; gap: 28px; align-items: start; }
@media (max-width: 900px) { .booking-layout { grid-template-columns: 1fr; } }

/* Left panel */
.booking-panel { background: #fff; border-radius: 16px; box-shadow: 0 2px 16px rgba(0,0,0,.07); padding: 32px; }
.booking-panel h4 { font-size: 16px; font-weight: 700; color: #1a3a5c; text-transform: uppercase;
  letter-spacing: 1px; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 2px solid #f0f0f0; }
.bk-form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px; }
@media (max-width: 600px) { .bk-form-row { grid-template-columns: 1fr; } }
.bk-form-group { display: flex; flex-direction: column; gap: 6px; margin-bottom: 16px; }
.bk-form-group label { font-size: 13px; font-weight: 600; color: #555; }
.bk-form-group label span.req { color: #d32f2f; }
.bk-form-group input, .bk-form-group select, .bk-form-group textarea {
  border: 1.5px solid #e0e0e0; border-radius: 8px; padding: 10px 14px;
  font-size: 15px; outline: none; transition: border-color .2s; width: 100%; }
.bk-form-group input:focus, .bk-form-group select:focus, .bk-form-group textarea:focus { border-color: #1a3a5c; }

/* Quantity selector */
.qty-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px; }
.qty-box { border: 1.5px solid #e0e0e0; border-radius: 10px; padding: 12px 16px;
  display: flex; align-items: center; justify-content: space-between; }
.qty-box .qty-label { font-size: 13px; font-weight: 600; color: #333; }
.qty-box .qty-sub { font-size: 11px; color: #999; }
.qty-controls { display: flex; align-items: center; gap: 10px; }
.qty-btn { width: 30px; height: 30px; border: 1.5px solid #ddd; border-radius: 50%;
  background: #fff; cursor: pointer; font-size: 18px; display: flex; align-items: center;
  justify-content: center; font-weight: 700; color: #555; transition: all .2s; }
.qty-btn:hover { background: #1a3a5c; color: #fff; border-color: #1a3a5c; }
.qty-val { font-size: 16px; font-weight: 700; min-width: 20px; text-align: center; }

/* Payment methods */
.payment-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; margin-bottom: 20px; }
@media (max-width: 600px) { .payment-grid { grid-template-columns: 1fr 1fr; } }
.pm-option { border: 2px solid #e0e0e0; border-radius: 10px; padding: 14px 10px;
  display: flex; flex-direction: column; align-items: center; gap: 8px; cursor: pointer;
  transition: all .2s; position: relative; }
.pm-option input[type=radio] { position: absolute; opacity: 0; }
.pm-option img { height: 36px; object-fit: contain; }
.pm-option span { font-size: 12px; font-weight: 600; color: #555; text-align: center; }
.pm-option:has(input:checked) { border-color: #1a3a5c; background: #f0f4ff; }

/* Submit btn */
.btn-submit-booking { background: linear-gradient(135deg, #d32f2f, #b71c1c); color: #fff;
  border: none; border-radius: 30px; padding: 14px 40px; font-size: 16px; font-weight: 700;
  cursor: pointer; width: 100%; margin-top: 20px; transition: all .2s; letter-spacing: .5px; }
.btn-submit-booking:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(211,47,47,.3); }

/* RIGHT: Summary sidebar */
.booking-summary-card { background: #fff; border-radius: 16px; box-shadow: 0 2px 16px rgba(0,0,0,.07);
  padding: 24px; position: sticky; top: 20px; }
.summary-tour-img { width: 100%; height: 160px; object-fit: cover; border-radius: 10px; margin-bottom: 16px; }
.summary-title { font-size: 16px; font-weight: 700; color: #1a3a5c; margin-bottom: 8px; }
.summary-row { display: flex; justify-content: space-between; align-items: center;
  padding: 8px 0; border-bottom: 1px solid #f0f0f0; font-size: 14px; }
.summary-row:last-child { border-bottom: none; }
.summary-row .label { color: #777; }
.summary-row .value { font-weight: 600; color: #333; }
.summary-total { display: flex; justify-content: space-between; align-items: center;
  padding: 14px 0 0; font-size: 18px; font-weight: 700; color: #d32f2f; margin-top: 8px; }
.coupon-row { display: flex; gap: 8px; margin: 14px 0; }
.coupon-row input { flex: 1; border: 1.5px solid #e0e0e0; border-radius: 8px;
  padding: 8px 12px; font-size: 14px; }
.coupon-row button { background: #1a3a5c; color: #fff; border: none; border-radius: 8px;
  padding: 8px 16px; font-size: 14px; font-weight: 600; cursor: pointer; }

/* Schedule info badge */
.schedule-badge { background: #e8f5e9; border: 1px solid #a5d6a7; border-radius: 10px;
  padding: 12px 16px; margin-bottom: 20px; font-size: 14px; }
.schedule-badge .sch-dates { font-weight: 700; color: #1a3a5c; font-size: 15px; }
</style>

<div class="booking-page">
    <div class="container">

        {{-- Steps --}}
        <div class="booking-steps">
            <div class="step-item active" id="step1-indicator">
                <div class="step-icon">📋</div>
                <span>Nhập thông tin</span>
            </div>
            <div class="step-arrow"></div>
            <div class="step-item" id="step2-indicator">
                <div class="step-icon">💳</div>
                <span>Thanh toán</span>
            </div>
            <div class="step-arrow"></div>
            <div class="step-item" id="step3-indicator">
                <div class="step-icon">✅</div>
                <span>Hoàn tất</span>
            </div>
        </div>

        <h2 style="text-align:center;font-size:28px;font-weight:800;color:#1a3a5c;margin-bottom:32px;">ĐẶT TOUR</h2>

        <form action="{{ route('create-booking') }}" method="post" id="bookingForm">
            @csrf
            <input type="hidden" name="tourId" value="{{ $tour->tourId }}">
            <input type="hidden" name="scheduleId" value="{{ isset($schedule) ? $schedule->scheduleId : '' }}">
            <input type="hidden" name="payment_hidden" id="payment_hidden">
            <input type="hidden" name="totalPrice" id="totalPriceInput">
            @if (!is_null($transIdMomo))
                <input type="hidden" name="transactionIdMomo" value="{{ $transIdMomo }}">
            @endif

            <div class="booking-layout">
                {{-- LEFT PANEL --}}
                <div>
                    {{-- STEP 1: Thông tin --}}
                    <div id="step1" class="booking-panel mb-4">
                        <h4>📋 Thông tin liên lạc</h4>

                        {{-- Schedule info --}}
                        @if(isset($schedule))
                        <div class="schedule-badge">
                            <div class="sch-dates">📅 {{ \Carbon\Carbon::parse($schedule->startDate)->format('d/m/Y') }} → {{ \Carbon\Carbon::parse($schedule->endDate)->format('d/m/Y') }}</div>
                            <div>Số chỗ còn lại: <strong style="color:#388e3c">{{ $schedule->quantity }}</strong></div>
                            @if($schedule->note)
                                <div style="color:#e65100;margin-top:4px">ℹ️ {{ $schedule->note }}</div>
                            @endif
                        </div>
                        @endif

                        <div class="bk-form-row">
                            <div class="bk-form-group">
                                <label>Họ tên <span class="req">*</span></label>
                                <input type="text" name="fullName" id="fullName" placeholder="Nguyễn Văn A" required>
                            </div>
                            <div class="bk-form-group">
                                <label>Số điện thoại <span class="req">*</span></label>
                                <input type="tel" name="tel" id="tel" placeholder="0912345678" required>
                            </div>
                        </div>
                        <div class="bk-form-row">
                            <div class="bk-form-group">
                                <label>Email <span class="req">*</span></label>
                                <input type="email" name="email" id="email" placeholder="email@gmail.com" required>
                            </div>
                            <div class="bk-form-group">
                                <label>Địa chỉ</label>
                                <input type="text" name="address" id="address" placeholder="TP. Hồ Chí Minh">
                            </div>
                        </div>

                        <h4 style="margin-top:24px;">👥 Số hành khách</h4>
                        <div class="qty-row">
                            <div class="qty-box">
                                <div>
                                    <div class="qty-label">Người lớn</div>
                                    <div class="qty-sub">Từ 12 tuổi trở lên</div>
                                </div>
                                <div class="qty-controls">
                                    <button type="button" class="qty-btn" onclick="changeQty('adults', -1)">−</button>
                                    <span class="qty-val" id="qtyAdults">1</span>
                                    <button type="button" class="qty-btn" onclick="changeQty('adults', 1)">+</button>
                                </div>
                            </div>
                            <div class="qty-box">
                                <div>
                                    <div class="qty-label">Trẻ em</div>
                                    <div class="qty-sub">Từ 2 đến 11 tuổi</div>
                                </div>
                                <div class="qty-controls">
                                    <button type="button" class="qty-btn" onclick="changeQty('children', -1)">−</button>
                                    <span class="qty-val" id="qtyChildren">0</span>
                                    <button type="button" class="qty-btn" onclick="changeQty('children', 1)">+</button>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="numAdults"   id="numAdults"   value="1"
                            data-price-adults="{{ isset($schedule) ? $schedule->priceAdult : $tour->priceAdult }}">
                        <input type="hidden" name="numChildren" id="numChildren" value="0"
                            data-price-children="{{ isset($schedule) ? $schedule->priceChild : $tour->priceChild }}">

                        <div class="bk-form-group" style="margin-top:16px">
                            <label>Ghi chú</label>
                            <textarea name="note" rows="3" placeholder="Quý khách có ghi chú lưu ý gì, hãy nói với chúng tôi..."></textarea>
                        </div>

                        <div style="display:flex;align-items:center;gap:10px;margin-bottom:8px">
                            <input type="checkbox" id="agree" name="agree" required>
                            <label for="agree" style="margin:0;font-size:14px">Tôi đã đọc và đồng ý với
                                <a href="#" style="color:#1a3a5c">Điều khoản thanh toán</a></label>
                        </div>

                        <button type="button" class="btn-submit-booking" onclick="goToStep2()">
                            Tiếp tục → Chọn thanh toán
                        </button>
                    </div>

                    {{-- STEP 2: Thanh toán --}}
                    <div id="step2" style="display:none">
                        <div class="booking-panel mb-4">
                            <h4>💳 Phương thức thanh toán</h4>
                            <div class="payment-grid">
                                <label class="pm-option">
                                    <input type="radio" name="payment" value="office-payment" checked>
                                    <img src="{{ asset('clients/assets/images/contact/icon.png') }}" alt="">
                                    <span>Tại văn phòng</span>
                                </label>
                                <label class="pm-option">
                                    <input type="radio" name="payment" value="paypal-payment">
                                    <img src="{{ asset('clients/assets/images/booking/cong-thanh-toan-paypal.jpg') }}" alt="PayPal">
                                    <span>PayPal</span>
                                </label>
                                <label class="pm-option">
                                    <input type="radio" name="payment" value="momo-payment">
                                    <img src="{{ asset('clients/assets/images/booking/thanh-toan-momo.jpg') }}" alt="MoMo">
                                    <span>MoMo</span>
                                </label>
                            </div>

                            <div id="paypal-button-container" style="display:none"></div>
                            <button id="btn-momo-payment" class="btn-submit-booking" style="display:none;background:linear-gradient(135deg,#ae2070,#8e1060)"
                                type="button" data-urlmomo="{{ route('createMomoPayment') }}">
                                Thanh toán với MoMo
                                <img src="{{ asset('clients/assets/images/booking/icon-thanh-toan-momo.png') }}" style="height:28px;vertical-align:middle;margin-left:8px">
                            </button>

                            <div style="display:flex;gap:12px;margin-top:16px">
                                <button type="button" onclick="goToStep1()" style="flex:1;padding:12px;border:2px solid #ddd;border-radius:30px;background:#fff;font-weight:700;cursor:pointer">
                                    ← Quay lại
                                </button>
                                <button type="submit" class="btn-submit-booking" id="btnConfirm" style="flex:2;margin-top:0">
                                    Xác nhận đặt tour
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- RIGHT: Summary --}}
                <div>
                    <div class="booking-summary-card">
                        <img src="{{ asset('admin/assets/images/gallery-tours/' . ($tour->images[0] ?? 'cau-vang-da-nang_1775281412.png')) }}"
                            class="summary-tour-img" alt="{{ $tour->title }}">

                        <div class="summary-title">{{ $tour->title }}</div>
                        <div style="color:#888;font-size:13px;margin-bottom:14px">
                            <i class="fal fa-map-marker-alt"></i> {{ $tour->destination }}
                        </div>

                        <div class="summary-row">
                            <span class="label">Ngày khởi hành</span>
                            <span class="value">
                                @if(isset($schedule))
                                    {{ \Carbon\Carbon::parse($schedule->startDate)->format('d/m/Y') }}
                                @else
                                    {{ date('d/m/Y', strtotime($tour->startDate)) }}
                                @endif
                            </span>
                        </div>
                        <div class="summary-row">
                            <span class="label">Ngày kết thúc</span>
                            <span class="value">
                                @if(isset($schedule))
                                    {{ \Carbon\Carbon::parse($schedule->endDate)->format('d/m/Y') }}
                                @else
                                    {{ date('d/m/Y', strtotime($tour->endDate)) }}
                                @endif
                            </span>
                        </div>
                        <div class="summary-row">
                            <span class="label">Thời gian</span>
                            <span class="value">{{ $tour->time }}</span>
                        </div>
                        <div class="summary-row">
                            <span class="label">Số chỗ còn lại</span>
                            <span class="value" style="color:#388e3c">
                                {{ isset($schedule) ? $schedule->quantity : $tour->quantity }}
                            </span>
                        </div>

                        <hr style="margin:14px 0">
                        <div class="summary-row">
                            <span class="label">Người lớn (<span id="summaryAdults">1</span>)</span>
                            <span class="value" id="summaryAdultsPrice">0 VNĐ</span>
                        </div>
                        <div class="summary-row">
                            <span class="label">Trẻ em (<span id="summaryChildren">0</span>)</span>
                            <span class="value" id="summaryChildrenPrice">0 VNĐ</span>
                        </div>

                        <div class="coupon-row">
                            <input type="text" id="couponCode" name="coupon_code" placeholder="Mã giảm giá">
                            <button type="button" onclick="applyCoupon()">Áp dụng</button>
                        </div>
                        <div id="couponMsg" style="font-size:13px;margin-bottom:8px;color:#d32f2f;display:none"></div>

                        <div class="summary-row" id="discountRow" style="display:none">
                            <span class="label">Giảm giá</span>
                            <span class="value" id="summaryDiscount" style="color:#388e3c">0 VNĐ</span>
                        </div>

                        <div class="summary-total">
                            <span>Tổng tiền</span>
                            <span id="summaryTotal">0 VNĐ</span>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
var PRICE_ADULT   = {{ isset($schedule) ? $schedule->priceAdult : $tour->priceAdult }};
var PRICE_CHILD   = {{ isset($schedule) ? $schedule->priceChild : $tour->priceChild }};
var numAdults     = 1;
var numChildren   = 0;
var discountAmt   = 0;

function fmtMoney(n) { return Number(n).toLocaleString('vi-VN') + ' VNĐ'; }

function updateSummary() {
    var subAdult   = numAdults * PRICE_ADULT;
    var subChild   = numChildren * PRICE_CHILD;
    var total      = Math.max(0, subAdult + subChild - discountAmt);

    document.getElementById('summaryAdults').textContent        = numAdults;
    document.getElementById('summaryChildren').textContent      = numChildren;
    document.getElementById('summaryAdultsPrice').textContent   = fmtMoney(subAdult);
    document.getElementById('summaryChildrenPrice').textContent = fmtMoney(subChild);
    document.getElementById('summaryTotal').textContent         = fmtMoney(total);
    document.getElementById('totalPriceInput').value            = total;

    if (discountAmt > 0) {
        document.getElementById('discountRow').style.display = '';
        document.getElementById('summaryDiscount').textContent = '- ' + fmtMoney(discountAmt);
    }
}

function changeQty(type, delta) {
    if (type === 'adults') {
        numAdults = Math.max(1, numAdults + delta);
        document.getElementById('qtyAdults').textContent = numAdults;
        document.getElementById('numAdults').value = numAdults;
    } else {
        numChildren = Math.max(0, numChildren + delta);
        document.getElementById('qtyChildren').textContent = numChildren;
        document.getElementById('numChildren').value = numChildren;
    }
    updateSummary();
}

function applyCoupon() {
    var code = document.getElementById('couponCode').value.trim();
    if (!code) return;
    fetch('/validate-booking', {
        method: 'POST',
        headers: {'Content-Type':'application/json','X-CSRF-TOKEN': '{{ csrf_token() }}'},
        body: JSON.stringify({coupon_code: code, numAdults, numChildren, tourId: {{ $tour->tourId }} })
    }).then(r => r.json()).then(data => {
        var msg = document.getElementById('couponMsg');
        if (data.success) {
            msg.style.color = '#388e3c';
            msg.textContent = '✅ Mã hợp lệ!';
        } else {
            msg.style.color = '#d32f2f';
            msg.textContent = '❌ ' + (data.message || 'Mã không hợp lệ');
        }
        msg.style.display = '';
    });
}

function goToStep2() {
    var fullName = document.getElementById('fullName').value.trim();
    var tel      = document.getElementById('tel').value.trim();
    var email    = document.getElementById('email').value.trim();
    var agree    = document.getElementById('agree').checked;

    if (!fullName || !tel || !email) { alert('Vui lòng điền đầy đủ thông tin liên lạc!'); return; }
    if (!agree) { alert('Vui lòng đồng ý với điều khoản thanh toán!'); return; }

    document.getElementById('step1').style.display = 'none';
    document.getElementById('step2').style.display = '';
    document.getElementById('step1-indicator').classList.remove('active'); document.getElementById('step1-indicator').classList.add('done');
    document.getElementById('step2-indicator').classList.add('active');
    window.scrollTo(0, 200);
}

function goToStep1() {
    document.getElementById('step2').style.display = 'none';
    document.getElementById('step1').style.display = '';
    document.getElementById('step2-indicator').classList.remove('active');
    document.getElementById('step1-indicator').classList.remove('done'); document.getElementById('step1-indicator').classList.add('active');
    window.scrollTo(0, 200);
}

// Payment method toggle
document.querySelectorAll('input[name="payment"]').forEach(function(radio) {
    radio.addEventListener('change', function() {
        document.getElementById('payment_hidden').value = this.value;
        document.getElementById('paypal-button-container').style.display =
            (this.value === 'paypal-payment') ? '' : 'none';
        document.getElementById('btn-momo-payment').style.display =
            (this.value === 'momo-payment') ? '' : 'none';
        document.getElementById('btnConfirm').style.display =
            (this.value === 'paypal-payment' || this.value === 'momo-payment') ? 'none' : '';
    });
});
document.getElementById('payment_hidden').value = 'office-payment';

// Form submit via AJAX
document.getElementById('bookingForm').addEventListener('submit', function(e) {
    e.preventDefault();
    var btn = document.getElementById('btnConfirm');
    btn.disabled = true; btn.textContent = 'Đang xử lý...';

    fetch('/create-booking', {
        method: 'POST',
        body: new FormData(this),
        headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
    }).then(r => r.json()).then(data => {
        if (data.success) {
            window.location.href = data.redirectUrl;
        } else {
            alert(data.message || 'Có lỗi xảy ra. Vui lòng thử lại.');
            btn.disabled = false; btn.textContent = 'Xác nhận đặt tour';
        }
    }).catch(() => {
        alert('Lỗi kết nối!'); btn.disabled = false; btn.textContent = 'Xác nhận đặt tour';
    });
});

// MoMo button
var momoBtn = document.getElementById('btn-momo-payment');
if (momoBtn) {
    momoBtn.addEventListener('click', function() {
        var urlMomo = this.dataset.urlmomo;
        var amount  = parseInt(document.getElementById('totalPriceInput').value) || 1000;
        fetch(urlMomo, {
            method: 'POST',
            headers: {'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},
            body: JSON.stringify({ tourId: {{ $tour->tourId }}, amount,
                fullName: document.getElementById('fullName').value,
                email:    document.getElementById('email').value,
                tel:      document.getElementById('tel').value,
                address:  document.getElementById('address').value,
                numAdults, numChildren, totalPrice: amount, payment_hidden: 'momo-payment' })
        }).then(r => r.json()).then(data => {
            if (data.payUrl) { window.location.href = data.payUrl; }
            else { alert('Lỗi kết nối MoMo: ' + (data.error || 'Unknown error')); }
        });
    });
}

// Init
updateSummary();
</script>

{{-- MoMo callback auto-submit --}}
@if(!empty($transIdMomo))
<script>
window.addEventListener('load', function() {
    var formData = new FormData();
    formData.append('payment_hidden', 'momo-payment');
    @if(!empty($momoBookingData))
    var momoData = @json($momoBookingData);
    for (var key in momoData) {
        if (momoData.hasOwnProperty(key)) {
            formData.append(key, momoData[key]);
        }
    }
    @endif
    formData.append('transactionIdMomo', '{{ $transIdMomo }}');

    fetch('/create-booking', {
        method: 'POST',
        body: formData,
        headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
    }).then(r => r.json()).then(data => {
        if (data.success) {
            window.location.href = data.redirectUrl;
        } else {
            alert('Lỗi tạo đơn hàng: ' + (data.message || 'Không xác định'));
        }
    });
});
</script>
@endif

{{-- PayPal SDK --}}
@if (!empty($paypalClientId))
<script src="https://www.paypal.com/sdk/js?client-id={{ $paypalClientId }}&currency=USD"></script>
@endif

@include('clients.blocks.footer')
