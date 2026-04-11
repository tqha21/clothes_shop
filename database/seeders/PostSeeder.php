<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $posts = [
            [
                'title' => 'BỘ SƯU TẬP POLO SHIRT BẠN SẼ THOẢI MÁI BIỂN TẦU MỌI PHONG CÁCH',
                'slug' => 'bo-suu-tap-polo-shirt-ban-se-thoai-mai-bien-tau-moi-phong-cach',
                'excerpt' => 'Khám phá bộ sưu tập polo shirt đa dạng với nhiều màu sắc và kiểu dáng, phù hợp với mọi phong cách từ lịch sự đến năng động.',
                'content' => '<h2>Giới thiệu về Polo Shirt</h2><p>Polo shirt là một trong những item không thể thiếu trong tủ đồ của mọi quý ông hiện đại. Với thiết kế thanh lịch nhưng không kém phần năng động, polo shirt có thể được mix & match với nhiều trang phục khác nhau.</p><h3>Đặc điểm nổi bật</h3><ul><li>Chất liệu cotton cao cấp, thấm hút mồ hôi tốt</li><li>Form dáng chuẩn, tôn dáng người mặc</li><li>Đa dạng màu sắc: trắng, đen, xanh navy, xám...</li><li>Dễ dàng phối đồ với quần jean, kaki hoặc short</li></ul><h3>Cách phối đồ với Polo Shirt</h3><p>Bạn có thể kết hợp polo shirt với quần jean để tạo nên phong cách casual năng động, hoặc mix cùng quần kaki để có vẻ ngoài lịch sự hơn khi đi làm hoặc gặp gỡ đối tác.</p>',
                'image' => 'https://images.unsplash.com/photo-1586790170083-2f9ceadc732d?w=800&h=600&fit=crop',
                'is_published' => true,
                'published_at' => now()->subDays(10),
            ],
            [
                'title' => 'COFFEE POLO - TỰ ĐỘ LƯỢNG ĐẾN TRANG PHỤC',
                'slug' => 'coffee-polo-tu-do-luong-den-trang-phuc',
                'excerpt' => 'Xu hướng thời trang mới với tone màu coffee ấm áp, mang đến sự thanh lịch và sang trọng cho người mặc.',
                'content' => '<h2>Xu hướng màu Coffee trong thời trang</h2><p>Màu coffee (nâu cafe) đang trở thành xu hướng hot trong làng thời trang. Tone màu ấm áp này không chỉ dễ phối đồ mà còn tôn lên vẻ sang trọng, lịch lãm cho người mặc.</p><h3>Tại sao nên chọn Polo màu Coffee?</h3><ul><li>Màu sắc trung tính, dễ phối với nhiều trang phục</li><li>Tạo cảm giác ấm áp, gần gũi</li><li>Phù hợp với mọi độ tuổi và hoàn cảnh</li><li>Không bị lỗi mốt theo thời gian</li></ul><h3>Gợi ý phối đồ</h3><p>Áo polo màu coffee kết hợp cùng quần jean xanh đậm hoặc quần kaki be sẽ tạo nên set đồ hoàn hảo cho những buổi hẹn hò cuối tuần hay đi làm văn phòng.</p>',
                'image' => 'https://images.unsplash.com/photo-1618354691373-d851c5c3a990?w=800&h=600&fit=crop',
                'is_published' => true,
                'published_at' => now()->subDays(8),
            ],
            [
                'title' => 'NAVY SHIRT - OUTFIT OF TODAY',
                'slug' => 'navy-shirt-outfit-of-today',
                'excerpt' => 'Sơ mi xanh navy - lựa chọn hoàn hảo cho outfit hàng ngày, kết hợp giữa sự lịch sự và thoải mái.',
                'content' => '<h2>Navy Shirt - Món đồ must-have</h2><p>Sơ mi xanh navy là một trong những item cơ bản nhưng vô cùng quan trọng trong tủ đồ của mọi người. Với màu sắc trung tính và dễ phối, navy shirt luôn là lựa chọn an toàn cho mọi dịp.</p><h3>Ưu điểm của Navy Shirt</h3><ul><li>Màu xanh navy tạo cảm giác chuyên nghiệp</li><li>Dễ dàng kết hợp với nhiều loại quần</li><li>Phù hợp cả đi làm và đi chơi</li><li>Không bị phai màu sau nhiều lần giặt</li></ul><h3>Cách mix đồ với Navy Shirt</h3><p>Bạn có thể mặc navy shirt với quần tây đen cho look công sở, hoặc kết hợp với quần jean và giày sneaker cho phong cách casual năng động.</p>',
                'image' => 'https://images.unsplash.com/photo-1620799140408-edc6dcb6d633?w=800&h=600&fit=crop',
                'is_published' => true,
                'published_at' => now()->subDays(5),
            ],
            [
                'title' => 'CHỌN SƠ MI ĐẸP HỢP PHONG THỦY',
                'slug' => 'chon-so-mi-dep-hop-phong-thuy',
                'excerpt' => 'Bí quyết chọn sơ mi theo phong thủy để mang lại may mắn và thành công trong công việc.',
                'content' => '<h2>Phong thủy trong việc chọn sơ mi</h2><p>Theo phong thủy, màu sắc trang phục có ảnh hưởng lớn đến vận khí và sự nghiệp. Việc chọn đúng màu sắc sơ mi phù hợp với mệnh sẽ giúp bạn thu hút năng lượng tích cực.</p><h3>Màu sơ mi theo mệnh</h3><ul><li><strong>Mệnh Kim:</strong> Nên chọn màu trắng, vàng, nâu</li><li><strong>Mệnh Mộc:</strong> Xanh lá, xanh lơ, đen</li><li><strong>Mệnh Thủy:</strong> Đen, xanh navy, xám</li><li><strong>Mệnh Hỏa:</strong> Đỏ, cam, hồng</li><li><strong>Mệnh Thổ:</strong> Vàng, nâu, be</li></ul><h3>Lưu ý khi chọn sơ mi</h3><p>Ngoài màu sắc, bạn cũng nên chú ý đến chất liệu và form dáng để đảm bảo sự thoải mái và phù hợp với vóc dáng.</p>',
                'image' => 'https://images.unsplash.com/photo-1602810318383-e386cc2a3ccf?w=800&h=600&fit=crop',
                'is_published' => true,
                'published_at' => now()->subDays(3),
            ],
            [
                'title' => 'ÁO THỂ THAO THƯƠNG HIỆU ĐƠN HÈ',
                'slug' => 'ao-the-thao-thuong-hieu-don-he',
                'excerpt' => 'Tổng hợp các thương hiệu áo thể thao chất lượng cho mùa hè năng động và thoải mái.',
                'content' => '<h2>Áo thể thao mùa hè</h2><p>Mùa hè là thời điểm lý tưởng để diện những bộ đồ thể thao năng động. Áo thể thao không chỉ giúp bạn thoải mái khi vận động mà còn tạo nên phong cách thời trang riêng.</p><h3>Tiêu chí chọn áo thể thao mùa hè</h3><ul><li>Chất liệu thoáng mát, thấm hút mồ hôi</li><li>Form dáng ôm vừa, không quá rộng</li><li>Màu sắc tươi sáng, năng động</li><li>Có công nghệ chống UV nếu tập ngoài trời</li></ul><h3>Top thương hiệu áo thể thao</h3><p>Một số thương hiệu nổi tiếng về áo thể thao như Nike, Adidas, Puma, Under Armour... đều có những dòng sản phẩm chất lượng cao với công nghệ hiện đại.</p><h3>Cách bảo quản</h3><p>Để áo thể thao bền đẹp, bạn nên giặt ở nhiệt độ thấp, không sử dụng nước xả vải và phơi nơi thoáng mát, tránh ánh nắng trực tiếp.</p>',
                'image' => 'https://images.unsplash.com/photo-1556821840-3a63f95609a7?w=800&h=600&fit=crop',
                'is_published' => true,
                'published_at' => now()->subDays(1),
            ],
        ];

        foreach ($posts as $post) {
            \App\Models\Post::create($post);
        }
    }
}
