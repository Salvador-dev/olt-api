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
        Schema::create('billing_history', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->bigInteger('billing_id');
            $table->string('transaction_id');
            $table->bigInteger('user_id');
            $table->float('amount');
            $table->timestamps();

            $table->foreign('billing_id', 'billing_history_ibfk_1')->references('id')->on('billings')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('user_id', 'billing_history_ibfk_2')->references('id')->on('users')->onUpdate('CASCADE')->onDelete('CASCADE');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('billing_history');
    }
};
