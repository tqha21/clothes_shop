<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mã Xác Nhận Đặt Lại Mật Khẩu</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f3f4f6; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #1f2937; line-height: 1.6;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color: #f3f4f6; padding: 40px 15px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" style="max-width: 560px; background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);">
                    <!-- Header -->
                    <tr>
                        <td style="background-color: #dc2626; padding: 32px 30px; text-align: center;">
                            <h1 style="margin: 0; color: #ffffff; font-size: 26px; font-weight: 800; letter-spacing: -0.5px;">HTD STORE</h1>
                            <p style="margin: 6px 0 0; color: #fecaca; font-size: 13px; font-weight: 500; text-transform: uppercase; letter-spacing: 1px;">Thời trang chất lượng cao</p>
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="padding: 36px 32px;">
                            <h2 style="margin: 0 0 16px; color: #111827; font-size: 20px; font-weight: 700;">Yêu cầu đặt lại mật khẩu</h2>
                            <p style="margin: 0 0 16px; color: #4b5563; font-size: 15px;">
                                Xin chào <strong>{{ $userName }}</strong>,
                            </p>
                            <p style="margin: 0 0 24px; color: #4b5563; font-size: 15px;">
                                Chúng tôi nhận được yêu cầu cấp lại mật khẩu cho tài khoản HTD STORE liên kết với địa chỉ email này. Dưới đây là mã xác thực (OTP) của bạn:
                            </p>

                            <!-- OTP Box -->
                            <div style="background-color: #fef2f2; border: 2px dashed #f87171; border-radius: 12px; padding: 24px 20px; text-align: center; margin: 28px 0;">
                                <div style="font-size: 13px; font-weight: 600; text-transform: uppercase; color: #991b1b; letter-spacing: 1px; margin-bottom: 8px;">
                                    Mã xác thực của bạn
                                </div>
                                <div style="font-size: 38px; font-weight: 800; color: #dc2626; letter-spacing: 8px; font-family: 'Courier New', Courier, monospace; margin: 6px 0;">
                                    {{ $otp }}
                                </div>
                                <div style="font-size: 13px; color: #6b7280; margin-top: 8px;">
                                    ⏰ Mã có hiệu lực trong vòng <strong>15 phút</strong>
                                </div>
                            </div>

                            <p style="margin: 0 0 12px; color: #4b5563; font-size: 14px;">
                                Vui lòng nhập mã này vào trang đặt lại mật khẩu để tiếp tục đổi mật khẩu mới.
                            </p>

                            <!-- Warning Note -->
                            <div style="background-color: #fffbeb; border-left: 4px solid #f59e0b; padding: 12px 16px; border-radius: 4px; margin-top: 24px;">
                                <p style="margin: 0; color: #92400e; font-size: 13px; line-height: 1.5;">
                                    <strong>Lưu ý bảo mật:</strong> Tuyệt đối không chia sẻ mã xác thực này cho bất kỳ ai, kể cả nhân viên hỗ trợ HTD STORE. Nếu bạn không thực hiện yêu cầu này, hãy bỏ qua email này.
                                </p>
                            </div>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f9fafb; padding: 24px 32px; border-top: 1px solid #e5e7eb; text-align: center;">
                            <p style="margin: 0 0 8px; color: #6b7280; font-size: 12px;">
                                Đây là email tự động, vui lòng không trả lời trực tiếp email này.
                            </p>
                            <p style="margin: 0; color: #9ca3af; font-size: 12px;">
                                © {{ date('Y') }} HTD STORE. Mọi quyền được bảo lưu.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
