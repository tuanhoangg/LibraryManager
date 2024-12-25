<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BorrowHistory extends Model
{
    use HasFactory;
    public const STATUS_RESERVE = 0;

    public const STATUS_UNRESERVE = 'unresrve';
    public const STATUS_PENDING = 'pending';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';
    public const STATUS_BORROWED = 'borrowed';
    public const STATUS_RETURNED = 'returned';
    public const STATUS_OVERDUE = 'overdue';
    public const STATUS_LOST = 'lost';
    public const STATUS_DAMAGED = 'damaged';

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
    public const OPTION_STATUS = [
        BorrowHistory::STATUS_PENDING => [
            BorrowHistory::STATUS_PENDING => "Chờ duyệt",
            BorrowHistory::STATUS_APPROVED => "Phê duyệt",
            BorrowHistory::STATUS_REJECTED => "Từ chối",
            BorrowHistory::STATUS_UNRESERVE => "Hủy mượn"
        ],
        BorrowHistory::STATUS_APPROVED => [
            BorrowHistory::STATUS_APPROVED => "Phê duyệt",
            BorrowHistory::STATUS_BORROWED => "Đang mượn",
        ],
        BorrowHistory::STATUS_OVERDUE => [
            BorrowHistory::STATUS_OVERDUE => "Quá hạn",
            BorrowHistory::STATUS_RETURNED => "Đã trả",
            BorrowHistory::STATUS_LOST => "Mất",
            BorrowHistory::STATUS_DAMAGED => "Bị hỏng",
        ],
        BorrowHistory::STATUS_BORROWED => [
            BorrowHistory::STATUS_BORROWED => "Đang mượn",
            BorrowHistory::STATUS_RETURNED => "Đã trả",
            BorrowHistory::STATUS_LOST => "Mất",
            // BorrowHistory::STATUS_OVERDUE => "Quá hạn",
            BorrowHistory::STATUS_DAMAGED => "Bị hỏng",
        ]
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'book_item_id',
        'book_code',
        'isbn_code',
        'user_id',
        'status',
        'borrow_date',
        'due_date',
        'returned_at',
    ];

    public function book()
    {
        return $this->belongsTo(Book::class, 'isbn_code', 'isbn_code');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
