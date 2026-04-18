@extends('layouts.app')

@section('title', 'Thông tin cá nhân')

@section('sidebar')
    @include('shared.sidebar')
@endsection

@section('footer')
    @include('shared.footer')
@endsection

@section('content')
<div class="max-w-4xl mx-auto p-4 md:p-8">
    
    <div class="mb-8 flex items-center justify-between">

    <!-- TEXT -->
    <div>
        <h1 class="text-3xl font-bold text-brown-900">Thông tin cá nhân</h1>
        <p class="text-brown-500 mt-1">Quản lý thông tin hồ sơ của bạn</p>
    </div>

</div>

    <div class="bg-white rounded-3xl shadow-sm border border-brown-100 overflow-hidden">
        
        <!-- Header Card -->
        <div class="bg-gradient-to-r from-orange-500 to-orange-400 p-6 md:p-8 flex items-center gap-6">
            <div class="w-20 h-20 rounded-full bg-white/20 backdrop-blur-md flex items-center justify-center text-white text-3xl font-bold border-4 border-white/30">
                {{ strtoupper(substr(auth()->user()->username ?? auth()->user()->name, 0, 1)) }}
            </div>
            <div class="text-white">
                <h2 class="text-2xl font-bold">{{ auth()->user()->username ?? auth()->user()->name }}</h2>
                <p class="opacity-90">Thành viên từ {{ auth()->user()->created_at->format('d/m/Y') }}</p>
            </div>
        </div>

        <!-- Info Body -->
        <div class="p-6 md:p-8 space-y-6">
            
            <!-- Username -->
            <div class="flex flex-col md:flex-row md:items-center justify-between pb-6 border-b border-brown-50">
                <div>
                    <p class="text-xs font-bold text-brown-400 uppercase tracking-wider mb-1">Tên tài khoản</p>
                    <p class="text-lg font-semibold text-brown-900">{{ auth()->user()->username }}</p>
                </div>
                <!-- Nút sửa (Optional) -->
                <button class="mt-2 md:mt-0 text-sm text-orange-600 font-medium hover:underline">Sửa tên tài khoản</button>
            </div>

            <!-- Email -->
            <div class="flex flex-col md:flex-row md:items-center justify-between pb-6 border-b border-brown-50">
                <div>
                    <p class="text-xs font-bold text-brown-400 uppercase tracking-wider mb-1">Địa chỉ Email</p>
                    <p class="text-lg font-semibold text-brown-900">{{ auth()->user()->email }}</p>
                </div>
                <span class="mt-2 md:mt-0 px-3 py-1 bg-green-100 text-green-700 text-xs font-bold rounded-full">Đã xác minh</span>
            </div>

            <!-- Created At -->
            <div class="flex flex-col md:flex-row md:items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-brown-400 uppercase tracking-wider mb-1">Ngày tham gia</p>
                    <p class="text-lg font-semibold text-brown-900">{{ auth()->user()->created_at->format('d/m/Y - H:i') }}</p>
                </div>
            </div>

        </div>
    </div>

</div>
@endsection