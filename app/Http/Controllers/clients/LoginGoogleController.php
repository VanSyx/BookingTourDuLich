<?php

namespace App\Http\Controllers\clients;

use App\Http\Controllers\Controller;
use App\Models\clients\Login;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class LoginGoogleController extends Controller
{

    protected $user;
    public function __construct()
    {
        $this->user = new Login();
    }

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $user = Socialite::driver('google')->stateless()->user();
            $finduser = $this->user->checkUserExistGoogle($user->id); //Kiểm tra xem thử có id google chưa
            
            if ($finduser) {
                session()->put('username', $finduser->username);
                return redirect()->intended('/');
            } else {
                // KIỂM TRA PHÒNG NGỪA CỤNG ĐỘ DỮ LIỆU EMAIL
                // Nếu email người dùng đã từng đăng ký tài khoản thường trước đó, chúng ta không tạo mới (sẽ gây lỗi SQL do email bị khoá UNIQUE) mà chỉ cập nhật lại google_id
                $existUserByEmail = \Illuminate\Support\Facades\DB::table('tbl_users')->where('email', $user->email)->first();
                if ($existUserByEmail) {
                    \Illuminate\Support\Facades\DB::table('tbl_users')->where('userId', $existUserByEmail->userId)->update(['google_id' => $user->id]);
                    session()->put('username', $existUserByEmail->username);
                    return redirect()->intended('/');
                }
                $data_google = [
                    'google_id' => $user->id,
                    'fullName' => $user->name,
                    'username' => 'user-google-' . time(), // Nối thêm timestamp
                    'password' => md5('12345678'),
                    'email' => $user->email,
                    'isActive' => 'y'   
                ];
                $newUser = $this->user->registerAcount($data_google);
                // Kiểm tra xem $newUser có hợp lệ không
                if ($newUser && isset($newUser->username)) {
                    // Lưu thông tin người dùng mới vào session
                    session()->put('username', $newUser->username);
                    return redirect()->intended('/');
                } else {
                    // Nếu có lỗi khi đăng ký người dùng mới, xử lý lỗi
                    return redirect()->back()->with('error', 'Có lỗi xảy ra trong quá trình đăng ký người dùng mới');
                }
            }
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Đăng nhập Google thất bại: ' . $e->getMessage());
        }
    }
}
