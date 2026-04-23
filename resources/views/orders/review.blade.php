<!-- resources/views/modals/review.blade.php -->

<div id="reviewModal" class="fixed inset-0 z-[99999] hidden">
    <div class="absolute inset-0 bg-black/70 backdrop-blur-sm" onclick="closeReviewModal()"></div>

    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md p-6 transform scale-95 opacity-0 transition-all duration-300" id="reviewModalContent">
            
            <div class="text-center mb-6">
                <div class="w-16 h-16 bg-yellow-50 rounded-full flex items-center justify-center mx-auto mb-3">
                    <span class="iconify text-3xl text-yellow-500" data-icon="lucide:star"></span>
                </div>
                <h3 class="text-xl font-bold text-brown-900">Đánh giá đơn hàng</h3>
                <p class="text-sm text-brown-500">Chia sẻ cảm nhận của bạn về sản phẩm</p>
            </div>

            <form id="reviewForm" onsubmit="submitReview(event)">
                <input type="hidden" name="order_id" id="review_order_id">
                <input type="hidden" name="product_id" id="review_product_id">

                <div class="flex justify-center gap-3 mb-6">
                    @for($i = 1; $i <= 5; $i++)
                        <button type="button"
                                onclick="setRating({{ $i }})"
                                class="text-4xl text-brown-200 hover:text-yellow-400 transition focus:outline-none star-btn transform hover:scale-110 duration-200"
                                data-value="{{ $i }}">
                            <span class="iconify" data-icon="ph:star-fill"></span>
                        </button>
                    @endfor
                    <input type="hidden" name="rating" id="rating_input" value="0" required>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-bold text-brown-800 mb-2">Nhận xét của bạn</label>
                    <textarea name="comment" id="review_comment" rows="3"
                              class="w-full rounded-xl border border-brown-200 bg-brown-50/30 p-3 text-sm focus:outline-none focus:ring-2 focus:ring-brown-500/20 focus:border-brown-500 transition-all resize-none"
                              placeholder="Sản phẩm này thế nào? Món ăn có ngon không?"></textarea>
                </div>

                <div class="flex gap-3">
                    <button type="button" onclick="closeReviewModal()"
                            class="flex-1 py-2.5 rounded-xl border border-brown-200 text-brown-600 font-medium hover:bg-brown-50 transition">
                        Hủy
                    </button>

                    <button type="submit" id="btnSubmitReview"
                            class="flex-1 py-2.5 rounded-xl bg-brown-600 text-white font-bold hover:bg-brown-700 shadow-lg shadow-brown-200 transition flex items-center justify-center gap-2">
                        <span>Gửi đánh giá</span>
                        <span class="iconify" data-icon="lucide:send"></span>
                    </button>
                    
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let selectedRating = 0;

    function openReviewModal(orderId, productId) {
    console.log('openReviewModal:', { orderId, productId });

    document.getElementById('review_order_id').value = orderId;
    document.getElementById('review_product_id').value = productId ?? '';
    document.getElementById('review_comment').value = '';
    setRating(0);

    const modal = document.getElementById('reviewModal');
    const content = document.getElementById('reviewModalContent');

    modal.classList.remove('hidden');

    setTimeout(() => {
        content.classList.remove('scale-95', 'opacity-0');
        content.classList.add('scale-100', 'opacity-100');
    }, 10);
}

    function closeReviewModal() {
        const modal = document.getElementById('reviewModal');
        const content = document.getElementById('reviewModalContent');

        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');

        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }

    function setRating(star) {
        selectedRating = star;
        document.getElementById('rating_input').value = star;

        const stars = document.querySelectorAll('.star-btn');
        stars.forEach((btn, index) => {
            if (index < star) {
                btn.classList.add('text-yellow-400');
                btn.classList.remove('text-brown-200');
            } else {
                btn.classList.remove('text-yellow-400');
                btn.classList.add('text-brown-200');
            }
        });
    }

    async function submitReview(e) {
    e.preventDefault();

    const orderId = document.getElementById('review_order_id').value;
    const productId = document.getElementById('review_product_id').value;
    const rating = document.getElementById('rating_input').value;
    const comment = document.getElementById('review_comment').value;

    console.log({ order_id: orderId, product_id: productId, rating, comment });

    if (!productId || productId === 'undefined') {
        alert('Không tìm thấy sản phẩm để đánh giá');
        return;
    }

    if (!rating || rating == 0) {
        alert('Vui lòng chọn số sao');
        return;
    }

    try {
        const res = await fetch("{{ route('reviews.store') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                order_id: orderId,
                product_id: productId,
                rating: rating,
                comment: comment
            })
        });

        const data = await res.json();
        console.log('review response:', data);

       if (res.ok && data.success) {
    const btn = document.querySelector(`[data-review-btn="true"][data-product-id="${productId}"]`);

    if (btn) {
        btn.outerHTML = `
            <span class="px-3 py-2 bg-green-50 text-green-700 border border-green-200 rounded-lg text-sm font-semibold inline-flex items-center gap-2">
                <span class="iconify text-sm" data-icon="lucide:check"></span>
                Đã đánh giá
            </span>
        `;
    }

    closeReviewModal();

    if (typeof showToast === 'function') {
        showToast(data.message || 'Đánh giá thành công', 'success');
    } else {
        alert(data.message || 'Đánh giá thành công');
    }
} else {
    if (typeof showToast === 'function') {
        showToast(data.message || 'Gửi đánh giá thất bại', 'error');
    } else {
        alert(data.message || 'Gửi đánh giá thất bại');
    }
}
    } catch (error) {
        console.error(error);
        alert('Lỗi kết nối');
    }
}
</script>
@endpush