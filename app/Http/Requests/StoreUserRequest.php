<?php

namespace App\Http\Requests;

use App\Constants\ErrorMessage;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
{
    /**
     * Xác thực người dùng có quyền thực hiện hành động này không (ví dụ: chỉ Admin mới được tạo)
     */
    public function authorize(): bool
    {
        // Có thể bổ sung thêm phân quyền tại đây, ví dụ: auth()->user()->is_admin
        return true;
    }

    /**
     * Quy tắc kiểm tra dữ liệu đầu vào
     */
    public function rules(): array
    {

        // Lấy tenant_id của người dùng đang đăng nhập thực hiện thao tác
        $tenantId = auth()->user()->tenant_id;

        return [
            'name' => ['required', 'string', 'max:255'],

            // 1. Kiểm tra Email duy nhất trong phạm vi Tenant
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->where(function ($query) use ($tenantId) {
                    return $query->where('tenant_id', $tenantId);
                }),
            ],

            // 2. Ép buộc mật khẩu có độ bảo mật cao
            'password' => ['required', 'string', 'min:8'],

            // 3. Kiểm tra chéo branch_id gửi lên phải thuộc về Tenant này
            'branch_id' => [
                'nullable', // Có thể trống nếu là tài khoản Admin quản lý toàn bộ chuỗi
                'integer',
                Rule::exists('branches', 'id')->where(function ($query) use ($tenantId) {
                    return $query->where('tenant_id', $tenantId);
                }),
            ],
        ];
    }

    /**
     * Tùy chỉnh thông báo lỗi trả về bằng tiếng Việt
     */
    public function messages(): array
    {
        return [
            'name.required' => ErrorMessage::USER_NAME_INPUT,
            'email.required' => ErrorMessage::USER_EMAIL_INPUT,
            'email.email' => ErrorMessage::USER_EMAIL_INVALID_FORMAT,
            'email.unique' => ErrorMessage::USER_EMAIL_USED,
            'password.required' => ErrorMessage::USER_PASSWORD_INPUT,
            'password.min' => ErrorMessage::USER_PASSWORD_MIN_8,
            'branch_id.exists' => ErrorMessage::USER_BRANCH_INVALID,
        ];
    }
}
