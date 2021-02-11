<?php

namespace App;

use App\Models\SocialAccount;
use App\Models\User;
use Laravel\Socialite\Two\User as SocialUser;

class SocialAccountService
{
    public function findOrCreate(SocialUser $social, string $provider): User
    {
        $account = SocialAccount::firstWhere([
            'provider_name' => $provider,
            'provider_id' => $social->getId()
        ]);

        if ($account) {
            return $account->user;
        }

        $user = User::firstOrCreate(
            ['email' => $social->getEmail()],
            [
                'email' => $social->getEmail(),
                'name' => $social->getName()
            ]
        );

        $user->accounts()->create([
            'provider_name' => $provider,
            'provider_id' => $social->getId()
        ]);

        return $user;
    }
}
