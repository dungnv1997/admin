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

            // ✅ ĐÃ SỬA: Thêm nullable() để nullOnDelete() hoạt động được
            $table->foreignId('customer_group_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->foreignId('tenant_id')
                ->constrained()
                ->restrictOnDelete();

            // ✅ ĐÃ SỬA: Bỏ ->unique() ở đây để tránh trùng mã giữa các Tenant khác nhau
            $table->string('code');
            $table->string('name');
            $table->string('phone', 20)->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->date('birthday')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();

            $table->decimal('debt_balance', 18, 2)->default(0);
            $table->decimal('total_sales', 18, 2)->default(0);
            $table->integer('loyalty_points')->default(0);

            $table->text('note')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
            $table->softDeletes();

            // ================= INDEXES & CONSTRAINTS =================

            // ✅ ĐÃ SỬA: Đảm bảo mã khách hàng là duy nhất TRONG CÙNG MỘT TENANT
            $table->unique(['tenant_id', 'code']);

            // 🚀 Tốc độ tối đa khi tìm kiếm theo Số điện thoại của từng Tenant
            $table->index(['tenant_id', 'phone']);

            // 🚀 Tốc độ tối đa khi tìm kiếm theo Tên của từng Tenant
            $table->index(['tenant_id', 'name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
