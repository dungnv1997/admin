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

            // ✅ ĐÃ SỬA: Bỏ ->unique() tại đây để tránh lỗi trùng mã giữa các Tenant
            $table->string('code');

            $table->decimal('subtotal', 18, 2);
            $table->decimal('discount_amount', 18, 2)->default(0);
            $table->decimal('tax_amount', 18, 2)->default(0);
            $table->decimal('total_amount', 18, 2);
            $table->decimal('paid_amount', 18, 2)->default(0);
            $table->decimal('debt_amount', 18, 2)->default(0);

            // ✅ ĐÃ SỬA: Giới hạn độ dài chuỗi của trạng thái để tối ưu bộ nhớ
            $table->string('status', 30);

            $table->foreignId('created_by')
                ->constrained('users')
                ->restrictOnDelete();

            $table->softDeletes();
            $table->timestamps();

            // ================= INDEXES & CONSTRAINTS =================

            // ✅ ĐÃ SỬA: Đảm bảo mã hóa đơn là duy nhất TRONG CÙNG MỘT TENANT
            $table->unique(['tenant_id', 'code']);

            // 🚀 TỐI ƯU: Chỉ mục giúp lọc hóa đơn theo chi nhánh của từng Tenant nhanh hơn
            $table->index(['tenant_id', 'branch_id']);

            // 🚀 TỐI ƯU: Chỉ mục giúp tra cứu lịch sử mua hàng của khách hàng nhanh hơn
            $table->index(['tenant_id', 'customer_id']);

            // 🚀 TỐI ƯU: Chỉ mục giúp lọc hóa đơn theo ngày tạo hoặc theo trạng thái đơn hàng
            $table->index(['tenant_id', 'status']);
            $table->index(['tenant_id', 'created_at']);
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
