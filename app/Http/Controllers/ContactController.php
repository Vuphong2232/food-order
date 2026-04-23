<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Category;

class ContactController extends Controller
{
    /**
     * Hiển thị trang thông tin liên hệ
     */
    public function index()
    {
        // Lấy thông tin user đang đăng nhập để truyền sang view (dùng cho sidebar)
        $user = Auth::user();

        $categories = Category::all(); 
        
        return view('contact', compact('user', 'categories'));
    }

    /**
     * Xử lý gửi form liên hệ
     */
    public function submit(Request $request)
    {
        // 1. Validate dữ liệu đầu vào
        $validated = $request->validate([
            'fullName' => 'required|string|max:255',
            'email'    => 'required|email|max:255',
            'phone'    => 'required|string|max:20',
            'subject'  => 'required|string',
            'message'  => 'required|string|min:10',
        ]);

        // 2. Logic xử lý (Lưu vào DB hoặc gửi Email)
        // Ví dụ lưu vào database:
        // \App\Models\ContactMessage::create($validated);
        
        // Ví dụ gửi email (cần cấu hình mail trước):
        // Mail::to('support@monngon.vn')->send(new \App\Mail\ContactFormSubmitted($validated));

        // 3. Trả về response (dùng JSON để AJAX xử lý hoặc redirect)
        return response()->json([
            'success' => true,
            'message' => 'Chúng tôi đã nhận được tin nhắn của bạn!'
        ]);
    }
}