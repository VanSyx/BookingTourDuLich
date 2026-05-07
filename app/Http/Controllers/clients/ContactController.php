<?php

namespace App\Http\Controllers\clients;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ContactController extends Controller
{
    public function index()
    {
        $title = 'Liên hệ';
        return view('clients.contact', compact('title'));
    }

    public function createContact(Request $req){
        $req->validate([
            'name'         => 'required|string|max:100',
            'phone_number' => 'required|string|max:20',
            'email'        => 'required|email|max:100',
            'message'      => 'required|string',
        ]);

        $dataContact = [
            'name'        => $req->name,
            'phoneNumber' => $req->phone_number,
            'email'       => $req->email,
            'message'     => $req->message,
            'isReply'     => 'n',
        ];

        $createContact = DB::table('tbl_contact')->insert($dataContact);

        if($createContact){
            toastr()->success('Gửi thành công. Chúng tôi sẽ sớm liên hệ tới bạn!');
        }else{
            toastr()->error('Có lỗi xảy ra. Xin vui lòng thử lại');
        }
        return redirect()->back();
    }
}
