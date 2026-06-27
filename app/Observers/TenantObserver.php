<?php

namespace App\Observers;

use App\Models\Tenant;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TenantObserver
{
    /**
     * Lắng nghe sự kiện sau khi Tenant được lưu thành công vào cơ sở dữ liệu.
     */
    public function created(Tenant $tenant): void
    {
        // 1. Tự động tạo Chi nhánh đầu tiên cho Tenant này
        $defaultBranch = $tenant->branches()->create([
            'branch_name' => 'Chi nhánh Trung tâm',
            'address'     => 'Chưa cập nhật',
            'is_active'   => true,
        ]);

        // 2. Tự động khởi tạo bộ Vai trò (Roles) bằng CHỮ cho riêng cửa hàng này
        // Quan trọng: quan hệ hasMany sẽ tự động gán tenant_id khi dùng create() từ $tenant->roles()
        $adminRole     = $tenant->roles()->create(['name' => 'admin']);
        $cashierRole   = $tenant->roles()->create(['name' => 'cashier']);
        $warehouseRole = $tenant->roles()->create(['name' => 'warehouse']);


        // 4. Tự động tạo tài khoản Chủ cửa hàng (Tenant Owner)
        $owner = $tenant->users()->create([
            'branch_id'       => $defaultBranch->id,
            'password'        => Hash::make('abc@1234'), // Mật khẩu mặc định ban đầu
            'name'       => 'Chủ Cửa Hàng (' . $tenant->business_name . ')',
            'is_tenant_owner' => true,
            'is_active'       => true,
        ]);

        // 5. Gắn quyền ADMIN bằng chữ (thông qua ID liên kết) vào bảng trung gian role_user
        // Hàm attach() của Eloquent sẽ tự động INSERT một bản ghi vào bảng role_user
        $owner->roles()->attach($adminRole->id);
    }

    /**
     * Handle the Tenant "updated" event.
     */
    public function updated(Tenant $tenant): void
    {
        //
    }

    /**
     * Handle the Tenant "deleted" event.
     */
    public function deleted(Tenant $tenant): void
    {
        //
    }

    /**
     * Handle the Tenant "restored" event.
     */
    public function restored(Tenant $tenant): void
    {
        //
    }

    /**
     * Handle the Tenant "force deleted" event.
     */
    public function forceDeleted(Tenant $tenant): void
    {
        //
    }
}
