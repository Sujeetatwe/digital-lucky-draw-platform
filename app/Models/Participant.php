<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Participant extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'full_name',
        'age',
        'mobile_number',
        'voter_id',
        'voter_member_count',
        'epic_voter_id_no',
        'adharcard_no',
        'permanent_address',
        'token',
        'status'
    ];

    protected $casts = [
        'voter_id' => 'boolean',
    ];

    public function winner()
    {
        return $this->hasOne(Winner::class);
    }
}
