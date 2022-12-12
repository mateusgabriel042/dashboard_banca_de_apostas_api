<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
use App\Models\League;

class Matche extends Model
{
    use HasFactory, Uuid;

    protected $table = 'matches';
    
    public $incrementing = false;

    protected $keyType = 'uuid';

    protected $fillable = [
        'id',
		'bet365_matche_id',
        'apievents_sport_id',
        'apievents_league_id',
        'team_home_id',
        'team_away_id',
        'team_home_name',
        'team_away_name',
        'team_home_image_id',
        'team_away_image_id',
        'date_matche',
        'object_odds_prematche',
        'sport_name',
        'sport_label',
        'league_id',
        'country_id',
    ];

    public function league() {
        return $this->hasOne(League::class, 'id', 'league_id');
    }
}
