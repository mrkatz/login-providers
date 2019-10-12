<?php


namespace Mrkatz\LoginProviders\Traits;

use Illuminate\Config\Repository;

trait Configable
{
    protected $CONFIG_PATH = __DIR__ . '/../../config/loginproviders.php';

    /**
     * @param string $property
     * @param string $append
     * @return Repository|mixed
     */
    protected function getConfigValue($property, $append = '')
    {
        return config($this->getConfigName() . '.' . $property) . $append;
    }

    /**
     * @return string
     */
    protected function getConfigName()
    {
        return 'mrkatz.login-providers';
    }
}