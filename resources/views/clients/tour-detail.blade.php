@include('clients.blocks.header')
<section class="page-banner-two rel z-1">
    <div class="container-fluid">
        <hr class="mt-0">
        <div class="container">
            <div class="banner-inner pt-15 pb-25">
                <!--<h2 class="page-title mb-10 aos-init aos-animate" data-aos="fade-left" data-aos-duration="1500"
                    data-aos-offset="50">{{ $tourDetail->destination }}</h2>-->
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-center mb-20 aos-init aos-animate" data-aos="fade-right"
                        data-aos-delay="200" data-aos-duration="1500" data-aos-offset="50">
                        <li class="breadcrumb-item"><a href="index.html">Trang chủ</a></li>
                        <li class="breadcrumb-item active">{{ $title }}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>
<!-- Tour Gallery start -->
<style>
    .tour-gallery .gallery-item img {
        object-fit: cover;
        width: 100%;
        height: 220px;
        /* Thu gọn khung hình cố định cho ảnh nhỏ */
        border-radius: 10px;
        /* Bo góc để giao diện chỉnh chu và modern hơn */
    }

    .tour-gallery .gallery-between img {
        height: 480px;
        /* Ảnh lớn ở giữa sẽ dài ra bằng ~ 2 ảnh nhỏ + thanh margin */
    }

    .widget-tour .image img {
        object-fit: cover;
        width: 100%;
        height: 100%;
    }
</style>
<div class="tour-gallery">
    <div class="container-fluid">
        <div class="row gap-10 justify-content-center rel">
            <div class="col-lg-4 col-md-6">
                <div class="gallery-item">
                    <img src="{{ asset('admin/assets/images/gallery-tours/' . ($tourDetail->images[0] ?? 'cau-vang-da-nang_1775281412.png')) }}"
                        alt="Tour List">
                </div>
                <div class="gallery-item">
                    <img src="{{ asset('admin/assets/images/gallery-tours/' . ($tourDetail->images[1] ?? 'ba-na-hill-da-nang-1_1775407853.png')) }}"
                        alt="Tour List">
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="gallery-item gallery-between">
                    <img src="{{ asset('admin/assets/images/gallery-tours/' . ($tourDetail->images[2] ?? 'ba-na-hill-da-nang-1_1775408993.png')) }}"
                        alt="Destination">
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="gallery-item">
                    <img src="{{ asset('admin/assets/images/gallery-tours/' . ($tourDetail->images[3] ?? 'cau-vang-da-nang_1775407854.png')) }}"
                        alt="Destination">
                </div>
                <div class="gallery-item">
                    <img src="{{ asset('admin/assets/images/gallery-tours/' . ($tourDetail->images[4] ?? 'ben-trong-ba-na-hills-da-nang_1775407854.png')) }}"
                        alt="Destination">
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Tour Gallery End -->


