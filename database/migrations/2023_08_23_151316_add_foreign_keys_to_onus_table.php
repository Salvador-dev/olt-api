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
        Schema::table('onus', function (Blueprint $table) {
            $table->foreign(['olt_id'], 'onus_ibfk_1')->references(['id'])->on('olts')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['ethernet_port_id'], 'onus_ibfk_2')->references(['id'])->on('ethernet_ports')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['services_port_id'], 'onus_ibfk_3')->references(['id'])->on('service_ports')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['onu_type_id'], 'onus_ibfk_4')->references(['id'])->on('onu_types')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['zone_id'], 'onus_ibfk_5')->references(['id'])->on('zones')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['wifi_port_id'], 'onus_ibfk_6')->references(['id'])->on('wifi_ports')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('onus', function (Blueprint $table) {
            $table->dropForeign('onus_ibfk_1');
            $table->dropForeign('onus_ibfk_2');
            $table->dropForeign('onus_ibfk_3');
            $table->dropForeign('onus_ibfk_4');
            $table->dropForeign('onus_ibfk_5');
            $table->dropForeign('onus_ibfk_6');
        });
    }
};
