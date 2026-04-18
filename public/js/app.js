// =====================
// State
// =====================
let cart = [];
let searchQuery = '';
let currentCategory = 'all';
let currentPriceRange = '';
let cartOpen = false;
let sidebarOpen = false;

// =====================
// Helpers
// =====================
const $ = (sel) => document.querySelector(sel);
const $$ = (sel) => document.querySelectorAll(sel);

function formatPrice(price) {
    return new Intl.NumberFormat('vi-VN').format(price) + '₫';
}

// =====================
// Toast
// =====================
window.showToast = function(message, type = 'success') {
    const container = document.getElementById('toast-container');
    if (!container) return;

    const configs = {
        success: {
            icon: 'lucide:check-circle',
            colorClass: 'text-green-500',
            borderClass: 'border-green-100',
            bgClass: 'bg-white'
        },
        error: {
            icon: 'lucide:x-circle',
            colorClass: 'text-red-500',
            borderClass: 'border-red-100',
            bgClass: 'bg-white'
        },
        info: {
            icon: 'lucide:info',
            colorClass: 'text-blue-500',
            borderClass: 'border-blue-100',
            bgClass: 'bg-white'
        }
    };

    const config = configs[type] || configs.info;

    const toast = document.createElement('div');
    toast.className = `
        toast pointer-events-auto flex items-center gap-3 p-4 rounded-2xl 
        shadow-2xl border transform transition-all duration-300 
        translate-x-full opacity-0 ${config.borderClass} ${config.bgClass}
        min-w-[300px] max-w-sm
    `;

    toast.innerHTML = `
        <div class="flex-shrink-0 w-6 h-6 flex items-center justify-center rounded-full ${type === 'success' ? 'bg-green-100' : 'bg-red-100'}">
            <span class="iconify ${config.colorClass} text-lg" data-icon="${config.icon}"></span>
        </div>
        <div class="flex-1 text-sm font-medium text-gray-800">${message}</div>
        <button onclick="this.parentElement.remove()" class="text-gray-400 hover:text-gray-600">
            <span class="iconify" data-icon="lucide:x"></span>
        </button>
    `;

    if (container.children.length >= 4) {
    container.removeChild(container.firstChild);
}

    container.appendChild(toast);

    requestAnimationFrame(() => {
        toast.classList.remove('translate-x-full', 'opacity-0');
    });

    setTimeout(() => {
        toast.classList.add('translate-x-full', 'opacity-0');
        setTimeout(() => toast.remove(), 300);
    }, 3000);
};

// =====================
// Cart
// =====================
function addToCart(id, name, price, img) {
    var existing = cart.find(function(c) { return c.id === id; });
    if (existing) {
        existing.qty++;
    } else {
        cart.push({ id: id, name: name, price: price, img: img, qty: 1 });
    }
    updateCartUI();
    showToast('Đã thêm "' + name + '" vào giỏ hàng');
}

function removeFromCart(id) {
    var item = cart.find(function(c) { return c.id === id; });
    cart = cart.filter(function(c) { return c.id !== id; });
    updateCartUI();
    if (item) showToast('Da xoa "' + item.name + '"', 'info');
}

