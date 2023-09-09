<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAuthorRequest;
use App\Http\Requests\UpdateAuthorRequest;
use App\Models\Author;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class AuthorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request):View
    {
        $data['sort'] = $request->sort ?? 'id'; 
        $data['order'] = $request->order ?? 'asc';
        $data['authors'] = Author::all()->sortCollection($data['sort'], $data['order']);

        return view('authors/index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create():View
    {
        return view('authors/create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAuthorRequest $request):RedirectResponse
    {
        $name = $request->string('name');

        Author::create([
            'name' => $name,
            ]
        );

        Session::flash('success', 'Successfully added author!');

        return redirect('/authors');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Author $author):View
    {
        $data['author'] = $author;
        return view('authors/edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAuthorRequest $request, Author $author):RedirectResponse
    {
        $author->name = $request->string('name');

        $author->save();

        Session::flash('success', 'Successfully updated author ' . $author->name . '!');

        return redirect('/authors');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Author $author):RedirectResponse
    {
        // If author is set to one or more books we do not delete
        if (!$author->books->isEmpty())
        {
            Session::flash('error', 'Please remove author ' . $author->name . ' from any books before deleting!');
        }
        else
        {
            $author->delete();
    
            Session::flash('success', 'Successfully deleted author ' . $author->name . '!');
        }

        return redirect('/authors');
    }
}
