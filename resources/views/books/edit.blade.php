@extends('layouts.master')
 
@section('title', 'Edit Book')
 
@section('content')
    <x-book-form type="edit" :$book :$author :$authors />
@stop