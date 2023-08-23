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
        Schema::table('olts', function (Blueprint $table) {
            $table->foreign(['pon_type_id'], 'olts_ibfk_1')->references(['id'])->on('pon_types')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['olt_hardware_version_id'], 'olts_ibfk_2')->references(['id'])->on('hardware_version')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['olt_software_version_id'], 'olts_ibfk_3')->references(['id'])->on('software_version')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('olts', function (Blueprint $table) {
            $table->dropForeign('olts_ibfk_1');
            $table->dropForeign('olts_ibfk_2');
            $table->dropForeign('olts_ibfk_3');
        });
    }
};
