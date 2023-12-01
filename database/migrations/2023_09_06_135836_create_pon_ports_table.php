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
        Schema::create('pon_ports', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->bigInteger('pon_type_id')->default(1); 
            $table->integer('admin_status')->comment('1: UP, 2: Down');
            $table->string('onus')->nullable();
            $table->string('average_signal')->nullable();
            $table->string('description')->nullable();
            $table->string('range')->nullable();
            $table->string('tx_power')->nullable();
            $table->string('board')->nullable();
            $table->string('min_range')->nullable();
            $table->string('max_range')->nullable();
            $table->string('operational_status')->nullable();
            $table->bigInteger('olt_id')->index('olt_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pon_ports');
    }
};