<!-- Tour Header Area start -->
<section class="tour-header-area pt-70 rel z-1">
    <div class="container">
        <div class="row justify-content-between">
            <div class="col-xl-6 col-lg-7">
                <div class="tour-header-content mb-15" data-aos="fade-left" data-aos-duration="1500"
                    data-aos-offset="50">
                    <span class="location d-inline-block mb-10"><i class="fal fa-map-marker-alt"></i>
                        {{ $tourDetail->destination }}</span>
                    <div class="section-title pb-5">
                        <h2>{{ $tourDetail->title }}</h2>
                    </div>
                    <div class="ratting">
                        @for ($i = 0; $i < 5; $i++)
                            @if ($avgStar && $i < $avgStar)
                                <i class="fas fa-star"></i>
                            @else
                                <i class="far fa-star"></i>
                            @endif
                        @endfor

                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-lg-5 text-lg-end" data-aos="fade-right" data-aos-duration="1500"
                data-aos-offset="50">
                <div class="tour-header-social mb-10">
                    {{-- ===== SHARE BUTTON DROPDOWN ===== --}}
                    <div class="tour-share-wrapper" style="position:relative; display:inline-block;">
                        <a href="#" id="btn-share-tour" class="tour-share-btn">
                            <i class="far fa-share-alt"></i>Chia sẻ
                        </a>
                        <div class="share-dropdown" id="share-dropdown" style="display:none;">
                            <a href="#" class="share-option share-facebook" title="Facebook">
                                <i class="fab fa-facebook-f"></i> Facebook
                            </a>
                            <a href="#" class="share-option share-twitter" title="Twitter/X">
                                <i class="fab fa-twitter"></i> Twitter / X
                            </a>
                            <a href="#" class="share-option share-linkedin" title="LinkedIn">
                                <i class="fab fa-linkedin-in"></i> LinkedIn
                            </a>
                            <a href="#" class="share-option share-whatsapp" title="WhatsApp">
                                <i class="fab fa-whatsapp"></i> WhatsApp
                            </a>
                            <a href="#" class="share-option share-copylink" id="btn-copy-link" title="Sao chép link">
                                <i class="far fa-copy"></i> <span id="copy-link-text">Sao chép link</span>
                            </a>
                        </div>
                    </div>

                    {{-- ===== WISHLIST BUTTON ===== --}}
                    <a href="#" id="btn-wishlist" class="tour-wishlist-btn {{ $isWishlisted ? 'wishlisted' : '' }}"
                        data-tour-id="{{ $tourDetail->tourId }}" data-url="{{ route('wishlist.toggle') }}"
                        data-login-url="{{ route('login') }}"
                        title="{{ $isWishlisted ? 'Xoá khỏi yêu thích' : 'Thêm vào yêu thích' }}">
                        <i class="{{ $isWishlisted ? 'fas' : 'far' }} fa-heart"></i>
                        <span id="wishlist-label">{{ $isWishlisted ? 'Đã yêu thích' : 'Yêu thích' }}</span>
                    </a>
                </div>

                {{-- CSS nội tuyến cho share & wishlist --}}
                <style>
                    /* --- Share Button --- */
                    .tour-share-wrapper {
                        margin-right: 8px;
                    }

                    .tour-share-btn {
                        display: inline-flex;
                        align-items: center;
                        gap: 6px;
                        padding: 8px 16px;
                        border-radius: 30px;
                        border: 2px solid #e0e0e0;
                        color: #555;
                        font-weight: 600;
                        font-size: 14px;
                        text-decoration: none;
                        transition: all .25s;
                        background: #fff;
                    }

                    .tour-share-btn:hover {
                        border-color: #0077b6;
                        color: #0077b6;
                        background: #f0f8ff;
                    }

                    /* --- Share Dropdown --- */
                    .share-dropdown {
                        position: absolute;
                        top: calc(100% + 8px);
                        right: 0;
                        background: #fff;
                        border-radius: 14px;
                        box-shadow: 0 8px 32px rgba(0, 0, 0, .13);
                        min-width: 190px;
                        overflow: hidden;
                        z-index: 999;
                        padding: 6px 0;
                        animation: fadeInDown .2s ease;
                    }

                    @keyframes fadeInDown {
                        from {
                            opacity: 0;
                            transform: translateY(-8px);
                        }

                        to {
                            opacity: 1;
                            transform: translateY(0);
                        }
                    }

                    .share-option {
                        display: flex;
                        align-items: center;
                        gap: 10px;
                        padding: 10px 18px;
                        color: #333;
                        font-size: 14px;
                        font-weight: 500;
                        text-decoration: none;
                        transition: background .18s;
                    }

                    .share-option:hover {
                        background: #f5f5f5;
                        color: #333;
                    }

                    .share-option i {
                        width: 20px;
                        text-align: center;
                        font-size: 16px;
                    }

                    .share-facebook i {
                        color: #1877F2;
                    }

                    .share-twitter i {
                        color: #1DA1F2;
                    }

                    .share-linkedin i {
                        color: #0A66C2;
                    }

                    .share-whatsapp i {
                        color: #25D366;
                    }

                    .share-copylink i {
                        color: #777;
                    }

                    #copy-link-text.copied {
                        color: #28a745;
                        font-weight: 700;
                    }

                    /* --- Wishlist Button --- */
                    .tour-wishlist-btn {
                        display: inline-flex;
                        align-items: center;
                        gap: 6px;
                        padding: 8px 16px;
                        border-radius: 30px;
                        border: 2px solid #e0e0e0;
                        color: #555;
                        font-weight: 600;
                        font-size: 14px;
                        text-decoration: none;
                        transition: all .25s;
                        background: #fff;
                    }

                    .tour-wishlist-btn:hover,
                    .tour-wishlist-btn.wishlisted {
                        border-color: #e74c3c;
                        color: #e74c3c;
                        background: #fff5f5;
                    }

                    .tour-wishlist-btn i {
                        font-size: 15px;
                        transition: transform .2s;
                    }

                    .tour-wishlist-btn:hover i,
                    .tour-wishlist-btn.wishlisted i {
                        transform: scale(1.2);
                    }
                </style>
            </div>
        </div>
        <hr class="mt-50 mb-70">
    </div>
