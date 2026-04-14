<!-- Modal Thêm/Sửa -->
<div id="product-modal" class="fixed inset-0 z-[80] hidden">
    <div class="absolute inset-0 bg-brown-950/40 backdrop-blur-sm" onclick="closeModal()"></div>
    <div class="absolute inset-0 flex items-center justify-center p-4 pointer-events-none">
        <div class="pointer-events-auto bg-cream rounded-3xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto animate-scale-in">

            <div class="flex items-center justify-between px-6 py-5 border-b border-brown-100">
                <h3 id="modal-title" class="font-serif font-bold text-lg text-brown-900">Thêm món mới</h3>
                <button onclick="closeModal()" class="w-9 h-9 rounded-xl hover:bg-brown-50 flex items-center justify-center text-brown-400 hover:text-brown-700 transition-all">
                    <span class="iconify text-lg" data-icon="lucide:x"></span>
                </button>
            </div>

            <form id="product-form" enctype="multipart/form-data" onsubmit="submitProduct(event)" class="px-6 py-5 space-y-4">
                <input type="hidden" id="form-id" value="">

                <div>
                    <label class="block text-sm font-medium text-brown-700 mb-1.5">Tên món ăn <span class="text-red-500">*</span></label>
                    <input id="form-name" type="text" required placeholder="VD: Phở Bò Tái"
                           class="w-full h-11 px-4 bg-white border border-brown-200 rounded-xl text-sm text-brown-900 placeholder:text-brown-300 outline-none focus:border-brown-400 focus:ring-2 focus:ring-brown-400/10 transition-all">
                </div>

                <div>
                    <label class="block text-sm font-medium text-brown-700 mb-1.5">Giá (VNĐ) <span class="text-red-500">*</span></label>
                    <input id="form-price" type="number" required min="0" placeholder="VD: 55000"
                           class="w-full h-11 px-4 bg-white border border-brown-200 rounded-xl text-sm text-brown-900 placeholder:text-brown-300 outline-none focus:border-brown-400 focus:ring-2 focus:ring-brown-400/10 transition-all">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-brown-700 mb-1.5">Danh mục</label>

                        <select name="category" id="form-category"
                            class="w-full h-11 px-4 bg-white border border-brown-200 rounded-xl text-sm text-brown-900 outline-none focus:border-brown-400 focus:ring-2 focus:ring-brown-400/10 transition-all appearance-none cursor-pointer">

                            <option value="mon-anh-nhau">Món ăn nhậu</option>
                            <option value="mon-an-kem">Món ăn kèm</option>
                            <option value="mon-an-chinh">Món ăn chính</option>
                            <option value="nuoc-giai-khat">Nước giải khát</option>

                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-brown-700 mb-1.5">Trạng thái</label>
                        <select id="form-status"
                                class="w-full h-11 px-4 bg-white border border-brown-200 rounded-xl text-sm text-brown-900 outline-none focus:border-brown-400 focus:ring-2 focus:ring-brown-400/10 transition-all appearance-none cursor-pointer">
                            <option value="1">Hiển thị</option>
                            <option value="0">Ẩn</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-brown-700 mb-1.5">Mô tả</label>
                    <input id="form-desc" type="text" placeholder="VD: Nước dùng đậm đà"
                           class="w-full h-11 px-4 bg-white border border-brown-200 rounded-xl text-sm text-brown-900 placeholder:text-brown-300 outline-none focus:border-brown-400 focus:ring-2 focus:ring-brown-400/10 transition-all">
                </div>

                <div>
                    <label class="block text-sm font-medium text-brown-700 mb-1.5">URL Hình ảnh</label>
                     <div class="grid grid-cols-10 gap-2">
                        <input id="form-image" type="text" placeholder="https://..."
                            class="col-span-9 h-11 px-4 bg-white border border-brown-200 rounded-xl text-sm text-brown-900 placeholder:text-brown-300 outline-none focus:border-brown-400 focus:ring-2 focus:ring-brown-400/10 transition-all">

                        <label class="col-span-1 h-11 flex items-center justify-center bg-brown-100 text-brown-700 rounded-xl cursor-pointer hover:bg-brown-200 transition-all border border-brown-200">
                            <span class="iconify text-lg" data-icon="lucide:upload"></span>
                            <input type="file" id="form-file" name="image_file" accept="image/*" class="hidden">
                        </label>
                    </div>
                           <p class="text-xs text-brown-400 mt-1">Dán link ảnh, chọn file hoặc để trống để dùng ảnh mặc định</p>
                </div>

                <div id="img-preview-box" class="hidden">
                    <p class="text-xs text-brown-400 mb-1.5">Xem trước:</p>
                    <img id="img-preview" src="" alt="Preview" class="w-24 h-24 rounded-xl object-cover border border-brown-100">
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="button" onclick="closeModal()"
                            class="flex-1 h-11 bg-white border border-brown-200 text-brown-600 font-medium rounded-xl hover:bg-brown-50 transition-colors">
                        Hủy
                    </button>
                    <button type="submit" id="form-submit-btn"
                            class="flex-1 h-11 bg-brown-600 text-white font-medium rounded-xl hover:bg-brown-700 transition-colors shadow-lg shadow-brown-600/20">
                        Thêm món
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Xác nhận xóa -->
<div id="delete-modal" class="fixed inset-0 z-[90] hidden">
    <div class="absolute inset-0 bg-brown-950/50 backdrop-blur-sm" onclick="closeDeleteModal()"></div>
    <div class="absolute inset-0 flex items-center justify-center p-4 pointer-events-none">
        <div class="pointer-events-auto bg-cream rounded-2xl shadow-2xl w-full max-w-sm p-6 animate-scale-in text-center">
            <div class="w-14 h-14 rounded-full bg-red-50 flex items-center justify-center mx-auto mb-4">
                <span class="iconify text-2xl text-red-500" data-icon="lucide:trash-2"></span>
            </div>
            <h3 class="font-serif font-bold text-lg text-brown-900 mb-1">Xóa sản phẩm</h3>
            <p id="delete-msg" class="text-sm text-brown-500 mb-6">Bạn có chắc muốn xóa món này?</p>
            <div class="flex gap-3">
                <button onclick="closeDeleteModal()" class="flex-1 h-11 bg-white border border-brown-200 text-brown-600 font-medium rounded-xl hover:bg-brown-50 transition-colors">
                    Hủy
                </button>
                <button onclick="confirmDelete()" class="flex-1 h-11 bg-red-500 text-white font-medium rounded-xl hover:bg-red-600 transition-colors">
                    Xóa ngay
                </button>
            </div>
        </div>
    </div>
</div>