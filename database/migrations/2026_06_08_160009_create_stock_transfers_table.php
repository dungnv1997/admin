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
        //Chuyển kho giữa chi nhánh.
        Schema::create('stock_transfers', function (Blueprint $table) {
            $table->id();

            // 🛡️ SỬA THÀNH restrictOnDelete: Bảo vệ dữ liệu phiếu chuyển kho của Tenant
            $table->foreignId('tenant_id')
                ->constrained()
                ->restrictOnDelete();

            // 🛡️ SỬA THÀNH restrictOnDelete: Ngăn xóa chi nhánh xuất hàng nếu đã có phiếu chuyển
            $table->foreignId('from_branch_id')
                ->constrained('branches')
                ->restrictOnDelete();

            // 🛡️ SỬA THÀNH restrictOnDelete: Ngăn xóa chi nhánh nhận hàng nếu đã có phiếu chuyển
            $table->foreignId('to_branch_id')
                ->constrained('branches')
                ->restrictOnDelete();

            $table->string('code')->unique();

            // ✅ CỰC CHUẨN: Quy trình trạng thái chuyển kho rất logic (draft -> shipping -> completed/cancelled)
            $table->enum('status', [
                'draft',
                'shipping',
                'completed',
                'cancelled'
            ]);

            $table->text('note')->nullable();

            // 🛡️ SỬA THÀNH restrictOnDelete: Không cho phép xóa nhân viên nếu họ đã lập phiếu này
            $table->foreignId('created_by')
                ->constrained('users')
                ->restrictOnDelete();

            $table->timestamps();

            // 💡 KHUYÊN DÙNG: Thêm xóa mềm để bảo vệ lịch sử chứng từ điều chuyển
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_transfers');
    }
};
