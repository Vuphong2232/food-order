<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Món Ngon')</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brown: {
                            50:'#FFF8F0',100:'#F5E6D3',200:'#E8CBA7',300:'#D4A574',
                            400:'#C08B52',500:'#A0714A',600:'#8B5E3C',700:'#6B4423',
                            800:'#4A2E17',900:'#3B2313',950:'#2A1A0E'
                        },
                        cream: '#FFFCF7'
                    },
                    fontFamily: {
                        sans: ['Inter','sans-serif'],
                        serif: ['Playfair Display','serif']
                    }
                }
            }
        }
    </script>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700;900&display=swap" rel="stylesheet">
    <script src="https://code.iconify.design/3/3.1.0/iconify.min.js"></script>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body class="flex flex-col min-h-screen bg-cream font-sans text-brown-950">

    @yield('toast')
    @yield('cart')

    <div class="flex flex-col min-h-screen">
        @yield('sidebar')

        <div class="flex flex-col flex-1 ml-64 transition-all duration-300">
            @yield('header')
            <main class="flex-1">
                @yield('content')
            </main>
            @yield('footer')
        </div>
    </div>

    @yield('modal')
    <script>
    window.bestSellerIds = @json($bestSellerIds ?? []);
</script>
    <script src="{{ asset('js/app.js') }}"></script>
    @stack('scripts')

    <!-- DELETE CONFIRM MODAL -->
        <div id="confirmDeleteModal" class="fixed inset-0 z-[999] flex items-center justify-center opacity-0 pointer-events-none transition-all duration-300">
            
            <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeDeleteModal()"></div>

            <div id="confirmDeleteBox" class="relative bg-white rounded-3xl shadow-2xl w-full max-w-sm p-6 transform scale-95 transition-all duration-300 text-center">

                <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-red-50 flex items-center justify-center">
                    <span class="iconify text-3xl text-red-500" data-icon="lucide:trash-2"></span>
                </div>

                <h3 class="text-lg font-bold text-gray-900 mb-2">
                    Xóa sản phẩm?
                </h3>

                <p class="text-sm text-gray-500 mb-6">
                    Bạn có chắc muốn xóa món này không?
                </p>

                <div class="flex gap-3">
                    <button onclick="closeDeleteModal()" 
                        class="flex-1 h-11 rounded-2xl border border-gray-200 text-gray-600 font-semibold hover:bg-gray-50">
                        Hủy
                    </button>

                    <button id="btnConfirmDelete"
                        class="flex-1 h-11 rounded-2xl bg-red-500 text-white font-semibold hover:bg-red-600">
                        Xóa
                    </button>
                </div>

            </div>
        </div>

        <!-- CONFIRM ORDER MODAL -->
<div id="confirmModal"
     class="fixed inset-0 z-[999] flex items-center justify-center opacity-0 pointer-events-none transition-all duration-300">
    
    <!-- nền -->
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm"
         onclick="hideConfirmModal()"></div>

    <!-- box -->
    <div id="confirmModalContent"
         class="relative bg-white rounded-3xl shadow-2xl w-full max-w-md p-6 transform scale-95 transition-all duration-300">

        <h3 class="text-lg font-bold text-gray-900 mb-2">
            Xác nhận đặt hàng
        </h3>

        <p class="text-sm text-gray-500 mb-6">
            Bạn có chắc chắn muốn đặt đơn hàng này không?
        </p>

        <!-- lỗi -->
        <p id="modalError" class="text-red-500 text-sm mb-3 hidden"></p>

        <div class="flex gap-3">
            <button onclick="hideConfirmModal()" 
                class="flex-1 h-11 rounded-2xl border border-gray-200 text-gray-600 font-semibold">
                Hủy
            </button>

            <button id="btnConfirm"
                onclick="confirmOrder()"
                class="flex-1 h-11 rounded-2xl bg-brown-600 text-white font-semibold">
                Xác nhận
            </button>
        </div>

    </div>
</div>

