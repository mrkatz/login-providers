<?php

namespace Mrkatz\LoginProviders\Traits;

use App\Actions\Fortify\CreateNewUser;
use App\User;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Auth;
use Laravel\Socialite\Contracts\User as ProviderUser;
use Redirect;
use Socialite;
use Mrkatz\LoginProviders\Model\LoginProvider;
use Str;
use Throwable;

trait UsesLoginProviders
{
    /**
     * Redirect to social provider.
     *
     * @param $provider
     *
     * @return RedirectResponse|Redirector
     */
    public function redirectToProvider($provider)
    {
        if (!in_array($provider, config('login-providers.providers.social'))) {
            return $this->redirectOnFail();
        }

        return Socialite::with($provider)->redirect();
    }

    /**
     * Redirect User on Failed Registration/Login
     *
     * @return RedirectResponse
     */
    public function redirectOnFail()
    {
        return Redirect::to('login');
    }

    /**
     * Obtain the user information from social network.
     *
     * @param $provider
     *
     * @return RedirectResponse
     * @throws Throwable
     */
    public function handleProviderCallback($provider)
    {
        try {
            $providerUser = Socialite::driver($provider)->user();
        } catch (Throwable | Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }
            return $this->redirectOnFail();
        }

        $user = $this->findOrCreateLoginProvider($providerUser, $provider);

        Auth::login($user);

        return $this->redirectOnSuccess();
    }

    /**
     * Find or Create New User & LoginProvider
     *
     * @param ProviderUser $providerUser
     * @param $provider
     *
     * @return User
     */
    protected function findOrCreateLoginProvider($providerUser, $provider)
    {
        $socialLogin = LoginProvider::where('provider_id', '=', $providerUser->id)
            ->where('provider_type', '=', $provider)
            ->first();

        if ($socialLogin == null) {
            $verified = true;

            $email = $providerUser->getEmail() ?: $provider . '.' . $providerUser->getId() . '@noemail.com';

            $user = User::where('email', '=', $email)->first();

            if ($user === null) {

                $user = $this->create([
                    'name' => $providerUser->getName(),
                    'email' => $email,
                    'password' => Str::random(),
                    'nickname' => $providerUser->nickname,
                    'avatar' => $providerUser->avatar,
                    'provider' => $providerUser,
                    'social' => true,
                ]);
            } else {

                if (!auth()->check() || auth()->user()->id !== $user->id) {
                    $verified = false;
                }
            }

            $socialLogin = LoginProvider::create([
                'provider_id' => $providerUser->getId(),
                'provider_type' => $provider,
                'verified' => $verified,
                'nickname' => $providerUser->nickname,
                'name' => $providerUser->getName(),
                'email' => $email,
                'avatar' => $providerUser->avatar,
                'meta' => json_encode($providerUser),
                'user_id' => $user->id,
            ]);

        }

        return $socialLogin->user;
    }

    /**
     * Redirect on successful Registration/Login
     *
     * @return RedirectResponse
     */
    public function redirectOnSuccess()
    {
//        flash('Welcome!!! You are now logged in...')->success();

        return redirect()->intended($this->redirectPath());
    }


}
