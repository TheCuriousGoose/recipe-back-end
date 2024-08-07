<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'notation',
    ];

    public function ingredients()
    {
        return $this->hasMany(Ingredient::class);
    }
}
