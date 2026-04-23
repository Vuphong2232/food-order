<section class="px-8 py-8">

    {{-- HEADER --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="font-serif text-2xl font-bold text-brown-900">
                Thực đơn hôm nay
            </h2>
            <p class="text-brown-400 text-sm mt-0.5">
                @if(method_exists($products, 'total'))
                    {{ $products->total() }} món ăn đặc sắc
                @else
                    {{ count($products) }} món ăn đặc sắc
                @endif
            </p>
        </div>

        <div class="flex items-center gap-3">
            @if(!isset($selectedProduct))
                <form method="GET" action="{{ route('home') }}" class="flex items-center gap-3">
                    <input type="hidden" name="category" value="{{ request('category', 'all') }}">

                    <select name="price"
                            onchange="this.form.submit()"
                            class="min-w-[180px] bg-white border border-brown-100 rounded-xl px-4 py-2.5 text-sm text-brown-700 outline-none focus:border-brown-300">
                        <option value="">Lọc theo giá</option>
                        <option value="0-50000" {{ request('price') == '0-50000' ? 'selected' : '' }}>0 - 50k</option>
                        <option value="50000-100000" {{ request('price') == '50000-100000' ? 'selected' : '' }}>50k - 100k</option>
                        <option value="100000-200000" {{ request('price') == '100000-200000' ? 'selected' : '' }}>100k - 200k</option>
                        <option value="200000-500000" {{ request('price') == '200000-500000' ? 'selected' : '' }}>200k - 500k</option>
                        <option value="500000-1000000" {{ request('price') == '500000-1000000' ? 'selected' : '' }}>500k - 1tr</option>
                    </select>
                </form>
            @endif

            @if(isset($selectedProduct))
                <a href="/home"
                   class="px-4 py-2 bg-brown-100 text-brown-700 rounded-xl hover:bg-brown-200 transition whitespace-nowrap">
                    ← Quay lại
                </a>
            @endif
        </div>
    </div>

    {{-- SKELETON --}}
    <div id="menu-skeleton">
        @if(isset($selectedProduct))
            <div class="max-w-6xl mx-auto bg-white rounded-3xl shadow-lg overflow-hidden">
                <div class="flex flex-col md:flex-row">
                    <div class="md:w-1/2">
                        <div class="w-full h-[420px] skeleton"></div>
                    </div>

                    <div class="md:w-1/2 p-8">
                        <div class="h-4 w-24 rounded skeleton mb-4"></div>
                        <div class="h-8 w-2/3 rounded skeleton mb-4"></div>
                        <div class="h-7 w-32 rounded skeleton mb-6"></div>
                        <div class="space-y-3">
                            <div class="h-4 w-full rounded skeleton"></div>
                            <div class="h-4 w-5/6 rounded skeleton"></div>
                            <div class="h-4 w-4/6 rounded skeleton"></div>
                        </div>
                        <div class="mt-10 h-14 w-full rounded-2xl skeleton"></div>
                    </div>
                </div>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 gap-4">
                @for($i = 0; $i < 10; $i++)
                    <div class="bg-white rounded-2xl shadow-sm border border-brown-100 overflow-hidden">
                        <div class="h-40 skeleton"></div>
                        <div class="p-4 flex items-center justify-between gap-3">
                            <div class="min-w-0 flex-1">
                                <div class="h-4 w-3/4 rounded skeleton mb-2"></div>
                                <div class="h-4 w-1/2 rounded skeleton"></div>
                            </div>
                            <div class="w-10 h-10 rounded-xl skeleton"></div>
                        </div>
                    </div>
                @endfor
            </div>
        @endif
    </div>


        <div id="product-grid"
             class="hidden grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 gap-4">

            @forelse($products as $product)

            <div 
                class="product-card block cursor-pointer"
                data-price="{{ $product->price }}"
                data-category="{{ is_object($product->category) ? $product->category->slug : $product->category }}"
                data-id="{{ $product->id }}"
                onclick="window.location.href='/products/{{ $product->id }}'"
            >
                <div class="bg-white rounded-2xl shadow-sm border border-brown-100 overflow-hidden hover:shadow-lg transition h-full">

                    {{-- IMAGE --}}
                    <div class="h-40 overflow-hidden relative">
                        @if(in_array($product->id, $bestSellerIds ?? []))
                            <span class="absolute top-2 left-2 z-10 bg-orange-500 text-white text-[11px] font-semibold px-2.5 py-1 rounded-full shadow">
                                Best Seller
                            </span>
                        @endif

                        <img src="{{ $product->image_url ?? 'https://via.placeholder.com/300' }}"
                            class="w-full h-full object-cover">
                    </div>

                    {{-- CONTENT --}}
                    <div class="p-4 flex items-center justify-between gap-3">

                        <div class="min-w-0 flex-1">
                            <h3 class="font-semibold text-brown-900 truncate">
                                {{ $product->name }}
                            </h3>
                            <p class="text-brown-600 font-medium mt-0.5">
                                {{ number_format($product->price) }}₫
                            </p>
                        </div>

                        <form class="add-to-cart-form flex-shrink-0"
                              onclick="event.stopPropagation()">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            
                            <button type="submit"
                                class="flex items-center justify-center w-10 h-10 rounded-xl bg-brown-600 text-white shadow-lg hover:scale-110 transition">
                                +
                            </button>
                        </form>

                    </div>

                </div>
            </div>

            @empty
                <div class="col-span-full text-center py-20 text-brown-400">
                    Không có sản phẩm
                </div>
            @endforelse

        </div>

        @if(method_exists($products, 'hasPages') && $products->hasPages())
            <div id="pagination" class="flex justify-center mt-8 gap-2 flex-wrap">
                @if ($products->onFirstPage())
                    <span class="px-3 py-2 bg-gray-100 text-gray-400 rounded-lg">←</span>
                @else
                    <a href="{{ $products->previousPageUrl() }}"
                       class="px-3 py-2 bg-white border border-brown-200 text-brown-700 rounded-lg hover:bg-brown-50">
                        ←
                    </a>
                @endif

                @foreach ($products->getUrlRange(1, $products->lastPage()) as $page => $url)
                    @if ($page == $products->currentPage())
                        <span class="px-3 py-2 bg-brown-600 text-white rounded-lg">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}"
                           class="px-3 py-2 bg-white border border-brown-200 text-brown-700 rounded-lg hover:bg-brown-50">
                            {{ $page }}
                        </a>
                    @endif
                @endforeach

                @if ($products->hasMorePages())
                    <a href="{{ $products->nextPageUrl() }}"
                       class="px-3 py-2 bg-white border border-brown-200 text-brown-700 rounded-lg hover:bg-brown-50">
                        →
                    </a>
                @else
                    <span class="px-3 py-2 bg-gray-100 text-gray-400 rounded-lg">→</span>
                @endif
            </div>
        @endif

    </div>

</section>

@push('scripts')
<script>
    window.addEventListener('load', function () {
    const skeleton = document.getElementById('menu-skeleton');
    const grid = document.getElementById('product-grid');

    setTimeout(() => {
        if (skeleton) skeleton.classList.add('hidden');
        if (grid) grid.classList.remove('hidden');
    }, 800);
});
</script>
@endpush