<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();

            $table->foreignId('tenant_id')
                ->constrained()
                ->cascadeOnDelete();

            // Khóa của cấu hình (Ví dụ: 'site_logo', 'mail_host')
            $table->string('key');

            // Giá trị cấu hình (Chấp nhận cả chuỗi dài hoặc JSON hóa)
            $table->longText('value')
                ->nullable();

            // Nhóm cấu hình để phân loại (Ví dụ: 'general', 'email', 'payment')
            $table->string('group')
                ->default('general');

            // Mô tả chi tiết chức năng của cấu hình này
            $table->text('description')
                ->nullable();

            $table->timestamps();

            // ✅ ĐÃ CHUẨN: Đảm bảo trong 1 Công ty không bao giờ có 2 cấu hình trùng tên khóa (key)
            $table->unique([
                'tenant_id',
                'key'
            ]);

            // 🚀 TỐI ƯU INDEX: Giúp Tenant lấy toàn bộ cấu hình theo từng nhóm (Ví dụ: Nhóm Email) siêu tốc
            $table->index(['tenant_id', 'group']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
