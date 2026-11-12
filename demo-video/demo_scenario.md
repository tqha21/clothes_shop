# KỊCH BẢN QUAY VIDEO DEMO CLOTHES SHOP (QUY TRÌNH MUA HÀNG VÀ QUẢN TRỊ)

## 1. Thông Tin Tổng Quan
- **Dự án:** Clothes Shop (Hệ thống quản lý và bán hàng thời trang)
- **Độ phân giải:** 1920×1080 Full HD (16:9), 30 FPS
- **Codec xuất:** H.264 (AVC) / CRF 19 / yuv420p
- **Quy trình:** Đúng tuần tự yêu cầu (Khách hàng xem sản phẩm → Chọn danh mục → Xem chi tiết & Thêm giỏ hàng → Đăng nhập khách hàng → Nhập thông tin giao hàng → Thanh toán COD → Đăng xuất → Đăng nhập Admin → Lần lượt tất cả chức năng sidebar Admin → Màn hình kết thúc).

---

## 2. Các Bước Thực Hiện Chi Tiết

### PHẦN 1: KHÁCH HÀNG MUA SẢN PHẨM
1. **Bước 1: Truy cập website và xem sản phẩm**
   - Mở trang chủ, di chuyển chuột qua navbar, cuộn xem banner và sản phẩm nổi bật.
   - *Phụ đề:* "Đầu tiên, khách hàng truy cập website và khám phá các sản phẩm thời trang."
2. **Bước 2: Chọn danh mục sản phẩm**
   - Vào danh mục sản phẩm (`/san-pham`), xem danh mục quần áo và danh sách sản phẩm.
   - *Phụ đề:* "Khách hàng lựa chọn danh mục để tìm kiếm sản phẩm phù hợp."
3. **Bước 3: Xem chi tiết sản phẩm**
   - Click xem chi tiết sản phẩm, chọn kích thước, màu sắc và số lượng 1.
   - *Phụ đề:* "Khách hàng xem thông tin chi tiết và lựa chọn kích thước, màu sắc của sản phẩm."
4. **Bước 4: Thêm sản phẩm vào giỏ hàng**
   - Bấm "Thêm vào giỏ hàng", mở giỏ hàng kiểm tra sản phẩm, số lượng và tổng tiền.
   - *Phụ đề:* "Sản phẩm được thêm vào giỏ hàng để chuẩn bị tiến hành đặt mua."
5. **Bước 5: Đăng nhập tài khoản khách hàng**
   - Đăng nhập tài khoản khách hàng sau khi đã thêm vào giỏ hàng, xác nhận giỏ hàng vẫn còn nguyên.
   - *Phụ đề:* "Khách hàng đăng nhập tài khoản để tiếp tục quá trình thanh toán."
6. **Bước 6: Nhập thông tin giao hàng**
   - Mở Checkout, nhập họ tên, số điện thoại, địa chỉ nhận hàng thử nghiệm.
   - *Phụ đề:* "Khách hàng cung cấp thông tin người nhận và địa chỉ giao hàng."
7. **Bước 7: Thanh toán và đặt hàng**
   - Chọn phương thức thanh toán khi nhận hàng (COD), xác nhận đặt hàng thành công và mở lịch sử đơn hàng.
   - *Phụ đề:* "Khách hàng xác nhận thông tin và hoàn tất đặt hàng thành công." / "Khách hàng có thể theo dõi đơn hàng trong lịch sử mua sắm."

### PHẦN 2: CHUYỂN SANG TÀI KHOẢN ADMIN
8. **Bước 8: Đăng xuất khách hàng**
   - Đăng xuất tài khoản khách hàng an toàn.
   - *Phụ đề:* "Tiếp theo, chúng ta chuyển sang hệ thống quản trị cửa hàng."
9. **Bước 9: Đăng nhập Admin**
   - Đăng nhập tài khoản quản trị Admin.
   - *Phụ đề:* "Quản trị viên đăng nhập để theo dõi và quản lý toàn bộ hoạt động kinh doanh."

### PHẦN 3: LẦN LƯỢT TOÀN BỘ CHỨC NĂNG SIDEBAR ADMIN
10. **Bước 10: Dashboard** (`/admin/dashboard`)
    - Xem thống kê doanh thu, đơn hàng, khách hàng và biểu đồ tăng trưởng.
    - *Phụ đề:* "Dashboard giúp quản trị viên theo dõi tổng quan hoạt động kinh doanh."
11. **Bước 11: Bán hàng POS (Quầy)** (`/pos`)
    - Mở ca quầy, quét chọn món, tra cứu VIP, tự động tính tiền thối và in bill K80.
    - *Phụ đề:* "Chức năng POS hỗ trợ nhân viên thực hiện giao dịch trực tiếp tại quầy."
12. **Bước 12: Báo cáo Doanh số** (`/admin/reports/sales`)
    - Phân tích Net Sales, tỷ trọng Online vs POS và xếp hạng nhân viên.
    - *Phụ đề:* "Hệ thống cung cấp báo cáo doanh thu và các chỉ số kinh doanh."
13. **Bước 13: Quản lý danh mục** (`/admin/categories`)
    - Xem cây danh mục sản phẩm thời trang.
    - *Phụ đề:* "Quản trị viên tổ chức và quản lý các danh mục sản phẩm."
14. **Bước 14: Quản lý sản phẩm** (`/admin/products`)
    - Danh sách sản phẩm, giá bán, biến thể màu sắc, kích thước và tồn kho.
    - *Phụ đề:* "Hệ thống hỗ trợ quản lý sản phẩm và các biến thể thời trang."
15. **Bước 15: Quản lý kho** (`/admin/inventory` & `/admin/inventory/history`)
    - Quản lý tồn kho 6 chi nhánh và nhật ký biến động kho.
    - *Phụ đề:* "Quản trị viên kiểm soát tồn kho và các hoạt động nhập xuất hàng hóa."
16. **Bước 16: Quản lý đơn hàng** (`/admin/orders`)
    - Mở chi tiết đơn hàng khách vừa đặt ở Phần 1 (Nguyễn Văn A, Chùa Bộc, COD).
    - *Phụ đề:* "Đơn hàng của khách hàng được tiếp nhận và xử lý tại trang quản trị."
17. **Bước 17: Quản lý nhân viên** (`/admin/employees`)
    - Danh sách nhân sự và phân quyền Admin/Manager/Staff.
    - *Phụ đề:* "Hệ thống hỗ trợ quản lý nhân viên và phân quyền hoạt động."
18. **Bước 18: Xếp ca làm việc & Quản lý ca** (`/admin/schedules`, `/admin/shifts`, `/staff/shifts`)
    - Lịch xếp ca tuần và đối soát bàn giao ca.
    - *Phụ đề:* "Quản trị viên theo dõi lịch làm việc và phân công ca cho nhân viên."
19. **Bước 19: Quản lý khách hàng** (`/admin/customers`)
    - Danh sách khách hàng và điểm tích lũy.
    - *Phụ đề:* "Quản trị viên theo dõi thông tin và hoạt động mua sắm của khách hàng."
20. **Bước 20: Voucher & Yêu thích** (`/admin/vouchers`, `/admin/wishlist`)
    - Quản lý mã giảm giá Voucher và danh sách sản phẩm được yêu thích.
    - *Phụ đề:* "Quản trị viên theo dõi mã khuyến mãi Voucher và các sản phẩm được quan tâm."

### KẾT THÚC VIDEO
- Hiển thị màn hình kết thúc với logo và slogan:
  `CLOTHES SHOP - Hệ thống quản lý và bán hàng thời trang` trong 3.5 giây.
