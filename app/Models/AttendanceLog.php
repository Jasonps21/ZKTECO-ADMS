<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceLog extends Model
{
  protected $table = 'attendance_logs';
  public $incrementing = false;
  protected $keyType = 'string';
  protected $fillable = ['id', 'device_sn', 'raw_line'];
}
