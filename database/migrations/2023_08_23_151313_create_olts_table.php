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
        Schema::create('olts', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->string('name')->comment('OLTS name');
            $table->bigInteger('olt_hardware_version_id')->index('olt_hardware_version_id');
            $table->bigInteger('olt_software_version_id')->index('olt_software_version_id')->comment('OLTS ip');
            $table->string('ip');
            $table->bigInteger('telnet_port');
            $table->string('telnet_username');
            $table->string('telnet_password');
            $table->string('snmp_read_only');
            $table->string('snmp_read_write');
            $table->bigInteger('snmp_udp_port');
            $table->integer('ipvt_module');
            $table->bigInteger('pon_type_id')->index('pon_type_id');
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
        Schema::dropIfExists('olts');
    }
};
