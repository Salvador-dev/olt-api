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
        Schema::create('speed_profiles', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->string('name');
            $table->boolean('use_prefix')->nullable();
            $table->string('preview_huawei')->nullable();
            $table->string('preview_zte')->nullable();
            $table->string('type_conexion')->nullable()->comment('pendiente por definir');
            $table->bigInteger('upload_speed');
            $table->bigInteger('download_speed');
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
        Schema::dropIfExists('speed_profiles');
    }
};