function updateCartUI() {
    var badge = document.getElementById('cart-badge');
    var headerCount = document.getElementById('cart-header-count');
    var itemsEl = document.getElementById('cart-items');
    var emptyEl = document.getElementById('cart-empty');
    var footerEl = document.getElementById('cart-footer');
    var subtotalEl = document.getElementById('cart-subtotal');
    var totalEl = document.getElementById('cart-total');

    if (!badge) return;

    var totalItems = cart.reduce(function(s, c) { return s + c.qty; }, 0);
    var totalPrice = cart.reduce(function(s, c) { return s + c.price * c.qty; }, 0);

    if (totalItems > 0) {
        badge.classList.remove('hidden');
        badge.classList.add('flex');
        badge.textContent = totalItems > 99 ? '99+' : totalItems;
        badge.classList.remove('badge-bounce');
        void badge.offsetWidth;
        badge.classList.add('badge-bounce');
    } else {
        badge.classList.add('hidden');
        badge.classList.remove('flex');
    }

    if (headerCount) headerCount.textContent = totalItems + ' mon';
    if (subtotalEl) subtotalEl.textContent = formatPrice(totalPrice);
    if (totalEl) totalEl.textContent = formatPrice(totalPrice);

    if (cart.length === 0) {
        itemsEl.classList.add('hidden');
        emptyEl.classList.remove('hidden');
        footerEl.classList.add('hidden');
    } else {
        itemsEl.classList.remove('hidden');
        emptyEl.classList.add('hidden');
        footerEl.classList.remove('hidden');

        itemsEl.innerHTML = cart.map(function(item) {
            return '<div class="flex gap-3.5 py-4 border-b border-brown-50 last:border-0 animate-scale-in">'
                + '<img src="' + item.img + '" alt="' + item.name + '" class="w-16 h-16 rounded-xl object-cover shrink-0 bg-brown-50">'
                + '<div class="flex-1 min-w-0">'
                + '<div class="flex items-start justify-between gap-2">'
                + '<h4 class="font-semibold text-sm text-brown-900 truncate">' + item.name + '</h4>'
                + '<button onclick="removeFromCart(' + item.id + ')" class="shrink-0 w-6 h-6 rounded-lg hover:bg-red-50 flex items-center justify-center text-brown-300 hover:text-red-500 transition-all">'
                + '<span class="iconify text-xs" data-icon="lucide:trash-2"></span></button></div>'
                + '<p class="text-xs text-brown-400 mt-0.5">' + formatPrice(item.price) + '</p>'
                + '<div class="flex items-center justify-between mt-2.5">'
                + '<div class="flex items-center gap-1 bg-brown-50 rounded-lg">'
                + '<button onclick="updateQty(' + item.id + ', -1)" class="qty-btn w-7 h-7 rounded-lg flex items-center justify-center text-brown-500 text-sm">−</button>'
                + '<span class="w-7 text-center text-sm font-semibold text-brown-800">' + item.qty + '</span>'
                + '<button onclick="updateQty(' + item.id + ', 1)" class="qty-btn w-7 h-7 rounded-lg flex items-center justify-center text-brown-500 text-sm">+</button>'
                + '</div>'
                + '<span class="font-bold text-sm text-brown-800">' + formatPrice(item.price * item.qty) + '</span>'
                + '</div></div></div>';
        }).join('');
    }
}

function toggleCart() {
    var overlay = document.getElementById('cart-overlay');
    var panel = document.getElementById('cart-panel');
    if (!overlay || !panel) return;

    if (!cartOpen) {
        overlay.classList.remove('hidden');
        panel.classList.remove('hidden');
        panel.classList.remove('close');
        panel.classList.add('open');
        document.body.style.overflow = 'hidden';
        cartOpen = true;
        loadCart();
    } else {
        panel.classList.remove('open');
        panel.classList.add('close');
        overlay.classList.add('hidden');
        document.body.style.overflow = '';
        cartOpen = false;
        setTimeout(function() { if (!cartOpen) panel.classList.add('hidden'); }, 300);
    }
}

function changeQty(delta) {
    let input = document.getElementById('product-qty');
    let hidden = document.getElementById('hidden-qty');

    if (!input || !hidden) return;

    let value = parseInt(input.value) || 1;
    value += delta;

    if (value < 1) value = 1;

    input.value = value;
    hidden.value = value;
}

function openProductModal() {
    const overlay = document.getElementById('product-modal-overlay');
    const content = document.getElementById('product-modal-content');

    if (!overlay || !content) return;

    overlay.classList.remove('opacity-0', 'invisible');
    overlay.classList.add('opacity-100', 'visible');

    content.classList.remove('scale-95');
    content.classList.add('scale-100');

    document.body.style.overflow = 'hidden';
}

function closeProductModal() {
    const overlay = document.getElementById('product-modal-overlay');
    const content = document.getElementById('product-modal-content');

    if (!overlay || !content) return;

    overlay.classList.remove('opacity-100', 'visible');
    overlay.classList.add('opacity-0', 'invisible');

    content.classList.remove('scale-100');
    content.classList.add('scale-95');

    document.body.style.overflow = '';
}


