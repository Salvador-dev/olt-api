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
            $table->bigInteger('olt_hardware_version_id')->nullable()->index('olt_hardware_version_id');
            $table->bigInteger('olt_software_version_id')->nullable()->index('olt_software_version_id')->comment('OLTS ip');
            $table->string('ip')->nullable();
            $table->bigInteger('telnet_port')->nullable();
            $table->string('telnet_username')->nullable();
            $table->string('telnet_password')->nullable();
            $table->string('snmp_read_only')->nullable();
            $table->string('snmp_read_write')->nullable();
            $table->bigInteger('snmp_udp_port')->nullable();
            $table->integer('ipvt_module')->nullable();
            $table->string('smart_olt_id')->nullable();
            $table->bigInteger('pon_type_id')->nullable()->index('pon_type_id');
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
