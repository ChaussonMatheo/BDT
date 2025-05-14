<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class upload extends Model
{
    protected $fillable = ['uuid'];
    public function images() {
        return $this->hasMany(Image::class);
    }
}
