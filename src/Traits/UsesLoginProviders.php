<?php

namespace Mrkatz\LoginProviders\Traits;

use App\User;
use Auth;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Redirect;
use Laravel\Socialite\AbstractUser;
use Laravel\Socialite\Facades\Socialite;
use Mrkatz\LoginProviders\Model\LoginProvider;

trait UsesLoginProviders
{
    protected $socialRoutes = ['redirectToProvider', 'handleProviderCallback'];

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        if (!config('mrkatz.login-providers.providers.email')) {
            return User::create([
                'name'     => $data['name'],
                'email'    => $data['email'],
                'password' => bcrypt($data['password']),
            ]);
        }

        $user = $this->createUser($data);

        LoginProvider::create([
            'provider_type' => 'email',
            'provider_id'   => bcrypt($data['password']),
            'name'          => $data['name'],
            'email'         => $data['email'],
            'user_id'       => $user->id,
        ]);

        return $user;
    }

    protected function createUser($data)
    {
        if ($data instanceof AbstractUser) {
            return User::create([
                'name'  => $data->getName(),
                'email' => $data->getEmail() == '' ? '' : $data->getEmail(),
            ]);
        };

        return User::create([
            'name'  => $data['name'],
            'email' => $data['email'],
        ]);

    }

    /**
     * Redirect to social provider.
     *
     * @param $provider
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function redirectToProvider($provider)
    {
        if (!in_array($provider, config('mrkatz.login-providers.providers.social'))) {
            return redirect('login');
        }

        return Socialite::with($provider)->redirect();
    }

    /**
     * Obtain the user information from social network.
     *
     * @param $provider
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handleProviderCallback($provider)
    {
        try {
            $socialUser = Socialite::driver($provider)->user();
        } catch (\Exception $e) {
            return Redirect::to('login');
        }

        $socialLogin = LoginProvider::where('provider_id', '=', $socialUser->id)
            ->where('provider_type', '=', $provider)
            ->first();

        if ($socialLogin == null) {
            $verified = true;

            $user = User::where('email', '=', $socialUser->getEmail())->first();

            if ($user === null) {
                $user = $this->createUser($socialUser);
            } else {

                if (!auth()->check() || auth()->user()->id !== $user->id) {
                    $verified = false;
                }
            };

            $socialLogin = LoginProvider::create([
                'provider_id'   => $socialUser->getId(),
                'provider_type' => $provider,
                'verified'      => $verified,
                'nickname'      => $socialUser->nickname,
                'name'          => $socialUser->getName(),
                'email'         => $socialUser->getEmail() == '' ? '' : $socialUser->getEmail(),
                'avatar'        => $socialUser->avatar,
                'meta'          => json_encode($socialUser),
                'user_id'       => $user->id,
            ]);

        } else {
            $user = $socialLogin->user;
        }

        if ($socialLogin->verified) {
            Auth::login($user);
        } else {
//            flash('Please check your email to confirm account')->important();

            return redirect('/');
        }

//        flash('Welcome!!! You are now logged in...')->success();

        return redirect()->intended($this->redirectPath());
    }


}