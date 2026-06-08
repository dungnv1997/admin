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
        Schema::create('sales_returns', function (Blueprint $table) {
            $table->id();

            // 🛡️ SỬA THÀNH restrictOnDelete: Bảo vệ dữ liệu chứng từ hoàn hàng của Tenant
            $table->foreignId('tenant_id')
                ->constrained()
                ->restrictOnDelete();

            // 🛡️ BỔ SUNG: Xác định hàng trả lại được nhập về kho của Chi nhánh nào
            $table->foreignId('branch_id')
                ->constrained()
                ->restrictOnDelete();

            // 🛡️ SỬA THÀNH restrictOnDelete: Giữ lại phiếu trả hàng kể cả khi hóa đơn gốc có biến động
            $table->foreignId('sales_order_id')
                ->constrained()
                ->restrictOnDelete();

            // ✅ ĐÃ CHUẨN: Khách hàng bị xóa thì phiếu trả hàng vẫn giữ lại để làm báo cáo doanh thu
            $table->foreignId('customer_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->string('code')->unique();

            // ✅ ĐÃ CHUẨN: Tổng số tiền hoàn lại cho khách dùng kiểu decimal chính xác cao
            $table->decimal('total_amount', 18, 2);

            $table->text('reason')->nullable();

            // 🛡️ SỬA THÀNH restrictOnDelete: Không cho phép xóa nhân viên nếu họ đã lập phiếu này
            $table->foreignId('created_by')
                ->constrained('users')
                ->restrictOnDelete();

            $table->timestamps();

            // 💡 KHUYÊN DÙNG: Thêm xóa mềm cho chứng từ hoàn hàng
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_returns');
    }
};
