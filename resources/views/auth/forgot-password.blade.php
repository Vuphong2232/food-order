<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quên Mật Khẩu | Hương Vị Tuyệt Hảo</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://code.iconify.design/3/3.1.0/iconify.min.js"></script>
    
    <style>
        body { font-family: 'Inter', sans-serif; }
        .bg-pattern {
            background-color: #fdfbf7;
            background-image: radial-gradient(#e7dcc8 1px, transparent 1px);
            background-size: 24px 24px;
        }
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
        
        <!-- CỘT TRÁI: Hình ảnh minh họa -->
        <div class="hidden md:flex md:w-1/2 bg-brown-100 relative overflow-hidden items-center justify-center p-12">
            <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1555664424-778a18a32c5a?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80')] bg-cover bg-center opacity-20 mix-blend-multiply"></div>
            
            <div class="relative z-10 text-center animate-float">
                <div class="w-24 h-24 bg-orange-400 rounded-full mx-auto flex items-center justify-center shadow-lg mb-6">
                    <span class="iconify text-5xl text-white" data-icon="lucide:lock-open"></span>
                </div>
                <h2 class="text-4xl font-bold text-brown-900 mb-4">Quên mật khẩu?</h2>
                <p class="text-brown-600 font-medium">Đừng lo lắng, chúng tôi sẽ gửi cho bạn hướng dẫn để đặt lại.</p>
            </div>
        </div>

        <!-- CỘT PHẢI: Form Quên Mật Khẩu -->
        <div class="w-full md:w-1/2 p-8 md:p-16 flex flex-col justify-center relative">
            <a href="{{ route('login') }}" class="absolute top-6 right-6 text-brown-300 hover:text-brown-500 transition-colors">
                <span class="iconify text-2xl" data-icon="lucide:x"></span>
            </a>

            <div class="mb-8">
                <h1 class="text-3xl font-bold text-brown-900 mb-2">Đặt lại mật khẩu 🔒</h1>
                <p class="text-brown-500">Nhập email liên kết với tài khoản của bạn.</p>
            </div>

            @if(session('success'))
                <div class="bg-green-50 text-green-700 p-4 rounded-2xl text-sm mb-6 flex items-center gap-3 border border-green-100">
                    <span class="iconify text-xl" data-icon="lucide:check-circle-2"></span>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <form action="{{ route('password.email') }}" method="POST" class="space-y-5">
                @csrf

                <!-- Email -->
                <div class="space-y-1.5">
                    <label class="text-sm font-semibold text-brown-700 ml-1">Địa chỉ Email</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <span class="iconify text-brown-400 text-lg group-focus-within:text-orange-500 transition-colors" data-icon="lucide:mail"></span>
                        </div>
                        <input type="email" name="email" required
                            class="w-full pl-11 pr-4 py-3.5 bg-brown-50/50 border-2 border-transparent rounded-2xl text-brown-900 placeholder:text-brown-300 focus:bg-white focus:border-orange-500 focus:outline-none transition-all duration-200"
                            placeholder="email@example.com">
                    </div>
                </div>

                <!-- Nút Gửi Link -->
                <button type="submit" 
                        class="w-full py-4 rounded-2xl font-bold text-white shadow-lg transform active:scale-[0.98] transition-all duration-200 flex items-center justify-center gap-2 mt-4"
                        style="background-color: #e67e22; box-shadow: 0 10px 15px -3px rgba(230, 126, 34, 0.3);">
                    <span>Gửi liên kết đặt lại</span>
                    <span class="iconify" data-icon="lucide:send"></span>
                </button>
            </form>

            <div class="mt-8 text-center text-sm text-brown-500">
                <a href="{{ route('login') }}" class="text-orange-600 font-bold hover:underline flex items-center justify-center gap-1">
                    <span class="iconify" data-icon="lucide:arrow-left"></span> Quay lại đăng nhập
                </a>
            </div>
        </div>
    </div>

</body>
</html>