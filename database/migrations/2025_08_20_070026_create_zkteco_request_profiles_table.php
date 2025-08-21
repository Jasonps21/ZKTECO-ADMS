<?php
use Illuminate\Database\Migrations\Migration; use Illuminate\Database\Schema\Blueprint; use Illuminate\Support\Facades\Schema;
return new class extends Migration {
  public function up(){ Schema::create('zkteco_request_profiles',function(Blueprint $t){ $t->uuid('id')->primary(); $t->string('device_sn')->nullable(); $t->string('ip')->nullable(); $t->string('path')->nullable(); $t->string('method')->nullable(); $t->integer('status_code')->nullable(); $t->integer('response_ms')->nullable(); $t->timestamps(); }); }
  public function down(){ Schema::dropIfExists('zkteco_request_profiles'); }
};
