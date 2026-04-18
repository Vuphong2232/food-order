/**
 * Món Ngon — Manage Page JS
 * CRUD: Load, Add, Edit, Delete products via API
 */

const API_URL = '/products';
let deleteTargetId = null;
let manageProducts = [];
let filteredProducts = [];

let currentPage = 1;
const perPage = 10;

// =====================
// Category labels
// =====================
const catLabels = {
    "mon-an-nhau": 'Món ăn nhậu',
    "mon-an-kem": 'Món ăn kèm',
    "mon-an-chinh": 'Món ăn chính',
    "nuoc-giai-khat": 'Nước giải khát',
};

// =====================
// Load Products
// =====================
async function loadProducts() {
    const empty = document.getElementById('table-empty');
    const tbody = document.getElementById('manage-table-body');
    const skeleton = document.getElementById('manage-skeleton');
    const realContent = document.getElementById('manage-real-content');

    if (skeleton) skeleton.classList.remove('hidden');
    if (realContent) realContent.classList.add('hidden');

    if (empty) {
        empty.classList.add('hidden');
        empty.classList.remove('flex');
    }

    if (tbody) tbody.innerHTML = '';

    try {
        const res = await fetch('/products');
        const json = await res.json();

        manageProducts = json.data || [];
        filteredProducts = [...manageProducts];
        currentPage = 1;

        if (skeleton) skeleton.classList.add('hidden');
        if (realContent) realContent.classList.remove('hidden');

        renderTable(filteredProducts);
        updateStats(filteredProducts);
    } catch (err) {
        console.error(err);

        if (skeleton) skeleton.classList.add('hidden');
        if (realContent) realContent.classList.remove('hidden');

        if (tbody) {
            tbody.innerHTML = `
                <div class="py-10 text-center text-red-500">
                    Không tải được dữ liệu sản phẩm
                </div>
            `;
        }
    }
}

// =====================
// Update Stats
// =====================
function updateStats(products = manageProducts) {
    const total = products.length;

    const active = products.filter(p => Number(p.is_active) === 1).length;

    const categories = new Set(
        products.map(p => p.category?.id || p.category_id).filter(Boolean)
    ).size;

    const avg = total > 0
        ? Math.round(products.reduce((sum, p) => sum + Number(p.price || 0), 0) / total)
        : 0;

    const statTotal = document.getElementById('stat-total');
    const statActive = document.getElementById('stat-active');
    const statCategories = document.getElementById('stat-categories');
    const statAvgPrice = document.getElementById('stat-avg-price');

    if (statTotal) statTotal.textContent = total;
    if (statActive) statActive.textContent = active;
    if (statCategories) statCategories.textContent = categories;
    if (statAvgPrice) statAvgPrice.textContent = formatPrice(avg);
}

// =====================
// Render Table
// =====================
function renderTable(products = manageProducts) {
    const tbody = $('#manage-table-body');
    const empty = $('#table-empty');
    const pagination = $('#manage-pagination');

    if (!tbody) return;

    tbody.innerHTML = '';

    if (!products || products.length === 0) {
        if (empty) {
            empty.classList.remove('hidden');
            empty.classList.add('flex');
        }
        if (pagination) pagination.innerHTML = '';
        return;
    }

    if (empty) {
        empty.classList.add('hidden');
        empty.classList.remove('flex');
    }

    const totalPages = Math.ceil(products.length / perPage);

    if (currentPage > totalPages) {
        currentPage = totalPages || 1;
    }

    const start = (currentPage - 1) * perPage;
    const end = start + perPage;
    const paginatedProducts = products.slice(start, end);

    tbody.innerHTML = paginatedProducts.map((p, i) => {
        let imageSrc = 'https://picsum.photos/seed/default/80/80.jpg';

        if (p.image) {
            if (p.image.startsWith('http://') || p.image.startsWith('https://')) {
                imageSrc = p.image;
            } else {
                imageSrc = '/storage/' + p.image;
            }
        }

        return `
            <div class="table-row grid grid-cols-12 gap-4 px-5 py-4 border-b border-brown-50 items-center animate-fade-in-up" style="animation-delay: ${i * 30}ms">
                <div class="col-span-1 text-sm text-brown-400 font-medium">${start + i + 1}</div>
                <div class="col-span-3 flex items-center gap-3 min-w-0">
                    <img src="${imageSrc}" alt="${p.name}"
                         class="w-11 h-11 rounded-xl object-cover shrink-0 bg-brown-50 border border-brown-100">
                    <div class="min-w-0">
                        <p class="text-sm font-semibold text-brown-900 truncate">${p.name}</p>
                        <p class="text-xs text-brown-400 truncate">${p.description || '—'}</p>
                    </div>
                </div>
                <div class="col-span-2">
                    <span class="badge-cat inline-flex px-2.5 py-1 rounded-lg text-xs font-medium">${p.category?.name || 'Khác'}</span>
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
        `;
    }).join('');

    renderPagination(products);
}

