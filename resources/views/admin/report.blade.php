
<div id="statsSkeleton" class="p-4 md:p-8 space-y-8">
    
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="h-8 w-56 skeleton mb-3"></div>
            <div class="h-4 w-72 skeleton"></div>
        </div>

        <div class="bg-white p-1.5 rounded-2xl shadow-sm border border-brown-100 inline-flex w-full md:w-auto gap-2">
            <div class="h-10 flex-1 md:w-28 skeleton rounded-xl"></div>
            <div class="h-10 flex-1 md:w-28 skeleton rounded-xl"></div>
            <div class="h-10 flex-1 md:w-28 skeleton rounded-xl"></div>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        @for($i = 0; $i < 4; $i++)
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-brown-100">
                <div class="flex items-center justify-between mb-4">
                    <div class="h-4 w-28 skeleton"></div>
                    <div class="w-8 h-8 rounded-full skeleton"></div>
                </div>
                <div class="h-8 w-32 skeleton mb-3"></div>
                <div class="h-4 w-24 skeleton"></div>
            </div>
        @endfor
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 bg-white p-6 rounded-3xl shadow-sm border border-brown-100">
            <div class="flex items-center justify-between mb-6">
                <div class="h-6 w-40 skeleton"></div>
                <div class="h-4 w-24 skeleton"></div>
            </div>
            <div class="w-full h-[300px] skeleton rounded-2xl"></div>
        </div>

        <div class="bg-white p-6 rounded-3xl shadow-sm border border-brown-100 flex flex-col">
            <div class="h-6 w-40 skeleton mb-6"></div>

            <div class="flex-1 space-y-4">
                @for($i = 0; $i < 3; $i++)
                    <div class="flex items-center gap-4 p-3 rounded-2xl">
                        <div class="w-8 h-8 rounded-lg skeleton"></div>
                        <div class="w-12 h-12 rounded-xl skeleton"></div>
                        <div class="flex-1">
                            <div class="h-4 w-32 skeleton mb-2"></div>
                            <div class="h-3 w-24 skeleton"></div>
                        </div>
                        <div class="h-4 w-16 skeleton"></div>
                    </div>
                @endfor
            </div>

            <div class="w-full mt-6 h-10 rounded-xl skeleton"></div>
        </div>
    </div>
</div>

