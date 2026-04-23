@extends('layouts.app')

@section('title', 'Lịch sử mua hàng')

@section('sidebar')
    @include('shared.sidebar')
@endsection

@section('footer')
    @include('shared.footer')
@endsection

@section('content')
<section class="px-8 py-6 max-w-6xl mx-auto">

    <!-- Header Tiêu đề & Tìm kiếm -->
    <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="shrink-0">
            <h1 class="font-serif text-2xl font-bold text-brown-900 flex items-center gap-2">
                <span class="iconify text-brown-600" data-icon="lucide:clipboard-list"></span>
                {{ $isAdminMode ? 'Quản lý đơn hàng' : 'Lịch sử mua hàng' }}
            </h1>
            <p class="text-brown-500 mt-1 text-xs">
                {{ $isAdminMode ? 'Theo dõi và xử lý tất cả đơn hàng' : 'Xem lại lịch sử các đơn hàng' }}
            </p>
        </div>

        <form method="GET" action="{{ route('orders.history') }}" class="w-full md:w-auto" >
            <div class="relative group">
                <input
                    type="text"
                    id="orderSearchInput"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Tìm kiếm mã đơn..."
                    autocomplete="off"
                    class="w-full md:w-72 pl-12 pr-4 py-3 text-sm rounded-xl border border-brown-200 bg-white text-xs text-brown-900 placeholder-brown-400 outline-none focus:border-brown-500 focus:ring-2 focus:ring-brown-500/10 transition-all shadow-sm">

                <span class="iconify absolute left-3.5 top-1/2 -translate-y-1/2 text-brown-400" data-icon="lucide:search"></span>
            </div>
        </form>
    </div>

    <div id="ordersHistorySkeleton" class="space-y-3 ">
    @for($i = 0; $i < 5; $i++)
        <div class="bg-white rounded-2xl shadow-sm border border-brown-100 p-4 relative overflow-hidden">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-brown-50 rounded-full opacity-40"></div>

            <div class="relative z-10">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 mb-4 pb-3 border-b border-dashed border-brown-100">
                    <div class="flex items-center gap-2.5">
                        <div class="w-9 h-9 rounded-lg skeleton"></div>
                        <div>
                            <div class="h-3 w-20 rounded skeleton mb-2"></div>
                            <div class="h-4 w-36 rounded skeleton"></div>
                        </div>
                    </div>

                    <div class="h-8 w-32 rounded-lg skeleton"></div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-center">
                    <div class="md:col-span-7 space-y-3">
                        @if($isAdminMode)
                            <div class="flex items-center gap-2 p-2 rounded-lg border border-gray-100">
                                <div class="w-7 h-7 rounded-full skeleton"></div>
                                <div class="flex-1">
                                    <div class="h-3 w-16 rounded skeleton mb-2"></div>
                                    <div class="h-4 w-40 rounded skeleton"></div>
                                </div>
                            </div>
                        @endif

                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 rounded-full skeleton"></div>
                            <div class="h-6 w-36 rounded-full skeleton"></div>
                        </div>
                    </div>

                    <div class="md:col-span-5 flex flex-col items-end justify-center gap-2 md:pl-4">
                        <div class="text-right">
                            <div class="h-3 w-24 rounded skeleton mb-2 ml-auto"></div>
                            <div class="h-6 w-28 rounded skeleton ml-auto"></div>
                        </div>
                    </div>
                </div>

                <div class="mt-3 pt-3 border-t border-brown-100 flex flex-wrap gap-2 justify-end">
                    <div class="h-8 w-24 rounded-lg skeleton"></div>
                    <div class="h-8 w-24 rounded-lg skeleton"></div>
                </div>
            </div>
        </div>
    @endfor
