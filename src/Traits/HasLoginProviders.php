<?php

namespace Mrkatz\LoginProviders\Traits;

use Mrkatz\LoginProviders\Model\LoginProvider;

trait HasLoginProviders
{
    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->password();
    }

    public function loginProviders($provider = null)
    {
        $logins = $this->hasMany(LoginProvider::class)->get();
        if ($provider === null) {
            return $logins;
        }

        return $logins->where('provider_type', '=', $provider)->first();
    }

    public function password($value = null)
    {
        if (property_exists(self::class, 'password')) {
            if ($value !== null) {
                $this->password = $value;
            }

            return $this->password;
        }

        $emailProvider = $this->loginProviders('email');

        if ($emailProvider !== null) {
            if ($value !== null){
                $emailProvider->provider_id = $value;
                $emailProvider->save();
            }
            return $emailProvider->provider_id;
        }

        return null;
    }

}
