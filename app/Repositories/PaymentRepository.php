<?php

namespace App\Repositories;

use App\Models\Payment;

class PaymentRepository extends BaseRepository
{
    function getModel()
    {
        return Payment::class;
    }
}
