@include('clients.blocks.header')
@include('clients.blocks.banner')
<!-- Tour List Area start -->
<section class="tour-list-page py-100 rel z-1">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-10 rmb-75">
                <div class="shop-sidebar mb-30">
                    @if (!$toursPopular->isEmpty())
                        <div class="widget widget-tour" data-aos="fade-up" data-aos-duration="1500"
                            data-aos-offset="50">
                            <h6 class="widget-title">Tour Nổi Bật</h6>
                            @foreach ($toursPopular as $tour)
                                <div class="destination-item tour-grid style-three bgc-lighter">
                                    <div class="image">
                                        <img src="{{ asset('admin/assets/images/gallery-tours/' . $tour->images[0]) }}"
                                            alt="Tour">
                                    </div>
                                    <div class="content">
                                        <div class="destination-header">
                                            <span class="location"><i class="fal fa-map-marker-alt"></i>
                                                {{ $tour->destination }}</span>
                                            <div class="ratting">
                                                <i class="fas fa-star"></i>
                                                <span>{{ $tour->rating ?? 0 }}</span>
                                            </div>
                                        </div>
                                        <h6><a
                                                href="{{ route('tour-detail', ['id' => $tour->tourId]) }}">{{ $tour->title }}</a>
                                        </h6>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

            </div>
            <div class="col-lg-9">
                <h4 class="mb-30 border-bottom pb-10">Danh sách tour được yêu thích</h4>
                @if($myWishlists->isEmpty())
                    <div class="alert alert-info">Bạn chưa theo dõi / thêm tour nào vào danh sách yêu thích. Hãy khám phá và lưu lại các tour thú vị nhé!</div>
                @else
                    @foreach ($myWishlists as $tour)
                        <div class="destination-item style-three bgc-lighter" data-aos="fade-up" data-aos-duration="1500"
                            data-aos-offset="50">
                            <div class="image">
                                <span class="badge bgc-pink"><i class="fas fa-heart"></i> Đã yêu thích</span>
                                <img src="{{ asset('admin/assets/images/gallery-tours/' . $tour->images[0] . '') }}"
                                    alt="Tour List">
                            </div>
                            <div class="content">
                                <div class="destination-header">
                                    <span class="location"><i
                                            class="fal fa-map-marker-alt"></i>{{ $tour->destination }}</span>
                                    <div class="ratting">
                                        @for ($i = 0; $i < 5; $i++)
                                            @if (isset($tour->rating) && $i < $tour->rating)
                                                <i class="fas fa-star"></i>
                                            @else
                                                <i class="far fa-star"></i>
                                            @endif
                                        @endfor

                                    </div>
                                </div>
                                <h5><a
                                        href="{{ route('tour-detail', ['id' => $tour->tourId]) }}">{{ $tour->title }}</a>
                                </h5>
                                <div class="truncate-3-lines mb-3">
                                    {!! $tour->description !!}
                                </div>

                                <ul class="blog-meta mt-1">
                                    <li><i class="far fa-clock"></i> {{ $tour->time }}</li>
                                    <li><i class="far fa-user"></i> Tổng: {{ $tour->quantity ?? 0 }} chỗ</li>
                                </ul>
                                <div class="destination-footer">
                                    <span class="price"><span>{{ number_format($tour->priceAdult, 0) }}</span>/vnđ</span>
                                    
                                    <a href="{{ route('tour-detail', ['id' => $tour->tourId]) }}"
                                        class="theme-btn style-two style-three">
                                        <span data-hover="Xem chi tiết">Xem chi tiết</span>
                                        <i class="fal fa-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</section>
<!-- Tour List Area end -->
@include('clients.blocks.footer')
