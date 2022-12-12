<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Bet;
use Carbon\Carbon;

class BetPurchase extends Model
{
    use HasFactory, Uuid;

    protected $table = 'bets_purchase';
    
    public $incrementing = false;

    protected $keyType = 'uuid';

    protected $fillable = [
        'id',
        'user_id',
        'invested_money',
        'return_money',
        'is_active',
        'win',
        'date_purchase',
    ];

    protected function getDatePurchaseAttribute($value){
        return Carbon::createFromFormat('Y-m-d H:i:s', $value)->format('d/m/Y H:i:s');
    }

    public function user() {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function bets() {
        return $this->hasMany(Bet::class, 'bet_purchase_id');
    }


}
