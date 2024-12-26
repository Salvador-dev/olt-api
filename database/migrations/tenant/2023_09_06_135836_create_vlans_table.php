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
        Schema::create('vlans', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->string('description')->nullable();
            $table->string('scope')->nullable();
            $table->boolean('multicast_vlan')->default(false);
            $table->boolean('management_voip')->default(false);
            $table->boolean('dhcp_snooping')->default(false);
            $table->boolean('lan_to_lan')->default(false);
            $table->string('pon_ports')->nullable();
            $table->bigInteger('olt_id')->index('olt_id');
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
        Schema::dropIfExists('vlans');
    }
};
