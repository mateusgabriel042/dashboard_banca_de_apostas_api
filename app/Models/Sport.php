<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;

class Sport extends Model
{
    use HasFactory, Uuid;

    protected $table = 'sports';
    
    public $incrementing = false;

    protected $keyType = 'uuid';

    protected $fillable = [
        'id',
		'apievents_id',
		'name',
		'label',
		'is_active',
    ];
}
