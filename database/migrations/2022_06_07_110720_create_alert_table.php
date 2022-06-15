<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Alert', function (Blueprint $table) {
            $table->id();
            $table->string('DeviceID');
            $table->string('device_name');
            $table->string('message')->nullable();
            $table->string('status');
            $table->float('value');
            $table->float('outThreshold');
            $table->datetime('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('alert');
    }
};
