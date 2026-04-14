// =====================
// State
// =====================
let cart = [];
let searchQuery = '';
let currentCategory = 'all';
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
function showToast(message, type) {
    type = type || 'success';
    var container = document.getElementById('toast-container');
    if (!container) return;

    var icons = {
        success: 'lucide:check-circle-2',
        error: 'lucide:alert-circle',
        info: 'lucide:info',
        warning: 'lucide:alert-triangle'
    };
    var colors = {
        success: 'text-green-500',
        error: 'text-red-500',
        info: 'text-brown-500',
        warning: 'text-yellow-500'
    };

    var toast = document.createElement('div');
    toast.className = 'toast pointer-events-auto flex items-center gap-2.5 bg-white border border-brown-100 shadow-xl shadow-brown-900/10 rounded-2xl px-4 py-3 min-w-[280px]';
    toast.innerHTML = '<span class="iconify ' + (colors[type] || colors.info) + ' text-lg shrink-0" data-icon="' + (icons[type] || icons.info) + '"></span><span class="text-sm text-brown-800 flex-1">' + message + '</span>';
    container.appendChild(toast);

    setTimeout(function() {
        toast.classList.add('removing');
        setTimeout(function() { toast.remove(); }, 250);
    }, 3000);
}

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
    showToast('Da them "' + name + '" vao gio hang');
}

function removeFromCart(id) {
    var item = cart.find(function(c) { return c.id === id; });
    cart = cart.filter(function(c) { return c.id !== id; });
    updateCartUI();
    if (item) showToast('Da xoa "' + item.name + '"', 'info');
}

function updateQty(id, delta) {
    var item = cart.find(function(c) { return c.id === id; });
    if (!item) return;
    item.qty += delta;
    if (item.qty <= 0) { removeFromCart(id); return; }
    updateCartUI();
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
    } else {
        panel.classList.remove('open');
        panel.classList.add('close');
        overlay.classList.add('hidden');
        document.body.style.overflow = '';
        cartOpen = false;
        setTimeout(function() { if (!cartOpen) panel.classList.add('hidden'); }, 300);
    }
}

function checkout() {
    if (cart.length === 0) return;
    var total = cart.reduce(function(s, c) { return s + c.price * c.qty; }, 0);
    showToast('Dat hang thanh cong! Tong: ' + formatPrice(total));
    cart = [];
    updateCartUI();
    setTimeout(function() { toggleCart(); }, 500);
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

function filterCategory(cat) {
    currentCategory = cat;
    var tabs = document.querySelectorAll('.cat-tab');
    for (var i = 0; i < tabs.length; i++) {
        if (tabs[i].getAttribute('data-cat') === cat) {
            tabs[i].classList.add('active');
            tabs[i].classList.remove('text-brown-400');
            tabs[i].classList.add('text-brown-900');
        } else {
            tabs[i].classList.remove('active');
            tabs[i].classList.remove('text-brown-900');
            tabs[i].classList.add('text-brown-400');
        }
    }
    applyFilters();
}

function applyFilters() {
    var grid = document.getElementById('product-grid');
    var empty = document.getElementById('empty-state');
    if (!grid) return;

    var cards = document.querySelectorAll('#product-grid .product-card');
    var visible = 0;

    for (var i = 0; i < cards.length; i++) {
        var card = cards[i];
        var name = (card.getAttribute('data-name') || '').toLowerCase();
        var desc = (card.getAttribute('data-desc') || '').toLowerCase();
        var cat = (card.getAttribute('data-cat') || '');
        var q = searchQuery.toLowerCase();

        var matchSearch = name.indexOf(q) !== -1 || desc.indexOf(q) !== -1;
        var matchCat = currentCategory === 'all' || cat === currentCategory;

        if (matchSearch && matchCat) {
            card.style.display = '';
            visible++;
        } else {
            card.style.display = 'none';
        }
    }

    if (visible === 0) {
        grid.classList.add('hidden');
        empty.classList.remove('hidden');
        empty.classList.add('flex');
    } else {
        grid.classList.remove('hidden');
        empty.classList.add('hidden');
        empty.classList.remove('flex');
    }
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

// =====================
// Init
// =====================
document.addEventListener('DOMContentLoaded', function() {
    updateCartUI();

    document.addEventListener('keydown', function(e) {
        if ((e.metaKey || e.ctrlKey) && e.key === 'k') {
            e.preventDefault();
            var si = document.getElementById('search-input');
            if (si) si.focus();
        }
        if (e.key === 'Escape' && cartOpen) toggleCart();
    });
});