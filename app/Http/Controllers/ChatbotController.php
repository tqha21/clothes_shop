<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChatbotController extends Controller
{
    /**
     * Xử lý tin nhắn chat từ khách hàng
     */
    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
            'history' => 'nullable|array',
        ]);

        $userMessage = trim($request->input('message'));
        $history = $request->input('history', []);

        // 1. Phân tích ngữ cảnh & tìm sản phẩm phù hợp trong DB
        $storeContext = $this->buildStoreContext($userMessage);

        // 2. Xác định cấu hình AI
        $provider = config('services.ai.provider', 'deepseek');
        $openaiKey = config('services.ai.openai_api_key');
        $deepseekKey = config('services.ai.deepseek_api_key');

        $activeKey = ($provider === 'openai') ? $openaiKey : $deepseekKey;
        if (empty($activeKey) && !empty($openaiKey)) {
            $provider = 'openai';
            $activeKey = $openaiKey;
        } elseif (empty($activeKey) && !empty($deepseekKey)) {
            $provider = 'deepseek';
            $activeKey = $deepseekKey;
        }

        // 3. Nếu có API key, gọi AI (DeepSeek / OpenAI)
        if (!empty($activeKey)) {
            try {
                $reply = $this->callAiApi($provider, $activeKey, $userMessage, $history, $storeContext);
                if (!empty($reply)) {
                    return response()->json([
                        'success' => true,
                        'reply' => $reply,
                        'products' => $this->formatProductsForClient($storeContext['products']),
                        'provider' => $provider,
                    ]);
                }
            } catch (\Throwable $e) {
                Log::warning('AI API Error: ' . $e->getMessage());
                // Fallback to local intelligent assistant below
            }
        }

        // 4. Trợ lý thông minh cục bộ tích hợp dữ liệu DB sâu sát
        $localResult = $this->generateLocalResponse($userMessage);

        return response()->json([
            'success' => true,
            'reply' => $localResult['reply'],
            'products' => $this->formatProductsForClient($localResult['products'] ?? collect()),
            'provider' => 'local_assistant',
        ]);
    }

    /**
     * Gọi API OpenAI hoặc DeepSeek (Chuẩn OpenAI chat completions)
     */
    protected function callAiApi(string $provider, string $apiKey, string $userMessage, array $history, array $storeContext): ?string
    {
        $endpoint = ($provider === 'openai') 
            ? 'https://api.openai.com/v1/chat/completions'
            : 'https://api.deepseek.com/chat/completions';

        $model = ($provider === 'openai')
            ? config('services.ai.openai_model', 'gpt-4o-mini')
            : config('services.ai.deepseek_model', 'deepseek-chat');

        $systemPrompt = "Bạn là Trợ lý AI tư vấn bán hàng chuyên nghiệp, duyên dáng và tận tâm của 'HTD Store' - cửa hàng thời trang nam, nữ và trẻ em cao cấp.\n"
            . "Nhiệm vụ của bạn:\n"
            . "1. Tư vấn nhiệt tình, xưng hô 'em' và 'anh/chị', sử dụng ngôn từ lịch sự, gần gũi, thêm emoji phù hợp ✨.\n"
            . "2. Tư vấn chọn size quần áo chuẩn xác dựa theo chiều cao và cân nặng:\n"
            . "   - Dưới 53kg (1m50 - 1m60): Size S\n"
            . "   - 54 - 62kg (1m60 - 1m68): Size M\n"
            . "   - 63 - 70kg (1m68 - 1m74): Size L\n"
            . "   - 71 - 78kg (1m72 - 1m78): Size XL\n"
            . "   - Trên 79kg: Size XXL\n"
            . "3. Giới thiệu sản phẩm phù hợp từ danh sách sản phẩm hiện có của shop. Khi nhắc đến sản phẩm, HÃY GẮN LINK MARKDOWN định dạng: [Tên sản phẩm](/san-pham/slug) kèm giá tiền để khách bấm vào xem ngay.\n"
            . "4. Khi khách hỏi về mùa đông: gợi ý áo phao, áo khoác dạ, áo gió, áo giữ nhiệt. Khi hỏi mùa hè: gợi ý áo thun, áo polo, chống nắng. Khi hỏi công sở: gợi ý sơ mi, vest, quần âu.\n"
            . "5. Chính sách mua hàng: Miễn phí vận chuyển toàn quốc cho đơn hàng từ 500.000đ; Đổi size/đổi mẫu miễn phí 7 ngày; Cho phép kiểm tra hàng trước khi thanh toán (Ship COD).\n"
            . "6. Trả lời súc tích, ngắt dòng rõ ràng, không viết đoạn quá dài dòng.\n\n"
            . "--- DỮ LIỆU SẢN PHẨM & CỬA HÀNG HIỆN TẠI ---\n"
            . $storeContext['text'];

        $messages = [
            ['role' => 'system', 'content' => $systemPrompt],
        ];

        $recentHistory = array_slice($history, -6);
        foreach ($recentHistory as $msg) {
            if (isset($msg['role']) && isset($msg['content'])) {
                $role = in_array($msg['role'], ['user', 'assistant']) ? $msg['role'] : 'user';
                $messages[] = [
                    'role' => $role,
                    'content' => (string) $msg['content']
                ];
            }
        }

        $messages[] = [
            'role' => 'user',
            'content' => $userMessage
        ];

        $response = Http::withToken($apiKey)
            ->timeout(25)
            ->post($endpoint, [
                'model' => $model,
                'messages' => $messages,
                'temperature' => 0.7,
                'max_tokens' => 800,
            ]);

        if ($response->successful()) {
            $data = $response->json();
            return $data['choices'][0]['message']['content'] ?? null;
        }

        Log::error('AI API Error Response: ' . $response->body());
        return null;
    }

    /**
     * Thu thập dữ liệu sản phẩm trong DB để đưa vào prompt theo từ khóa ngữ nghĩa
     */
    protected function buildStoreContext(string $query): array
    {
        $categories = Category::pluck('name')->toArray();
        $msgLower = mb_strtolower($query, 'UTF-8');

        // Tìm kiếm theo ngữ nghĩa
        $matchedProducts = $this->queryProductsByIntent($msgLower);

        if ($matchedProducts->isEmpty()) {
            $matchedProducts = Product::latest()->take(6)->get();
        }

        $lines = [];
        $lines[] = "Danh mục sản phẩm: " . implode(', ', $categories);
        $lines[] = "Một số sản phẩm phù hợp:";

        foreach ($matchedProducts as $p) {
            $priceText = number_format($p->sale_price ?? $p->price, 0, ',', '.') . 'đ';
            $origPrice = ($p->sale_price && $p->sale_price < $p->price) ? ' (Gốc: ' . number_format($p->price, 0, ',', '.') . 'đ)' : '';
            $sizes = is_array($p->sizes) ? implode(', ', $p->sizes) : ($p->sizes ?? 'S, M, L, XL');
            $lines[] = "- {$p->name} (Slug: {$p->slug}) | Giá: {$priceText}{$origPrice} | Size: {$sizes}";
        }

        return [
            'text' => implode("\n", $lines),
            'products' => $matchedProducts
        ];
    }

    /**
     * Query sản phẩm thông minh theo ngữ nghĩa (Intent-based Product Search)
     */
    protected function queryProductsByIntent(string $msgLower)
    {
        // 0. Nếu tin nhắn hướng đến một sản phẩm cụ thể
        $target = $this->findTargetProduct($msgLower);
        if ($target) {
            return collect([$target]);
        }

        // 1. Áo mùa đông / Đồ mùa đông / Giữ ấm / Lạnh
        if ($this->hasAnyKeyword($msgLower, ['mùa đông', 'mua dong', 'áo đông', 'ao dong', 'mùa lạnh', 'mua lanh', 'rét', 'ret', 'lạnh', 'giữ nhiệt', 'giu nhiet', 'phao', 'áo phao', 'áo khoác', 'ao khoac', 'lông cừu', 'long cuu', 'măng tô', 'mang to', 'ấm', 'áo ấm', 'dạ'])) {
            return Product::where(function($q) {
                $q->where('name', 'like', '%phao%')
                  ->orWhere('name', 'like', '%giữ nhiệt%')
                  ->orWhere('name', 'like', '%dạ%')
                  ->orWhere('name', 'like', '%lông cừu%')
                  ->orWhere('name', 'like', '%măng tô%')
                  ->orWhere('name', 'like', '%khoác%')
                  ->orWhere('name', 'like', '%gió%');
            })->take(5)->get();
        }

        // 2. Áo mùa hè / Thoáng mát / Nắng nóng
        if ($this->hasAnyKeyword($msgLower, ['mùa hè', 'mua he', 'nắng', 'nóng', 'mát', 'thoáng mát', 'ngắn tay', 'cộc tay', 'chống nắng'])) {
            return Product::where(function($q) {
                $q->where('name', 'like', '%thun%')
                  ->orWhere('name', 'like', '%polo%')
                  ->orWhere('name', 'like', '%chống nắng%')
                  ->orWhere('name', 'like', '%ngắn tay%');
            })->take(5)->get();
        }

        // 3. Áo sơ mi / Đi làm / Công sở / Lịch sự
        if ($this->hasAnyKeyword($msgLower, ['công sở', 'cong so', 'đi làm', 'di lam', 'sơ mi', 'so mi', 'vest', 'blazer', 'kaki', 'khaki', 'quần âu', 'quan au', 'lịch sự'])) {
            return Product::where(function($q) {
                $q->where('name', 'like', '%sơ mi%')
                  ->orWhere('name', 'like', '%vest%')
                  ->orWhere('name', 'like', '%blazer%')
                  ->orWhere('name', 'like', '%khaki%')
                  ->orWhere('name', 'like', '%kaki%');
            })->take(5)->get();
        }

        // 4. Áo thun / Phông / T-shirt
        if ($this->hasAnyKeyword($msgLower, ['áo thun', 'ao thun', 't-shirt', 'phông', 'tshirt'])) {
            return Product::where('name', 'like', '%thun%')->take(5)->get();
        }

        // 5. Áo polo
        if ($this->hasAnyKeyword($msgLower, ['polo', 'áo polo', 'ao polo'])) {
            return Product::where('name', 'like', '%polo%')->take(5)->get();
        }

        // 6. Quần jean / Bò
        if ($this->hasAnyKeyword($msgLower, ['jean', 'bò', 'quần bò', 'denim'])) {
            return Product::where('name', 'like', '%jean%')->take(5)->get();
        }

        // 7. Váy đầm / Đi tiệc / Dạo phố
        if ($this->hasAnyKeyword($msgLower, ['váy', 'đầm', 'dam', 'tiệc', 'chân váy', 'dạo phố', 'hen ho'])) {
            return Product::where(function($q) {
                $q->where('name', 'like', '%đầm%')
                  ->orWhere('name', 'like', '%váy%')
                  ->orWhere('name', 'like', '%tiệc%');
            })->take(5)->get();
        }

        // 8. Đồ trẻ em / Cho bé
        if ($this->hasAnyKeyword($msgLower, ['trẻ em', 'tre em', 'bé trai', 'be trai', 'bé gái', 'be gai', 'cho bé', 'cho con', 'kid'])) {
            return Product::where(function($q) {
                $q->where('name', 'like', '%trẻ em%')
                  ->orWhere('name', 'like', '%bé%')
                  ->orWhere('name', 'like', '%kid%');
            })->take(5)->get();
        }

        // 9. Giảm giá / Khuyến mãi / Sale
        if ($this->hasAnyKeyword($msgLower, ['sale', 'giảm giá', 'giam gia', 'khuyến mãi', 'khuyen mai', 'ưu đãi', 'voucher'])) {
            return Product::whereNotNull('sale_price')->whereColumn('sale_price', '<', 'price')->take(5)->get();
        }

        // 10. Tìm kiếm theo từ khóa tổng quát
        $keywords = explode(' ', $msgLower);
        return Product::where(function($q) use ($keywords) {
            foreach ($keywords as $kw) {
                if (mb_strlen($kw) >= 3) {
                    $q->orWhere('name', 'like', "%{$kw}%");
                }
            }
        })->take(5)->get();
    }

    /**
     * Trợ lý thông minh cục bộ khi chưa cấu hình API key
     */
    protected function generateLocalResponse(string $message): array
    {
        $msgLower = mb_strtolower($message, 'UTF-8');

        $targetProduct = $this->findTargetProduct($message);
        $hw = $this->extractHeightAndWeight($message);

        // A. Tư vấn size cho sản phẩm cụ thể (Đã có chiều cao hoặc cân nặng)
        if ($targetProduct && ($hw['height'] || $hw['weight'])) {
            $sizeInfo = $this->calculateRecommendedSize($hw['height'], $hw['weight']);
            $heightDesc = $hw['height'] ? "chiều cao **{$hw['height']}cm**" : "";
            $weightDesc = $hw['weight'] ? "cân nặng **{$hw['weight']}kg**" : "";
            $bodyMeasure = trim($heightDesc . ($heightDesc && $weightDesc ? " và " : "") . $weightDesc);
            $priceText = number_format($targetProduct->sale_price ?? $targetProduct->price, 0, ',', '.') . 'đ';

            $reply = "Dạ với mẫu **[{$targetProduct->name}](/san-pham/{$targetProduct->slug})** (Giá ưu đãi: **{$priceText}**):\n\n"
                . "Dựa vào {$bodyMeasure} và đối chiếu **Bảng size chuẩn của HTD Store**:\n\n"
                . "🎯 **Size vừa vặn và đẹp nhất cho anh/chị là: SIZE {$sizeInfo['size']}** ✨\n\n"
                . "👕 **Tư vấn chọn phom dáng:**\n"
                . "• **Mặc vừa người, chuẩn phom tôn dáng:** Chọn ngay **Size {$sizeInfo['size']}**.\n"
                . "• **Thích mặc phom rộng rãi (Oversize) hoặc mùa đông lót thêm áo dày bên trong:** Anh/chị có thể cân nhắc chọn **Size {$sizeInfo['upsize']}** nhé!\n\n"
                . "📦 Shop có chính sách **hỗ trợ đổi size miễn phí trong 7 ngày** và **cho phép kiểm tra hàng trước khi nhận** (Ship COD toàn quốc). Anh/chị bấm xem sản phẩm bên dưới để đặt ngay nha! 👇";

            return [
                'reply' => $reply,
                'products' => collect([$targetProduct])
            ];
        }

        // B. Khách hỏi tư vấn size cho sản phẩm cụ thể nhưng CHƯA nhập chiều cao/cân nặng
        if ($targetProduct && !$hw['height'] && !$hw['weight'] && $this->hasAnyKeyword($msgLower, ['size', 'tư vấn', 'vừa', 'mặc'])) {
            $priceText = number_format($targetProduct->sale_price ?? $targetProduct->price, 0, ',', '.') . 'đ';
            $reply = "Dạ em rất sẵn lòng hỗ trợ tư vấn size cho mẫu **[{$targetProduct->name}](/san-pham/{$targetProduct->slug})** (Giá: **{$priceText}**) ạ! 📏✨\n\n"
                . "👉 Anh/chị vui lòng cho em biết **chiều cao** (cm) và **cân nặng** (kg) của mình với nhé!\n\n"
                . "*(Ví dụ: Anh/chị nhắn `cao 1m70 nặng 65kg` để em đối chiếu bảng size chọn chuẩn xác nhất cho mình nha!)*";

            return [
                'reply' => $reply,
                'products' => collect([$targetProduct])
            ];
        }

        // C. Nhận diện câu hỏi chung về chiều cao / cân nặng chọn size (chưa gắn sản phẩm cụ thể)
        if ($hw['weight'] || $hw['height']) {
            $sizeInfo = $this->calculateRecommendedSize($hw['height'], $hw['weight']);
            $heightDesc = $hw['height'] ? "chiều cao **{$hw['height']}cm**" : "";
            $weightDesc = $hw['weight'] ? "cân nặng **{$hw['weight']}kg**" : "";
            $bodyMeasure = trim($heightDesc . ($heightDesc && $weightDesc ? " và " : "") . $weightDesc);

            $bestProducts = Product::latest()->take(3)->get();

            return [
                'reply' => "Dạ với {$bodyMeasure}, theo **bảng size chuẩn của HTD Store** thì anh/chị mặc đẹp và chuẩn phom nhất là **Size {$sizeInfo['size']}** ạ! ✨\n\n"
                    . "💡 *Mẹo chọn đồ:* Nếu anh/chị thích mặc form rộng thoải mái (oversize) hơn thì có thể nhích lên **Size {$sizeInfo['upsize']}** nhé.\n\n"
                    . "Dưới đây là một số mẫu áo đang bán chạy nhất tại shop, anh/chị tham khảo thử nha:",
                'products' => $bestProducts
            ];
        }

        // 2. Nhận diện câu hỏi MÙA ĐÔNG (Áo phao, áo khoác dạ, áo gió, giữ nhiệt)
        if ($this->hasAnyKeyword($msgLower, ['mùa đông', 'mua dong', 'áo đông', 'ao dong', 'mùa lạnh', 'mua lanh', 'rét', 'ret', 'lạnh', 'giữ nhiệt', 'giu nhiet', 'phao', 'áo phao', 'áo khoác', 'ao khoac', 'lông cừu', 'long cuu', 'măng tô', 'mang to', 'ấm', 'áo ấm', 'dạ'])) {
            $winterProducts = $this->queryProductsByIntent('mùa đông');

            $reply = "Dạ, mùa đông này **HTD Store** có rất nhiều mẫu áo giữ nhiệt, áo phao và áo khoác dạ cao cấp cản gió cực ấm áp cho cả nam và nữ ạ! ❄️🧥\n\n"
                . "Em gửi anh/chị các mẫu áo **MÙA ĐÔNG** đang bán chạy nhất hiện nay:\n\n";

            foreach ($winterProducts as $p) {
                $priceText = number_format($p->sale_price ?? $p->price, 0, ',', '.') . 'đ';
                $reply .= "🔹 [{$p->name}](/san-pham/{$p->slug}) - Giá: **{$priceText}**\n";
            }
            $reply .= "\n👉 Anh/chị bấm nút **📏 Tư vấn** bên cạnh từng mẫu để em tư vấn size chuẩn theo chiều cao và cân nặng cho mình nhé! ✨";

            return [
                'reply' => $reply,
                'products' => $winterProducts
            ];
        }

        // 3. Nhận diện câu hỏi MÙA HÈ / THOÁNG MÁT
        if ($this->hasAnyKeyword($msgLower, ['mùa hè', 'mua he', 'nắng', 'nóng', 'mát', 'thoáng mát', 'ngắn tay', 'cộc tay', 'chống nắng'])) {
            $summerProducts = $this->queryProductsByIntent('mùa hè');

            $reply = "Dạ mùa hè thời tiết oi bức, **HTD Store** gợi ý anh/chị các mẫu áo thun cotton 100% thấm hút mồ hôi và áo polo thoáng khí cực mát mẻ ạ: ☀️🌴\n\n";
            foreach ($summerProducts as $p) {
                $priceText = number_format($p->sale_price ?? $p->price, 0, ',', '.') . 'đ';
                $reply .= "🔹 [{$p->name}](/san-pham/{$p->slug}) - Giá: **{$priceText}**\n";
            }
            $reply .= "\n👉 Anh/chị bấm nút **📏 Tư vấn** bên cạnh mẫu áo yêu thích để nhập chiều cao & cân nặng, em sẽ tư vấn size vừa vặn nhất cho mình nha! ✨";

            return [
                'reply' => $reply,
                'products' => $summerProducts
            ];
        }

        // 4. Nhận diện CÔNG SỞ / ĐI LÀM / SƠ MI
        if ($this->hasAnyKeyword($msgLower, ['công sở', 'cong so', 'đi làm', 'di lam', 'sơ mi', 'so mi', 'vest', 'blazer', 'kaki', 'khaki', 'quần âu', 'quan au'])) {
            $workProducts = $this->queryProductsByIntent('công sở');

            $reply = "Dạ với phong cách công sở lịch sự và chỉn chu, em xin gợi ý cho anh/chị các mẫu sơ mi chống nhăn và vest/blazer cao cấp của shop:\n\n";
            foreach ($workProducts as $p) {
                $priceText = number_format($p->sale_price ?? $p->price, 0, ',', '.') . 'đ';
                $reply .= "👔 [{$p->name}](/san-pham/{$p->slug}) - Giá: **{$priceText}**\n";
            }
            $reply .= "\n👉 Anh/chị bấm nút **📏 Tư vấn** bên cạnh từng mẫu để em chọn size chuẩn phom công sở cho mình nhé!";

            return [
                'reply' => $reply,
                'products' => $workProducts
            ];
        }

        // 5. Nhận diện POLO / THUN / JEAN / GIẢM GIÁ
        $otherKeywords = [
            'polo' => ['polo'],
            'áo thun' => ['áo thun', 'ao thun', 't-shirt', 'phông'],
            'quần jean' => ['jean', 'bò', 'quần bò'],
            'giảm giá' => ['sale', 'giảm giá', 'khuyến mãi', 'ưu đãi', 'voucher'],
            'trẻ em' => ['trẻ em', 'tre em', 'bé trai', 'bé gái', 'cho bé'],
            'váy đầm' => ['váy', 'đầm', 'dam', 'tiệc'],
        ];

        foreach ($otherKeywords as $group => $kwList) {
            if ($this->hasAnyKeyword($msgLower, $kwList)) {
                $products = $this->queryProductsByIntent($group);
                if ($products->isNotEmpty()) {
                    $reply = "Dạ, em gửi anh/chị một số mẫu **" . mb_strtoupper($group, 'UTF-8') . "** được yêu thích nhất tại HTD Store:\n\n";
                    foreach ($products as $p) {
                        $priceText = number_format($p->sale_price ?? $p->price, 0, ',', '.') . 'đ';
                        $reply .= "🔹 [{$p->name}](/san-pham/{$p->slug}) - Giá chỉ: **{$priceText}**\n";
                    }
                    $reply .= "\n👉 Anh/chị bấm nút **📏 Tư vấn** bên cạnh mẫu áo để em đo size chuẩn nhất theo chiều cao, cân nặng nhé! ✨";

                    return [
                        'reply' => $reply,
                        'products' => $products
                    ];
                }
            }
        }

        // 6. Chính sách giao hàng / đổi trả
        if ($this->hasAnyKeyword($msgLower, ['đổi', 'trả', 'bảo hành'])) {
            return [
                'reply' => "Dạ chính sách đổi trả tại **HTD Store** cực kỳ thuận tiện cho khách hàng ạ:\n\n"
                    . "✅ **Thời gian:** Hỗ trợ đổi size / đổi mẫu trong vòng **7 ngày** kể từ khi nhận hàng.\n"
                    . "✅ **Điều kiện:** Sản phẩm còn nguyên tem mác, chưa qua giặt tẩy.\n"
                    . "✅ **Đổi tận nơi:** Shop hỗ trợ shipper mang size mới đến tận nhà đổi cho mình nếu không vừa size luôn ạ! ✨",
                'products' => collect()
            ];
        }

        if ($this->hasAnyKeyword($msgLower, ['ship', 'giao hàng', 'phí vận chuyển', 'vận chuyển'])) {
            return [
                'reply' => "Dạ về chính sách giao hàng tại **HTD Store**:\n\n"
                    . "🚀 **Miễn phí vận chuyển (Freeship)** toàn quốc cho đơn hàng từ **500.000đ**.\n"
                    . "📦 Đơn dưới 500k, phí ship đồng giá chỉ 30.000đ.\n"
                    . "⏱️ Thời gian nhận hàng: Nội thành 1-2 ngày, các tỉnh khác 2-4 ngày.\n"
                    . "🔍 Khách hàng được quyền kiểm tra hàng trước khi thanh toán (Ship COD) ạ!",
                'products' => collect()
            ];
        }

        // 7. Lời chào và gợi ý mặc định
        $sampleProducts = Product::latest()->take(3)->get();
        return [
            'reply' => "Dạ em chào anh/chị! Em là **Trợ lý AI của HTD Store** 🤖✨\n\n"
                . "Em có thể giúp anh/chị:\n"
                . "👕 Tư vấn chọn size chuẩn (Anh/chị chỉ cần nhắn chiều cao & cân nặng)\n"
                . "🧥 Gợi ý các mẫu **áo mùa đông** (áo phao, áo khoác dạ, áo giữ nhiệt)\n"
                . "☀️ Gợi ý mẫu **áo mùa hè**, áo sơ mi công sở, quần jeans\n"
                . "📦 Giải đáp chính sách freeship, giao hàng và đổi trả\n\n"
                . "Dưới đây là một số mẫu mới về tuần này của shop, anh/chị đang muốn tìm trang phục nào ạ?",
            'products' => $sampleProducts
        ];
    }

    /**
     * Định dạng danh sách sản phẩm gửi về cho client render thẻ sản phẩm trực tiếp
     */
    protected function formatProductsForClient($products): array
    {
        return $products->map(function($p) {
            $imageSrc = asset('images/logo.png');
            if (!empty($p->image)) {
                $imageSrc = str_starts_with($p->image, 'dummy_url|')
                    ? str_replace('dummy_url|', '', $p->image)
                    : asset('storage/' . $p->image);
            }

            return [
                'id' => $p->id,
                'name' => $p->name,
                'slug' => $p->slug,
                'price' => number_format($p->price, 0, ',', '.') . 'đ',
                'sale_price' => $p->sale_price ? number_format($p->sale_price, 0, ',', '.') . 'đ' : null,
                'has_sale' => !empty($p->sale_price) && $p->sale_price < $p->price,
                'image' => $imageSrc,
                'url' => url('/san-pham/' . $p->slug),
            ];
        })->toArray();
    }

    /**
     * Kiểm tra chuỗi có chứa bất kỳ từ khóa nào trong danh sách không
     */
    protected function hasAnyKeyword(string $text, array $keywords): bool
    {
        foreach ($keywords as $kw) {
            if (str_contains($text, $kw)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Trích xuất thông tin chiều cao (cm) và cân nặng (kg) từ câu chat
     */
    protected function extractHeightAndWeight(string $message): array
    {
        $msgLower = mb_strtolower($message, 'UTF-8');
        $weight = null;
        $height = null;

        if (preg_match('/(\d{2,3})\s*(kg|kí|ký|cân)/u', $msgLower, $m)) {
            $weight = (int) $m[1];
        } elseif (preg_match('/nặng\s*(\d{2,3})/u', $msgLower, $m)) {
            $weight = (int) $m[1];
        }

        if (preg_match('/1m(\d{1,2})/u', $msgLower, $m)) {
            $decimal = strlen($m[1]) === 1 ? $m[1] . '0' : $m[1];
            $height = 100 + (int) $decimal;
        } elseif (preg_match('/(\d{3})\s*cm/u', $msgLower, $m)) {
            $height = (int) $m[1];
        } elseif (preg_match('/cao\s*(\d{3})/u', $msgLower, $m)) {
            $height = (int) $m[1];
        }

        return ['height' => $height, 'weight' => $weight];
    }

    /**
     * Tính toán size chuẩn dựa theo chiều cao và cân nặng theo Bảng Size HTD Store
     */
    protected function calculateRecommendedSize(?int $height, ?int $weight): array
    {
        $size = 'M';
        $upsize = 'L';

        if ($weight) {
            if ($weight < 53) {
                $size = 'S'; $upsize = 'M';
            } elseif ($weight <= 62) {
                $size = 'M'; $upsize = 'L';
            } elseif ($weight <= 70) {
                $size = 'L'; $upsize = 'XL';
            } elseif ($weight <= 78) {
                $size = 'XL'; $upsize = 'XXL';
            } else {
                $size = 'XXL'; $upsize = '3XL (hoặc form rộng)';
            }
        } elseif ($height) {
            if ($height < 160) {
                $size = 'S'; $upsize = 'M';
            } elseif ($height <= 168) {
                $size = 'M'; $upsize = 'L';
            } elseif ($height <= 174) {
                $size = 'L'; $upsize = 'XL';
            } elseif ($height <= 178) {
                $size = 'XL'; $upsize = 'XXL';
            } else {
                $size = 'XXL'; $upsize = '3XL';
            }
        }

        return ['size' => $size, 'upsize' => $upsize];
    }

    /**
     * Tìm sản phẩm được chỉ định trong câu chat (theo tên hoặc từ khóa nhận diện)
     */
    protected function findTargetProduct(string $message): ?Product
    {
        if (preg_match('/tư vấn\s*(?:size)?\s*(?:cho)?\s*([^:\,\.\?]+)/ui', $message, $match)) {
            $candidate = trim($match[1]);
            $candidate = trim(preg_replace('/^(mẫu|sản phẩm)\s+/ui', '', $candidate));
            if (mb_strlen($candidate) >= 3) {
                $p = Product::where('name', 'like', "%{$candidate}%")->first();
                if ($p) return $p;

                // Thử tìm theo từng từ khóa quan trọng nếu tên dài
                $words = array_filter(explode(' ', $candidate), fn($w) => mb_strlen($w) >= 3);
                if (!empty($words)) {
                    $p = Product::where(function($q) use ($words) {
                        foreach ($words as $w) {
                            $q->where('name', 'like', "%{$w}%");
                        }
                    })->first();
                    if ($p) return $p;
                }
            }
        }

        $allProducts = Product::select('id', 'name', 'slug', 'price', 'sale_price', 'image')->get();
        foreach ($allProducts as $p) {
            if (mb_stripos($message, $p->name) !== false) {
                return $p;
            }
        }

        return null;
    }
}