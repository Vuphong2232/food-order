<div id="cart-overlay" class="cart-overlay fixed inset-0 bg-brown-950/40 backdrop-blur-sm z-[60] hidden" onclick="toggleCart()"></div>

<div id="cart-panel" class="cart-panel fixed top-0 right-0 bottom-0 w-full max-w-md bg-cream z-[70] shadow-2xl hidden flex-col">
    <div class="flex items-center justify-between px-6 h-16 border-b border-brown-100 shrink-0">
        <div class="flex items-center gap-2.5">
            <span class="iconify text-xl text-brown-600" data-icon="lucide:shopping-bag"></span>
            <h3 class="font-serif font-bold text-lg text-brown-900">Giỏ hàng</h3>
        </div>
        
        <button onclick="toggleCart()" class="w-9 h-9 rounded-xl hover:bg-brown-50 flex items-center justify-center text-brown-400 hover:text-brown-700 transition-all">
            <span class="iconify text-lg" data-icon="lucide:x"></span>
        </button>
    </div>

    <div id="cart-items" class="flex-1 overflow-y-auto px-6 py-4"></div>

    <div id="cart-empty" class="flex-1 flex flex-col items-center justify-center px-6 hidden">
        <div class="w-20 h-20 rounded-full bg-brown-50 flex items-center justify-center mb-4 animate-float">
            <span class="iconify text-3xl text-brown-300" data-icon="lucide:shopping-bag"></span>
        </div>
        <h4 class="font-serif text-lg text-brown-700 mb-1">Giỏ hàng trống</h4>
        <p class="text-sm text-brown-400 text-center">Hãy thêm món ăn yêu thích vào giỏ hàng nhé!</p>
    </div>

    <div id="cart-footer" class="border-t border-brown-100 px-6 py-5 shrink-0 space-y-3 hidden">
        <div class="flex items-center justify-between">
            <span class="text-sm text-brown-500">Tạm tính</span>
            <span id="cart-subtotal" class="text-sm text-brown-700">0₫</span>
        </div>
        <div class="flex items-center justify-between">
            <span class="text-sm text-brown-500">Phí giao hàng</span>
            <span class="text-sm text-green-600 font-medium">Miễn phí</span>
        </div>
        <div class="h-px bg-brown-100"></div>
        <div class="flex items-center justify-between">
            <span class="font-semibold text-brown-900">Tổng cộng</span>
            <span id="cart-total" class="font-bold text-xl text-brown-800 font-serif">0₫</span>
        </div>
        <!-- Nút đặt hàng -->
        <button onclick="confirmOrder()" 
            class="w-full h-12 bg-brown-600 text-white font-semibold rounded-2xl hover:bg-brown-700 transition-all shadow-lg shadow-brown-600/20 flex items-center justify-center gap-2">
            
            <span class="iconify" data-icon="lucide:credit-card"></span>
            Đặt hàng ngay
        </button>

<!-- Lỗi hiển thị ở đây, MÀU ĐỎ, to hơn, dễ thấy -->
<p id="modalError" class="text-center text-red-600 text-sm font-medium mt-4 hidden leading-relaxed"></p>
    </div>
</div>
    </div>
</div>

@include('partials.checkout_modal')
