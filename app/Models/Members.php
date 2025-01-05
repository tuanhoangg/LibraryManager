<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Members extends Model
{
    use HasFactory;

    const VALID = 1;
    const EXPIRED = 0;

    /**
     * The attributes that should be appended to model's array form.
     *
     * @var array
     */
    protected $appends = [
        'books_can_borrow',
    ];
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'membership_plan_id',
        'status',
        'due_date',
        'member_code',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function membership_plan()
    {
        return $this->belongsTo(MembershipPlan::class, 'membership_plan_id', 'id');
    }

    public function borrowedHistory()
    {
        return $this->hasManyThrough(BorrowHistory::class, User::class, 'member_id', 'user_id', 'id', 'id');
    }

    public function getBooksCanBorrowAttribute()
    {
        $maxBooks = $this->membership_plan->limit_book;
        $currentBorrowedBooks = BorrowHistory::query()
            ->where('user_id', Auth::user()->id)
            ->where(function ($q) {
                $q->where('status', BorrowHistory::STATUS_BORROWED)
                    ->orWhere('status', BorrowHistory::STATUS_PENDING);
            })
            ->count();
        // dd($currentBorrowedBooks, Auth::user()->id);
        return $maxBooks - $currentBorrowedBooks;
    }
}
