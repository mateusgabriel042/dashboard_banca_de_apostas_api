<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;
use App\Models\Country;
use App\Models\Matche;

class League extends Model
{
    use HasFactory, Uuid;

    protected $table = 'leagues';
    
    public $incrementing = false;

    protected $keyType = 'uuid';

    protected $fillable = [
        'id',
        'sport_name',
        'sport_label',
        'apievents_sport_id',
        'apievents_league_id',
        'name',
        'label_name',
        'type',
        'label_type',
        'logo',
        'is_active',
        'sport_id',
        'country_id',
    ];

    public function scopeInfo ($query) {
        return $query->with('matches', 'country');
    }

    public function country() {
        return $this->hasOne(Country::class, 'id', 'country_id');
    }

    public function matches(){
        return $this->hasMany(Matche::class, 'league_id')->orderBy('date_matche', 'ASC');
    }
}
