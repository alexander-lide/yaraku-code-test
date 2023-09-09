<?php

namespace App\View\Components;

use App\Collections\LibraryCollection;
use App\Models\Author;
use App\Models\Book;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class BookForm extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $type,
        public Book $book,
        public Author $author,
        public LibraryCollection $authors
    )
    {
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        // Get options for author select
        $data['options'] = $this->authors->getAsOptionsArray('id','name');
        return view('components.book-form', $data);
    }
}
