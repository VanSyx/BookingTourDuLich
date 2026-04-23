<div class="col-md-3 left_col">
    <div class="left_col scroll-view">
        <div class="navbar nav_title" style="border: 0;">
            <a href="{{ route('admin.dashboard') }}" class="site_title"><i class="fa fa-paw"></i> <span>Admin</span></a>
        </div>

        <div class="clearfix"></div>

        <!-- menu profile quick info -->
        <div class="profile clearfix">
            <div class="profile_pic">
                <img src="{{ asset('admin/assets/images/user-profile/avt_admin.jpg') }}" alt="..."
                    class="img-circle profile_img">
            </div>
            <div class="profile_info">
                <span>Xin chào,</span>
                <h2>Admin</h2>
            </div>
        </div>
        <!-- /menu profile quick info -->

        <br />

        <!-- sidebar menu -->
        <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
            <div class="menu_section">
                <h3>Tổng quan</h3>
                <ul class="nav side-menu">
                    <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a> </li>
                    <li><a href="{{ route('admin.admin') }}"><i class="fa fa-table"></i> Quản lý Admin</a> </li>
                    <li><a href="{{ route('admin.users') }}"><i class="fa fa-table"></i> Quản lý người dùng</a> </li>
                    <li><a><i class="fa fa-table"></i> Quản lý Tours<span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <li><a href="{{ route('admin.page-add-tours') }}">Thêm Tours</a></li>
                            <li><a href="{{ route('admin.tours') }}">Danh sách Tours</a></li>
                            <li><a href="{{ route('admin.tour-schedules') }}">📅 Lịch khởi hành</a></li>
                        </ul>
                    </li>

                    <li><a href="{{ route('admin.booking') }}"><i class="fa fa-home"></i> Quản lý Booking</a> </li>
                    <li><a href="{{ route('admin.contact') }}"><i class="fa fa-envelope-o"></i> Liên hệ </a> </li>
                </ul>
            </div>
        </div>
        <!-- /sidebar menu -->

        <!-- /menu footer buttons -->
        <div class="sidebar-footer hidden-small">
            <a data-toggle="tooltip" data-placement="top" title="Settings">
                <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
            </a>
            <a data-toggle="tooltip" data-placement="top" title="FullScreen">
                <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
            </a>
            <a data-toggle="tooltip" data-placement="top" title="Lock">
                <span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
            </a>
            <a data-toggle="tooltip" data-placement="top" title="Logout" href="{{ route('admin.logout') }}">
                <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
            </a>
        </div>
        <!-- /menu footer buttons -->
    </div>
</div>

