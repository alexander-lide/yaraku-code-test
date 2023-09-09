<?php

namespace App\Models;

use App\Collections\LibraryCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

class Base extends Model
{
    use HasFactory;

    public function newCollection(array $models = []): Collection
    {
        return new LibraryCollection($models);
    }
}
