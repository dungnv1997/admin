<?php

namespace App\Constants;

class ErrorMessage
{
    // Resource errors
    const RESOURCE_NOT_FOUND = 'Bản ghi này không tồn tại.';
    const USER_NOT_FOUND = 'Người dùng này không tồn tại.';
    const INVALID_CREDENTIALS = 'Email hoặc mật khẩu không chính xác.';

    // Validation errors
    const VALIDATION_FAILED = 'Dữ liệu không hợp lệ.';
    const REQUIRED_FIELD = 'Trường này là bắt buộc.';
    const INVALID_EMAIL = 'Email không hợp lệ.';
    const PASSWORD_TOO_SHORT = 'Mật khẩu phải có ít nhất 8 ký tự.';

    // User validation messages
    const USER_NAME_REQUIRED = 'Tên là trường bắt buộc.';
    const USER_NAME_STRING = 'Tên phải là chuỗi ký tự.';
    const USER_NAME_MAX = 'Tên không được vượt quá 255 ký tự.';
    const USER_EMAIL_REQUIRED = 'Email là trường bắt buộc.';
    const USER_EMAIL_FORMAT = 'Định dạng email không hợp lệ.';
    const USER_EMAIL_MAX = 'Email không được vượt quá 255 ký tự.';
    const USER_EMAIL_UNIQUE = 'Email đã tồn tại trong tenant này.';
    const USER_PASSWORD_REQUIRED = 'Mật khẩu là trường bắt buộc.';
    const USER_PASSWORD_STRING = 'Mật khẩu phải là chuỗi ký tự.';
    const USER_PASSWORD_MIN = 'Mật khẩu phải có ít nhất 6 ký tự.';
    const USER_PASSWORD_MIN_8 = 'Mật khẩu phải chứa ít nhất 8 ký tự.';
    const USER_BRANCH_INVALID = 'Chi nhánh được chọn không hợp lệ hoặc không thuộc quyền sở hữu của bạn.';
    const USER_NAME_INPUT = 'Vui lòng nhập tên nhân viên.';
    const USER_EMAIL_INPUT = 'Vui lòng nhập địa chỉ email.';
    const USER_EMAIL_INVALID_FORMAT = 'Địa chỉ email không đúng định dạng.';
    const USER_EMAIL_USED = 'Địa chỉ email này đã được sử dụng trong hệ thống của bạn.';
    const USER_PASSWORD_INPUT = 'Vui lòng nhập mật khẩu tài khoản.';

    // Authorization errors
    const UNAUTHORIZED = 'Bạn chưa được xác thực.';
    const PERMISSION_DENIED = 'Bạn không có quyền truy cập.';

    // Generic errors
    const INTERNAL_ERROR = 'Đã xảy ra lỗi.';
}
