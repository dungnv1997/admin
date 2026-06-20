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
        Schema::create('brands', function (Blueprint $table) {

            $table->id();

            $table->foreignId('tenant_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('name');

            $table->string('code')
                ->nullable();

            $table->string('logo')
                ->nullable();

            $table->text('description')
                ->nullable();

            $table->boolean('status')
                ->default(true);

            $table->timestamps();

            $table->softDeletes();

            // Hãy sửa lại phần unique như sau:
            $table->unique([
                'tenant_id',
                'name',
                'deleted_at' // Thêm cột này vào để không bị lỗi khi tạo lại thương hiệu đã xóa
            ]);

            // Hãy sửa lại phần unique như sau:
            $table->unique([
                'tenant_id',
                'code',
                'deleted_at' // Thêm cột này vào để không bị lỗi khi tạo lại thương hiệu đã xóa
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('brands');
    }
};
