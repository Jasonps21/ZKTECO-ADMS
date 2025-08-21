<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VerifyZKTecoSignature
{
  public function handle(Request $r, Closure $next)
  {
    $key = config('zkteco.comm_key');
    if ($key) {
      $given = $r->query('commkey') ?? $r->input('commkey');
      if ($given !== $key) return response('FORBIDDEN', 403);
    }
    return $next($r);
  }
}
