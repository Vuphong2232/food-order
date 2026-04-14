<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cảm ơn bạn đã đặt hàng</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brown: {
                            50: '#FBF9F6',
                            100: '#F5EFE3',
                            200: '#E6DCC8',
                            300: '#D5C49A',
                            400: '#C4A868',
                            500: '#B0924E',
                            600: '#94783B',
                            700: '#8C7A63',
                            800: '#5C4A2A',
                            900: '#3D3021',
                            950: '#2A2012',
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        /* Vòng tròn tick animation */
        @keyframes drawCircle {
            0% { stroke-dashoffset: 314; }
            100% { stroke-dashoffset: 0; }
        }
        @keyframes drawCheck {
            0% { stroke-dashoffset: 50; }
            100% { stroke-dashoffset: 0; }
        }
        @keyframes scaleIn {
            0% { transform: scale(0); opacity: 0; }
            60% { transform: scale(1.15); }
            100% { transform: scale(1); opacity: 1; }
        }
        @keyframes fadeUp {
            0% { opacity: 0; transform: translateY(20px); }
            100% { opacity: 1; transform: translateY(0); }
        }
        @keyframes confettiFall {
            0% { transform: translateY(-10px) rotate(0deg); opacity: 1; }
            100% { transform: translateY(100vh) rotate(720deg); opacity: 0; }
        }

        .anim-circle {
            stroke-dasharray: 314;
            stroke-dashoffset: 314;
            animation: drawCircle 0.6s ease-out 0.3s forwards;
        }
        .anim-check {
            stroke-dasharray: 50;
            stroke-dashoffset: 50;
            animation: drawCheck 0.4s ease-out 0.8s forwards;
        }
        .anim-scale-in {
            animation: scaleIn 0.5s ease-out 0.2s both;
        }
        .anim-fade-up-1 { animation: fadeUp 0.5s ease-out 0.6s both; }
        .anim-fade-up-2 { animation: fadeUp 0.5s ease-out 0.8s both; }
        .anim-fade-up-3 { animation: fadeUp 0.5s ease-out 1.0s both; }
        .anim-fade-up-4 { animation: fadeUp 0.5s ease-out 1.2s both; }
        .anim-fade-up-5 { animation: fadeUp 0.5s ease-out 1.4s both; }

        .confetti-piece {
            position: fixed;
            top: -10px;
            width: 8px;
            height: 8px;
            border-radius: 2px;
            animation: confettiFall linear forwards;
            pointer-events: none;
            z-index: 100;
        }

        .timeline-dot::before {
            content: '';
            position: absolute;
            left: 15px;
            top: 36px;
            bottom: -12px;
            width: 2px;
            background: linear-gradient(to bottom, #C4A868, transparent);
        }
        .timeline-dot:last-child::before {
            display: none;
        }
    </style>
</head>
<body class="bg-brown-50 font-sans min-h-screen flex items-center justify-center p-4">

    <!-- Confetti container -->
    <div id="confettiContainer"></div>

    <!-- Main Card -->
    <div class="w-full max-w-lg">

        <!-- Card -->
        <div class="bg-white rounded-3xl shadow-xl shadow-brown-900/5 overflow-hidden">

            <!-- Top accent bar -->
            <div class="h-1.5 bg-gradient-to-r from-brown-400 via-brown-500 to-brown-600"></div>

            <div class="p-8 sm:p-10">

                <!-- Animated Check Icon -->
                <div class="flex justify-center mb-6 anim-scale-in">
                    <div class="relative">
                        <svg width="88" height="88" viewBox="0 0 88 88" fill="none">
                            <!-- Outer glow -->
                            <circle cx="44" cy="44" r="42" fill="#F5EFE3" opacity="0.5"/>
                            <!-- Circle -->
                            <circle cx="44" cy="44" r="38" stroke="#C4A868" stroke-width="3" fill="none" class="anim-circle"/>
                            <!-- Check -->
                            <path d="M28 44 L38 54 L60 32" stroke="#94783B" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round" fill="none" class="anim-check"/>
                        </svg>
                    </div>
                </div>

                <!-- Heading -->
                <div class="text-center anim-fade-up-1">
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight mb-2">
                        Đặt hàng thành công!
                    </h1>
                    <p class="text-gray-500 text-sm sm:text-base leading-relaxed">
                        Cảm ơn bạn đã tin tưởng và mua sắm cùng chúng tôi.
                    </p>
                </div>

                <!-- Order Info Card -->
                <div class="mt-6 bg-brown-50 rounded-2xl p-5 anim-fade-up-2">
                    <!-- Thay thế đoạn mã đơn hàng cũ bằng: -->
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-semibold text-gray-400 uppercase tracking-widest">Mã đơn hàng</span>
                        <span class="text-xs font-semibold text-brown-600 bg-brown-100 px-3 py-1 rounded-full">#{{ $orderCode }}</span>
                    </div>
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-semibold text-gray-400 uppercase tracking-widest">Ngày đặt</span>
                        <span class="text-sm text-gray-700 font-medium">{{ $orderDate }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold text-gray-400 uppercase tracking-widest">Tình trạng</span>
                        <span class="inline-flex items-center gap-1.5 text-sm font-semibold text-emerald-600">
                            <span class="relative flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                            </span>
                            Chờ xác nhận
                        </span>
                    </div>
                </div>

                <!-- Timeline -->
                <div class="mt-8 anim-fade-up-3">
                    <h3 class="text-sm font-semibold text-gray-900 mb-4">Quy trình xử lý đơn hàng</h3>
                    <div class="space-y-4">
                        <div class="relative flex items-start gap-4 timeline-dot">
                            <div class="flex-shrink-0 w-8 h-8 rounded-full bg-brown-600 flex items-center justify-center">
                                <iconify-icon icon="lucide:check" class="text-white text-sm"></iconify-icon>
                            </div>
                            <div class="pt-0.5">
                                <p class="text-sm font-semibold text-gray-900">Tiếp nhận đơn hàng</p>
                                <p class="text-xs text-gray-400 mt-0.5">Đơn hàng đã được ghi nhận thành công</p>
                            </div>
                        </div>
                        <div class="relative flex items-start gap-4 timeline-dot">
                            <div class="flex-shrink-0 w-8 h-8 rounded-full bg-brown-100 flex items-center justify-center">
                                <iconify-icon icon="lucide:package" class="text-brown-500 text-sm"></iconify-icon>
                            </div>
                            <div class="pt-0.5">
                                <p class="text-sm font-semibold text-gray-900">Chuẩn bị hàng</p>
                                <p class="text-xs text-gray-400 mt-0.5">Đang đóng gói sản phẩm</p>
                            </div>
                        </div>
                        <div class="relative flex items-start gap-4 timeline-dot">
                            <div class="flex-shrink-0 w-8 h-8 rounded-full bg-brown-100 flex items-center justify-center">
                                <iconify-icon icon="lucide:truck" class="text-brown-500 text-sm"></iconify-icon>
                            </div>
                            <div class="pt-0.5">
                                <p class="text-sm font-semibold text-gray-900">Giao hàng</p>
                                <p class="text-xs text-gray-400 mt-0.5">Đơn vị vận chuyển sẽ giao đến bạn</p>
                            </div>
                        </div>
                        <div class="relative flex items-start gap-4">
                            <div class="flex-shrink-0 w-8 h-8 rounded-full bg-brown-100 flex items-center justify-center">
                                <iconify-icon icon="lucide:badge-check" class="text-brown-500 text-sm"></iconify-icon>
                            </div>
                            <div class="pt-0.5">
                                <p class="text-sm font-semibold text-gray-900">Hoàn tất</p>
                                <p class="text-xs text-gray-400 mt-0.5">Giao hàng thành công</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Divider -->
                <div class="my-6 border-t border-gray-100 anim-fade-up-4"></div>

                <!-- Note -->
                <div class="flex items-start gap-3 bg-amber-50 rounded-xl p-4 anim-fade-up-4">
                    <iconify-icon icon="lucide:info" class="text-amber-500 text-lg flex-shrink-0 mt-0.5"></iconify-icon>
                    <p class="text-xs text-amber-700 leading-relaxed">
                        Chúng tôi sẽ gửi email xác nhận chi tiết đơn hàng đến địa chỉ email của bạn. 
                        Vui lòng kiểm tra hộp thư (kể cả thư rác) để không bỏ lỡ thông tin quan trọng.
                    </p>
                </div>

                <!-- Action Buttons -->
                <div class="mt-6 flex flex-col sm:flex-row gap-3 anim-fade-up-5">
                    <a href="{{ route('home') }}" class="flex-1 h-11 rounded-2xl border-2 border-gray-200 text-gray-600 font-semibold text-sm hover:bg-gray-50 transition-all duration-200 flex items-center justify-center gap-2">
                        <iconify-icon icon="lucide:home" class="text-base"></iconify-icon>
                        Về trang chủ
                    </a>
                    <a href="{{ route('orders.history') }}" class="flex-1 h-11 rounded-2xl bg-brown-600 text-white font-semibold text-sm hover:bg-brown-700 transition-all duration-200 shadow-lg shadow-brown-600/20 flex items-center justify-center gap-2">
                        <iconify-icon icon="lucide:package-search" class="text-base"></iconify-icon>
                        Theo dõi đơn
                    </a>
                </div>
            </div>
        </div>

        <!-- Footer text -->
        <p class="text-center text-xs text-gray-400 mt-6 anim-fade-up-5">
            Nếu cần hỗ trợ, vui lòng gọi 
            <a href="tel:1900xxxx" class="text-brown-500 font-semibold hover:underline">1900 xxxx</a>
            hoặc email 
            <a href="mailto:hotro@example.com" class="text-brown-500 font-semibold hover:underline">hotro@example.com</a>
        </p>
    </div>

    <script>
        // Confetti effect
        function createConfetti() {
            const container = document.getElementById('confettiContainer');
            const colors = ['#C4A868', '#94783B', '#E6DCC8', '#B0924E', '#F5EFE3', '#5C4A2A'];
            const shapes = ['rounded', 'rounded-full', 'rounded-none'];

            for (let i = 0; i < 40; i++) {
                const piece = document.createElement('div');
                piece.classList.add('confetti-piece');
                piece.style.left = Math.random() * 100 + 'vw';
                piece.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
                piece.style.borderRadius = shapes[Math.floor(Math.random() * shapes.length)];
                piece.style.width = (Math.random() * 8 + 4) + 'px';
                piece.style.height = (Math.random() * 8 + 4) + 'px';
                piece.style.animationDuration = (Math.random() * 2 + 2) + 's';
                piece.style.animationDelay = (Math.random() * 1.5) + 's';
                container.appendChild(piece);
            }

            // Dọn dẹp confetti sau animation
            setTimeout(() => {
                container.innerHTML = '';
            }, 5000);
        }

        // Chạy confetti khi trang load
        window.addEventListener('load', () => {
            setTimeout(createConfetti, 800);
        });
    </script>
</body>
</html>