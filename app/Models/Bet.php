<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class Bet extends Model
{
    use HasFactory, Uuid;

    protected $table = 'bet';
    
    public $incrementing = false;

    protected $keyType = 'uuid';

    protected $fillable = [
        'id',
        'type_event',
        'type_bet',
        'subtype_bet',
        'customer_bet',
        'bet_result_finish',
        'apievents_sport_id',
        'apievents_league_id',
        'bet365_matche_id',
        'odd_id',
        'odd',
        'is_active',
        'win',
        'bet_purchase_id',
    ];

    
}
