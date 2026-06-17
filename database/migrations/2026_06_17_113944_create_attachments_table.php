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
        Schema::create('attachments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('tenant_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->index('tenant_id');

            // Tạo sẵn 2 trường 'attachable_type' và 'attachable_id' kèm index kết hợp của Laravel
            // gọi là Quan hệ đa hình - Polymorphic Relation
            $table->morphs('attachable');

            $table->string('file_name'); // Tên gốc của file (ví dụ: hình_mẫu.png)
            $table->string('file_path'); // Đường dẫn lưu file trên ổ đĩa

            // GỢI Ý: Xác định nơi lưu file (local, public, s3...) để dễ nâng cấp hệ thống sau này
            $table->string('disk')->default('public');

            $table->string('mime_type')->nullable(); // Ví dụ: image/jpeg, application/pdf

            // SỬA: Đổi sang unsigned để đảm bảo dung lượng file luôn là số dương
            $table->unsignedBigInteger('file_size')->default(0);

            $table->timestamps();

            // TỐI ƯU: Index kết hợp giúp lấy nhanh tất cả ảnh/file của một Tenant
            $table->index(['tenant_id', 'attachable_type', 'attachable_id'], 'tenant_attachable_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attachments');
    }
};
