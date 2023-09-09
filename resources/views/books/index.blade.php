@extends('layouts.master')
 
@section('title', 'Books')
 
@section('content')
    <a href="/books/create" class="btn btn-success mb-3" role="button">Add Book</a>
    <div class="card card-body bg-light mb-3">
        <p><strong>Search books</strong></p>
        <form method="GET" action="">
            @csrf
            <x-form-input type="hidden" name="sort" value="{{ $sort }}"/>
            <x-form-input type="hidden" name="order" value="{{ $order }}"/>
            <x-form-input type="text" name="title" value="{{ $title }}" label="Title"/>
            <x-form-input type="text" name="author" value="{{ $author }}" label="Author"/>
            <input class="btn btn-primary" type="submit" value="Search">
        </form>
    </div>
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <td>
                    Id
                    <x-sorting-arrow url="/books/?sort=id&order=asc{{ $url_query }}" order="asc" active="{{ $sort === 'id' && $order == 'asc' ? 'true' : 'false' }}"/>
                    <x-sorting-arrow url="/books/?sort=id&order=desc{{ $url_query }}" order="desc" active="{{ $sort === 'id' && $order == 'desc' ? 'true' : 'false' }}"/>
                </td>
                <td>
                    Title
                    <x-sorting-arrow url="/books/?sort=title&order=asc{{ $url_query }}" order="asc" active="{{ $sort === 'title' && $order == 'asc' ? 'true' : 'false' }}"/>
                    <x-sorting-arrow url="/books/?sort=title&order=desc{{ $url_query }}" order="desc" active="{{ $sort === 'title' && $order == 'desc' ? 'true' : 'false' }}"/>
                </td>
                <td>
                    Author
                    <x-sorting-arrow url="/books/?sort=author&order=asc{{ $url_query }}" order="asc" active="{{ $sort === 'author' && $order == 'asc' ? 'true' : 'false' }}"/>
                    <x-sorting-arrow url="/books/?sort=author&order=desc{{ $url_query }}" order="desc" active="{{ $sort === 'author' && $order == 'desc' ? 'true' : 'false' }}"/>
                </td>
                <td width="150px"></td>
            </tr>
        </thead>
        <tbody>
        @foreach($books as $book)
            <tr>
                <td>{{ $book->id }}</td>
                <td>{{ $book->title }}</td>
                <td>{{ $book->author }}</td>
                <td>
                    <a class="btn btn-small btn-info float-start d-inline" href="/books/{{ $book->id }}/edit">Edit</a>
                    <form method="POST" action="/books/{{$book->id}}" class="float-end d-inline">
                        @csrf
                        @method('delete')
                
                        <div class="form-group">
                            <input type="submit" class="btn btn-danger delete-book" value="Delete">
                        </div>
                    </form>
                </td>
            </tr>
        @endforeach
        @if ($books->isEmpty())
            <tr>
                <td colspan="4">No books found</td>
            </tr>
        @endif
        </tbody>
    </table>
    @section('scripts')
        <script>
            $('.delete-book').click(function(e){
                e.preventDefault() // Don't post the form, unless confirmed
                if (confirm('Are you sure you want to delete the book?')) {
                    // Post the form
                    $(e.target).closest('form').submit() // Post the surrounding form
                }
            });
        </script>
    @stop
@stop