// Hàm tải giỏ hàng
function loadCart() {
    fetch('/api/cart')
    .then(res => res.json())
    .then(data => {

        let btnDeleteAll = document.getElementById('btn-delete-all');
        let badge = document.getElementById('cart-badge');
        let count = document.getElementById('cart-header-count');
        let cartItemsDiv = document.getElementById('cart-items');
        let emptyDiv = document.getElementById('cart-empty');
        let footer = document.getElementById('cart-footer');

        // 1. Tính tổng số lượng
        let totalItems = data.reduce((sum, item) => sum + item.quantity, 0);
        
        // 2. Cập nhật Badge
        if (totalItems > 0) {
            if(badge) {
                badge.classList.remove('hidden');
                badge.classList.add('flex');
                badge.textContent = totalItems > 99 ? '99+' : totalItems;
                badge.classList.remove('scale-0');
                badge.classList.add('scale-100');
            }
        } else {
            if(badge) {
                badge.classList.add('hidden');
                badge.classList.remove('flex');
            }
        }

        // 3. Xử lý hiển thị nút Xóa tất cả
        if (totalItems > 0 && btnDeleteAll) {
            btnDeleteAll.classList.remove('hidden');
        } else {
            if(btnDeleteAll) btnDeleteAll.classList.add('hidden');
        }

        // 4. Render danh sách
        cartItemsDiv.innerHTML = '';

        if (!data || data.length === 0) {
            emptyDiv.classList.remove('hidden');
            footer.classList.add('hidden');
            if(count) count.innerText = '0 món';
            document.getElementById('cart-subtotal').innerText = '0₫';
            document.getElementById('cart-total').innerText = '0₫';
            return;
        }

        emptyDiv.classList.add('hidden');
        footer.classList.remove('hidden');
        if(count) count.innerText = totalItems + ' món';

        let total = 0; 

        data.forEach(item => {
            let price = (item.product && item.product.price) ? Number(item.product.price) : 0;
            let qty = Number(item.quantity) || 0;
            let subtotal = price * qty;
            total += subtotal; 

            if (!item.product) return;

            cartItemsDiv.innerHTML += `
                <div class="flex items-center gap-3 mb-4 border-b border-brown-100 pb-3">

                    <!-- ẢNH -->
                    <img src="${item.product.image_url ?? 'https://via.placeholder.com/80'}" 
                        class="w-16 h-16 object-cover rounded-xl shrink-0 border border-brown-100">

                    <!-- INFO -->
                    <div class="flex-1 min-w-0">
                        <!-- TÊN TO HƠN -->
                        <p class="font-semibold text-brown-900 text-base leading-tight truncate">
                            ${item.product.name}
                        </p>

                        <!-- HÀNG DƯỚI -->
                        <div class="flex items-center justify-between mt-2">

                            <!-- QUANTITY (nhỏ lại) -->
                            <div class="flex items-center gap-1 bg-brown-50 rounded-md px-1 py-0.5 border border-brown-100">
                                <button onclick="updateQty(${item.id}, -1)" 
                                        class="w-5 h-5 rounded hover:bg-white flex items-center justify-center text-brown-600 text-xs">
                                    -
                                </button>

                                <span class="text-xs font-semibold w-5 text-center">
                                    ${item.quantity}
                                </span>

                                <button onclick="updateQty(${item.id}, 1)" 
                                        class="w-5 h-5 rounded hover:bg-white flex items-center justify-center text-brown-600 text-xs">
                                    +
                                </button>
                            </div>

                            <!-- GIÁ + XÓA CÙNG HÀNG -->
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-sm text-brown-900">
                                    ${subtotal.toLocaleString()}đ
                                </span>

                                <!-- NÚT XÓA -->
                                <button onclick="openDeleteModal(${item.id})"
                                    class="w-7 h-7 rounded-lg bg-red-500 hover:bg-red-600 flex items-center justify-center transition-colors"
                                    title="Xóa">
                                    <span class="iconify text-white text-sm" data-icon="lucide:trash-2"></span>
                                </button>
                            </div>

                        </div>
                    </div>

                </div>
            `;
        });

        // Cập nhật tổng tiền (SỬA LỖI HIỂN THỊ)
        document.getElementById('cart-subtotal').innerText = total.toLocaleString() + '₫';
        document.getElementById('cart-total').innerText = total.toLocaleString() + '₫';

    })
    .catch(err => console.log(err));
}

let deleteId = null;

function openDeleteModal(id) {
    deleteId = id;

    const modal = document.getElementById('confirmDeleteModal');
    const box = document.getElementById('confirmDeleteBox');

    modal.classList.remove('opacity-0', 'pointer-events-none');
    modal.classList.add('opacity-100');

    box.classList.remove('scale-95');
    box.classList.add('scale-100');
}

function closeDeleteModal() {
    const modal = document.getElementById('confirmDeleteModal');
    const box = document.getElementById('confirmDeleteBox');

    modal.classList.add('opacity-0', 'pointer-events-none');
    modal.classList.remove('opacity-100');

    box.classList.add('scale-95');
    box.classList.remove('scale-100');
}

