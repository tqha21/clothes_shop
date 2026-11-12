<?php

namespace Tests\Unit;

use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\ShiftSession;
use App\Models\User;
use PHPUnit\Framework\TestCase;

class OmnichannelTest extends TestCase
{
    /** @test TC-01: Ma trận quyền theo nghiệp vụ */
    public function test_tc01_role_permission_matrix()
    {
        $matrix = require __DIR__ . '/../../config/permissions.php';

        // pos.sell cho phép cả admin, manager, staff
        $this->assertContains('admin', $matrix['roles']['pos.sell']);
        $this->assertContains('manager', $matrix['roles']['pos.sell']);
        $this->assertContains('staff', $matrix['roles']['pos.sell']);

        // pos.refund chỉ cho phép admin, manager (staff không được tự hoàn tiền)
        $this->assertContains('admin', $matrix['roles']['pos.refund']);
        $this->assertContains('manager', $matrix['roles']['pos.refund']);
        $this->assertNotContains('staff', $matrix['roles']['pos.refund']);

        // report.store chỉ cho phép admin, manager
        $this->assertContains('admin', $matrix['roles']['report.store']);
        $this->assertContains('manager', $matrix['roles']['report.store']);
        $this->assertNotContains('staff', $matrix['roles']['report.store']);

        // employee.manage chỉ cho phép admin
        $this->assertEquals(['admin'], $matrix['roles']['employee.manage']);
    }

    /** @test TC-02: Tính toán giá bán hiệu lực (effective price) */
    public function test_tc02_product_effective_price_calculation()
    {
        $product = new Product();
        $product->price = 500000;
        $product->sale_price = 350000;
        $this->assertEquals(350000, $product->effective_price);

        // Trường hợp không có giá sale hoặc giá sale lớn hơn giá gốc
        $product->sale_price = 0;
        $this->assertEquals(500000, $product->effective_price);

        $product->sale_price = 600000; // lỗi nhập liệu sale > price -> dùng price gốc
        $this->assertEquals(500000, $product->effective_price);
    }

    /** @test TC-03: Tính toán đối soát tiền mặt phiên ca (Expected vs Counted vs Difference) */
    public function test_tc03_shift_session_cash_reconciliation()
    {
        $session = new ShiftSession();
        $session->opening_cash = 1000000;  // 1 triệu đầu ca
        $session->cash_sales = 3500000;    // +3.5 triệu bán hàng tiền mặt
        $session->cash_refunds = 200000;   // -200k hoàn tiền khách
        $session->cash_in = 50000;         // +50k thu khác
        $session->cash_out = 150000;       // -150k chi tiền shipper

        // Tiền mặt dự kiến = 1.000.000 + 3.500.000 - 200.000 + 50.000 - 150.000 = 4.200.000đ
        $expected = $session->current_expected_cash;
        $this->assertEquals(4200000, $expected);

        // Trường hợp kiểm đếm đủ
        $counted = 4200000;
        $difference = $counted - $expected;
        $this->assertEquals(0, $difference);

        // Trường hợp thừa tiền
        $countedSurplus = 4250000;
        $this->assertEquals(50000, $countedSurplus - $expected);

        // Trường hợp thiếu tiền
        $countedShortage = 4100000;
        $this->assertEquals(-100000, $countedShortage - $expected);
    }

    /** @test TC-04: Tính doanh thu thuần của đơn hàng sau khi hoàn trả (BR-07) */
    public function test_tc04_order_net_revenue_calculation()
    {
        $order = new Order();
        $order->total = 1200000;
        $order->refunded_amount = 0;
        $this->assertEquals(1200000, $order->net_total);

        // Hoàn 1 phần
        $order->refunded_amount = 300000;
        $this->assertEquals(900000, $order->net_total);

        // Hoàn toàn bộ
        $order->refunded_amount = 1200000;
        $this->assertEquals(0, $order->net_total);
    }

    /** @test TC-05: Nhãn hiển thị phương thức thanh toán */
    public function test_tc05_payment_method_labels()
    {
        $p1 = new Payment(['method' => 'cash']);
        $this->assertEquals('Tiền mặt', $p1->method_label);

        $p2 = new Payment(['method' => 'card']);
        $this->assertEquals('Thẻ ngân hàng (POS)', $p2->method_label);

        $p3 = new Payment(['method' => 'bank_transfer']);
        $this->assertEquals('Chuyển khoản / QR', $p3->method_label);
    }

    /** @test TC-06: Kiểm tra SKU tự sinh */
    public function test_tc06_product_sku_generation()
    {
        $p = new Product();
        $p->id = 42;
        $this->assertEquals('SP00042', $p->sku);
    }
}

