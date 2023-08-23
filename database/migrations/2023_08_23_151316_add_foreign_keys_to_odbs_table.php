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
        Schema::table('odbs', function (Blueprint $table) {
            $table->foreign(['zone_id'], 'odbs_ibfk_1')->references(['id'])->on('zones')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('odbs', function (Blueprint $table) {
            $table->dropForeign('odbs_ibfk_1');
        });
    }
};
