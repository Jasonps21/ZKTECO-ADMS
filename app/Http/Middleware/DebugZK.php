<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class DebugZK
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $r, Closure $next)
    {
        $raw = $r->getContent();
        Log::info('[ZK DEBUG] incoming', [
            'ip'     => $r->ip(),
            'method' => $r->method(),
            'path'   => $r->path(),
            'query'  => $r->query(),
            'len'    => strlen((string)$raw),
            'ct'     => $r->header('Content-Type'),
            'first1k' => mb_substr($raw ?? '', 0, 1000),
            'headers' => $r->headers->all(),
        ]);
        return $next($r);
    }
}