</div>

    <div id="ordersHistoryContainer" class="hidden">
        @if($orders->count() > 0)
            <div id="order-list" class="space-y-3">
                @foreach($orders as $order)
                    @php
                        $processLabels = [
                            'received' => ['text' => 'Chờ tiếp nhận', 'class' => 'bg-yellow-50 text-yellow-700 border-yellow-100', 'icon' => 'clock'],
                            'preparing' => ['text' => 'Chuẩn bị', 'class' => 'bg-blue-50 text-blue-700 border-blue-100', 'icon' => 'chef-hat'],
                            'shipping' => ['text' => 'Đang giao', 'class' => 'bg-purple-50 text-purple-700 border-purple-100', 'icon' => 'truck'],
                            'completed' => ['text' => 'Hoàn tất', 'class' => 'bg-green-50 text-green-700 border-green-100', 'icon' => 'check-circle'],
                        ];
                        $currentProcess = $processLabels[$order->process_status ?? 'received'];
                    @endphp

                    <!-- Order Card - Chiều cao đã giảm (p-4 thay vì p-6) -->
                    <div ondblclick="openInvoiceModal({{ $order->id }})"
                         class="bg-white rounded-2xl shadow-sm border border-brown-100 p-4 hover:shadow-lg hover:border-brown-200 transition-all duration-300 cursor-pointer relative group overflow-hidden">
                        
                        <!-- Background Decor (Thu nhỏ lại) -->
                        <div class="absolute -right-4 -top-4 w-24 h-24 bg-brown-50 rounded-full opacity-40 group-hover:scale-110 transition-transform duration-500 pointer-events-none"></div>

                        <div class="relative z-10">
                            <!-- Top Row: Code & Time (Giảm khoảng cách mb-4 thay vì mb-6) -->
                            <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 mb-4 pb-3 border-b border-dashed border-brown-100">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-9 h-9 rounded-lg bg-brown-50 flex items-center justify-center text-brown-600 shrink-0">
                                        <span class="iconify text-lg" data-icon="lucide:shopping-bag"></span>
                                    </div>
                                    <div>
                                        <div class="text-[10px] text-brown-400 font-semibold uppercase tracking-wider">Mã đơn hàng</div>
                                        <div class="font-bold text-brown-900 text-base leading-tight">{{ $order->code }}</div>
                                    </div>
                                </div>
                                
                                <div class="flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-brown-50 border border-brown-100">
                                    <span class="iconify text-brown-400 text-sm" data-icon="lucide:calendar"></span>
                                    <span class="text-xs font-medium text-brown-600">{{ $order->created_at->format('d/m/Y H:i') }}</span>
                                </div>
                            </div>

                            <!-- Middle Row: Info & Price (Giảm gap và padding) -->
                            <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-center">
                                
                                <!-- Info Column -->
                                <div class="md:col-span-7 space-y-3">
                                    @if($isAdminMode)
                                        <div class="flex items-center gap-2 p-2 rounded-lg bg-gray-50 border border-gray-100">
                                            <div class="w-7 h-7 rounded-full bg-white border border-gray-200 flex items-center justify-center shadow-sm shrink-0">
                                                <span class="iconify text-gray-500 text-sm" data-icon="lucide:user"></span>
                                            </div>
                                            <div class="min-w-0">
                                                <div class="text-[14px] text-gray-500 font-medium">Người mua</div>
                                                <div class="text-sm font-semibold text-gray-900 truncate">
                                                    {{ $order->user->email ?? 'N/A' }}
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="flex items-center gap-2">
                                        <div class="w-7 h-7 rounded-full bg-white border border-brown-100 flex items-center justify-center shadow-sm shrink-0">
                                            <span class="iconify text-brown-400 text-sm" data-icon="lucide:info"></span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <span class="text-sm font-medium text-brown-600">Trạng thái:</span>
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[13px] font-bold border {{ $currentProcess['class'] }}">
                                                <span class="iconify text-sl" data-icon="lucide:{{ $currentProcess['icon'] }}"></span>
                                                {{ $currentProcess['text'] }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Price Column -->
                                <div class="md:col-span-5 flex flex-col items-end justify-center gap-2 border-l-0 md:border-l border-dashed border-brown-100 md:pl-4">
                                    <div class="text-right">
                                        <div class="text-[14px] text-brown-400 font-bold uppercase tracking-wider mb-0.5">Tổng thanh toán</div>
                                        <div class="text-xl font-black text-brown-900">
                                            {{ number_format($order->total_amount) }}₫
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Bottom Row: Actions (Giảm khoảng cách mt-3) -->
                            <div class="mt-3 pt-3 border-t border-brown-100 flex flex-wrap gap-2 justify-end opacity-100 md:opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                @if(!$isAdminMode)
                                    <button type="button"
                                            onclick="event.stopPropagation(); openTrackModal({{ $order->id }})"
                                            class="px-3 py-1.5 bg-brown-50 text-brown-700 border border-brown-200 rounded-lg text-xs font-semibold hover:bg-brown-100 hover:border-brown-300 transition-colors flex items-center gap-1.5">
                                        <span class="iconify text-sm" data-icon="lucide:package-search"></span>
                                        Theo dõi
                                    </button>

                                @if($order->items->count() > 0 &&
                                    (
                                        $order->status === 'completed' ||
                                        $order->process_status === 'completed'
                                    )
                                )
                                    <button type="button"
                                            onclick="event.stopPropagation(); openReviewSpModal({{ $order->id }}, {{ $order->items->first()->product_id }})"
                                            class="px-3 py-1.5 bg-yellow-50 text-yellow-700 border border-yellow-200 rounded-lg text-xs font-semibold hover:bg-yellow-100 hover:border-yellow-300 transition-colors flex items-center gap-1.5">
                                        <span class="iconify text-sm" data-icon="lucide:star"></span>
                                        Đánh giá
                                    </button>
                                @endif
                                @endif

                                @if($isAdminMode)
                                    <button type="button"
                                        onclick="event.stopPropagation(); openManageOrderModal({{ $order->id }})"
                                        class="px-3 py-1.5 bg-brown-600 text-white rounded-lg text-xs font-semibold hover:bg-brown-700 shadow-sm transition-all flex items-center gap-1.5">
                                        <span class="iconify text-sm" data-icon="lucide:settings"></span>
                                        Quản lý
                                    </button>

                                    <button type="button"
                                        onclick="event.stopPropagation(); openDeleteOrderModal({{ $order->id }})"
                                        class="px-3 py-1.5 bg-white text-red-600 border border-red-100 rounded-lg text-xs font-semibold hover:bg-red-50 hover:border-red-200 transition-colors flex items-center gap-1.5">
                                        <span class="iconify text-sm" data-icon="lucide:trash-2"></span>
                                        Xóa
                                    </button>
                                @endif
                            </div>

                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if ($orders->hasPages())
                <div class="flex justify-center mt-8 gap-2">
                    @if ($orders->onFirstPage())
                        <span class="w-9 h-9 flex items-center justify-center rounded-lg bg-gray-50 text-gray-400 cursor-not-allowed">
                            <span class="iconify" data-icon="lucide:chevron-left"></span>
                        </span>
                    @else
                        <a href="{{ $orders->previousPageUrl() }}"
                           class="w-9 h-9 flex items-center justify-center rounded-lg bg-white border border-brown-200 text-brown-600 hover:bg-brown-50 transition text-xs">
                            <span class="iconify" data-icon="lucide:chevron-left"></span>
                        </a>
                    @endif

                    @foreach ($orders->getUrlRange(1, $orders->lastPage()) as $page => $url)
                        @if ($page == $orders->currentPage())
                            <span class="w-9 h-9 flex items-center justify-center rounded-lg bg-brown-600 text-white font-bold text-xs shadow-sm">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}"
                               class="w-9 h-9 flex items-center justify-center rounded-lg bg-white border border-brown-200 text-brown-600 hover:bg-brown-50 transition text-xs">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach

                    @if ($orders->hasMorePages())
                        <a href="{{ $orders->nextPageUrl() }}"
                           class="w-9 h-9 flex items-center justify-center rounded-lg bg-white border border-brown-200 text-brown-600 hover:bg-brown-50 transition text-xs">
                            <span class="iconify" data-icon="lucide:chevron-right"></span>
                        </a>
                    @else
                        <span class="w-9 h-9 flex items-center justify-center rounded-lg bg-gray-50 text-gray-400 cursor-not-allowed">
                            <span class="iconify" data-icon="lucide:chevron-right"></span>
                        </span>
                    @endif
                </div>
            @endif
        @else
            <!-- Empty State -->
            <div class="text-center py-16 bg-white rounded-2xl border-2 border-dashed border-brown-100">
                <div class="w-16 h-16 bg-brown-50 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="iconify text-3xl text-brown-300" data-icon="lucide:inbox"></span>
                </div>
                <h3 class="text-brown-900 font-bold text-lg mb-2">
                    {{ $isAdminMode ? 'Chưa có đơn hàng' : 'Chưa có đơn hàng' }}
                </h3>
                <p class="text-brown-500 text-sm mb-6">
                    {{ $isAdminMode ? 'Đơn hàng mới sẽ xuất hiện tại đây' : 'Hãy đặt món ngay nhé' }}
                </p>
                <a href="{{ route('home') }}"
                   class="inline-flex items-center gap-2 px-6 py-2.5 bg-brown-600 text-white rounded-lg text-sm font-bold hover:bg-brown-700 transition shadow-sm">
                    <span class="iconify" data-icon="lucide:utensils"></span>
                    {{ $isAdminMode ? 'Về trang chủ' : 'Mua ngay' }}
                </a>
            </div>
        @endif
    </div>

    <script>
    (function () {
        const searchInput = document.getElementById('orderSearchInput');
        const container = document.getElementById('ordersHistoryContainer');
        const skeleton = document.getElementById('ordersHistorySkeleton');
        let timer = null;

        if (!searchInput || !container || !skeleton) return;

        function showSkeleton() {
            skeleton.classList.remove('hidden');
            container.classList.add('hidden');
        }

        function hideSkeleton() {
            skeleton.classList.add('hidden');
            container.classList.remove('hidden');
        }

        searchInput.addEventListener('input', function () {
            clearTimeout(timer);

            timer = setTimeout(function () {
                const keyword = searchInput.value.trim();
                const url = new URL("{{ route('orders.history') }}", window.location.origin);

                if (keyword !== '') {
                    url.searchParams.set('search', keyword);
                }

                @if(request('filter'))
                    url.searchParams.set('filter', "{{ request('filter') }}");
                @endif

                showSkeleton();

                fetch(url.toString(), {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(function (response) {
                    return response.text();
                })
                .then(function (html) {
                    const temp = document.createElement('div');
                    temp.innerHTML = html;

                    const newContainer = temp.querySelector('#ordersHistoryContainer');
                    if (newContainer) {
                        container.innerHTML = newContainer.innerHTML;
                        window.history.replaceState({}, '', url.toString());
                    }

                    hideSkeleton();
                })
                .catch(function (error) {
                    console.error(error);
                    hideSkeleton();
                });
            }, 300);
        });
    })();
    window.addEventListener('load', function () {
    const skeleton = document.getElementById('ordersHistorySkeleton');
    const container = document.getElementById('ordersHistoryContainer');

    setTimeout(() => {
        skeleton.classList.add('hidden');
        container.classList.remove('hidden');
    }, 800);
});

async function openReviewSpModal(orderId, productId) {
    const modal = document.getElementById('reviewSpModal');
    const content = document.getElementById('reviewSpModalContent');

    content.innerHTML = `
        <div class="w-full bg-white rounded-3xl shadow-2xl border border-brown-100 p-8 text-center text-brown-600">
            Đang tải...
        </div>
    `;

    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';

    try {
        const res = await fetch(`/orders/${orderId}/review-sp/${productId}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });

        const html = await res.text();
        content.innerHTML = html;

        if (window.Iconify) Iconify.scan();
    } catch (e) {
        content.innerHTML = `
            <div class="w-full bg-white rounded-3xl shadow-2xl border border-red-100 p-8 text-center text-red-500">
                Không tải được giao diện đánh giá đơn hàng
            </div>
        `;
    }
}

function closeReviewSpModal() {
    document.getElementById('reviewSpModal').classList.add('hidden');
    document.getElementById('reviewSpModalContent').innerHTML = '';
    document.body.style.overflow = '';
}

async function openReviewModal(orderId, productId) {
    const modal = document.getElementById('reviewModal');
    const content = document.getElementById('reviewModalContent');

    content.innerHTML = `
        <div class="w-full bg-white rounded-3xl shadow-2xl border border-brown-100 p-8 text-center text-brown-600">
            Đang tải...
        </div>
    `;

    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';

    try {
        const res = await fetch(`/orders/review/${orderId}/${productId}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });

        const html = await res.text();
        content.innerHTML = html;

        if (window.Iconify) Iconify.scan();
    } catch (e) {
        content.innerHTML = `
            <div class="w-full bg-white rounded-3xl shadow-2xl border border-red-100 p-8 text-center text-red-500">
                Không tải được form đánh giá sản phẩm
            </div>
        `;
    }
}

function closeReviewModal() {
    document.getElementById('reviewModal').classList.add('hidden');
    document.getElementById('reviewModalContent').innerHTML = '';
}
</script>

</section>
@endsection

@section('modal')
    @include('orders.invoice')
    @include('orders.manage_modal')
    @include('orders.review')
    
    <div id="trackModal" class="fixed inset-0 z-[99999] hidden">
        <div class="absolute inset-0 bg-black/70 backdrop-blur-sm" onclick="closeTrackModal()"></div>
        <div class="relative min-h-screen flex items-center justify-center p-4 sm:p-6">
            <div id="trackModalContent" class="w-full max-w-3xl"></div>
        </div>
    </div>

    <div id="reviewSpModal" class="fixed inset-0 z-[9999] hidden">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeReviewSpModal()"></div>
        <div class="relative z-10 w-full h-full flex items-center justify-center p-4">
            <div id="reviewSpModalContent" class="w-full max-w-2xl"></div>
        </div>
    </div>

    <div id="reviewModal" class="fixed inset-0 z-[10000] hidden">
        <div class="absolute inset-0 bg-black/55 backdrop-blur-sm" onclick="closeReviewModal()"></div>
        <div class="relative z-10 w-full h-full flex items-center justify-center p-4">
            <div id="reviewModalContent" class="w-full max-w-xl"></div>
        </div>
    </div>
@endsection