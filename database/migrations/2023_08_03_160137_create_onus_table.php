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
            $table->bigIncrements('id');
            $table->integer('autoincrement')->nullable();
            $table->string('onu_external')->nullable();
            $table->string('pon_type')->nullable();
            $table->string('sn')->nullable();
            $table->integer('onu_type')->nullable();
            $table->string('name')->nullable();
            $table->integer('olt_id')->nullable();
            $table->integer('board')->nullable();
            $table->integer('port')->nullable();
            $table->integer('allocated_onu')->nullable();
            $table->integer('zone_id')->nullable();
            $table->string('address')->nullable();
            $table->string('lat')->nullable();
            $table->string('lng')->nullable();
            $table->integer('odb_id')->nullable();
            $table->string('mode')->nullable();
            $table->string('wam_mode')->nullable();
            $table->string('ip_address')->nullable();
            $table->string('subnet_mask')->nullable();
            $table->string('default_gateway')->nullable();
            $table->string('dns1')->nullable();
            $table->string('dns2')->nullable();
            $table->string('username')->nullable();
            $table->string('password')->nullable();
            $table->string('catv')->nullable();
            $table->string('administrative_status')->nullable();
            $table->string('auth_date')->nullable();
            $table->string('status')->nullable();
            $table->integer('signal')->nullable();
            $table->integer('signal_1310')->nullable();
            $table->integer('signal_1490')->nullable();
            $table->integer('distance')->nullable();
            $table->string('service_port')->nullable();
            $table->string('service_port_vlan')->nullable();
            $table->string('service_port_cvlan')->nullable();
            $table->string('service_port_svlan')->nullable();
            $table->string('service_port_tag_transform_mode')->nullable();
            $table->integer('speed_up_id')->nullable();
            $table->integer('speed_download_id')->nullable();
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
