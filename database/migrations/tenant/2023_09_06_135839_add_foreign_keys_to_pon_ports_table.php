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
        Schema::table('pon_ports', function (Blueprint $table) {
            $table->foreign(['olt_id'], 'pon_ports_ibfk_1')->references(['id'])->on('olts');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pon_ports', function (Blueprint $table) {
            $table->dropForeign('pon_ports_ibfk_1');
        });
    }
};
