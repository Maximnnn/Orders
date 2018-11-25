<?php

namespace App\Http\Middleware;

use App\Services\IpLocation;
use Illuminate\Routing\Middleware\ThrottleRequests;

class CountryLimiter extends ThrottleRequests
{
    /**
     * Resolve the number of attempts if the user is authenticated or not.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int|string  $maxAttempts
     * @return int
     */
    protected function resolveMaxAttempts($request, $maxAttempts)
    {
        return (int) $maxAttempts;
    }

    /**
     * Resolve request signature.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     *
     * @throws \RuntimeException
     */
    protected function resolveRequestSignature($request)
    {
        $ipLocation = IpLocation::getInstanceFromIp($request->ip());

        $country = $ipLocation->get(IpLocation::COUNTRY);

        return sha1($country);
    }

}
