<?php
namespace App\Models; use Illuminate\Database\Eloquent\Model; use Illuminate\Support\Str;
class ZKTecoRequestProfile extends Model {
  protected $table='zkteco_request_profiles'; public $incrementing=false; protected $keyType='string';
  protected $fillable=['id','device_sn','ip','path','method','status_code','response_ms'];
  protected static function booted(){ static::creating(function($m){ if(!$m->id)$m->id=(string)Str::uuid(); }); }
}
