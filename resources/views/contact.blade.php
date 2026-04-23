@extends('layouts.app')

@section('title', 'Món Ngon — Thông Tin Liên Hệ')

@section('sidebar')
    @include('shared.sidebar')
@endsection

@section('content')
<!-- Main Container: Centered and max-width adjusted for balance -->
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:py-12 fade-in">
    
    <!-- Header Section -->
    <div class="text-center mb-12">
        <h1 class="text-orange-600 font-bold uppercase text-4xl mb-2">Thông tin liên hệ</h1>
        <p class="text-brown-500 max-w-2xl mx-auto text-lg">Đội ngũ chăm sóc khách hàng của Món Ngon luôn sẵn sàng lắng nghe và hỗ trợ bạn 24/7.</p>
    </div>

    <!-- Content Grid (Single Column Centered, but using grid for internal layout if needed) -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
        
        <!-- Cột 1: Hotline & Email (Phóng to) -->
        <div class="bg-white rounded-3xl p-8 sm:p-10 shadow-lg shadow-brown-200/50 border border-brown-100 text-center flex flex-col justify-center h-full transform hover:-translate-y-1 transition-transform duration-300">
            <div class="mb-8">
                <div class="w-20 h-20 rounded-full bg-orange-100 flex items-center justify-center text-orange-600 mx-auto mb-6 shadow-inner">
                    <span class="iconify text-4xl" data-icon="lucide:headphones"></span>
                </div>
                <h3 class="font-bold text-2xl text-brown-900 mb-2">Hotline hỗ trợ</h3>
                <a href="tel:19001234" class="text-3xl font-bold text-orange-600 hover:underline tracking-tight">0334183254</a>
                <p class="text-sm text-brown-400 mt-2">Miễn phí 24/7</p>
            </div>
            
            <div class="pt-8 border-t border-brown-100">
                <div class="w-16 h-16 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 mx-auto mb-4">
                    <span class="iconify text-3xl" data-icon="lucide:mail"></span>
                </div>
                <h3 class="font-bold text-xl text-brown-900 mb-1">Email liên hệ</h3>
                <a href="mailto:support@monngon.vn" class="text-base text-brown-600 hover:text-orange-600 font-medium">support@monngon.vn</a>
            </div>
        </div>

        <!-- Cột 2: Danh sách Cơ sở & Bản đồ (Phóng to) -->
        <div class="bg-white rounded-3xl p-8 sm:p-10 shadow-lg shadow-brown-200/50 border border-brown-100 h-full flex flex-col">
            <div class="flex items-center justify-center gap-3 mb-6">
                <span class="iconify text-3xl text-orange-500" data-icon="lucide:map-pin"></span>
                <h3 class="font-serif font-bold text-2xl text-brown-900">Hệ thống cửa hàng</h3>
            </div>
            
            <div class="space-y-6 flex-1">
                <!-- Cơ sở 1 -->
                <div class="group flex gap-4 items-start">
                    <div class="mt-1 w-8 h-8 rounded-full bg-orange-100 border-2 border-orange-500 flex items-center justify-center text-xs font-bold text-orange-700 shrink-0">1</div>
                    <div>
                        <h4 class="font-bold text-lg text-brown-900 group-hover:text-orange-600 transition-colors">Chi nhánh Quận 1</h4>
                        <p class="text-brown-600 mt-1">123 Nguyễn Huệ, Quận 1, TP.HCM</p>
                        <p class="text-sm text-brown-400 mt-1 flex items-center gap-1">
                            <span class="iconify text-sm" data-icon="lucide:clock"></span> 08:00 - 22:00
                        </p>
                    </div>
                </div>

                <div class="h-px bg-gray-100 w-full"></div>

                <!-- Cơ sở 2 -->
                <div class="group flex gap-4 items-start">
                    <div class="mt-1 w-8 h-8 rounded-full bg-brown-50 border-2 border-brown-300 flex items-center justify-center text-xs font-bold text-brown-600 shrink-0">2</div>
                    <div>
                        <h4 class="font-bold text-lg text-brown-900 group-hover:text-orange-600 transition-colors">Chi nhánh Quận 7</h4>
                        <p class="text-brown-600 mt-1">456 Huỳnh Tấn Phát, Quận 7, TP.HCM</p>
                        <p class="text-sm text-brown-400 mt-1 flex items-center gap-1">
                            <span class="iconify text-sm" data-icon="lucide:clock"></span> 09:00 - 21:00
                        </p>
                    </div>
                </div>

                <div class="h-px bg-gray-100 w-full"></div>

                <!-- Cơ sở 3 -->
                <div class="group flex gap-4 items-start">
                    <div class="mt-1 w-8 h-8 rounded-full bg-brown-50 border-2 border-brown-300 flex items-center justify-center text-xs font-bold text-brown-600 shrink-0">3</div>
                    <div>
                        <h4 class="font-bold text-lg text-brown-900 group-hover:text-orange-600 transition-colors">Chi nhánh Hà Nội</h4>
                        <p class="text-brown-600 mt-1">789 Đường Láng, Đống Đa, HN</p>
                        <p class="text-sm text-brown-400 mt-1 flex items-center gap-1">
                            <span class="iconify text-sm" data-icon="lucide:clock"></span> 08:00 - 23:00
                        </p>
                    </div>
                </div>
            </div>

            <!-- Map Placeholder (Gọn hơn nhưng nổi bật) -->
            <a href="https://www.google.com/maps" target="_blank" rel="noopener noreferrer"
            class="mt-8 rounded-2xl overflow-hidden h-40 shadow-md border border-brown-100 bg-brown-50 flex items-center justify-center relative group cursor-pointer block">
                <img src="https://picsum.photos/seed/mapvn/800/400.jpg" alt="Map" class="w-full h-full object-cover opacity-90 group-hover:opacity-70 transition-opacity">
                
                <div class="absolute inset-0 flex items-center justify-center bg-black/10 group-hover:bg-black/0 transition-colors">
                    <div class="bg-white px-6 py-2.5 rounded-full shadow-xl text-sm font-bold text-brown-900 flex items-center gap-2 hover:bg-orange-600 hover:text-white transition-all transform hover:scale-105">
                        <span class="iconify" data-icon="lucide:map"></span>
                        Xem bản đồ đầy đủ
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>

