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
        Schema::table('olt_cards', function (Blueprint $table) {
            $table->foreign(['olt_id'], 'olt_cards_ibfk_1')->references(['id'])->on('olts')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('olt_cards', function (Blueprint $table) {
            $table->dropForeign('olt_cards_ibfk_1');
        });
    }
};
