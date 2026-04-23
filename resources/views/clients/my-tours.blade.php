@include('clients.blocks.header')
@include('clients.blocks.banner')

<style>
.my-tours-page { background: #f5f7fa; min-height: 80vh; padding: 50px 0 80px; }
.mt-booking-card {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 2px 16px rgba(0,0,0,.07);
    margin-bottom: 24px;
    display: flex;
    overflow: hidden;
    transition: transform .2s, box-shadow .2s;
}
.mt-booking-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 32px rgba(0,0,0,.13);
}
.mt-card-img {
    width: 200px;
    min-width: 200px;
    position: relative;
    overflow: hidden;
}
.mt-card-img img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}
.mt-badge {
    position: absolute;
    top: 12px; left: 12px;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 700;
    color: #fff;
    z-index: 2;
}
.mt-badge.pending   { background: #ff9800; }
.mt-badge.waiting   { background: #1a3a5c; }
.mt-badge.confirmed { background: #e91e63; }
.mt-badge.finished  { background: #4caf50; }
.mt-badge.cancelled { background: #f44336; }

.mt-card-body {
    flex: 1;
    padding: 20px 24px;
    display: flex;
    flex-direction: column;
    gap: 8px;
}
.mt-card-title { font-size: 18px; font-weight: 700; color: #1a3a5c; text-decoration: none; }
.mt-card-title:hover { color: #d32f2f; }
.mt-card-meta { display: flex; flex-wrap: wrap; gap: 16px; font-size: 13px; color: #777; }
.mt-card-meta span i { margin-right: 4px; color: #1a3a5c; }
.mt-card-desc { font-size: 14px; color: #555; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }

.mt-card-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-top: auto;
    padding-top: 12px;
    border-top: 1px solid #f0f0f0;
    flex-wrap: wrap;
    gap: 8px;
}
.mt-price { font-size: 18px; font-weight: 800; color: #d32f2f; }
.mt-booking-id { font-size: 12px; color: #aaa; }
.mt-actions { display: flex; gap: 8px; flex-wrap: wrap; }

.mt-btn {
    padding: 8px 18px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 600;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    border: none;
    cursor: pointer;
    transition: all .2s;
}
.mt-btn.primary   { background: #1a3a5c; color: #fff; }
.mt-btn.primary:hover { background: #0d2240; color: #fff; }
.mt-btn.danger    { background: #fff; color: #d32f2f; border: 2px solid #d32f2f; }
.mt-btn.danger:hover  { background: #d32f2f; color: #fff; }
.mt-btn.success   { background: #2e7d32; color: #fff; }
.mt-btn.success:hover { background: #1b5e20; color: #fff; }

.mt-payment-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 3px 10px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 600;
}
.mt-payment-badge.paid   { background: #e8f5e9; color: #2e7d32; }
.mt-payment-badge.unpaid { background: #fff8e1; color: #e65100; }

.mt-empty {
    text-align: center;
    padding: 80px 20px;
    color: #999;
}
.mt-empty i { font-size: 60px; color: #ddd; display: block; margin-bottom: 16px; }
.mt-empty h4 { color: #555; margin-bottom: 8px; }

@media (max-width: 768px) {
    .mt-booking-card { flex-direction: column; }
    .mt-card-img { width: 100%; min-width: unset; height: 180px; }
}
</style>

<section class="my-tours-page">
    <div class="container">
        <div class="row">
            {{-- Sidebar --}}
            <div class="col-lg-3 col-md-4 mb-4">
                <div class="shop-sidebar">
                    @if (!$toursPopular->isEmpty())
                        <div class="widget widget-tour" style="background:#fff;border-radius:12px;padding:20px;box-shadow:0 2px 12px rgba(0,0,0,.06);">
                            <h6 class="widget-title" style="font-size:15px;font-weight:700;color:#1a3a5c;margin-bottom:16px;">🔥 Tour Phổ Biến</h6>
                            @foreach ($toursPopular as $popTour)
                                <div style="display:flex;gap:10px;margin-bottom:14px;align-items:center;">
                                    <img src="{{ asset('admin/assets/images/gallery-tours/' . $popTour->images[0]) }}"
                                        style="width:56px;height:56px;border-radius:8px;object-fit:cover;flex-shrink:0;" alt="">
                                    <div>
                                        <a href="{{ route('tour-detail', ['id' => $popTour->tourId]) }}"
                                            style="font-size:13px;font-weight:600;color:#1a3a5c;text-decoration:none;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">
                                            {{ $popTour->title }}
                                        </a>
                                        <div style="font-size:12px;color:#aaa;"><i class="fal fa-map-marker-alt"></i> {{ $popTour->destination }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            {{-- Main content --}}
            <div class="col-lg-9 col-md-8">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;">
                    <h4 style="font-weight:800;color:#1a3a5c;margin:0;">📋 Tour Đã Đặt <span style="font-size:14px;color:#aaa;font-weight:400;">({{ $myTours->count() }} tour)</span></h4>
                    <a href="{{ route('tours') }}" class="mt-btn primary"><i class="fal fa-plus"></i> Đặt Tour Mới</a>
                </div>

                @if($myTours->isEmpty())
                    <div class="mt-empty">
                        <i class="fal fa-calendar-times"></i>
                        <h4>Bạn chưa đặt tour nào</h4>
                        <p>Khám phá và đặt tour ngay để có chuyến đi tuyệt vời!</p>
                        <a href="{{ route('tours') }}" class="mt-btn primary" style="display:inline-flex;margin-top:12px;">
                            <i class="fal fa-search"></i> Khám Phá Tours
                        </a>
                    </div>
                @else
                    @foreach ($myTours as $tour)
                        @php
                            $statusLabel = '';
                            $statusClass = '';
                            if ($tour->bookingStatus == 'c') { $statusLabel = 'Đã hủy'; $statusClass = 'cancelled'; }
                            elseif ($tour->bookingStatus == 'f') { $statusLabel = 'Đã hoàn thành'; $statusClass = 'finished'; }
                            elseif ($tour->bookingStatus == 'y') { $statusLabel = 'Chuẩn bị khởi hành'; $statusClass = 'confirmed'; }
                            elseif ($tour->paymentStatus == 'n') { $statusLabel = 'Chưa thanh toán'; $statusClass = 'pending'; }
                            else { $statusLabel = 'Đợi xác nhận'; $statusClass = 'waiting'; }

                            $isPaid = ($tour->paymentStatus ?? 'n') === 'y';
                            $invoiceUrl = route('tour-booked', ['bookingId' => $tour->bookingId, 'checkoutId' => $tour->checkoutId]);
                        @endphp
                        <div class="mt-booking-card">
                            <div class="mt-card-img">
                                <span class="mt-badge {{ $statusClass }}">{{ $statusLabel }}</span>
                                <img src="{{ asset('admin/assets/images/gallery-tours/' . ($tour->images[0] ?? 'default.jpg')) }}"
                                    alt="{{ $tour->title }}">
                            </div>
                            <div class="mt-card-body">
                                <a href="{{ $invoiceUrl }}" class="mt-card-title">{{ $tour->title }}</a>

                                <div class="mt-card-meta">
                                    <span><i class="fal fa-map-marker-alt"></i>{{ $tour->destination }}</span>
                                    <span><i class="fal fa-clock"></i>{{ $tour->time }}</span>
                                    <span><i class="fal fa-users"></i>{{ $tour->numAdults + $tour->numChildren }} người</span>
                                    <span><i class="fal fa-calendar-alt"></i>{{ date('d/m/Y', strtotime($tour->startDate)) }}</span>
                                    @if($tour->bookingDate)
                                        <span><i class="fal fa-receipt"></i>Đặt: {{ date('d/m/Y', strtotime($tour->bookingDate)) }}</span>
                                    @endif
                                </div>

                                <div class="mt-card-desc">{!! strip_tags($tour->description) !!}</div>

                                <div class="mt-card-footer">
                                    <div>
                                        <div class="mt-price">{{ number_format($tour->totalPrice, 0, ',', '.') }} VNĐ</div>
                                        <div style="display:flex;gap:8px;align-items:center;margin-top:4px;">
                                            <span class="mt-booking-id">#{{ $tour->bookingId }}</span>
                                            <span class="mt-payment-badge {{ $isPaid ? 'paid' : 'unpaid' }}">
                                                {{ $isPaid ? '✅ Đã thanh toán' : '⏳ Chưa TT' }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="mt-actions">
                                        {{-- Xem hóa đơn --}}
                                        <a href="{{ $invoiceUrl }}" class="mt-btn primary">
                                            <i class="fal fa-file-invoice"></i> Hóa đơn
                                        </a>

                                        @if ($tour->bookingStatus == 'f')
                                            {{-- Tour đã hoàn thành → Đánh giá --}}
                                            <a href="{{ route('tour-detail', ['id' => $tour->tourId]) }}" class="mt-btn success">
                                                <i class="fas fa-star"></i>
                                                {{ $tour->rating ? 'Đã đánh giá' : 'Đánh giá' }}
                                            </a>
                                        @elseif (in_array($tour->bookingStatus, ['b', 'y']) && \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($tour->startDate), false) >= 3)
                                            {{-- Tour chưa khởi hành và còn > 3 ngày → Huỷ --}}
                                            <form action="{{ route('cancel-booking') }}" method="POST" style="display:inline-block;"
                                                onsubmit="return confirm('Bạn có chắc muốn hủy? Hủy trước 7 ngày miễn phí 100%, trước 3 ngày mất 50% phí.')">
                                                @csrf
                                                <input type="hidden" name="tourId" value="{{ $tour->tourId }}">
                                                <input type="hidden" name="bookingId" value="{{ $tour->bookingId }}">
                                                <input type="hidden" name="quantity__adults" value="{{ $tour->numAdults }}">
                                                <input type="hidden" name="quantity__children" value="{{ $tour->numChildren }}">
                                                <button type="submit" class="mt-btn danger">
                                                    <i class="fal fa-times"></i> Hủy Tour
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</section>

@include('clients.blocks.footer')