// nút xác nhận xóa
document.addEventListener('DOMContentLoaded', function () {
    const btnConfirmDelete = document.getElementById('btnConfirmDelete');
    if (!btnConfirmDelete) return;

    btnConfirmDelete.onclick = function () {
        if (!deleteId) return;

        let formData = new FormData();
        formData.append('cart_item_id', deleteId);

        fetch('/cart/delete-item', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                loadCart();
                showToast('Đã xóa sản phẩm', 'success');
            }
            closeDeleteModal();
        });
    };
});


function updateQty(itemId, delta) {
    fetch('/api/cart/update-qty', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ cart_item_id: itemId, delta: delta })
    })
    .then(res => res.json())
    .then(data => {
        if(data.success) {
            loadCart(); 
        }
    });
}


function showConfirmModal() {
    const modal = document.getElementById('confirmModal');
    const content = document.getElementById('confirmModalContent');
    const error = document.getElementById('modalError');
    error.classList.add('hidden');
    modal.classList.remove('opacity-0', 'pointer-events-none');
    modal.classList.add('opacity-100', 'pointer-events-auto');
    content.classList.remove('scale-95');
    content.classList.add('scale-100');
    document.body.style.overflow = 'hidden';
}

function hideConfirmModal() {
    const modal = document.getElementById('confirmModal');
    const content = document.getElementById('confirmModalContent');
    modal.classList.add('opacity-0', 'pointer-events-none');
    modal.classList.remove('opacity-100', 'pointer-events-auto');
    content.classList.add('scale-95');
    content.classList.remove('scale-100');
    document.body.style.overflow = '';
    resetBtn();
}

function resetBtn() {
    const btn = document.getElementById('btnConfirm');
    btn.disabled = false;
    btn.innerHTML = '<span class="iconify" data-icon="lucide:check"></span> Xác nhận';
}

// =====================
// CHECKOUT MODAL LOGIC (MỚI)
// =====================

// 1. Hàm này được gọi khi bấm "Đặt hàng ngay" ở giỏ hàng
function confirmOrder() {
    // Đóng giỏ hàng lại cho gọn
    toggleCart();

    // Hiển thị Modal thông tin giao hàng
    const modal = document.getElementById('checkoutModal');
    const overlay = document.getElementById('checkoutOverlay');
    const panel = document.getElementById('checkoutPanel');
    const errorEl = document.getElementById('checkoutModalError');

    if (modal) {
        modal.classList.remove('hidden');
        // Reset lỗi cũ và form
        if(errorEl) errorEl.classList.add('hidden');
        document.getElementById('orderForm').reset();

        // Hiệu ứng mở Modal
        setTimeout(() => {
            overlay.classList.remove('opacity-0');
            panel.classList.remove('scale-95', 'opacity-0');
            panel.classList.add('scale-100', 'opacity-100');
        }, 10);
        
        document.body.style.overflow = 'hidden'; // Chặn cuộn trang
    }
}
function openInvoiceModal(orderId) {

    fetch(`/api/orders/${orderId}`)
        .then(res => res.json())
        .then(data => {

            if (!data.success) {
                showToast("Không tìm thấy đơn hàng", "error");
                return;
            }

            const order = data.order;

            // ====== GÁN DATA ======
            document.getElementById('invoice-code').innerText = '#' + order.code;
            document.getElementById('invoice-date').innerText = new Date(order.created_at).toLocaleString();
            document.getElementById('invoice-name').innerText = order.name;
            document.getElementById('invoice-phone').innerText = order.phone;
            document.getElementById('invoice-address').innerText = order.address;
            document.getElementById('invoice-total').innerText = Number(order.total_amount).toLocaleString() + '₫';

            // ====== STATUS ======
            let statusText = "Chờ xử lý";
            let statusClass = "bg-yellow-100 text-yellow-700";

            if (order.status === 'completed') {
                statusText = "Hoàn thành";
                statusClass = "bg-green-100 text-green-700";
            }

            const statusEl = document.getElementById('invoice-status');
            statusEl.innerText = statusText;
            statusEl.className = `mt-1 px-3 py-1 rounded-full text-xs font-bold ${statusClass}`;

            // ====== ITEMS ======
            let itemsHtml = '';

            if (order.items) {
                order.items.forEach(item => {
                    itemsHtml += `
                        <tr class="border-b border-brown-100">
                            <td class="px-4 py-2">${item.name}</td>
                            <td class="px-4 py-2 text-center">x${item.quantity}</td>
                            <td class="px-4 py-2 text-right">
                                ${Number(item.price * item.quantity).toLocaleString()}₫
                            </td>
                        </tr>
                    `;
                });
            }

            document.getElementById('invoice-items-list').innerHTML = itemsHtml;

            // ====== SHOW MODAL ======
            const modal = document.getElementById('invoiceModal');
            const overlay = document.getElementById('invoiceOverlay');
            const panel = document.getElementById('invoicePanel');

            modal.classList.remove('hidden');

            setTimeout(() => {
                overlay.classList.remove('opacity-0');
                panel.classList.remove('scale-95', 'opacity-0');
            }, 10);

        });
}

