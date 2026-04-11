@extends('layouts.storefront')

@section('title', 'Ưu Đãi & Khuyến Mãi')

@section('content')
<!-- Page Header -->
<div class="bg-gradient-to-r from-purple-600 via-pink-600 to-red-600 text-white rounded-2xl p-12 mb-12 text-center shadow-2xl">
    <h1 class="text-5xl font-bold mb-4">🎁 Ưu Đãi & Khuyến Mãi</h1>
    <p class="text-xl text-purple-100">Nhận ngay mã giảm giá hấp dẫn cho đơn hàng của bạn!</p>
</div>

<!-- Active Coupons Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
    <!-- Coupon Card 1 -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden border-2 border-purple-200 hover:border-purple-500 transition-all hover:shadow-2xl">
        <div class="bg-gradient-to-r from-purple-500 to-pink-500 p-6 text-white">
            <div class="flex items-center justify-between mb-3">
                <span class="text-sm font-semibold bg-white/20 px-3 py-1 rounded-full">Mới nhất</span>
                <span class="text-3xl">🔥</span>
            </div>
            <h3 class="text-2xl font-bold mb-2">Giảm 50%</h3>
            <p class="text-purple-100">Cho đơn hàng đầu tiên</p>
        </div>
        <div class="p-6">
            <div class="bg-gray-100 rounded-lg p-4 mb-4 border-2 border-dashed border-gray-300">
                <div class="flex items-center justify-between">
                    <code class="text-xl font-bold text-purple-600">WELCOME50</code>
                    <button onclick="copyCode('WELCOME50')" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition-colors text-sm font-semibold">
                        Sao chép
                    </button>
                </div>
            </div>
            <ul class="text-sm text-gray-600 space-y-2">
                <li>✓ Áp dụng cho khách hàng mới</li>
                <li>✓ Đơn hàng tối thiểu: 500.000đ</li>
                <li>✓ HSD: 31/03/2026</li>
            </ul>
        </div>
    </div>

    <!-- Coupon Card 2 -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden border-2 border-yellow-200 hover:border-yellow-500 transition-all hover:shadow-2xl">
        <div class="bg-gradient-to-r from-yellow-500 to-orange-500 p-6 text-white">
            <div class="flex items-center justify-between mb-3">
                <span class="text-sm font-semibold bg-white/20 px-3 py-1 rounded-full">Hot</span>
                <span class="text-3xl">⚡</span>
            </div>
            <h3 class="text-2xl font-bold mb-2">Freeship</h3>
            <p class="text-yellow-100">Miễn phí vận chuyển</p>
        </div>
        <div class="p-6">
            <div class="bg-gray-100 rounded-lg p-4 mb-4 border-2 border-dashed border-gray-300">
                <div class="flex items-center justify-between">
                    <code class="text-xl font-bold text-orange-600">FREESHIP2026</code>
                    <button onclick="copyCode('FREESHIP2026')" class="bg-orange-600 text-white px-4 py-2 rounded-lg hover:bg-orange-700 transition-colors text-sm font-semibold">
                        Sao chép
                    </button>
                </div>
            </div>
            <ul class="text-sm text-gray-600 space-y-2">
                <li>✓ Miễn phí ship toàn quốc</li>
                <li>✓ Đơn hàng tối thiểu: 300.000đ</li>
                <li>✓ HSD: 28/02/2026</li>
            </ul>
        </div>
    </div>

    <!-- Coupon Card 3 -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden border-2 border-green-200 hover:border-green-500 transition-all hover:shadow-2xl">
        <div class="bg-gradient-to-r from-green-500 to-teal-500 p-6 text-white">
            <div class="flex items-center justify-between mb-3">
                <span class="text-sm font-semibold bg-white/20 px-3 py-1 rounded-full">VIP</span>
                <span class="text-3xl">💎</span>
            </div>
            <h3 class="text-2xl font-bold mb-2">Giảm 200K</h3>
            <p class="text-green-100">Cho thành viên thân thiết</p>
        </div>
        <div class="p-6">
            <div class="bg-gray-100 rounded-lg p-4 mb-4 border-2 border-dashed border-gray-300">
                <div class="flex items-center justify-between">
                    <code class="text-xl font-bold text-green-600">VIP200K</code>
                    <button onclick="copyCode('VIP200K')" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors text-sm font-semibold">
                        Sao chép
                    </button>
                </div>
            </div>
            <ul class="text-sm text-gray-600 space-y-2">
                <li>✓ Dành cho khách VIP</li>
                <li>✓ Đơn hàng tối thiểu: 1.000.000đ</li>
                <li>✓ HSD: 30/04/2026</li>
            </ul>
        </div>
    </div>

    <!-- Coupon Card 4 -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden border-2 border-blue-200 hover:border-blue-500 transition-all hover:shadow-2xl">
        <div class="bg-gradient-to-r from-blue-500 to-indigo-500 p-6 text-white">
            <div class="flex items-center justify-between mb-3">
                <span class="text-sm font-semibold bg-white/20 px-3 py-1 rounded-full">Combo</span>
                <span class="text-3xl">🎉</span>
            </div>
            <h3 class="text-2xl font-bold mb-2">Giảm 30%</h3>
            <p class="text-blue-100">Mua 2 tặng 1</p>
        </div>
        <div class="p-6">
            <div class="bg-gray-100 rounded-lg p-4 mb-4 border-2 border-dashed border-gray-300">
                <div class="flex items-center justify-between">
                    <code class="text-xl font-bold text-blue-600">COMBO30</code>
                    <button onclick="copyCode('COMBO30')" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors text-sm font-semibold">
                        Sao chép
                    </button>
                </div>
            </div>
            <ul class="text-sm text-gray-600 space-y-2">
                <li>✓ Mua 2 sản phẩm bất kỳ</li>
                <li>✓ Tặng 1 sản phẩm giá trị thấp nhất</li>
                <li>✓ HSD: 15/03/2026</li>
            </ul>
        </div>
    </div>

    <!-- Coupon Card 5 -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden border-2 border-red-200 hover:border-red-500 transition-all hover:shadow-2xl">
        <div class="bg-gradient-to-r from-red-500 to-pink-500 p-6 text-white">
            <div class="flex items-center justify-between mb-3">
                <span class="text-sm font-semibold bg-white/20 px-3 py-1 rounded-full">Flash Sale</span>
                <span class="text-3xl">⏰</span>
            </div>
            <h3 class="text-2xl font-bold mb-2">Giảm 100K</h3>
            <p class="text-red-100">Khung giờ vàng</p>
        </div>
        <div class="p-6">
            <div class="bg-gray-100 rounded-lg p-4 mb-4 border-2 border-dashed border-gray-300">
                <div class="flex items-center justify-between">
                    <code class="text-xl font-bold text-red-600">FLASH100</code>
                    <button onclick="copyCode('FLASH100')" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors text-sm font-semibold">
                        Sao chép
                    </button>
                </div>
            </div>
            <ul class="text-sm text-gray-600 space-y-2">
                <li>✓ Áp dụng 9h-12h & 18h-21h</li>
                <li>✓ Đơn hàng tối thiểu: 700.000đ</li>
                <li>✓ HSD: 10/02/2026</li>
            </ul>
        </div>
    </div>

    <!-- Coupon Card 6 -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden border-2 border-indigo-200 hover:border-indigo-500 transition-all hover:shadow-2xl">
        <div class="bg-gradient-to-r from-indigo-500 to-purple-500 p-6 text-white">
            <div class="flex items-center justify-between mb-3">
                <span class="text-sm font-semibold bg-white/20 px-3 py-1 rounded-full">Sinh nhật</span>
                <span class="text-3xl">🎂</span>
            </div>
            <h3 class="text-2xl font-bold mb-2">Giảm 25%</h3>
            <p class="text-indigo-100">Tháng sinh nhật</p>
        </div>
        <div class="p-6">
            <div class="bg-gray-100 rounded-lg p-4 mb-4 border-2 border-dashed border-gray-300">
                <div class="flex items-center justify-between">
                    <code class="text-xl font-bold text-indigo-600">BDAY25</code>
                    <button onclick="copyCode('BDAY25')" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors text-sm font-semibold">
                        Sao chép
                    </button>
                </div>
            </div>
            <ul class="text-sm text-gray-600 space-y-2">
                <li>✓ Dành cho khách có sinh nhật</li>
                <li>✓ Đơn hàng tối thiểu: 400.000đ</li>
                <li>✓ Sử dụng trong tháng sinh nhật</li>
            </ul>
        </div>
    </div>
