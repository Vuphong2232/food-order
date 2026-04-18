@extends('layouts.app')

@section('title', 'Danh Sách Thông Báo')

@section('sidebar')
    @include('shared.sidebar')
@endsection

@section('content')
<section class="px-8 py-8 max-w-6xl mx-auto">

    <div class="mb-6 flex items-center justify-between gap-4">
        <div class="shrink-0">
            <h1 class="font-serif text-2xl font-bold text-brown-900 flex items-center gap-2">
                <span class="iconify text-brown-600" data-icon="lucide:bell-ring"></span>
                Lịch sử thông báo
            </h1>
            <p class="text-sm text-brown-500 mt-1">
                Xem các thông báo đơn hàng mới và hoạt động của hệ thống.
            </p>
        </div>

        @if(count($notifications) > 0)
            <button onclick="clearAllNotificationsAndRefresh()" class="px-4 py-2 bg-red-50 text-red-600 border border-red-100 rounded-xl text-sm font-medium hover:bg-red-100 transition flex items-center gap-2">
                <span class="iconify" data-icon="lucide:trash-2"></span>
                Xóa tất cả
            </button>
        @endif
    </div>

    <!-- Skeleton -->
    <div id="notifications-skeleton" class="space-y-4">
        @for($i = 0; $i < 5; $i++)
            <div class="bg-white rounded-2xl shadow-sm border border-brown-100 p-4 overflow-hidden">
                <div class="flex gap-4">
                    <div class="w-10 h-10 rounded-full skeleton shrink-0"></div>

                    <div class="flex-1">
                        <div class="flex justify-between items-start gap-3">
                            <div class="flex-1">
                                <div class="h-4 w-40 rounded skeleton mb-3"></div>
                                <div class="h-3 w-full rounded skeleton mb-2"></div>
                                <div class="h-3 w-3/4 rounded skeleton"></div>
                            </div>

                            <div class="flex flex-col items-end gap-2 shrink-0">
                                <div class="h-3 w-16 rounded skeleton"></div>
                                <div class="h-7 w-20 rounded-lg skeleton"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endfor
    </div>

    <!-- Nội dung thật -->
    <div id="notifications-content" class="hidden">
        @if(count($notifications) > 0)
            <div class="space-y-4">
                @foreach($notifications as $notif)
                    @php
                        $borderColor = 'border-l-green-500';
                        $icon = 'bell';
                        $iconColor = 'text-green-500';

                        if ($notif->type == 'user_register') {
                            $borderColor = 'border-l-blue-500';
                            $icon = 'user-plus';
                            $iconColor = 'text-blue-500';
                        } elseif ($notif->type == 'product_deleted') {
                            $borderColor = 'border-l-red-500';
                            $icon = 'trash-2';
                            $iconColor = 'text-red-500';
                        } elseif ($notif->type == 'revenue_threshold') {
                            $borderColor = 'border-l-yellow-500';
                            $icon = 'trophy';
                            $iconColor = 'text-yellow-500';
                        } elseif ($notif->type == 'best_seller') {
                            $borderColor = 'border-l-purple-500';
                            $icon = 'star';
                            $iconColor = 'text-purple-500';
                        } elseif ($notif->type == 'product_created') {
                            $borderColor = 'border-l-green-500';
                            $icon = 'utensils-crossed';
                            $iconColor = 'text-green-500';
                        } elseif ($notif->type == 'product_updated') {
                            $borderColor = 'border-l-orange-500';
                            $icon = 'pencil';
                            $iconColor = 'text-orange-500';
                        } elseif ($notif->type == 'order_success') {
                            $borderColor = 'border-l-emerald-500';
                            $icon = 'shopping-bag';
                            $iconColor = 'text-emerald-500';
                        }
                    @endphp

                    <div class="bg-white rounded-2xl shadow-sm border border-brown-100 p-4 hover:shadow-md transition relative overflow-hidden group">
                        <div class="absolute left-0 top-0 bottom-0 w-1.5 {{ $borderColor }}"></div>

                        <div class="flex gap-4">
                            <div class="w-10 h-10 rounded-full bg-brown-50 flex items-center justify-center shrink-0">
                                <span class="iconify text-xl {{ $iconColor }}" data-icon="lucide:{{ $icon }}"></span>
                            </div>

                            <div class="flex-1">
                                <div class="flex justify-between items-start gap-3">
                                    <div>
                                        <h4 class="font-bold text-brown-900 text-sm">
                                            {{ $notif->title }}
                                        </h4>

                                        <p class="text-sm text-brown-600 mt-1">
                                            {!! $notif->message !!}
                                        </p>
                                    </div>

                                    <div class="flex flex-col items-end gap-2 shrink-0">
                                        <span class="text-[10px] text-brown-400 whitespace-nowrap">
                                            {{ $notif->created_at?->diffForHumans() }}
                                        </span>

                                        @if($notif->type === 'order_success' && !empty($notif->data['order_id']))
                                            <a href="{{ route('orders.history', ['search' => $notif->data['order_code'] ?? '']) }}"
                                               class="inline-flex items-center gap-1 px-3 py-1.5 bg-brown-50 text-brown-600 border border-brown-200 rounded-lg text-xs font-medium hover:bg-brown-100 hover:text-brown-900 transition">
                                                <span>Chi tiết</span>
                                                <span class="iconify text-xs" data-icon="lucide:arrow-right"></span>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-20 flex flex-col items-center bg-white rounded-3xl border-2 border-dashed border-brown-100">
                <div class="w-20 h-20 bg-brown-50 rounded-full flex items-center justify-center mb-4">
                    <span class="iconify text-4xl text-brown-300" data-icon="lucide:bell-off"></span>
                </div>
                <h3 class="text-brown-900 font-bold text-lg mb-2">Không có thông báo nào</h3>
                <p class="text-brown-500 text-sm max-w-md mx-auto mb-6">
                    Hệ thống chưa ghi nhận được đơn hàng hoặc hoạt động mới nào gần đây.
                </p>
                <a href="{{ route('home') }}" class="text-brown-600 font-medium hover:underline">
                    Về trang chủ
                </a>
            </div>
        @endif
    </div>

</section>
@endsection

@push('scripts')
<script>
    window.addEventListener('load', function () {
        const skeleton = document.getElementById('notifications-skeleton');
        const content = document.getElementById('notifications-content');

        if (skeleton) skeleton.classList.add('hidden');
        if (content) content.classList.remove('hidden');
    });

    function clearAllNotificationsAndRefresh() {
        if (confirm('Bạn có chắc muốn xóa toàn bộ lịch sử thông báo?')) {
            fetch('/admin/notifications/clear', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('Đã xóa toàn bộ thông báo', 'success');
                    setTimeout(() => window.location.reload(), 500);
                } else {
                    showToast('Xóa thông báo thất bại', 'error');
                }
            })
            .catch(() => {
                showToast('Lỗi kết nối server', 'error');
            });
        }
    }
</script>
@endpush