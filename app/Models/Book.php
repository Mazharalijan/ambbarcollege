<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $guarded = ['id'];

    public function students(){
        return $this->belongsToMany(User::class, 'user_books');
    }


    public function parent()
    {
        return $this->belongsTo(Book::class, 'parent_id');
    }


    public function files()
    {
        return $this->belongsToMany(Language::class, 'book_file_languages')->withPivot(['file AS book_file']);
    }

    public function assigned_students()
    {
        return $this->belongsToMany(User::class, 'user_chapters');
    }
}
