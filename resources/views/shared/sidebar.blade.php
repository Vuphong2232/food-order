<aside id="sidebar" class="fixed top-0 left-0 bottom-0 w-64 bg-brown-900 z-[150] flex flex-col border-r border-brown-800">

    <div class="h-16 flex items-center gap-2.5 px-6 border-b border-brown-800 shrink-0">
        <div class="w-9 h-9 rounded-xl bg-brown-600 flex items-center justify-center shadow-lg shadow-brown-600/30">
            <span class="iconify text-white text-lg" data-icon="lucide:chef-hat"></span>
        </div>
        <span class="font-serif font-bold text-xl text-white tracking-tight">Món Ngon</span>
    </div>

    @php
        $user = auth()->user();
        $isAdminMode = session('admin_mode', false) && auth()->check() && auth()->user()->role === 'admin';
    @endphp

    <nav class="flex-1 px-4 py-6 space-y-1.5 overflow-y-auto">
        <p class="text-[10px] font-semibold text-brown-500 uppercase tracking-widest px-3 mb-3">Menu</p>

        @if($isAdminMode)
            <a href="{{ route('manage') }}"
            class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('manage') ? 'bg-brown-600 text-white shadow-lg shadow-brown-600/20' : 'text-brown-300 hover:bg-brown-800 hover:text-white' }}">
                <span class="iconify text-lg" data-icon="lucide:settings-2"></span>
                Quản lý sản phẩm
            </a>

            <a href="{{ route('admin.categories') }}"
            class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.categories') ? 'bg-brown-600 text-white shadow-lg shadow-brown-600/20' : 'text-brown-300 hover:bg-brown-800 hover:text-white' }}">
                <span class="iconify text-lg" data-icon="lucide:layers"></span>
                Quản lý danh mục
            </a>

            <a href="{{ route('orders.history') }}"
            class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('orders.history') ? 'bg-brown-600 text-white shadow-lg shadow-brown-600/20' : 'text-brown-300 hover:bg-brown-800 hover:text-white' }}">
                <span class="iconify text-lg" data-icon="lucide:clipboard-list"></span>
                Lịch sử mua hàng
            </a>

            <a href="{{ route('admin.report') }}"
            class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.report') ? 'bg-brown-600 text-white shadow-lg shadow-brown-600/20' : 'text-brown-300 hover:bg-brown-800 hover:text-white' }}">
                <span class="iconify text-lg" data-icon="lucide:bar-chart-3"></span>
                Thống kê
            </a>

            <a href="{{ route('admin.notifications') }}"
            class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.notifications') ? 'bg-brown-600 text-white shadow-lg shadow-brown-600/20' : 'text-brown-300 hover:bg-brown-800 hover:text-white' }}">
                <span class="iconify text-lg" data-icon="lucide:bell"></span>
                Thông báo
            </a>

            <div class="!mt-6">
                <p class="text-[10px] font-semibold text-brown-500 uppercase tracking-widest px-3 mb-3">Điều hướng</p>
            </div>

            <a href="{{ route('admin.mode.off') }}"
            class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 text-brown-300 hover:bg-brown-800 hover:text-white">
                <span class="iconify text-lg" data-icon="lucide:house"></span>
                Quay về giao diện chính
            </a>
        @else
            <a href="{{ route('home') }}"
               class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('home') ? 'bg-brown-600 text-white shadow-lg shadow-brown-600/20' : 'text-brown-300 hover:bg-brown-800 hover:text-white' }}">
                <span class="iconify text-lg" data-icon="lucide:utensils-crossed"></span>
                Giao diện chính
            </a>

            <a href="{{ route('orders.history') }}"
               class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('orders.history') ? 'bg-brown-600 text-white shadow-lg shadow-brown-600/20' : 'text-brown-300 hover:bg-brown-800 hover:text-white' }}">
                <span class="iconify text-lg" data-icon="lucide:clipboard-list"></span>
                Lịch sử mua hàng
            </a>

            <a href="{{ route('user.notifications') }}"
            class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('user.notifications') ? 'bg-brown-600 text-white shadow-lg shadow-brown-600/20' : 'text-brown-300 hover:bg-brown-800 hover:text-white' }}">
                <span class="iconify text-lg" data-icon="lucide:bell"></span>
                Thông báo
            </a>

            @auth
                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('admin.mode.on') }}"
                       class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 text-brown-300 hover:bg-brown-800 hover:text-white">
                        <span class="iconify text-lg" data-icon="lucide:shield"></span>
                        Truy cập admin
                    </a>
                @endif
            @endauth
        @endif
    </nav>
    
   <div class="px-4 py-5 border-t border-brown-800 shrink-0 relative z-[200]">
    <div onclick="toggleProfile(this, event)"
         class="flex items-center gap-3 px-3 py-2.5 rounded-xl bg-brown-800/50 hover:bg-brown-800 transition-all cursor-pointer group border border-brown-700">
        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-orange-400 to-orange-600 flex items-center justify-center text-white text-sm font-bold shadow-lg ring-2 ring-brown-700">
            {{ strtoupper(substr($user->username ?? $user->name, 0, 1)) }}
        </div>

        <div class="flex-1 min-w-0">
            <p class="text-sm font-bold text-white truncate group-hover:text-orange-400 transition-colors">
                {{ $user->username ?? $user->name }}
            </p>
            <p class="text-[11px] text-brown-400 truncate">
                {{ $user->email }}
            </p>
        </div>

        <div class="w-6 h-6 flex items-center justify-center text-brown-500 group-hover:text-white transition-colors">
            <span class="iconify" data-icon="lucide:chevron-up"></span>
        </div>
    </div>

    <div class="profileMenu hidden absolute bottom-full left-4 right-4 mb-2 bg-white rounded-2xl shadow-2xl p-2 text-sm z-[9999] origin-bottom transition-all duration-200 opacity-0 translate-y-2">
        <a href="{{ route('profile.info') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-orange-50 rounded-xl text-brown-700 hover:text-orange-600 transition-colors font-medium">
            <span class="iconify text-lg" data-icon="lucide:user"></span>
            Thông tin cá nhân
        </a>

        <a href="{{ route('profile.security') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-orange-50 rounded-xl text-brown-700 hover:text-orange-600 transition-colors font-medium">
            <span class="iconify text-lg" data-icon="lucide:shield"></span>
            Tài khoản & Bảo mật
        </a>

        <div class="h-px bg-gray-100 my-1"></div>

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 hover:bg-red-50 rounded-xl text-red-500 hover:text-red-600 transition-colors font-medium">
                <span class="iconify text-lg" data-icon="lucide:log-out"></span>
                Đăng xuất
            </button>
        </form>
    </div>
