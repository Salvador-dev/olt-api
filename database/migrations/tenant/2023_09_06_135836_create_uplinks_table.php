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
        Schema::create('uplinks', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->bigInteger('olt_id')->index('olt_id');
            $table->string('name')->nullable();
            $table->string('description')->nullable();
            $table->string('type')->nullable();
            $table->integer('administrative_status_id')->nullable()->default(1);
            $table->string('status')->nullable();
            $table->string('negotiation')->nullable();
            $table->string('mtu')->nullable();
            $table->string('wavelength')->nullable();
            $table->string('temperature')->nullable();
            $table->string('pivd')->nullable();
            $table->string('vlan_tag')->nullable();
            $table->string('mode')->nullable();
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
        Schema::dropIfExists('uplinks');
    }
};
