<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


use App\Models\GoogleAccount;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

class GoogleAuthController extends Controller
{
    public function redirect(): RedirectResponse
    {
        return Socialite::driver('google')
            ->scopes([
                'openid',
                'profile',
                'email',
                'https://www.googleapis.com/auth/drive',
            ])
            ->with([
                'access_type' => 'offline',
                'prompt' => 'consent select_account',
            ])
            ->redirect();
    }

    public function callback(): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            $user = User::updateOrCreate(
                [
                    'email' => $googleUser->getEmail(),
                ],
                [
                    'name' => $googleUser->getName(),
                ]
            );

            $email = $googleUser->getEmail();

            $domain = str_contains($email, '@')
                ? explode('@', $email)[1]
                : null;

            $accountType = $domain === 'gmail.com'
                ? 'personal'
                : 'workspace';

            $googleAccount = GoogleAccount::updateOrCreate(
                [
                    'google_id' => $googleUser->getId(),
                ],
                [
                    'user_id' => $user->id,
                    'email' => $email,
                    'avatar' => $googleUser->getAvatar(),
                    'account_type' => $accountType,
                    'workspace_domain' => $accountType === 'workspace'
                        ? $domain
                        : null,
                    'access_token' => $googleUser->token,
                    'refresh_token' => $googleUser->refreshToken,
                    'token_expires_at' => $googleUser->expiresIn
                        ? now()->addSeconds($googleUser->expiresIn)
                        : null,
                ]
            );

            Auth::login($user);

            request()->session()->regenerate();

            return redirect()->route('drive.index');

        } catch (Throwable $exception) {

            report($exception);

            //dd($exception->getMessage());

            return redirect()
                ->route('login')
                ->with('error', 'Google authentication failed. Please try again.');
        }
    }

    public function logout(): RedirectResponse
    {
        Auth::logout();

        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()->route('login');
    }
}
