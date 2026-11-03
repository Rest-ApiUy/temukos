<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_code',
        'user_id',
        'city',
        'budget',
        'movein_date',
        'near',
        'distance',
        'features',
        'gender',
        'priority',
        'notes',
        'service_extras',
        'pickup',
        'pickup_point',
        'package_name',
        'price',
        'payment_status',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'features' => 'array',
            'service_extras' => 'array',
            'pickup' => 'boolean',
            'price' => 'integer',
            'movein_date' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(OrderMessage::class)->oldest();
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(OrderInvoice::class)->latest();
    }

    public static function generateCode(): string
    {
        do {
            $code = 'TK-' . strtoupper(substr(uniqid(), -5));
        } while (static::where('order_code', $code)->exists());

        return $code;
    }
}
