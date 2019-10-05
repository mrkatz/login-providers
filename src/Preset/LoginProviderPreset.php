<?php

namespace Mrkatz\LoginProviders\Preset;

use Illuminate\Foundation\Console\Presets\Preset;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Container\Container;


class LoginProviderPreset extends Preset
{
    protected static $stubs = [
        'app/Http/Controllers/Auth/ForgotPasswordController.php' => __DIR__ . '/stubs/ForgotPasswordController.stub',
        'app/Http/Controllers/Auth/LoginController.php'          => __DIR__ . '/stubs/LoginController.stub',
        'app/Http/Controllers/Auth/RegisterController.php'       => __DIR__ . '/stubs/RegisterController.stub',
        'app/Http/Controllers/Auth/ResetPasswordController.php'  => __DIR__ . '/stubs/ResetPasswordController.stub',
        'app/Http/Controllers/HomeController.php'                => __DIR__ . '/stubs/HomeController.stub',
    ];

    /**
     * Install the preset.
     *
     * @return void
     */
    public static function install()
    {
        //Won't Work IN 6.0
        Artisan::call('make:auth', ['-q' => '']);

        if (!is_dir($directory = app_path('Http/Controllers/Auth'))) {
            mkdir($directory, 0755, true);
        }

        foreach (static::$stubs as $key => $stub) {
            file_put_contents(
                base_path($key),
                static::compileControllerStub($stub)
            );
        }
    }

    protected static function compileControllerStub($stub)
    {
        return str_replace(
            '{{namespace}}',
            Container::getInstance()->getNamespace(),
            file_get_contents($stub));
    }
}
