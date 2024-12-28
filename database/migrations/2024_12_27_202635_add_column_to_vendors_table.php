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
        Schema::table('vendors', function (Blueprint $table) {
            $table->string('kkiapay_public_key')->nullable();
            $table->string('kkiapay_secret_key')->nullable();
            $table->string('kkiapay_private_key')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vendors', function (Blueprint $table) {
            $table->dropColumn('kkiapay_public_key');
            $table->dropColumn('kkiapay_secret_key');
            $table->dropColumn('kkiapay_private_key');
        });
    }
};
