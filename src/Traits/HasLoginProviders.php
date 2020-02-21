<?php

namespace Mrkatz\LoginProviders\Traits;

use Mrkatz\LoginProviders\Model\LoginProvider;

trait HasLoginProviders
{
    public function loginProviders($provider = null)
    {
        $logins = $this->hasMany(LoginProvider::class)->get();

        if ($provider === null) {
            return $logins;
        }

        return $logins->where('provider_type', '=', $provider)->first();
    }

}
