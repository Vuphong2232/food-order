<div class="px-8 pt-6 pb-4">
    <div class="flex items-start justify-between gap-4 flex-wrap">
        <div>
            <h2 class="font-serif text-2xl font-bold text-brown-900 leading-tight">
                Danh sách sản phẩm
            </h2>
            <p class="text-sm text-brown-400 mt-1">
                Quản lý tất cả món ăn trong hệ thống
            </p>
        </div>

        <button onclick="openModal()"
            class="h-11 px-5 rounded-xl bg-brown-600 text-white font-medium
                   hover:bg-brown-700 transition-all shadow-lg shadow-brown-600/20
                   flex items-center gap-2 shrink-0">
            <span class="iconify text-lg" data-icon="lucide:plus"></span>
            Thêm sản phẩm
        </button>
    </div>
</div>
<div class="px-8 pt-6">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl border border-brown-100/60 p-5">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-xl bg-brown-50 flex items-center justify-center">
                    <span class="iconify text-xl text-brown-600" data-icon="lucide:utensils-crossed"></span>
                </div>
            </div>
            <p id="stat-total" class="text-2xl font-bold text-brown-900 font-serif">0</p>
            <p class="text-xs text-brown-400 mt-1">Tổng sản phẩm</p>
        </div>
        <div class="bg-white rounded-2xl border border-brown-100/60 p-5">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-xl bg-green-50 flex items-center justify-center">
                    <span class="iconify text-xl text-green-600" data-icon="lucide:check-circle-2"></span>
                </div>
            </div>
            <p id="stat-active" class="text-2xl font-bold text-brown-900 font-serif">0</p>
            <p class="text-xs text-brown-400 mt-1">Đang hiển thị</p>
        </div>
        <div class="bg-white rounded-2xl border border-brown-100/60 p-5">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center">
                    <span class="iconify text-xl text-blue-600" data-icon="lucide:tag"></span>
                </div>
            </div>
            <p id="stat-categories" class="text-2xl font-bold text-brown-900 font-serif">0</p>
            <p class="text-xs text-brown-400 mt-1">Danh mục</p>
        </div>
        <div class="bg-white rounded-2xl border border-brown-100/60 p-5">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-xl bg-yellow-50 flex items-center justify-center">
                    <span class="iconify text-xl text-yellow-600" data-icon="lucide:trending-up"></span>
                </div>
            </div>
            <p id="stat-avg-price" class="text-2xl font-bold text-brown-900 font-serif">0₫</p>
            <p class="text-xs text-brown-400 mt-1">Giá trung bình</p>
        </div>
    </div>
</div>