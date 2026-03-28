<?php

namespace App\Http\Concerns;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

trait RemoteApiHttp
{
    protected function remoteHttp(): PendingRequest
    {
        return Http::acceptJson()
            ->timeout((int) config('services.remote_api.timeout', 15))
            ->connectTimeout((int) config('services.remote_api.connect_timeout', 5));
    }
}
