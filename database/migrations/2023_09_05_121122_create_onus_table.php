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
        Schema::create('onus', function (Blueprint $table) {
            $table->bigInteger('id', true)->comment('ONU');
            $table->string('unique_external_id')->comment('Unique External ID SN');
            $table->string('sn')->comment('SERIAL');
            $table->bigInteger('olt_id')->index('olt_id')->comment('OLT ID');
            $table->string('board')->nullable();
            $table->string('port')->nullable();
            $table->bigInteger('onu_type_id')->index('onu_type_id');
            $table->bigInteger('zone_id')->index('zone_id');
            $table->string('name');
            $table->string('address');
            $table->string('odb_name')->nullable();
            $table->string('mode');
            $table->string('wan_mode');
            $table->string('ip_address')->nullable();
            $table->string('subnet_mask')->nullable();
            $table->string('default_gateway')->nullable();
            $table->string('dns1')->nullable();
            $table->string('dns2')->nullable();
            $table->string('username')->nullable();
            $table->string('password')->nullable();
            $table->string('catv')->nullable();
            $table->string('administrative_status')->nullable()->comment('Enabled | Disabled');
            $table->timestamp('authorization_date')->nullable();
            $table->string('status')->nullable()->comment('Online | Ofline');
            $table->string('signal')->nullable();
            $table->string('signal_1310')->nullable()->comment('-27.45');
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->bigInteger('pon_type_id')->nullable()->index('onus_ibfk_7_idx');
            $table->bigInteger('wifi_port_id')->nullable()->index('wifi_port_id')->comment('Wifi Ports');
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
        Schema::dropIfExists('onus');
    }
};
