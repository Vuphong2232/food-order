/**
 * Món Ngon — Manage Page JS
 * CRUD: Load, Add, Edit, Delete products via API
 */

const API_URL = '/products';
let deleteTargetId = null;
let manageProducts = [];

// =====================
// Category labels
// =====================
const catLabels = {
    'mon-chinh':   'Món chính',
    'mon-an-vat':  'Ăn vặt',
    'do-uong':     'Đồ uống',
    'trang-mieng': 'Tráng miệng',
};

// =====================
// Load Products
// =====================
async function loadProducts() {
    const loading = $('#table-loading');
    const empty   = $('#table-empty');
    const tbody   = $('#manage-table-body');

    loading.classList.remove('hidden');
    empty.classList.add('hidden');
    empty.classList.remove('flex');
    if (tbody) tbody.innerHTML = '';

    try {
        const res = await fetch(API_URL);
        const json = await res.json();
        manageProducts = json.data || [];

        loading.classList.add('hidden');

        if (manageProducts.length === 0) {
            empty.classList.remove('hidden');
            empty.classList.add('flex');
        } else {
            renderTable();
        }

        updateStats();
    } catch (err) {
        loading.classList.add('hidden');
        showToast('Lỗi tải dữ liệu: ' + err.message, 'error');
    }
}

// =====================
// Render Table
// =====================
function renderTable() {
    const tbody = $('#manage-table-body');
    if (!tbody) return;

    tbody.innerHTML = manageProducts.map((p, i) => `
        <div class="table-row grid grid-cols-12 gap-4 px-5 py-4 border-b border-brown-50 items-center animate-fade-in-up" style="animation-delay: ${i * 30}ms">
            <div class="col-span-1 text-sm text-brown-400 font-medium">${p.id}</div>
            <div class="col-span-3 flex items-center gap-3 min-w-0">
                <img src="${p.image || 'https://picsum.photos/seed/default/80/80.jpg'}" alt="${p.name}"
                     class="w-11 h-11 rounded-xl object-cover shrink-0 bg-brown-50 border border-brown-100">
                <div class="min-w-0">
                    <p class="text-sm font-semibold text-brown-900 truncate">${p.name}</p>
                    <p class="text-xs text-brown-400 truncate">${p.description || '—'}</p>
                </div>
            </div>
            <div class="col-span-2">
                <span class="badge-cat inline-flex px-2.5 py-1 rounded-lg text-xs font-medium">${catLabels[p.category] || 'Khác'}</span>
            </div>
            <div class="col-span-2 text-sm font-bold text-brown-800">${formatPrice(p.price)}</div>
            <div class="col-span-2">
                <span class="${p.is_active ? 'badge-active' : 'badge-inactive'} inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-medium">
                    <span class="w-1.5 h-1.5 rounded-full ${p.is_active ? 'bg-green-500' : 'bg-red-400'}"></span>
                    ${p.is_active ? 'Hiển thị' : 'Ẩn'}
                </span>
            </div>
            <div class="col-span-2 flex items-center justify-end gap-1.5">
                <button onclick="editProduct(${p.id})"
                        class="action-btn w-9 h-9 rounded-xl bg-blue-50 hover:bg-blue-100 flex items-center justify-center text-blue-600" title="Sửa">
                    <span class="iconify text-base" data-icon="lucide:pencil"></span>
                </button>
                <button onclick="openDeleteModal(${p.id}, '${p.name.replace(/'/g, "\\'")}')"
                        class="action-btn w-9 h-9 rounded-xl bg-red-50 hover:bg-red-100 flex items-center justify-center text-red-500" title="Xóa">
                    <span class="iconify text-base" data-icon="lucide:trash-2"></span>
                </button>
            </div>
        </div>
    `).join('');
}

// =====================
// Update Stats
// =====================
function updateStats() {
    const total = manageProducts.length;
    const active = manageProducts.filter(p => p.is_active).length;
    const cats = new Set(manageProducts.map(p => p.category)).size;
    const avg = total > 0 ? Math.round(manageProducts.reduce((s, p) => s + p.price, 0) / total) : 0;

    const elTotal = $('#stat-total');
    const elActive = $('#stat-active');
    const elCats = $('#stat-categories');
    const elAvg = $('#stat-avg-price');

    if (elTotal) elTotal.textContent = total;
    if (elActive) elActive.textContent = active;
    if (elCats) elCats.textContent = cats;
    if (elAvg) elAvg.textContent = formatPrice(avg);
}

// =====================
// Modal: Open/Close
// =====================
function openModal(product = null) {
    const modal = $('#product-modal');
    const title = $('#modal-title');
    const btn = $('#form-submit-btn');
    const formId = $('#form-id');

    // Reset form
    $('#product-form').reset();
    $('#img-preview-box').classList.add('hidden');
    formId.value = '';

    if (product) {
        title.textContent = 'Chỉnh sửa món ăn';
        btn.textContent = 'Cập nhật';
        formId.value = product.id;
        $('#form-name').value = product.name;
        $('#form-price').value = product.price;
        $('#form-desc').value = product.description || '';
        $('#form-image').value = product.image || '';
        $('#form-category').value = product.category || 'mon-chinh';
        $('#form-status').value = product.is_active ? '1' : '0';

        if (product.image) {
            $('#img-preview').src = product.image;
            $('#img-preview-box').classList.remove('hidden');
        }
    } else {
        title.textContent = 'Thêm món mới';
        btn.textContent = 'Thêm món';
    }

    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';

    setTimeout(() => $('#form-name').focus(), 100);
}

