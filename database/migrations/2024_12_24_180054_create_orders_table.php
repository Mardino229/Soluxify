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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string("reference")->unique();
            $table->decimal("total");
            $table->string("delivery_address");
            $table->string('kkiapay_id')->nullable();
            $table->enum('status', ['Pending', 'Validated', 'Refused', 'Cancelled'])->default('Pending');
            $table->enum('payment_status', ['Paid', 'Unpaid', 'Reverted'])->default('Unpaid');
            $table->timestamps();
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->foreignId('vendor_id')->constrained()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
