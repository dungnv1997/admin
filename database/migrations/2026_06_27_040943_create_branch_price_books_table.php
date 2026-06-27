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
        Schema::create('branch_price_books', function (Blueprint $table) {
            $table->id();

            // 1. Khai báo các cột trước
            $table->unsignedBigInteger('tenant_id');
            $table->unsignedBigInteger('branch_id');
            $table->unsignedBigInteger('price_book_id');
            $table->boolean('is_default')->default(false);
            $table->timestamps();

            // 2. Tạo khóa ngoại phức hợp để bảo vệ dữ liệu chống nhầm lẫn giữa các Tenant
            // Ràng buộc: Chi nhánh phải thuộc về đúng Tenant này
            $table->foreign(['tenant_id', 'branch_id'])
                ->references(['tenant_id', 'id'])
                ->on('branches')
                ->cascadeOnDelete();

            // Ràng buộc: Bảng giá phải thuộc về đúng Tenant này
            $table->foreign(['tenant_id', 'price_book_id'])
                ->references(['tenant_id', 'id'])
                ->on('price_books')
                ->cascadeOnDelete();

            // 3. Khóa ngoại gốc tới bảng tenants (để cascade xóa sạch khi xóa tenant)
            $table->foreign('tenant_id')
                ->references('id')
                ->on('tenants')
                ->cascadeOnDelete();

            // 4. Đảm bảo một cặp branch_id và price_book_id không bị trùng lặp trong một Tenant
            $table->unique(['tenant_id', 'branch_id', 'price_book_id'], 'branch_price_book_tenant_unique');

            // 5. Index tối ưu cho câu lệnh tìm bảng giá của một chi nhánh: WHERE tenant_id = X AND branch_id = Y
            $table->index(['tenant_id', 'branch_id', 'is_default'], 'idx_tenant_branch_default');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('branch_price_books');
    }
};
