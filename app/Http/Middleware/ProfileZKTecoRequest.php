<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\ZKTecoRequestProfile;

class ProfileZKTecoRequest
{
  public function handle(Request $r, Closure $next)
  {
    $t0 = microtime(true);
    $resp = $next($r);
    $ms = (int)((microtime(true) - $t0) * 1000);
    try {
      ZKTecoRequestProfile::create([
        'device_sn' => $r->query('SN'),
        'ip' => $r->ip(),
        'path' => $r->path(),
        'method' => $r->method(),
        'status_code' => $resp->getStatusCode(),
        'response_ms' => $ms
      ]);
    } catch (\Throwable $e) {
    }
    return $resp;
  }
}
