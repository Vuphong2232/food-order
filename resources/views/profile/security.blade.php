@extends('layouts.app')

@section('title', 'Thông tin cá nhân')

@section('sidebar')
    @include('shared.sidebar')
@endsection

@section('footer')
    @include('shared.footer')
@endsection

@section('content')
<div class="max-w-2xl mx-auto p-4 md:p-8">
    
    <div class="mb-8 flex items-center justify-between">

    <!-- TEXT -->
    <div>
        <h1 class="text-3xl font-bold text-brown-900">Thông tin cá nhân</h1>
        <p class="text-brown-500 mt-1">Quản lý thông tin hồ sơ của bạn</p>
    </div>

</div>

    <div class="bg-white rounded-3xl shadow-sm border border-brown-100 p-6 md:p-8">
        
        <form action="{{ route('profile.changePassword') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Hiển thị lỗi nếu có -->
            @if ($errors->any())
                <div class="bg-red-50 text-red-600 p-4 rounded-xl text-sm mb-4 border border-red-100">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Old Password -->
            <div>
                <label class="block text-sm font-bold text-brown-700 mb-2">Mật khẩu hiện tại</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-brown-400">
                        <span class="iconify" data-icon="lucide:lock"></span>
                    </span>
                    <input type="password" name="old_password" required
                        class="w-full pl-11 pr-4 py-3 rounded-xl bg-brown-50 border border-brown-200 focus:bg-white focus:border-orange-500 focus:outline-none transition-colors text-brown-900"
                        placeholder="Nhập mật khẩu cũ">
                </div>
            </div>

            <!-- New Password -->
            <div>
                <label class="block text-sm font-bold text-brown-700 mb-2">Mật khẩu mới</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-brown-400">
                        <span class="iconify" data-icon="lucide:key"></span>
                    </span>
                    <input type="password" name="new_password" required
                        class="w-full pl-11 pr-4 py-3 rounded-xl bg-brown-50 border border-brown-200 focus:bg-white focus:border-orange-500 focus:outline-none transition-colors text-brown-900"
                        placeholder="Tối thiểu 6 ký tự">
                </div>
            </div>

            <!-- Confirm Password -->
            <div>
                <label class="block text-sm font-bold text-brown-700 mb-2">Xác nhận mật khẩu mới</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-brown-400">
                        <span class="iconify" data-icon="lucide:shield-check"></span>
                    </span>
                    <input type="password" name="new_password_confirmation" required
                        class="w-full pl-11 pr-4 py-3 rounded-xl bg-brown-50 border border-brown-200 focus:bg-white focus:border-orange-500 focus:outline-none transition-colors text-brown-900"
                        placeholder="Nhập lại mật khẩu mới">
                </div>
            </div>

            <!-- Button -->
            <div class="pt-4">
                <button type="submit" 
                        class="w-full bg-orange-500 hover:bg-orange-600 text-white font-bold py-3.5 rounded-xl shadow-lg shadow-orange-200 transform active:scale-[0.98] transition-all">
                    Cập nhật mật khẩu
                </button>
            </div>
        </form>

    </div>

</div>
@endsection