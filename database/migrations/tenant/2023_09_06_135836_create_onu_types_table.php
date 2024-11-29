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
        Schema::create('onu_types', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->string('name');
            $table->bigInteger('pon_type_id')->index('pon_type_id');
            $table->bigInteger('capability_id')->index('capability_id')->comment('Bridging/Routing');
            $table->bigInteger('ethernet_ports')->nullable();
            $table->bigInteger('wifi_ports')->nullable();
            $table->bigInteger('voip_ports')->nullable();
            $table->boolean('catv')->default(false);
            $table->boolean('allow_custom_profiles')->nullable();
            $table->string('smart_olt_id')->nullable();
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
        Schema::dropIfExists('onu_types');
    }
};
