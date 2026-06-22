<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CustomerGroup extends Model
{
    /*
    Khách lẻ
    Khách VIP
    Khách Đại lý
    Khách Sỉ
    Khách Thân Thiết
    => Sau này có thể dùng cho:
    ✓ Bảng giá riêng
    ✓ Chiết khấu riêng
    ✓ Tích điểm riêng
    ✓ Khuyến mãi riêng
    */
    protected $fillable = [
        'tenant_id',
        'name',
        'description',
        'status',
        'discount_percent',
    ];

    protected $casts = [
        'discount_percent' => 'decimal:2',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(
            Tenant::class
        );
    }

    public function customers(): HasMany
    {
        return $this->hasMany(
            Customer::class
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeActive($query)
    {
        return $query->where(
            'status',
            true
        );
    }
}
