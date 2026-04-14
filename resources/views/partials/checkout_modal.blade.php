<!-- ================= MODAL FORM NHẬP THÔNG TIN ĐẶT HÀNG ================= -->
<div id="checkoutModal" class="fixed inset-0 z-[80] hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <!-- Background Overlay -->
    <div class="fixed inset-0 bg-brown-950/60 backdrop-blur-sm transition-opacity opacity-0" id="checkoutOverlay"></div>

    <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <!-- Modal Panel -->
            <div class="relative transform overflow-hidden rounded-2xl bg-cream text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg scale-95 opacity-0" id="checkoutPanel">
                
                <!-- Header Modal -->
                <div class="bg-brown-50 px-4 py-3 sm:px-6 flex justify-between items-center border-b border-brown-100 shrink-0">
                    <div>
                        <h3 class="font-serif font-bold text-lg text-brown-900" id="modal-title">Thông tin giao hàng</h3>
                        <p class="text-xs text-brown-500 mt-0.5">Vui lòng điền thông tin để hoàn tất đơn hàng</p>
                    </div>
                    <button type="button" onclick="closeCheckoutModal()" class="text-brown-400 hover:text-red-500 transition-colors rounded-full p-1 hover:bg-brown-100">
                        <span class="iconify text-xl" data-icon="lucide:x"></span>
                    </button>
                </div>

                <!-- Body Modal (Form Input) -->
                <div class="px-4 py-5 sm:p-6 bg-white">
                    <form id="orderForm" class="space-y-4">
                        @csrf
                        
                        <!-- Họ và Tên -->
                        <div>
                            <label for="checkout_name" class="block text-sm font-medium text-brown-700 mb-1">Họ và tên <span class="text-red-500">*</span></label>
                            <input type="text" name="name" id="checkout_name" required 
                                class="block w-full rounded-lg border border-brown-200 bg-brown-50/50 px-3 py-2.5 text-sm text-brown-900 shadow-sm focus:border-brown-500 focus:outline-none focus:ring-2 focus:ring-brown-100 transition-all" 
                                placeholder="Nhập họ tên của bạn">
                        </div>

                        <!-- Số điện thoại -->
                        <div>
                            <label for="checkout_phone" class="block text-sm font-medium text-brown-700 mb-1">Số điện thoại <span class="text-red-500">*</span></label>
                           <input type="tel" 
                                name="phone" 
                                id="checkout_phone" 
                                required
                                pattern="[0-9]{9,11}"
                                inputmode="numeric"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                class="block w-full rounded-lg border border-brown-200 bg-brown-50/50 px-3 py-2.5 text-sm text-brown-900 shadow-sm focus:border-brown-500 focus:outline-none focus:ring-2 focus:ring-brown-100 transition-all" 
                                placeholder="09xx xxx xxx">
                        </div>

                        <!-- Địa chỉ nhận hàng -->
                        <div>
                            <label for="checkout_address" class="block text-sm font-medium text-brown-700 mb-1">Địa chỉ nhận hàng <span class="text-red-500">*</span></label>
                            <textarea name="address" id="checkout_address" required rows="2" 
                                class="block w-full rounded-lg border border-brown-200 bg-brown-50/50 px-3 py-2.5 text-sm text-brown-900 shadow-sm focus:border-brown-500 focus:outline-none focus:ring-2 focus:ring-brown-100 transition-all resize-none" 
                                placeholder="Số nhà, tên đường, phường/xã, quận/huyện..."></textarea>
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="checkout_email" class="block text-sm font-medium text-brown-700 mb-1">Email <span class="text-red-500">*</span></label>
                            <input type="email" name="email" id="checkout_email" required 
                                class="block w-full rounded-lg border border-brown-200 bg-brown-50/50 px-3 py-2.5 text-sm text-brown-900 shadow-sm focus:border-brown-500 focus:outline-none focus:ring-2 focus:ring-brown-100 transition-all" 
                                placeholder="email@example.com">
                        </div>

                        <!-- Ghi chú -->
                        <div>
                            <label for="checkout_note" class="block text-sm font-medium text-brown-700 mb-1">Ghi chú (Tùy chọn)</label>
                            <textarea name="note" id="checkout_note" rows="2" 
                                class="block w-full rounded-lg border border-brown-200 bg-brown-50/50 px-3 py-2.5 text-sm text-brown-900 shadow-sm focus:border-brown-500 focus:outline-none focus:ring-2 focus:ring-brown-100 transition-all resize-none" 
                                placeholder="Ví dụ: Giao giờ hành chính, bớt hành..."></textarea>
                        </div>

                        <!-- Phương thức thanh toán (Mặc định COD) -->
                        <input type="hidden" name="payment_method" value="cod">

                        <!-- Hiển thị lỗi (Màu đỏ, dễ thấy) -->
                        <p id="checkoutModalError" class="text-red-600 text-sm font-medium mt-2 hidden text-center bg-red-50 py-2 rounded-lg border border-red-100"></p>
                    </form>
                </div>

                <!-- Footer Modal -->
                <div class="bg-brown-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 gap-2 border-t border-brown-100 shrink-0">
                    <button type="button" onclick="submitOrder()" id="btnSubmitOrder" class="inline-flex w-full justify-center items-center rounded-xl bg-brown-600 px-3 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-brown-700 sm:w-auto transition-all">
                        <span class="iconify mr-1.5" data-icon="lucide:check-circle"></span>
                        Xác nhận đặt hàng
                    </button>
                    <button type="button" onclick="closeCheckoutModal()" class="mt-3 inline-flex w-full justify-center rounded-xl bg-white px-3 py-2.5 text-sm font-semibold text-brown-900 shadow-sm ring-1 ring-inset ring-brown-300 hover:bg-brown-50 sm:mt-0 sm:w-auto transition-all">
                        Hủy bỏ
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>