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
        // Mọi nhập/xuất đều ghi vào đây.
        Schema::create('stock_transactions', function (Blueprint $table) {
            $table->id();

            // 🛡️ SỬA THÀNH restrictOnDelete: Bảo vệ lịch sử dòng chạy của hàng hóa
            $table->foreignId('tenant_id')
                ->constrained()
                ->restrictOnDelete();

            $table->foreignId('branch_id')
                ->constrained()
                ->restrictOnDelete();

            $table->foreignId('product_variant_id')
                ->constrained()
                ->restrictOnDelete();

            // ✅ CỰC CHUẨN: Bộ danh sách các lý do xuất nhập kho thực tế rất đầy đủ
            $table->enum('type', [
                'purchase',          // Nhập hàng từ nhà cung cấp
                'sale',              // Xuất hàng bán cho khách
                'sale_return',       // Khách trả lại hàng
                'purchase_return',   // Trả lại hàng cho nhà cung cấp
                'adjustment',        // Cân bằng kho khi kiểm hàng
                'transfer_in',       // Nhận hàng chuyển từ chi nhánh khác sang
                'transfer_out'       // Chuyển hàng sang chi nhánh khác
            ]);

            // ✅ CỰC CHUẨN: Bộ 3 cột số lượng lưu vết lịch sử kho (Thiết kế rất chuyên nghiệp)
            $table->decimal('quantity', 18, 2);
            $table->decimal('before_qty', 18, 2);
            $table->decimal('after_qty', 18, 2);

            // 🧾 SỬA THÀNH nullable: Liên kết chứng từ gốc (Có thể trống nếu là cân bằng kho tự do)
            $table->string('reference_type')->nullable();
            $table->unsignedBigInteger('reference_id')->nullable();

            // 🛡️ SỬA THÀNH restrictOnDelete: Ngăn xóa tài khoản nhân viên đã làm lệnh xuất/nhập kho
            $table->foreignId('created_by')
                ->constrained('users')
                ->restrictOnDelete();

            $table->timestamps();

            // 🚀 TỐI ƯU INDEX KẾT HỢP: Giúp bộ phận kho lọc lịch sử biến động của 1 sản phẩm tại 1 chi nhánh siêu nhanh
            $table->index(['tenant_id', 'branch_id', 'product_variant_id'], 'tenant_branch_variant_stock_index');
            $table->index(['reference_type', 'reference_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_transactions');
    }
};
