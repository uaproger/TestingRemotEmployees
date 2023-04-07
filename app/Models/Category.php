<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    public function essence() {
        return $this->belongsTo(Essence::class, 'essence_id', 'id');
    }
}
