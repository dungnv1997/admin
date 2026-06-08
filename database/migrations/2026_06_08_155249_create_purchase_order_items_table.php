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
        Schema::create('purchase_order_items', function (Blueprint $table) {
            $table->id();

            // ✅ ĐÃ CHUẨN: Đơn nhập tổng bị xóa thì chi tiết của nó cũng phải xóa theo để sạch database
            $table->foreignId('purchase_order_id')
                ->constrained()
                ->cascadeOnDelete();

            // 🛡️ SỬA THÀNH restrictOnDelete: Ngăn xóa biến thể sản phẩm nếu nó đã từng được nhập hàng
            $table->foreignId('product_variant_id')
                ->constrained()
                ->restrictOnDelete();

            // ✅ ĐÃ CHUẨN: Số lượng và giá vốn dùng decimal chính xác cao (cost_price thay vì price là rất đúng chuyên ngành)
            $table->decimal('quantity', 18, 2);
            $table->decimal('cost_price', 18, 2);

            $table->decimal('discount_amount', 18, 2)->default(0);
            $table->decimal('total_amount', 18, 2);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_order_items');
    }
};
