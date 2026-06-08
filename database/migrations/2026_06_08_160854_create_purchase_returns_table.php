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
        Schema::create('purchase_returns', function (Blueprint $table) {
            $table->id();

            // 🛡️ SỬA THÀNH restrictOnDelete: Bảo vệ dữ liệu chứng từ hoàn hàng của Tenant
            $table->foreignId('tenant_id')
                ->constrained()
                ->restrictOnDelete();

            // 🛡️ BỔ SUNG: Xác định hàng được xuất trả đi từ kho của Chi nhánh nào
            $table->foreignId('branch_id')
                ->constrained()
                ->restrictOnDelete();

            // 🛡️ SỬA THÀNH restrictOnDelete: Giữ lại phiếu trả hàng kể cả khi đơn nhập gốc có biến động
            $table->foreignId('purchase_order_id')
                ->constrained()
                ->restrictOnDelete();

            // 🛡️ SỬA THÀNH restrictOnDelete: Ngăn xóa Nhà cung cấp nếu mình đã từng có phiếu trả hàng cho họ
            $table->foreignId('supplier_id')
                ->constrained()
                ->restrictOnDelete();

            $table->string('code')->unique();

            // ✅ ĐÃ CHUẨN: Tổng số tiền nhà cung cấp hoàn lại dùng kiểu decimal chính xác cao
            $table->decimal('total_amount', 18, 2);

            $table->text('reason')->nullable();

            // 🛡️ SỬA THÀNH restrictOnDelete: Không cho phép xóa nhân viên nếu họ đã lập phiếu này
            $table->foreignId('created_by')
                ->constrained('users')
                ->restrictOnDelete();

            $table->timestamps();

            // 💡 KHUYÊN DÙNG: Thêm xóa mềm cho chứng từ trả hàng nhà cung cấp
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_returns');
    }
};