function closeModal() {
    const modal = $('#product-modal');
    modal.classList.add('hidden');
    document.body.style.overflow = '';
}

// =====================
// Edit Product
// =====================
function editProduct(id) {
    const product = manageProducts.find(p => p.id === id);
    if (product) openModal(product);
}

// =====================
// Submit Form (Add / Update)
// =====================
async function submitProduct(e) {
    e.preventDefault();

    const id        = $('#form-id').value;
    const name      = $('#form-name').value.trim();
    const price     = parseInt($('#form-price').value) || 0;
    const desc      = $('#form-desc').value.trim();
    const image     = $('#form-image').value.trim();
    const category  = $('#form-category').value;
    const is_active = $('#form-status').value === '1';

    if (!name) {
        showToast('Vui lòng nhập tên món ăn', 'warning');
        return;
    }
    if (price <= 0) {
        showToast('Vui lòng nhập giá lớn hơn 0', 'warning');
        return;
    }

    const payload = { name, price, description: desc, image, category, is_active };

    try {
        let res;
        if (id) {
            // Update
            res = await fetch(`${API_URL}/${id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                },
                body: JSON.stringify(payload),
            });
        } else {
            // Create
            res = await fetch(API_URL, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                },
                body: JSON.stringify(payload),
            });
        }

        const json = await res.json();

        if (res.ok) {
            showToast(json.message || (id ? 'Cập nhật thành công!' : 'Thêm thành công!'));
            closeModal();
            loadProducts();
        } else {
            // Validation errors
            if (json.errors) {
                const firstError = Object.values(json.errors)[0][0];
                showToast(firstError, 'error');
            } else {
                showToast(json.message || 'Có lỗi xảy ra', 'error');
            }
        }
    } catch (err) {
        showToast('Lỗi kết nối: ' + err.message, 'error');
    }
}

// =====================
// Delete
// =====================
function openDeleteModal(id, name) {
    deleteTargetId = id;
    $('#delete-msg').textContent = `Bạn có chắc muốn xóa "${name}"? Hành động này không thể hoàn tác.`;
    $('#delete-modal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeDeleteModal() {
    deleteTargetId = null;
    $('#delete-modal').classList.add('hidden');
    document.body.style.overflow = '';
}

async function confirmDelete() {
    if (!deleteTargetId) return;

    try {
        const res = await fetch(`${API_URL}/${deleteTargetId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
            },
        });

        const json = await res.json();

        if (res.ok) {
            showToast(json.message || 'Xóa thành công!');
            closeDeleteModal();
            loadProducts();
        } else {
            showToast(json.message || 'Xóa thất bại', 'error');
        }
    } catch (err) {
        showToast('Lỗi kết nối: ' + err.message, 'error');
    }
}

// =====================
// Image Preview
// =====================
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

    // Load products on init
    loadProducts();
});

// === LOGIC THÔNG BÁO ADMIN ===

// 1. Hàm mở/đóng Dropdown
function toggleNotificationDropdown() {
    const dropdown = document.getElementById('notificationDropdown');
    
    if (dropdown.classList.contains('hidden')) {
        // Mở
        dropdown.classList.remove('hidden');
        // Thêm timeout nhỏ để CSS transition hoạt động
        requestAnimationFrame(() => {
            dropdown.classList.remove('scale-95', 'opacity-0');
            dropdown.classList.add('scale-100', 'opacity-100');
        });
    } else {
        closeNotificationDropdown();
    }
}

// 2. Hàm đóng Dropdown
function closeNotificationDropdown() {
    const dropdown = document.getElementById('notificationDropdown');
    if (dropdown) {
        dropdown.classList.remove('scale-100', 'opacity-100');
        dropdown.classList.add('scale-95', 'opacity-0');
        
        // Đợi animation xong rồi mới ẩn hoàn toàn
        setTimeout(() => {
            dropdown.classList.add('hidden');
        }, 200);
    }
}

// 3. Hàm Xóa tất cả thông báo
function clearAllNotifications() {
    if(confirm('Bạn có chắc muốn xóa tất cả thông báo?')) {
        fetch('/admin/notifications/clear', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                // Tải lại trang để cập nhật giao diện
                window.location.reload();
            }
        })
        .catch(error => console.error('Error:', error));
    }
}

// 4. Đóng dropdown khi click ra ngoài
document.addEventListener('click', function(event) {
    const container = document.querySelector('.relative.group'); // Container bao quanh nút trong sidebar
    const dropdown = document.getElementById('notificationDropdown');
    
    // Nếu dropdown đang mở và click ra ngoài container
    if (dropdown && !dropdown.classList.contains('hidden') && !container.contains(event.target)) {
        closeNotificationDropdown();
    }
});