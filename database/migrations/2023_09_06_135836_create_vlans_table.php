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
            $table->string('description');
            $table->boolean('multicast_vlan');
            $table->boolean('management_voip');
            $table->boolean('dhcp_snooping');
            $table->boolean('lan_to_lan');
            $table->string('pon_ports')->nullable()->comment('Campo por definir');
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
