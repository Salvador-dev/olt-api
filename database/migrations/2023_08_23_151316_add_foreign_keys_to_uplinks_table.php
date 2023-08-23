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
        Schema::table('uplinks', function (Blueprint $table) {
            $table->foreign(['olt_id'], 'uplinks_ibfk_1')->references(['id'])->on('olts')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('uplinks', function (Blueprint $table) {
            $table->dropForeign('uplinks_ibfk_1');
        });
    }
};
