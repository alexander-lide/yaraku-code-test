<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;

class Book extends Base
{
    use HasFactory;
    protected $table = 'books';
    protected $fillable = ['title', 'author_id'];

    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class, 'author_id');
    }

    public function allBooksWithAuthors():Collection
    {
        return $this->join('authors', 'authors.id', '=', 'books.author_id')
            ->get(['books.*', 'authors.name as author']);
    }
}
