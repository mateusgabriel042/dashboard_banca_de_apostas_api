<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;
use App\Models\Country;

class League extends Model
{
    use HasFactory, Uuid;

    protected $table = 'leagues';
    
    public $incrementing = false;

    protected $keyType = 'uuid';

    protected $fillable = [
        'id',
        'name',
        'label',
        'is_active',
        'league_id',
        'country_id',
    ];

    public function country() {
        return $this->hasOne(Country::class, 'id', 'country_id');
    }
}
