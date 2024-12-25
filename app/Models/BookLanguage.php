<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookLanguage extends Model
{
    use HasFactory;

    protected $fillable = [
        'language_code',
        'language_name'
    ];

    public function book_item()
    {
        return $this->hasOne(BookItem::class, 'book_language_id');
    }
}
