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
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();

            // Thêm index cho tenant_id
            $table->foreignId('tenant_id')
                ->constrained()
                ->cascadeOnDelete();

            // BỎ ->unique() ở đây để các tenant khác nhau có thể trùng mã code
            $table->string('code');

            $table->string('name');

            $table->enum('type', [
                'percentage',
                'fixed_amount'
            ]);

            $table->decimal('value', 18, 2);
            $table->decimal('min_order_amount', 18, 2)->default(0);

            $table->integer('usage_limit')->nullable(); // Tổng số lần mã được dùng trên hệ thống
            $table->integer('used_count')->default(0);

            // GỢI Ý THÊM: Giới hạn số lần dùng của MỖI khách hàng (ví dụ: mỗi người chỉ được dùng 1 lần)
            // $table->integer('usage_limit_per_user')->nullable()->default(1);

            // Dùng timestamp và thêm index để tối ưu câu lệnh kiểm tra hạn sử dụng
            $table->timestamp('start_date')->index();
            $table->timestamp('end_date')->index();

            $table->boolean('status')->default(true)->index();
            $table->timestamps();

            // SỬA LỖI: Đảm bảo mã code chỉ là duy nhất TRONG CÙNG MỘT TENANT mà thôi
            $table->unique(['tenant_id', 'code']);

            // Index kết hợp giúp tối ưu câu lệnh tìm kiếm mã coupon đang còn hạn của tenant đó
            $table->index(['tenant_id', 'status', 'start_date', 'end_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
