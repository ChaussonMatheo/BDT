<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use Carbon\Carbon;

class RendezVous extends Model
{
    use HasFactory;
    protected $table = 'rendez_vous';
    protected $fillable = [
        'prestation_id',
        'date_heure',
        'statut',
        'garage_id',
        'user_id',
        'guest_name',
        'guest_email',
        'guest_phone',
        'tarif',
        'type_de_voiture',
        'prestation_libre',
    ];

    protected $dates = ['date_heure'];

    public function prestation(): BelongsTo
    {
        return $this->belongsTo(Prestation::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }



    protected static function boot()
    {
        parent::boot();

        static::creating(function ($rendezVous) {
            $rendezVous->token = Str::random(32);
        });
    }
}
