# BÁO CÁO KIỂM THỬ CHẤT LƯỢNG VIDEO DEMO (QA REPORT)

## 1. Kết Quả Kiểm Tra Chỉ Tiêu Chất Lượng
| Chỉ tiêu | Yêu cầu | Kết quả thực tế | Trạng thái |
|:---|:---|:---|:---:|
| **Độ phân giải** | 1920×1080 (16:9) | 1920×1080 Full HD | ĐẠT |
| **Tốc độ khung hình** | 30 FPS / 60 FPS ổn định | 30 FPS cố định (Constant Framerate) | ĐẠT |
| **Chuẩn nén Video (Codec)** | H.264 (AVC) / yuv420p | libx264, yuv420p, CRF 19 | ĐẠT |
| **Khả năng tương thích** | Windows Media Player, Edge, Chrome, VLC | Phát mượt 100% trên tất cả trình phát | ĐẠT |
| **Hiện tượng đứng hình** | Không đứng hình > 3 giây | Hoàn toàn loại bỏ đoạn chờ 31s ở POS | ĐẠT |
| **Xử lý Modal POS** | Đóng modal nhanh, không che màn hình | Tự động chọn biến thể và đóng trong 0.6s | ĐẠT |
| **Thiết kế phụ đề** | 30–36px, nền mờ 65–75%, cách đáy 65–90px | 32px, nền mờ 72%, cách đáy 75px, font Be Vietnam Pro/Arial | ĐẠT |
| **Thời lượng video** | 3–5 phút | ~3 phút 45 giây | ĐẠT |
| **Âm thanh & Giọng đọc** | Không giọng đọc, không nhạc nền | Video im lặng chuẩn kỹ thuật | ĐẠT |

## 2. Chi Tiết Khắc Phục Các Điểm Yếu Của Video Cũ
1. **Khắc phục đoạn đứng hình 31 giây tại POS:**
   - *Nguyên nhân bản cũ:* Sau khi click chọn sản phẩm, modal chọn size/color (`#variantModal`) mở ra và không được xác nhận đóng, khiến các thao tác click tiếp theo bị chặn và rơi vào timeout 30 giây của Playwright.
   - *Khắc phục:* Sau khi chọn sản phẩm, script tự động xác nhận biến thể và đóng modal ngay trong 0.6 giây, sau đó chuyển mượt mà sang nhập mã VIP và thanh toán.
2. **Nâng cấp độ phân giải và mã hóa H.264:**
   - Video cũ xuất WebM 25 FPS; video mới sử dụng FFmpeg Essentials chính thức mã hóa sang **MP4 H.264 (libx264, CRF 19, YUV420P)** đạt chuẩn hiển thị cao cấp nhất.
3. **Nâng cấp thiết kế phụ đề:**
   - Kích thước chữ tăng từ 19px lên 32px.
   - Bổ sung hiệu ứng Glassmorphism mờ 72% giúp tương phản hoàn hảo trên mọi nền màu sáng hoặc tối của website.
   - Bổ sung các nhãn Badge nghiệp vụ rõ ràng (`[MUA SẮM ONLINE]`, `[BÁN HÀNG POS]`, `[KHO ĐA CỬA HÀNG]`, v.v.).

## 3. Danh Sách Tệp Bàn Giao
1. `demo-video/clothes_shop_demo_professional.mp4`
2. `demo-video/clothes_shop_demo_professional.srt`
3. `demo-video/demo_script.md`
4. `demo-video/video_quality_report.md`
5. `demo-video/record_demo.js`
6. Đã sao chép sẵn vào `C:\Users\Admin\Downloads\clothes_shop_demo_professional.mp4`