function renderPagination(products = filteredProducts) {
    const pagination = $('#manage-pagination');
    if (!pagination) return;

    const totalPages = Math.ceil(products.length / perPage);

    if (totalPages <= 1) {
        pagination.innerHTML = '';
        return;
    }

    let html = '';

    if (currentPage === 1) {
        html += `<span class="px-3 py-2 bg-gray-100 text-gray-400 rounded-lg">←</span>`;
    } else {
        html += `
            <button onclick="goToPage(${currentPage - 1})"
                    class="px-3 py-2 bg-white border border-brown-200 text-brown-700 rounded-lg hover:bg-brown-50">
                ←
            </button>
        `;
    }

    for (let page = 1; page <= totalPages; page++) {
        if (page === currentPage) {
            html += `<span class="px-3 py-2 bg-brown-600 text-white rounded-lg">${page}</span>`;
        } else {
            html += `
                <button onclick="goToPage(${page})"
                        class="px-3 py-2 bg-white border border-brown-200 text-brown-700 rounded-lg hover:bg-brown-50">
                    ${page}
                </button>
            `;
        }
    }

    if (currentPage === totalPages) {
        html += `<span class="px-3 py-2 bg-gray-100 text-gray-400 rounded-lg">→</span>`;
    } else {
        html += `
            <button onclick="goToPage(${currentPage + 1})"
                    class="px-3 py-2 bg-white border border-brown-200 text-brown-700 rounded-lg hover:bg-brown-50">
                →
            </button>
        `;
    }

    pagination.innerHTML = html;
}

function goToPage(page) {
    const totalPages = Math.ceil(filteredProducts.length / perPage);

    if (page < 1 || page > totalPages) return;

    currentPage = page;
    renderTable(filteredProducts);

    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
}

// =====================
// Update Stats
// =====================
function filterManageProducts(keyword = '') {
    const q = keyword.trim().toLowerCase();

    if (!q) {
        filteredProducts = [...manageProducts];
    } else {
        filteredProducts = manageProducts.filter(product => {
            const name = (product.name || '').toLowerCase();
            const description = (product.description || '').toLowerCase();
            const category = (product.category?.name || '').toLowerCase();
            const price = String(product.price || '');

            return (
                name.includes(q) ||
                description.includes(q) ||
                category.includes(q) ||
                price.includes(q)
            );
        });
    }
    currentPage = 1;
    renderTable(filteredProducts);
    updateStats(filteredProducts);
}

function initManageSearch() {
    const input = document.getElementById('manage-search-input');
    if (!input) return;

    input.addEventListener('input', function () {
        filterManageProducts(this.value);
    });
}

// =====================
// Modal: Open/Close
// =====================
function loadCategoriesForDropdown(selectedId = '') {
    console.log('CALL API CATEGORY');
    fetch('/api/categories')
        .then(res => res.json())
        .then(data => {
            const select = document.getElementById('form-category');
            if (!select || !data.data) return;

            // reset select (KHÔNG dùng selected)
            let html = `<option value="">Chọn danh mục</option>`;

            data.data.forEach(cat => {
                html += `
                    <option value="${cat.id}" ${String(selectedId) === String(cat.id) ? 'selected' : ''}>
                        ${cat.name}
                    </option>
                `;
            });

            select.innerHTML = html;
        })
        .catch(err => {console.error(err);
        showToast('Lỗi kết nối server', 'error');
    });
}


function openModal(product = null) {
    loadCategoriesForDropdown(product ? product.category_id : '');
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
        $('#form-category').value = product.category_id || '';
        $('#form-status').value = product.is_active ? '1' : '0';

        if (product.image) {
        let imageSrc = product.image;

        if (!product.image.startsWith('http://') && !product.image.startsWith('https://')) {
            imageSrc = '/storage/' + product.image;
        }

    $('#img-preview').src = imageSrc;
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

    const id = $('#form-id').value;
    const name = $('#form-name').value.trim();
    const price = parseInt($('#form-price').value) || 0;

    if (!name) {
        showToast('Vui lòng nhập tên món ăn', 'warning');
        return;
    }

    if (price <= 0) {
        showToast('Vui lòng nhập giá lớn hơn 0', 'warning');
        return;
    }

    const formData = new FormData();
    formData.append('name', name);
    formData.append('price', $('#form-price').value);
    formData.append('description', $('#form-desc').value.trim());
    const imageValue = $('#form-image').value.trim();

    if (imageValue) {
        const isHttpUrl = imageValue.startsWith('http://') || imageValue.startsWith('https://');
        const isGoogleUrl = imageValue.includes('google.com') || imageValue.includes('gstatic.com');
        const isBase64 = imageValue.startsWith('data:image/');

        if (isBase64) {
            showToast('Không dùng chuỗi base64 cho ô URL hình ảnh', 'error');
            return;
        }

        if (!isHttpUrl) {
            showToast('URL hình ảnh không hợp lệ', 'error');
            return;
        }

        if (isGoogleUrl) {
            showToast('Không nên dùng link ảnh sao chép từ Google, hãy dùng link ảnh trực tiếp', 'error');
            return;
        }

        formData.append('image', imageValue);
    }
    formData.append('category_id', $('#form-category').value);
    formData.append('is_active', $('#form-status').value);

    const fileInput = $('#form-file');
    if (fileInput && fileInput.files.length > 0) {
        formData.append('image_file', fileInput.files[0]);
    }

    try {
        let res;

        if (id) {
            formData.append('_method', 'PUT');

            res = await fetch(`${API_URL}/${id}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    'Accept': 'application/json',
                },
                body: formData,
            });
        } else {
            res = await fetch(API_URL, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    'Accept': 'application/json',
                },
                body: formData,
            });
        }

        const json = await res.json();

        if (res.ok) {
            showToast(json.message || (id ? 'Cập nhật thành công!' : 'Thêm thành công!'));
            closeModal();
            loadProducts();
        } else {
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
    const fileInput = $('#form-file');

        if (fileInput) {
            fileInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (!file) return;

                // ❗ QUAN TRỌNG: xóa URL cũ (tránh base64)
                $('#form-image').value = '';

                const reader = new FileReader();
                reader.onload = function(ev) {
                    $('#img-preview').src = ev.target.result;
                    $('#img-preview-box').classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            });
        }

    // Load products on init
    loadProducts();
    initManageSearch();
});