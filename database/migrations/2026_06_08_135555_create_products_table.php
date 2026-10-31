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
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            // 1. Khai báo các cột ID trước
            $table->unsignedBigInteger('tenant_id');
            $table->unsignedBigInteger('category_id')->nullable();
            $table->unsignedBigInteger('brand_id')->nullable();

            $table->string('code');
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();

            // 2. Khóa ngoại gốc tới bảng tenants (để xóa sạch sản phẩm khi xóa tenant)
            $table->foreign('tenant_id')
                ->references('id')
                ->on('tenants')
                ->cascadeOnDelete();

            // 3. RÀNG BUỘC PHỨC HỢP: Danh mục phải thuộc về đúng Tenant này
            $table->foreign(['tenant_id', 'category_id'])
                ->references(['tenant_id', 'id'])
                ->on('categories')
                ->onDelete('restrict'); // Giữ nullOnDelete theo thiết kế ban đầu của bạn

            // 4. RÀNG BUỘC PHỨC HỢP: Thương hiệu phải thuộc về đúng Tenant này
            $table->foreign(['tenant_id', 'brand_id'])
                ->references(['tenant_id', 'id'])
                ->on('brands')
                ->onDelete('restrict'); // Giữ nullOnDelete theo thiết kế ban đầu của bạn

            // 5. Đảm bảo mã sản phẩm chỉ duy nhất TRONG CÙNG MỘT TENANT
            $table->unique(['tenant_id', 'code'], 'uq_tenant_product_code');

            // 6. Các chỉ mục tối ưu hóa tốc độ tìm kiếm
            $table->index(['tenant_id', 'category_id', 'status'], 'idx_tenant_category_status');
            $table->index(['tenant_id', 'brand_id', 'status'], 'idx_tenant_brand_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
