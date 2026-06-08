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
        Schema::create('sales_orders', function (Blueprint $table) {

            $table->id();

            $table->foreignId('tenant_id')
                ->constrained()
                ->restrictOnDelete();

            $table->foreignId('branch_id')
                ->constrained()
                ->restrictOnDelete();

            $table->foreignId('customer_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->string('code')->unique();

            $table->decimal('subtotal', 18, 2);

            $table->decimal('discount_amount', 18, 2)->default(0);

            $table->decimal('tax_amount', 18, 2)->default(0);

            $table->decimal('total_amount', 18, 2);

            $table->decimal('paid_amount', 18, 2)->default(0);

            $table->decimal('debt_amount', 18, 2)->default(0);

            $table->string('status');

            $table->foreignId('created_by')
                ->constrained('users')
                ->restrictOnDelete();

            $table->softDeletes();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_orders');
    }
};
