<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check() || !in_array(auth()->user()->role, ['admin', 'manager', 'staff'])) {
            return redirect('/')->with('error', 'Bạn không có quyền truy cập trang này.');
        }

        // Tài khoản nhân viên bị vô hiệu hóa: đăng xuất ngay
        if (!auth()->user()->isActive()) {
            auth()->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('login')->with('error', 'Tài khoản của bạn đã bị vô hiệu hóa.');
        }
        
        return $next($request);
    }
}
