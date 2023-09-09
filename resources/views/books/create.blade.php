@extends('layouts.master')
 
@section('title', 'Add Book')
 
@section('content')
    @if ($authors->isEmpty())
        <div class="card card-body bg-light mb-3">
            <p>Before you can add books you need to create authors to add to the books.</p>
            <p>You can do that <a href="/authors/create">here</a>.
        </div>
    @else
        <x-book-form type="create" :$authors/>
    @endif

@stop
