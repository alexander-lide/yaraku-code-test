@extends('layouts.master')
 
@section('title', 'Authors')
 
@section('content')
    <a href="/authors/create" class="btn btn-success mb-3" role="button">Add Author</a>
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <td>
                    Id
                    <x-sorting-arrow url="/authors/?sort=id&order=asc" order="asc" active="{{ $sort === 'id' && $order == 'asc' ? 'true' : 'false' }}"/>
                    <x-sorting-arrow url="/authors/?sort=id&order=desc" order="desc" active="{{ $sort === 'id' && $order == 'desc' ? 'true' : 'false' }}"/>
                </td>
                <td>
                    Name
                    <x-sorting-arrow url="/authors/?sort=name&order=asc" order="asc" active="{{ $sort === 'name' && $order == 'asc' ? 'true' : 'false' }}"/>
                    <x-sorting-arrow url="/authors/?sort=name&order=desc" order="desc" active="{{ $sort === 'name' && $order == 'desc' ? 'true' : 'false' }}"/>
                </td>
                <td width="150px"></td>
            </tr>
        </thead>
        <tbody>
        @foreach($authors as $author)
            <tr>
                <td>{{ $author->id }}</td>
                <td>{{ $author->name }}</td>
                <td>
                    <a class="btn btn-small btn-info float-start d-inline" href="/authors/{{ $author->id }}/edit">Edit</a>
                    <form method="POST" action="/authors/{{$author->id}}" class="float-end d-inline">
                        @csrf
                        @method('delete')
                
                        <div class="form-group">
                            <input type="submit" class="btn btn-danger delete-author" value="Delete">
                        </div>
                    </form>
                </td>
            </tr>
        @endforeach
        @if ($authors->isEmpty())
            <tr>
                <td colspan="4">No authors found</td>
            </tr>
        @endif
        </tbody>
    </table>
    @section('scripts')
        <script>
            $('.delete-author').click(function(e){
                e.preventDefault() // Don't post the form, unless confirmed
                if (confirm('Are you sure you want to delete the author?')) {
                    // Post the form
                    $(e.target).closest('form').submit() // Post the surrounding form
                }
            });
        </script>
    @stop
@stop