<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Matche;

class ResultSoccer extends Model
{
    use HasFactory, Uuid;

    protected $table = 'results_soccer';
    
    public $incrementing = false;

    protected $keyType = 'uuid';

    protected $fillable = [
        'id',
        'score',
        'time_status',
        'home_goals_first_time',
        'home_goals_second_time',
        'away_goals_first_time',
        'away_goals_second_time',
        'home_attacks',
        'away_attacks',
        'home_ball_safe',
        'away_ball_safe',
        'home_corners',
        'away_corners',
        'home_corner_h',
        'away_corner_h',
        'home_dangerous_attacks',
        'away_dangerous_attacks',
        'home_fouls',
        'away_fouls',
        'home_freekicks',
        'away_freekicks',
        'home_goalattempts',
        'away_goalattempts',
        'home_goalkicks',
        'away_goalkicks',
        'home_goals',
        'away_goals',
        'home_injuries',
        'away_injuries',
        'home_offsides',
        'away_offsides',
        'home_off_target',
        'away_off_target',
        'home_on_target',
        'away_on_target',
        'home_penalties',
        'away_penalties',
        'home_possession_rt',
        'away_possession_rt',
        'home_redcards',
        'away_redcards',
        'home_saves',
        'away_saves',
        'home_shots_blocked',
        'away_shots_blocked',
        'home_substitutions',
        'away_substitutions',
        'home_throwins',
        'away_throwins',
        'home_yellowcards',
        'away_yellowcards',
        'home_yellowred_cards',
        'away_yellowred_cards',
        'matche_id',
    ];

    public function matche() {
        return $this->hasOne(Matche::class, 'id', 'matche_id');
    }
}
