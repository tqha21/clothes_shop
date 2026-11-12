# KỊCH BẢN QUAY VIDEO DEMO WEBSITE CLOTHES SHOP (OMNICHANNEL)

## Thông Tin Tổng Quan
- **Dự án:** Website Bán Quần Áo Thời Trang Clothes Shop (HTD Store)
- **Nhánh Git:** `feature/omnichannel`
- **Độ phân giải:** 1920x1080 Full HD (16:9), 30 FPS
- **Công cụ tự động:** Playwright Chromium + FFmpeg
- **Phụ đề:** Tiếng Việt có dấu, hiển thị trực quan dạng overlay và file SRT riêng.

---

## 9 Phân Cảnh Thao Tác Chi Tiết

### Phần 1: Giới thiệu website
- **Phụ đề:** "Giới thiệu hệ thống quản lý và bán hàng thời trang Clothes Shop."
- **Thao tác:** Truy cập trang chủ, di chuyển chuột qua menu điều hướng, cuộn trang xem banner khuyến mãi, bộ sưu tập và sản phẩm nổi bật.

### Phần 2: Chức năng khách hàng (Mua hàng trực tuyến)
- **Phụ đề:** "Khách hàng có thể tìm kiếm, lựa chọn và đặt mua sản phẩm trực tuyến."
- **Thao tác:** Mở danh sách sản phẩm (/products), tìm kiếm từ khóa "Áo", xem chi tiết sản phẩm, chọn size/màu, thêm vào giỏ hàng, cập nhật giỏ hàng và tiến hành đặt hàng trực tuyến.

### Phần 3: Quản trị sản phẩm (Admin)
- **Phụ đề:** "Quản trị viên quản lý danh mục, sản phẩm và các biến thể hàng hóa."
- **Thao tác:** Đăng nhập tài khoản admin, mở bảng điều khiển Dashboard, quản lý danh mục (/admin/categories), duyệt danh sách sản phẩm thời trang (/admin/products) kèm giá bán và tồn kho.

### Phần 4: Quản lý kho đa chi nhánh
- **Phụ đề:** "Hệ thống hỗ trợ theo dõi số lượng tồn kho và các hoạt động nhập xuất hàng."
- **Thao tác:** Truy cập /admin/inventory, xem bảng tổng hợp tồn kho 6 chi nhánh cửa hàng, xem lịch sử biến động kho (/admin/inventory/history).

### Phần 5: Quản lý đơn hàng
- **Phụ đề:** "Đơn hàng được quản lý tập trung với trạng thái xử lý rõ ràng."
- **Thao tác:** Mở danh sách đơn hàng (/admin/orders), xem chi tiết đơn hàng trực tuyến vừa tạo, kiểm tra thông tin khách và trạng thái đơn.

### Phần 6: Bán hàng tại quầy POS
- **Phụ đề:** "Nhân viên sử dụng hệ thống POS để thực hiện giao dịch bán hàng trực tiếp tại cửa hàng."
- **Thao tác:** Truy cập /pos, mở ca làm việc trực tiếp với két tiền 1.000.000đ, chọn sản phẩm, tra cứu thẻ thành viên VIP Gold giảm 10%, tính tiền thối tự động, hoàn tất đơn F9 và in hóa đơn K80.

### Phần 7: Quản lý nhân viên và ca làm việc
- **Phụ đề:** "Hệ thống hỗ trợ quản lý nhân viên, phân ca và theo dõi hoạt động bán hàng."
- **Thao tác:** Xem danh sách nhân viên (/admin/employees), lịch xếp ca theo tuần (/admin/schedules), chi tiết đối soát ca làm việc (/staff/shifts).

### Phần 8: Thống kê và báo cáo
- **Phụ đề:** "Dashboard cung cấp thông tin tổng quan về hoạt động kinh doanh."
- **Thao tác:** Truy cập /admin/reports/sales, xem biểu đồ doanh thu thuần Net Sales, phân bổ tỷ trọng Online vs POS, bảng xếp hạng năng suất nhân viên.

### Phần 9: Kết thúc
- **Phụ đề:** "Clothes Shop – Hệ thống hỗ trợ quản lý bán hàng và vận hành cửa hàng thời trang."
- **Thao tác:** Hiển thị trang Dashboard tổng quan.
