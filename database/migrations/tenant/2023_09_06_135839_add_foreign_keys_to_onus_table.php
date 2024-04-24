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
            $table->foreign(['olt_id'], 'onus_ibfk_1')->references(['id'])->on('olts');
            $table->foreign(['zone_id'], 'onus_ibfk_5')->references(['id'])->on('zones');
            $table->foreign(['pon_type_id'], 'onus_ibfk_7')->references(['id'])->on('pon_types');
            $table->foreign(['onu_type_id'], 'onus_ibfk_4')->references(['id'])->on('onu_types');
            $table->foreign(['wifi_port_id'], 'onus_ibfk_6')->references(['id'])->on('wifi_ports');

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
            $table->dropForeign('onus_ibfk_5');
            $table->dropForeign('onus_ibfk_7');
            $table->dropForeign('onus_ibfk_4');
            $table->dropForeign('onus_ibfk_6');
            $table->dropForeign('onus_ibfk_10');
            $table->dropForeign('onus_ibfk_8');
            $table->dropForeign('onus_ibfk_9');
        });
    }
};
