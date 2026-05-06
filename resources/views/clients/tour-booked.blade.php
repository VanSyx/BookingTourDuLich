@include('clients.blocks.header')
@include('clients.blocks.banner')

<style>
@media print {
    .no-print { display: none !important; }
    .invoice-wrap { box-shadow: none !important; }
    body { background: #fff; }
}
.invoice-page { background: #f5f7fa; min-height: 80vh; padding: 50px 0 80px; }
.invoice-wrap { max-width: 860px; margin: 0 auto; background: #fff;
  border-radius: 18px; box-shadow: 0 4px 40px rgba(0,0,0,.1); overflow: hidden; }
.invoice-header { background: linear-gradient(135deg, #1a3a5c, #0d2240); color: #fff; padding: 36px 40px; }
.invoice-header h1 { font-size: 28px; font-weight: 800; margin: 0 0 6px; letter-spacing: 1px; }
.invoice-header .sub { font-size: 14px; opacity: .8; }
.invoice-success-badge { display: inline-flex; align-items: center; gap: 8px;
  background: rgba(255,255,255,.15); border-radius: 30px; padding: 6px 16px;
  font-size: 14px; font-weight: 600; margin-bottom: 12px; }
.invoice-meta { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; margin-top: 20px; }
.invoice-meta-item { font-size: 14px; }
.invoice-meta-item .lbl { opacity: .7; font-size: 12px; text-transform: uppercase; letter-spacing: .5px; }

.invoice-body { padding: 32px 40px; }
.invoice-section { margin-bottom: 28px; }
.invoice-section h3 { font-size: 14px; font-weight: 700; text-transform: uppercase;
  letter-spacing: 1px; color: #1a3a5c; margin-bottom: 16px; padding-bottom: 8px;
  border-bottom: 2px solid #f0f0f0; }
.info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
.info-item .lbl { font-size: 12px; color: #999; margin-bottom: 2px; }
.info-item .val { font-size: 15px; font-weight: 600; color: #333; }

/* Price table */
.price-table { width: 100%; border-collapse: collapse; }
.price-table th { background: #f8f9fa; font-size: 12px; font-weight: 700; text-transform: uppercase;
  color: #888; padding: 10px 14px; text-align: left; }
.price-table td { padding: 12px 14px; border-bottom: 1px solid #f0f0f0; font-size: 15px; }
.price-table td:last-child { text-align: right; font-weight: 600; }
.price-table tfoot td { font-size: 18px; font-weight: 800; color: #d32f2f; padding-top: 16px;
  border-top: 2px solid #1a3a5c; border-bottom: none; }
.price-table .discount-row td { color: #388e3c; }

/* Status badge */
.status-badge { display: inline-block; padding: 4px 14px; border-radius: 30px;
  font-size: 13px; font-weight: 700; }
.status-badge.pending  { background: #fff8e1; color: #f57c00; }
.status-badge.confirmed { background: #e8f5e9; color: #2e7d32; }
.status-badge.finished  { background: #e3f2fd; color: #1565c0; }
.status-badge.cancelled { background: #fce4ec; color: #c62828; }

/* Payment badge */
.payment-badge { display: inline-flex; align-items: center; gap: 6px; padding: 6px 14px;
  border-radius: 8px; font-size: 13px; font-weight: 600; }
.payment-badge.paid    { background: #e8f5e9; color: #2e7d32; }
.payment-badge.unpaid  { background: #fff8e1; color: #e65100; }

/* QR section */
.qr-section { text-align: center; padding: 24px; background: linear-gradient(135deg, #fff8f0, #fff3e0);
  border-radius: 12px; border: 2px dashed #ffcc02; margin-bottom: 24px; }

/* Action buttons */
.invoice-actions { display: flex; gap: 12px; padding: 24px 40px; background: #f8f9fa;
  border-top: 1px solid #e0e0e0; }
.btn-invoice { padding: 12px 28px; border-radius: 30px; font-size: 15px;
  font-weight: 700; cursor: pointer; text-decoration: none; text-align: center; display: inline-block; }
.btn-invoice.primary { background: linear-gradient(135deg, #1a3a5c, #0d2240); color: #fff; border: none; }
.btn-invoice.outline { background: #fff; color: #1a3a5c; border: 2px solid #1a3a5c; }
.btn-invoice.danger  { background: #fff; color: #d32f2f; border: 2px solid #d32f2f; }
.btn-invoice.success-btn { background: linear-gradient(135deg, #2e7d32, #1b5e20); color: #fff; border: none; }
</style>

<div class="invoice-page">
    <div class="container">
        <div class="invoice-wrap">

            {{-- Header --}}
            <div class="invoice-header">
                <div class="invoice-success-badge">✅ Đặt tour thành công!</div>
                <h1>🧾 HÓA ĐƠN ĐẶT TOUR</h1>
                <div class="sub">Cảm ơn bạn đã chọn Travela. Chúc bạn có chuyến đi tuyệt vời!</div>
                <div class="invoice-meta">
                    <div class="invoice-meta-item">
                        <div class="lbl">Mã đặt tour</div>
                        <div style="font-size:18px;font-weight:700">#{{ $bookingId }}</div>
                    </div>
                    <div class="invoice-meta-item">
                        <div class="lbl">Ngày đặt</div>
                        <div>{{ \Carbon\Carbon::parse($tour_booked->bookingDate ?? now())->format('d/m/Y H:i') }}</div>
                    </div>
                    <div class="invoice-meta-item">
                        <div class="lbl">Trạng thái đặt tour</div>
                        <div>
                            @php
                                $statusMap = ['p' => ['pending', 'Chờ xác nhận'], 'c' => ['confirmed', 'Đã xác nhận'],
                                              'f' => ['finished', 'Hoàn thành'],  'x' => ['cancelled', 'Đã hủy']];
                                $st = $statusMap[$tour_booked->bookingStatus ?? 'p'] ?? ['pending', 'Chờ xác nhận'];
                            @endphp
                            <span class="status-badge {{ $st[0] }}">{{ $st[1] }}</span>
                        </div>
                    </div>
                    <div class="invoice-meta-item">
                        <div class="lbl">Thanh toán</div>
                        <div>
                            @php
                                $paid = ($tour_booked->paymentStatus ?? 'n') === 'y';
                            @endphp
                            <span class="payment-badge {{ $paid ? 'paid' : 'unpaid' }}">
                                {{ $paid ? '✅ Đã thanh toán' : '⏳ Chưa thanh toán' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="invoice-body">

                {{-- QR E-Ticket --}}
                <div class="qr-section">
                    <h3 style="color:#e65100;margin-bottom:12px">🎫 Vé điện tử (E-Ticket)</h3>
                    <div style="display:inline-block;background:#fff;padding:12px;border-radius:10px;box-shadow:0 2px 10px rgba(0,0,0,.1)">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=TRAVELA-{{ $bookingId }}-{{ $tour_booked->tourId }}"
                            alt="QR Code" style="display:block">
                    </div>
                    <div style="margin-top:8px;font-weight:700;color:#555">Mã: <span style="color:#d32f2f">TRAVELA-{{ $bookingId }}</span></div>
                    <div style="font-size:13px;color:#888">Xuất trình QR này cho HDV vào ngày khởi hành</div>
                </div>

                {{-- Tour Info --}}
                <div class="invoice-section">
                    <h3>🏖️ Thông tin tour</h3>
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="lbl">Tên tour</div>
                            <div class="val">{{ $tour_booked->title }}</div>
                        </div>
                        <div class="info-item">
                            <div class="lbl">Điểm đến</div>
                            <div class="val"><i class="fal fa-map-marker-alt" style="color:#1a3a5c"></i> {{ $tour_booked->destination }}</div>
                        </div>
                        <div class="info-item">
                            <div class="lbl">Ngày khởi hành</div>
                            <div class="val" style="color:#d32f2f">{{ date('d/m/Y', strtotime($tour_booked->startDate)) }}</div>
                        </div>
                        <div class="info-item">
                            <div class="lbl">Ngày kết thúc</div>
                            <div class="val">{{ date('d/m/Y', strtotime($tour_booked->endDate)) }}</div>
                        </div>
                        <div class="info-item">
                            <div class="lbl">Thời gian</div>
                            <div class="val">{{ $tour_booked->time }}</div>
                        </div>
                    </div>
                </div>

                {{-- Customer Info --}}
                <div class="invoice-section">
                    <h3>👤 Thông tin khách hàng</h3>
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="lbl">Họ tên</div>
                            <div class="val">{{ $tour_booked->fullName }}</div>
                        </div>
                        <div class="info-item">
                            <div class="lbl">Số điện thoại</div>
                            <div class="val">{{ $tour_booked->phoneNumber }}</div>
                        </div>
                        <div class="info-item">
                            <div class="lbl">Email</div>
                            <div class="val">{{ $tour_booked->email }}</div>
                        </div>
                        <div class="info-item">
                            <div class="lbl">Địa chỉ</div>
                            <div class="val">{{ $tour_booked->address ?? '—' }}</div>
                        </div>
                    </div>
                </div>

                {{-- Price Table --}}
                <div class="invoice-section">
                    <h3>💰 Chi tiết giá</h3>
                    <table class="price-table">
                        <thead>
                            <tr>
                                <th>Hạng mục</th>
                                <th>Đơn giá</th>
                                <th>Số lượng</th>
                                <th>Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Người lớn</td>
                                <td>{{ number_format($tour_booked->priceAdult, 0, ',', '.') }} VNĐ</td>
                                <td>{{ $tour_booked->numAdults }}</td>
                                <td>{{ number_format($tour_booked->numAdults * $tour_booked->priceAdult, 0, ',', '.') }} VNĐ</td>
                            </tr>
                            @if($tour_booked->numChildren > 0)
                            <tr>
                                <td>Trẻ em</td>
                                <td>{{ number_format($tour_booked->priceChild, 0, ',', '.') }} VNĐ</td>
                                <td>{{ $tour_booked->numChildren }}</td>
                                <td>{{ number_format($tour_booked->numChildren * $tour_booked->priceChild, 0, ',', '.') }} VNĐ</td>
                            </tr>
                            @endif
                            @php
                                $subtotal  = $tour_booked->numAdults * $tour_booked->priceAdult + $tour_booked->numChildren * $tour_booked->priceChild;
                                $discount  = max(0, $subtotal - $tour_booked->totalPrice);
                            @endphp
                            @if($discount > 0)
                            <tr class="discount-row">
                                <td colspan="3">Giảm giá (mã khuyến mãi)</td>
                                <td>- {{ number_format($discount, 0, ',', '.') }} VNĐ</td>
                            </tr>
                            @endif
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3"><strong>TỔNG TIỀN</strong></td>
                                <td>{{ number_format($tour_booked->totalPrice, 0, ',', '.') }} VNĐ</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                {{-- Payment Method --}}
                <div class="invoice-section">
                    <h3>💳 Phương thức thanh toán</h3>
                    @php
                        $pmLabels = ['office-payment' => '🏢 Thanh toán tại văn phòng', 'paypal-payment' => '💳 PayPal', 'momo-payment' => '💜 MoMo'];
                        $pmLabel = $pmLabels[$tour_booked->paymentMethod] ?? $tour_booked->paymentMethod;
                    @endphp
                    <div style="font-size:15px;font-weight:600">{{ $pmLabel }}</div>
                    @if($tour_booked->paymentMethod === 'office-payment')
                    <div style="margin-top:8px;padding:12px 16px;background:#fff8e1;border-radius:8px;font-size:14px;color:#e65100">
                        📍 Vui lòng đến văn phòng Travela để hoàn tất thanh toán trước ngày khởi hành.
                    </div>
                    @endif
                </div>
            </div>

            {{-- Actions --}}
            <div class="invoice-actions no-print">
                <button class="btn-invoice outline" onclick="window.print()">🖨️ In hóa đơn</button>
                @if($tour_booked->bookingStatus === 'f')
                    <a href="{{ route('tour-detail', ['id' => $tour_booked->tourId]) }}" class="btn-invoice success-btn">⭐ Đánh giá tour</a>
                @else
                    <form method="POST" action="{{ route('cancel-booking') }}" onsubmit="return confirm('Bạn có chắc muốn hủy tour này?')">
                        @csrf
                        <input type="hidden" name="bookingId" value="{{ $bookingId }}">
                        <input type="hidden" name="tourId" value="{{ $tour_booked->tourId }}">
                        <button type="submit" class="btn-invoice danger {{ $hide ?? '' }}">❌ Hủy tour</button>
                    </form>
                @endif
                <a href="{{ route('my-tours') }}" class="btn-invoice primary" style="margin-left:auto">📋 Xem lịch sử tour</a>
            </div>
        </div>
    </div>
</div>

@include('clients.blocks.footer')
