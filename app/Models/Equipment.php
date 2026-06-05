<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
    use HasFactory;

    protected $table = 'equipment';

    protected $fillable = [
        'name',
        'category',
        'rental_price_per_day',
        'stock',
        'description',
        'image_path',
    ];

    public function rentalDetails()
    {
        return $this->hasMany(RentalDetail::class, 'equipment_id');
    }
}
