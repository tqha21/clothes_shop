# Clothes Shop (Cửa Hàng Bán Quần Áo)

Đây là một dự án website thương mại điện tử chuyên cung cấp các sản phẩm thời trang, quần áo. Dự án được xây dựng dựa trên framework mạnh mẽ **Laravel** (PHP), cung cấp giao diện người dùng thân thiện cũng như hệ thống quản trị (Admin Dashboard) toàn diện để quản lý sản phẩm, đơn hàng, khách hàng, nhân viên và các số liệu kinh doanh.

## 🚀 Công nghệ sử dụng
- **Backend:** PHP 8.x, Laravel Framework 9.x
- **Frontend:** HTML5, CSS3, JavaScript, Bootstrap / Tailwind (hoặc Blade Templates)
- **Database:** MySQL
- **Xác thực & Bảo mật:** Laravel Sanctum, Laravel Socialite

## ✨ Các tính năng nổi bật
- **Dành cho Người dùng (Customer):**
  - Xem danh sách sản phẩm, chi tiết sản phẩm.
  - Tìm kiếm và lọc sản phẩm theo danh mục.
  - Thêm sản phẩm vào giỏ hàng, quản lý giỏ hàng.
  - Thanh toán đơn hàng, xem lịch sử mua hàng.
  - Đăng ký, đăng nhập (hỗ trợ đăng nhập qua mạng xã hội).

- **Dành cho Quản trị viên (Admin/Staff):**
  - Quản lý danh mục, sản phẩm, biến thể (màu sắc, kích thước).
  - Quản lý đơn hàng, trạng thái thanh toán, và vận chuyển.
  - Quản lý tài khoản người dùng, nhân viên, khách hàng.
  - Thống kê doanh thu, báo cáo tồn kho.
  - Quản lý kho hàng, nhập/xuất kho.

## 🛠 Hướng dẫn cài đặt

Để chạy dự án này trên máy local của bạn, vui lòng làm theo các bước sau:

### Yêu cầu hệ thống
- PHP >= 8.0.2
- Composer
- Node.js & NPM
- MySQL

### Cài đặt
1. **Clone repository về máy:**
   ```bash
   git clone https://github.com/tqha21/clothes_shop.git
   cd shop_quan_ao
   ```

2. **Cài đặt các gói phụ thuộc PHP (Composer):**
   ```bash
   composer install
   ```

3. **Cài đặt các gói phụ thuộc Frontend (NPM):**
   ```bash
   npm install
   npm run dev
   ```

4. **Tạo file cấu hình môi trường:**
   ```bash
   cp .env.example .env
   ```
   *Mở file `.env` và cấu hình thông tin kết nối Database của bạn.*

5. **Tạo key ứng dụng:**
   ```bash
   php artisan key:generate
   ```

6. **Chạy Migration & Seeder (Tạo bảng và dữ liệu mẫu):**
   ```bash
   php artisan migrate --seed
   ```

7. **Chạy server local:**
   ```bash
   php artisan serve
   ```
   *Truy cập vào ứng dụng qua địa chỉ: `http://localhost:8000`*

## 📜 Giấy phép (License)
Dự án được phân phối dưới dạng mã nguồn mở theo giấy phép [MIT license](https://opensource.org/licenses/MIT).
