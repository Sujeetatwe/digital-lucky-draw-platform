<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Winner extends Model
{
    protected $fillable = ['participant_id', 'winning_gift_id', 'quantity', 'assigned_by'];

    public function participant()
    {
        return $this->belongsTo(Participant::class);
    }

    public function gift()
    {
        return $this->belongsTo(WinningGift::class, 'winning_gift_id');
    }

    public function assigner()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }
}
