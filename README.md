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
php artisan vendor:publish --provider="Mrkatz\LoginProviders\ServiceProvider" --tag="config"
```

###Add Routes For Social Login

```
Route::get('login/{provider}', 'Auth\RegisterController@redirectToProvider');
Route::get('login/{provider}/callback', 'Auth\RegisterController@handleProviderCallback');
```

###Set client id and client secret config/service.php file :

```php
'facebook' => [
    'client_id' => env('FACEBOOK_CLIENT_ID'),
    'client_secret' => env('FACEBOOK_CLIENT_SECRET'),
    'redirect' => 'http://your-callback-url/login/{provider}/callback',
],
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
