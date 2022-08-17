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
        'bet_purchase_id',
        'bet',
        'result_final',
        'type_bet',
        'id_matche',
        'win',
    ];
}
