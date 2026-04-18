@php
    $process = $order->process_status ?? 'received';

    $statusMap = [
        'received' => 'Chờ tiếp nhận',
        'preparing' => 'Chuẩn bị đơn hàng',
        'shipping' => 'Đang giao hàng',
        'completed' => 'Hoàn tất đơn hàng',
    ];

    $currentStep = match($process) {
        'received' => 1,
        'preparing' => 2,
        'shipping' => 3,
        'completed' => 4,
        default => 1,
    };
@endphp

<div class="w-full max-w-2xl mx-auto bg-white rounded-3xl shadow-2xl overflow-hidden border border-brown-100">
    <div class="bg-brown-600 px-6 py-5 flex items-start justify-between">
        <div class="flex items-start gap-3">
            <div class="w-11 h-11 rounded-2xl bg-white/15 flex items-center justify-center text-white">
                <span class="iconify text-xl" data-icon="lucide:package-search"></span>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-white leading-tight">Theo dõi đơn hàng</h2>
                <p class="text-sm text-white/80 mt-1">#{{ $order->code }}</p>
            </div>
        </div>

        <button type="button"
                onclick="closeTrackModal()"
                class="w-10 h-10 rounded-xl flex items-center justify-center text-white hover:bg-white/10 transition">
            <span class="iconify text-xl" data-icon="lucide:x"></span>
        </button>
    </div>

    <div class="p-6 sm:p-7">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
            <div class="rounded-2xl border border-brown-100 bg-brown-50 px-4 py-4">
                <div class="text-[11px] uppercase tracking-wider text-brown-400 font-semibold mb-2">Mã đơn hàng</div>
                <div class="text-sm font-bold text-brown-900">#{{ $order->code }}</div>
            </div>

            <div class="rounded-2xl border border-brown-100 bg-brown-50 px-4 py-4">
                <div class="text-[11px] uppercase tracking-wider text-brown-400 font-semibold mb-2">Ngày đặt</div>
                <div class="text-sm font-semibold text-brown-900">
                    {{ $order->created_at->format('d/m/Y H:i') }}
                </div>
            </div>

            <div class="rounded-2xl border border-brown-100 bg-brown-50 px-4 py-4">
                <div class="text-[11px] uppercase tracking-wider text-brown-400 font-semibold mb-2">Tình trạng</div>
                <div class="inline-flex items-center gap-2 text-sm font-semibold text-green-700">
                    <span class="w-2.5 h-2.5 rounded-full bg-green-500"></span>
                    {{ $statusMap[$process] ?? 'Chờ tiếp nhận' }}
                </div>
            </div>
        </div>

    <div class="bg-white rounded-xl border border-brown-100 shadow-sm overflow-hidden mb-6">
        <div class="px-4 py-3 bg-[#f7f1ea] border-b border-brown-100">
            <h4 class="text-sm font-bold text-[#7a4e2d] uppercase">Danh sách món</h4>
        </div>

        <div class="divide-y divide-[#e8d9c9]">
            @forelse($order->items as $item)
                @php
                    $price = $item->price ?? 0;
                    $subtotal = $price * $item->quantity;
                @endphp

                <div class="grid grid-cols-3 items-center px-5 py-4 bg-[#fcfcfc]">
                    <div class="text-lg text-[#4b2e1f] pr-4 truncate">
                        {{ $item->product->name ?? $item->name ?? 'Sản phẩm' }}
                    </div>

                    <div class="text-center text-lg text-[#8b5e3c] border-l border-r border-[#e8d9c9]">
                        SL: x{{ $item->quantity }}
                    </div>

                    <div class="text-right text-lg text-[#4b2e1f] pl-4">
                        {{ number_format($subtotal, 0, ',', '.') }}đ
                    </div>
                </div>
            @empty
                <div class="px-5 py-6 text-center text-sm text-brown-400">
                    Chưa có món ăn nào
                </div>
            @endforelse
        </div>
    </div> 

        <div class="rounded-2xl border border-brown-100 bg-white p-5">
            <h3 class="text-base font-bold text-brown-900 mb-5">Quy trình xử lý đơn hàng</h3>

            <div class="space-y-5">
                <div class="flex gap-4">
                    <div class="relative flex flex-col items-center">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center {{ $currentStep >= 1 ? 'bg-brown-600 text-white' : 'bg-brown-100 text-brown-400' }}">
                            <span class="iconify" data-icon="{{ $currentStep >= 1 ? 'lucide:check' : 'lucide:circle' }}"></span>
                        </div>
                        <div class="w-0.5 h-10 {{ $currentStep >= 2 ? 'bg-brown-500' : 'bg-brown-100' }}"></div>
                    </div>
                    <div class="pt-1">
                        <div class="text-sm font-semibold text-brown-900">Tiếp nhận đơn hàng</div>
                        <div class="text-xs text-brown-400 mt-1">Đơn hàng đã được ghi nhận thành công</div>
                    </div>
                </div>

                <div class="flex gap-4">
                    <div class="relative flex flex-col items-center">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center {{ $currentStep >= 2 ? 'bg-brown-600 text-white' : 'bg-brown-100 text-brown-400' }}">
                            <span class="iconify" data-icon="{{ $currentStep >= 2 ? 'lucide:check' : 'lucide:package' }}"></span>
                        </div>
                        <div class="w-0.5 h-10 {{ $currentStep >= 3 ? 'bg-brown-500' : 'bg-brown-100' }}"></div>
                    </div>
                    <div class="pt-1">
                        <div class="text-sm font-semibold text-brown-900">Chuẩn bị đơn hàng</div>
                        <div class="text-xs text-brown-400 mt-1">Đơn hàng đang được chuẩn bị</div>
                    </div>
                </div>

                <div class="flex gap-4">
                    <div class="relative flex flex-col items-center">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center {{ $currentStep >= 3 ? 'bg-brown-600 text-white' : 'bg-brown-100 text-brown-400' }}">
                            <span class="iconify" data-icon="{{ $currentStep >= 3 ? 'lucide:check' : 'lucide:truck' }}"></span>
                        </div>
                        <div class="w-0.5 h-10 {{ $currentStep >= 4 ? 'bg-brown-500' : 'bg-brown-100' }}"></div>
                    </div>
                    <div class="pt-1">
                        <div class="text-sm font-semibold text-brown-900">Đang giao hàng</div>
                        <div class="text-xs text-brown-400 mt-1">Đơn vị vận chuyển đang giao đơn đến bạn</div>
                    </div>
                </div>

                <div class="flex gap-4">
                    <div class="relative flex flex-col items-center">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center {{ $currentStep >= 4 ? 'bg-green-600 text-white' : 'bg-brown-100 text-brown-400' }}">
                            <span class="iconify" data-icon="{{ $currentStep >= 4 ? 'lucide:check-check' : 'lucide:badge-check' }}"></span>
                        </div>
                    </div>
                    <div class="pt-1">
                        <div class="text-sm font-semibold text-brown-900">Hoàn tất đơn hàng</div>
                        <div class="text-xs text-brown-400 mt-1">Đơn hàng đã được giao thành công</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-6 flex justify-end">
            <button type="button"
                    onclick="closeTrackModal()"
                    class="px-5 h-11 rounded-2xl bg-brown-600 text-white font-semibold text-sm hover:bg-brown-700 transition">
                Đóng
            </button>
        </div>
    </div>
</div>