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
        Schema::create('price_books', function (Blueprint $table) {
            $table->id();

            // Thêm index ở đây để tăng tốc độ tìm kiếm theo Tenant
            $table->foreignId('tenant_id')
                ->constrained()
                ->cascadeOnDelete()
                ->index();

            $table->string('name');
            $table->text('description')->nullable();

            // Giữ nguyên hoặc đổi thành index tùy theo nhu cầu lọc dữ liệu của bạn
            $table->boolean('is_default')->default(false)->index();
            $table->boolean('status')->default(true)->index();

            // GỢI Ý THÊM: Thường bảng giá sẽ có ngày bắt đầu và kết thúc áp dụng
            // $table->timestamp('start_at')->nullable();
            // $table->timestamp('end_at')->nullable();

            $table->timestamps();

            // Tạo index kết hợp để tối ưu các câu lệnh tìm kiếm: WHERE tenant_id = X AND status = Y
            $table->index(['tenant_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('price_books');
    }
};
