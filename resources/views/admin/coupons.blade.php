@extends('layouts.app')

@section('title', 'Quản Lý Mã Giảm Giá')

@section('sidebar')
    @include('shared.sidebar')
@endsection

@section('content')
<section class="px-8 py-8 max-w-6xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-brown-900">Danh sách mã giảm giá</h1>
        <button onclick="openCouponModal()" class="px-4 py-2 bg-brown-600 text-white rounded-xl hover:bg-brown-700 shadow-lg flex items-center gap-2">
            <span class="iconify" data-icon="lucide:plus-circle"></span>
            Thêm mã giảm giá
        </button>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-brown-100 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-brown-50 text-brown-700 text-xs uppercase tracking-wider font-semibold">
                    <th class="px-6 py-4">ID</th>
                    <th class="px-6 py-4">Mã Code</th>
                    <th class="px-6 py-4 text-center">% Giảm giá</th>
                    <th class="px-6 py-4 text-center">Trạng thái</th>
                    <th class="px-6 py-4 text-right">Hành động</th>
                </tr>
            </thead>
            <tbody id="coupon-list" class="divide-y divide-brown-100">
                <!-- Dữ liệu sẽ được JS render ở đây -->
            </tbody>
        </table>
    </div>
</section>

<!-- Modal Mã Giảm Giá -->
<div id="coupon-modal" class="fixed inset-0 z-[80] hidden">
    <div class="absolute inset-0 bg-brown-950/40 backdrop-blur-sm" onclick="closeCouponModal()"></div>
    <div class="absolute inset-0 flex items-center justify-center p-4 pointer-events-none">
        <div class="pointer-events-auto bg-cream rounded-3xl shadow-2xl w-full max-w-md animate-scale-in">
            <div class="flex items-center justify-between px-6 py-5 border-b border-brown-100">
                <h3 id="coupon-modal-title" class="font-serif font-bold text-lg text-brown-900">Thêm mã giảm giá</h3>
                <button onclick="closeCouponModal()" class="w-9 h-9 rounded-xl hover:bg-brown-50 flex items-center justify-center text-brown-400 hover:text-brown-700">
                    <span class="iconify text-lg" data-icon="lucide:x"></span>
                </button>
            </div>
            <form id="coupon-form" class="px-6 py-5 space-y-4">
                <input type="hidden" id="coupon-id">
                
                <div>
                    <label class="block text-sm font-medium text-brown-700 mb-1.5">Mã Code <span class="text-red-500">*</span></label>
                    <input id="coupon-code" type="text" required placeholder="VD: MONNGON10" class="w-full h-11 px-4 bg-white border border-brown-200 rounded-xl text-sm uppercase font-bold tracking-wider">
                </div>

                <div>
                    <label class="block text-sm font-medium text-brown-700 mb-1.5">% Giảm giá (1-100) <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <input id="coupon-discount" type="number" min="1" max="100" required placeholder="VD: 10" class="w-full h-11 px-4 bg-white border border-brown-200 rounded-xl text-sm pl-4 pr-10">
                        <span class="absolute right-4 top-1/2 -translate-y-1/2 text-brown-400 font-bold">%</span>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <input type="checkbox" id="coupon-status" checked class="w-5 h-5 rounded text-brown-600 border-brown-300 focus:ring-brown-500">
                    <label for="coupon-status" class="text-sm text-brown-700">Kích hoạt mã ngay</label>
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="button" onclick="closeCouponModal()" class="flex-1 h-11 bg-white border border-brown-200 text-brown-600 font-medium rounded-xl hover:bg-brown-50">Hủy</button>
                    <button type="submit" class="flex-1 h-11 bg-brown-600 text-white font-medium rounded-xl hover:bg-brown-700">Lưu</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function renderCouponSkeleton() {
        const list = document.getElementById('coupon-list');
        if (!list) return;

        list.innerHTML = Array.from({ length: 5 }).map(() => `
            <tr>
                <td class="px-6 py-4"><div class="h-4 w-10 rounded skeleton"></div></td>
                <td class="px-6 py-4"><div class="h-4 w-24 rounded skeleton"></div></td>
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

    function loadCoupons() {
        renderCouponSkeleton();

        fetch('/api/coupons')
            .then(res => res.json())
            .then(data => {
                const list = document.getElementById('coupon-list');

                if (!list || !data.data) {
                    showToast('Không có dữ liệu mã giảm giá', 'error');
                    list.innerHTML = '';
                    return;
                }

                list.innerHTML = data.data.map(coupon => `
                    <tr class="hover:bg-brown-50 transition-colors">
                        <td class="px-6 py-4 text-sm text-brown-500">#${coupon.id}</td>
                        <td class="px-6 py-4 font-bold text-brown-900 uppercase tracking-wide bg-orange-50/50 text-orange-700 inline-block px-2 py-0.5 rounded border border-orange-100">
                            ${coupon.code}
                        </td>
                        <td class="px-6 py-4 text-center text-sm text-brown-600 font-medium">
                            ${coupon.discount_percent}%
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="${coupon.is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600'} px-3 py-1 rounded-full text-xs font-bold">
                                ${coupon.is_active ? 'Hoạt động' : 'Tạm dừng'}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <button onclick="editCoupon(${coupon.id}, '${coupon.code.replace(/'/g, "\\'")}', ${coupon.discount_percent}, ${coupon.is_active})" class="text-blue-600 hover:text-blue-800">
                                <span class="iconify" data-icon="lucide:edit-2"></span>
                            </button>
                            <button onclick="deleteCoupon(${coupon.id})" class="text-red-500 hover:text-red-700">
                                <span class="iconify" data-icon="lucide:trash-2"></span>
                            </button>
                        </td>
                    </tr>
                `).join('');
            })
            .catch(err => {
                console.error(err);
                showToast('Không tải được danh sách mã', 'error');
                const list = document.getElementById('coupon-list');
                if (list) list.innerHTML = '';
            });
    }

    function openCouponModal() {
        document.getElementById('coupon-form').reset();
        document.getElementById('coupon-id').value = '';
        document.getElementById('coupon-status').checked = true; // Mặc định checked khi thêm mới
        document.getElementById('coupon-modal-title').innerText = 'Thêm mã giảm giá';
        document.getElementById('coupon-modal').classList.remove('hidden');
    }

    function closeCouponModal() {
        document.getElementById('coupon-modal').classList.add('hidden');
    }

    function editCoupon(id, code, discount, isActive) {
        document.getElementById('coupon-id').value = id;
        document.getElementById('coupon-code').value = code;
        document.getElementById('coupon-discount').value = discount;
        document.getElementById('coupon-status').checked = isActive === 1;
        document.getElementById('coupon-modal-title').innerText = 'Sửa mã giảm giá';
        document.getElementById('coupon-modal').classList.remove('hidden');
    }

    function deleteCoupon(id) {
        if (confirm('Bạn có chắc chắn muốn xóa mã giảm giá này?')) {
            fetch(`/coupons/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.message) {
                    showToast(data.message, 'success');
                } else {
                    showToast('Xóa thành công', 'success');
                }
                loadCoupons();
            })
            .catch(err => {
                console.error(err);
                showToast('Lỗi kết nối server', 'error');
            });
        }
    }

    document.getElementById('coupon-form').addEventListener('submit', function(e) {
        e.preventDefault();

        const id = document.getElementById('coupon-id').value;
        const code = document.getElementById('coupon-code').value;
        const discount = document.getElementById('coupon-discount').value;
        const isActive = document.getElementById('coupon-status').checked;

        const url = id ? `/coupons/${id}` : '/coupons';
        const method = id ? 'PUT' : 'POST';

        fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                code: code,
                discount_percent: discount,
                is_active: isActive
            })
        })
        .then(res => {
            if (!res.ok) throw res; // Throw error to catch block
            return res.json();
        })
        .then(data => {
            if (data.message) {
                showToast(data.message, 'success');
            }
            closeCouponModal();
            loadCoupons();
        })
        .catch(err => {
            err.json().then(data => {
                showToast(data.message || 'Có lỗi xảy ra', 'error');
            }).catch(() => {
                showToast('Lỗi không xác định', 'error');
            });
        });
    });

    document.addEventListener('DOMContentLoaded', function () {
        loadCoupons();
    });
</script>
@endpush
@endsection