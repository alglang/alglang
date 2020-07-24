<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(): \Illuminate\View\View
    {
        return view('auth.login');
    }

    public function redirectToProvider(string $provider): \Illuminate\Http\RedirectResponse
    {
        try {
            return Socialite::driver($provider)->redirect();
        } catch (\InvalidArgumentException $e) {
            abort(404);
        }
    }

    public function handleProviderCallback(string $provider): \Illuminate\Http\RedirectResponse
    {
        try {
            $user = Socialite::driver($provider)->user();
        } catch (\InvalidArgumentException $e) {
            abort(404);
        } catch (\Exception $e) {
            return redirect()->route('auth', ['provider' => $provider]);
        }

        $authUser = $this->findOrCreateUser($user);

        auth()->login($authUser, true);

        return redirect()->route('home');
    }

    public function findOrCreateUser(\Laravel\Socialite\AbstractUser $githubUser): User
    {
        return User::firstOrCreate(
            ['github_id' => $githubUser->id],
            ['name' => $githubUser->nickname, 'email' => $githubUser->email]
        );
    }
}