</div>

<!-- How to Use Section -->
<div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-2xl p-8 mb-12">
    <h2 class="text-3xl font-bold text-gray-900 mb-6 text-center">📋 Cách Sử Dụng Mã Giảm Giá</h2>
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="text-center">
            <div class="bg-purple-600 text-white w-16 h-16 rounded-full flex items-center justify-center text-2xl font-bold mx-auto mb-4 shadow-lg">1</div>
            <h3 class="font-bold mb-2">Chọn sản phẩm</h3>
            <p class="text-gray-600 text-sm">Thêm sản phẩm yêu thích vào giỏ hàng</p>
        </div>
        <div class="text-center">
            <div class="bg-pink-600 text-white w-16 h-16 rounded-full flex items-center justify-center text-2xl font-bold mx-auto mb-4 shadow-lg">2</div>
            <h3 class="font-bold mb-2">Sao chép mã</h3>
            <p class="text-gray-600 text-sm">Click "Sao chép" để lấy mã giảm giá</p>
        </div>
        <div class="text-center">
            <div class="bg-orange-600 text-white w-16 h-16 rounded-full flex items-center justify-center text-2xl font-bold mx-auto mb-4 shadow-lg">3</div>
            <h3 class="font-bold mb-2">Thanh toán</h3>
            <p class="text-gray-600 text-sm">Tiến hành thanh toán đơn hàng</p>
        </div>
        <div class="text-center">
            <div class="bg-green-600 text-white w-16 h-16 rounded-full flex items-center justify-center text-2xl font-bold mx-auto mb-4 shadow-lg">4</div>
            <h3 class="font-bold mb-2">Nhập mã</h3>
            <p class="text-gray-600 text-sm">Dán mã vào ô "Mã giảm giá" và áp dụng</p>
        </div>
    </div>
