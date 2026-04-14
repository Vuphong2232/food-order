{{-- resources/views/product/show.blade.php --}}

@extends('layouts.app') {{-- Thay 'layouts.app' bằng tên file layout chính của bạn ---

@section('title', $product->name)

@section('content')
<div class="px-8 py-8 max-w-7xl mx-auto w-full">
    
    {{-- Breadcrumb / Nút quay lại --}}
    <div class="mb-6">
        <a href="{{ route('products.index') }}" class="inline-flex items-center text-sm font-medium text-brown-500 hover:text-brown-800 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
            Quay lại danh sách
        </a>
    </div>

    {{-- KHUNG CHI TIẾT SẢN PHẨM --}}
    <div class="bg-white rounded-3xl shadow-lg border border-brown-100 overflow-hidden">
        <div class="flex flex-col md:flex-row h-full">
            
            {{-- CỘT TRÁI: Ảnh lớn --}}
            <div class="w-full md:w-[55%] bg-brown-50/50 relative h-[400px] md:h-[600px]">
                <img src="{{ $product->image ?? 'https://via.placeholder.com/800x600' }}" 
                     alt="{{ $product->name }}"
                     class="w-full h-full object-cover">
            </div>

            {{-- CỘT PHẢI: Thông tin chi tiết --}}
            <div class="w-full md:w-[45%] p-8 md:p-12 flex flex-col justify-center bg-white">
                
                <div>
                    @if($product->category)
                        <span class="inline-block px-3 py-1 text-xs font-bold uppercase tracking-wider text-brown-600 bg-brown-100 rounded-full mb-4">
                            {{ $product->category }}
                        </span>
                    @endif

                    <h1 class="font-serif text-4xl font-bold text-brown-900 leading-tight mb-2">
                        {{ $product->name }}
                    </h1>

                    <div class="flex items-center gap-4 mb-6">
                        <p class="text-3xl font-bold text-brown-600">
                            {{ number_format($product->price) }}₫
                        </p>
                        <div class="h-1 w-12 bg-brown-200 rounded"></div>
                        <span class="text-green-600 font-medium text-sm flex items-center">
                            <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span> Còn hàng
                        </span>
                    </div>

                    <div class="prose prose-brown max-w-none mb-8">
                        <p class="text-gray-600 text-lg leading-relaxed">
                            {{ $product->description ?? 'Không có mô tả cho sản phẩm này.' }}
                        </p>
                    </div>
                </div>

                {{-- KHU VỰC HÀNH ĐỘNG (Mua hàng) --}}
                <div class="mt-4 pt-6 border-t border-gray-100">
                    <form class="flex flex-col gap-4 add-to-cart-form">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        
                        <div class="flex items-center gap-4 mb-2">
                            <label class="text-sm font-semibold text-brown-900">Số lượng:</label>
                            <div class="flex items-center border border-brown-200 rounded-lg overflow-hidden w-32">
                                <button type="button" onclick="updateQty(-1)" class="px-3 py-2 hover:bg-brown-50 text-brown-600 font-bold">-</button>
                                <input type="text" id="qty-input" name="quantity" value="1" readonly class="w-full text-center border-none focus:ring-0 text-brown-900 font-medium">
                                <button type="button" onclick="updateQty(1)" class="px-3 py-2 hover:bg-brown-50 text-brown-600 font-bold">+</button>
                            </div>
                        </div>

                        <button type="submit" class="w-full flex items-center justify-center gap-3 py-4 rounded-xl bg-brown-600 text-white font-bold text-lg shadow-lg shadow-brown-600/20 hover:bg-brown-700 hover:shadow-brown-600/40 active:scale-95 transition-all duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg>
                            Thêm vào giỏ hàng ngay
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
    // Hàm xử lý tăng giảm số lượng
    function updateQty(change) {
        const input = document.getElementById('qty-input');
        let currentVal = parseInt(input.value);
        let newVal = currentVal + change;
        
        if (newVal < 1) newVal = 1;
        // Có thể thêm giới hạn tối đa nếu muốn: if (newVal > 99) newVal = 99;
        
        input.value = newVal;
    }
</script>
@endsection