<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use App\Mail\ForgotPasswordOtpMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class AuthController extends Controller
{
    // --- ĐĂNG KÝ ---
    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        // 1. Validate: Yêu cầu BẮT BUỘC phải có username
        $request->validate([
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        // 2. TẠO USER - TRUYỀN ĐẦY ĐỦ DỮ LIỆU
        $user = User::create([
            'username' => $request->username,  
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // 3. Đăng nhập luôn
        Auth::login($user);
        $request->session()->regenerate();
        $request->session()->forget('admin_mode');

        return redirect()->route('home')->with('success', 'Đăng ký thành công!');
    }

    // --- ĐĂNG NHẬP ---
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        $loginField = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        
        if (Auth::attempt([$loginField => $request->login, 'password' => $request->password])) {
            $request->session()->regenerate();
            $request->session()->forget('admin_mode');
            return redirect()->intended(route('home'));
        }

        return back()->withErrors([
            'login' => 'Thông tin đăng nhập không chính xác.',
        ])->onlyInput('login');
    }

    public function logout(Request $request)
    {
        $request->session()->forget('admin_mode');

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    // --- QUÊN MẬT KHẨU ---
    // --- QUÊN MẬT KHẨU ---
public function showForgotPassword()
{
    return view('auth.forgot-password');
}

public function forgotPassword(Request $request)
{
    $request->validate([
        'email' => 'required|email'
    ]);

    $user = User::where('email', $request->email)->first();

    if (!$user) {
        return back()->withErrors([
            'email' => 'Email không tồn tại trong hệ thống'
        ])->withInput();
    }

    $code = (string) random_int(100000, 999999);

    DB::table('password_reset_tokens')->where('email', $request->email)->delete();

    DB::table('password_reset_tokens')->insert([
        'email' => $request->email,
        'code' => $code,
        'expires_at' => Carbon::now()->addMinutes(10),
        'is_used' => false,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    Mail::to($request->email)->send(new ForgotPasswordOtpMail($code, $request->email));

    session([
        'password_reset_email' => $request->email
    ]);

    return redirect()->route('password.verify.form')
        ->with('success', 'Mã xác nhận đã được gửi về email của bạn.');
}

public function showVerifyCodeForm()
{
    if (!session('password_reset_email')) {
        return redirect()->route('password.request');
    }

    return view('auth.verify-code');
}

public function verifyCode(Request $request)
{
    $request->validate([
        'code' => 'required|digits:6'
    ]);

    $email = session('password_reset_email');

    if (!$email) {
        return redirect()->route('password.request')
            ->withErrors(['email' => 'Phiên đặt lại mật khẩu đã hết.']);
    }

    $otp = DB::table('password_reset_tokens')
        ->where('email', $email)
        ->where('code', $request->code)
        ->where('is_used', false)
        ->first();

    if (!$otp) {
        return back()->withErrors([
            'code' => 'Mã xác nhận không đúng'
        ])->withInput();
    }

    if (Carbon::parse($otp->expires_at)->isPast()) {
        return back()->withErrors([
            'code' => 'Mã xác nhận đã hết hạn'
        ]);
    }

    session([
        'password_reset_verified' => true
    ]);

    return redirect()->route('password.reset.form')
        ->with('success', 'Xác nhận mã thành công. Vui lòng nhập mật khẩu mới.');
}

public function showResetPasswordForm()
{
    if (!session('password_reset_email') || !session('password_reset_verified')) {
        return redirect()->route('password.request');
    }

    return view('auth.reset-password');
}

public function resetPassword(Request $request)
{
    $request->validate([
        'password' => 'required|string|min:6|confirmed',
    ]);

    $email = session('password_reset_email');

    if (!$email || !session('password_reset_verified')) {
        return redirect()->route('password.request')
            ->withErrors(['email' => 'Phiên đặt lại mật khẩu không hợp lệ.']);
    }

    $user = User::where('email', $email)->first();

    if (!$user) {
        return redirect()->route('password.request')
            ->withErrors(['email' => 'Không tìm thấy tài khoản.']);
    }

    $user->update([
        'password' => Hash::make($request->password)
    ]);

    DB::table('password_reset_tokens')
        ->where('email', $email)
        ->update([
            'is_used' => true,
            'updated_at' => now()
        ]);

    session()->forget([
        'password_reset_email',
        'password_reset_verified'
    ]);

    return redirect()->route('login')
        ->with('success', 'Đặt lại mật khẩu thành công. Vui lòng đăng nhập lại.');
}

    public function profileInfo()
{
    return view('profile.info');
}

    public function profileSecurity()
    {
        return view('profile.security');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        $user = auth()->user();

        if (!Hash::check($request->old_password, $user->password)) {
            return back()->withErrors(['old_password' => 'Mật khẩu cũ không đúng']);
        }

        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return back()->with('success', 'Đổi mật khẩu thành công');
    }
}