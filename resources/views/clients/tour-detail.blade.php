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
                    <img src="{{ asset('admin/assets/images/gallery-tours/' . ($tourDetail->images[0] ?? 'vinh-ha-long-quang-ninh_1735834627.jpg')) }}"
                        alt="Tour List">
                </div>
                <div class="gallery-item">
                    <img src="{{ asset('admin/assets/images/gallery-tours/' . ($tourDetail->images[1] ?? 'vinh-ha-long-quang-ninh_1735834627.jpg')) }}"
                        alt="Tour List">
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="gallery-item gallery-between">
                    <img src="{{ asset('admin/assets/images/gallery-tours/' . ($tourDetail->images[2] ?? 'vinh-ha-long-quang-ninh_1735834627.jpg')) }}"
                        alt="Destination">
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="gallery-item">
                    <img src="{{ asset('admin/assets/images/gallery-tours/' . ($tourDetail->images[3] ?? 'vinh-ha-long-quang-ninh_1735834627.jpg')) }}"
                        alt="Destination">
                </div>
                <div class="gallery-item">
                    <img src="{{ asset('admin/assets/images/gallery-tours/' . ($tourDetail->images[4] ?? 'vinh-ha-long-quang-ninh_1735834627.jpg')) }}"
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
                <h3>Lịch trình</h3>
                <div class="accordion-two mt-25 mb-60" id="faq-accordion-two">
                    @php
                        $day = 1;
                    @endphp
                    @foreach ($tourDetail->timeline as $timeline)
                        <div class="accordion-item">
                            <h5 class="accordion-header">
                                <button class="accordion-button collapsed" data-bs-toggle="collapse"
                                    data-bs-target="#collapseTwo{{ $timeline->timelineId }}">
                                    Ngày {{ $day++ }} - {{ $timeline->title }}
                                </button>
                            </h5>
                            <div id="collapseTwo{{ $timeline->timelineId }}" class="accordion-collapse collapse"
                                data-bs-parent="#faq-accordion-two">
                                <div class="accordion-body">
                                    <p>{!! $timeline->description !!}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div id="partials_reviews">
                    @include('clients.partials.reviews')
                </div>

                <h3 class="{{ $checkDisplay }}">Thêm Đánh giá</h3>
                <form id="comment-form" class="comment-form bgc-lighter z-1 rel mt-30 {{ $checkDisplay }}"
                    name="review-form" action="{{ route('reviews') }}" method="post" data-aos="fade-up"
                    data-aos-duration="1500" data-aos-offset="50">
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
                                <label for="message">Nội dung</label>
                                <textarea name="message" id="message" class="form-control" rows="5"
                                    required=""></textarea>
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

            </div>
            <div class="col-lg-4 col-md-8 col-sm-10 rmt-75">
                <div class="blog-sidebar tour-sidebar">

                    <div class="widget widget-booking" data-aos="fade-up" data-aos-duration="1500" data-aos-offset="50">
                        <h5 class="widget-title">Tour Booking</h5>
                        <form action="{{ route('booking', ['id' => $tourDetail->tourId]) }}" method="POST">
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
                            <button type="submit" class="theme-btn style-two w-100 mt-15 mb-5">
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