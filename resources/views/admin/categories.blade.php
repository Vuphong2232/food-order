@extends('layouts.app')

@section('title', 'Quản Lý Danh Mục')

@section('sidebar')
    @include('shared.sidebar')
@endsection

@section('content')
<section class="px-8 py-8 max-w-6xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-brown-900">Danh sách danh mục</h1>
        <button onclick="openCategoryModal()" class="px-4 py-2 bg-brown-600 text-white rounded-xl hover:bg-brown-700 shadow-lg flex items-center gap-2">
            <span class="iconify" data-icon="lucide:plus-circle"></span>
            Thêm danh mục
        </button>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-brown-100 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-brown-50 text-brown-700 text-xs uppercase tracking-wider font-semibold">
                    <th class="px-6 py-4">ID</th>
                    <th class="px-6 py-4">Tên danh mục</th>
                    <th class="px-6 py-4 text-center">Số sản phẩm</th>
                    <th class="px-6 py-4 text-center">Trạng thái</th>
                    <th class="px-6 py-4 text-right">Hành động</th>
                </tr>
            </thead>
            <tbody id="category-list" class="divide-y divide-brown-100">
                <!-- Dữ liệu sẽ được JS render ở đây -->
            </tbody>
        </table>
    </div>
</section>

<!-- Modal Danh Mục -->
<div id="category-modal" class="fixed inset-0 z-[80] hidden">
    <div class="absolute inset-0 bg-brown-950/40 backdrop-blur-sm" onclick="closeCategoryModal()"></div>
    <div class="absolute inset-0 flex items-center justify-center p-4 pointer-events-none">
        <div class="pointer-events-auto bg-cream rounded-3xl shadow-2xl w-full max-w-md animate-scale-in">
            <div class="flex items-center justify-between px-6 py-5 border-b border-brown-100">
                <h3 id="cat-modal-title" class="font-serif font-bold text-lg text-brown-900">Thêm danh mục</h3>
                <button onclick="closeCategoryModal()" class="w-9 h-9 rounded-xl hover:bg-brown-50 flex items-center justify-center text-brown-400 hover:text-brown-700">
                    <span class="iconify text-lg" data-icon="lucide:x"></span>
                </button>
            </div>
            <form id="category-form" class="px-6 py-5 space-y-4">
                <input type="hidden" id="cat-id">
                <div>
                    <label class="block text-sm font-medium text-brown-700 mb-1.5">Tên danh mục <span class="text-red-500">*</span></label>
                    <input id="cat-name" type="text" required placeholder="VD: Món ăn chính" class="w-full h-11 px-4 bg-white border border-brown-200 rounded-xl text-sm">
                </div>
                <div class="flex items-center gap-2">
                    <input type="checkbox" id="cat-status" class="w-5 h-5 rounded text-brown-600 border-brown-300 focus:ring-brown-500">
                    <label for="cat-status" class="text-sm text-brown-700">Hiển thị danh mục</label>
                </div>
                <div class="flex gap-3 pt-2">
                    <button type="button" onclick="closeCategoryModal()" class="flex-1 h-11 bg-white border border-brown-200 text-brown-600 font-medium rounded-xl hover:bg-brown-50">Hủy</button>
                    <button type="submit" class="flex-1 h-11 bg-brown-600 text-white font-medium rounded-xl hover:bg-brown-700">Lưu</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