</div>

<script>
    function toggleProfile(el, event) {
        event.stopPropagation();

        const wrapper = el.closest('.relative');
        if (!wrapper) return;

        const menu = wrapper.querySelector('.profileMenu');
        if (!menu) return;

        document.querySelectorAll('.profileMenu').forEach(otherMenu => {
            if (otherMenu !== menu) {
                otherMenu.classList.add('opacity-0', 'translate-y-2');
                setTimeout(() => {
                    otherMenu.classList.add('hidden');
                }, 200);
            }
        });

        if (menu.classList.contains('hidden')) {
            menu.classList.remove('hidden');
            setTimeout(() => {
                menu.classList.remove('opacity-0', 'translate-y-2');
            }, 10);
        } else {
            menu.classList.add('opacity-0', 'translate-y-2');
            setTimeout(() => {
                menu.classList.add('hidden');
            }, 200);
        }
    }

    document.addEventListener('click', function (e) {
        if (!e.target.closest('.profileMenu') && !e.target.closest('[onclick*="toggleProfile"]')) {
            document.querySelectorAll('.profileMenu').forEach(menu => {
                menu.classList.add('opacity-0', 'translate-y-2');
                setTimeout(() => {
                    menu.classList.add('hidden');
                }, 200);
            });
        }
    });
</script>
</aside>

<div id="sidebar-overlay" class="fixed inset-0 bg-brown-950/50 z-30 hidden lg:hidden" onclick="toggleSidebar()"></div>