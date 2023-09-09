<?php

namespace App\Http\Controllers;

use App\Http\Requests\DownloadListRequest;
use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Models\Author;
use App\Models\Book;
use DOMDocument;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request):View
    {
        $data['sort'] = $request->sort ?? 'id'; 
        $data['order'] = $request->order ?? 'asc';
        $data['title'] = $request->title;
        $data['author'] = $request->author;

        $book = new Book();
        $books = $book->allBooksWithAuthors();

        $books = $books->sortCollection($data['sort'], $data['order']);

        $url_query = '';

        // Filter our Collection of Books by title and/or author
        if ($data['title'] !== null)
        {
            $books = $books->filterBy('title', $data['title']);
            $url_query .= '&title='.$data['title'];
        }

        if ($data['author'] !== null)
        {
            $books = $books->filterBy('author', $data['author']);
            $url_query .= '&author='.$data['author'];
        }

        $data['books'] = $books;

        // Search query to add to our sorting links
        $data['url_query'] = $url_query;

        return view('books/index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create():View
    {
        $data['authors'] = Author::all();
        return view('books/create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBookRequest $request):RedirectResponse
    {
        $title = $request->string('title');
        $author_id = $request->integer('author_id');

        Book::create([
            'title' => $title,
            'author_id' => $author_id,
            ]
        );

        Session::flash('success', 'Successfully added book!');

        return redirect('/books');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Book $book):View
    {
        $data['book'] = $book;
        $data['author'] = $book->author;
        $data['authors'] = Author::all();
        return view('books/edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBookRequest $request, Book $book):RedirectResponse
    {
        $book->title = $request->string('title');
        $book->author_id = $request->integer('author_id');

        $book->save();

        Session::flash('success', 'Successfully updated book ' . $book->title . '!');

        return redirect('/books');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book):RedirectResponse
    {
        $book->delete();

        Session::flash('success', 'Successfully deleted book ' . $book->title . '!');

        return redirect('/books');
    }
}
