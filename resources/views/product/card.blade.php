<div class="product-card bg-white rounded-2xl border border-brown-100/60 overflow-hidden group animate-fade-in-up"
     style="animation-delay: {{ $index * 60 }}ms"
     data-name="{{ $product->name }}"
     data-desc="{{ $product->description ?? '' }}"
     data-cat="{{ $product->category }}">

    <div class="relative aspect-square overflow-hidden bg-brown-50">
        <img src="{{ $product->image }}" alt="{{ $product->name }}"
             class="product-img w-full h-full object-cover" loading="lazy">

        <div class="absolute inset-0 bg-gradient-to-t from-brown-950/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>

        {{-- BUTTON ADD TO CART --}}
        <form action="{{ route('cart.store') }}" method="POST"
              class="absolute bottom-3 right-3 opacity-0 group-hover:opacity-100 translate-y-2 group-hover:translate-y-0 transition-all duration-300">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">

            <button type="submit"
                class="w-10 h-10 bg-white/90 backdrop-blur-sm rounded-xl flex items-center justify-center text-brown-600 shadow-lg hover:scale-105 transition">
                <span class="iconify text-lg" data-icon="lucide:plus"></span>
            </button>
        </form>
    </div>

    <div class="p-3.5">
        <h3 class="font-semibold text-sm text-brown-900 truncate mb-0.5">
            {{ $product->name }}
        </h3>

        <p class="text-xs text-brown-400 truncate mb-2.5">
            {{ $product->description ?? '' }}
        </p>

        <div class="flex items-center justify-between">
            <span class="font-bold text-brown-700 text-sm">
                {{ number_format($product->price) }}₫
            </span>

            {{-- MOBILE BUTTON --}}
            <form action="{{ route('cart.store') }}" method="POST" class="md:hidden">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">

                <button type="submit"
                    class="w-8 h-8 rounded-lg bg-brown-50 hover:bg-brown-100 flex items-center justify-center text-brown-600 transition">
                    <span class="iconify text-sm" data-icon="lucide:plus"></span>
                </button>
            </form>
        </div>
    </div>
</div>