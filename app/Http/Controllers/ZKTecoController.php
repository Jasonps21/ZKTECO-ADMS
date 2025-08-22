<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\AttendanceLog;
use Clockwork\Storage\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ZKTecoController extends Controller
{
  public function cdata(Request $request)
  {
    Log::info('[CDATA ANY] hit', [
      'method' => $request->method(),
      'q'      => $request->query(),
      'len'    => strlen((string)$request->getContent()),
      'ip'     => $request->ip(),
      'ua'     => $request->userAgent(),
    ]);

    // 1) Handshake “options=all”
    if ($request->isMethod('GET') && strtolower((string)$request->query('options')) === 'all') {
      $sn = $request->query('SN');

      $lines = [
        "GET OPTION FROM: {$sn}",
        "Stamp=0",
        "ATTLOGStamp=0",
        "OpStamp=0",
        "OPERLOGStamp=0",
        "ATTPHOTOStamp=0",
        "ErrorDelay=10",
        "Delay=5",
        "TransTimes=00:00;12:00",
        "TransInterval=1",
        "Realtime=1",
        "Encrypt=0",
      ];
      $reply = implode("\r\n", $lines) . "\r\n";

      // Log hexdump (opsional, untuk cek 0d0a/CRLF)
      Log::info('[CDATA OPT REPLY HEX]', ['hex' => bin2hex($reply)]);

      $response = response($reply, 200);
      $response->headers->set('Content-Type', 'text/plain', true); // override total
      $response->headers->remove('X-Powered-By'); // opsional
      return $response
        ->header('Content-Length', (string) strlen($reply))
        ->header('Connection', 'close');          // <-- penting di beberapa firmware
    }

    if ($request->isMethod('GET') && !$request->has('options')) {
      $sn = $request->query('SN');

      $lines = [
        "GET OPTION FROM: {$sn}",
        "Stamp=0",
        "ATTLOGStamp=0",
        "OpStamp=0",
        "OPERLOGStamp=0",
        "ATTPHOTOStamp=0",
        "ErrorDelay=10",
        "Delay=5",
        "TransTimes=00:00;12:00",
        "TransInterval=1",
        "Realtime=1",
        "Encrypt=0",
      ];
      $reply = implode("\r\n", $lines) . "\r\n";

      // Log hexdump (opsional, untuk cek 0d0a/CRLF)
      Log::info('[CDATA OPT REPLY HEX]', ['hex' => bin2hex($reply)]);

      $response = response($reply, 200);
      $response->headers->set('Content-Type', 'text/plain', true); // override total
      $response->headers->remove('X-Powered-By'); // opsional
      return $response
        ->header('Content-Length', (string) strlen($reply))
        ->header('Connection', 'close');         // <-- penting di beberapa firmware
    }

    // 2) Terima log (POST)
    if ($request->isMethod('POST')) {
      $sn  = $request->query('SN') ?? 'UNKNOWN';
      $raw = (string)$request->getContent();

      Log::info('[CDATA POST] len=' . strlen($raw), ['sn' => $sn]);
      Log::info('[CDATA POST BODY first2k]', ['first2k' => mb_substr($raw, 0, 2000)]);

      // Parse per baris
      $lines = preg_split("/\r\n|\n|\r/", $raw);
      $saved = 0;
      foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '') continue;

        DB::table('attendance_logs')->insert([
          'id'         => (string)\Illuminate\Support\Str::uuid(),
          'device_sn'  => $sn,
          'raw_line'   => $line,
          'created_at' => now(),
          'updated_at' => now(),
        ]);
        $saved++;
        Log::info('[ATTLOG] saved', ['sn' => $sn, 'line' => $line]);
      }

      // Balasan standar
      $ok = "OK\r\n";
      $response = response($ok, 200);
      $response->headers->set('Content-Type', 'text/plain', true); // override total
      $response->headers->remove('X-Powered-By'); // opsional
      return $response
        ->header('Content-Length', (string) strlen($ok))
        ->header('Connection', 'close');
    }

    // Default fallback
    return response("OK\r\n", 200)->header('Content-Type', 'text/plain')->header('Connection', 'close');
  }

  public function getrequest(Request $r)
  {
    Log::info('[GETREQUEST] hit', ['sn' => $r->query('SN'), 'q' => $r->query()]);
    return response("", 200)->header('Content-Type', 'text/plain');
  }

  public function devicecmd(Request $r)
  {
    Log::info('[DEVICECMD] result', [
      'SN'     => $r->query('SN') ?? $r->input('SN'),
      'ID'     => $r->query('ID') ?? $r->input('ID'),
      'Return' => $r->query('Return') ?? $r->input('Return'),
      'CMD'    => $r->query('CMD') ?? $r->input('CMD'),
      'body'   => $r->getContent(),
    ]);
    return response("OK\r\n", 200)->header('Content-Type', 'text/plain');
  }

  public function registry(Request $r)
  {
    Log::info('[REGISTRY] hit', ['sn' => $r->query('SN'), 'q' => $r->query()]);
    return response("OK\r\n", 200)->header('Content-Type', 'text/plain');
  }
}
