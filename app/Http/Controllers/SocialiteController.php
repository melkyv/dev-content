<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Socialite;

class SocialiteController extends Controller
{
    public function redirect($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function callback($provider)
    {
        try {
            $socialUser = Socialite::driver($provider)->user();

            $user = User::updateOrCreate(
                ['email' => $socialUser->getEmail()],
                [
                    'name' => $socialUser->getName(),
                    'avatar_path' => function ($user) use ($socialUser) {
                        if ($user->avatar_path) {
                            return $user->avatar_path;
                        }

                        return $socialUser->getAvatar();
                    },
                    'provider' => $provider,
                    'provider_id' => $socialUser->getId(),
                    'avatar_path' => $socialUser->getAvatar(),
                ]
            );

            Auth::login($user);

            return redirect()->intended('dashboard');

        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'Erro ao autenticar');
        }
    }
}
