<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;
use Exception;

class SocialLoginController extends Controller
{
    /**
     * Supported providers
     */
    protected $allowedProviders = ['google', 'facebook'];

    /**
     * Redirect the user to the provider authentication page.
     *
     * @param Request $request
     * @param string $provider
     * @return \Illuminate\Http\Response
     */
    public function redirect(Request $request, $provider)
    {
        if (!in_array($provider, $this->allowedProviders)) {
            return redirect()->route('login')->with('error', 'Phương thức đăng nhập không được hỗ trợ.');
        }

        $clientId = config("services.{$provider}.client_id");
        $clientSecret = config("services.{$provider}.client_secret");

        // Check if demo bypass was requested in local development environment
        if ($request->has('demo') && app()->environment('local')) {
            return $this->handleDemoLogin($provider);
        }

        if (empty($clientId) || empty($clientSecret)) {
            $providerName = ucfirst($provider);
            $envKey = strtoupper($provider);

            if (app()->environment('local')) {
                return redirect()->route('login')->with('social_demo_prompt', [
                    'provider' => $provider,
                    'name' => $providerName,
                    'env_key' => $envKey,
                    'demo_url' => route('social.redirect', ['provider' => $provider, 'demo' => 1]),
                ]);
            }

            return redirect()->route('login')->with(
                'error',
                "Chưa cấu hình {$providerName} API credentials! Vui lòng thêm {$envKey}_CLIENT_ID và {$envKey}_CLIENT_SECRET vào file .env."
            );
        }

        try {
            return Socialite::driver($provider)->redirect();
        } catch (Exception $e) {
            Log::error("Social login redirect error [{$provider}]: " . $e->getMessage());
            return redirect()->route('login')->with('error', 'Không thể chuyển hướng đến ' . ucfirst($provider) . ': ' . $e->getMessage());
        }
    }

    /**
     * Obtain the user information from the provider.
     *
     * @param string $provider
     * @return \Illuminate\Http\Response
     */
    public function callback($provider)
    {
        if (!in_array($provider, $this->allowedProviders)) {
            return redirect()->route('login')->with('error', 'Phương thức đăng nhập không được hỗ trợ.');
        }

        try {
            // Attempt stateful user retrieval, fallback to stateless if session state mismatch occurs
            try {
                $socialUser = Socialite::driver($provider)->user();
            } catch (Exception $e) {
                $socialUser = Socialite::driver($provider)->stateless()->user();
            }

            if (!$socialUser) {
                return redirect()->route('login')->with('error', 'Không lấy được thông tin từ ' . ucfirst($provider) . '. Vui lòng thử lại!');
            }

            $socialId = $socialUser->getId();
            $email = $socialUser->getEmail();
            $name = $socialUser->getName() ?: ($socialUser->getNickname() ?: 'Khách hàng ' . ucfirst($provider));
            $avatar = $socialUser->getAvatar();
            $token = $socialUser->token ?? null;

            // 1. Search existing user by provider and provider_id
            $user = User::where('provider', $provider)
                ->where('provider_id', $socialId)
                ->first();

            // 2. If not found by provider_id, search by email if available
            if (!$user && !empty($email)) {
                $user = User::where('email', $email)->first();
            }

            if ($user) {
                // Update social credentials and avatar
                $updateData = [
                    'provider' => $provider,
                    'provider_id' => $socialId,
                ];
                if ($token) {
                    $updateData['provider_token'] = $token;
                }
                if ($avatar && empty($user->avatar)) {
                    $updateData['avatar'] = $avatar;
                }
                $user->update($updateData);
            } else {
                // If provider did not return email (e.g. phone-based Facebook account), generate fallback
                if (empty($email)) {
                    $email = $provider . '_' . $socialId . '@social.shop';
                }

                // Create new user account
                $user = User::create([
                    'name' => $name,
                    'email' => $email,
                    'role' => 'customer',
                    'status' => 'active',
                    'provider' => $provider,
                    'provider_id' => $socialId,
                    'provider_token' => $token,
                    'avatar' => $avatar,
                    'password' => Hash::make(Str::random(32)),
                    'email_verified_at' => now(),
                ]);
            }

            // Log user in
            Auth::login($user, true);

            $targetRoute = $user->isAdmin() ? route('admin.dashboard') : route('home');

            return redirect()->intended($targetRoute)->with('success', 'Đăng nhập thành công bằng ' . ucfirst($provider) . '! Chào mừng bạn, ' . $user->name . '.');

        } catch (Exception $e) {
            Log::error("Social login callback error [{$provider}]: " . $e->getMessage());
            return redirect()->route('login')->with('error', 'Lỗi đăng nhập qua ' . ucfirst($provider) . ': ' . $e->getMessage());
        }
    }

    /**
     * Demo login handler for local environment testing without OAuth credentials.
     *
     * @param string $provider
     * @return \Illuminate\Http\Response
     */
    protected function handleDemoLogin($provider)
    {
        $demoEmail = "demo_{$provider}@htdstore.vn";
        $providerName = ucfirst($provider);

        $avatarUrl = $provider === 'google'
            ? 'https://lh3.googleusercontent.com/a/default-user=s96-c'
            : 'https://ui-avatars.com/api/?name=FB+User&background=1877F2&color=fff&size=128';

        $user = User::firstOrCreate(
            ['email' => $demoEmail],
            [
                'name' => "Khách hàng {$providerName} (Demo)",
                'role' => 'customer',
                'status' => 'active',
                'provider' => $provider,
                'provider_id' => 'demo_' . $provider . '_' . rand(100000, 999999),
                'avatar' => $avatarUrl,
                'password' => Hash::make(Str::random(32)),
                'email_verified_at' => now(),
            ]
        );

        if (empty($user->avatar)) {
            $user->update(['avatar' => $avatarUrl]);
        }

        Auth::login($user, true);

        return redirect()->intended(route('home'))->with('success', "Đăng nhập thành công bằng tài khoản {$providerName}! Chào mừng bạn, {$user->name}.");
    }
}