</section>
<!-- Tour Header Area end -->


<!-- Tour Details Area start -->
<section class="tour-details-page pb-100">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">

                {{-- ====== TAB NAVIGATION (Vanilla JS) ====== --}}
                <style>
                /* Custom tab CSS — không phụ thuộc Bootstrap version */
                .td-nav-tabs { display: flex; gap: 0; border-bottom: 2px solid #e0e0e0;
                  margin-bottom: 28px; list-style: none; padding: 0; }
                .td-nav-tabs li { margin: 0; }
                .td-tab-btn { background: none; border: none; padding: 12px 20px;
                  font-size: 15px; font-weight: 600; color: #888; cursor: pointer;
                  border-bottom: 3px solid transparent; margin-bottom: -2px;
                  transition: all .2s; white-space: nowrap; }
                .td-tab-btn:hover { color: #1a3a5c; }
                .td-tab-btn.active { color: #1a3a5c; border-bottom-color: #1a3a5c; }
                .td-tab-pane { display: none; }
                .td-tab-pane.active { display: block; }
                </style>
                <ul class="td-nav-tabs" id="tourDetailTab">
                    <li><button class="td-tab-btn active" data-tab="tab-overview">Tổng quan</button></li>
                    <li><button class="td-tab-btn" data-tab="tab-schedule">📅 Lịch khởi hành</button></li>
                    <li><button class="td-tab-btn" data-tab="tab-timeline">Lịch trình</button></li>
                    <li><button class="td-tab-btn" data-tab="tab-reviews">Đánh giá</button></li>
                </ul>
                <script>
                function switchTab(tabId) {
                    var btn = document.querySelector('.td-tab-btn[data-tab="' + tabId + '"]');
                    if (btn) btn.click();
                    window.scrollTo({
                        top: document.getElementById('tourDetailTab').offsetTop - 100,
                        behavior: 'smooth'
                    });
                }

                document.querySelectorAll('.td-tab-btn').forEach(function(btn) {
                    btn.addEventListener('click', function() {
                        // Deactivate all
                        document.querySelectorAll('.td-tab-btn').forEach(b => b.classList.remove('active'));
                        document.querySelectorAll('.td-tab-pane').forEach(p => p.classList.remove('active'));
                        // Activate clicked
                        this.classList.add('active');
                        var target = this.dataset.tab;
                        var pane = document.getElementById(target);
                        if (pane) pane.classList.add('active');
                        
                        // Load calendar when schedule tab opened (always reload if not loaded yet)
                        if (target === 'tab-schedule' && typeof loadSchedules === 'function') {
                            loadSchedules();
                        }

                        // Show/hide sidebar booking widget
                        var sidebarWidget = document.getElementById('sidebar-booking-widget');
                        if (sidebarWidget) {
                            if (target === 'tab-schedule' || target === 'tab-reviews') {
                                sidebarWidget.style.display = 'none';
                            } else {
                                sidebarWidget.style.display = 'block';
                            }
                        }
                    });
                });
                </script>

                <div id="td-tab-content">
                {{-- ====== TAB 1: TỔNG QUAN ====== --}}
                <div class="td-tab-pane active" id="tab-overview">
                <div class="tour-details-content">
                    <h3>Khám phá Tours</h3>
                    <p>{!! $tourDetail->description !!} </p>
                    <div class="row pb-55">
                        <div class="col-md-6">
                            <div class="tour-include-exclude mt-30">
                                <h5>Bao gồm và không bao gồm</h5>
                                <ul class="list-style-one check mt-25">
                                    <li><i class="far fa-check"></i> Dịch vụ đón và trả khách</li>
                                    <li><i class="far fa-check"></i> 1 bữa ăn mỗi ngày</li>
                                    <li><i class="far fa-check"></i> Bữa tối trên du thuyền & Sự kiện âm nhạc</li>
                                    <li><i class="far fa-check"></i> Tham quan 7 địa điểm tuyệt vời nhất trong thành phố
                                    </li>
                                    <li><i class="far fa-check"></i> Nước đóng chai trên xe buýt</li>
                                    <li><i class="far fa-check"></i> Phương tiện di chuyển Xe buýt du lịch hạng sang
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="tour-include-exclude mt-30">
                                <h5>Không bao gồm</h5>
                                <ul class="list-style-one mt-25">
                                    <li><i class="far fa-times"></i> Tiền boa</li>
                                    <li><i class="far fa-times"></i> Đón và trả khách tại khách sạn</li>
                                    <li><i class="far fa-times"></i> Bữa trưa, Đồ ăn & Đồ uống</li>
                                    <li><i class="far fa-times"></i> Nâng cấp tùy chọn lên một ly</li>
                                    <li><i class="far fa-times"></i> Dịch vụ bổ sung</li>
                                    <li><i class="far fa-times"></i> Bảo hiểm</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                </div>

                {{-- ====== TAB 2: LỊCH KHỞI HÀNH ====== --}}
                <div class="td-tab-pane" id="tab-schedule">
                <style>
                .schedule-calendar { display: flex; gap: 20px; margin-bottom: 30px; }
                .month-list { display: flex; flex-direction: column; gap: 8px; min-width: 100px; }
                .month-btn { background: #f5f5f5; border: none; border-radius: 8px; padding: 8px 14px;
                  cursor: pointer; font-weight: 600; font-size: 14px; color: #555; transition: all .2s; }
                .month-btn.active { background: #1a3a5c; color: #fff; }
                .calendar-wrap { flex: 1; background: #fff; border-radius: 12px; border: 1px solid #e0e0e0; padding: 20px; }
                .calendar-header { display: flex; align-items: center; justify-content: center;
                  gap: 16px; margin-bottom: 16px; font-size: 18px; font-weight: 700; color: #1a3a5c; }
                .cal-grid { display: grid; grid-template-columns: repeat(7, 1fr); gap: 4px; }
                .cal-day-name { text-align: center; font-weight: 700; font-size: 13px; padding: 6px 0;
                  color: #888; }
                .cal-day-name.weekend { color: #e53935; }
                .cal-cell { text-align: center; padding: 6px 4px; border-radius: 8px; font-size: 13px;
                  min-height: 52px; display: flex; flex-direction: column; align-items: center; justify-content: center; }
                .cal-cell.empty { background: transparent; }
                .cal-cell.has-tour { background: #fff3e0; cursor: pointer; border: 1px solid #ffcc02;
                  font-weight: 700; color: #d32f2f; transition: all .2s; }
                .cal-cell.has-tour:hover { background: #d32f2f; color: #fff; transform: scale(1.08); }
                .cal-cell.has-tour .cal-price { font-size: 10px; color: #e65100; font-weight: 600; margin-top: 2px; }
                .cal-cell.has-tour:hover .cal-price { color: #ffe082; }
                .schedule-detail-panel { background: #f8f9fa; border-radius: 12px; border: 1px solid #dee2e6;
                  padding: 24px; margin-top: 16px; display: none; }
                .schedule-detail-panel.show { display: block; }
                .sch-info-row { display: flex; align-items: center; gap: 12px; margin-bottom: 12px; font-size: 15px; }
                .sch-info-row i { width: 20px; color: #1a3a5c; }
                .sch-price-table { width: 100%; border-collapse: collapse; margin: 16px 0; }
                .sch-price-table td { padding: 10px 14px; border-bottom: 1px solid #e0e0e0; font-size: 15px; }
                .sch-price-table td:last-child { text-align: right; color: #d32f2f; font-weight: 700; }
                .btn-book-now { background: #d32f2f; color: #fff; border: none; border-radius: 30px;
                  padding: 12px 36px; font-size: 16px; font-weight: 700; cursor: pointer;
                  display: block; width: 100%; margin-top: 16px; text-decoration: none; text-align: center;
                  transition: background .2s; }
                .btn-book-now:hover { background: #b71c1c; color: #fff; }
                </style>

                <div class="schedule-calendar">
                    <div class="month-list" id="monthList"></div>
                    <div style="flex:1">
                        <div class="calendar-wrap">
                            <div class="calendar-header">
                                <button onclick="prevMonth()" style="background:none;border:none;font-size:20px;cursor:pointer;">&#8592;</button>
                                <span id="calTitle">THÁNG 5/2026</span>
                                <button onclick="nextMonth()" style="background:none;border:none;font-size:20px;cursor:pointer;">&#8594;</button>
                            </div>
                            <div class="cal-grid" id="calGrid">
                                <div class="cal-day-name">T2</div>
                                <div class="cal-day-name">T3</div>
                                <div class="cal-day-name">T4</div>
                                <div class="cal-day-name">T5</div>
                                <div class="cal-day-name">T6</div>
                                <div class="cal-day-name weekend">T7</div>
                                <div class="cal-day-name weekend">CN</div>
                            </div>
                        </div>
                        <div class="schedule-detail-panel" id="scheduleDetailPanel">
                            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px">
                                <button onclick="closeDetail()" style="background:none;border:none;cursor:pointer;color:#555;font-size:14px">&#8592; Quay lại</button>
                                <span id="detailDate" style="font-size:20px;font-weight:700;color:#d32f2f"></span>
                            </div>
                            <div class="sch-info-row"><i class="fas fa-map-marker-alt"></i><span>Khởi hành: <b id="detailDestination"></b></span></div>
                            <div class="sch-info-row"><i class="fas fa-calendar"></i><span>Ngày đi: <b id="detailStart"></b> &nbsp;—&nbsp; Ngày về: <b id="detailEnd"></b></span></div>
                            <div class="sch-info-row"><i class="fas fa-clock"></i><span>Thời gian: <b id="detailTime"></b></span></div>
                            <div class="sch-info-row"><i class="fas fa-users"></i><span>Số chỗ còn lại: <b id="detailQuantity" style="color:#388e3c"></b></span></div>
                            <div id="detailNote" class="sch-info-row" style="display:none;background:#fff8e1;padding:8px 14px;border-radius:8px;color:#e65100">
                                <i class="fas fa-info-circle"></i><span id="detailNoteText"></span>
                            </div>
                            <table class="sch-price-table">
                                <tr><td>Người lớn</td><td id="detailPriceAdult"></td></tr>
                                <tr><td>Trẻ em</td><td id="detailPriceChild"></td></tr>
                            </table>
                            <a id="btnBookNow" href="#" class="btn-book-now">Đặt ngay</a>
                        </div>
                    </div>
                </div>

                <script>
                var TOUR_ID = {{ $tourDetail->tourId }};
                var scheduleData = [];
                var calYear, calMonth;
                var today = new Date();
                calYear  = today.getFullYear();
                calMonth = today.getMonth(); // 0-indexed

                function formatMoney(n) {
                    return Number(n).toLocaleString('vi-VN') + ' ₫';
                }
                function formatDate(d) {
                    var p = d.split('-'); return p[2]+'/'+p[1]+'/'+p[0];
                }

                function loadSchedules() {
                    var calWrap = document.querySelector('.calendar-wrap');
                    if (calWrap) calWrap.style.opacity = '0.5';

                    fetch('/api/tour-schedules/' + TOUR_ID)
                        .then(r => r.json())
                        .then(function(data) {
                            scheduleData = data;
                            if (calWrap) calWrap.style.opacity = '1';

                            if (scheduleData.length === 0) {
                                // Không có lịch khởi hành
                                buildMonthList();
                                renderCalendar();
                                var grid = document.getElementById('calGrid');
                                var msg = document.getElementById('noScheduleMsg');
                                if (!msg) {
                                    msg = document.createElement('div');
                                    msg.id = 'noScheduleMsg';
                                    msg.style.cssText = 'text-align:center;padding:40px 20px;color:#888;font-size:15px;';
                                    msg.innerHTML = '<i class="fas fa-calendar-times" style="font-size:36px;color:#ccc;display:block;margin-bottom:12px;"></i>Tour này chưa có lịch khởi hành.<br><span style="font-size:13px;">Vui lòng liên hệ để được tư vấn.</span>';
                                    grid.parentNode.insertBefore(msg, grid.nextSibling);
                                }
                                return;
                            }

                            // Xóa thông báo cũ nếu có
                            var oldMsg = document.getElementById('noScheduleMsg');
                            if (oldMsg) oldMsg.remove();

                            buildMonthList();

                            // Tự động nhảy đến tháng đầu tiên có lịch nếu tháng hiện tại không có
                            var hasThisMonth = scheduleData.some(function(s) {
                                var d = parseScheduleDate(s.startDate);
                                return d.y === calYear && d.m === calMonth;
                            });

                            if (!hasThisMonth && scheduleData.length > 0) {
                                // Sắp xếp theo startDate và lấy tháng đầu tiên
                                var sorted = scheduleData.slice().sort(function(a,b){
                                    return a.startDate.localeCompare(b.startDate);
                                });
                                var first = parseScheduleDate(sorted[0].startDate);
                                calYear  = first.y;
                                calMonth = first.m;
                                // Cập nhật active button trong monthList
                                document.querySelectorAll('.month-btn').forEach(function(b) {
                                    b.classList.toggle('active',
                                        b.textContent === (calMonth+1)+'/'+calYear);
                                });
                            }

                            renderCalendar();
                        })
                        .catch(function() {
                            if (calWrap) calWrap.style.opacity = '1';
                        });
                }

                // Helper: parse date string 'YYYY-MM-DD' an toàn (tránh lỗi timezone UTC)
                function parseScheduleDate(str) {
                    var p = str.split('-');
                    return { y: parseInt(p[0],10), m: parseInt(p[1],10)-1, d: parseInt(p[2],10) };
                }

                function buildMonthList() {
                    // Sắp xếp các tháng theo thứ tự thời gian
                    var months = {};
                    scheduleData.forEach(function(s) {
                        var pd = parseScheduleDate(s.startDate);
                        var key = pd.y + '-' + String(pd.m+1).padStart(2,'0');
                        months[key] = {y: pd.y, m: pd.m};
                    });
                    var list = document.getElementById('monthList');
                    list.innerHTML = '';
                    // Sắp xếp key để hiển thị đúng thứ tự
                    Object.keys(months).sort().forEach(function(key) {
                        var v = months[key];
                        var btn = document.createElement('button');
                        btn.className = 'month-btn' + (v.y===calYear && v.m===calMonth ? ' active' : '');
                        btn.textContent = (v.m+1)+'/'+v.y;
                        btn.onclick = function() {
                            calYear=v.y; calMonth=v.m;
                            document.querySelectorAll('.month-btn').forEach(b=>b.classList.remove('active'));
                            btn.classList.add('active');
                            closeDetail(); renderCalendar();
                        };
                        list.appendChild(btn);
                    });
                }

                function renderCalendar() {
                    var months = ['THÁNG 1','THÁNG 2','THÁNG 3','THÁNG 4','THÁNG 5','THÁNG 6',
                                  'THÁNG 7','THÁNG 8','THÁNG 9','THÁNG 10','THÁNG 11','THÁNG 12'];
                    document.getElementById('calTitle').textContent = months[calMonth] + '/' + calYear;

                    // Map startDate -> schedule (dùng parseScheduleDate tránh lỗi timezone)
                    var schedMap = {};
                    scheduleData.forEach(function(s) {
                        var pd = parseScheduleDate(s.startDate);
                        if (pd.y === calYear && pd.m === calMonth) {
                            schedMap[pd.d] = s;
                        }
                    });

                    var firstDay = new Date(calYear, calMonth, 1).getDay(); // 0=Sun
                    firstDay = firstDay === 0 ? 6 : firstDay - 1; // Mon=0
                    var daysInMonth = new Date(calYear, calMonth+1, 0).getDate();

                    var grid = document.getElementById('calGrid');
                    // Remove old day cells (keep headers = first 7 children)
                    while (grid.children.length > 7) grid.removeChild(grid.lastChild);

                    // Empty cells before 1st
                    for (var i = 0; i < firstDay; i++) {
                        var e = document.createElement('div'); e.className='cal-cell empty'; grid.appendChild(e);
                    }
                    for (var d = 1; d <= daysInMonth; d++) {
                        var cell = document.createElement('div');
                        if (schedMap[d]) {
                            cell.className = 'cal-cell has-tour';
                            cell.innerHTML = d + '<div class="cal-price">' +
                                Number(schedMap[d].priceAdult/1000).toLocaleString('vi') + 'K</div>';
                            (function(s){ cell.onclick = function(){ showDetail(s); }; })(schedMap[d]);
                        } else {
                            cell.className = 'cal-cell';
                            cell.textContent = d;
                        }
                        grid.appendChild(cell);
                    }
                }

                function showDetail(s) {
                    document.getElementById('detailDate').textContent =
                        formatDate(s.startDate);
                    document.getElementById('detailDestination').textContent =
                        '{{ $tourDetail->destination }}';
                    document.getElementById('detailStart').textContent = formatDate(s.startDate);
                    document.getElementById('detailEnd').textContent   = formatDate(s.endDate);
                    document.getElementById('detailTime').textContent  = s.note ? s.note : '{{ $tourDetail->time }}';
                    document.getElementById('detailQuantity').textContent = s.quantity + ' chỗ';
                    document.getElementById('detailPriceAdult').textContent = formatMoney(s.priceAdult);
                    document.getElementById('detailPriceChild').textContent = formatMoney(s.priceChild);
                    if (s.note) {
                        document.getElementById('detailNote').style.display = 'flex';
                        document.getElementById('detailNoteText').textContent = s.note;
                    } else {
                        document.getElementById('detailNote').style.display = 'none';
                    }
                    document.getElementById('btnBookNow').href =
                        '/booking-schedule/' + s.scheduleId;
                    document.getElementById('scheduleDetailPanel').classList.add('show');
                }

                function closeDetail() {
                    document.getElementById('scheduleDetailPanel').classList.remove('show');
                }
                function prevMonth() {
                    calMonth--; if(calMonth<0){calMonth=11;calYear--;}
                    closeDetail(); renderCalendar();
                }
                function nextMonth() {
                    calMonth++; if(calMonth>11){calMonth=0;calYear++;}
                    closeDetail(); renderCalendar();
                }

                // (Tab loading được xử lý trong event listener ở trên - không cần Bootstrap handler)
                </script>
                </div>{{-- end #tab-schedule --}}

                {{-- ====== TAB 3: LỊCH TRÌNH ====== --}}
                <div class="td-tab-pane" id="tab-timeline">
                <h3>Lịch trình</h3>
                <div class="accordion-two mt-25 mb-60" id="faq-accordion-timeline">
                    @php $day2 = 1; @endphp
                    @foreach ($tourDetail->timeline as $timeline)
                        <div class="accordion-item">
                            <h5 class="accordion-header">
                                <button class="accordion-button collapsed" data-bs-toggle="collapse"
                                    data-bs-target="#collapseT{{ $timeline->timelineId }}">
                                    Ngày {{ $day2++ }} - {{ $timeline->title }}
                                </button>
                            </h5>
                            <div id="collapseT{{ $timeline->timelineId }}" class="accordion-collapse collapse"
                                data-bs-parent="#faq-accordion-timeline">
                                <div class="accordion-body">
                                    <p>{!! $timeline->description !!}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                </div>{{-- end #tab-timeline --}}

                {{-- ====== TAB 4: ĐÁNH GIÁ ====== --}}
                <div class="td-tab-pane" id="tab-reviews">
                <div id="partials_reviews">
                    @include('clients.partials.reviews')
                </div>
                <h3 class="{{ $checkDisplay }}">Thêm Đánh giá</h3>
                <form id="comment-form" class="comment-form bgc-lighter z-1 rel mt-30 {{ $checkDisplay }}"
                    name="review-form" action="{{ route('reviews') }}" method="post">
                    @csrf
                    <div class="comment-review-wrap">
                        <div class="comment-ratting-item">
                            <span class="title">Đánh giá</span>
                            <div class="ratting" id="rating-stars">
                                <i class="far fa-star" data-value="1"></i>
                                <i class="far fa-star" data-value="2"></i>
                                <i class="far fa-star" data-value="3"></i>
                                <i class="far fa-star" data-value="4"></i>
                                <i class="far fa-star" data-value="5"></i>
                            </div>
                        </div>
                    </div>
                    <hr class="mt-30 mb-40">
                    <h5>Để lại phản hồi</h5>
                    <div class="row gap-20 mt-20">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="message2">Nội dung</label>
                                <textarea name="message" id="message2" class="form-control" rows="5" required></textarea>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group mb-0">
                                <button type="submit" class="theme-btn bgc-secondary style-two" id="submit-reviews"
                                    data-url-checkBooking="{{ route('checkBooking') }}"
                                    data-tourId-reviews="{{ $tourDetail->tourId }}">
                                    <span data-hover="Gửi đánh giá">Gửi đánh giá</span>
                                    <i class="fal fa-arrow-right"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
                </div>{{-- end #tab-reviews --}}

                </div>{{-- end td-tab-content --}}

            </div>{{-- end col-lg-8 --}}
            <div class="col-lg-4 col-md-8 col-sm-10 rmt-75">
                <div class="blog-sidebar tour-sidebar">

                    <div id="sidebar-booking-widget" class="widget widget-booking" data-aos="fade-up" data-aos-duration="1500" data-aos-offset="50">
                        <h5 class="widget-title">Tour Booking</h5>
                        <form action="javascript:void(0);" method="POST">
                            @csrf
                            <div class="date mb-25">
                                <b>Ngày bắt đầu</b>
                                <input type="text" value="{{ date('d-m-Y', strtotime($tourDetail->startDate)) }}"
                                    name="startdate" disabled>
                            </div>
                            <hr>
                            <div class="date mb-25">
                                <b>Ngày kết thúc</b>
                                <input type="text" value="{{ date('d-m-Y', strtotime($tourDetail->endDate)) }}"
                                    name="enddate" disabled>
                            </div>
                            <hr>
                            <div class="time py-5">
                                <b>Thời gian :</b>
                                <p>{{ $tourDetail->time }}</p>
                                <input type="hidden" name="time">
                            </div>
                            <hr class="mb-25">
                            <h6>Vé:</h6>
                            <ul class="tickets clearfix">
                                <li>
                                    Người lớn <span
                                        class="price">{{ number_format($tourDetail->priceAdult, 0, ',', '.') }} VND
                                    </span>
                                </li>
                                <li>
                                    Trẻ em <span class="price">{{ number_format($tourDetail->priceChild, 0, ',', '.') }}
                                        VND
                                    </span>
                                </li>
                            </ul>
                            <button type="button" onclick="switchTab('tab-schedule')" class="theme-btn style-two w-100 mt-15 mb-5">
                                <span data-hover="Đặt ngay">Đặt ngay</span>
                                <i class="fal fa-arrow-right"></i>
                            </button>
                            <div class="text-center">
                                <a href="{{ route('contact') }}">Bạn cần trợ giúp không?</a>
                            </div>
                        </form>
                    </div>

                    <div class="widget widget-contact" data-aos="fade-up" data-aos-duration="1500" data-aos-offset="50">
                        <h5 class="widget-title">Cần trợ giúp?</h5>
                        <ul class="list-style-one">
                            <li><i class="far fa-envelope"></i> <a href="emilto:admin@gmail.com">admin@gmail.com</a>
                            </li>
                            <li><i class="far fa-phone-volume"></i> <a href="callto:+000(123)45688">+000 (123) 456
                                    88</a></li>
                        </ul>
                    </div>
                    @if (!empty($tourRecommendations))
                        <div class="widget widget-tour" data-aos="fade-up" data-aos-duration="1500" data-aos-offset="50">
                            <h6 class="widget-title">Tours tương tự</h6>
                            @foreach ($tourRecommendations as $tour)
                                <div class="destination-item tour-grid style-three bgc-lighter">
                                    <div class="image">
                                        {{-- <span class="badge">10% Off</span> --}}
                                        <img src="{{ asset('admin/assets/images/gallery-tours/' . $tour->images[0]) }}"
                                            alt="Tour" style="max-height: 137px">
                                    </div>
                                    <div class="content">
                                        <div class="destination-header">
                                            <span class="location"><i class="fal fa-map-marker-alt"></i>
                                                {{ $tour->destination }}</span>
                                            <div class="ratting">
                                                <i class="fas fa-star"></i>
                                                <span>({{ $tour->rating }})</span>
                                            </div>
                                        </div>
                                        <h6><a href="{{ route('tour-detail', ['id' => $tour->tourId]) }}">{{ $tour->title }}</a>
                                        </h6>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</section>
<!-- Tour Details Area end -->

@include('clients.blocks.new_letter')
@include('clients.blocks.footer')