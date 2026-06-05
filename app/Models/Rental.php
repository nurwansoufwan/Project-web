<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rental extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_code',
        'customer_id',
        'rental_date',
        'return_date',
        'actual_return_date',
        'total_price',
        'fine_amount',
        'status',
        'payment_status',
    ];

    protected $casts = [
        'rental_date' => 'date',
        'return_date' => 'date',
        'actual_return_date' => 'date',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function details()
    {
        return $this->hasMany(RentalDetail::class);
    }
}
