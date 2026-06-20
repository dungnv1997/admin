<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('tenant_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            // LOGIN, LOGOUT, CREATE_ORDER...
            $table->string('event');

            // Mô tả ngắn
            $table->text('description')
                ->nullable();

            // Đối tượng liên quan (Ví dụ: App\Models\Order)
            $table->string('subject_type')
                ->nullable();

            // ID bản ghi đối tượng liên quan (Ví dụ: 10)
            $table->unsignedBigInteger('subject_id')
                ->nullable();

            /*Thông tin bổ sung dạng JSON Dùng để lưu tất cả những thông tin "râu ria"
            linh hoạt mà bạn không muốn tạo thêm cột riêng để chứa.
            Vì nó có kiểu dữ liệu là json, bạn có thể ném bất kỳ dữ liệu dạng mảng (array)
            hoặc đối tượng (object) nào vào đây cũng được. */
            $table->json('properties')
                ->nullable();

            $table->ipAddress('ip_address')
                ->nullable();
            //Nhân viên này bấm nút duyệt đơn hàng bằng điện thoại iPhone hay bằng máy tính ở công ty
            $table->text('user_agent')
                ->nullable();

            $table->timestamps();

            // 🚀 INDEX TỐI ƯU 1: Giúp Tenant lọc tìm theo Nhân viên hoặc theo Sự kiện siêu nhanh
            $table->index(['tenant_id', 'user_id', 'event']);

            // 🚀 INDEX TỐI ƯU 2: Giúp tìm nhanh xem một Đối tượng cụ thể (Ví dụ: Đơn hàng X) đã có những hoạt động nào tác động vào
            $table->index(['subject_type', 'subject_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
