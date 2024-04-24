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
        Schema::create('advanced_settings_olts', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->boolean('admin_onu_unconfigured')->nullable();
            $table->boolean('dhcp_option82')->nullable();
            $table->string('option82_field')->nullable();
            $table->boolean('pppoe')->nullable();
            $table->boolean('onu_ip_source')->nullable();
            $table->boolean('mac_learn')->nullable();
            $table->string('vport_onu')->nullable();
            $table->boolean('mac_allow_list')->nullable();
            $table->boolean('mac_drop_list')->nullable();
            $table->boolean('use_acl')->nullable();
            $table->string('ports_acl')->nullable();
            $table->string('onu_signal_warning')->nullable();
            $table->string('onu_signal_critical')->nullable();
            $table->boolean('separate_voip_mgmt')->nullable();
            $table->string('temperature_warning')->nullable();
            $table->string('temperature_critical')->nullable();
            $table->boolean('use_cvlan')->nullable();
            $table->boolean('use_svlan')->nullable();
            $table->string('tag_transform_mode')->nullable();
            $table->boolean('use_tls_vlan')->nullable();
            $table->bigInteger('olt_id');
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
        Schema::dropIfExists('advanced_settings_olts');
    }
};
