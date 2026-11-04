<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Mail\ResetPasswordOtpMail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class ForgotPasswordController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Hiển thị form nhập email để nhận mã xác nhận OTP.
     */
    public function showForgotForm()
    {
        return view('auth.passwords.email');
    }

    /**
     * Gửi mã OTP xác nhận về Gmail của người dùng.
     */
    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.required' => 'Vui lòng nhập địa chỉ email của bạn.',
            'email.email' => 'Địa chỉ email không đúng định dạng.',
            'email.exists' => 'Không tìm thấy tài khoản nào liên kết với email này.',
        ]);

        $email = trim($request->email);
        $user = User::where('email', $email)->first();

        // Tạo mã OTP ngẫu nhiên 6 chữ số
        $otp = (string) random_int(100000, 999999);

        // Đảm bảo bảng tồn tại
        $this->ensurePasswordResetsTableExists();

        // Lưu hoặc cập nhật mã vào bảng password_resets
        DB::table('password_resets')->where('email', $email)->delete();
        DB::table('password_resets')->insert([
            'email' => $email,
            'token' => $otp,
            'created_at' => Carbon::now(),
        ]);

        // Gửi email chứa mã OTP
        $mailSent = false;
        try {
            Mail::to($email)->send(new ResetPasswordOtpMail($otp, $user->name ?? 'Quý khách'));
            $mailSent = true;
        } catch (\Exception $e) {
            Log::error('Lỗi gửi email đặt lại mật khẩu: ' . $e->getMessage());
        }

        // Lưu email vào session để tiện chuyển qua bước xác thực
        session(['reset_password_email' => $email]);

        // Nếu gửi mail thành công hoặc đang ở môi trường dev/local
        if ($mailSent) {
            return redirect()->route('password.reset.code')->with('status', 'Mã xác nhận 6 chữ số đã được gửi tới email ' . $email . '. Vui lòng kiểm tra hộp thư đến (hoặc hòm thư rác/spam).');
        } else {
            // Trường hợp localhost chưa cấu hình SMTP mail server
            return redirect()->route('password.reset.code')
                ->with('status', 'Mã xác nhận đã được tạo thành công cho email: ' . $email)
                ->with('dev_otp_code', $otp);
        }
    }

    /**
     * Hiển thị giao diện nhập mã OTP và đổi mật khẩu mới.
     */
    public function showVerifyCodeForm(Request $request)
    {
        $email = $request->get('email', session('reset_password_email'));

        if (!$email) {
            return redirect()->route('password.request')->withErrors([
                'email' => 'Vui lòng nhập email trước khi tiến hành xác nhận mã.',
            ]);
        }

        return view('auth.passwords.reset', compact('email'));
    }

    /**
     * Xác thực mã OTP và cập nhật mật khẩu mới.
     */
    public function verifyCodeAndReset(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'otp' => 'required|string|size:6',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'email.required' => 'Vui lòng nhập email.',
            'email.email' => 'Email không hợp lệ.',
            'email.exists' => 'Tài khoản không tồn tại trong hệ thống.',
            'otp.required' => 'Vui lòng nhập mã xác nhận 6 chữ số.',
            'otp.size' => 'Mã xác nhận phải gồm đúng 6 chữ số.',
            'password.required' => 'Vui lòng nhập mật khẩu mới.',
            'password.min' => 'Mật khẩu mới phải có ít nhất 6 ký tự.',
            'password.confirmed' => 'Mật khẩu xác nhận không khớp.',
        ]);

        $email = trim($request->email);
        $otp = trim($request->otp);

        $this->ensurePasswordResetsTableExists();

        // Kiểm tra mã OTP trong database
        $record = DB::table('password_resets')->where('email', $email)->first();

        if (!$record || $record->token !== $otp) {
            return back()->withInput($request->only('email'))
                ->withErrors(['otp' => 'Mã xác nhận không đúng hoặc đã được sử dụng. Vui lòng kiểm tra lại.']);
        }

        // Kiểm tra thời hạn mã (15 phút)
        $createdAt = Carbon::parse($record->created_at);
        if ($createdAt->addMinutes(15)->isPast()) {
            return back()->withInput($request->only('email'))
                ->withErrors(['otp' => 'Mã xác nhận này đã hết hạn (chỉ có hiệu lực trong 15 phút). Vui lòng yêu cầu mã mới.']);
        }

        // Cập nhật mật khẩu cho user
        $user = User::where('email', $email)->first();
        if (!$user) {
            return back()->withInput($request->only('email'))
                ->withErrors(['email' => 'Không tìm thấy người dùng.']);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        // Xóa token đã sử dụng và dọn session
        DB::table('password_resets')->where('email', $email)->delete();
        session()->forget('reset_password_email');

        return redirect()->route('login')->with('status', 'Đặt lại mật khẩu thành công! Bạn có thể đăng nhập ngay với mật khẩu mới.');
    }

    /**
     * Gửi lại mã xác nhận OTP.
     */
    public function resendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.required' => 'Vui lòng cung cấp email để gửi lại mã.',
            'email.exists' => 'Không tìm thấy tài khoản phù hợp với email này.',
        ]);

        $email = trim($request->email);
        $user = User::where('email', $email)->first();

        $otp = (string) random_int(100000, 999999);

        $this->ensurePasswordResetsTableExists();

        DB::table('password_resets')->where('email', $email)->delete();
        DB::table('password_resets')->insert([
            'email' => $email,
            'token' => $otp,
            'created_at' => Carbon::now(),
        ]);

        $mailSent = false;
        try {
            Mail::to($email)->send(new ResetPasswordOtpMail($otp, $user->name ?? 'Quý khách'));
            $mailSent = true;
        } catch (\Exception $e) {
            Log::error('Lỗi gửi lại mã OTP: ' . $e->getMessage());
        }

        session(['reset_password_email' => $email]);

        if ($mailSent) {
            return redirect()->route('password.reset.code')->with('status', 'Mã xác nhận mới đã được gửi tới email của bạn.');
        } else {
            return redirect()->route('password.reset.code')
                ->with('status', 'Đã cấp mã xác nhận mới cho email: ' . $email)
                ->with('dev_otp_code', $otp);
        }
    }

    /**
     * Đảm bảo bảng password_resets luôn tồn tại.
     */
    private function ensurePasswordResetsTableExists()
    {
        if (!Schema::hasTable('password_resets')) {
            Schema::create('password_resets', function (Blueprint $table) {
                $table->string('email')->index();
                $table->string('token');
                $table->timestamp('created_at')->nullable();
            });
        }
    }
}
