<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LateReturn extends Model
{
    use HasFactory, HasUuids;
    public const STATUS_PENDING = 0;
    public const STATUS_SUCCESS = 1;


    public const STATUS_LIST = [
        LateReturn::STATUS_PENDING => [
            'label' => 'Chưa nộp phạt',
            'class' => 'badge badge-danger' // màu vàng
        ],
        LateReturn::STATUS_SUCCESS => [
            'label' => 'Đã nộp',
            'class' => 'badge badge-info' // màu xanh da trời
        ],
    ];
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'borrow_id',
        'user_id',
        'late_fee',
        'late_days',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function borrow()
    {
        return $this->belongsTo(BorrowHistory::class, 'borrow_id', 'id');
    }
}
