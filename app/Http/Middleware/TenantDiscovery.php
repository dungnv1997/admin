<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class TenantDiscovery
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Lấy tenant_code từ tham số của Route URL (ví dụ: shopa)
        $tenantCode = $request->route()->parameter('tenant_code');

        if ($tenantCode) {
            // 2. Tra cứu nhanh trong bảng tenants để lấy ID
            $tenant = DB::table('tenants')->where('code', $tenantCode)->first();

            // Nếu gõ bừa Subdomain không tồn tại, chặn lại ngay lập tức
            if (!$tenant) {
                return response()->json([
                    'success' => false,
                    'code' => 'TENANT_001',
                    'message' => 'Cửa hàng không tồn tại trên hệ thống.'
                ], 404);
            }

            // 3. Đưa ID của tenant này vào cấu hình tạm thời của hệ thống để gọi mọi nơi
            config(['app.current_tenant_id' => $tenant->id]);
            config(['app.current_tenant_model' => $tenant]);

            // 4. GIẢI PHÓNG CONTROLLER: Xóa bỏ tham số tenant_code khỏi danh sách tham số của Route
            // Dòng này giúp các hàm trong Controller KHÔNG CẦN phải nhận biến $tenant_code nữa!
            $request->route()->forgetParameter('tenant_code');
        }

        return $next($request);
    }
}
