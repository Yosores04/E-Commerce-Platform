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
        Schema::create('refunds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('payment_id')->nullable()->constrained()->onDelete('set null');
            $table->string('refund_number')->unique();
            $table->decimal('amount', 10, 2);
            $table->enum('reason', ['requested_by_customer', 'defective', 'wrong_item', 'other'])->default('requested_by_customer');
            $table->text('reason_details')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected', 'processed'])->default('pending');
            $table->string('transaction_id')->nullable(); // Gateway refund transaction ID
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();
            
            $table->index('order_id');
            $table->index('refund_number');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('refunds');
    }
};
