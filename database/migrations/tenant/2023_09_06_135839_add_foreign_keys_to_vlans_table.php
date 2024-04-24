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
        Schema::table('vlans', function (Blueprint $table) {
            $table->foreign(['olt_id'], 'vlans_ibfk_1')->references(['id'])->on('olts');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vlans', function (Blueprint $table) {
            $table->dropForeign('vlans_ibfk_1');
        });
    }
};
