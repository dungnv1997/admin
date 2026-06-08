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
        Schema::create('debt_transactions', function (Blueprint $table) {
            $table->id();

            // 🛡️ SỬA THÀNH restrictOnDelete: Bảo vệ lịch sử dòng nợ tài chính
            $table->foreignId('tenant_id')
                ->constrained()
                ->restrictOnDelete();

            // 🛡️ BỔ SUNG: Xác định công nợ này phát sinh tại chi nhánh nào
            $table->foreignId('branch_id')
                ->constrained()
                ->restrictOnDelete();

            // 🔥 TỐI ƯU ĐA HÌNH: Thay thế cho cả customer_id và supplier_id
            // Tự động tạo 2 cột: debteable_type (Lưu Model) và debteable_id (Lưu ID)
            $table->morphs('debteable');

            // ✅ TỐI ƯU CÁC LOẠI TRẠNG THÁI: Rút gọn lại vì đã phân biệt được Khách/Nhà cung cấp qua morphs
            // 'increase': Nợ tăng lên (Mua hàng chưa trả tiền)
            // 'decrease': Nợ giảm đi (Trả bớt tiền nợ)
            $table->enum('type', [
                'increase',
                'decrease'
            ]);

            // 💰 Số tiền biến động và số dư sau khi thay đổi (Rất chuẩn)
            $table->decimal('amount', 18, 2);
            $table->decimal('balance_after', 18, 2);

            // 🧾 Liên kết chứng từ gốc (Ví dụ: sales_orders hoặc purchase_orders)
            $table->string('reference_type')->nullable();
            $table->unsignedBigInteger('reference_id')->nullable();

            $table->text('note')->nullable();

            // 🛡️ SỬA THÀNH restrictOnDelete: Không cho phép xóa nhân viên nếu họ đã tạo giao dịch nợ
            $table->foreignId('created_by')
                ->constrained('users')
                ->restrictOnDelete();

            $table->timestamps();

            // 🚀 TỐI ƯU INDEX KẾT HỢP: Tìm lịch sử nợ của một đối tượng thuộc Tenant siêu nhanh
            $table->index(['tenant_id', 'debteable_type', 'debteable_id'], 'tenant_debteable_debt_index');
            $table->index(['reference_type', 'reference_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('debt_transactions');
    }
};
