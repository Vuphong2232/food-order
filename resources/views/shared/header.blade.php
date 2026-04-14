<div class="sticky top-0 z-20 bg-cream/80 backdrop-blur-xl">
    <div class="flex items-center justify-between px-8 h-16 gap-4">

        <!-- Trái: Hamburger mobile -->
        <button onclick="toggleSidebar()" class="lg:hidden w-10 h-10 rounded-xl flex items-center justify-center text-brown-500 hover:text-brown-700 shrink-0">
            <span class="iconify text-lg" data-icon="lucide:menu"></span>
        </button>

        <!-- Giữa: Tabs phân loại -->
        <div class="flex items-center gap-10 flex-1 justify-center">
            <a href="{{ route('home', ['category' => 'all', 'price' => request('price')]) }}"
            class="cat-tab text-sm font-medium pb-0.5 border-b-2 whitespace-nowrap transition-all duration-200
            {{ request('category', 'all') == 'all' ? 'text-brown-900 border-brown-900' : 'text-brown-400 border-transparent' }}">
                Tất cả
            </a>

            <a href="{{ route('home', ['category' => 'mon-anh-nhau', 'price' => request('price')]) }}"
            class="cat-tab text-sm font-medium pb-0.5 border-b-2 whitespace-nowrap transition-all duration-200
            {{ request('category') == 'mon-anh-nhau' ? 'text-brown-900 border-brown-900' : 'text-brown-400 border-transparent' }}">
                Món ăn nhậu
            </a>

            <a href="{{ route('home', ['category' => 'mon-an-kem', 'price' => request('price')]) }}"
            class="cat-tab text-sm font-medium pb-0.5 border-b-2 whitespace-nowrap transition-all duration-200
            {{ request('category') == 'mon-an-kem' ? 'text-brown-900 border-brown-900' : 'text-brown-400 border-transparent' }}">
                Món ăn kèm
            </a>

            <a href="{{ route('home', ['category' => 'mon-an-chinh', 'price' => request('price')]) }}"
            class="cat-tab text-sm font-medium pb-0.5 border-b-2 whitespace-nowrap transition-all duration-200
            {{ request('category') == 'mon-an-chinh' ? 'text-brown-900 border-brown-900' : 'text-brown-400 border-transparent' }}">
                Món ăn chính
            </a>

            <a href="{{ route('home', ['category' => 'nuoc-giai-khat', 'price' => request('price')]) }}"
            class="cat-tab text-sm font-medium pb-0.5 border-b-2 whitespace-nowrap transition-all duration-200
            {{ request('category') == 'nuoc-giai-khat' ? 'text-brown-900 border-brown-900' : 'text-brown-400 border-transparent' }}">
                Nước giải khát
            </a>

            <a href="{{ route('home', ['category' => 'best-seller', 'price' => request('price')]) }}"
            class="cat-tab text-sm font-medium pb-0.5 border-b-2 whitespace-nowrap transition-all duration-200
            {{ request('category') == 'best-seller' ? 'text-brown-900 border-brown-900' : 'text-brown-400 border-transparent' }}">
                Best Seller
            </a>
        </div>

            <div class="search-box flex items-center gap-3 bg-white border border-brown-100 rounded-2xl px-4 py-2.5 transition-all w-[300px]">
                <span class="iconify text-brown-400 text-lg shrink-0" data-icon="lucide:search"></span>
                <input id="search-input" type="text" placeholder="Tìm kiếm món ăn..."
                    class="flex-1 bg-transparent text-sm text-brown-900 placeholder:text-brown-300 outline-none"
                    oninput="handleSearch(this.value)">
                <button onclick="closeSearch()" class="w-7 h-7 rounded-lg hover:bg-brown-50 flex items-center justify-center text-brown-400 hover:text-brown-600 transition-colors">
                    <span class="iconify text-sm" data-icon="lucide:x"></span>
                </button>
            </div>
            <button onclick="toggleCart()" class="relative w-10 h-10 rounded-xl flex items-center justify-center text-brown-500 hover:text-brown-700 hover:bg-brown-50 transition-all shrink-0">
            <span class="iconify text-xl" data-icon="lucide:shopping-cart"></span>
            
            <!-- Badge số lượng sản phẩm -->
            <span id="cart-badge" class="hidden absolute -top-1 -right-1 w-5 h-5 bg-red-500 text-white text-[10px] font-bold rounded-full items-center justify-center transform scale-0 transition-transform duration-300">
                0
            </span>
        </button>
    </div>
</div>