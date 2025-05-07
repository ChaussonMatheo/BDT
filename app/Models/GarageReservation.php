<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GarageReservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'garage_id',
        'start_date',
        'end_date',
        'notes',
        'couleur'
    ];

    protected $dates = ['start_date', 'end_date'];

    public function garage(): BelongsTo
    {
        return $this->belongsTo(Garage::class);
    }

    public function prestations(): HasMany
    {
        return $this->hasMany(GarageReservationPrestation::class);
    }
}
