<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    protected $fillable = [
        'language'
    ];

    public function books()
    {
        return $this->belongsToMany(Book::class, 'book_file_languages');
    }
}