<!-- --- FOOTER THÊM VÀO --- -->
<div class="max-w-7ml mx-auto px-4 sm:px-6 lg:px-8 mb-8">
    <div class="bg-white rounded-3xl p-8 md:p-10 shadow-sm border border-brown-100">
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10 lg:gap-16">
            
            <!-- Cột 1: Giới thiệu -->
            <div class="lg:pr-6">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-10 h-10 rounded-xl bg-orange-600 flex items-center justify-center shadow-sm">
                        <span class="iconify text-white text-lg" data-icon="lucide:chef-hat"></span>
                    </div>
                    <span class="font-serif font-bold text-2xl text-brown-900">Món Ngon</span>
                </div>

                <p class="text-brown-500 text-[15px] leading-7 max-w-md">
                    Hệ thống giao đồ ăn trực tuyến hàng đầu, mang đến những hương vị ẩm thực truyền thống và hiện đại đến tận cửa nhà bạn.
                </p>
            </div>
            
            <!-- Cột 2: Liên kết nhanh -->
            <div class="lg:mx-auto">
                <h4 class="font-bold text-brown-900 text-xl mb-5">Liên kết nhanh</h4>
                <ul class="space-y-3 text-[15px] text-brown-600">
                    <li>
                        <a href="#" class="hover:text-orange-600 transition-colors">Về chúng tôi</a>
                    </li>
                    <li>
                        <a href="#" class="hover:text-orange-600 transition-colors">Tuyển dụng</a>
                    </li>
                    <li>
                        <a href="#" class="hover:text-orange-600 transition-colors">Chính sách bảo mật</a>
                    </li>
                    <li>
                        <a href="#" class="hover:text-orange-600 transition-colors">Điều khoản sử dụng</a>
                    </li>
                </ul>
            </div>

            <!-- Cột 3: Kết nối -->
            <div class="lg:ml-auto">
                <h4 class="font-bold text-brown-900 text-xl mb-5">Kết nối</h4>
                <div class="flex items-center gap-4">
                    <a href="#" class="w-11 h-11 rounded-full bg-brown-100 flex items-center justify-center text-brown-600 hover:bg-orange-500 hover:text-white transition-all duration-300 hover:-translate-y-0.5">
                        <span class="iconify text-lg" data-icon="lucide:facebook"></span>
                    </a>
                    <a href="#" class="w-11 h-11 rounded-full bg-brown-100 flex items-center justify-center text-brown-600 hover:bg-orange-500 hover:text-white transition-all duration-300 hover:-translate-y-0.5">
                        <span class="iconify text-lg" data-icon="lucide:instagram"></span>
                    </a>
                    <a href="#" class="w-11 h-11 rounded-full bg-brown-100 flex items-center justify-center text-brown-600 hover:bg-orange-500 hover:text-white transition-all duration-300 hover:-translate-y-0.5">
                        <span class="iconify text-lg" data-icon="lucide:twitter"></span>
                    </a>
                </div>
            </div>
        </div>
        
        <div class="border-t border-brown-100 mt-10 pt-6 text-center text-sm text-brown-400">
            &copy; 2023 Món Ngon Restaurant. All rights reserved. Designed with care.
        </div>
    </div>
</div>
@endsection