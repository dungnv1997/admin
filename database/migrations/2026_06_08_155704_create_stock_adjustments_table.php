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
        Schema::create('stock_adjustments', function (Blueprint $table) {
            $table->id();

            // 🛡️ SỬA THÀNH restrictOnDelete: Bảo vệ dữ liệu phiếu kiểm kho của Tenant
            $table->foreignId('tenant_id')
                ->constrained()
                ->restrictOnDelete();

            // 🛡️ SỬA THÀNH restrictOnDelete: Ngăn xóa Chi nhánh nếu chi nhánh đó đã từng có phiếu kiểm kho
            $table->foreignId('branch_id')
                ->constrained()
                ->restrictOnDelete();

            $table->string('code')->unique();

            $table->text('reason')->nullable();

            // ✅ ĐÃ CHUẨN: Trạng thái phiếu kiểm (draft: phiếu nháp/đang kiểm, completed: đã cân bằng kho xong)
            $table->enum('status', [
                'draft',
                'completed'
            ]);

            // 🛡️ SỬA THÀNH restrictOnDelete: Không cho phép xóa nhân viên nếu họ đã lập phiếu kiểm kho này
            $table->foreignId('created_by')
                ->constrained('users')
                ->restrictOnDelete();

            $table->timestamps();

            // 💡 KHUYÊN DÙNG: Thêm xóa mềm để bảo vệ lịch sử chứng từ kiểm kho
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_adjustments');
    }
};
