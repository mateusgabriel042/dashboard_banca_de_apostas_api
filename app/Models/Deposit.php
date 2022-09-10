<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Carbon\Carbon;

class Deposit extends Model
{
    use HasFactory, Uuid;

    protected $table = 'deposits';
    
    public $incrementing = false;

    protected $keyType = 'uuid';

    protected $fillable = [
        'id',
        'transaction_amount',
        'type_payment',
        'colletion_id',
        'payer_email',
        'currency_id',
        'identification_type',
        'identification_number',
        'external_reference',
        'qr_code',
        'qr_code_base64',
        'status',
        'transaction_id',
        'bank_transfer_id',
        'user_id',
        'date_deposit',
    ];

    public function user() {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    protected function getDateDepositAttribute($value){
        return Carbon::createFromFormat('Y-m-d H:i:s', $value)->format('d/m/Y H:i:s');
    }

}