<div id="statsRealContent" class="hidden">
    <div class="p-4 md:p-8 space-y-8 animate-fade-in-up">
        
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-serif font-bold text-brown-900">Báo cáo & Thống kê</h2>
                <p class="text-brown-500 text-sm mt-1">Dữ liệu được cập nhật theo thời gian thực từ đơn hàng.</p>
            </div>

            <div class="bg-white p-1.5 rounded-2xl shadow-sm border border-brown-100 inline-flex w-full md:w-auto">
                <button onclick="updateStats('week')" id="tab-week" class="tab-btn active flex-1 md:flex-none px-6 py-2 rounded-xl text-sm font-semibold transition-all duration-300">
                    Tuần này
                </button>
                <button onclick="updateStats('month')" id="tab-month" class="tab-btn flex-1 md:flex-none px-6 py-2 rounded-xl text-sm font-semibold text-brown-500 hover:bg-brown-50 transition-all duration-300">
                    Tháng này
                </button>
                <button onclick="updateStats('year')" id="tab-year" class="tab-btn flex-1 md:flex-none px-6 py-2 rounded-xl text-sm font-semibold text-brown-500 hover:bg-brown-50 transition-all duration-300">
                    Năm nay
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-brown-100 hover:shadow-lg transition-shadow relative overflow-hidden group">
                <div class="absolute -right-6 -top-6 w-24 h-24 bg-brown-50 rounded-full opacity-50 group-hover:scale-110 transition-transform"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-brown-400 text-sm font-medium">Tổng doanh thu</span>
                        <span class="w-8 h-8 rounded-full bg-green-100 text-green-600 flex items-center justify-center">
                            <span class="iconify text-lg" data-icon="lucide:dollar-sign"></span>
                        </span>
                    </div>
                    <h3 class="text-2xl font-bold text-brown-900" id="stat-revenue">
                        {{ number_format($reportStats['revenue'] ?? 0) }} ₫
                    </h3>
                    <div class="mt-2 text-xs text-green-600 font-medium flex items-center gap-1">
                        <span class="iconify" data-icon="lucide:trending-up"></span>
                        <span>+12.5%</span> <span class="text-brown-400 font-normal">so với kỳ trước</span>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-3xl shadow-sm border border-brown-100 hover:shadow-lg transition-shadow relative overflow-hidden group">
                <div class="absolute -right-6 -top-6 w-24 h-24 bg-blue-50 rounded-full opacity-50 group-hover:scale-110 transition-transform"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-brown-400 text-sm font-medium">Đơn hàng thành công</span>
                        <span class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center">
                            <span class="iconify text-lg" data-icon="lucide:shopping-bag"></span>
                        </span>
                    </div>

                    <h3 class="text-2xl font-bold text-brown-900" id="stat-orders">
                        {{ $reportStats['orders_count'] ?? 0 }}
                    </h3>

                    <a href="{{ route('orders.history', ['filter' => 'processing']) }}"
                    class="mt-2 inline-flex text-xs text-blue-600 font-medium items-center gap-1 hover:text-blue-700 hover:underline">
                        <span class="iconify" data-icon="lucide:activity"></span>
                        <span>Đang xử lý:</span>
                        <span class="text-brown-900 font-bold">{{ $reportStats['processing_orders'] ?? 0 }}</span>
                    </a>
                </div>
            </div>

            <div class="bg-white p-6 rounded-3xl shadow-sm border border-brown-100 hover:shadow-lg transition-shadow relative overflow-hidden group">
                <div class="absolute -right-6 -top-6 w-24 h-24 bg-orange-50 rounded-full opacity-50 group-hover:scale-110 transition-transform"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-brown-400 text-sm font-medium">Sản phẩm bán ra</span>
                        <span class="w-8 h-8 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center">
                            <span class="iconify text-lg" data-icon="lucide:package"></span>
                        </span>
                    </div>
                    <h3 class="text-2xl font-bold text-brown-900" id="stat-products">
                        {{ $reportStats['products_sold'] ?? 0 }}
                    </h3>
                    <div class="mt-2 text-xs text-brown-400">
                        Trung bình <span class="text-brown-900 font-bold">{{ number_format($reportStats['avg_order_value'] ?? 0) }} ₫</span> / đơn
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-3xl shadow-sm border border-brown-100 hover:shadow-lg transition-shadow relative overflow-hidden group">
                <div class="absolute -right-6 -top-6 w-24 h-24 bg-purple-50 rounded-full opacity-50 group-hover:scale-110 transition-transform"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-brown-400 text-sm font-medium">Khách hàng mới</span>
                        <span class="w-8 h-8 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center">
                            <span class="iconify text-lg" data-icon="lucide:users"></span>
                        </span>
                    </div>
                    <h3 class="text-2xl font-bold text-brown-900">
                        {{ $reportStats['new_users'] ?? 0 }}
                    </h3>
                    <div class="mt-2 text-xs text-purple-600 font-medium flex items-center gap-1">
                        <span class="iconify" data-icon="lucide:arrow-up-right"></span>
                        Tăng trưởng tốt
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 bg-white p-6 rounded-3xl shadow-sm border border-brown-100">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold text-brown-900">Biểu đồ doanh thu</h3>
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-brown-500"></span>
                        <span class="text-xs text-brown-500 font-medium">Doanh thu</span>
                    </div>
                </div>
                <div class="chart-container w-full h-[300px]">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>

            <div class="bg-white p-6 rounded-3xl shadow-sm border border-brown-100 flex flex-col">
                <h3 class="text-lg font-bold text-brown-900 mb-6">Sản phẩm bán chạy</h3>
                
                <div class="flex-1 space-y-4">
                    @forelse($topProducts ?? [] as $index => $product)
                    <div class="flex items-center gap-4 p-3 rounded-2xl hover:bg-brown-50 transition-colors group">
                        <div class="w-8 h-8 flex-shrink-0 rounded-lg flex items-center justify-center font-bold text-sm
                            {{ $index == 0 ? 'bg-yellow-100 text-yellow-700' : ($index == 1 ? 'bg-gray-100 text-gray-600' : 'bg-orange-100 text-orange-600') }}">
                            {{ $index + 1 }}
                        </div>
                        
                        <img src="{{ $product->image_url }}"
                        alt="{{ $product->name }}"
                        class="w-12 h-12 rounded-xl object-cover border border-brown-100 shadow-sm">

                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-semibold text-brown-900 truncate group-hover:text-brown-600 transition-colors">
                                {{ $product->name }}
                            </h4>
                            <p class="text-xs text-brown-500 mt-0.5">
                                Đã bán: <span class="font-bold text-brown-700">{{ $product->sold_count ?? 0 }}</span> cái
                            </p>
                        </div>

                        <div class="text-right">
                            <p class="text-sm font-bold text-brown-900">
                                {{ number_format($product->price) }}₫
                            </p>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8 text-brown-400 text-sm">
                        Chưa có dữ liệu bán hàng.
                    </div>
                    @endforelse
                </div>
                
                <button class="w-full mt-6 py-2.5 rounded-xl border border-brown-200 text-brown-600 text-sm font-medium hover:bg-brown-50 hover:border-brown-300 transition">
                    Xem tất cả sản phẩm
                </button>
            </div>
        </div>
    </div>
