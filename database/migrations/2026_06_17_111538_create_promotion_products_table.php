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
        Schema::create('promotion_products', function (Blueprint $table) {
            $table->id();

            $table->foreignId('promotion_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('product_variant_id')
                ->constrained()
                ->cascadeOnDelete();

            // GỢI Ý ĐỂ XỬ LÝ 'buy_x_get_y': Phân biệt sản phẩm mua hay sản phẩm tặng
            // 'buy' = sản phẩm điều kiện cần mua, 'get' = sản phẩm quà tặng kèm
            $table->enum('role', ['apply', 'buy', 'get'])->default('apply')->index();

            // GỢI Ý THÊM (Tùy chọn): Nếu muốn quy định số lượng riêng cho loại buy_x_get_y
            // Ví dụ: Mua đúng 2 sản phẩm A mới được tặng 1 sản phẩm B
            // $table->integer('quantity')->default(1);

            // Ngăn chặn trùng lặp bản ghi
            $table->unique([
                'promotion_id',
                'product_variant_id',
                'role' // Thêm role vào unique nếu 1 sản phẩm vừa là hàng mua vừa là hàng tặng
            ], 'promo_product_role_unique'); // Đặt tên ngắn gọn cho key để tránh lỗi quá ký tự ở một số DB

            $table->timestamps(); // Giữ lại nếu bạn muốn biết sản phẩm được áp dụng từ lúc nào
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promotion_products');
    }
};
