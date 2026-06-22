<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Customer extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'customer_group_id',
        'code',
        'name',
        'phone',
        'email',
        'birthday',
        'gender',
        'address',
        'debt_balance',
        'total_sales',
        'loyalty_points',
        'note',
        'status',
    ];

    protected $casts = [
        'birthday'      => 'date',
        'total_sales'   => 'decimal:2',
        'debt_balance'  => 'decimal:2',
        'loyalty_points' => 'integer',
        'status'        => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function customerGroup(): BelongsTo
    {
        return $this->belongsTo(CustomerGroup::class);
    }

    public function pointLogs(): HasMany
    {
        return $this->hasMany(CustomerPointLog::class);
    }

    public function couponUsages(): HasMany
    {
        return $this->hasMany(CouponUsage::class);
    }

    public function debtTransactions(): MorphMany
    {
        return $this->morphMany(
            DebtTransaction::class,
            'debteable'
        );
    }

    public function salesOrders(): HasMany
    {
        return $this->hasMany(SalesOrder::class);
    }

    public function salesReturns(): HasMany
    {
        return $this->hasMany(SalesReturn::class);
    }

    public function payments(): MorphMany
    {
        return $this->morphMany(
            Payment::class,
            'paymentable'
        );
    }

    public function attachments(): MorphMany
    {
        return $this->morphMany(
            Attachment::class,
            'attachable'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function scopeVip($query)
    {
        return $query->where('points', '>=', 1000);
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    public function getDisplayNameAttribute(): string
    {
        return "{$this->code} - {$this->name}";
    }
}
