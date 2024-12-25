<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    const MEMBER_FEE = 1;
    const PENALTIES_FEE = 2;
    const FINES_FEE = 3;
    const CASH_METHOD = 1;
    const BANK_METHOD = 2;
    const STATUS_PENDING = 0;
    const STATUS_SUCCESS = 1;
    const STATUS_FAILED = 2;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'reference_type', // Dong hoi phi, dong phi phat
        'type',    // payment method
        'status',
        'invoice_id',
        'amount',
    ];
}
