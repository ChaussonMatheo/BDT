<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class image extends Model
{
    protected $fillable = ['upload_id', 'path'];
    public function upload() {
        return $this->belongsTo(Upload::class);
    }
}