<!-- top navigation -->
<div class="top_nav">
    <div class="nav_menu">
        <div class="nav toggle">
            <a id="menu_toggle"><i class="fa fa-bars"></i></a>
        </div>
        <nav class="nav navbar-nav">
            <ul class=" navbar-right">
                <li class="nav-item dropdown open" style="padding-left: 15px;">
                    <a href="javascript:;" class="user-profile dropdown-toggle" aria-haspopup="true" id="navbarDropdown"
                        data-toggle="dropdown" aria-expanded="false">
                        <img src="{{ asset('admin/assets/images/user-profile/avt_admin.jpg') }}" alt="">
                        @if (session()->has('admin'))
                            {{ session('admin') }}
                        @endif
                    </a>
                    <div class="dropdown-menu dropdown-usermenu pull-right" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="javascript:;"> Thông tin cá nhân</a>
                        <a class="dropdown-item" href="{{ route('admin.logout') }}"><i
                                class="fa fa-sign-out pull-right"></i> Đăng xuất</a>
                    </div>
                </li>

                <li role="presentation" class="nav-item dropdown open">
                    <a href="javascript:;" class="dropdown-toggle info-number" id="navbarDropdown1"
                        data-toggle="dropdown" aria-expanded="false">
                        <i class="fa fa-bell-o"></i>
                        @if ($totalNotifications > 0)
                            <span class="badge bg-red">{{ $totalNotifications > 99 ? '99+' : $totalNotifications }}</span>
                        @endif
                    </a>
                    <ul class="dropdown-menu list-unstyled msg_list" role="menu" aria-labelledby="navbarDropdown1"
                        style="min-width:320px; max-height:420px; overflow-y:auto;">

                        {{-- Header --}}
                        <li class="nav-item" style="padding:10px 15px; border-bottom:1px solid #eee; font-weight:600; color:#555;">
                            <i class="fa fa-bell"></i> Thông báo hệ thống
                        </li>

                        {{-- Liên hệ chưa trả lời --}}
                        @if ($unreadCount > 0)
                            <li class="nav-item" style="padding:6px 15px; background:#fff8e1;">
                                <a class="dropdown-item" href="{{ route('admin.contact') }}" style="padding:0;">
                                    <span style="color:#f57c00; font-weight:600;">
                                        <i class="fa fa-envelope"></i>
                                        {{ $unreadCount }} liên hệ chưa được trả lời
                                    </span>
                                </a>
                            </li>
                            @foreach ($unreadContacts->take(2) as $item)
                                <li class="nav-item" style="padding:4px 15px 4px 30px; border-bottom:1px solid #f5f5f5;">
                                    <a class="dropdown-item" href="{{ route('admin.contact') }}" style="padding:0;">
                                        <b>{{ $item->name }}</b>
                                        <span class="message text-contact-truncate" style="display:block; color:#888; font-size:12px;">
                                            {{ Str::limit($item->message, 50) }}
                                        </span>
                                    </a>
                                </li>
                            @endforeach
                        @endif

                        {{-- Booking mới chưa xác nhận --}}
                        @if ($newBookingsCount > 0)
                            <li class="nav-item" style="padding:6px 15px; background:#e8f5e9;">
                                <a class="dropdown-item" href="{{ route('admin.booking') }}" style="padding:0;">
                                    <span style="color:#388e3c; font-weight:600;">
                                        <i class="fa fa-calendar-check-o"></i>
                                        {{ $newBookingsCount }} đặt tour chưa xác nhận
                                    </span>
                                </a>
                            </li>
                            @foreach ($newBookingsList->take(2) as $item)
                                <li class="nav-item" style="padding:4px 15px 4px 30px; border-bottom:1px solid #f5f5f5;">
                                    <a class="dropdown-item" href="{{ route('admin.booking-detail', ['id' => $item->bookingId]) }}" style="padding:0;">
                                        <b>{{ $item->fullName }}</b>
                                        <span style="display:block; color:#888; font-size:12px;">{{ $item->tourTitle }}</span>
                                    </a>
                                </li>
                            @endforeach
                        @endif

                        {{-- User mới đăng ký --}}
                        @if ($newUsersCount > 0)
                            <li class="nav-item" style="padding:6px 15px; background:#e3f2fd; border-bottom:1px solid #eee;">
                                <a class="dropdown-item" href="{{ route('admin.users') }}" style="padding:0;">
                                    <span style="color:#1565c0; font-weight:600;">
                                        <i class="fa fa-user-plus"></i>
                                        {{ $newUsersCount }} tài khoản mới (7 ngày)
                                    </span>
                                </a>
                            </li>
                        @endif

                        {{-- Review mới --}}
                        @if ($newReviewsCount > 0)
                            <li class="nav-item" style="padding:6px 15px; background:#fce4ec; border-bottom:1px solid #eee;">
                                <a class="dropdown-item" href="{{ route('admin.tours') }}" style="padding:0;">
                                    <span style="color:#c62828; font-weight:600;">
                                        <i class="fa fa-star"></i>
                                        {{ $newReviewsCount }} đánh giá mới (7 ngày)
                                    </span>
                                </a>
                            </li>
                        @endif

                        {{-- Không có thông báo --}}
                        @if ($totalNotifications == 0)
                            <li class="nav-item" style="padding:15px; text-align:center; color:#999;">
                                <i class="fa fa-check-circle" style="color:#4caf50;"></i> Không có thông báo mới
                            </li>
                        @endif

                        {{-- Footer link --}}
                        <li class="nav-item" style="padding:8px 15px; border-top:1px solid #eee; text-align:center;">
                            <a href="{{ route('admin.contact') }}" style="color:#337ab7; font-size:13px;">
                                Xem tất cả liên hệ <i class="fa fa-arrow-right"></i>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>
    </div>
</div>
<!-- /top navigation -->
