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
        Schema::create('olt_cards', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->bigInteger('slot');
            $table->string('type');
            $table->string('real_type');
            $table->bigInteger('ports')->nullable();
            $table->string('sw')->nullable();
            $table->string('status');
            $table->string('role')->nullable();
            $table->bigInteger('olt_id')->index('olt_id');
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
        Schema::dropIfExists('olt_cards');
    }
};
