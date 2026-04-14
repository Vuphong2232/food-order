

<div id="invoiceModal" class="fixed inset-0 z-[90] hidden" role="dialog" aria-modal="true">
    <!-- Overlay mờ -->
    <div id="invoiceOverlay" class="fixed inset-0 bg-brown-950/60 backdrop-blur-sm transition-opacity opacity-0" onclick="closeInvoiceModal()"></div>

    <div class="fixed inset-0 z-10 w-screen overflow-y-auto pointer-events-none">
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <!-- Khung Modal -->
            <div id="invoicePanel" class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-2xl scale-95 opacity-0 pointer-events-auto">
                
                <!-- Header Modal -->
                <div class="bg-brown-600 px-6 py-4 flex justify-between items-center shrink-0">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-white/10 rounded-lg">
                            <span class="iconify text-xl text-white" data-icon="lucide:file-text"></span>
                        </div>
                        <div>
                            <h3 class="font-serif font-bold text-lg text-white">Hóa đơn chi tiết</h3>
                            <p class="text-xs text-brown-100" id="invoice-code">#Mã đơn hàng</p>
                        </div>
                    </div>
                    <button onclick="closeInvoiceModal()" class="no-print text-white/70 hover:text-white p-1.5 rounded-full hover:bg-white/10 transition-colors">
                        <span class="iconify text-xl" data-icon="lucide:x"></span>
                    </button>
                </div>

                <!-- Nội dung Modal -->
                <div class="px-6 py-6 bg-brown-50/30">
                    
                    <!-- Thông tin trạng thái & Ngày -->
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <span class="text-[10px] font-bold text-brown-400 uppercase tracking-wider">Trạng thái</span>
                            <div id="invoice-status" class="mt-1 px-3 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-600 inline-block">
                                ...
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-[10px] font-bold text-brown-400 uppercase tracking-wider">Ngày đặt</span>
                            <div id="invoice-date" class="mt-1 text-sm font-semibold text-brown-900">
                                ...
                            </div>
                        </div>
                    </div>

                    <!-- Thông tin khách hàng -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div class="bg-white p-4 rounded-xl border border-brown-100 shadow-sm">
                            <h4 class="text-[10px] font-bold text-brown-400 uppercase mb-2">Người nhận</h4>
                            <p id="invoice-name" class="text-sm font-semibold text-brown-900 mb-1">...</p>
                            <p id="invoice-phone" class="text-sm text-brown-600">...</p>
                        </div>
                        <div class="bg-white p-4 rounded-xl border border-brown-100 shadow-sm">
                            <h4 class="text-[10px] font-bold text-brown-400 uppercase mb-2">Giao tới</h4>
                            <p id="invoice-address" class="text-sm text-brown-600 leading-relaxed">...</p>
                        </div>
                    </div>

                    <!-- Bảng món ăn -->
                    <div class="bg-white rounded-xl border border-brown-100 shadow-sm overflow-hidden mb-6">
                        <div class="px-4 py-3 bg-brown-50 border-b border-brown-100">
                            <h4 class="text-xs font-bold text-brown-700 uppercase">Danh sách món</h4>
                        </div>
                        <div class="max-h-60 overflow-y-auto">
                            <table class="w-full text-sm text-left">
                                <tbody id="invoice-items-list">
                                    <!-- JS sẽ chèn dòng vào đây -->
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Tổng tiền -->
                    <div class="flex justify-between items-center pt-4 border-t-2 border-dashed border-brown-200">
                        <span class="text-base font-bold text-brown-700">Tổng cộng</span>
                        <span id="invoice-total" class="text-2xl font-extrabold text-brown-900 font-serif">0₫</span>
                    </div>

                </div>

                <!-- Footer Modal -->
                <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3 border-t border-brown-100 shrink-0">
                    <button onclick="printInvoice()" 
                        class="no-print inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-lg">
                        <span class="iconify mr-2" data-icon="lucide:printer"></span>
                        In hóa đơn
                    </button>
                    <button onclick="closeInvoiceModal()" class="no-print px-4 py-2 bg-white border border-brown-300 text-brown-700 text-sm font-semibold rounded-lg hover:bg-brown-50 transition-colors">
                        Đóng
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>