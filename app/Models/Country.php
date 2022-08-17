<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;
use App\Models\League;

class Country extends Model
{
    use HasFactory, Uuid;

    protected $table = 'countries';
    
    public $incrementing = false;

    protected $keyType = 'uuid';

    protected $fillable = [
        'id',
        'name',
        'sigle',
        'label',
        'is_active',
    ];

    public function leagues() {
        return $this->hasMany(League::class, 'country_id');
    }
}
