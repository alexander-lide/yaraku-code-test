<?php

namespace App\View\Components;

use App\Models\Author;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class AuthorForm extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $type,
        public Author $author,
    )
    {
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.author-form');
    }
}
