<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class BetPurchase extends Model
{
    use HasFactory, Uuid;

    protected $table = 'bets_purchase';
    
    public $incrementing = false;

    protected $keyType = 'uuid';

    protected $fillable = [
        'id',
        'value_bet',
        'date_purchase',
        'user_id',
    ];

    public function user() {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
