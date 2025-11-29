<?php

namespace App\Http\Controllers\Concerns;

use App\Helpers\MikroTikHelper;
use App\Models\RouterosAPI;

trait UsesMikroTikCredentials
{
    /**
     * Get MikroTik credentials and return API instance if connected.
     *
     * @return RouterosAPI|null
     */
    protected function getMikroTikAPI(): ?RouterosAPI
    {
        $credentials = MikroTikHelper::getCredentials();

        if (!$credentials) {
            return null;
        }

        $API = new RouterosAPI();
        $API->debug = false;
        $API->timeout = 5;
        $API->attempts = 2;

        if ($API->connect($credentials['ip'], $credentials['user'], $credentials['password'])) {
            return $API;
        }

        return null;
    }

    /**
     * Get MikroTik credentials.
     *
     * @return array|null
     */
    protected function getMikroTikCredentials(): ?array
    {
        return MikroTikHelper::getCredentials();
    }
}

