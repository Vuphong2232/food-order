@extends('layouts.app')

@section('title', $selectedProduct->name ?? 'Chi tiết sản phẩm')

@section('toast')
    @include('product.toast')
@endsection

@section('cart')
    @include('product.cart')
@endsection

@section('sidebar')
    @include('shared.sidebar')
@endsection

@section('header')
    @include('shared.header')
@endsection

@section('content')
<section class="px-4 md:px-8 py-6">
    <div class="max-w-7xl mx-auto">
        <div class="bg-white rounded-[32px] shadow-sm border border-brown-100 overflow-hidden">
            <div class="flex flex-col md:flex-row h-full">

                {{-- CỘT TRÁI: Ảnh lớn --}}
                <div class="w-full md:w-[55%] bg-brown-50/50 relative h-[400px] md:h-[760px]">
                    <img src="{{ $selectedProduct->image_url ?? 'https://via.placeholder.com/800x600' }}"
                         alt="{{ $selectedProduct->name }}"
                         class="w-full h-full object-cover">
                </div>

                {{-- CỘT PHẢI: Thông tin chi tiết --}}
                <div class="w-full md:w-[45%] p-8 md:p-12 flex flex-col justify-between bg-white">
                    <div>
                        @if($selectedProduct->category)
                            <span class="inline-block px-3 py-1 text-xs font-bold uppercase tracking-wider text-brown-600">
                                {{ is_object($selectedProduct->category) ? $selectedProduct->category->name : $selectedProduct->category }}
                            </span>
                        @endif

                        <h1 class="mt-3 text-4xl font-bold text-brown-950 leading-tight">
                            {{ $selectedProduct->name }}
                        </h1>

                        <div class="mt-4 text-5xl font-semibold text-brown-700">
                            {{ number_format($selectedProduct->price, 0, ',', '.') }}đ
                        </div>

                        <p class="mt-8 text-2xl text-brown-600 leading-relaxed">
                            {{ $selectedProduct->description }}
                        </p>
                    </div>

                    <div class="mt-10">
                        <button
                            onclick="addToCart({{ $selectedProduct->id }}, '{{ addslashes($selectedProduct->name) }}', {{ $selectedProduct->price }}, '{{ $selectedProduct->image_url }}')"
                            class="w-full h-14 rounded-2xl bg-brown-600 text-white text-xl font-bold hover:bg-brown-700 transition">
                            Thêm vào giỏ
                        </button>

                        @php
                            $reviews = $selectedProduct->reviews;
                            $latestReviews = $reviews->take(2);
                            $remainingReviews = $reviews->slice(2);
                        @endphp

                        <div class="mt-8 pt-6 border-t border-brown-100">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-xl font-bold text-brown-900">Đánh giá sản phẩm</h3>
                                <span class="text-sm text-brown-500">
                                    {{ $reviews->count() }} đánh giá
                                </span>
                            </div>

                            @if($reviews->count() > 0)
                                <div class="space-y-4">
                                    @foreach($latestReviews as $review)
                                        <div class="bg-white border border-brown-100 rounded-2xl p-4 shadow-sm">
                                            <div class="flex items-start justify-between gap-3 mb-2">
                                                <div>
                                                    <div class="font-semibold text-brown-900">
                                                        {{ $review->user->username ?? $review->user->name ?? 'Người dùng' }}
                                                    </div>
                                                    <div class="text-xs text-brown-400">
                                                        {{ $review->created_at->format('d/m/Y H:i') }}
                                                    </div>
                                                </div>

                                                <div class="flex items-center gap-1 text-yellow-500 text-sm">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <span>{{ $i <= $review->rating ? '★' : '☆' }}</span>
                                                    @endfor
                                                </div>
                                            </div>

                                            <p class="text-sm text-brown-700 leading-6">
                                                {{ $review->comment ?: 'Không có nhận xét.' }}
                                            </p>
                                        </div>
                                    @endforeach
                                </div>

                                @if($remainingReviews->count() > 0)
                                    <div id="more-reviews" class="hidden space-y-4 mt-4">
                                        @foreach($remainingReviews as $review)
                                            <div class="bg-white border border-brown-100 rounded-2xl p-4 shadow-sm">
                                                <div class="flex items-start justify-between gap-3 mb-2">
                                                    <div>
                                                        <div class="font-semibold text-brown-900">
                                                            {{ $review->user->username ?? $review->user->name ?? 'Người dùng' }}
                                                        </div>
                                                        <div class="text-xs text-brown-400">
                                                            {{ $review->created_at->format('d/m/Y H:i') }}
                                                        </div>
                                                    </div>

                                                    <div class="flex items-center gap-1 text-yellow-500 text-sm">
                                                        @for($i = 1; $i <= 5; $i++)
                                                            <span>{{ $i <= $review->rating ? '★' : '☆' }}</span>
                                                        @endfor
                                                    </div>
                                                </div>

                                                <p class="text-sm text-brown-700 leading-6">
                                                    {{ $review->comment ?: 'Không có nhận xét.' }}
                                                </p>
                                            </div>
                                        @endforeach
                                    </div>

                                    <div class="mt-4">
                                        <button type="button"
                                                id="toggle-reviews-btn"
                                                onclick="toggleMoreReviews()"
                                                class="px-4 py-2 rounded-xl border border-brown-200 text-brown-700 hover:bg-brown-50 transition font-medium text-sm">
                                            Xem thêm
                                        </button>
                                    </div>
                                @endif
                            @else
                                <div class="rounded-2xl border border-brown-100 bg-brown-50 px-4 py-4 text-sm text-brown-500">
                                    Chưa có đánh giá nào cho sản phẩm này.
                                </div>
                            @endif
                        </div>                    </div>
                </div>

            </div>
        </div>
    </div>
</section>
@endsection

@section('footer')
    @include('shared.footer')
@endsection