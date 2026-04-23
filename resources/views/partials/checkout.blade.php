@extends('layouts.app')

@section('title', 'Món Ngon — Đặt Hàng')

@section('sidebar')
    @include('shared.sidebar')
@endsection

@section('footer')
    @include('shared.footer')
@endsection

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:py-12 fade-in">
    
    <div class="mb-6">
        <h1 class="text-3xl font-serif font-bold text-brown-900">Xác nhận đơn hàng</h1>
        <p class="text-brown-500">Vui lòng kiểm tra thông tin và sản phẩm trước khi đặt.</p>
    </div>

    <div class="flex flex-col lg:flex-row gap-8">
        
        <!-- --- CỘT PHẢI (70%): DANH SÁCH SẢN PHẨM & ĐÁNH GIÁ --- -->
        <div class="lg:w-[70%] w-full space-y-6">
            
            <div class="bg-white rounded-2xl shadow-sm border border-brown-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-brown-100 bg-brown-50/50 flex justify-between items-center">
                    <h2 class="font-bold text-brown-900 text-lg flex items-center gap-2">
                        <span class="iconify text-orange-500" data-icon="lucide:shopping-cart"></span>
                        Sản phẩm trong giỏ hàng
                    </h2>
                </div>

                <div class="divide-y divide-brown-50 max-h-[650px] overflow-y-auto" id="checkout-items">
                    @forelse($cartItems as $item)
                        @php
                            $product = $item->product;
                            $avgRating = $product && $product->reviews->count() > 0
                                ? $product->reviews->avg('rating')
                                : 0;
                        @endphp

                        <div class="p-6 flex gap-4 items-start">
                            <!-- Ảnh sản phẩm -->
                            <div class="w-24 h-24 sm:w-28 sm:h-28 rounded-xl bg-gray-100 shrink-0 overflow-hidden">
                                <img src="{{ $product->image ?? 'https://via.placeholder.com/150' }}" alt="{{ $product->name }}" class="w-full h-full object-cover hover:scale-110 transition-transform duration-300">
                            </div>

                            <!-- Thông tin & Đánh giá -->
                            <div class="flex-1 min-w-0">
                                <h3 class="font-bold text-brown-900 text-lg truncate pr-2">
                                    <a href="#" class="hover:text-orange-600 transition-colors">{{ $product->name }}</a>
                                </h3>
                                
                                <p class="text-orange-600 font-bold text-lg mt-1">
                                    {{ number_format($product->price) }}₫
                                </p>

                                <!-- PHẦN ĐÁNH GIÁ (Nếu có đánh giá thì hiển thị) -->
                                @if($product->reviews && $product->reviews->count() > 0)
                                    <div class="mt-2 flex items-center gap-1">
                                        <div class="flex text-yellow-400 text-sm">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= round($avgRating))
                                                    <span class="iconify" data-icon="lucide:star" data-filled="true"></span>
                                                @else
                                                    <span class="iconify text-gray-300" data-icon="lucide:star"></span>
                                                @endif
                                            @endfor
                                        </div>
                                        <a href="{{ route('products.show', $product->id) }}" class="text-xs text-brown-500 ml-1">
                                            ({{ $product->reviews->count() }} đánh giá)
                                        </a>
                                    </div>
                                @else
                                    <span class="text-xs text-brown-400 mt-2 block italic">Chưa có đánh giá</span>
                                @endif
                            </div>

                            <!-- Số lượng & Tổng tiền -->
                            <div class="flex flex-col items-end gap-2 shrink-0">
                                <div class="flex items-center border border-brown-200 rounded-lg">
                                    <button type="button" class="px-2 py-1 text-brown-500 hover:bg-brown-100 rounded-l-lg transition-colors">-</button>
                                    <input type="text" readonly value="{{ $item->quantity }}" class="w-10 text-center text-sm font-medium border-none focus:ring-0 p-1 bg-transparent">
                                    <button type="button" class="px-2 py-1 text-brown-500 hover:bg-brown-100 rounded-r-lg transition-colors">+</button>
                                </div>
                                <div class="text-sm font-bold text-brown-900">
                                    {{ number_format($product->price * $item->quantity) }}₫
                                </div>
                            </div>
                        </div>

                    @empty
                        <div class="p-12 text-center">
                            <p class="text-brown-500">Giỏ hàng của bạn đang trống.</p>
                            <a href="{{ route('home') }}" class="inline-block mt-4 text-orange-600 font-bold hover:underline">Quay lại mua sắm</a>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- --- CỘT TRÁI (30%): THÔNG TIN ĐẶT HÀNG (Giống ảnh) --- -->
        <div class="lg:w-[32%] w-full shrink-0">
    <div class="bg-white rounded-3xl shadow-[0_12px_40px_rgba(120,53,15,0.08)] border border-brown-100 sticky top-24 overflow-hidden">

        <!-- Header -->
        <div class="px-6 py-5 border-b border-brown-100 bg-gradient-to-b from-orange-50/70 to-white">
            <h2 class="font-bold text-brown-900 text-[22px] flex items-center gap-3">
                <span class="w-10 h-10 rounded-2xl bg-orange-100 text-orange-500 flex items-center justify-center shadow-sm">
                    <span class="iconify text-xl" data-icon="lucide:clipboard-list"></span>
                </span>
                <span>Thông tin Order</span>
            </h2>
            <p class="text-sm text-brown-500 mt-2 ml-[52px]">
                Kiểm tra ưu đãi và chọn phương thức thanh toán trước khi đặt hàng
            </p>
        </div>

        <div class="p-6 space-y-6">
            <form id="checkoutForm" action="{{ route('checkout.store') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Coupon -->
                <div>
                    <label class="block text-sm font-semibold text-brown-800 mb-2">
                        Bạn có mã giảm giá?
                    </label>

                    <div class="flex gap-3">
                        <input type="text"
                            name="coupon_code"
                            id="coupon_code"
                            placeholder="NHẬP MÃ GIẢM GIÁ"
                            class="flex-1 h-12 px-4 rounded-xl border border-brown-200 bg-brown-50/40 text-sm text-brown-800 uppercase placeholder:text-brown-300 outline-none focus:border-orange-400 focus:ring-4 focus:ring-orange-100 transition">

                        <button type="button"
                                onclick="applyCoupon()"
                                class="h-12 px-5 rounded-xl bg-orange-500 text-white text-sm font-bold hover:bg-orange-600 active:scale-95 shadow-md shadow-orange-200 transition-all">
                            Áp dụng
                        </button>
                    </div>

                    <input type="hidden" id="hidden_coupon_code" name="hidden_coupon_code">

                    <p id="coupon-message"
                       class="text-sm mt-3 px-3 py-2 rounded-xl hidden bg-orange-50 border border-orange-100">
                    </p>
                </div>

                <!-- Payment -->
                <div>
                    <label class="block text-sm font-semibold text-brown-800 mb-3">
                        Phương thức thanh toán
                    </label>

                    <div class="space-y-3">
                        <label class="group flex items-center gap-3 px-4 py-3 rounded-2xl border border-brown-200 bg-white cursor-pointer hover:border-orange-300 hover:bg-orange-50/40 transition-all">
                            <input type="radio"
                                   name="payment_method"
                                   value="cod"
                                   checked
                                   class="w-4 h-4 text-orange-500 focus:ring-orange-400">

                            <div class="flex-1">
                                <p class="text-sm font-semibold text-brown-800">
                                    Thanh toán khi nhận hàng (COD)
                                </p>
                                <p class="text-xs text-brown-500 mt-0.5">
                                    Thanh toán trực tiếp cho shipper khi nhận món
                                </p>
                            </div>
                        </label>

                        <label class="group flex items-center gap-3 px-4 py-3 rounded-2xl border border-brown-200 bg-white cursor-pointer hover:border-orange-300 hover:bg-orange-50/40 transition-all">
                            <input type="radio"
                                   name="payment_method"
                                   value="vnpay"
                                   class="w-4 h-4 text-orange-500 focus:ring-orange-400">

                            <div class="flex-1">
                                <p class="text-sm font-semibold text-brown-800">
                                    Ví VNPAY
                                </p>
                                <p class="text-xs text-brown-500 mt-0.5">
                                    Thanh toán online nhanh chóng và tiện lợi
                                </p>
                            </div>
                        </label>
                    </div>
                </div>
            </form>
        </div>

        <!-- Summary -->
        <div class="px-6 py-5 border-t border-brown-100 bg-brown-50/30">
            <div class="space-y-3">
                <div class="flex items-center justify-between text-[15px] text-brown-600">
                    <span>Tạm tính</span>
                    <span id="subtotal-display" class="font-medium text-brown-800">
                        {{ number_format($subtotal ?? 0) }}₫
                    </span>
                </div>

                <div class="flex items-center justify-between text-[15px] text-green-600">
                    <span>Giảm giá</span>
                    <span id="discount-display" class="font-semibold">0₫</span>
                </div>

                <div class="h-px bg-brown-100 my-2"></div>

                <div class="flex items-end justify-between">
                    <div>
                        <p class="text-sm text-brown-500">Tổng thanh toán</p>
                        <p class="text-xs text-brown-400 mt-1">Đã bao gồm ưu đãi hiện tại</p>
                    </div>

                    <span id="total-display" class="text-[32px] leading-none font-extrabold text-brown-900 tracking-tight">
                        {{ number_format($subtotal ?? 0) }}₫
                    </span>
                </div>
            </div>
        </div>

        <!-- Button -->
        <div class="p-6 pt-5">
            <button type="button"
                    id="btnCheckout"
                    onclick="openCheckoutModalOnly()"
                    class="w-full h-14 bg-brown-600 text-white font-semibold text-lg rounded-2xl hover:bg-brown-700 transition-all shadow-lg shadow-brown-600/20 active:scale-[0.98] flex items-center justify-center gap-2">
                <span class="iconify text-[22px]" data-icon="lucide:shopping-bag"></span>
                <span>Đặt hàng ngay</span>
            </button>

            <p id="checkoutError"
               class="text-center text-red-600 text-sm font-semibold mt-4 px-4 py-3 rounded-xl bg-red-50 border border-red-100 hidden">
                Vui lòng điền đầy đủ thông tin!
            </p>
        </div>
    </div>
</div>

    </div>
</div>
@endsection

@section('modal')
    @include('partials.checkout_modal', ['subtotal' => $subtotal ?? 0])
@endsection

