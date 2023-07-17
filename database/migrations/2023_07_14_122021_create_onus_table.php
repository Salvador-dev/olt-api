<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('onus', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('olt_id')->nullable();
            $table->unsignedBigInteger('onu_type_id')->nullable();
            $table->unsignedBigInteger('odb_id')->nullable();
            $table->unsignedBigInteger('zone_id')->nullable();
            $table->unsignedBigInteger('speed_profile_id')->nullable();

            $table->string('name')->nullable();
            $table->string('onu')->nullable();
            $table->integer('board')->nullable();
            $table->integer('port')->nullable();
            $table->string('sn')->nullable();

            $table->string('address')->nullable();
            $table->timestamp('authorization_date')->nullable();

            $table->string('onu_external_id')->nullable();
            $table->integer('status');
            $table->string('onu_olt_rx_signal')->nullable();
            $table->string('onu_mode')->nullable();
            $table->string('mgmt_ip')->nullable();
            $table->string('wan_setup_mode')->nullable();
            $table->string('ethernet_ports')->nullable();

            $table->foreign('olt_id')->references('idOlt')->on('olts');
            $table->foreign('onu_type_id')->references('idOnuType')->on('olts');
            $table->foreign('odb_id')->references('idOdb')->on('odbs');
            $table->foreign('zone_id')->references('idZone')->on('zones');
            $table->foreign('speed_profile_id')->references('idSpeedProfile')->on('speed_profiles');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('onus');
    }
};
