<?php

namespace Mrkatz\LoginProviders\Traits;

use App\User;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Auth;
use Redirect;
use Socialite;
use Mrkatz\LoginProviders\Model\LoginProvider;
use Str;
use Throwable;

trait UsesLoginProviders
{
    use Configable;

    /**
     * Redirect to social provider.
     *
     * @param $provider
     *
     * @return RedirectResponse|Redirector
     */
    public function redirectToProvider($provider)
    {
        if (!in_array($provider, $this->getConfigValue('providers.social'))) {
            return redirect('login');
        }

        return Socialite::with($provider)->redirect();
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
        } catch (Throwable |Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }
            return $this->redirectOnFail();
        }

        $user = $this->findOrCreateLoginProvider($providerUser, $provider);

        Auth::login($user);

        return $this->redirectOnSuccess();
    }

    public function redirectOnFail()
    {
        return Redirect::to('login');
    }

    protected function findOrCreateLoginProvider($providerUser, $provider)
    {
        $socialLogin = LoginProvider::where('provider_id', '=', $providerUser->id)
                                    ->where('provider_type', '=', $provider)
                                    ->first();

        if ($socialLogin == null) {
            $verified = true;

            $user = User::where('email', '=', $providerUser->getEmail())->first();

            if ($user === null) {
                $user = $this->create([
                                          'name'     => $providerUser->getName(),
                                          'email'    => $providerUser->getEmail() == '' ? Str::random(30) . "@noemail.com" : $providerUser->getEmail(),
                                          'password' => Str::random(),
                                          'nickname' => $providerUser->nickname,
                                          'avatar'   => $providerUser->avatar,
                                          'provider' => $providerUser,
                                      ]);
            } else {

                if (!auth()->check() || auth()->user()->id !== $user->id) {
                    $verified = false;
                }
            }

            $socialLogin = LoginProvider::create([
                                                     'provider_id'   => $providerUser->getId(),
                                                     'provider_type' => $provider,
                                                     'verified'      => $verified,
                                                     'nickname'      => $providerUser->nickname,
                                                     'name'          => $providerUser->getName(),
                                                     'email'         => $providerUser->getEmail() == '' ? '' : $providerUser->getEmail(),
                                                     'avatar'        => $providerUser->avatar,
                                                     'meta'          => json_encode($providerUser),
                                                     'user_id'       => $user->id,
                                                 ]);

        }
        return $socialLogin->user;
    }

    public function redirectOnSuccess()
    {
//        flash('Welcome!!! You are now logged in...')->success();

        return redirect()->intended($this->redirectPath());
    }


}