@push('scripts')
<script>
    function renderCategorySkeleton() {
        const list = document.getElementById('category-list');
        if (!list) return;

        list.innerHTML = Array.from({ length: 5 }).map(() => `
            <tr>
                <td class="px-6 py-4"><div class="h-4 w-10 rounded skeleton"></div></td>
                <td class="px-6 py-4"><div class="h-4 w-32 rounded skeleton"></div></td>
                <td class="px-6 py-4 text-center"><div class="h-4 w-10 mx-auto rounded skeleton"></div></td>
                <td class="px-6 py-4 text-center"><div class="h-6 w-16 mx-auto rounded-full skeleton"></div></td>
                <td class="px-6 py-4 text-right">
                    <div class="flex justify-end gap-2">
                        <div class="w-6 h-6 rounded skeleton"></div>
                        <div class="w-6 h-6 rounded skeleton"></div>
                    </div>
                </td>
            </tr>
        `).join('');
    }

    function loadCategories() {
        renderCategorySkeleton();

        fetch('/api/categories')
            .then(res => res.json())
            .then(data => {
                const list = document.getElementById('category-list');

                if (!list || !data.data) {
                    showToast('Không có dữ liệu danh mục', 'error');
                    list.innerHTML = '';
                    return;
                }

                list.innerHTML = data.data.map(cat => `
                    <tr class="hover:bg-brown-50 transition-colors">
                        <td class="px-6 py-4 text-sm text-brown-500">#${cat.id}</td>
                        <td class="px-6 py-4 font-semibold text-brown-900">${cat.name}</td>
                        <td class="px-6 py-4 text-center text-sm text-brown-600">${cat.products_count ?? 0}</td>
                        <td class="px-6 py-4 text-center">
                            <span class="${cat.is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600'} px-3 py-1 rounded-full text-xs font-bold">
                                ${cat.is_active ? 'Hiển thị' : 'Ẩn'}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <button onclick="editCategory(${cat.id}, '${cat.name.replace(/'/g, "\\'")}')" class="text-blue-600 hover:text-blue-800">
                                <span class="iconify" data-icon="lucide:edit-2"></span>
                            </button>
                            <button onclick="deleteCategory(${cat.id})" class="text-red-500 hover:text-red-700">
                                <span class="iconify" data-icon="lucide:trash-2"></span>
                            </button>
                        </td>
                    </tr>
                `).join('');
            })
            .catch(err => {
                console.error(err);
                showToast('Không tải được danh mục', 'error');
                const list = document.getElementById('category-list');
                if (list) list.innerHTML = '';
            });
    }

    function openCategoryModal() {
        document.getElementById('category-form').reset();
        document.getElementById('cat-id').value = '';
        document.getElementById('cat-modal-title').innerText = 'Thêm danh mục';
        document.getElementById('category-modal').classList.remove('hidden');
    }

    function closeCategoryModal() {
        document.getElementById('category-modal').classList.add('hidden');
    }

    function editCategory(id, name) {
        document.getElementById('cat-id').value = id;
        document.getElementById('cat-name').value = name;
        document.getElementById('cat-modal-title').innerText = 'Sửa danh mục';
        document.getElementById('category-modal').classList.remove('hidden');
    }

    function deleteCategory(id) {
        if (confirm('Bạn có chắc chắn muốn xóa danh mục này?')) {
            fetch(`/categories/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    showToast('Đã xóa danh mục', 'success');
                    loadCategories();
                } else {
                    showToast(data.message || 'Xóa thất bại', 'error');
                }
            })
            .catch(err => {
                console.error(err);
                showToast('Lỗi kết nối server', 'error');
            });
        }
    }

    document.getElementById('category-form').addEventListener('submit', function(e) {
        e.preventDefault();

        const id = document.getElementById('cat-id').value;
        const name = document.getElementById('cat-name').value;
        const isActive = document.getElementById('cat-status').checked;

        const url = id ? `/categories/${id}` : '/categories';
        const method = id ? 'PUT' : 'POST';

        fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                name: name,
                is_active: isActive
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.message) {
                showToast(data.message, 'success');
            } else {
                showToast('Lưu danh mục thành công', 'success');
            }
            closeCategoryModal();
            loadCategories();
        })
        .catch(err => {
            console.error(err);
            showToast('Lỗi kết nối server', 'error');
        });
    });

    document.addEventListener('DOMContentLoaded', function () {
        loadCategories();
    });
</script>
@endpush
@endsection