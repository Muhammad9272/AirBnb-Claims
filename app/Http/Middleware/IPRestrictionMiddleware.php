<?php

namespace App\Http\Middleware;

use Closure;

class IPRestrictionMiddleware
{
    public function handle($request, Closure $next)
    {   
        // dd($request->getHost());
        // Check if the request domain is .net
        // if ($request->getHost() === '.net') {
            // $allowedIPs = ['192.168.1.1', '192.168.1.2', '192.168.1.3', '192.168.1.4', '192.168.1.5'];
            // $requestIP = $request->ip();

            // if (in_array($requestIP, $allowedIPs)) {
            //     return $next($request);
            // }

            // abort(403, 'Unauthorized IP address for .net domain.');
        // }

        // return $next($request);
    }
}
