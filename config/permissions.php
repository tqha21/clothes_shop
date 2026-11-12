<?php

/*
|--------------------------------------------------------------------------
| Ma trận phân quyền theo nghiệp vụ (Admin / Manager / Staff)
|--------------------------------------------------------------------------
| Mỗi quyền được đăng ký thành một Laravel Gate trong AuthServiceProvider.
| Dùng trong route: ->middleware('can:pos.sell')
| Dùng trong code:  $user->hasPermission('pos.sell') hoặc Gate::allows(...)
| Ngoài vai trò, tài khoản phải ở trạng thái active mới được cấp quyền.
*/

return [
    'roles' => [
        'pos.sell'          => ['admin', 'manager', 'staff'],
        'pos.refund'        => ['admin', 'manager'],
        'pos.void'          => ['admin', 'manager'],
        'inventory.view'    => ['admin', 'manager', 'staff'],
        'inventory.adjust'  => ['admin', 'manager'],
        'inventory.transfer'=> ['admin', 'manager'],
        'shift.view'        => ['admin', 'manager', 'staff'],
        'shift.manage'      => ['admin', 'manager'],
        'report.store'      => ['admin', 'manager'],
        'report.personal'   => ['admin', 'manager', 'staff'],
        'employee.manage'   => ['admin'],
    ],

    // Mô tả hiển thị (dùng cho trang tham chiếu / tài liệu)
    'labels' => [
        'pos.sell'          => 'Bán hàng tại quầy',
        'pos.refund'        => 'Hoàn tiền / đổi trả',
        'pos.void'          => 'Hủy giao dịch POS',
        'inventory.view'    => 'Xem tồn kho',
        'inventory.adjust'  => 'Nhập / xuất / điều chỉnh / kiểm kê',
        'inventory.transfer'=> 'Chuyển kho giữa cửa hàng',
        'shift.view'        => 'Xem ca & mở/đóng ca của mình',
        'shift.manage'      => 'Phân ca & quản lý phiên ca',
        'report.store'      => 'Báo cáo doanh số cửa hàng',
        'report.personal'   => 'Báo cáo doanh số cá nhân',
        'employee.manage'   => 'Quản lý tài khoản nhân viên',
    ],
];

