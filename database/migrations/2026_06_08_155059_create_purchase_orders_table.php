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
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();

            // 🛡️ SỬA THÀNH restrictOnDelete: Bảo vệ dữ liệu hóa đơn nhập của Tenant
            $table->foreignId('tenant_id')
                ->constrained()
                ->restrictOnDelete();

            // 🛡️ SỬA THÀNH restrictOnDelete: Ngăn xóa Chi nhánh nếu chi nhánh đó đã từng nhập hàng
            $table->foreignId('branch_id')
                ->constrained()
                ->restrictOnDelete();

            // 🛡️ SỬA THÀNH restrictOnDelete: Ngăn xóa Nhà cung cấp nếu đã có đơn nhập hàng của họ
            $table->foreignId('supplier_id')
                ->constrained()
                ->restrictOnDelete();

            $table->string('code')->unique();

            // 💰 Các cột số tiền (Thiết kế kiểu decimal rất chuẩn)
            $table->decimal('subtotal', 18, 2);
            $table->decimal('discount_amount', 18, 2)->default(0);
            $table->decimal('total_amount', 18, 2);
            $table->decimal('paid_amount', 18, 2)->default(0);
            $table->decimal('debt_amount', 18, 2)->default(0);

            // 📊 Trạng thái đơn nhập hàng (draft: nháp, completed: đã hoàn thành, cancelled: đã hủy)
            $table->enum('status', [
                'draft',
                'completed',
                'cancelled'
            ]);

            $table->text('note')->nullable();

            // 🛡️ SỬA THÀNH restrictOnDelete: Không cho phép xóa nhân viên nếu họ đã lập đơn nhập hàng
            $table->foreignId('created_by')
                ->constrained('users')
                ->restrictOnDelete();

            $table->timestamps();

            // 💡 KHUYÊN DÙNG: Thêm xóa mềm để bảo vệ lịch sử chứng từ
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_orders');
    }
};