function closeInvoiceModal() {
    const modal = document.getElementById('invoiceModal');
    const overlay = document.getElementById('invoiceOverlay');
    const panel = document.getElementById('invoicePanel');

    overlay.classList.add('opacity-0');
    panel.classList.add('scale-95', 'opacity-0');

    setTimeout(() => {
        modal.classList.add('hidden');
    }, 200);
}

function printInvoice() {
    const content = document.getElementById('invoicePanel').outerHTML;

    const win = window.open('', '', 'width=900,height=700');

    win.document.write(`
        <html>
        <head>
            <title>Hóa đơn</title>

            <script src="https://cdn.tailwindcss.com"></script>

            <style>
                body {
                    background: white;
                    padding: 30px;
                }

                #invoicePanel {
                    max-width: 700px;
                    margin: auto;
                }

                /* Ẩn nút */
                .no-print {
                    display: none !important;
                }
            </style>
        </head>
        <body>
            ${content}
        </body>
        </html>
    `);

    win.document.close();

    setTimeout(() => {
        win.print();
        win.close();
    }, 500);
}

function showConfirmToast(message, onConfirm) {
    if (confirm(message)) {
        onConfirm();
    }
}

let deleteOrderId = null;

function openDeleteOrderModal(id) {
    deleteOrderId = id;

    const modal = document.getElementById('confirmDeleteOrderModal');
    const box = document.getElementById('confirmDeleteOrderBox');

    modal.classList.remove('opacity-0', 'pointer-events-none');
    modal.classList.add('opacity-100');

    box.classList.remove('scale-95');
    box.classList.add('scale-100');
}

function closeDeleteOrderModal() {
    const modal = document.getElementById('confirmDeleteOrderModal');
    const box = document.getElementById('confirmDeleteOrderBox');

    modal.classList.add('opacity-0', 'pointer-events-none');
    modal.classList.remove('opacity-100');

    box.classList.add('scale-95');
    box.classList.remove('scale-100');
}

