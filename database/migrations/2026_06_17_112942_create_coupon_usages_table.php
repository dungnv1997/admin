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
        //Ghi nhận thay đổi dữ liệu quan trọng.
        Schema::create('coupon_usages', function (Blueprint $table) {
            $table->id();

            // GỢI Ý: Thêm tenant_id để làm báo cáo, thống kê siêu nhanh mà không cần JOIN bảng
            $table->foreignId('tenant_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('coupon_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('sales_order_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('customer_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->decimal('discount_amount', 18, 2);
            $table->timestamps();

            // TỐI ƯU: Thêm index kết hợp để tối ưu các câu lệnh kiểm tra:
            // Khách hàng này đã dùng mã coupon này chưa? (Phục vụ logic giới hạn lượt dùng của mỗi user)
            $table->index(['coupon_id', 'customer_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupon_usages');
    }
};
