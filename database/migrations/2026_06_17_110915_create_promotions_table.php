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
    {   //Khuyến mãi tự động.
        Schema::create('promotions', function (Blueprint $table) {
            $table->id();

            // Thêm index để tăng tốc độ lọc theo Tenant
            $table->foreignId('tenant_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('name');

            $table->enum('type', [
                'percentage',
                'fixed_amount',
                'buy_x_get_y'
            ]);

            // Cho phép nullable vì loại 'buy_x_get_y' có thể không dùng số tiền đơn thuần ở đây
            $table->decimal('value', 18, 2)->nullable();

            $table->decimal('min_order_amount', 18, 2)->default(0);

            // Dùng timestamp để chuẩn hóa múi giờ tốt hơn, thêm index vì sẽ lọc WHERE start_date <= NOW()
            $table->timestamp('start_date')->index();
            $table->timestamp('end_date')->index();

            $table->boolean('status')->default(true)->index();

            // GỢI Ý THÊM: Quản lý giới hạn lượt dùng
            $table->integer('max_uses')->nullable(); // null là không giới hạn
            $table->integer('used_count')->default(0); // số lần đã dùng

            $table->timestamps();

            // Index kết hợp để tối ưu câu lệnh lấy khuyến mãi đang chạy của tenant
            $table->index(['tenant_id', 'status', 'start_date', 'end_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promotions');
    }
};
