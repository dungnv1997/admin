<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();

            $table->foreignId('tenant_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('product_id')
                ->constrained()
                ->cascadeOnDelete();

            // Mã quản lý kho (Ví dụ: SKU-NIKE-RED-L)
            $table->string('sku');

            // Mã vạch để quét sản phẩm (Ví dụ: 893123456789)
            $table->string('barcode')->nullable();

            // Tên biến thể (Ví dụ: Đỏ - Size L)
            $table->string('name')->nullable();

            // Lưu thuộc tính động dưới dạng mảng (Ví dụ: {"color": "red", "size": "L"})
            $table->json('attributes')->nullable();

            // Giá vốn nhập hàng
            $table->decimal('cost_price', 18, 2)->default(0);

            // Giá bán ra cho khách
            $table->decimal('sale_price', 18, 2)->default(0);

            // Trạng thái (true: đang bán, false: ngừng kinh doanh)
            $table->boolean('status')->default(true);

            $table->softDeletes();
            $table->timestamps();

            // 🚀 TỐI ƯU UNIQUE 1: Chống trùng SKU trong 1 công ty và không lỗi khi Xóa mềm
            $table->unique([
                'tenant_id',
                'sku',
                'deleted_at'
            ]);

            // 🚀 TỐI ƯU UNIQUE 2: Chống trùng mã vạch trong 1 công ty và không lỗi khi Xóa mềm
            $table->unique([
                'tenant_id',
                'barcode',
                'deleted_at'
            ]);

            // 🚀 TỐI ƯU INDEX: Giúp Tenant lấy danh sách biến thể của một sản phẩm siêu tốc
            $table->index(['tenant_id', 'product_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};
