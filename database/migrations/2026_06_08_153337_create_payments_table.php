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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            // 🛡️ SỬA THÀNH restrictOnDelete: Bảo vệ dòng tiền và báo cáo tài chính của doanh nghiệp
            $table->foreignId('tenant_id')
                ->constrained()
                ->restrictOnDelete();

            // 🛡️ SỬA THÀNH restrictOnDelete: Bắt buộc dòng tiền phải gắn liền với một Chi nhánh cụ thể
            $table->foreignId('branch_id')
                ->constrained()
                ->restrictOnDelete();

            // 🔥 CỰC CHUẨN: Tạo ra 2 cột 'paymentable_type' và 'paymentable_id' để liên kết đa hình
            $table->morphs('paymentable');

            // ✅ ĐÃ CHUẨN: Danh sách phương thức thanh toán thực tế phổ biến tại Việt Nam
            $table->enum('payment_method', [
                'cash',
                'bank',
                'momo',
                'vnpay',
                'zalopay'
            ]);

            // ✅ ĐÃ CHUẨN: Số tiền thanh toán dùng decimal chính xác cao
            $table->decimal('amount', 18, 2);

            // ✅ ĐÃ CHUẨN: Mã tham chiếu (Ví dụ: Mã giao dịch ngân hàng, mã ví điện tử để đối soát)
            $table->string('reference_no')->nullable();

            $table->text('note')->nullable();

            // 🛡️ SỬA THÀNH restrictOnDelete: Không cho phép xóa nhân viên nếu họ đã cầm tiền/thu tiền của khách
            $table->foreignId('created_by')
                ->constrained('users')
                ->restrictOnDelete();

            // ✅ ĐÃ CHUẨN: Ghi nhận thời gian khách thực tế xuống tiền (Có thể khác với thời gian tạo phiếu trên máy)
            $table->timestamp('paid_at');

            $table->timestamps();

            // 🚀 TỐI ƯU INDEX KẾT HỢP: Giúp kế toán lọc báo cáo dòng tiền theo phương thức hoặc theo ngày cực nhanh
            $table->index(['tenant_id', 'branch_id', 'payment_method']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
