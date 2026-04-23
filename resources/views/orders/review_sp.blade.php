<div class="w-full max-w-2xl mx-auto bg-white rounded-3xl shadow-2xl overflow-hidden border border-brown-100">
    <div class="bg-brown-600 px-6 py-5 flex items-start justify-between">
        <div class="flex items-start gap-3">
            <div class="w-11 h-11 rounded-2xl bg-white/15 flex items-center justify-center text-white">
                <span class="iconify text-xl" data-icon="lucide:package-search"></span>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-white leading-tight">Đánh giá đơn hàng</h2>
                <p class="text-sm text-white/80 mt-1">#{{ $order->code }}</p>
            </div>
        </div>

        <button type="button"
                onclick="closeReviewSpModal()"
                class="w-10 h-10 rounded-xl flex items-center justify-center text-white hover:bg-white/10 transition">
            <span class="iconify text-xl" data-icon="lucide:x"></span>
        </button>
    </div>

    <div class="p-6 sm:p-7">
        <div class="bg-white rounded-xl border border-brown-100 shadow-sm overflow-hidden mb-6">
            <div class="px-4 py-3 bg-[#f7f1ea] border-b border-brown-100">
                <h4 class="text-sm font-bold text-[#7a4e2d] uppercase">Danh sách món</h4>
            </div>

            <div class="divide-y divide-[#e8d9c9]">
                @forelse($order->items as $item)
                    @php
                        $price = $item->price ?? 0;
                        $subtotal = $price * $item->quantity;
                        $isReviewed = in_array($item->product_id, $reviewedProductIds ?? []);
                    @endphp

                    <div class="px-5 py-4 bg-[#fcfcfc]">
                        <div class="grid grid-cols-1 md:grid-cols-4 items-center gap-4">
                            <div class="text-lg text-[#4b2e1f] truncate">
                                {{ $item->product->name ?? $item->name ?? 'Sản phẩm' }}
                            </div>

                            <div class="text-center text-lg text-[#8b5e3c] md:border-l md:border-r border-[#e8d9c9]">
                                SL: x{{ $item->quantity }}
                            </div>

                            <div class="text-right text-lg text-[#4b2e1f]">
                                {{ number_format($subtotal, 0, ',', '.') }}đ
                            </div>

                            <div class="flex justify-end">
                               @if(!$isReviewed)
                                <button type="button"
                                        data-review-btn="true"
                                        data-product-id="{{ $item->product_id }}"
                                        onclick="openReviewModal({{ $order->id }}, {{ $item->product_id }})"
                                        class="px-3 py-2 bg-yellow-50 text-yellow-700 border border-yellow-200 rounded-lg text-sm font-semibold hover:bg-yellow-100 hover:border-yellow-300 transition-colors flex items-center gap-2">
                                    <span class="iconify text-sm" data-icon="lucide:star"></span>
                                    Đánh giá
                                </button>
                            @else
                                <span class="px-3 py-2 bg-green-50 text-green-700 border border-green-200 rounded-lg text-sm font-semibold inline-flex items-center gap-2">
                                    <span class="iconify text-sm" data-icon="lucide:check"></span>
                                    Đã đánh giá
                                </span>
                            @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="px-5 py-6 text-center text-sm text-brown-400">
                        Chưa có món ăn nào
                    </div>
                @endforelse
            </div>
        </div>

        <div class="mt-6 flex justify-end">
            <button type="button"
                    onclick="closeReviewSpModal()"
                    class="px-5 h-11 rounded-2xl bg-brown-600 text-white font-semibold text-sm hover:bg-brown-700 transition">
                Đóng
            </button>
        </div>
    </div>
</div>