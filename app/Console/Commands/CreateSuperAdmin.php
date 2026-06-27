<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CreateSuperAdmin extends Command
{
    // Tên lệnh bạn sẽ gõ ngoài Terminal
    protected $signature = 'make:super-admin';
    protected $description = 'Tạo Tenant hệ thống, Role hệ thống và tài khoản Super Admin đầu tiên';

    public function handle()
    {
        $this->info('--- KHỞI TẠO TÀI KHOẢN TỐI CAO PRODUCTION ---');

        // 1. Nhập thông tin cấu hình từ Terminal
        $name = $this->ask('Nhập tên hiển thị của Admin:');
        $code = $this->ask('Nhập mã code của tenant:');
        $email = $this->ask('Nhập địa chỉ Email đăng nhập:');
        $password = $this->secret('Nhập Mật khẩu (ký tự nhập vào sẽ bị ẩn):');

        DB::beginTransaction();

        try {
            // 2. Kiểm tra hoặc tự tạo Tenant quản trị (Mặc định gán ID = 1)
            DB::table('tenants')->updateOrInsert(
                ['id' => 1],
                [
                    'name' => 'Tổng Bộ Quản Trị Hệ Thống',
                    'code' => $code,
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            );

            // 3. Khởi tạo hoặc lấy ra Vai trò 'super_admin' thuộc Tenant quản trị này
            // Sử dụng updateOrInsert để tránh lỗi trùng lặp khi chạy lệnh nhiều lần
            DB::table('roles')->updateOrInsert(
                [
                    'tenant_id' => 1,
                    'name' => 'super_admin',
                ],
                [
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            );

            // Lấy lại ID của role vừa tạo để sử dụng cho bảng trung gian ở bước sau
            $roleId = DB::table('roles')
                ->where('tenant_id', 1)
                ->where('name', 'super_admin')
                ->value('id');

            // 4. Tạo tài khoản User đầu tiên gắn chặt vào Tenant ID = 1
            // Sử dụng insertGetId để lấy ra ID tự sinh của User mới nhằm phục vụ việc gán Role
            $userId = DB::table('users')->insertGetId([
                'tenant_id'  => 1, // Ép buộc thuộc Tenant 1 để thỏa mãn cấu trúc của bạn
                'branch_id'  => null, // Admin tổng hệ thống không cần trực thuộc chi nhánh nào
                'name'       => $name,
                'email'      => $email,
                'password'   => Hash::make($password),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // 5. Gắn vai trò Nhiều - Nhiều vào bảng trung gian role_user
            DB::table('user_roles')->insert([
                'user_id'    => $userId,
                'role_id'    => $roleId,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            DB::commit();
            $this->info("Thành công! Đã khởi tạo Super Admin với Email: {$email} sở hữu quyền [super_admin].");
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("Thất bại! Đã xảy ra lỗi: " . $e->getMessage());
        }
    }
}
