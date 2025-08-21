<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\AttendanceLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ZKTecoController extends Controller
{
  public function cdata(Request $request)
  {

    Log::info('[CDAT ANY] hit', [
      'method' => $request->method(),
      'q'      => $request->query(),
      'len'    => strlen((string)$request->getContent()),
      'ip'     => $request->ip(),
      'ua'     => $request->userAgent(),
    ]);

    if ($request->isMethod('POST')) {
      Log::info('[CDAT POST BODY]', ['first2k' => mb_substr((string)$request->getContent(), 0, 2000)]);
    }
    // 1) Handshake “options=all”
    if ($request->isMethod('GET') && $request->query('options')) {
      $lines = [
        'GET OPTION FROM :',
        'ATTLOGStamp=0',      // kirim semua attendance dari awal
        'OPERLOGStamp=0',     // log operasi/access events
        'ATTPHOTOStamp=0',    // abaikan jika tidak support
        'ErrorDelay=10',
        'Delay=5',            // percepat sedikit supaya cepat terlihat
        'TransTimes=00:00;23:59',
        'TransInterval=1',
        'Realtime=1',
        // Tambahan yang sering membantu ACC:
        'TimeZone=7',         // contoh: GMT+7 (sesuaikan zona kamu)
        'ServerVer=Laravel-Webhook-1.0',
      ];
      Log::info('[CDAT OPT] reply options', ['sn' => $request->query('SN')]);
      return response(implode("\r\n", $lines) . "\r\n", 200)
        ->header('Content-Type', 'text/plain');
    }

    // 2) Terima log (POST)
    $sn  = $request->query('SN') ?? 'UNKNOWN';
    $raw = (string) $request->getContent();
    Log::info('[CDAT POST] len=' . strlen($raw), ['sn' => $sn]);

    $lines = preg_split("/\r\n|\n|\r/", $raw);
    $saved = 0;
    foreach ($lines as $line) {
      $line = trim($line);
      if ($line === '') continue;
      DB::table('attendance_logs')->insert([
        'id'         => (string) \Illuminate\Support\Str::uuid(),
        'device_sn'  => $sn,
        'raw_line'   => $line,
        'created_at' => now(),
        'updated_at' => now(),
      ]);
      $saved++;
      Log::info('[ATTLOG] saved', ['sn' => $sn, 'line' => $line]);
    }
    return response("OK\r\n", 200)->header('Content-Type', 'text/plain');
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