<div id="confirmDeleteOrderModal"
     class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 opacity-0 pointer-events-none transition">

    <div id="confirmDeleteOrderBox"
         class="bg-white rounded-2xl p-6 w-[90%] max-w-sm scale-95 transition shadow-xl">

        <h3 class="text-lg font-bold text-brown-900 mb-2">
            Xóa đơn hàng
        </h3>

        <p class="text-sm text-brown-500 mb-5">
            Bạn có chắc muốn xóa đơn này không?
        </p>

        <div class="flex justify-end gap-3">
            <button onclick="closeDeleteOrderModal()"
                class="px-4 py-2 rounded-xl border border-brown-200 text-brown-700 hover:bg-brown-50">
                Hủy
            </button>

            <button onclick="confirmDeleteOrder()"
                class="px-4 py-2 rounded-xl bg-red-500 text-white hover:bg-red-600">
                Xóa
            </button>
        </div>

    </div>
</div>

        <!-- ... nội dung trang web khác ... -->

    <!-- TOAST NOTIFICATION COMPONENT -->
    <div id="toast-container" class="fixed top-4 right-4 z-[100] flex flex-col gap-3 pointer-events-none">
        <!-- Toast sẽ được JS chèn vào đây -->
    </div>

    <!-- SCRIPT HIỂN THỊ TOAST -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Hàm hiển thị Toast
            window.showToast = function(message, type = 'success') {
                type = type || 'success';
                var container = document.getElementById('toast-container');
                if (!container) return;

                // Cấu hình màu và icon theo loại
                var configs = {
                    success: {
                        icon: 'lucide:check-circle',
                        colorClass: 'text-green-500',
                        borderClass: 'border-green-100',
                        bgClass: 'bg-white'
                    },
                    error: {
                        icon: 'lucide:alert-circle',
                        colorClass: 'text-red-500',
                        borderClass: 'border-red-100',
                        bgClass: 'bg-white'
                    },
                    info: {
                        icon: 'lucide:info',
                        colorClass: 'text-blue-500',
                        borderClass: 'border-blue-100',
                        bgClass: 'bg-white'
                    }
                };

                var config = configs[type] || configs.info;

                // Tạo phần tử Toast
                var toast = document.createElement('div');
                toast.className = 'toast pointer-events-auto flex items-center gap-3 p-4 rounded-2xl shadow-2xl border transform transition-all duration-300 translate-x-full opacity-0 ' + config.borderClass + ' ' + config.bgClass + ' min-w-[300px] max-w-sm';
                toast.innerHTML = 
                    '<div class="flex-shrink-0 w-6 h-6 flex items-center justify-center rounded-full ' + (type === 'success' ? 'bg-green-100' : 'bg-red-100') + '">' +
                        '<span class="iconify ' + config.colorClass + ' text-lg" data-icon="' + config.icon + '"></span>' +
                    '</div>' +
                    '<div class="flex-1 text-sm font-medium text-gray-800">' + message + '</div>' +
                    '<button onclick="this.parentElement.remove()" class="text-gray-400 hover:text-gray-600">' +
                        '<span class="iconify" data-icon="lucide:x"></span>' +
                    '</button>';

                container.appendChild(toast);

                // Animation: Trượt vào
                requestAnimationFrame(() => {
                    toast.classList.remove('translate-x-full', 'opacity-0');
                });

                // Tự động ẩn sau 3 giây
                setTimeout(function() {
                    toast.classList.add('translate-x-full', 'opacity-0');
                    setTimeout(function() { toast.remove(); }, 300);
                }, 3000);
            };

            // Kiểm tra session từ Laravel để hiển thị Toast ngay khi load trang
            @if(session('success'))
                window.showToast('{{ session('success') }}', 'success');
            @endif
            
            @if(session('error'))
                window.showToast('{{ session('error') }}', 'error');
            @endif

            // Hiển thị lỗi validate (nếu có)
            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    window.showToast('{{ $error }}', 'error');
                @endforeach
            @endif
        });
    </script>
</body>
</html>