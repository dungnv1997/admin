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
        Schema::create('stock_transfer_items', function (Blueprint $table) {
            $table->id();

            // ✅ ĐÃ CHUẨN: Phiếu chuyển kho tổng bị xóa thì các dòng chi tiết của nó phải tự động biến mất theo
            $table->foreignId('stock_transfer_id')
                ->constrained()
                ->cascadeOnDelete();

            // 🛡️ SỬA THÀNH restrictOnDelete: Ngăn xóa biến thể sản phẩm nếu nó đã nằm trong phiếu chuyển kho nào
            $table->foreignId('product_variant_id')
                ->constrained()
                ->restrictOnDelete();

            // ✅ ĐÃ CHUẨN: Số lượng hàng chuyển kho dùng kiểu decimal chính xác cao (Rất chuyên nghiệp)
            $table->decimal('quantity', 18, 2);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_transfer_items');
    }
};
