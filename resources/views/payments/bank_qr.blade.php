@extends('layouts.app')

@section('title', 'Thanh toán QR — Món Ngon')

@section('sidebar')
    @include('shared.sidebar')
@endsection

@section('footer')
    @include('shared.footer')
@endsection

@section('content')
@php
    $bankCode = 'MB';
    $bankName = 'MB Bank';
    $accountNo = '171020045555';
    $accountName = 'Vu Van Phong';
    $amount = (int) $order->total_amount;
    $description = $order->code;

    $qrUrl = 'https://img.vietqr.io/image/'
        . $bankCode . '-' . $accountNo . '-compact2.png'
        . '?amount=' . $amount
        . '&addInfo=' . urlencode($description)
        . '&accountName=' . urlencode($accountName);
@endphp

<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10 fade-in">
    <div class="mb-8">
        <h1 class="text-3xl font-serif font-bold text-brown-900">
            Thanh toán đơn hàng
        </h1>
        <p class="text-brown-500 mt-1">
            Vui lòng quét mã QR và chuyển khoản đúng nội dung để hoàn tất đơn hàng.
        </p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-start">
        <div class="bg-white rounded-3xl border border-brown-100 shadow-sm p-6">
            <div class="flex items-center gap-3 mb-5">
                <div class="w-12 h-12 rounded-2xl bg-orange-100 text-orange-500 flex items-center justify-center">
                    <span class="iconify text-2xl" data-icon="lucide:qr-code"></span>
                </div>

                <div>
                    <h2 class="text-xl font-bold text-brown-900">
                        Quét mã QR ngân hàng
                    </h2>
                    <p class="text-sm text-brown-500">
                        Mã QR đã bao gồm số tiền và nội dung chuyển khoản
                    </p>
                </div>
            </div>

            <div class="bg-brown-50 rounded-3xl p-5 flex justify-center">
                <img src="{{ $qrUrl }}"
                     alt="QR thanh toán {{ $order->code }}"
                     class="w-full max-w-[360px] rounded-2xl border border-brown-100 shadow-md bg-white">
            </div>

            <p class="text-sm text-red-500 mt-5 text-center font-medium">
                Lưu ý: Không sửa nội dung chuyển khoản để hệ thống/admin dễ đối chiếu đơn hàng.
            </p>
        </div>

        <div class="bg-white rounded-3xl border border-brown-100 shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-brown-100 bg-gradient-to-b from-orange-50/70 to-white">
                <h2 class="text-xl font-bold text-brown-900 flex items-center gap-3">
                    <span class="w-10 h-10 rounded-2xl bg-orange-100 text-orange-500 flex items-center justify-center">
                        <span class="iconify text-xl" data-icon="lucide:receipt-text"></span>
                    </span>
                    Thông tin thanh toán
                </h2>
            </div>

            <div class="p-6 space-y-4">
                <div class="flex justify-between gap-4 border-b border-brown-50 pb-3">
                    <span class="text-brown-500">Mã đơn hàng</span>
                    <span class="font-bold text-brown-900">{{ $order->code }}</span>
                </div>

                <div class="flex justify-between gap-4 border-b border-brown-50 pb-3">
                    <span class="text-brown-500">Ngân hàng</span>
                    <span class="font-bold text-brown-900">{{ $bankName }}</span>
                </div>

                <div class="flex justify-between gap-4 border-b border-brown-50 pb-3">
                    <span class="text-brown-500">Số tài khoản</span>
                    <span class="font-bold text-brown-900">{{ $accountNo }}</span>
                </div>

                <div class="flex justify-between gap-4 border-b border-brown-50 pb-3">
                    <span class="text-brown-500">Chủ tài khoản</span>
                    <span class="font-bold text-brown-900">{{ $accountName }}</span>
                </div>

                <div class="flex justify-between gap-4 border-b border-brown-50 pb-3">
                    <span class="text-brown-500">Nội dung</span>
                    <span class="font-bold text-orange-600">{{ $description }}</span>
                </div>

                <div class="flex justify-between gap-4 items-end pt-2">
                    <span class="text-brown-500">Tổng tiền</span>
                    <span class="text-3xl font-extrabold text-brown-900">
                        {{ number_format($order->total_amount) }}₫
                    </span>
                </div>

                <div class="mt-6 rounded-2xl bg-yellow-50 border border-yellow-100 p-4 text-sm text-yellow-800">
                    Sau khi chuyển khoản thành công, bấm nút bên dưới để hoàn tất đơn hàng.
                    Với hệ thống thật, bước này nên được xác nhận bằng webhook/cổng thanh toán hoặc admin kiểm tra sao kê.
                </div>

                <form action="{{ route('bank.payment.confirm', $order->id) }}" method="POST" class="pt-2">
                    @csrf

                    <button type="submit"
                            class="w-full h-14 rounded-2xl bg-brown-600 text-white font-bold text-lg hover:bg-brown-700 transition-all shadow-lg shadow-brown-600/20 flex items-center justify-center gap-2">
                        <span class="iconify text-xl" data-icon="lucide:check-circle"></span>
                        Tôi đã thanh toán
                    </button>
                </form>

                <a href="{{ route('orders.history') }}"
                   class="block text-center text-sm text-brown-500 hover:text-brown-700 mt-3">
                    Thanh toán sau / xem lại trong lịch sử đơn hàng
                </a>
            </div>
        </div>
    </div>
</div>
@endsection