</div>


@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const chartData = {
        week: {
            labels: ['T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'CN'],
            data: [1200000, 1900000, 300000, 500000, 2300000, 3200000, 4100000],
            total: 13500000
        },
        month: {
            labels: ['Tuần 1', 'Tuần 2', 'Tuần 3', 'Tuần 4'],
            data: [15000000, 22000000, 18000000, 25000000],
            total: 80000000
        },
        year: {
            labels: ['T1', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'T8', 'T9', 'T10', 'T11', 'T12'],
            data: [50, 60, 45, 80, 95, 110, 105, 120, 90, 130, 150, 180].map(x => x * 1000000),
            total: 1215000000
        }
    };

    let myChart = null;

    function initChart(period) {
        const canvas = document.getElementById('revenueChart');
        if (!canvas) return;

        const ctx = canvas.getContext('2d');
        const dataInfo = chartData[period];

        let gradient = ctx.createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, 'rgba(139, 94, 60, 0.2)');
        gradient.addColorStop(1, 'rgba(139, 94, 60, 0)');

        if (myChart) {
            myChart.destroy();
        }

        myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: dataInfo.labels,
                datasets: [{
                    label: 'Doanh thu (VND)',
                    data: dataInfo.data,
                    borderColor: '#8B5E3C',
                    backgroundColor: gradient,
                    borderWidth: 3,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#8B5E3C',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#3B2313',
                        padding: 12,
                        titleFont: { family: 'Inter', size: 13 },
                        bodyFont: { family: 'Inter', size: 13 },
                        callbacks: {
                            label: function(context) {
                                return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(context.parsed.y);
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { borderDash: [4, 4], color: '#E8CBA7', drawBorder: false },
                        ticks: { color: '#8B5E3C', font: { size: 11 } }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { color: '#8B5E3C', font: { size: 11 } }
                    }
                }
            }
        });
    }

    function updateStats(period) {
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.classList.remove('active', 'bg-brown-600', 'text-white', 'shadow-md');
            btn.classList.add('text-brown-500', 'hover:bg-brown-50');
        });

        const activeBtn = document.getElementById(`tab-${period}`);
        if (activeBtn) {
            activeBtn.classList.remove('text-brown-500', 'hover:bg-brown-50');
            activeBtn.classList.add('active', 'bg-brown-600', 'text-white', 'shadow-md');
        }

        initChart(period);
    }

    document.addEventListener('DOMContentLoaded', () => {
        const skeleton = document.getElementById('statsSkeleton');
        const realContent = document.getElementById('statsRealContent');

        setTimeout(() => {
            if (skeleton) skeleton.classList.add('hidden');
            if (realContent) realContent.classList.remove('hidden');
            initChart('week');
        }, 800);
    });
</script>
@endpush