function confirmDeleteOrder() {

    if (!deleteOrderId) return;

    fetch(`/orders/${deleteOrderId}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            _method: 'DELETE'
        })
    })
    .then(res => res.json())
    .then(data => {

        if (data.success) {
            showToast('Đã xóa đơn hàng', 'success');

            closeDeleteOrderModal();

            location.reload();
        } else {
            showToast(data.message || 'Lỗi', 'error');
        }

    })
    .catch(() => {
        showToast('Lỗi server', 'error');
    });
}

let searchTimeout;

function searchOrderRealtime(keyword) {

    clearTimeout(searchTimeout);

    // debounce 300ms (tránh spam request)
    searchTimeout = setTimeout(() => {

        fetch(`/lich-su-mua-hang?search=${encodeURIComponent(keyword)}`)
            .then(res => res.text())
            .then(html => {

                // parse HTML trả về
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');

                // lấy danh sách đơn hàng mới
                const newList = doc.querySelector('#order-list');
                const currentList = document.querySelector('#order-list');

                if (newList && currentList) {
                    currentList.innerHTML = newList.innerHTML;
                }

                // cập nhật pagination luôn
                const newPagination = doc.querySelector('#pagination');
                const currentPagination = document.querySelector('#pagination');

                if (newPagination && currentPagination) {
                    currentPagination.innerHTML = newPagination.innerHTML;
                }

            });

    }, 300);
}

function getProcessStatusBadge(processStatus) {
    switch (processStatus) {
        case 'received':
            return {
                text: 'Chờ tiếp nhận',
                className: 'bg-yellow-100 text-yellow-700'
            };
        case 'preparing':
            return {
                text: 'Chuẩn bị đơn hàng',
                className: 'bg-blue-100 text-blue-700'
            };
        case 'shipping':
            return {
                text: 'Đang giao hàng',
                className: 'bg-purple-100 text-purple-700'
            };
        case 'completed':
            return {
                text: 'Hoàn tất đơn hàng',
                className: 'bg-green-100 text-green-700'
            };
        default:
            return {
                text: 'Chờ tiếp nhận',
                className: 'bg-yellow-100 text-yellow-700'
            };
    }
}

function openManageOrderModal(orderId) {
    fetch(`/api/orders/${orderId}`)
        .then(res => res.json())
        .then(data => {
            if (!data.success) {
                showToast(data.message || 'Không tải được dữ liệu đơn hàng', 'error');
                return;
            }

            const order = data.order;

            document.getElementById('manage-order-id').value = order.id;
            document.getElementById('manage-order-code').innerText = '#' + order.code;
            document.getElementById('manage-order-date').innerText = new Date(order.created_at).toLocaleString();
            document.getElementById('manage-order-name').innerText = order.name || '...';
            document.getElementById('manage-order-phone').innerText = order.phone || '...';
            document.getElementById('manage-order-email').innerText = order.buyer_email || 'Không có email';
            document.getElementById('manage-order-address').innerText = order.address || '...';
            document.getElementById('manage-order-total').innerText = Number(order.total_amount).toLocaleString() + '₫';
            document.getElementById('manage-order-process-status').value = order.process_status || 'received';

            const badge = getProcessStatusBadge(order.process_status || 'received');
            const statusEl = document.getElementById('manage-order-status');
            statusEl.className = 'mt-1 px-3 py-1 rounded-full text-xs font-bold inline-block ' + badge.className;
            statusEl.innerText = badge.text;

            let itemsHtml = '';
            if (order.items && order.items.length > 0) {
                order.items.forEach(item => {
                    itemsHtml += `
                        <tr class="border-b border-brown-100">
                            <td class="px-4 py-3 text-brown-900">${item.name}</td>
                            <td class="px-4 py-3 text-center text-brown-700">x${item.quantity}</td>
                            <td class="px-4 py-3 text-right font-medium text-brown-900">
                                ${Number(item.subtotal).toLocaleString()}₫
                            </td>
                        </tr>
                    `;
                });
            } else {
                itemsHtml = `
                    <tr>
                        <td colspan="3" class="px-4 py-4 text-center text-brown-400">
                            Không có sản phẩm
                        </td>
                    </tr>
                `;
            }

            document.getElementById('manage-order-items-list').innerHTML = itemsHtml;

            const modal = document.getElementById('manageOrderModal');
            const overlay = document.getElementById('manageOrderOverlay');
            const panel = document.getElementById('manageOrderPanel');

            modal.classList.remove('hidden');

            setTimeout(() => {
                overlay.classList.remove('opacity-0');
                panel.classList.remove('scale-95', 'opacity-0');
            }, 10);
        })
        .catch(err => {
            console.log(err);
            showToast('Không tải được dữ liệu đơn hàng', 'error');
        });
}

function closeManageOrderModal() {
    const modal = document.getElementById('manageOrderModal');
    const overlay = document.getElementById('manageOrderOverlay');
    const panel = document.getElementById('manageOrderPanel');

    overlay.classList.add('opacity-0');
    panel.classList.add('scale-95', 'opacity-0');

    setTimeout(() => {
        modal.classList.add('hidden');
    }, 200);
}

function submitManageOrder() {
    const orderId = document.getElementById('manage-order-id').value;
    const processStatus = document.getElementById('manage-order-process-status').value;

    fetch(`/orders/${orderId}/update-process`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            process_status: processStatus
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            showToast(data.message || 'Cập nhật đơn hàng thành công', 'success');
            closeManageOrderModal();
            setTimeout(() => location.reload(), 300);
        } else {
            showToast(data.message || 'Cập nhật thất bại', 'error');
        }
    })
    .catch(err => {
        console.log(err);
        showToast('Có lỗi khi cập nhật đơn hàng', 'error');
    });
}


    function closeTrackModal() {
        document.getElementById('trackModal').classList.add('hidden');
        document.getElementById('trackModalContent').innerHTML = '';
        document.body.classList.remove('overflow-hidden');
    }

    async function openTrackModal(orderId) {
    try {
        const response = await fetch(`/don-hang/${orderId}/theo-doi`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });

        if (!response.ok) {
            throw new Error('Lỗi server');
        }

        const html = await response.text();
        console.log(html); // debug

        document.getElementById('trackModalContent').innerHTML = html;
        document.getElementById('trackModal').classList.remove('hidden');
    } catch (e) {
        console.error(e);
        alert('Lỗi load đơn hàng');
    }
}


// 2. Hàm đóng Modal
function closeCheckoutModal() {
    const modal = document.getElementById('checkoutModal');
    const overlay = document.getElementById('checkoutOverlay');
    const panel = document.getElementById('checkoutPanel');

    if (modal) {
        // Hiệu ứng đóng Modal
        overlay.classList.add('opacity-0');
        panel.classList.remove('scale-100', 'opacity-100');
        panel.classList.add('scale-95', 'opacity-0');

        setTimeout(() => {
            modal.classList.add('hidden');
            document.body.style.overflow = ''; // Mở lại cuộn trang
        }, 300);
    }
}

// 3. Hàm này được gọi khi bấm "Xác nhận đặt hàng" BÊN TRONG Modal
function submitOrder() {
    const errorEl = document.getElementById('checkoutModalError');
    
    // Lấy dữ liệu từ các input trong Modal (chú ý ID phải khớp với file checkout_modal.blade.php)
    const name = document.getElementById('checkout_name').value;
    const phone = document.getElementById('checkout_phone').value;
    const address = document.getElementById('checkout_address').value;
    const email = document.getElementById('checkout_email').value;
    const note = document.getElementById('checkout_note').value;
    const paymentMethod = 'cod'; // Mặc định COD

    // Validate phía Client (Kiểm tra rỗng)
    if (!name || !phone || !address || !email) {
        if(errorEl) {
            errorEl.innerText = "Vui lòng điền đầy đủ thông tin các trường có dấu (*)!";
            errorEl.classList.remove('hidden');
        }
        return;
    }

    // Chuẩn bị dữ liệu gửi đi
    const formData = {
        name: name,
        phone: phone,
        email: email,
        address: address,
        note: note,
        payment_method: paymentMethod
    };

    // Hiển thị trạng thái đang tải
    const btn = document.getElementById('btnSubmitOrder');
    const originalText = btn.innerHTML;
    btn.innerHTML = '<span class="iconify animate-spin" data-icon="lucide:loader-2"></span> Đang xử lý...';
    btn.disabled = true;
    if(errorEl) errorEl.classList.add('hidden');

    // Gửi request về Server (Giữ nguyên logic fetch cũ của bạn)
    fetch('/dat-hang', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify(formData),
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            // Thành công: Chuyển hướng
            window.location.href = data.redirect;
        } else {
            // Thất bại: Hiện lỗi trong Modal
            if(errorEl) {
                errorEl.innerText = data.message || "Có lỗi xảy ra, vui lòng thử lại.";
                errorEl.classList.remove('hidden');
            }
            // Reset nút bấm
            btn.innerHTML = originalText;
            btn.disabled = false;
        }
    })
    .catch(() => {
        if(errorEl) {
            errorEl.innerText = "Lỗi kết nối mạng. Vui lòng thử lại.";
            errorEl.classList.remove('hidden');
        }
        btn.innerHTML = originalText;
        btn.disabled = false;
    });
}

// =====================
// Search & Filter
// =====================
function toggleSearch() {
    var bar = document.getElementById('search-bar');
    if (!bar) return;
    bar.classList.toggle('hidden');
    if (!bar.classList.contains('hidden')) {
        setTimeout(function() {
            var input = document.getElementById('search-input');
            if (input) input.focus();
        }, 100);
    }
}

function closeSearch() {
    var bar = document.getElementById('search-bar');
    var input = document.getElementById('search-input');
    if (bar) bar.classList.add('hidden');
    if (input) { input.value = ''; handleSearch(''); }
}

function handleSearch(val) {
    searchQuery = val;
    applyFilters();
}



function applyFilters() {
    let cards = document.querySelectorAll('#product-grid .product-card');

    cards.forEach(card => {
        let cat = card.getAttribute('data-cat');

        if (currentCategory === 'all' || cat === currentCategory) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
}

// =====================
// Sidebar Mobile
// =====================
function toggleSidebar() {
    var sidebar = document.getElementById('sidebar');
    var overlay = document.getElementById('sidebar-overlay');
    if (!sidebar) return;

    sidebarOpen = !sidebarOpen;
    if (sidebarOpen) {
        sidebar.classList.add('mobile-open');
        if (overlay) overlay.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    } else {
        sidebar.classList.remove('mobile-open');
        if (overlay) overlay.classList.add('hidden');
        document.body.style.overflow = '';
    }
}

function filterPrice(range) {
        const items = document.querySelectorAll('.product-card');

        if (!range) {
            items.forEach(item => item.style.display = 'block');
            return;
        }

        const [min, max] = range.split('-').map(Number);

        items.forEach(item => {
            const price = parseInt(item.dataset.price || 0);

            if (price >= min && price <= max) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    }

const bestSellerIds = window.bestSellerIds || [];


function filterCategory(category) {
    const items = document.querySelectorAll('.product-card');

    items.forEach(item => {
        const itemCategory = item.dataset.category;
        const itemId = parseInt(item.dataset.id);
        let isShow = false;

        if (category === 'all') {
            isShow = true;
        } else if (category === 'best-seller') {
            isShow = bestSellerIds.includes(itemId);
        } else {
            isShow = itemCategory === category;
        }

        item.style.display = isShow ? 'block' : 'none';
    });

    document.querySelectorAll('.cat-tab').forEach(tab => {
        tab.classList.remove('text-brown-900', 'border-brown-900');
        tab.classList.add('text-brown-400');
    });

    const activeTab = document.querySelector(`.cat-tab[data-cat="${category}"]`);
    if (activeTab) {
        activeTab.classList.remove('text-brown-400');
        activeTab.classList.add('text-brown-900', 'border-brown-900');
    }
}


function handleSearch(val) {
    searchQuery = (val || '').toLowerCase().trim();
    applyFilters();
}


function filterPrice(range) {
    currentPriceRange = range || '';
    applyFilters();
}


function applyFilters() {
    const items = document.querySelectorAll('.product-card');

    items.forEach(item => {
        const itemCategory = item.dataset.category || item.dataset.cat || '';
        const itemId = parseInt(item.dataset.id || 0);
        const itemPrice = parseInt(item.dataset.price || 0);
        const itemName = (item.dataset.name || item.innerText || '').toLowerCase();

        let matchCategory = false;
        if (currentCategory === 'all') {
            matchCategory = true;
        } else if (currentCategory === 'best-seller') {
            matchCategory = bestSellerIds.includes(itemId);
        } else {
            matchCategory = itemCategory === currentCategory;
        }

        let matchPrice = true;
        if (currentPriceRange) {
            const [min, max] = currentPriceRange.split('-').map(Number);
            if (!isNaN(min) && itemPrice < min) matchPrice = false;
            if (!isNaN(max) && itemPrice > max) matchPrice = false;
        }

        let matchSearch = true;
        if (searchQuery) {
            matchSearch = itemName.includes(searchQuery);
        }

        item.style.display = (matchCategory && matchPrice && matchSearch) ? 'block' : 'none';
    });
}

document.addEventListener('DOMContentLoaded', () => {
    const imgInput = $('#form-image');
    if (imgInput) {
        imgInput.addEventListener('input', (e) => {
            const url = e.target.value.trim();
            const previewBox = $('#img-preview-box');
            const preview = $('#img-preview');

            if (url) {
                preview.src = url;
                previewBox.classList.remove('hidden');
                preview.onerror = () => previewBox.classList.add('hidden');
            } else {
                previewBox.classList.add('hidden');
            }
        });
    }

    const fileInput = $('#form-file');
    if (fileInput) {
        fileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            const previewBox = $('#img-preview-box');
            const preview = $('#img-preview');

            if (!file) return;

            const reader = new FileReader();
            reader.onload = function(ev) {
                preview.src = ev.target.result;
                previewBox.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        });
    }
});

document.addEventListener('submit', function(e) {
    if (e.target.classList.contains('add-to-cart-form')) {
        e.preventDefault();
        e.stopPropagation();

        let formData = new FormData(e.target);

        fetch('/them-vao-gio', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(res => {
            console.log('STATUS:', res.status);
            return res.json();
        })
        .then(data => {
            console.log('DATA:', data);

            showToast(data.message, data.success ? 'success' : 'error');

            if (data.success) {
                loadCart();
            }
        })
        .catch(err => {
            console.log('ERROR:', err);
            showToast('Có lỗi xảy ra', 'error');
        });
    }
});

document.addEventListener('DOMContentLoaded', function() {
    loadCart();
});