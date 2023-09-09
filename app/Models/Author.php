<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Author extends Base
{
    use HasFactory;
    protected $table = 'authors';
    protected $fillable = ['name'];

    /**
     * Return authors books.
     */
    public function books():HasMany
    {
        return $this->hasMany(Book::class);
    }
}