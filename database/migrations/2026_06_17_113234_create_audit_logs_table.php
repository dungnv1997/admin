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
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();

            // Thêm index để tăng tốc độ lọc theo Tenant
            $table->foreignId('tenant_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->string('action')->index(); // Thêm index để lọc theo hành động (create, update, delete)

            // Giữ nguyên cấu trúc của bạn nhưng đổi thành index kết hợp ở phía dưới
            $table->string('auditable_type');
            $table->unsignedBigInteger('auditable_id');

            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();

            $table->string('ip_address', 45)->nullable(); // Độ dài 45 giúp lưu tốt cả IPv4 và IPv6

            // Thay vì dùng timestamps(), chỉ dùng created_at và thêm index để sắp xếp log từ mới đến cũ
            $table->timestamp('created_at')->useCurrent()->index();

            // TỐI ƯU QUAN TRỌNG: Chỉ mục kết hợp cho liên kết đa hình
            $table->index(['auditable_type', 'auditable_id']);

            // Chỉ mục kết hợp giúp Tenant xem nhanh dòng thời gian (Timeline) hoạt động của cửa hàng mình
            $table->index(['tenant_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
