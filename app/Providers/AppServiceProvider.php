<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\BaseRepositoryInterface;
use App\Repositories\BaseRepository;
use App\Repositories\UserRepositoryInterface;
use App\Repositories\UserRepository;
use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Gate;
use App\Models\Tenant;
use App\Observers\TenantObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Đăng ký BaseRepository
        $this->app->bind(BaseRepositoryInterface::class, BaseRepository::class);

        // Đăng ký UserRepository
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // --- CẤU HÌNH THỜI GIAN HẾT HẠN CỦA PASSPORT ---
        $time = now()->addMinutes(config('constant.token_expired'));

        Passport::tokensExpireIn($time);
        Passport::refreshTokensExpireIn($time);
        Passport::personalAccessTokensExpireIn($time);
        Tenant::observe(TenantObserver::class);


        // --- CẤU HÌNH CỔNG PHÂN QUYỀN (GATE) ---
        // Kiểm tra xem user có cùng công ty/tổ chức với thực thể dữ liệu hay không
        Gate::define('modify', function ($user, $entity) {
            return $user->tenant_id == $entity->tenant_id;
        });
    }
}
