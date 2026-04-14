<div class="px-8 py-6">
    <div class="bg-white rounded-2xl border border-brown-100/60 overflow-hidden">

        <div class="grid grid-cols-12 gap-4 px-5 py-3.5 bg-brown-50/50 border-b border-brown-100/60 text-xs font-semibold text-brown-500 uppercase tracking-wider">
            <div class="col-span-1">#</div>
            <div class="col-span-3">Sản phẩm</div>
            <div class="col-span-2">Danh mục</div>
            <div class="col-span-2">Giá</div>
            <div class="col-span-2">Trạng thái</div>
            <div class="col-span-2 text-right">Thao tác</div>
        </div>

        <div id="manage-table-body"></div>

        <div id="table-loading" class="flex items-center justify-center py-16">
            <div class="flex items-center gap-3 text-brown-400">
                <svg class="animate-spin h-5 w-5" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Đang tải dữ liệu...
            </div>
        </div>

        <div id="table-empty" class="hidden flex-col items-center justify-center py-16 text-center">
            <div class="w-16 h-16 rounded-full bg-brown-50 flex items-center justify-center mb-3">
                <span class="iconify text-2xl text-brown-300" data-icon="lucide:package-open"></span>
            </div>
            <p class="text-sm text-brown-500">Chưa có sản phẩm nào</p>
            <button onclick="openModal()" class="mt-3 text-sm font-medium text-brown-600 hover:text-brown-700 underline underline-offset-2">
                Thêm món đầu tiên
            </button>
        </div>
    </div>
</div>