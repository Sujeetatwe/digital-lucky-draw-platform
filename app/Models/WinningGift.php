<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WinningGift extends Model
{
    protected $fillable = ['gift_name', 'quantity'];

    public function winners()
    {
        return $this->hasMany(Winner::class);
    }
}