</div>

<!-- CTA Section -->
<div class="bg-gradient-to-r from-purple-600 to-pink-600 rounded-2xl p-12 text-white text-center shadow-2xl">
    <h2 class="text-4xl font-bold mb-4">Bắt Đầu Mua Sắm Ngay! 🛍️</h2>
    <p class="text-xl text-purple-100 mb-8">Khám phá hàng ngàn sản phẩm thời trang chất lượng cao</p>
    <a href="{{ route('products.index') }}" class="inline-block bg-white text-purple-700 px-12 py-5 rounded-full font-bold text-xl hover:bg-yellow-400 hover:text-purple-900 transition-all transform hover:scale-105 shadow-2xl">
        XEM SẢN PHẨM
    </a>
</div>

<script>
function copyCode(code) {
    navigator.clipboard.writeText(code).then(function() {
        // Show success message
        const btn = event.target;
        const originalText = btn.textContent;
        btn.textContent = '✓ Đã sao chép!';
        btn.classList.add('bg-green-600');
        btn.classList.remove('bg-purple-600', 'bg-orange-600', 'bg-green-600', 'bg-blue-600', 'bg-red-600', 'bg-indigo-600');
        
        setTimeout(function() {
            btn.textContent = originalText;
            btn.classList.remove('bg-green-600');
            // Restore original color based on parent
            const gradientDiv = btn.closest('.bg-white').querySelector('[class*="from-"]');
            if (gradientDiv.classList.contains('from-purple-500')) btn.classList.add('bg-purple-600');
            else if (gradientDiv.classList.contains('from-yellow-500')) btn.classList.add('bg-orange-600');
            else if (gradientDiv.classList.contains('from-green-500')) btn.classList.add('bg-green-600');
            else if (gradientDiv.classList.contains('from-blue-500')) btn.classList.add('bg-blue-600');
            else if (gradientDiv.classList.contains('from-red-500')) btn.classList.add('bg-red-600');
            else if (gradientDiv.classList.contains('from-indigo-500')) btn.classList.add('bg-indigo-600');
        }, 2000);
    });
}
</script>
@endsection
