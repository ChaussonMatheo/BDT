<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GarageReservationPrestation extends Model
{
    use HasFactory;

    protected $fillable = [
        'garage_reservation_id',
        'description',
        'montant',
    ];

    public function reservation(): BelongsTo
    {
        return $this->belongsTo(GarageReservation::class, 'garage_reservation_id');
    }
}
