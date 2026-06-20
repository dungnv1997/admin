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
        // Chi nhánh nào đang còn bao nhiêu hàng
        Schema::create('inventories', function (Blueprint $table) {

            $table->id();

            $table->foreignId('tenant_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('branch_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('product_variant_id')
                ->constrained()
                ->cascadeOnDelete();
            //Tồn kho thực tế
            $table->decimal('quantity', 18, 2)->default(0);
            //Đã giữ cho đơn hàng nhưng chưa xuất kho
            $table->decimal('reserved_quantity', 18, 2)
                ->default(0);
            //Có thể bán
            $table->decimal('available_quantity', 18, 2)
                ->default(0);

            $table->timestamps();

            $table->unique([
                'branch_id',
                'product_variant_id'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventories');
    }
};
