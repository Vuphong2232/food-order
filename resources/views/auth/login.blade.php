<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập | Hương Vị Tuyệt Hảo</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Iconify -->
    <script src="https://code.iconify.design/3/3.1.0/iconify.min.js"></script>
    
    <style>
        body { font-family: 'Inter', sans-serif; }
        
        /* Họa tiết nền chấm bi nhẹ nhàng */
        .bg-pattern {
            background-color: #fdfbf7; /* Màu nấu kem rất nhạt (brown-50) */
            background-image: radial-gradient(#e7dcc8 1px, transparent 1px);
            background-size: 24px 24px;
        }

        /* Hiệu ứng floating nhẹ cho ảnh minh họa */
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }
        .animate-float {
            animation: float 4s ease-in-out infinite;
        }
    </style>
</head>
<body class="bg-pattern min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-5xl bg-white rounded-[2.5rem] shadow-2xl shadow-brown-200/50 overflow-hidden flex flex-col md:flex-row min-h-[600px]">
        
        <!-- CỘT TRÁI: Hình ảnh minh họa (Ẩn trên Mobile, hiện trên Desktop) -->
        <div class="hidden md:flex md:w-1/2 bg-brown-100 relative overflow-hidden items-center justify-center p-12">
            <!-- Hình nền ăn mờ -->
            <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1504674900247-0877df9cc836?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80')] bg-cover bg-center opacity-20 mix-blend-multiply"></div>
            
            <!-- Nội dung bên trái -->
            <div class="relative z-10 text-center animate-float">
                <div class="w-24 h-24 bg-brown-600 rounded-3xl mx-auto flex items-center justify-center shadow-lg mb-6 transform rotate-3">
                    <span class="iconify text-5xl text-white" data-icon="lucide:utensils"></span>
                </div>
                <h2 class="text-4xl font-bold text-brown-900 mb-4">Thưởng thức<br>mỗi ngày</h2>
                <p class="text-brown-600 font-medium">Truy cập hàng ngàn món ngon hấp dẫn chỉ với một cú click.</p>
            </div>
        </div>

        <!-- CỘT PHẢI: Form Đăng Nhập -->
        <div class="w-full md:w-1/2 p-8 md:p-16 flex flex-col justify-center relative">
            
            <!-- Nút đóng (X) chỉ mang tính trang trí hoặc về trang chủ nếu cần -->
            <a href="#" class="absolute top-6 right-6 text-brown-300 hover:text-brown-500 transition-colors">
                <span class="iconify text-2xl" data-icon="lucide:x"></span>
            </a>

            <div class="mb-10">
                <h1 class="text-3xl font-bold text-brown-900 mb-2">Chào mừng trở lại! 👋</h1>
                <p class="text-brown-500">Vui lòng nhập thông tin để tiếp tục.</p>
            </div>

            @if ($errors->any())
                <div class="bg-red-50 text-red-600 p-4 rounded-2xl text-sm mb-6 flex items-center gap-3 border border-red-100">
                    <span class="iconify text-xl" data-icon="lucide:alert-circle"></span>
                    <span>{{ $errors->first('login') ?? 'Thông tin đăng nhập không chính xác.' }}</span>
                </div>
            @endif

            <!-- FORM -->
            <form action="/login" method="POST" class="space-y-5">
                @csrf
                
                <!-- Input Tài khoản / Email -->
                <div class="space-y-1.5">
                    <label class="text-sm font-semibold text-brown-700 ml-1">Tài khoản hoặc Email</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <span class="iconify text-brown-400 text-lg group-focus-within:text-brown-600 transition-colors" data-icon="lucide:mail"></span>
                        </div>
                        <input type="text" name="login" value="{{ old('login') }}" required
                            class="w-full pl-11 pr-4 py-3.5 bg-brown-50/50 border-2 border-transparent rounded-2xl text-brown-900 placeholder:text-brown-300 focus:bg-white focus:border-brown-500 focus:outline-none transition-all duration-200"
                            placeholder="example@gmail.com">
                    </div>
                </div>

                <!-- Input Mật khẩu -->
                <div class="space-y-1.5">
                    <div class="flex justify-between items-center ml-1">
                        <label class="text-sm font-semibold text-brown-700">Mật khẩu</label>
                        <a href="{{ route('password.request') }}" class="text-xs font-semibold text-brown-500 hover:text-brown-700 transition-colors">Quên mật khẩu?</a>
                    </div>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <span class="iconify text-brown-400 text-lg group-focus-within:text-brown-600 transition-colors" data-icon="lucide:lock"></span>
                        </div>
                        <input type="password" name="password" required
                            class="w-full pl-11 pr-4 py-3.5 bg-brown-50/50 border-2 border-transparent rounded-2xl text-brown-900 placeholder:text-brown-300 focus:bg-white focus:border-brown-500 focus:outline-none transition-all duration-200"
                            placeholder="••••••••">
                    </div>
                </div>

                <!-- Nút Đăng Nhập -->
                <button type="submit" 
                        class="w-full py-4 rounded-2xl font-bold text-white shadow-lg transform active:scale-[0.98] transition-all duration-200 flex items-center justify-center gap-2 mt-4 hover:brightness-110"
                        style="background-color: #e67e22; box-shadow: 0 10px 15px -3px rgba(230, 126, 34, 0.3);">
                    <span>Đăng Nhập</span>
                    <span class="iconify" data-icon="lucide:arrow-right"></span>
                </button>
            </form>

            <!-- Footer -->
            <div class="mt-8 text-center text-sm text-brown-500">
                Chưa có tài khoản? 
                <a href="{{ route('register') }}" class="text-brown-600 font-bold hover:underline">Đăng ký ngay</a>
            </div>

            <!-- Social Login (Decor) -->
            <div class="mt-8">
                <div class="relative flex py-2 items-center">
                    <div class="flex-grow border-t border-brown-100"></div>
                    <span class="flex-shrink-0 mx-4 text-brown-300 text-xs">HOẶC ĐĂNG NHẬP VỚI</span>
                    <div class="flex-grow border-t border-brown-100"></div>
                </div>
                <div class="flex justify-center gap-4 mt-4">
                    <button class="w-12 h-12 rounded-2xl border border-brown-100 flex items-center justify-center hover:bg-brown-50 transition-colors">
                        <span class="iconify text-2xl text-brown-700" data-icon="logos:google-icon"></span>
                    </button>
                    <button class="w-12 h-12 rounded-2xl border border-brown-100 flex items-center justify-center hover:bg-brown-50 transition-colors">
                        <span class="iconify text-2xl text-brown-700" data-icon="logos:facebook"></span>
                    </button>
                </div>
            </div>

        </div>
    </div>

</body>
</html>