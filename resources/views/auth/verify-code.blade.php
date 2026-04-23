<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác nhận mã OTP</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.iconify.design/3/3.1.0/iconify.min.js"></script>
</head>
<body class="min-h-screen bg-[#FFFCF7] relative overflow-hidden">

    <div class="absolute inset-0 opacity-40"
         style="background-image: radial-gradient(#d6b89a 1px, transparent 1px); background-size: 20px 20px;">
    </div>

    <div class="relative min-h-screen flex items-center justify-center px-4 py-10">
        <div class="w-full max-w-5xl bg-white rounded-[36px] shadow-[0_20px_60px_rgba(0,0,0,0.08)] overflow-hidden grid grid-cols-1 lg:grid-cols-2">

            <div class="relative hidden lg:flex items-center justify-center p-10">
                <img src="https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?q=80&w=1200&auto=format&fit=crop"
                     alt="OTP"
                     class="absolute inset-0 w-full h-full object-cover opacity-25">
                <div class="absolute inset-0 bg-white/55"></div>

                <div class="relative z-10 text-center max-w-md">
                    <div class="w-24 h-24 mx-auto rounded-[28px] bg-orange-500 text-white flex items-center justify-center shadow-lg mb-8">
                        <span class="iconify text-[44px]" data-icon="lucide:shield-check"></span>
                    </div>

                    <h2 class="text-[34px] font-extrabold text-black leading-tight mb-4">
                        Xác nhận mã OTP
                    </h2>

                    <p class="text-gray-700 text-[18px] leading-8">
                        Chúng tôi đã gửi mã xác nhận đến email của bạn. Hãy nhập mã để tiếp tục đổi mật khẩu.
                    </p>
                </div>
            </div>

            <div class="relative p-8 md:p-12 lg:p-14 flex items-center">
                <a href="{{ route('login') }}"
                   class="absolute top-6 right-6 text-black hover:text-orange-500 transition">
                    <span class="iconify text-[28px]" data-icon="lucide:x"></span>
                </a>

                <div class="w-full max-w-md mx-auto">
                    <h1 class="text-[42px] font-extrabold text-black leading-tight mb-3">
                        Nhập mã xác nhận <span class="inline-block">📩</span>
                    </h1>

                    <p class="text-gray-600 text-lg mb-2">
                        Vui lòng nhập mã OTP đã được gửi đến:
                    </p>

                    <p class="text-orange-500 font-semibold text-base mb-8 break-all">
                        {{ session('password_reset_email') }}
                    </p>

                    @if(session('success'))
                        <div class="mb-5 rounded-2xl border border-green-200 bg-green-50 px-4 py-3 text-green-700">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="mb-5 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-red-700">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form action="{{ route('password.verify') }}" method="POST" class="space-y-6">
                        @csrf

                        <div>
                            <label class="block text-[15px] font-semibold text-gray-900 mb-3">
                                Mã OTP
                            </label>

                            <div class="flex items-center gap-3 border-b border-gray-200 pb-3">
                                <span class="iconify text-[22px] text-gray-700" data-icon="lucide:key-round"></span>
                                <input type="text"
                                       name="code"
                                       maxlength="6"
                                       required
                                       value="{{ old('code') }}"
                                       placeholder="Nhập mã 6 số"
                                       class="w-full bg-transparent outline-none text-[22px] tracking-[6px] text-gray-800 placeholder:text-gray-400">
                            </div>
                        </div>

                        <button type="submit"
                                class="w-full h-14 rounded-2xl bg-orange-500 hover:bg-orange-600 text-white text-xl font-bold shadow-[0_12px_24px_rgba(249,115,22,0.25)] transition-all">
                            Xác nhận mã <span class="ml-1">✔️</span>
                        </button>
                    </form>

                    <div class="mt-8 text-center">
                        <a href="{{ route('password.request') }}" class="text-orange-500 font-semibold hover:underline">
                            ← Quay lại nhập email
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</body>
</html>