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
        Schema::create('voip_profiles', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->bigInteger('olt_id')->index('olt_id');
            $table->bigInteger('pon_type_id')->index('pon_type_id');
            $table->string('profile_name')->nullable();
            $table->string('server')->nullable();
            $table->bigInteger('server_port')->nullable();
            $table->string('proxy_server')->nullable();
            $table->bigInteger('outbound_server_port')->nullable();
            $table->string('user_agent_domain')->nullable();
            $table->string('primary_dns')->nullable();
            $table->string('secondary_dns')->nullable();
            $table->string('udp_port')->nullable();
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
        Schema::dropIfExists('voip_profiles');
    }
};
