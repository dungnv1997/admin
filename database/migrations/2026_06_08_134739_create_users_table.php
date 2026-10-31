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
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            // 1. Khai báo các cột ID trước
            $table->unsignedBigInteger('tenant_id');
            $table->unsignedBigInteger('branch_id')->nullable(); // Chi nhánh làm việc (có thể trống nếu là Admin tổng của Tenant)

            $table->string('name');

            // SỬA LỖI: Xóa ->unique() ở đây để tránh đá nhau giữa các tenant
            $table->string('email');


            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();

            // 2. Khóa ngoại gốc tới bảng tenants
            $table->foreign('tenant_id')
                ->references('id')
                ->on('tenants')
                ->cascadeOnDelete();

            // 3. RÀNG BUỘC PHỨC HỢP: Chi nhánh được gán phải thuộc về đúng Tenant này
            // Khi chi nhánh bị xóa, tài khoản nhân viên vẫn giữ nguyên, chỉ có branch_id chuyển về null
            $table->foreign(['tenant_id', 'branch_id'])
                ->references(['tenant_id', 'id'])
                ->on('branches')
                ->onDelete('restrict'); // Dùng restrict để ép Laravel dọn dẹp bằng Observer hoặc code trước khi xóa chi nhánh

            // 4. ĐẢM BẢO: Email là duy nhất TRONG CÙNG MỘT TENANT
            $table->unique(['tenant_id', 'email'], 'uq_tenant_user_email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
