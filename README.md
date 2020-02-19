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
use Notifiable, HasLoginProviders;
```

Add Trait `UsesLoginProviders` in `Auth/RegisterController` Controller
```php
use UsesLoginProviders;
```

Replace Trait `ResetsPasswords` to `ResetsLoginProviders` in `Auth/ResetPasswordController` Controller
```php
use ResetsLoginProviders;
```

### Edit RegisterController

Rename create function to createUser and make sure to pass $data['name'], if changed

```
    protected function createUser(&$data)
    {
        $data['name'] = $data['first_name'] . ' ' . $data['last_name'];

        return User::create([
            'first_name' => $data['first_name'],
            'last_name'  => $data['last_name'],
            'email'      => $data['email'],
            'password'   => Hash::make($data['password']),
        ]);
    }
```

### Publish Configuration File

```
php artisan vendor:publish --provider="Mrkatz\LoginProviders\ServiceProvider" --tag="config"
```

###Add Routes For Social Login

```
Route::get('login/{provider}', 'Auth\RegisterController@redirectToProvider');
Route::get('login/{provider}/callback', 'Auth\RegisterController@handleProviderCallback');
```

## Usage

CHANGE ME

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
