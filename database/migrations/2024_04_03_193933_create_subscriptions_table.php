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
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->string('tenant_id');
            $table->unsignedBigInteger('plan_id');
            $table->string('description');
            $table->enum('status',['suspended','declined','completed','renovated','pending','debtor'])->default('pending');
            $table->date('expiration_date');
            $table->foreign('tenant_id')->references('id')->on('tenants');
            $table->foreign('plan_id')->references('id')->on('plans');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
