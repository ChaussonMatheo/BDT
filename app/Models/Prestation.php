<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prestation extends Model
{
    use HasFactory;

    protected $fillable = [
        'service',
        'description',
        'tarif_petite_voiture',
        'tarif_berline',
        'tarif_suv_4x4',
        'duree_estimee'
    ];
}
