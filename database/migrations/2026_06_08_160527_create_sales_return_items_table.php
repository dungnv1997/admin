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
        Schema::create('sales_return_items', function (Blueprint $table) {
            $table->id();

            // ✅ ĐÃ CHUẨN: Phiếu trả hàng tổng bị xóa thì các dòng chi tiết của nó phải tự động biến mất theo
            $table->foreignId('sales_return_id')
                ->constrained()
                ->cascadeOnDelete();

            // 🛡️ SỬA THÀNH restrictOnDelete: Ngăn xóa biến thể sản phẩm nếu nó đã nằm trong phiếu trả hàng nào
            $table->foreignId('product_variant_id')
                ->constrained()
                ->restrictOnDelete();

            // ✅ ĐÃ CHUẨN: Bộ các cột số lượng và số tiền dùng kiểu decimal chính xác cao (Rất chuyên nghiệp)
            $table->decimal('quantity', 18, 2);     // Số lượng khách trả lại
            $table->decimal('price', 18, 2);        // Giá hoàn lại của 1 sản phẩm
            $table->decimal('total_amount', 18, 2); // Tổng tiền món này hoàn lại (quantity * price)

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_return_items');
    }
};
