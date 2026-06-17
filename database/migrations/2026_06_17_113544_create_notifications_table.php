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
        // Nên đổi tên file migration thành: xxxx_xx_xx_xxxxxx_create_user_notifications_table.php
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();

            // Thêm index để lọc theo từng cửa hàng (Tenant)
            $table->foreignId('tenant_id')
                ->constrained()
                ->cascadeOnDelete();

            // Người nhận thông báo (Nhân viên)
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('title');
            $table->text('content');

            // Thêm index vì hệ thống sẽ liên tục chạy câu lệnh: WHERE is_read = false (đếm số thông báo chưa đọc)
            $table->boolean('is_read')->default(false)->index();

            $table->timestamps();

            // Index kết hợp giúp lấy nhanh danh sách thông báo chưa đọc của một người dùng cụ thể
            $table->index(['user_id', 'is_read']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
