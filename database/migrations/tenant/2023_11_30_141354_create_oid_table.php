<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('oids', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('hardware_version_id'); 
            $table->foreign('hardware_version_id')->references('id')->on('hardware_versions'); 
            $table->string('oid');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('oids');
    }
};
