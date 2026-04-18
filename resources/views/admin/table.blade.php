<!-- SKELETON -->
<div id="manage-skeleton" class="px-8 py-6 space-y-6">
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5">
        <div class="bg-white rounded-3xl border border-brown-100 p-5 shadow-sm">
            <div class="w-10 h-10 skeleton rounded-2xl mb-3"></div>
            <div class="h-7 w-16 skeleton rounded mb-2"></div>
            <div class="h-3 w-24 skeleton rounded"></div>
        </div>
        <div class="bg-white rounded-3xl border border-brown-100 p-5 shadow-sm">
            <div class="w-10 h-10 skeleton rounded-2xl mb-3"></div>
            <div class="h-7 w-16 skeleton rounded mb-2"></div>
            <div class="h-3 w-24 skeleton rounded"></div>
        </div>
        <div class="bg-white rounded-3xl border border-brown-100 p-5 shadow-sm">
            <div class="w-10 h-10 skeleton rounded-2xl mb-3"></div>
            <div class="h-7 w-16 skeleton rounded mb-2"></div>
            <div class="h-3 w-24 skeleton rounded"></div>
        </div>
        <div class="bg-white rounded-3xl border border-brown-100 p-5 shadow-sm">
            <div class="w-10 h-10 skeleton rounded-2xl mb-3"></div>
            <div class="h-7 w-20 skeleton rounded mb-2"></div>
            <div class="h-3 w-28 skeleton rounded"></div>
        </div>
    </div>

    <div class="bg-white rounded-3xl border border-brown-100 overflow-hidden shadow-sm">
        <div class="grid grid-cols-12 gap-4 px-5 py-4">
            <div class="col-span-1 skeleton h-3 rounded"></div>
            <div class="col-span-3 skeleton h-3 rounded"></div>
            <div class="col-span-2 skeleton h-3 rounded"></div>
            <div class="col-span-2 skeleton h-3 rounded"></div>
            <div class="col-span-2 skeleton h-3 rounded"></div>
            <div class="col-span-2 skeleton h-3 rounded"></div>
        </div>
    </div>
</div>

<!-- REAL CONTENT -->
<div id="manage-real-content" class="hidden">
    <div class="px-8 pt-6 pb-4">
        <div class="px-6 py-4">
            <div class="grid grid-cols-1 xl:grid-cols-3 items-center gap-4">
                
                <!-- Bên trái -->
                <div class="text-left">
                    <h2 class="text-3xl font-bold text-brown-900 tracking-tight">
                        Danh sách sản phẩm
                    </h2>
                    <p class="text-sm text-brown-400 mt-1">
                        Quản lý tất cả món ăn trong hệ thống
                    </p>
                </div>

                <!-- Ở giữa -->
                <div class="flex justify-center">
                    <div class="relative w-full max-w-md">
                        <input
                            type="text"
                            id="manage-search-input"
                            placeholder="Tìm kiếm món ăn..."
                            class="search-box w-full pl-11 pr-4 py-3 rounded-full border border-brown-200 bg-white text-sm text-brown-900 placeholder-brown-400 focus:outline-none transition-all shadow-sm"
                        >
                        <span class="iconify absolute left-4 top-1/2 -translate-y-1/2 text-brown-400 text-sm"
                            data-icon="lucide:search"></span>
                    </div>
                </div>

                <!-- Bên phải -->
                <div class="flex justify-end">
                    <button onclick="openModal()"
                            class="h-11 px-5 bg-brown-600 hover:bg-brown-700 text-white rounded-xl shadow-md shadow-brown-200/50 transition-all font-medium">
                        + Thêm sản phẩm
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- STATS -->
    <div class="px-8 pt-2">
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5">
            <div class="bg-white p-5 rounded-3xl border border-brown-100 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-11 h-11 rounded-2xl bg-orange-50 flex items-center justify-center text-orange-600">
                        <span class="iconify text-lg" data-icon="lucide:package"></span>
                    </div>
                </div>
                <p id="stat-total" class="text-3xl font-bold text-brown-900">0</p>
                <p class="text-sm text-brown-400 mt-1">Tổng sản phẩm</p>
            </div>

            <div class="bg-white p-5 rounded-3xl border border-brown-100 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-11 h-11 rounded-2xl bg-emerald-50 flex items-center justify-center text-emerald-600">
                        <span class="iconify text-lg" data-icon="lucide:eye"></span>
                    </div>
                </div>
                <p id="stat-active" class="text-3xl font-bold text-brown-900">0</p>
                <p class="text-sm text-brown-400 mt-1">Đang hiển thị</p>
            </div>

            <div class="bg-white p-5 rounded-3xl border border-brown-100 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-11 h-11 rounded-2xl bg-amber-50 flex items-center justify-center text-amber-600">
                        <span class="iconify text-lg" data-icon="lucide:layers-3"></span>
                    </div>
                </div>
                <p id="stat-categories" class="text-3xl font-bold text-brown-900">0</p>
                <p class="text-sm text-brown-400 mt-1">Danh mục</p>
            </div>

            <div class="bg-white p-5 rounded-3xl border border-brown-100 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-11 h-11 rounded-2xl bg-sky-50 flex items-center justify-center text-sky-600">
                        <span class="iconify text-lg" data-icon="lucide:wallet"></span>
                    </div>
                </div>
                <p id="stat-avg-price" class="text-3xl font-bold text-brown-900">0₫</p>
                <p class="text-sm text-brown-400 mt-1">Giá trung bình</p>
            </div>
        </div>
    </div>

    <!-- TABLE -->
    <div class="px-8 py-6">
        <div class="bg-white rounded-[28px] border border-brown-100 overflow-hidden shadow-sm">
            
            <div class="px-5 py-4 border-b border-brown-100 bg-brown-50/40">
                <div class="grid grid-cols-12 text-xs font-semibold uppercase tracking-wide text-brown-500">
                    <div class="col-span-1">#</div>
                    <div class="col-span-4">Sản phẩm</div>
                    <div class="col-span-2">Danh mục</div>
                    <div class="col-span-2">Giá</div>
                    <div class="col-span-2">Trạng thái</div>
                    <div class="col-span-1 text-right">Thao tác</div>
                </div>
            </div>

            <div id="manage-table-body"></div>
            <div id="manage-pagination" class="flex justify-center mt-8 gap-2 flex-wrap pb-6"></div>

            <div id="table-empty" class="hidden flex-col items-center justify-center py-16 px-6 text-center">
                <div class="w-16 h-16 rounded-full bg-brown-50 flex items-center justify-center text-brown-400 mb-4">
                    <span class="iconify text-3xl" data-icon="lucide:search-x"></span>
                </div>
                <h3 class="text-lg font-semibold text-brown-800">Không tìm thấy sản phẩm</h3>
                <p class="text-sm text-brown-400 mt-1">
                    Hãy thử từ khóa khác hoặc thêm sản phẩm mới
                </p>
            </div>
        </div>
    </div>
</div>