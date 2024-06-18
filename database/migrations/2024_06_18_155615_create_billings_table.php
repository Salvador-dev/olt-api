<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('billings', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->bigInteger('olt_id');
            $table->string('price_currency')->default('USD');
            $table->float('monthly_price');
            $table->integer('subscription_status_id')->default(0);
            $table->timestamp('subscription_end_date')->nullable();
            $table->timestamps();

            $table->foreign('olt_id', 'billings_ibfk_1')->references('id')->on('olts')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('subscription_status_id', 'billings_ibfk_2')->references('status_id')->on('subscription_status')->onUpdate('CASCADE')->onDelete('CASCADE');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('billings');
    }
};
