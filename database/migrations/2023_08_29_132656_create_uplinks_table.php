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
            $table->string('uplink_port');
            $table->string('description')->nullable();
            $table->string('type')->nullable()->comment('Pendiente por definir');
            $table->boolean('admin_state')->nullable();
            $table->string('status')->nullable()->comment('Pendiente por definir');
            $table->string('negotiation')->nullable()->comment('Pendiente por definir');
            $table->string('mtu')->nullable()->comment('Pendiente por definir');
            $table->string('wavel')->nullable()->comment('Pendiente por definir');
            $table->string('temp')->nullable()->comment('Pendiente por definir');
            $table->string('pivd_untag')->nullable()->comment('Pendiente por definir');
            $table->string('mode_vlan')->nullable()->comment('Pendiente por definir');
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
