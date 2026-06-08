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
        Schema::create('purchase_return_items', function (Blueprint $table) {
            $table->id();

            // ✅ ĐÃ CHUẨN: Phiếu trả hàng tổng bị xóa thì các dòng chi tiết của nó phải tự động biến mất theo
            $table->foreignId('purchase_return_id')
                ->constrained()
                ->cascadeOnDelete();

            // 🛡️ SỬA THÀNH restrictOnDelete: Ngăn xóa biến thể sản phẩm nếu nó đã nằm trong phiếu trả hàng nào
            $table->foreignId('product_variant_id')
                ->constrained()
                ->restrictOnDelete();

            // ✅ ĐÃ CHUẨN: Bộ các cột số lượng và số tiền dùng kiểu decimal chính xác cao (Rất chuyên nghiệp)
            $table->decimal('quantity', 18, 2);     // Số lượng xuất trả cho nhà cung cấp
            $table->decimal('cost_price', 18, 2);   // Giá nhập/Giá vốn của 1 sản phẩm lúc trả
            $table->decimal('total_amount', 18, 2); // Tổng tiền món này (quantity * cost_price)

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_return_items');
    }
};
