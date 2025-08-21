<?php
use Illuminate\Database\Migrations\Migration; use Illuminate\Database\Schema\Blueprint; use Illuminate\Support\Facades\Schema;
return new class extends Migration {
  public function up(){ Schema::create('attendance_logs',function(Blueprint $t){ $t->uuid('id')->primary(); $t->string('device_sn')->nullable(); $t->text('raw_line')->nullable(); $t->timestamps(); }); }
  public function down(){ Schema::dropIfExists('attendance_logs'); }
};
