# LoginProviders

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]
[![Build Status][ico-travis]][link-travis]

This is a login Provider Package

## Installation

Via Composer

``` bash
$ composer require mrkatz/login-providers
```

## Usage

### Add Traits

Add Trait `HasLoginProviders` to `User` modal
```php
use Mrkatz\LoginProviders\Traits\HasLoginProviders;

class User extends Authenticatable
{
use Notifiable, HasLoginProviders;
}
```

If using Jetstream then skip this step....

Add Trait `UsesLoginProviders` in `Auth/RegisterController` Controller
```php
use Mrkatz\LoginProviders\Traits\UsesLoginProviders;

class RegisterController extends Controller
{
use RegistersUsers, UsesLoginProviders;
}
```

### Publish Configuration File

```
php artisan vendor:publish --provider="Mrkatz\LoginProviders\LoginProvidersServiceProvider" --tag="config"
```

###Add Routes For Social Login

Jetstream Routes
```
Route::get('login/{provider}', 'Mrkatz\LoginProviders\Controllers\SocialController@redirectToProvider');
Route::get('login/{provider}/callback', 'Mrkatz\LoginProviders\Controllers\SocialController@handleProviderCallback');
```
Normal Laravel Routes
```
Route::get('login/{provider}', 'Auth\RegisterController@redirectToProvider');
Route::get('login/{provider}/callback', 'Auth\RegisterController@handleProviderCallback');
```

###Set client id and client secret config/service.php file :

```
'facebook' => [
    'client_id' => env('FACEBOOK_CLIENT_ID'),
    'client_secret' => env('FACEBOOK_CLIENT_SECRET'),
    'redirect' => 'http://your-callback-url/login/{provider}/callback',
],
```

Add Social Login buttons to Login/Register Pages
```
@include('login-providers::social-providers')
```


##Create User from Passed properties
###Properties
````
'name' => $providerUser->getName(),
'email' => $email,
'password' => RandomPassword,
'password_confirmation' => RandomPassword,
'nickname' => $providerUser->nickname,
'avatar' => $providerUser->avatar,
'provider' => $providerUser,
'terms' => 'on',
'social' => true
````

####Jetstream
Add to create method in \App\Actions\Fortify\CreateNewUser
```
    public function create(array $input)
    {
        if (!array_key_exists('social')) {
            return $this->createSocialUser($input);
        }
        
        ...
```
Create User with Social Available properties
```
/**
     * Create a newly registered Social user.
     *
     * @param array $input
     * @return \App\Models\User
     * @throws \Throwable
     */
    protected function createSocialUser(array $input)
    {
        return DB::transaction(function () use ($input) {
            return tap(User::create([
                'name' => $input['name'],
                'email' => $input['email'],
                'password' => Hash::make($input['password']),
            ]), function (User $user) {
                $this->createTeam($user);
            });
        });
    }
```

###Normal Laravel

Add to create method in RegisterController

````
    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        if (!array_key_exists('social')) {
            return $this->createSocialUser($data);
        }
        
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($input['password']),
        ]);
    }
````

Create User with Social Available properties
```
/**
     * Create a newly registered Social user.
     *
     * @param array $input
     * @return \App\Models\User
     * @throws \Throwable
     */
    protected function createSocialUser(array $data)
    {
       return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
    }
```

## Usage

Add provider to config [login-providers]->providers[social]=[facebook, google, etc...]

## Security

If you discover any security related issues, please email adamkaczocha@gmail.com
instead of using the issue tracker.

## Credits

- [Adam Kaczocha][link-author]
- [All Contributors][link-contributors]

## License

MIT. Please see the [license file](license.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/mrkatz/login-providers.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/mrkatz/login-providers.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/mrkatz/login-providers/master.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/mrkatz/login-providers
[link-downloads]: https://packagist.org/packages/mrkatz/login-providers
[link-travis]: https://travis-ci.org/mrkatz/login-providers
[link-styleci]: https://styleci.io/repos/12345678
[link-author]: https://github.com/mrkatz
[link-contributors]: ../../contributors
