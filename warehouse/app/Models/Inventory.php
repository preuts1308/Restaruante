<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $fillable = [
        'ingredient_id',
        'quantity',
        // ... otros campos que quieras permitir
    ];
}
