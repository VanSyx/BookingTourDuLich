<?php

namespace App\Http\Controllers\clients;

use App\Http\Controllers\Controller;
use App\Models\clients\Tours;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    private $tours;

    public function __construct()
    {
        parent::__construct();
        $this->tours = new Tours();
    }

    public function index(Request $request)
    {
        $userId = $this->getUserId();
        if (!$userId) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để xem danh sách yêu thích!');
        }

        $myWishlists = $this->tours->getWishlistByUser($userId);
        $toursPopular = $this->tours->toursPopular(4);
        $title = "Danh sách yêu thích";

        return view('clients.wishlist', compact('title', 'myWishlists', 'toursPopular'));
    }

    /**
     * Toggle wishlist (thêm hoặc xoá).
     * Yêu cầu đăng nhập → trả JSON lỗi nếu chưa login (không redirect để AJAX xử lý được).
     */
    public function toggle(Request $request)
    {
        // Kiểm tra auth qua session (giống middleware checkLoginClient)
        if (!$request->session()->has('username')) {
            return response()->json([
                'success'  => false,
                'auth'     => false,
                'message'  => 'Vui lòng đăng nhập để thêm vào danh sách yêu thích.',
            ], 401);
        }

        $tourId = $request->input('tourId');
        if (!$tourId || !is_numeric($tourId)) {
            return response()->json([
                'success' => false,
                'message' => 'Tour không hợp lệ.',
            ], 422);
        }

        $userId = $this->getUserId();
        if (!$userId) {
            return response()->json([
                'success'  => false,
                'auth'     => false,
                'message'  => 'Vui lòng đăng nhập để thêm vào danh sách yêu thích.',
            ], 401);
        }

        try {
            $action = $this->tours->toggleWishlist((int) $tourId, $userId);
            $message = $action === 'added'
                ? 'Đã thêm tour vào danh sách yêu thích!'
                : 'Đã xoá tour khỏi danh sách yêu thích.';

            return response()->json([
                'success' => true,
                'action'  => $action,  // 'added' | 'removed'
                'message' => $message,
            ]);
        } catch (\Exception $e) {
            \Log::error('Wishlist toggle error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra. Vui lòng thử lại sau.',
            ], 500);
        }
    }
}
