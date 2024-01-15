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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->unsignedInteger('capacity')->default(50);
            $table->string('location');
            $table->point('location_coords')->nullable();
            $table->dateTime('held_date');
            $table->timestamps();
        });

        Schema::create('transactions', function (Blueprint $table) {
            $table->string('id')->primary();

            $table->unsignedDecimal('amount');
            $table->string('status')->default('pending');
            $table->string('payment_channel');
            $table->string('payment_link', 512);
            $table->timestamp('expire_at');

            $table->timestamps();
        });

        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique()->nullable();

            $table->string('transaction_id')->nullable();
            $table->foreign('transaction_id')
                ->references('id')->on('transactions')
                ->onDelete('set null')->onUpdate('cascade');

            $table->foreignId('event_id')->nullable()
                ->references('id')->on('events')
                ->onDelete('set null')->onUpdate('cascade');

            $table->foreignId('user_id')
                ->references('id')->on('users')
                ->onDelete('cascade')->onUpdate('cascade');

            $table->string('holder_name');
            $table->enum('holder_gender', ['male', 'female'])->default('male');
            $table->string('holder_email');
            $table->string('holder_phone')->nullable();
            $table->unsignedDecimal('purchase_amount');
            $table->string('notes')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
        Schema::dropIfExists('transactions');
        Schema::dropIfExists('tickets');
    }
};
