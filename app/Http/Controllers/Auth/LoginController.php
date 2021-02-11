<?php

namespace App\Http\Controllers\Auth;

use App\SocialAccountService;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Laravel\Socialite\Facades\Socialite;

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

    public function redirectToProvider(string $provider): RedirectResponse
    {
        try {
            return Socialite::driver($provider)->redirect();
        } catch (\InvalidArgumentException $error) {
            abort(404);
        }
    }

    public function handleProviderCallback(SocialAccountService $accountService, string $provider): RedirectResponse
    {
        try {
            $social = Socialite::driver($provider)->user();
        } catch (\InvalidArgumentException $error) {
            abort(404);
        }

        $user = $accountService->findOrCreate($social, $provider);

        auth()->login($user, true);
        return redirect()->route('home');
    }

    public function findOrCreateUser(object $githubUser): User
    {
        return User::firstOrCreate(
            ['github_id' => $githubUser->id],
            ['name' => $githubUser->nickname, 'email' => $githubUser->email]
        );
    }
}
