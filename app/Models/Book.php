<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;
    const STATUS_RETURNED = 1;
    const STATUS_BORROWING = 2;
    const STATUS_LOST = 3;
    const STATUS_LATE = 4;
    const STATUS_AVAILABLE = 5;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'isbn_code',
        'author_id',
        'year',
        'description',
        'image',
        'genres_id',
    ];

    public function author()
    {
        return $this->belongsTo(Author::class, 'author_id');
    }

    public function book_item()
    {
        return $this->hasMany(BookItem::class, 'book_id');
    }

    public function borrow_history()
    {
        return $this->hasMany(BorrowHistory::class, 'isbn_code', 'isbn_code');
    }
}
