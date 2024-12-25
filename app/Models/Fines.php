<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fines extends Model
{
    use HasFactory, HasUuids;
    public const STATUS_PENDING = 0;
    public const STATUS_SUCCESS = 1;
    public const FINE_FEE = 50000;
    public const FILTER_STATUS = [
        LateReturn::STATUS_PENDING => [
            'label' => 'Chưa nộp phạt',
            'class' => 'badge badge-danger' // màu vàng
        ],
        LateReturn::STATUS_SUCCESS => [
            'label' => 'Đã nộp',
            'class' => 'badge badge-info' // màu xanh da trời
        ],
    ];
    public const STATUS_LIST = [
        BorrowHistory::STATUS_PENDING => [
            'label' => 'Chờ duyệt',
            'class' => 'badge badge-warning' // màu vàng
        ],
        BorrowHistory::STATUS_APPROVED => [
            'label' => 'Phê duyệt',
            'class' => 'badge badge-info' // màu xanh da trời
        ],
        BorrowHistory::STATUS_UNRESERVE => [
            'label' => 'Hủy mượn',
            'class' => 'badge badge-danger' // màu đỏ
        ],
        BorrowHistory::STATUS_REJECTED => [
            'label' => 'Từ chối',
            'class' => 'badge badge-danger' // màu đỏ
        ],
        BorrowHistory::STATUS_BORROWED => [
            'label' => 'Đang mượn',
            'class' => 'badge badge-info' // màu xanh da trời
        ],
        BorrowHistory::STATUS_RETURNED => [
            'label' => 'Đã trả',
            'class' => 'badge badge-success' // màu xanh lá cây
        ],
        BorrowHistory::STATUS_OVERDUE => [
            'label' => 'Quá hạn',
            'class' => 'badge badge-danger' // màu đỏ
        ],
        BorrowHistory::STATUS_LOST => [
            'label' => 'Mất',
            'class' => 'badge badge-danger' // màu đỏ
        ],
        BorrowHistory::STATUS_DAMAGED => [
            'label' => 'Bị hỏng',
            'class' => 'badge badge-danger' // màu đỏ
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
        'fine_type',
        'amount',
        'reference_id',
        'status',
    ];


    public function late_return()
    {
        return $this->belongsTo(LateReturn::class, 'reference_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function borrow()
    {
        return $this->belongsTo(BorrowHistory::class, 'borrow_id', 'id');
    }
}
