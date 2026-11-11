<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class ShopUnitTest extends TestCase
{
    /** @test */
    public function test_utc_01_validate_password_below_limit()
    {
        $password = "12345";
        $this->assertFalse(strlen($password) >= 8);
    }

    /** @test */
    public function test_utc_02_validate_password_valid()
    {
        $password = "12345678";
        $this->assertTrue(strlen($password) >= 8);
    }

    /** @test */
    public function test_utc_03_calculate_cart_total()
    {
        $price = 100000;
        $quantity = 2;
        $total = $price * $quantity;
        $this->assertEquals(200000, $total);
    }

    /** @test */
    public function test_utc_04_check_stock_limit_negative()
    {
        $stock = 5;
        $quantity = -1;
        $isValid = ($quantity > 0 && $quantity <= $stock);
        $this->assertFalse($isValid);
    }

    /** @test */
    public function test_utc_05_validate_email_format()
    {
        $email = "admingmail.com";
        $isValid = filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
        $this->assertFalse($isValid);
    }

    /** @test */
    public function test_utc_06_validate_phone_10_digits()
    {
        $phone = "0912345678";
        $isValid = preg_match('/^[0-9]{10,11}$/', $phone) === 1;
        $this->assertTrue($isValid);
    }

    /** @test */
    public function test_utc_07_validate_phone_invalid_length()
    {
        $phone = "091234568"; // 9 số
        $isValid = preg_match('/^[0-9]{10,11}$/', $phone) === 1;
        $this->assertFalse($isValid);
    }

    /** @test */
    public function test_shift_conflict_identical_time()
    {
        $overlap = \App\Http\Controllers\Admin\ShiftScheduleController::isTimeOverlap('08:00', '12:00', '08:00', '12:00');
        $this->assertTrue($overlap);
    }

    /** @test */
    public function test_shift_conflict_overlapping_time()
    {
        $overlap = \App\Http\Controllers\Admin\ShiftScheduleController::isTimeOverlap('06:00', '14:00', '08:00', '12:00');
        $this->assertTrue($overlap);
    }

    /** @test */
    public function test_shift_consecutive_no_conflict()
    {
        // Ca 1 kết thúc lúc 14:00, Ca 2 bắt đầu lúc 14:00 -> Không trùng!
        $overlap = \App\Http\Controllers\Admin\ShiftScheduleController::isTimeOverlap('06:00', '14:00', '14:00', '22:00');
        $this->assertFalse($overlap);
    }

    /** @test */
    public function test_shift_separate_no_conflict()
    {
        $overlap = \App\Http\Controllers\Admin\ShiftScheduleController::isTimeOverlap('06:00', '10:00', '14:00', '18:00');
        $this->assertFalse($overlap);
    }

    /** @test */
    public function test_shift_overnight_conflict()
    {
        // Ca đêm 22:00 -> 06:00 sáng hôm sau, ca sáng 05:00 -> 09:00 -> Trùng lúc 05:00-06:00
        $overlap = \App\Http\Controllers\Admin\ShiftScheduleController::isTimeOverlap('22:00', '06:00', '05:00', '09:00');
        $this->assertTrue($overlap);
    }
}