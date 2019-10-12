<?php

namespace Mrkatz\LoginProviders\Traits;

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

trait ResetsLoginProviders
{
    use ResetsPasswords;
    /**
     * Reset the given user's password.
     *
     * @param  CanResetPassword $user
     * @param  string                                      $password
     * @return void
     */
    protected function resetPassword($user, $password)
    {
        $user->password(Hash::make($password));

        $user->setRememberToken(Str::random(60));

        $user->save();

        event(new PasswordReset($user));

        $this->guard()->login($user);
    }

}


