<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookItem extends Model
{
    use HasFactory;

    const STATUS_AVAIABLE = 1;
    const STATUS_UNAVAIABLE = 2;
    const STATUS_LOST = 3;
    const STATUS_DAMAGED = 4;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'book_id',
        'book_code',
        'isbn_code',
        'status',
        'price',
        'publiser_id',
        'location',
        'preview_url',
        'barcode',
        'edition',
        'book_language_id',
    ];


    public function book()
    {
        return $this->hasOne(Book::class, 'isbn_code', 'isbn_code');
    }

    public function language()
    {
        return $this->hasOne(BookLanguage::class, 'id', 'book_language_id');
    }
}
