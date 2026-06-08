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
        Schema::create('stock_adjustment_items', function (Blueprint $table) {
            $table->id();

            // ✅ ĐÃ CHUẨN: Phiếu kiểm kho tổng bị xóa thì chi tiết của nó cũng phải tự động biến mất theo
            $table->foreignId('stock_adjustment_id')
                ->constrained()
                ->cascadeOnDelete();

            // 🛡️ SỬA THÀNH restrictOnDelete: Ngăn xóa biến thể sản phẩm nếu nó đã nằm trong phiếu kiểm kho nào
            $table->foreignId('product_variant_id')
                ->constrained()
                ->restrictOnDelete();

            // ✅ ĐÃ CHUẨN: Bộ 3 cột số lượng kiểm kho dùng kiểu decimal chính xác cao (Rất chuyên nghiệp)
            $table->decimal('system_qty', 18, 2);     // Số lượng đang có trên máy tính trước khi kiểm
            $table->decimal('actual_qty', 18, 2);     // Số lượng nhân viên thực tế đếm được tại quầy
            $table->decimal('difference_qty', 18, 2); // Số lượng lệch (actual_qty - system_qty)

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_adjustment_items');
    }
};
