@include('clients.blocks.header')

<style>
.profile-page { background: linear-gradient(135deg, #f5f7fa 0%, #e8edf5 100%); min-height: 90vh; padding: 50px 0 80px; }

/* Profile header card */
.profile-hero {
    background: linear-gradient(135deg, #1a3a5c, #0d2240);
    border-radius: 20px;
    padding: 36px 32px;
    color: #fff;
    display: flex;
    align-items: center;
    gap: 28px;
    margin-bottom: 28px;
    position: relative;
    overflow: hidden;
}
.profile-hero::before {
    content: '';
    position: absolute;
    right: -40px; top: -40px;
    width: 200px; height: 200px;
    background: rgba(255,255,255,.06);
    border-radius: 50%;
}
.profile-hero::after {
    content: '';
    position: absolute;
    right: 60px; bottom: -60px;
    width: 150px; height: 150px;
    background: rgba(255,255,255,.04);
    border-radius: 50%;
}
.profile-avatar-wrap {
    position: relative;
    flex-shrink: 0;
}
.profile-avatar-wrap img {
    width: 100px; height: 100px;
    border-radius: 50%;
    object-fit: cover;
    border: 4px solid rgba(255,255,255,.3);
    display: block;
}
.avatar-upload-btn {
    position: absolute;
    bottom: 2px; right: 2px;
    background: #ffcc02;
    border: none;
    border-radius: 50%;
    width: 28px; height: 28px;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer;
    font-size: 13px;
    color: #1a3a5c;
    transition: transform .2s;
    padding: 0;
}
.avatar-upload-btn:hover { transform: scale(1.15); }
.profile-hero-info h3 { font-size: 22px; font-weight: 800; margin: 0 0 4px; }
.profile-hero-info p  { margin: 0; opacity: .75; font-size: 14px; }
.profile-hero-stats { margin-left: auto; display: flex; gap: 24px; z-index: 1; }
.profile-stat { text-align: center; }
.profile-stat .num { font-size: 24px; font-weight: 800; }
.profile-stat .lbl { font-size: 12px; opacity: .7; }

/* Section cards */
.profile-card {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 2px 16px rgba(0,0,0,.06);
    overflow: hidden;
    margin-bottom: 20px;
}
.profile-card-header {
    padding: 18px 24px;
    border-bottom: 1px solid #f0f0f0;
    font-size: 15px;
    font-weight: 700;
    color: #1a3a5c;
    display: flex;
    align-items: center;
    gap: 8px;
}
.profile-card-body { padding: 24px; }

/* Form inputs */
.pf-group { margin-bottom: 20px; }
.pf-group label { font-size: 13px; font-weight: 600; color: #555; margin-bottom: 6px; display: block; }
.pf-group input {
    width: 100%;
    padding: 11px 16px;
    border: 1.5px solid #e0e0e0;
    border-radius: 10px;
    font-size: 15px;
    color: #333;
    transition: border-color .2s, box-shadow .2s;
    outline: none;
}
.pf-group input:focus {
    border-color: #1a3a5c;
    box-shadow: 0 0 0 3px rgba(26,58,92,.08);
}
.pf-group input[disabled], .pf-group input[readonly] {
    background: #f8f9fa;
    color: #aaa;
}

.pf-btn {
    padding: 11px 28px;
    border-radius: 25px;
    border: none;
    font-size: 14px;
    font-weight: 700;
    cursor: pointer;
    transition: all .2s;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}
.pf-btn.primary   { background: linear-gradient(135deg, #1a3a5c, #0d2240); color: #fff; }
.pf-btn.primary:hover { opacity: .9; transform: translateY(-1px); }
.pf-btn.outline   { background: #fff; color: #1a3a5c; border: 2px solid #1a3a5c; }
.pf-btn.outline:hover { background: #1a3a5c; color: #fff; }
.pf-btn.danger    { background: #fff; color: #d32f2f; border: 2px solid #d32f2f; }
.pf-btn.danger:hover { background: #d32f2f; color: #fff; }

/* Password toggle */
.pf-group.collapsible { display: none; }
.pf-group.collapsible.show { display: block; }

@media (max-width: 768px) {
    .profile-hero { flex-direction: column; text-align: center; }
    .profile-hero-stats { margin-left: 0; margin-top: 16px; }
}
</style>

<div class="profile-page">
    <div class="container">

        {{-- Profile Hero --}}
        <div class="profile-hero">
            <div class="profile-avatar-wrap">
                <img id="avatarPreview"
                    src="{{ asset('admin/assets/images/user-profile/' . ($user->avatar ?? 'default.png')) }}"
                    alt="Avatar">
                <label for="avatarInput" class="avatar-upload-btn" title="Thay đổi ảnh">
                    📷
                </label>
                <input type="file" id="avatarInput" name="avatar" style="display:none" accept="image/*">
                <input type="hidden" name="_token" value="{{ csrf_token() }}" class="__token">
                <input type="hidden" value="{{ route('change-avatar') }}" class="label_avatar">
            </div>
            <div class="profile-hero-info">
                <h3>{{ $user->fullName ?? 'Người dùng' }}</h3>
                <p>📧 {{ $user->email }}</p>
                <p>📍 {{ $user->address ?? 'Chưa cập nhật địa chỉ' }}</p>
            </div>
            <div class="profile-hero-stats">
                <div class="profile-stat">
                    <div class="num">{{ $tourCount ?? 0 }}</div>
                    <div class="lbl">Tour đã đặt</div>
                </div>
                <div class="profile-stat">
                    <div class="num">{{ $completedCount ?? 0 }}</div>
                    <div class="lbl">Đã hoàn thành</div>
                </div>
            </div>
        </div>

        <div class="row">
            {{-- Left: Quick links --}}
            <div class="col-lg-4 col-md-5">
                <div class="profile-card">
                    <div class="profile-card-header">🗂️ Tài khoản của tôi</div>
                    <div class="profile-card-body" style="padding: 12px 0;">
                        <a href="{{ route('my-tours') }}"
                            style="display:flex;align-items:center;gap:12px;padding:14px 24px;color:#333;text-decoration:none;transition:background .15s;"
                            onmouseover="this.style.background='#f5f7fa'" onmouseout="this.style.background='transparent'">
                            <span style="font-size:20px;">📋</span>
                            <span style="font-weight:600;">Tour đã đặt</span>
                            <i class="fal fa-chevron-right" style="margin-left:auto;color:#aaa;"></i>
                        </a>
                        <a href="{{ route('my-wishlist') }}"
                            style="display:flex;align-items:center;gap:12px;padding:14px 24px;color:#333;text-decoration:none;transition:background .15s;"
                            onmouseover="this.style.background='#f5f7fa'" onmouseout="this.style.background='transparent'">
                            <span style="font-size:20px;">❤️</span>
                            <span style="font-weight:600;">Danh sách yêu thích</span>
                            <i class="fal fa-chevron-right" style="margin-left:auto;color:#aaa;"></i>
                        </a>
                        <a href="{{ route('logout') }}"
                            style="display:flex;align-items:center;gap:12px;padding:14px 24px;color:#d32f2f;text-decoration:none;transition:background .15s;"
                            onmouseover="this.style.background='#fff5f5'" onmouseout="this.style.background='transparent'">
                            <span style="font-size:20px;">🚪</span>
                            <span style="font-weight:600;">Đăng xuất</span>
                            <i class="fal fa-chevron-right" style="margin-left:auto;color:#aaa;"></i>
                        </a>
                    </div>
                </div>
            </div>

            {{-- Right: Edit form --}}
            <div class="col-lg-8 col-md-7">

                {{-- Info form --}}
                <div class="profile-card">
                    <div class="profile-card-header">✏️ Chỉnh sửa thông tin</div>
                    <div class="profile-card-body">
                        <form action="{{ route('update-user-profile') }}" method="POST" name="updateUser" class="updateUser">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="pf-group">
                                        <label>Họ và tên <span style="color:#d32f2f">*</span></label>
                                        <input type="text" id="inputFullName" placeholder="Họ và tên"
                                            value="{{ $user->fullName }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="pf-group">
                                        <label>Số điện thoại</label>
                                        <input type="text" id="inputPhone" placeholder="Số điện thoại"
                                            value="{{ $user->phoneNumber }}">
                                    </div>
                                </div>
                            </div>
                            <div class="pf-group">
                                <label>Email</label>
                                <input type="email" id="inputEmailAddress" placeholder="Email"
                                    value="{{ $user->email }}" readonly>
                            </div>
                            <div class="pf-group">
                                <label>Địa chỉ</label>
                                <input type="text" id="inputLocation" placeholder="Địa chỉ"
                                    value="{{ $user->address }}">
                            </div>
                            <button type="submit" class="pf-btn primary" id="update_profile">
                                <i class="fal fa-save"></i> Lưu thay đổi
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Password form --}}
                <div class="profile-card">
                    <div class="profile-card-header">🔐 Đổi mật khẩu</div>
                    <div class="profile-card-body">
                        <div id="card_change_password">
                            <form action="{{ route('change-password') }}" method="post" class="change_password_profile">
                                @csrf
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="pf-group">
                                            <label>Mật khẩu cũ</label>
                                            <input type="password" id="inputOldPass" placeholder="Mật khẩu hiện tại" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="pf-group">
                                            <label>Mật khẩu mới</label>
                                            <input type="password" id="inputNewPass" placeholder="Mật khẩu mới" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4 d-flex align-items-end">
                                        <div class="pf-group" style="width:100%">
                                            <label>&nbsp;</label>
                                            <button type="submit" class="pf-btn primary" style="width:100%">
                                                <i class="fal fa-key"></i> Đổi mật khẩu
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="invalid-feedback" id="validate_password"></div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

@include('clients.blocks.footer')
