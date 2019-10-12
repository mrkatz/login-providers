<?php


namespace Mrkatz\LoginProviders\Traits;

use Illuminate\Config\Repository;

trait Configable
{
    protected $CONFIG_PATH = __DIR__ . '/../../config/login-providers.php';

    /**
     * @param string $property
     * @param string $append
     * @return mixed|Repository|string
     */
    protected function getConfigValue($property, $append = '')
    {
        if ($append == '') {
            return config($this->getConfigNameSpace() . $this->getConfigName() . '.' . $property);
        }
        return config($this->getConfigNameSpace() . $this->getConfigName() . '.' . $property) . $append;
    }

    /**
     * @return string
     */
    protected function getConfigName()
    {
        return 'login-providers';
    }

    /**
     * @return string
     */
    protected function getConfigNameSpace()
    {
        return '';
    }
}