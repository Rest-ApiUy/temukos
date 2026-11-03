<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderInvoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'invoice_code',
        'title',
        'note',
        'amount',
        'approved',
        'paid',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'integer',
            'approved' => 'boolean',
            'paid' => 'boolean',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public static function generateCode(): string
    {
        do {
            $code = 'ADD-' . strtoupper(substr(uniqid(), -5));
        } while (static::where('invoice_code', $code)->exists());

        return $code;
    }
}
