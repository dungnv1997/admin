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
        Schema::create('customer_point_logs', function (Blueprint $table) {
            $table->id();

            // 🛡️ BỔ SUNG: Xác định dòng lịch sử này thuộc về Tenant (công ty) nào
            $table->foreignId('tenant_id')
                ->constrained()
                ->restrictOnDelete();

            // 🛡️ SỬA THÀNH restrictOnDelete: Giữ lại lịch sử dòng điểm để làm báo cáo kế toán
            $table->foreignId('customer_id')
                ->constrained()
                ->restrictOnDelete();

            // ✅ ĐÃ CHUẨN: Điểm số (Có thể là số dương khi tích điểm, hoặc số âm khi đổi quà)
            $table->integer('points');

            // ✅ ĐÃ CHUẨN: Phân loại dòng điểm (earn: tích điểm, redeem: tiêu điểm, adjust: điều chỉnh thủ công)
            $table->enum('type', [
                'earn',
                'redeem',
                'adjust'
            ]);

            $table->string('description')->nullable();

            // ✅ ĐÃ CHUẨN: Lưu lại nhân viên nào đã thao tác dòng điểm này
            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            // 🚀 TỐI ƯU INDEX: Giúp tìm kiếm lịch sử điểm của một khách hàng siêu nhanh
            $table->index(['tenant_id', 'customer_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_point_logs');
    }
};
