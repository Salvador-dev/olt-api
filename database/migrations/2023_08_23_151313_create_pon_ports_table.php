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
            $table->bigInteger('pon_type_id');
            $table->string('board')->nullable();
            $table->string('online_onus_count')->nullable();
            $table->string('min_range')->nullable();
            $table->string('max_range')->nullable();
            $table->string('operational_status')->nullable();
            $table->integer('admin_status');
            $table->string('onus')->nullable()->comment('Columna por definir');
            $table->string('average_signal')->nullable();
            $table->string('description')->nullable();
            $table->string('tx_power')->nullable();
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
