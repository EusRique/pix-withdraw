<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('account_withdraw', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('account_id');

            $table->string('method');
            $table->decimal('amount', 12, 2);

            $table->string('status')->index();

            $table->boolean('scheduled')->default(false);
            $table->dateTime('scheduled_for')->nullable();

            $table->string('error_reason')->nullable();
            $table->dateTime('processed_at')->nullable();

            $table->timestamps();

            $table->index('account_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('account_withdraw');
    }
};
