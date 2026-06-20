<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();

            // 🛡️ SỬA THÀNH restrictOnDelete: Bảo vệ thông tin khách hàng và công nợ
            $table->foreignId('tenant_id')
                ->constrained()
                ->restrictOnDelete();

            $table->string('code')->unique();
            $table->string('name');
            $table->string('phone', 20)->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->date('birthday')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();

            // Tiền tệ và điểm thưởng (Rất chuẩn)
            $table->decimal('debt_balance', 18, 2)->default(0);
            $table->decimal('total_sales', 18, 2)->default(0);
            $table->integer('loyalty_points')->default(0);

            $table->text('note')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();

            /// 🚀 Tốc độ tối đa khi tìm kiếm theo Số điện thoại của từng Tenant
            $table->index(['tenant_id', 'phone']);

            // 🚀 Tốc độ tối đa khi tìm kiếm theo Tên của từng Tenant
            $table->index(['tenant_id', 'name']);

            // 💡 Khuyên dùng: Nên thêm xóa mềm để khi xóa khách hàng không bị mất dữ liệu vĩnh viễn
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
